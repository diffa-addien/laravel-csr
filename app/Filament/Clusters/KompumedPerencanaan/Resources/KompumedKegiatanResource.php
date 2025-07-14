<?php

namespace App\Filament\Clusters\KompumedPerencanaan\Resources;

use App\Filament\Clusters\KompumedPerencanaan;
use App\Filament\Clusters\KompumedPerencanaan\Resources\KompumedKegiatanResource\Pages;
use App\Filament\Clusters\KompumedPerencanaan\Resources\KompumedKegiatanResource\RelationManagers;
use App\Models\KompumedKegiatan;
use App\Models\TahunFiskal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;

use App\Filament\Traits\HasResourcePermissions;

class KompumedKegiatanResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'komunikasi_media';
    protected static ?string $model = KompumedKegiatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Data Kegiatan';
    protected static ?string $pluralModelLabel = 'Data Kegiatan';
    protected static ?string $modelLabel = 'Data';
    protected static ?int $navigationSort = 2;

    protected static ?string $cluster = KompumedPerencanaan::class;
    private static bool $notificationSent = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Kegiatan Details')
                    ->schema([
                        Select::make('regional_id')
                            ->label('Regional')
                            ->required()
                            ->relationship('regional', 'nama_regional', fn($query) => $query->selectRaw('id, nama_regional')->whereNotNull('nama_regional'))
                            ->columnSpanFull(),
                        Select::make('program_id')
                            ->label('Program')
                            ->required()
                            ->relationship('program', 'nama', fn($query) => $query->selectRaw('id, nama')->whereNotNull('nama'))
                            ->columnSpanFull(),

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

                        TextInput::make('nama')
                            ->label('Nama Kegiatan')
                            ->placeholder('Nama Kegiatan Program Baru')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->rows(4)
                            ->columnSpanFull(),
                        DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->required()
                            ->displayFormat('d/m/Y'),
                        DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->required()
                            ->displayFormat('d/m/Y')
                            ->minDate(fn($get) => $get('tanggal_mulai')),
                    ])
                    ->columns(2),
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
                TextColumn::make('nama')
                    ->label('Nama Kegiatan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->date()
                    ->sortable(),
                TextColumn::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
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
            'index' => Pages\ListKompumedKegiatans::route('/'),
            // 'create' => Pages\CreateKompumedKegiatan::route('/create'),
            // 'edit' => Pages\EditKompumedKegiatan::route('/{record}/edit'),
        ];
    }
}
