<?php

namespace App\Filament\Clusters\StakeholderPelaksanaan\Resources;

use App\Filament\Clusters\StakeholderPelaksanaan;
use App\Filament\Clusters\StakeholderPelaksanaan\Resources\StkholderPelaksanaanPpkResource\Pages;
use App\Filament\Clusters\StakeholderPelaksanaan\Resources\StkholderPelaksanaanPpkResource\RelationManagers;
use App\Models\StkholderPelaksanaanPpk;
use App\Models\StkholderPerencanaanProgramAnggaran;
use App\Models\StkholderProfilExternal;
use App\Models\StkholderProfilInternal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;

class StkholderPelaksanaanPpkResource extends Resource
{
    protected static ?string $model = StkholderPelaksanaanPpk::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Kegiatan PPK';
    protected static ?string $pluralModelLabel = 'Kegiatan PPK';
    protected static ?string $modelLabel = 'Data';

    protected static ?string $cluster = StakeholderPelaksanaan::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Kegiatan Details')
                    ->schema([
                        Select::make('kegiatan_id')
                            ->label('Kegiatan')
                            ->required()
                            ->relationship('kegiatan', 'kegiatan', fn($query) => $query->with(['regional', 'program'])->selectRaw('stkholder_perencanaan_program_anggarans.id, CONCAT(stkholder_perencanaan_program_anggarans.kegiatan, " (", regionals.nama_regional, " - ", stkholder_perencanaan_ppks.nama, ")") as kegiatan')->join('regionals', 'stkholder_perencanaan_program_anggarans.regional_id', '=', 'regionals.id')->join('stkholder_perencanaan_ppks', 'stkholder_perencanaan_program_anggarans.program_id', '=', 'stkholder_perencanaan_ppks.id'))
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Pelaksana Details')
                    ->schema([
                        Select::make('pelaksana')
                            ->label('Pelaksana')
                            ->required()
                            ->options(function () {
                                $externals = StkholderProfilExternal::whereNotNull('nama')
                                    ->selectRaw('id, CONCAT("[External] ", nama) as pelaksana')
                                    ->get()
                                    ->mapWithKeys(fn($item) => ['ext_' . $item->id => $item->pelaksana])
                                    ->toArray();

                                $internals = StkholderProfilInternal::whereNotNull('nama')
                                    ->selectRaw('id, CONCAT("[Internal] ", nama) as pelaksana')
                                    ->get()
                                    ->mapWithKeys(fn($item) => ['int_' . $item->id => $item->pelaksana])
                                    ->toArray();

                                $options = array_merge($internals, $externals);

                                return $options;
                            })
                            ->searchable()
                            ->live() // Gunakan live() daripada afterStateUpdated untuk reactivity yang lebih baik
                            ->afterStateHydrated(function (Select $component, $state, $record) {
                                // Pastikan state terisi dengan nilai dari record jika ada
                                if ($record && blank($state)) {
                                    $component->state($record->pelaksana_key);
                                }
                            })
                            ->dehydrated(false)
                            ->columnSpanFull()
                            ->afterStateUpdated(function ($state, callable $set) {

                                if ($state) {
                                    // Pecah prefix dan id
                                    [$prefix, $id] = explode('_', $state, 2);

                                    if ($prefix === 'ext') {
                                        $modelClass = \App\Models\StkholderProfilExternal::class;
                                    } elseif ($prefix === 'int') {
                                        $modelClass = \App\Models\StkholderProfilInternal::class;
                                    } else {
                                        return; // Prefix tidak valid
                                    }

                                    // Set data relasional
                                    $set('pelaksana_id', $id);
                                    $set('pelaksana_type', $modelClass);
                                }
                            }),
                        Hidden::make('pelaksana_id'),
                        Hidden::make('pelaksana_type'),
                    ]),
                Section::make('Pelaksanaan Details')
                    ->schema([
                        Select::make('coverage')
                            ->label('Coverage')
                            ->options([
                                'desa' => 'Desa',
                                'kecamatan' => 'Kecamatan',
                                'kabupaten' => 'Kabupaten',
                                'provinsi' => 'Provinsi',
                            ])
                            ->nullable(),
                        TextInput::make('kategori')
                            ->label('Kategori')
                            ->nullable(),
                        TextInput::make('karakter')
                            ->label('Karakter')
                            ->nullable(),
                        TextInput::make('biaya')
                            ->label('Biaya')
                            ->numeric()
                            ->prefix('Rp')
                            ->nullable(),
                        DatePicker::make('tanggal_pelaksanaan')
                            ->label('Tanggal Pelaksanaan')
                            ->nullable(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kegiatan.kegiatan')
                    ->label('Kegiatan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('kegiatan.program.nama')
                    ->label('Program')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('pelaksana.nama')
                    ->label('Pelaksana')
                    ->formatStateUsing(function ($state, $record) {
                        $prefix = '';

                        if ($record->pelaksana_type === \App\Models\StkholderProfilInternal::class) {
                            $prefix = '[Internal] ';
                        } elseif ($record->pelaksana_type === \App\Models\StkholderProfilExternal::class) {
                            $prefix = '[External] ';
                        }

                        return $prefix . $state;
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('coverage')
                    ->label('Coverage')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('biaya')
                    ->label('Biaya')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                TextColumn::make('tanggal_pelaksanaan')
                    ->label('Tanggal Pelaksanaan')
                    ->date()
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
            'index' => Pages\ListStkholderPelaksanaanPpks::route('/'),
            'create' => Pages\CreateStkholderPelaksanaanPpk::route('/create'),
            'edit' => Pages\EditStkholderPelaksanaanPpk::route('/{record}/edit'),
        ];
    }
}
