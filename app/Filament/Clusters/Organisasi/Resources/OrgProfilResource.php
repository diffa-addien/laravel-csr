<?php

namespace App\Filament\Clusters\Organisasi\Resources;

use App\Filament\Clusters\Organisasi;
use App\Filament\Clusters\Organisasi\Resources\OrgProfilResource\Pages;
use App\Filament\Clusters\Organisasi\Resources\OrgProfilResource\RelationManagers;
use App\Models\OrgProfil;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload as FilamentSpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn as FilamentSpatieMediaLibraryImageColumn;

class OrgProfilResource extends Resource
{
    protected static ?string $model = OrgProfil::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationLabel = 'Profil Organisasi';
    protected static ?string $modelLabel = 'Data';
    protected static ?string $pluralModelLabel = 'Profil';

    protected static ?string $cluster = Organisasi::class;
    protected static ?int $navigationSort = 1;

    public static function getPluralModelLabel(): string
    {
        return 'Profil Organisasi';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FilamentSpatieMediaLibraryFileUpload::make('images')
                    ->collection('images')
                    ->label('Logo')
                    ->directory('organisasi')
                    ->disk('uploads')
                    ->maxSize(2048) // 2MB per file
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif'])
                    ->nullable(),
                TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                TextInput::make('pimpinan')->label('Nama Pimpinan')
                    ->required()
                    ->maxLength(255),
                TextInput::make('lv1')
                    ->label('Nama Jabatan (Tingkat Manajer)')
                    ->maxLength(255)
                    ->nullable(),
                TextInput::make('lv2')
                    ->label('Nama Jabatan (Tingkat Supervisor)')
                    ->maxLength(255)
                    ->nullable(),
                TextInput::make('lv3')
                    ->label('Nama Jabatan (Tingkat Operator)')
                    ->maxLength(255)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                FilamentSpatieMediaLibraryImageColumn::make('images')
                    ->collection('images')
                    ->label('Logo')
                    ->circular()
                    ->extraImgAttributes(['style' => 'max-height: 50px;']),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Organisasi'),
                Tables\Columns\TextColumn::make('pimpinan')
                    ->label('Pimpinan')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->paginated(false);;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageOrgProfils::route('/'),
            // 'create' => Pages\CreateOrgProfil::route('/create'),
        ];
    }

    public static function canCreate(): bool
    {
        return OrgProfil::count() === 0;
    }
}
