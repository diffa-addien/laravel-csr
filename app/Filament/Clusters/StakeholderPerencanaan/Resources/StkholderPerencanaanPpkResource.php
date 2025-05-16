<?php

namespace App\Filament\Clusters\StakeholderPerencanaan\Resources;

use App\Filament\Clusters\StakeholderPerencanaan;
use App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderPerencanaanPpkResource\Pages;
use App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderPerencanaanPpkResource\RelationManagers;
use App\Models\StkholderPerencanaanPpk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StkholderPerencanaanPpkResource extends Resource
{
    protected static ?string $model = StkholderPerencanaanPpk::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-arrow-up';

    protected static ?string $navigationLabel = 'Perencanaan PPK';
    protected static ?string $pluralModelLabel = 'Perencanaan PPK';
    protected static ?string $modelLabel = 'Data';

    protected static ?string $cluster = StakeholderPerencanaan::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('keterangan')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable()
                    ->limit(50),
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
            'index' => Pages\ListStkholderPerencanaanPpks::route('/'),
            'create' => Pages\CreateStkholderPerencanaanPpk::route('/create'),
            'edit' => Pages\EditStkholderPerencanaanPpk::route('/{record}/edit'),
        ];
    }
}
