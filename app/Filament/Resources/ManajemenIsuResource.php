<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ManajemenIsuResource\Pages;
use App\Models\ManajemenIsu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ManajemenIsuResource extends Resource
{
    protected static ?string $model = ManajemenIsu::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right'; // Icon yang sesuai

    protected static ?string $navigationLabel = 'Manajemen Isu'; // Label navigasi yang jelas
    protected static ?string $pluralModelLabel = 'Manajemen Isu'; // Label jamak
    protected static ?string $navigationGroup = 'Data Induk';
    protected static ?string $modelLabel = 'Data';
    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_isu')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Isu'),
                    
                Forms\Components\RichEditor::make('deskripsi')
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
                Tables\Columns\TextColumn::make('nama_isu')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Isu'),

                Tables\Columns\TextColumn::make('deskripsi')
                    ->searchable()
                    ->formatStateUsing(fn(?string $state): string => strip_tags($state ?? ''))
                     ->tooltip("Klik untuk melihat deskripsi lengkap") // Petunjuk tambahan
                    ->limit(50),

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
            'index' => Pages\ListManajemenIsus::route('/'),
            // 'create' => Pages\CreateManajemenIsu::route('/create'),
            // 'edit' => Pages\EditManajemenIsu::route('/{record}/edit'),
        ];
    }
}