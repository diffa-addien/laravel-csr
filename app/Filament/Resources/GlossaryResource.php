<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GlossaryResource\Pages;
use App\Models\Glossary;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use App\Filament\Traits\HasResourcePermissions;


class GlossaryResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'data_induk';
    protected static ?string $model = Glossary::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Data Induk';
    // protected static ?string $navigationParentItem = 'Wilayah';
    protected static ?string $navigationLabel = 'Glossary';
    protected static ?string $pluralModelLabel = 'Data Glossary';
    protected static ?string $modelLabel = 'Data';
    protected static ?int $navigationSort = 99; // Memastikan urutan terakhir

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_glossary')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('keterangan')
                    ->nullable()
                    ->columnSpanFull()
                    ->disableToolbarButtons([
                        'attachFiles',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_glossary')
                ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->limit(50)
                    ->formatStateUsing(fn(?string $state): string => strip_tags($state ?? ''))
                    ->tooltip("Klik untuk melihat keterangan lengkap")
                    ->toggleable(),
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
            'index' => Pages\ListGlossaries::route('/'),
            // 'create' => Pages\CreateGlossary::route('/create'),
            // 'edit' => Pages\EditGlossary::route('/{record}/edit'),
        ];
    }
}