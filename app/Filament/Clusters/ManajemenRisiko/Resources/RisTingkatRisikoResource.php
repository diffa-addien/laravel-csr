<?php

namespace App\Filament\Clusters\ManajemenRisiko\Resources;

use App\Filament\Clusters\ManajemenRisiko;
use App\Filament\Clusters\ManajemenRisiko\Resources\RisTingkatRisikoResource\Pages;
use App\Models\RisTingkatRisiko;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use App\Filament\Traits\HasResourcePermissions;
class RisTingkatRisikoResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'data_induk';
    protected static ?string $model = RisTingkatRisiko::class;

    protected static ?string $cluster = ManajemenRisiko::class;
    protected static ?string $navigationIcon = 'heroicon-o-ellipsis-horizontal-circle';
    protected static ?int $navigationSort = 3;
    protected static ?string $modelLabel = 'Tingkat Risiko';
    protected static ?string $navigationLabel = 'Tingkat Risiko';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tingkat_risiko')
                    ->required()
                    ->maxLength(255)
                    ->label('Tingkat Risiko'),
                Forms\Components\TextInput::make('deskripsi')
                    ->required()
                    ->maxLength(255)
                    ->label('Deskripsi'),
                Forms\Components\Textarea::make('petunjuk')
                    ->nullable()
                    ->columnSpanFull()
                    ->label('Petunjuk'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tingkat_risiko')
                    ->label('Tingkat Risiko')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('petunjuk'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRisTingkatRisikos::route('/'),
            // 'create' => Pages\CreateRisTingkatRisiko::route('/create'),
            // 'edit' => Pages\EditRisTingkatRisiko::route('/{record}/edit'),
        ];
    }
}