<?php

namespace App\Filament\Clusters\StakeholderProfil\Resources;

use App\Filament\Clusters\StakeholderProfil;
use App\Filament\Clusters\StakeholderProfil\Resources\StkholderProfilInternalResource\Pages;
use App\Filament\Clusters\StakeholderProfil\Resources\StkholderProfilInternalResource\RelationManagers;
use App\Models\StkholderProfilInternal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class StkholderProfilInternalResource extends Resource
{
    protected static ?string $model = StkholderProfilInternal::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = "Profil Internal";
    protected static ?string $pluralModelLabel = 'Profil Internal';
    protected static ?string $modelLabel = 'Data';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = StakeholderProfil::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('jabatan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('keterangan')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jabatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable()
                    ->limit(50),
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
            'index' => Pages\ListStkholderProfilInternals::route('/'),
            // 'create' => Pages\CreateStkholderProfilInternal::route('/create'),
            // 'edit' => Pages\EditStkholderProfilInternal::route('/{record}/edit'),
        ];
    }
}
