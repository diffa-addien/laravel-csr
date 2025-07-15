<?php

namespace App\Filament\Clusters\Engagement\Resources;

use App\Filament\Clusters\Engagement;
use App\Filament\Clusters\Engagement\Resources\EnggmntStakeholderEngagementApproachResource\Pages;
use App\Models\EnggmntStakeholderEngagementApproach;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use App\Filament\Traits\HasResourcePermissions;

class EnggmntStakeholderEngagementApproachResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'data_induk';
    protected static ?string $model = EnggmntStakeholderEngagementApproach::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';
    protected static ?int $navigationSort = 3;
    protected static ?string $cluster = Engagement::class;

    protected static ?string $modelLabel = 'Stakeholder Engagement Approach';
    protected static ?string $pluralModelLabel = 'Stakeholder Engagement Approach';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_approach')
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
                Tables\Columns\TextColumn::make('nama_approach')
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
            'index' => Pages\ListEnggmntStakeholderEngagementApproaches::route('/'),
            //     'create' => Pages\CreateEnggmntStakeholderEngagementApproach::route('/create'),
            //     'edit' => Pages\EditEnggmntStakeholderEngagementApproach::route('/{record}/edit'),
        ];
    }
}