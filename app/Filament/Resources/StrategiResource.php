<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StrategiResource\Pages;
use App\Filament\Resources\StrategiResource\RelationManagers;
use App\Models\Strategi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StrategiResource extends Resource
{
    protected static ?string $model = Strategi::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Data Induk';
    public static int $navigationGroupSort = 8;

    protected static ?string $navigationLabel = 'Strategi Program';
    protected static ?int $navigationSort = 9;

    public static function getPluralModelLabel(): string
    {
        return 'Daftar Strategi Program';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('keterangan')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Strategi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
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
            'index' => Pages\ListStrategis::route('/'),
            'create' => Pages\CreateStrategi::route('/create'),
            'edit' => Pages\EditStrategi::route('/{record}/edit'),
        ];
    }
}
