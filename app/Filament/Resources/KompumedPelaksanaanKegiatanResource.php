<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KompumedPelaksanaanKegiatanResource\Pages;
use App\Filament\Resources\KompumedPelaksanaanKegiatanResource\RelationManagers;
use App\Models\KompumedPelaksanaanKegiatan;
use App\Models\KompumedKegiatan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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
                    ->label('Kegiatan')
                    ->options(function () {
                        return KompumedKegiatan::query()
                            ->with(['regional', 'program'])
                            ->selectRaw('kompumed_kegiatans.id, CONCAT(kompumed_kegiatans.nama, " (", regionals.nama_regional, " - ", kompumed_rencana_programs.nama, ")") as kegiatan')
                            ->join('regionals', 'kompumed_kegiatans.regional_id', '=', 'regionals.id')
                            ->join('kompumed_rencana_programs', 'kompumed_kegiatans.program_id', '=', 'kompumed_rencana_programs.id')
                            ->orderBy('kompumed_kegiatans.nama') // Atur pengurutan berdasarkan kolom yang sesuai
                            ->pluck('kegiatan', 'id');
                    })
                    ->required()
                    ->searchable()
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
        return $table
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
