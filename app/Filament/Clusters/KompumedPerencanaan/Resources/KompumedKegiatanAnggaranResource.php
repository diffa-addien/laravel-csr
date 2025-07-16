<?php

namespace App\Filament\Clusters\KompumedPerencanaan\Resources;

use App\Filament\Clusters\KompumedPerencanaan;
use App\Filament\Clusters\KompumedPerencanaan\Resources\KompumedKegiatanAnggaranResource\Pages;
use App\Filament\Clusters\KompumedPerencanaan\Resources\KompumedKegiatanAnggaranResource\RelationManagers;
use App\Models\KompumedKegiatanAnggaran;
use App\Models\KompumedKegiatan;
use App\Models\TahunFiskal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Traits\HasResourcePermissions;

class KompumedKegiatanAnggaranResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'komunikasi_media';
    protected static ?string $model = KompumedKegiatanAnggaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Data Anggaran';
    protected static ?string $pluralModelLabel = 'Data Anggaran';
    protected static ?string $modelLabel = 'Data';
    protected static ?int $navigationSort = 3;

    protected static ?string $cluster = KompumedPerencanaan::class;
    private static bool $notificationSent = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // --- AWAL BAGIAN YANG DIUBAH ---
                Select::make('kegiatan_id')
                    ->label('Dari Kegiatan')
                    ->relationship(
                        name: 'kegiatan',
                        titleAttribute: 'nama', // Atribut untuk pencarian
                        modifyQueryUsing: function (Builder $query) {
                            $activeTahunFiskalId = TahunFiskal::where('is_active', true)->value('id');

                            if (!$activeTahunFiskalId) {
                                return $query->where('id', -1); // Kembalikan query kosong
                            }

                            // Filter KompumedKegiatan yang programnya termasuk dalam tahun fiskal aktif
                            return $query
                                ->whereHas('program', function (Builder $subQuery) use ($activeTahunFiskalId) {
                                $subQuery->where('tahun_fiskal', $activeTahunFiskalId);
                            })
                                ->with(['regional', 'program']); // Eager load untuk efisiensi
                        }
                    )
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        // $record adalah instance dari model KompumedKegiatan
                        $regionalName = $record->regional?->nama_regional ?? 'N/A';
                        $programName = $record->program?->nama ?? 'N/A';

                        return "{$record->nama} ({$regionalName} - {$programName})";
                    })
                    ->required()
                    ->searchable()
                    ->preload(),
                // --- AKHIR BAGIAN YANG DIUBAH ---
                Textarea::make('deskripsi')
                    ->nullable()
                    ->columnSpanFull(),
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
            ]);
    }

    public static function table(Table $table): Table
    {
        // Siapkan teks untuk header tabel
        $activeTahunFiskal = TahunFiskal::where('is_active', true)->first();
        $teksFiskal = $activeTahunFiskal ? 'Tahun Fiskal ' . $activeTahunFiskal->nama_tahun_fiskal : 'Tahun Fiskal Belum Diaktifkan';

        return $table
            // --- AWAL BAGIAN YANG DIUBAH ---
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

                // Filter Anggaran berdasarkan tahun fiskal aktif melalui relasi:
                // KompumedKegiatanAnggaran -> kegiatan -> program -> tahun_fiskal
                return $query->whereHas('kegiatan.program', function (Builder $programQuery) use ($activeTahunFiskalId) {
                    $programQuery->where('tahun_fiskal', $activeTahunFiskalId);
                });
            })
            // --- AKHIR BAGIAN YANG DIUBAH ---
            ->columns([
                TextColumn::make('kegiatan.nama')
                    ->label('Kegiatan')
                    ->formatStateUsing(fn($record) => "{$record->kegiatan->nama} ({$record->kegiatan->regional->nama_regional} - {$record->kegiatan->program->nama})")
                    ->sortable()
                    ->limit(40)
                    ->searchable(),
                TextColumn::make('deskripsi')
                    ->limit(50)
                    ->searchable(),
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
            'index' => Pages\ListKompumedKegiatanAnggarans::route('/'),
            // 'create' => Pages\CreateKompumedKegiatanAnggaran::route('/create'),
            // 'edit' => Pages\EditKompumedKegiatanAnggaran::route('/{record}/edit'),
        ];
    }
}
