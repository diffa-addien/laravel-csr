<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengmasPelaksanaanKegiatanResource\Pages;
use App\Filament\Resources\PengmasPelaksanaanKegiatanResource\RelationManagers;
use App\Models\PengmasPelaksanaanKegiatan;
use App\Models\PengmasRencanaProgramAnggaran;
use App\Models\PengmasWilayahKegiatan;
use App\Models\TahunFiskal; // <-- TAMBAHKAN IMPORT INI

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload as FilamentSpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn as FilamentSpatieMediaLibraryImageColumn;
use Filament\Forms\Get; // <-- Import Get
use Filament\Forms\Set; // <-- Import Set
use Illuminate\Database\Eloquent\Builder; // <-- Import Builder
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString; // Tambahkan baris ini
use App\Filament\Traits\HasResourcePermissions;

class PengmasPelaksanaanKegiatanResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'pengembangan_masyarakat';
    protected static ?string $model = PengmasPelaksanaanKegiatan::class;
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationGroup = 'Pengembangan Masyarakat';
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
                    ->directory('pengmas-images')
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
                // STEP 1: Select Program (Dengan Logika Filter dan Label Custom)
                Select::make('program_selector')
                    ->label('Pilih Program')
                    // --- LOGIKA BARU DIMULAI DI SINI ---
                    ->options(function () {
                        // 1. Dapatkan ID tahun fiskal yang aktif
                        $activeTahunFiskalId = TahunFiskal::where('is_active', true)->value('id');

                        // Jika tidak ada tahun aktif, kembalikan array kosong
                        if (!$activeTahunFiskalId) {
                            return [];
                        }

                        // 2. Query program berdasarkan tahun fiskal aktif
                        $programs = PengmasRencanaProgramAnggaran::query()
                            ->where('tahun_fiskal', $activeTahunFiskalId)
                            ->with('dariTahunFiskal') // Eager load untuk efisiensi
                            ->get();

                        // 3. Buat label custom seperti "Nama Program (Tahun Fiskal)"
                        return $programs->mapWithKeys(function ($program) {
                            $tahun = $program->dariTahunFiskal?->nama_tahun_fiskal ?? 'N/A';
                            return [$program->id => "{$program->nama_program} ({$tahun})"];
                        });
                    })
                    // --- AKHIR LOGIKA BARU ---
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(fn(Set $set) => $set('kegiatan_id', null))
                    ->dehydrated(false),
                Select::make('kegiatan_id')
                    ->label('Pilih Kegiatan')
                    ->options(function (Get $get) {
                        $programId = $get('program_selector');
                        if ($programId) {
                            return PengmasWilayahKegiatan::query()
                                ->where('program_id', $programId)
                                ->pluck('nama_kegiatan', 'id');
                        }
                        return collect();
                    })
                    ->afterStateHydrated(function (Set $set, $state) {
                        if ($state) {
                            $kegiatan = PengmasWilayahKegiatan::find($state);
                            if ($kegiatan) {
                                $set('program_selector', $kegiatan->program_id);
                            }
                        }
                    })
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state) {
                        if ($state) {
                            $kegiatan = PengmasWilayahKegiatan::find($state);
                            if ($kegiatan) {
                                $set('tanggal_pelaksanaan', $kegiatan->rencana_mulai);
                            }
                        } else {
                            $set('tanggal_pelaksanaan', null);
                        }
                    }),

                TextInput::make('jumlah_penerima')
                    ->required()
                    ->numeric()
                    ->minValue(1)->helperText(function (Get $get) {
                        $kegiatanId = $get('kegiatan_id');
                        if (!$kegiatanId) {
                            return null;
                        }

                        $kegiatan = PengmasWilayahKegiatan::find($kegiatanId);
                        if (!$kegiatan) {
                            return null;
                        }

                        return 'Penerima yang direncanakan: ' . ($kegiatan->jumlah_penerima ?? '0');
                    }),
                TextInput::make('anggaran_pelaksanaan')
                    ->required()
                    ->prefix('Rp')
                    ->numeric()
                    ->required()
                    ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2)->helperText(function (Get $get) {
                        $kegiatanId = $get('kegiatan_id');
                        if (!$kegiatanId) {
                            // Beri petunjuk umum jika belum ada kegiatan yang dipilih
                            return 'Masukkan angka saja tanpa titik atau koma.';
                        }

                        $kegiatan = PengmasWilayahKegiatan::find($kegiatanId);
                        if (!$kegiatan) {
                            return null;
                        }

                        $formattedAnggaran = 'Rp ' . number_format($kegiatan->anggaran, 0, ',', '.');

                        return 'Anggaran yang direncanakan: ' . $formattedAnggaran;
                    }),
                DatePicker::make('tanggal_pelaksanaan')
                    ->required(),
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
                $activeTahunFiskalId = TahunFiskal::where('is_active', true)->value('id');

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
                    $query->whereRaw('1 = 0');
                    return;
                }

                $query->whereHas('kegiatan.dariProgram', function (Builder $programQuery) use ($activeTahunFiskalId) {
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
                TextColumn::make('kegiatan.nama_kegiatan')
                    ->label('Kegiatan')
                    ->formatStateUsing(fn($record) => "{$record->kegiatan->nama_kegiatan}")
                    ->sortable()
                    ->searchable(),
                TextColumn::make('jumlah_penerima')
                    ->label('Jumlah Penerima Manfaat')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('anggaran_pelaksanaan')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                TextColumn::make('tanggal_pelaksanaan')
                    ->date()
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
            'index' => Pages\ListPengmasPelaksanaanKegiatans::route('/'),
            // 'create' => Pages\CreatePengmasPelaksanaanKegiatan::route('/create'),
            // 'edit' => Pages\EditPengmasPelaksanaanKegiatan::route('/{record}/edit'),
        ];
    }
}
