<?php

namespace App\Filament\Clusters\Engagement\Resources;

use App\Filament\Clusters\Engagement;
use App\Filament\Clusters\Engagement\Resources\EnggmntMaterialKomunikasiResource\Pages;
use App\Models\EnggmntMaterialKomunikasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use App\Filament\Traits\HasResourcePermissions;

class EnggmntMaterialKomunikasiResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'data_induk';
    protected static ?string $model = EnggmntMaterialKomunikasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 2;
    protected static ?string $cluster = Engagement::class;

    protected static ?string $modelLabel = 'Material Komunikasi';
    protected static ?string $pluralModelLabel = 'Material Komunikasi';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_material')
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
                Tables\Columns\TextColumn::make('nama_material')
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
            'index' => Pages\ListEnggmntMaterialKomunikasis::route('/'),
            // 'create' => Pages\CreateEnggmntMaterialKomunikasi::route('/create'),
            // 'edit' => Pages\EditEnggmntMaterialKomunikasi::route('/{record}/edit'),
        ];
    }
}