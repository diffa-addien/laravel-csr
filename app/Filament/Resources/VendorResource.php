<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VendorResource\Pages;
use App\Filament\Resources\VendorResource\RelationManagers;
use App\Models\Vendor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

use App\Filament\Traits\HasResourcePermissions;

class VendorResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'data_induk';
    protected static ?string $model = Vendor::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Data Induk';
    protected static ?string $navigationLabel = 'Vendor';
    protected static ?string $pluralModelLabel = 'Data Vendor';
    protected static ?string $modelLabel = 'Data';
    public static int $navigationGroupSort = 8;
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->label('Nama Vendor Baru')
                    ->placeholder('PT. Contoh')
                    ->maxLength(255),
                Forms\Components\TextInput::make('pimpinan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('ruang_lingkup')
                    ->label('Lingkup Pekerjaan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('alamat')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Vendor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pimpinan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ruang_lingkup')
                    ->label('Lingkup Pekerjaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('alamat')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
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
            'index' => Pages\ListVendors::route('/'),
            // 'create' => Pages\CreateVendor::route('/create'),
            // 'edit' => Pages\EditVendor::route('/{record}/edit'),
        ];
    }
}
