<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BidangResource\Pages;
use App\Filament\Resources\BidangResource\RelationManagers;
use App\Models\Bidang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class BidangResource extends Resource
{
    protected static ?string $model = Bidang::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string $navigationGroup = 'Data Induk';
    protected static ?string $navigationLabel = 'Bidang';
    protected static ?string $pluralModelLabel = 'Bidang';
    protected static ?string $modelLabel = 'Bidang';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_bidang')
                    ->required()
                    ->maxLength(255)
                    ->unique(Bidang::class, 'kode_bidang', ignoreRecord: true),
                TextInput::make('nama_bidang')
                    ->required()
                    ->maxLength(255),
                Textarea::make('keterangan')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_bidang')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nama_bidang')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('keterangan')
                    ->limit(50)
                    ->searchable(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBidangs::route('/'),
            // 'create' => Pages\CreateBidang::route('/create'),
            // 'edit' => Pages\EditBidang::route('/{record}/edit'),
        ];
    }
}
