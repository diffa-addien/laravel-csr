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

use Illuminate\Database\Eloquent\Builder;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

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
                            ->required()
                            ->relationship('dariProgram', 'nama_program', fn($query) => $query->selectRaw('id, nama_program')->whereNotNull('nama_program'))
                            ->columnSpanFull(),
                        Select::make('id_provinsi')
                            ->label('Provinsi')
                            ->required()
                            ->options(fn() => Provinsi::whereNotNull('nama_provinsi')->pluck('nama_provinsi', 'id'))
                            ->reactive()
                            ->afterStateUpdated(fn(callable $set) => $set('id_kabupaten', null)),
                        Select::make('id_kabupaten')
                            ->label('Kabupaten')
                            ->required()
                            ->options(function ($get) {
                                $provinsiId = $get('id_provinsi');
                                if (!$provinsiId) return [];
                                return Kabupaten::where('id_provinsi', $provinsiId)->whereNotNull('nama_kab')->pluck('nama_kab', 'id');
                            })
                            ->reactive()
                            ->afterStateUpdated(fn(callable $set) => $set('id_kecamatan', null)),
                        Select::make('id_kecamatan')
                            ->label('Kecamatan')
                            ->required()
                            ->options(function ($get) {
                                $kabupatenId = $get('id_kabupaten');
                                if (!$kabupatenId) return [];
                                return Kecamatan::where('id_kabupaten', $kabupatenId)->whereNotNull('nama_kec')->pluck('nama_kec', 'id');
                            })
                            ->reactive()
                            ->afterStateUpdated(fn(callable $set) => $set('id_desa', null)),
                        Select::make('id_desa')
                            ->label('Desa')
                            ->required()
                            ->relationship('desa', 'nama_desa', fn($query, $get) => $query->where('id_kecamatan', $get('id_kecamatan'))->whereNotNull('nama_desa'))
                            ->columnSpanFull(),
                        TextInput::make('alamat')
                            ->label('Alamat')
                            ->nullable()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('jumlah_penerima')
                            ->label('Jumlah Penerima Manfaat')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->columnSpanFull(),
                        Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->rows(4)
                            ->nullable()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('desa.nama_desa')
                    ->label('Desa')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('dariProgram.nama_program')
                    ->label('Dari Program')
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
                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->sortable()
                    ->searchable()
                    ->limit(50),
                TextColumn::make('jumlah_penerima')
                    ->label('Jumlah Penerima')
                    ->sortable(),
                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
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
