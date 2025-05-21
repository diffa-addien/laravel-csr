<?php

namespace App\Filament\Clusters\Wilayah\Resources;

use App\Filament\Clusters\Wilayah;
use App\Filament\Clusters\Wilayah\Resources\ProvinsiResource\Pages;
use App\Filament\Clusters\Wilayah\Resources\ProvinsiResource\RelationManagers;
use App\Models\Provinsi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class ProvinsiResource extends Resource
{
    protected static ?string $model = Provinsi::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationLabel = "Provinsi";

    protected static ?int $navigationSort = 8;

    protected static ?string $cluster = Wilayah::class;

    public static function getPluralModelLabel(): string
    {
        return 'Provinsi';
    }

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('nama_provinsi')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('gubernur')
                ->required()
                ->maxLength(255),
        ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_provinsi')
                    ->label('Provinsi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gubernur')
                    ->searchable()
                    ->limit(50),
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
            'index' => Pages\ListProvinsis::route('/'),
            // 'create' => Pages\CreateProvinsi::route('/create'),
            // 'edit' => Pages\EditProvinsi::route('/{record}/edit'),
        ];
    }
}
