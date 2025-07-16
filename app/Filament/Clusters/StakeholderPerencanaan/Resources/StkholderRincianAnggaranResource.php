<?php

namespace App\Filament\Clusters\StakeholderPerencanaan\Resources;

use App\Filament\Clusters\StakeholderPerencanaan;
use App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderRincianAnggaranResource\Pages;
use App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderRincianAnggaranResource\RelationManagers;
use App\Models\StkholderRincianAnggaran;
use App\Models\TahunFiskal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
// use Illuminate\Database\Eloquent\Builder; // <-- Import Builder
use Illuminate\Support\HtmlString; // Tambahkan baris ini
use Filament\Notifications\Notification;
use App\Filament\Traits\HasResourcePermissions;

class StkholderRincianAnggaranResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'stakeholder';
    protected static ?string $model = StkholderRincianAnggaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationLabel = 'Rincian Anggaran';
    protected static ?string $pluralModelLabel = 'Rincian Anggaran';
    protected static ?string $modelLabel = 'Data';

    protected static ?string $cluster = StakeholderPerencanaan::class;
    private static bool $notificationSent = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kegiatan_id')
                    ->label('Dari Kegiatan Program')
                    ->relationship(
                        name: 'kegiatan',
                        titleAttribute: 'kegiatan', // Atribut untuk pencarian
                        modifyQueryUsing: function (Builder $query) {
                            // 1. Dapatkan ID Tahun Fiskal yang aktif.
                            $activeTahunFiskalId = TahunFiskal::where('is_active', true)->value('id');

                            // 2. Jika tidak ada tahun fiskal aktif, jangan tampilkan opsi apa pun.
                            if (!$activeTahunFiskalId) {
                                // Mengembalikan query yang dijamin kosong.
                                return $query->where('id', -1);
                            }

                            // 3. Filter 'kegiatan' (model StkholderPerencanaanProgramAnggaran)
                            //    yang memiliki relasi 'program' dengan tahun_fiskal yang aktif.
                            return $query
                                ->whereHas('program', function (Builder $subQuery) use ($activeTahunFiskalId) {
                                    // Filter di sini diterapkan pada model Program (StkholderPerencanaanPpk)
                                    $subQuery->where('tahun_fiskal', $activeTahunFiskalId);
                                })
                                // Eager load relasi untuk ditampilkan di label dan meningkatkan efisiensi
                                ->with(['regional', 'program']);
                        }
                    )
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        // $record adalah instance dari model StkholderPerencanaanProgramAnggaran
                        $regionalName = $record->regional?->nama_regional ?? 'N/A';
                        $programName = $record->program?->nama ?? 'N/A';
                        
                        return "{$record->kegiatan} ({$regionalName} - {$programName})";
                    })
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('pelaksana_id')
                    ->label('Pelaksana')
                    ->multiple()
                    ->options(function () {
                        return \App\Models\Vendor::pluck('nama', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    // Saat data disimpan, ubah array jadi string
                    ->dehydrateStateUsing(function ($state) {
                        return is_array($state) ? implode(',', $state) : $state;
                    })
                    // Saat diedit, ubah string jadi array
                    ->formatStateUsing(function ($state) {
                        return is_string($state) ? explode(',', $state) : $state;
                    })
                    ->afterStateHydrated(function (Select $component, $state) {
                        if (is_string($state)) {
                            $component->state(explode(',', $state));
                        }
                    }),
                TextInput::make('frekuensi')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                Select::make('frekuensi_unit')
                    ->label('Satuan Frekuensi')
                    ->options([
                        'hari' => 'Hari',
                        'minggu' => 'Minggu',
                        'bulan' => 'Bulan',
                    ])
                    ->default('hari')
                    ->required(),
                TextInput::make('biaya')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->columnSpanFull()
                    ->dehydrateStateUsing(fn($state) => str_replace(['Rp', '.', ' '], '', $state)),
                TextInput::make('kuantitas')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                Select::make('kuantitas_unit')
                    ->label('Satuan Kuantitas')
                    ->options([
                        'unit' => 'Unit',
                        'orang' => 'Orang',
                        'item' => 'Item',
                    ])
                    ->default('unit')
                    ->required(),
                Textarea::make('keterangan')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }


    public static function table(Table $table): Table
    {
        $activeTahunFiskal = TahunFiskal::where('is_active', true)->first();
        $teksFiskal = $activeTahunFiskal ? 'Tahun Fiskal ' . $activeTahunFiskal->nama_tahun_fiskal : 'Tahun Fiskal Belum Diaktifkan';

        return $table
            // --- INI BAGIAN YANG DIUBAH ---
            ->header(
                fn() => new HtmlString('<div class="text-center px-4 py-2 bg-gray-50 dark:bg-gray-800 text-sm font-medium">' . $teksFiskal . '</div>')
            )
            ->modifyQueryUsing(function (Builder $query) {
                $activeTahunFiskalId = TahunFiskal::where('is_active', true)->value('id');

                if (!$activeTahunFiskalId) {
                    if (!self::$notificationSent) {
                        Notification::make()
                            ->title('Tahun fiskal belum diaktifkan')
                            ->body('Silahkan hubungi bagian admin untuk mengaktifkan tahun fiskal.')
                            ->danger()
                            ->persistent()
                            ->send();
                        self::$notificationSent = true;
                    }
                    return $query->whereRaw('1 = 0');
                }

                // Filter Rincian Anggaran berdasarkan tahun fiskal aktif melalui relasi berjenjang:
                // StkholderRincianAnggaran -> kegiatan -> program -> tahun_fiskal
                return $query->whereHas('kegiatan.program', function (Builder $programQuery) use ($activeTahunFiskalId) {
                    $programQuery->where('tahun_fiskal', $activeTahunFiskalId);
                });
            })
            ->columns([
                TextColumn::make('kegiatan.kegiatan')
                    ->label('Kegiatan')
                    ->formatStateUsing(fn($record) => "{$record->kegiatan->kegiatan} ({$record->kegiatan->regional->nama_regional} - {$record->kegiatan->program->nama})")
                    ->sortable()
                    ->searchable(),
                TextColumn::make('pelaksana_names')
                    ->label('Pelaksana')
                    ->limit(50),
                TextColumn::make('frekuensi')
                    ->formatStateUsing(fn($record) => "{$record->frekuensi} " . ucfirst($record->frekuensi_unit))
                    ->sortable(),
                TextColumn::make('biaya')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                TextColumn::make('kuantitas')
                    ->formatStateUsing(fn($record) => "{$record->kuantitas} " . ucfirst($record->kuantitas_unit))
                    ->sortable(),
                TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->getStateUsing(fn($record) => $record->biaya * $record->kuantitas)
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                TextColumn::make('keterangan')
                    ->limit(50)
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStkholderRincianAnggarans::route('/'),
            // 'create' => Pages\CreateStkholderRincianAnggaran::route('/create'),
            // 'edit' => Pages\EditStkholderRincianAnggaran::route('/{record}/edit'),
        ];
    }
}
