<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengmasPelaksanaanKegiatanResource\Pages;
use App\Filament\Resources\PengmasPelaksanaanKegiatanResource\RelationManagers;
use App\Models\PengmasPelaksanaanKegiatan;
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

class PengmasPelaksanaanKegiatanResource extends Resource
{
    protected static ?string $model = PengmasPelaksanaanKegiatan::class;
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationGroup = 'Pengembangan Masyarakat';
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
                Select::make('program_id')
                    ->label('Program')
                    ->relationship('dariProgram', 'nama_program')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('jumlah_penerima')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                TextInput::make('anggaran_pelaksanaan')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->dehydrateStateUsing(fn ($state) => str_replace(['Rp', '.', ' '], '', $state)),
                DatePicker::make('tanggal_pelaksanaan')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dariProgram.nama_program')
                    ->label('Program')
                    ->formatStateUsing(fn ($record) => "{$record->dariProgram->nama_program}")
                    ->sortable()
                    ->searchable(),
                TextColumn::make('jumlah_penerima')
                    ->label('Jumlah Penerima Manfaat')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('anggaran_pelaksanaan')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
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
