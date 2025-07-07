<?php

namespace App\Filament\Clusters\ManajemenRisiko\Resources;

use App\Filament\Clusters\ManajemenRisiko;
use App\Filament\Clusters\ManajemenRisiko\Resources\RisTingkatKemungkinanResource\Pages;
use App\Models\RisTingkatKemungkinan; // <-- Sesuaikan nama model
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use App\Filament\Traits\HasResourcePermissions;

class RisTingkatKemungkinanResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'data_induk';
    protected static ?string $model = RisTingkatKemungkinan::class; // <-- Sesuaikan nama model

    protected static ?string $cluster = ManajemenRisiko::class;
    protected static ?string $navigationIcon = 'heroicon-o-ellipsis-horizontal-circle';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Tingkat Kemungkinan Risiko';
    protected static ?string $navigationLabel = 'Tingkat Kemungkinan Risiko';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tingkat')
                    ->required()
                    ->numeric()
                    ->label('Tingkat'),
                Forms\Components\TextInput::make('kemungkinan_risiko')
                    ->required()
                    ->maxLength(255)
                    ->label('Kemungkinan Risiko'),
                Forms\Components\Textarea::make('deskripsi')
                    ->nullable()
                    ->columnSpanFull()
                    ->label('Deskripsi'),
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\Textarea::make('kriteria_kualitatif')
                            ->nullable()
                            ->label('Kriteria Kualitatif'),
                        Forms\Components\Textarea::make('kriteria_kuantitatif')
                            ->nullable()
                            ->label('Kriteria Kuantitatif'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tingkat')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kemungkinan_risiko')
                    ->searchable()
                    ->sortable()
                    ->label('Kemungkinan Risiko'),
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
            'index' => Pages\ListResTingkatKemungkinans::route('/'),
            // 'create' => Pages\CreateResTingkatKemungkinan::route('/create'),
            // 'edit' => Pages\EditResTingkatKemungkinan::route('/{record}/edit'),
        ];
    }
}