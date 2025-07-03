<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegionalResource\Pages;
use App\Models\Regional;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use App\Filament\Traits\HasResourcePermissions;

class RegionalResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'data_induk';
    protected static ?string $model = Regional::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'Data Induk';
    // protected static ?string $navigationParentItem = 'Wilayah';
    protected static ?string $navigationLabel = 'Regional';
    protected static ?string $pluralModelLabel = 'Data Regional';
    protected static ?string $modelLabel = 'Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_regional')
                    ->label('Nama Regional')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('pimpinan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('visi')
                    ->nullable(),
                Forms\Components\Textarea::make('misi')
                    ->nullable(),
                Forms\Components\Textarea::make('tujuan')
                    ->nullable()
                    ->columnSpanFull(),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_regional')
                    ->label('Nama Regional')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pimpinan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tujuan')
                    ->searchable()
                    ->limit(50),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Semua'),
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
            'index' => Pages\ListRegionals::route('/'),
            // 'create' => Pages\CreateRegional::route('/create'),
            // 'edit' => Pages\EditRegional::route('/{record}/edit'),
        ];
    }
}