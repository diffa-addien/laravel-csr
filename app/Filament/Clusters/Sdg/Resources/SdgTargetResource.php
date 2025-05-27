<?php

namespace App\Filament\Clusters\Sdg\Resources;

use App\Filament\Clusters\Sdg;
use App\Filament\Clusters\Sdg\Resources\SdgTargetResource\Pages;
use App\Filament\Clusters\Sdg\Resources\SdgTargetResource\RelationManagers;
use App\Models\SdgTarget;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Illuminate\Database\Eloquent\Model;

class SdgTargetResource extends Resource
{
    protected static ?string $model = SdgTarget::class;

    protected static ?string $navigationIcon = 'heroicon-o-cursor-arrow-rays';
    protected static ?string $navigationLabel = 'Target SDGs';
    protected static ?string $pluralModelLabel = 'Data Target SDGs';
    protected static ?string $modelLabel = 'Data';
    protected static ?int $navigationSort = 2;

    protected static ?string $cluster = Sdg::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tujuan_id')
                    ->label('Tujuan')
                    ->options(
                        \App\Models\SdgTujuan::pluck('tujuan', 'id')->toArray()
                    )
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('no_target')
                    ->required()
                    ->maxLength(255)
                    ->unique(
                        SdgTarget::class, // Nama class model
                        'no_target',      // Nama kolom yang ingin di-unique-kan
                        fn(?Model $record): ?Model => $record, // Abaikan record saat ini saat edit
                    ),
                Forms\Components\Textarea::make('target')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('dariTujuan.tujuan')
                    ->label('Tujuan')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_target')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('target')
                    ->limit(50)
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSdgTargets::route('/'),
            // 'create' => Pages\CreateSdgTarget::route('/create'),
            // 'edit' => Pages\EditSdgTarget::route('/{record}/edit'),
        ];
    }
}
