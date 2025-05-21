<?php

namespace App\Filament\Clusters\StakeholderPerencanaan\Resources;

use App\Filament\Clusters\StakeholderPerencanaan;
use App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderRincianAnggaranResource\Pages;
use App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderRincianAnggaranResource\RelationManagers;
use App\Models\StkholderRincianAnggaran;
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


class StkholderRincianAnggaranResource extends Resource
{
    protected static ?string $model = StkholderRincianAnggaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationLabel = 'Rincian Anggaran';
    protected static ?string $pluralModelLabel = 'Rincian Anggaran';
    protected static ?string $modelLabel = 'Data';

    protected static ?string $cluster = StakeholderPerencanaan::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kegiatan_id')
                    ->label('Kegiatan')
                    ->relationship('kegiatan', 'kegiatan', fn($query) => $query->with(['regional', 'program'])->selectRaw('stkholder_perencanaan_program_anggarans.id, CONCAT(stkholder_perencanaan_program_anggarans.kegiatan, " (", regionals.nama_regional, " - ", stkholder_perencanaan_ppks.nama, ")") as kegiatan')->join('regionals', 'stkholder_perencanaan_program_anggarans.regional_id', '=', 'regionals.id')->join('stkholder_perencanaan_ppks', 'stkholder_perencanaan_program_anggarans.program_id', '=', 'stkholder_perencanaan_ppks.id'))
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('pelaksana_id')
                    ->label('Pelaksana')
                    ->relationship('pelaksana', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('frekuensi')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                Select::make('frekuensi_unit')
                    ->label('Satuan Frekuensi')
                    ->options([
                        'hari' => 'Hari',
                        'minggu' => 'Minggu',
                        'bulan' => 'Bulan',
                    ])
                    ->default('hari')
                    ->required(),
                TextInput::make('biaya')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->columnSpanFull()
                    ->dehydrateStateUsing(fn($state) => str_replace(['Rp', '.', ' '], '', $state)),
                TextInput::make('kuantitas')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                Select::make('kuantitas_unit')
                    ->label('Satuan Kuantitas')
                    ->options([
                        'unit' => 'Unit',
                        'orang' => 'Orang',
                        'item' => 'Item',
                    ])
                    ->default('unit')
                    ->required(),
                Textarea::make('keterangan')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kegiatan.kegiatan')
                    ->label('Kegiatan')
                    ->formatStateUsing(fn($record) => "{$record->kegiatan->kegiatan} ({$record->kegiatan->regional->nama_regional} - {$record->kegiatan->program->nama})")
                    ->sortable()
                    ->searchable(),
                TextColumn::make('pelaksana.nama')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('frekuensi')
                    ->formatStateUsing(fn ($record) => "{$record->frekuensi} " . ucfirst($record->frekuensi_unit))
                    ->sortable(),
                TextColumn::make('biaya')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                TextColumn::make('kuantitas')
                    ->formatStateUsing(fn ($record) => "{$record->kuantitas} " . ucfirst($record->kuantitas_unit))
                    ->sortable(),
                TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->getStateUsing(fn ($record) => $record->biaya * $record->kuantitas)
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                TextColumn::make('keterangan')
                    ->limit(50)
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListStkholderRincianAnggarans::route('/'),
            // 'create' => Pages\CreateStkholderRincianAnggaran::route('/create'),
            // 'edit' => Pages\EditStkholderRincianAnggaran::route('/{record}/edit'),
        ];
    }
}
