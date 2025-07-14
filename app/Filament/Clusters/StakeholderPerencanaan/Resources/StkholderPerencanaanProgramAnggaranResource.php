<?php

namespace App\Filament\Clusters\StakeholderPerencanaan\Resources;

use App\Filament\Clusters\StakeholderPerencanaan;
use App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderPerencanaanProgramAnggaranResource\Pages;
use App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderPerencanaanProgramAnggaranResource\RelationManagers;
use App\Models\StkholderPerencanaanProgramAnggaran;
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

class StkholderPerencanaanProgramAnggaranResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'stakeholder';
    protected static ?string $model = StkholderPerencanaanProgramAnggaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Rencana Kegiatan dan Anggaran';
    protected static ?string $pluralModelLabel = 'Rencana Kegiatan dan Anggaran';
    protected static ?string $modelLabel = 'Data';

    protected static ?string $cluster = StakeholderPerencanaan::class;
    private static bool $notificationSent = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('regional_id')
                    ->label('Regional')
                    ->relationship('regional', 'nama_regional')
                    ->required()
                    ->searchable()
                    ->preload(),
                    
                Select::make('program_id')
                    ->label('Dari Program')
                    ->relationship(
                        name: 'program',
                        titleAttribute: 'nama_program', // Biarkan ini sebagai default
                        modifyQueryUsing: function (Builder $query) {
                            // Logika filter Anda tetap sama
                            $activeTahunFiskalId = TahunFiskal::where('is_active', true)->value('id');
                            if (!$activeTahunFiskalId) {
                                return $query->where('id', -1);
                            }
                            // Penting: Eager load relasi tahunFiskal agar tidak terjadi N+1 problem
                            return $query->where('tahun_fiskal', $activeTahunFiskalId)->with('dariTahunFiskal');
                        }
                    )
                    // --- TAMBAHKAN METHOD INI ---
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        // $record adalah objek model Program untuk setiap pilihan
                        $tahun = $record->dariTahunFiskal?->nama_tahun_fiskal ?? 'N/A';
                        return "{$record->nama} ({$tahun})";
                    })
                    ->required()
                    // ->searchable()
                    ->preload(),

                TextInput::make('kegiatan')
                    ->label('Nama Kegiatan')
                    ->placeholder('Nama Kegiatan Program Baru')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                TextInput::make('anggaran_pengajuan')
                    ->label('Pengajuan Anggaran')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->dehydrateStateUsing(fn($state) => str_replace(['Rp', '.', ' '], '', $state)),
                TextInput::make('anggaran_kesepakatan')
                    ->label('Kesepakatan Anggaran')
                    ->numeric()
                    ->prefix('Rp')
                    ->dehydrateStateUsing(fn($state) => $state ? str_replace(['Rp', '.', ' '], '', $state) : null)
                    ->nullable(),
                Textarea::make('keterangan')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        $activeTahunFiskalId = TahunFiskal::where('is_active', true)->first();
        $teksFiskal = "";

        if (!$activeTahunFiskalId) {
            $teksFiskal = "Tahun Fiskal belum diaktifkan";
        } else {
            $teksFiskal = "Tahun fiskal " . $activeTahunFiskalId->nama_tahun_fiskal;
        }

        return $table
            ->header(
                fn() => new HtmlString('<div class="text-center px-4 bg-gray-50 dark:bg-gray-900">' . $teksFiskal . '</div>')
            )
            ->modifyQueryUsing(function (Builder $query) {
                // 1. Dapatkan ID dari tahun fiskal yang aktif.
                $activeTahunFiskalId = TahunFiskal::where('is_active', true)->value('id');

                // 2. Jika tidak ada tahun fiskal yang aktif, jangan tampilkan data apa pun (best practice).
                if (!$activeTahunFiskalId) {
                    if (!self::$notificationSent) {
                        $cek = TahunFiskal::where('is_active', true)->value('id');
                        if (!$cek) {
                            Notification::make()
                                ->title('Tahun fiskal belum diaktifkan')
                                ->body('Silahkan hubungi bagian admin untuk mengaktifkan tahun fiskal')
                                ->danger()
                                ->persistent()
                                ->send();
                        }
                        self::$notificationSent = true;
                    }
                    // Menggunakan trik query kosong untuk tidak mengembalikan hasil.
                    $query->whereRaw('1 = 0');
                    return; // Hentikan eksekusi lebih lanjut dari fungsi ini.
    
                }

                //relasi di model
                $query->whereHas('program', function (Builder $programQuery) use ($activeTahunFiskalId) {
                    $programQuery->where('tahun_fiskal', $activeTahunFiskalId);
                });
            })
            ->columns([
                TextColumn::make('regional.nama_regional')
                    ->label('Regional')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('program.nama')
                    ->label('Program')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('kegiatan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('anggaran_pengajuan')
                    ->label('Pengajuan Anggaran')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                TextColumn::make('anggaran_kesepakatan')
                    ->label('Kesepakatan Anggaran')
                    ->formatStateUsing(fn($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : '-')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListStkholderPerencanaanProgramAnggarans::route('/'),
            // 'create' => Pages\CreateStkholderPerencanaanProgramAnggaran::route('/create'),
            // 'edit' => Pages\EditStkholderPerencanaanProgramAnggaran::route('/{record}/edit'),
        ];
    }
}
