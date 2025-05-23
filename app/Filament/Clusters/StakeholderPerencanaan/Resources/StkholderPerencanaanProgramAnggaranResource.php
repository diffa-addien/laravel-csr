<?php

namespace App\Filament\Clusters\StakeholderPerencanaan\Resources;

use App\Filament\Clusters\StakeholderPerencanaan;
use App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderPerencanaanProgramAnggaranResource\Pages;
use App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderPerencanaanProgramAnggaranResource\RelationManagers;
use App\Models\StkholderPerencanaanProgramAnggaran;
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

class StkholderPerencanaanProgramAnggaranResource extends Resource
{
    protected static ?string $model = StkholderPerencanaanProgramAnggaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Rencana Program dan Anggaran';
    protected static ?string $pluralModelLabel = 'Rencana Program dan Anggaran';
    protected static ?string $modelLabel = 'Data';

    protected static ?string $cluster = StakeholderPerencanaan::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('regional_id')
                    ->label('Regional')
                    ->relationship('regional', 'nama_regional')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('program_id')
                    ->label('Dari Program PPK')
                    ->relationship('program', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('kegiatan')
                    ->label('Nama Kegiatan')
                    ->placeholder('Nama Kegiatan Program Baru')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                TextInput::make('anggaran_pengajuan')
                    ->label('Pengajuan Anggaran')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->dehydrateStateUsing(fn($state) => str_replace(['Rp', '.', ' '], '', $state)),
                TextInput::make('anggaran_kesepakatan')
                    ->label('Kesepakatan Anggaran')
                    ->numeric()
                    ->prefix('Rp')
                    ->dehydrateStateUsing(fn($state) => $state ? str_replace(['Rp', '.', ' '], '', $state) : null)
                    ->nullable(),
                Textarea::make('keterangan')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('regional.nama_regional')
                    ->label('Regional')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('program.nama')
                    ->label('Program')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('kegiatan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('anggaran_pengajuan')
                    ->label('Pengajuan Anggaran')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                TextColumn::make('anggaran_kesepakatan')
                    ->label('Kesepakatan Anggaran')
                    ->formatStateUsing(fn($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : '-')
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
            'index' => Pages\ListStkholderPerencanaanProgramAnggarans::route('/'),
            // 'create' => Pages\CreateStkholderPerencanaanProgramAnggaran::route('/create'),
            // 'edit' => Pages\EditStkholderPerencanaanProgramAnggaran::route('/{record}/edit'),
        ];
    }
}
