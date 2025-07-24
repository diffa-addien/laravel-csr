<?php

namespace App\Filament\Clusters\Engagement\Resources;

use App\Filament\Clusters\Engagement; // Make sure to import the Cluster
use App\Filament\Clusters\Engagement\Resources\KategoriStakeholderResource\Pages;
use App\Filament\Clusters\Engagement\Resources\KategoriStakeholderResource\RelationManagers;
use App\Models\KategoriStakeholder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Traits\HasResourcePermissions;

class KategoriStakeholderResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'data_induk';
    protected static ?string $model = KategoriStakeholder::class;

    // Assign this resource to the Engagement cluster
    protected static ?string $cluster = Engagement::class;
    protected static ?int $navigationSort = 5;

    // Set a navigation icon from heroicons
    protected static ?string $navigationIcon = 'heroicon-o-tag';

    // Set a label for navigation
    protected static ?string $navigationLabel = 'Kategori Stakeholder';

    // Set a plural label for the model
    protected static ?string $pluralModelLabel = 'Kategori Stakeholder';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form section for better layout
                Forms\Components\Section::make()
                    ->schema([
                        // Input for 'nama_kategori'
                        Forms\Components\TextInput::make('nama_kategori')
                            ->required() // This field is mandatory
                            ->maxLength(255)
                            ->label('Nama Kategori'),

                        // Text area for 'deskripsi'
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(), // Make this field take the full width
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Column for the category name
                Tables\Columns\TextColumn::make('nama_kategori')
                    ->label('Nama Kategori')
                    ->searchable() // Allow searching by this column
                    ->sortable(),

                // Column for the description
                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50) // Limit the text length in the table view
                    ->toggleable(isToggledHiddenByDefault: true), // Hide by default, but can be shown by user

                // Column for creation date
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Hide by default
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
            'index' => Pages\ListKategoriStakeholders::route('/'),
            // 'create' => Pages\CreateKategoriStakeholder::route('/create'),
            // 'edit' => Pages\EditKategoriStakeholder::route('/{record}/edit'),
        ];
    }
}
