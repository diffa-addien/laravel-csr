<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KompumedPelaksanaanKegiatanResource\Pages;
use App\Filament\Resources\KompumedPelaksanaanKegiatanResource\RelationManagers;
use App\Models\KompumedPelaksanaanKegiatan;
use App\Models\KompumedKegiatan;
use App\Models\TahunFiskal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Builder; // <-- Import Builder
use Illuminate\Support\HtmlString; // Tambahkan baris ini
use Filament\Notifications\Notification;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload as FilamentSpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn as FilamentSpatieMediaLibraryImageColumn;
use App\Filament\Traits\HasResourcePermissions;

class KompumedPelaksanaanKegiatanResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'komunikasi_media';
    protected static ?string $model = KompumedPelaksanaanKegiatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $navigationGroup = 'Komunikasi, Publikasi, dan Hubungan Media';
    protected static ?string $navigationLabel = 'Pelaksanaan';
    protected static ?string $pluralModelLabel = 'Data Pelaksanaan Program';
    protected static ?string $modelLabel = 'Data';
    protected static ?int $navigationSort = 2;
    private static bool $notificationSent = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FilamentSpatieMediaLibraryFileUpload::make('images')
                    ->collection('images')
                    ->multiple()
                    ->image()
                    ->maxFiles(5)
                    ->maxSize(2048) // 2MB per file
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif'])
                    ->disk('uploads')
                    ->directory('kompumed-images')
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        null, // Free crop
                        '16:9',
                        '4:3',
                        '1:1',
                    ])
                    ->downloadable()
                    ->openable()
                    ->reorderable()
                    ->appendFiles()
                    ->label('Gambar')
                    ->rules(['image', 'max:2048'])
                    ->validationMessages([
                        'image' => 'File harus berupa gambar (jpeg, png, atau gif).',
                        'max' => 'Ukuran file tidak boleh melebihi 2MB.',
                    ])
                    ->previewable(true)
                    ->imagePreviewHeight('150') // Tinggi preview 80px untuk tampilan compact
                    ->panelLayout('grid') // Tata letak grid untuk preview lebih rapi
                    ->extraAttributes(['style' => 'gap: 10px;']) // Jarak antar thumbnail
                    ->columnSpanFull(),
                Select::make('kegiatan_id')
                    ->label('Untuk Kegiatan Program')
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

                        return "{$record->nama} ({$regionalName} - {$programName})";
                    })
                    ->required()
                    ->columnSpanFull()
                    ->preload(),
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
                DatePicker::make('tanggal_pelaksanaan')
                    ->label('Tanggal Pelaksanaan')
                    ->required()
                    ->displayFormat('d/m/Y')
                    ->minDate(fn($get) => $get('tanggal_mulai')),
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
                FilamentSpatieMediaLibraryImageColumn::make('images')
                    ->collection('images')
                    ->label('Gambar')
                    ->limit(3)
                    ->circular()
                    ->stacked()
                    ->extraImgAttributes(['style' => 'max-height: 50px;']),
                TextColumn::make('kegiatan.nama')
                    ->label('Kegiatan')
                    ->formatStateUsing(fn($record) => "{$record->kegiatan->nama} ({$record->kegiatan->regional->nama_regional} - {$record->kegiatan->program->nama})")
                    ->sortable()
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
                TextColumn::make('tanggal_pelaksanaan')
                    ->label('Tanggal Pelaksanaan')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListKompumedPelaksanaanKegiatans::route('/'),
            // 'create' => Pages\CreateKompumedPelaksanaanKegiatan::route('/create'),
            // 'edit' => Pages\EditKompumedPelaksanaanKegiatan::route('/{record}/edit'),
        ];
    }
}
