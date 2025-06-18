<?php

namespace App\Filament\Clusters\PengmasPerencanaan\Resources;

use App\Filament\Clusters\PengmasPerencanaan;
use App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasWilayahKegiatanResource\Pages;
use App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasWilayahKegiatanResource\RelationManagers;
use App\Models\PengmasWilayahKegiatan;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\PengmasRencanaProgramAnggaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Support\RawJs; // This is the correct import for RawJs

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;

use App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasWilayahKegiatanResource\Widgets\PengmasWilayahKegiatanStats;

class PengmasWilayahKegiatanResource extends Resource
{
    protected static ?string $model = PengmasWilayahKegiatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Wilayah Kegiatan Program';
    protected static ?string $pluralModelLabel = 'Wilayah Kegiatan Program';
    protected static ?string $modelLabel = 'Data';
    protected static ?int $navigationSort = 2;

    protected static ?string $cluster = PengmasPerencanaan::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Wilayah Kegiatan Details')
                    ->schema([
                        Select::make('program_id')
                            ->label('Dari Program')
                            ->relationship('dariProgram', 'nama_program', fn($query) => $query->selectRaw('id, nama_program')->whereNotNull('nama_program'))
                            ->required(),
                        TextInput::make('anggaran')
                            ->label('Anggaran Kegiatan')
                            ->prefix('Rp')
                            ->numeric() // Ensures only numbers can be entered
                            ->required()
                            ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2),
                        TextInput::make('nama_kegiatan')
                            ->label('Nama Kegiatan')
                            ->required(),
                        Select::make('bidang_id')
                            ->label('Pilar')
                            ->relationship('dariBidang', 'nama_bidang')
                            ->required()
                            ->preload(),
                        Select::make('id_provinsi')
                            ->label('Provinsi')
                            ->required()
                            ->options(fn() => Provinsi::whereNotNull('nama_provinsi')->pluck('nama_provinsi', 'id'))
                            ->reactive()
                            ->afterStateUpdated(fn(callable $set) => $set('id_kabupaten', null))
                            ->dehydrateStateUsing(fn($state) => $state) // Keep the state as is when saving
                            ->afterStateHydrated(function (Select $component, $state, callable $set, $record) {
                                // When hydrating the form (on edit), if id_desa exists,
                                // try to find the corresponding province ID.
                                if ($record && $record->id_desa) {
                                    $desa = Desa::find($record->id_desa);
                                    if ($desa && $desa->kecamatan && $desa->kecamatan->kabupaten && $desa->kecamatan->kabupaten->provinsi) {
                                        $set('id_provinsi', $desa->kecamatan->kabupaten->provinsi->id);
                                    }
                                }
                            }),
                        Select::make('id_kabupaten')
                            ->label('Kabupaten')
                            ->required()
                            ->options(function ($get) {
                                $provinsiId = $get('id_provinsi');
                                if (!$provinsiId) {
                                    return [];
                                }
                                return Kabupaten::where('id_provinsi', $provinsiId)->whereNotNull('nama_kab')->pluck('nama_kab', 'id');
                            })
                            ->reactive()
                            ->afterStateUpdated(fn(callable $set) => $set('id_kecamatan', null))
                            ->dehydrateStateUsing(fn($state) => $state) // Keep the state as is when saving
                            ->afterStateHydrated(function (Select $component, $state, callable $set, $record) {
                                // When hydrating the form (on edit), if id_desa exists,
                                // try to find the corresponding kabupaten ID.
                                if ($record && $record->id_desa) {
                                    $desa = Desa::find($record->id_desa);
                                    if ($desa && $desa->kecamatan && $desa->kecamatan->kabupaten) {
                                        $set('id_kabupaten', $desa->kecamatan->kabupaten->id);
                                    }
                                }
                            }),

                        Select::make('id_kecamatan')
                            ->label('Kecamatan')
                            ->required()
                            ->options(function ($get) {
                                $kabupatenId = $get('id_kabupaten');
                                if (!$kabupatenId) {
                                    return [];
                                }
                                return Kecamatan::where('id_kabupaten', $kabupatenId)->whereNotNull('nama_kec')->pluck('nama_kec', 'id');
                            })
                            ->reactive()
                            ->afterStateUpdated(fn(callable $set) => $set('id_desa', null))
                            ->dehydrateStateUsing(fn($state) => $state) // Keep the state as is when saving
                            ->afterStateHydrated(function (Select $component, $state, callable $set, $record) {
                                // When hydrating the form (on edit), if id_desa exists,
                                // try to find the corresponding kecamatan ID.
                                if ($record && $record->id_desa) {
                                    $desa = Desa::find($record->id_desa);
                                    if ($desa && $desa->kecamatan) {
                                        $set('id_kecamatan', $desa->kecamatan->id);
                                    }
                                }
                            }),

                        Select::make('id_desa')
                            ->label('Desa')
                            ->required()
                            ->options(function ($get) {
                                $kecamatanId = $get('id_kecamatan');
                                if (!$kecamatanId) {
                                    return [];
                                }
                                return Desa::where('id_kecamatan', $kecamatanId)->whereNotNull('nama_desa')->pluck('nama_desa', 'id');
                            })
                            ->dehydrateStateUsing(fn($state) => $state) // Keep the state as is when saving
                            ->afterStateHydrated(function (Select $component, $state, callable $set, $record) {
                                // This is the field that holds the saved value,
                                // so we don't need to explicitly set other fields here.
                                // Filament will automatically set this field's value based on $record->id_desa.
                            }),
                        DatePicker::make('rencana_mulai')
                            ->required(),
                        DatePicker::make('rencana_selesai')
                            ->required()
                            ->afterOrEqual('rencana_mulai'),
                        TextInput::make('alamat')
                            ->label('Alamat')
                            ->nullable()
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->hint('(Opsional)'),
                        TextInput::make('jumlah_penerima')
                            ->label('Jumlah Penerima Manfaat')
                            ->numeric()
                            ->minValue(1)
                            ->columnSpanFull()
                            ->hint('(Opsional)'),
                        Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->rows(4)
                            ->nullable()
                            ->columnSpanFull()
                            ->hint('(Opsional)'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_kegiatan')
                    ->label('Kegiatan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('dariProgram.nama_program')
                    ->label('Dari Program')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('dariBidang.nama_bidang')
                    ->label('Pilar')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('anggaran')
                    ->label('Anggaran')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                TextColumn::make('desa.nama_desa')
                    ->label('Desa')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('desa.kecamatan.nama_kec')
                    ->label('Kecamatan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('desa.kecamatan.kabupaten.nama_kab')
                    ->label('Kabupaten')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('desa.kecamatan.kabupaten.provinsi.nama_provinsi')
                    ->label('Provinsi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('jumlah_penerima')
                    ->label('Jumlah Penerima')
                    ->sortable(),
            ])
            ->filters([// --- TAMBAHKAN FILTER DI SINI ---
                SelectFilter::make('program_id') // Nama field di database PengmasWilayahKegiatan
                    ->label('Program')
                    ->options(
                        // Ambil opsi dari model PengmasRencanaProgramAnggaran
                        // Ini akan mengambil semua program yang memiliki nama_program tidak null
                        // dan menggunakannya sebagai opsi filter.
                        // Kunci array adalah 'id' program, dan nilainya adalah 'nama_program'.
                        PengmasRencanaProgramAnggaran::whereNotNull('nama_program')
                            ->pluck('nama_program', 'id')
                            ->all() // Konversi ke array
                    )
                    // Jika Anda ingin filter ini diterapkan pada relasi, gunakan ->relationship()
                    // Namun karena 'program_id' adalah kolom langsung di tabel 'pengmas_wilayah_kegiatans',
                    // maka tidak perlu ->relationship() di sini. Filament akan otomatis
                    // menambahkan klausa WHERE program_id = 'nilai_terpilih' ke query.
                    ->placeholder('Semua Program'), // Teks untuk opsi "tidak ada filter"

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
            'index' => Pages\ListPengmasWilayahKegiatans::route('/'),
            // 'create' => Pages\CreatePengmasWilayahKegiatan::route('/create'),
            // 'edit' => Pages\EditPengmasWilayahKegiatan::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            PengmasWilayahKegiatanStats::class,
        ];
    }
}
