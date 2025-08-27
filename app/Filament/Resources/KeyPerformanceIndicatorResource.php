<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KeyPerformanceIndicatorResource\Pages;
use App\Filament\Resources\KeyPerformanceIndicatorResource\RelationManagers;
use App\Models\KeyPerformanceIndicator;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput; // Import TextInput
use Filament\Tables\Columns\TextColumn;
use App\Filament\Traits\HasResourcePermissions;


class KeyPerformanceIndicatorResource extends Resource
{

    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'data_induk';

    protected static ?string $model = KeyPerformanceIndicator::class;
    protected static ?string $navigationGroup = 'Data Induk';

    protected static ?string $navigationLabel = 'KPI';
    protected static ?string $pluralModelLabel = 'Data Key Performance Indicator';
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?int $navigationSort = 99; 

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kategori')
                    ->required() // Wajib diisi
                    ->maxLength(255)
                    ->label('Kategori KPI'),

                TextInput::make('metrik')
                    ->required()
                    ->maxLength(255)
                    ->label('Metrik atau Nama KPI'),

                TextInput::make('ukuran')
                    ->required()
                    ->maxLength(255)
                    ->label('Satuan Ukuran')
                    ->placeholder('Contoh: %, Poin, Jam, Rupiah'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kategori')
                    ->searchable() // Aktifkan pencarian untuk kolom ini
                    ->sortable(),  // Aktifkan pengurutan untuk kolom ini

                TextColumn::make('metrik')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('ukuran')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->dateTime('d-M-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Sembunyikan default, bisa ditampilkan
            ])
            ->filters([
                // Filter bisa ditambahkan di sini jika perlu
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


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKeyPerformanceIndicators::route('/'),
            // 'create' => Pages\CreateKeyPerformanceIndicator::route('/create'),
            // 'edit' => Pages\EditKeyPerformanceIndicator::route('/{record}/edit'),
        ];
    }
}
