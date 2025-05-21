<?php

namespace App\Filament\Clusters\KompumedPerencanaan\Resources;

use App\Filament\Clusters\KompumedPerencanaan;
use App\Filament\Clusters\KompumedPerencanaan\Resources\KompumedKegiatanAnggaranResource\Pages;
use App\Filament\Clusters\KompumedPerencanaan\Resources\KompumedKegiatanAnggaranResource\RelationManagers;
use App\Models\KompumedKegiatanAnggaran;
use App\Models\KompumedKegiatan;
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

class KompumedKegiatanAnggaranResource extends Resource
{
    protected static ?string $model = KompumedKegiatanAnggaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Data Anggaran';
    protected static ?string $pluralModelLabel = 'Data Anggaran';
    protected static ?string $modelLabel = 'Anggaran';
    protected static ?int $navigationSort = 3;

    protected static ?string $cluster = KompumedPerencanaan::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kegiatan_id')
                    ->label('Kegiatan')
                    ->options(function () {
                        return KompumedKegiatan::query()
                            ->with(['regional', 'program'])
                            ->selectRaw('kompumed_kegiatans.id, CONCAT(kompumed_kegiatans.nama, " (", regionals.nama_regional, " - ", kompumed_rencana_programs.nama, ")") as kegiatan')
                            ->join('regionals', 'kompumed_kegiatans.regional_id', '=', 'regionals.id')
                            ->join('kompumed_rencana_programs', 'kompumed_kegiatans.program_id', '=', 'kompumed_rencana_programs.id')
                            ->orderBy('kompumed_kegiatans.nama') // Atur pengurutan berdasarkan kolom yang sesuai
                            ->pluck('kegiatan', 'id');
                    })
                    ->required()
                    ->searchable()
                    ->preload(),
                Textarea::make('deskripsi')
                    ->nullable()
                    ->columnSpanFull(),
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kegiatan.nama')
                    ->label('Kegiatan')
                    ->formatStateUsing(fn($record) => "{$record->kegiatan->nama} ({$record->kegiatan->regional->nama_regional} - {$record->kegiatan->program->nama})")
                    ->sortable()
                    ->limit(40)
                    ->searchable(),
                TextColumn::make('deskripsi')
                    ->limit(50)
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
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListKompumedKegiatanAnggarans::route('/'),
            // 'create' => Pages\CreateKompumedKegiatanAnggaran::route('/create'),
            // 'edit' => Pages\EditKompumedKegiatanAnggaran::route('/{record}/edit'),
        ];
    }
}
