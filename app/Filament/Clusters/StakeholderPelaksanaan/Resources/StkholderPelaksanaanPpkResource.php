<?php

namespace App\Filament\Clusters\StakeholderPelaksanaan\Resources;

use App\Filament\Clusters\StakeholderPelaksanaan;
use App\Filament\Clusters\StakeholderPelaksanaan\Resources\StkholderPelaksanaanPpkResource\Pages;
use App\Filament\Clusters\StakeholderPelaksanaan\Resources\StkholderPelaksanaanPpkResource\RelationManagers;
use App\Models\StkholderPelaksanaanPpk;
use App\Models\StkholderPerencanaanProgramAnggaran;
use App\Models\StkholderProfilExternal;
use App\Models\StkholderProfilInternal;
use App\Models\TahunFiskal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder; // <-- Import Builder
use Illuminate\Support\HtmlString; // Tambahkan baris ini
use Filament\Notifications\Notification;

use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload as FilamentSpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn as FilamentSpatieMediaLibraryImageColumn;
use App\Filament\Traits\HasResourcePermissions;

class StkholderPelaksanaanPpkResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'stakeholder';
    protected static ?string $model = StkholderPelaksanaanPpk::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Kegiatan PPK';
    protected static ?string $pluralModelLabel = 'Kegiatan PPK';
    protected static ?string $modelLabel = 'Data';

    protected static ?string $cluster = StakeholderPelaksanaan::class;
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
                    ->directory('stkholder-images')
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
                Section::make('Kegiatan Details')
                    ->schema([
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

                                return "{$record->kegiatan} ({$regionalName} - {$programName})";
                            })
                            ->required()
                            ->columnSpanFull()
                            ->preload(),
                    ])
                    ->columns(2),
                Section::make('Pelaksana Details')
                    ->schema([
                        Select::make('pelaksana')
                            ->label('Pelaksana')
                            ->required()
                            ->options(function () {
                                $externals = StkholderProfilExternal::whereNotNull('nama')
                                    ->selectRaw('id, CONCAT("[External] ", nama) as pelaksana')
                                    ->get()
                                    ->mapWithKeys(fn($item) => ['ext_' . $item->id => $item->pelaksana])
                                    ->toArray();

                                $internals = StkholderProfilInternal::whereNotNull('nama')
                                    ->selectRaw('id, CONCAT("[Internal] ", nama) as pelaksana')
                                    ->get()
                                    ->mapWithKeys(fn($item) => ['int_' . $item->id => $item->pelaksana])
                                    ->toArray();

                                $options = array_merge($internals, $externals);

                                return $options;
                            })
                            ->searchable()
                            ->live() // Gunakan live() daripada afterStateUpdated untuk reactivity yang lebih baik
                            ->afterStateHydrated(function (Select $component, $state, $record) {
                                // Pastikan state terisi dengan nilai dari record jika ada
                                if ($record && blank($state)) {
                                    $component->state($record->pelaksana_key);
                                }
                            })
                            ->dehydrated(false)
                            ->columnSpanFull()
                            ->afterStateUpdated(function ($state, callable $set) {

                                if ($state) {
                                    // Pecah prefix dan id
                                    [$prefix, $id] = explode('_', $state, 2);

                                    if ($prefix === 'ext') {
                                        $modelClass = \App\Models\StkholderProfilExternal::class;
                                    } elseif ($prefix === 'int') {
                                        $modelClass = \App\Models\StkholderProfilInternal::class;
                                    } else {
                                        return; // Prefix tidak valid
                                    }

                                    // Set data relasional
                                    $set('pelaksana_id', $id);
                                    $set('pelaksana_type', $modelClass);
                                }
                            }),
                        Hidden::make('pelaksana_id'),
                        Hidden::make('pelaksana_type'),
                    ]),
                Section::make('Pelaksanaan Details')
                    ->schema([
                        Select::make('coverage')
                            ->label('Lingkup Area')
                            ->options([
                                'desa' => 'Desa',
                                'kecamatan' => 'Kecamatan',
                                'kabupaten' => 'Kabupaten',
                                'provinsi' => 'Provinsi',
                            ])
                            ->nullable(),
                        TextInput::make('kategori')
                            ->label('Kategori')
                            ->nullable(),
                        TextInput::make('karakter')
                            ->label('Karakter')
                            ->nullable(),
                        TextInput::make('biaya')
                            ->label('Biaya')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->nullable(),
                        DatePicker::make('tanggal_pelaksanaan')
                            ->label('Tanggal Pelaksanaan')
                            ->nullable()
                            ->native(false),
                    ])
                    ->columns(2),
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
                TextColumn::make('kegiatan.kegiatan')
                    ->label('Kegiatan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('kegiatan.program.nama')
                    ->label('Program')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('pelaksana.nama')
                    ->label('Pelaksana')
                    ->formatStateUsing(function ($state, $record) {
                        $prefix = '';

                        if ($record->pelaksana_type === \App\Models\StkholderProfilInternal::class) {
                            $prefix = '[Internal] ';
                        } elseif ($record->pelaksana_type === \App\Models\StkholderProfilExternal::class) {
                            $prefix = '[External] ';
                        }

                        return $prefix . $state;
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('coverage')
                    ->label('Lingkup Area')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('biaya')
                    ->label('Biaya')
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
            'index' => Pages\ListStkholderPelaksanaanPpks::route('/'),
            // 'create' => Pages\CreateStkholderPelaksanaanPpk::route('/create'),
            // 'edit' => Pages\EditStkholderPelaksanaanPpk::route('/{record}/edit'),
        ];
    }
}
