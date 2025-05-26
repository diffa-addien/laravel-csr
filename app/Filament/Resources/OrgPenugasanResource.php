<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Organisasi;
use App\Filament\Resources\OrgPenugasanResource\Pages;
use App\Filament\Resources\OrgPenugasanResource\RelationManagers;
use App\Models\OrgPenugasan;
use App\Models\OrgProfil;
use App\Models\Regional;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class OrgPenugasanResource extends Resource
{
    protected static ?string $model = OrgPenugasan::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Data Induk';
    protected static ?string $navigationParentItem = 'Regional';
    protected static ?string $navigationLabel = 'Penugasan Regional';
    protected static ?string $modelLabel = 'Data';
    protected static ?string $pluralModelLabel = 'Data Penugasan';

    protected static ?int $navigationSort = 2;

    public static function getPluralModelLabel(): string
    {
        return 'Penugasan Regional';
    }

    public static function form(Form $form): Form
    {
        $profil = OrgProfil::first();
        $jabatanOptions = [
            1 => $profil->lv1 ?? $profil->lv1,
            2 => $profil->lv2 ?? $profil->lv2,
            3 => $profil->lv3 ?? $profil->lv3,
        ];

        return $form
            ->schema([
                Select::make('regional_id')
                    ->label('Regional')
                    ->relationship('regional', 'nama_regional') // Pastikan 'nama' ada di model Regional
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('petugas')
                    ->required()
                    ->maxLength(255),
                Select::make('jabatan')
                    ->options($jabatanOptions)
                    ->required(),
                Textarea::make('keterangan')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        $profil = OrgProfil::first();
        return $table
            ->columns([
                TextColumn::make('regional.nama_regional')
                    ->label('Regional')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('petugas')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('jabatan')
                    ->formatStateUsing(function ($state) use ($profil) {
                        return match ($state) {
                            1 => $profil->lv1 ?? $profil->lv1,
                            2 => $profil->lv2 ?? $profil->lv2,
                            3 => $profil->lv3 ?? $profil->lv3,
                            default => 'Unknown',
                        };
                    })
                    ->sortable(),
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
            'index' => Pages\ListOrgPenugasans::route('/'),
            // 'create' => Pages\CreateOrgPenugasan::route('/create'),
            // 'edit' => Pages\EditOrgPenugasan::route('/{record}/edit'),
        ];
    }
}
