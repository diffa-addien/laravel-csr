<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KuadranResource\Pages;
use App\Models\Kuadran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KuadranResource extends Resource
{
    protected static ?string $model = Kuadran::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2'; // Icon yang relevan

    protected static ?string $navigationGroup = 'Data Induk';
    // protected static ?string $navigationParentItem = 'Wilayah';
    protected static ?string $navigationLabel = 'Kuadran Pemangku Kepentingan';
    protected static ?string $pluralModelLabel = 'Data Kuadran';
    protected static ?string $modelLabel = 'Data';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_kuadran')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Kuadran'), // Label yang lebih ramah pengguna
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
                Tables\Columns\TextColumn::make('nama_kuadran')
                    ->searchable() // Agar bisa dicari
                    ->sortable()
                    ->label('Nama Kuadran'),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->searchable()
                    ->formatStateUsing(fn(?string $state): string => strip_tags($state ?? ''))
                     ->tooltip("Klik untuk melihat deskripsi lengkap") // Petunjuk tambahan
                    ->limit(50),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Sembunyikan secara default

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
            'index' => Pages\ListKuadrans::route('/'),
            // 'create' => Pages\CreateKuadran::route('/create'),
            // 'edit' => Pages\EditKuadran::route('/{record}/edit'),
        ];
    }
}