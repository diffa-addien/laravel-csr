<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BeritaResource\Pages;
use App\Models\Berita;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BeritaResource extends Resource
{
    protected static ?string $model = Berita::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Konten Utama')
                            ->schema([
                                Forms\Components\TextInput::make('judul')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(debounce: 2000) // `live` untuk update slug otomatis
                                    ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                                Forms\Components\TextInput::make('slug')
                                    ->hint('untuk belakang link')
                                    ->required()
                                    ->maxLength(255)
                                    ->disabled()
                                    ->dehydrated()
                                    ->unique(Berita::class, 'slug', ignoreRecord: true),

                                Forms\Components\RichEditor::make('konten')
                                    ->required()
                                    ->disableToolbarButtons([
                                        'attachFiles',
                                    ])
                                    ->columnSpanFull(), // Agar lebarnya penuh

                                Forms\Components\TextInput::make('sumber')
                                    ->label('Sumber Berita (Link)')
                                    ->url() // Validasi format URL
                                    ->nullable()
                                    ->columnSpanFull(),
                            ])->columns(2),

                        Forms\Components\Section::make('Gambar')
                            ->schema([
                                Forms\Components\SpatieMediaLibraryFileUpload::make('images')
                                    ->label('Upload Gambar Berita')
                                    ->collection('images') // Nama collection dari model
                                    ->disk('uploads')    // Nama disk custom Anda
                                    ->multiple()            // Izinkan upload banyak gambar
                                    ->image()
                                    ->imageEditor(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Status & Atribut')
                            ->schema([
                                Forms\Components\Toggle::make('is_published')
                                    ->label('Publikasikan')
                                    ->default(true),

                                // ✅ PERBAIKAN: Ubah dari Select ke TextInput
                                Forms\Components\TextInput::make('penulis')
                                    ->label('Nama Penulis')
                                    ->required()
                                    ->maxLength(255)
                                    ->default(auth()->user()->name), // Default nama user login

                                Forms\Components\Select::make('kategori_id')
                                    ->label('Kategori')
                                    ->relationship('kategori', 'nama')
                                    // ->searchable()
                                    ->preload() // preload agar pilihan muncul saat form dibuka
                                    ->required()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('nama')
                                            ->required()
                                            ->maxLength(255),
                                    ])
                                    ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                                        // Mengubah teks tombol default "Create"
                                        return $action
                                            ->label('Buat Kategori Baru')
                                            ->modalHeading('Buat Kategori')
                                            ->modalButton('Buat');
                                    }),
                                    
                                Forms\Components\Select::make('tags')
                                    ->relationship('tags', 'nama')
                                    ->multiple()
                                    ->preload()
                                    ->searchable()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('nama')
                                            ->required()
                                            ->maxLength(255),
                                    ])
                                    ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                                        return $action
                                            ->label('Buat Tag Baru')
                                            ->modalHeading('Buat Tag')
                                            ->modalButton('Buat');
                                    }),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),

            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->label('Gambar')
                    ->collection('images'), // Ambil dari collection 'images'

                Tables\Columns\TextColumn::make('judul')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kategori.nama')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Status')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListBeritas::route('/'),
            'create' => Pages\CreateBerita::route('/create'),
            // 'edit' => Pages\EditBerita::route('/{record}/edit'),
        ];
    }
}