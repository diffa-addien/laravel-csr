<?php

namespace App\Filament\Clusters\Sdg\Resources;

use App\Filament\Clusters\Sdg;
use App\Filament\Clusters\Sdg\Resources\SdgIndikatorResource\Pages;
use App\Filament\Clusters\Sdg\Resources\SdgIndikatorResource\RelationManagers;
use App\Models\SdgIndikator;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class SdgIndikatorResource extends Resource
{
    protected static ?string $model = SdgIndikator::class;

    protected static ?string $navigationIcon = 'heroicon-o-viewfinder-circle';
    protected static ?string $navigationLabel = 'Indikator SDGs';
    protected static ?string $pluralModelLabel = 'Data Indikator SDGs';
    protected static ?string $modelLabel = 'Data';
    protected static ?int $navigationSort = 3;

    protected static ?string $cluster = Sdg::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('target_id')
                    ->relationship('dariTarget', 'no_target')
                    ->required(),
                Forms\Components\TextInput::make('no_indikator')
                    ->required()
                    ->maxLength(255)
                    ->unique(
                        SdgIndikator::class, // Nama class model
                        'no_indikator',      // Nama kolom yang ingin di-unique-kan
                        fn(?Model $record): ?Model => $record, // Abaikan record saat ini saat edit
                    ),
                Forms\Components\Textarea::make('nama_indikator')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(1000),
                Forms\Components\Textarea::make('keterangan')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('dariTarget.no_target')
                    ->label('No. Target'),
                Tables\Columns\TextColumn::make('no_indikator')
                    ->label('No.'),
                Tables\Columns\TextColumn::make('nama_indikator')
                    ->limit(30),
                Tables\Columns\TextColumn::make('keterangan')
                    ->limit(50),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSdgIndikators::route('/'),
            // 'create' => Pages\CreateSdgIndikator::route('/create'),
            // 'edit' => Pages\EditSdgIndikator::route('/{record}/edit'),
        ];
    }
}
