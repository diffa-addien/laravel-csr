<?php

namespace App\Filament\Clusters\Wilayah\Resources;

use App\Filament\Clusters\Wilayah;
use App\Filament\Clusters\Wilayah\Resources\KabupatenResource\Pages;
use App\Filament\Clusters\Wilayah\Resources\KabupatenResource\RelationManagers;
use App\Models\Kabupaten;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KabupatenResource extends Resource
{
    protected static ?string $model = Kabupaten::class;

    protected static ?string $navigationIcon = 'heroicon-o-ellipsis-horizontal-circle';

    protected static ?string $navigationLabel = "Kabupaten";

    protected static ?string $modelLabel = 'Data';
    protected static ?string $navigationParentItem = "Provinsi";

    protected static ?int $navigationSort = 9;

    protected static ?string $cluster = Wilayah::class;

    public static function getPluralModelLabel(): string
    {
        return 'Kabupaten';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id_provinsi')
                    ->label('Provinsi')
                    ->relationship('provinsi', 'nama_provinsi')
                    ->required()
                    ->searchable()
                    ->columnSpanFull()
                    ->preload(),
                Forms\Components\TextInput::make('nama_kab')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('bupati')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('provinsi.nama_provinsi')
                    ->label('Provinsi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_kab')
                    ->label('Kabupaten / Kota')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bupati')
                    ->searchable()
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
            'index' => Pages\ListKabupatens::route('/'),
            // 'create' => Pages\CreateKabupaten::route('/create'),
            // 'edit' => Pages\EditKabupaten::route('/{record}/edit'),
        ];
    }
}
