<?php

namespace App\Filament\Clusters\Sdg\Resources;

use App\Filament\Clusters\Sdg;
use App\Filament\Clusters\Sdg\Resources\SdgTujuanResource\Pages;
use App\Filament\Clusters\Sdg\Resources\SdgTujuanResource\RelationManagers;
use App\Models\SdgTujuan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SdgTujuanResource extends Resource
{
    protected static ?string $model = SdgTujuan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Tujuan SDGs';
    protected static ?string $pluralModelLabel = 'Data Tujuan SDGs';
    protected static ?string $modelLabel = 'Data';
    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Sdg::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tujuan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('keterangan')
                    ->rows(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tujuan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->limit(50),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
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
            'index' => Pages\ListSdgTujuans::route('/'),
            // 'create' => Pages\CreateSdgTujuan::route('/create'),
            // 'edit' => Pages\EditSdgTujuan::route('/{record}/edit'),
        ];
    }
}
