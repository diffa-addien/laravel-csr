<?php

namespace App\Filament\Clusters\ManajemenRisiko\Resources;

use App\Filament\Clusters\ManajemenRisiko;
use App\Filament\Clusters\ManajemenRisiko\Resources\RisTingkatDampakResource\Pages;
use App\Models\RisTingkatDampak;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use App\Filament\Traits\HasResourcePermissions;
class RisTingkatDampakResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'data_induk';
    protected static ?string $model = RisTingkatDampak::class;
    protected static ?string $cluster = ManajemenRisiko::class;
    protected static ?string $navigationIcon = 'heroicon-o-ellipsis-horizontal-circle';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Tingkat Dampak Risiko';
    protected static ?string $navigationLabel = 'Tingkat Dampak Risiko';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tingkat')
                    ->required()
                    ->numeric()
                    ->label('Tingkat'),
                Forms\Components\TextInput::make('dampak_risiko')
                    ->required()
                    ->maxLength(255)
                    ->label('Dampak Risiko'),
                Forms\Components\Textarea::make('deskripsi')
                    ->nullable()
                    ->columnSpanFull()
                    ->label('Deskripsi'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tingkat')
                    ->sortable(),
                Tables\Columns\TextColumn::make('dampak_risiko')
                    ->label('Dampak Risiko')
                    ->searchable(),
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
            'index' => Pages\ListRisTingkatDampaks::route('/'),
            // 'create' => Pages\CreateRisTingkatDampak::route('/create'),
            // 'edit' => Pages\EditRisTingkatDampak::route('/{record}/edit'),
        ];
    }
}