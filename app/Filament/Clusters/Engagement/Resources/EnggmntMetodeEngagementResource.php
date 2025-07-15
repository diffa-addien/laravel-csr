<?php

namespace App\Filament\Clusters\Engagement\Resources;

use App\Filament\Clusters\Engagement;
use App\Filament\Clusters\Engagement\Resources\EnggmntMetodeEngagementResource\Pages;
use App\Models\EnggmntMetodeEngagement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use App\Filament\Traits\HasResourcePermissions;
class EnggmntMetodeEngagementResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'data_induk';
    protected static ?string $model = EnggmntMetodeEngagement::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?int $navigationSort = 1;
    protected static ?string $cluster = Engagement::class;

    // Opsional: untuk nama yang lebih rapi di sidebar
    protected static ?string $modelLabel = 'Metode Engagement';
    protected static ?string $pluralModelLabel = 'Metode Engagement';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_metode')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('deskripsi')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_metode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->searchable()
                    ->limit(45),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEnggmntMetodeEngagements::route('/'),
            // 'create' => Pages\CreateEnggmntMetodeEngagement::route('/create'),
            // 'edit' => Pages\EditEnggmntMetodeEngagement::route('/{record}/edit'),
        ];
    }
}