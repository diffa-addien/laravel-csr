<?php

namespace App\Filament\Clusters\Wilayah\Resources;

use App\Filament\Clusters\Wilayah;
use App\Filament\Clusters\Wilayah\Resources\DesaResource\Pages;
use App\Filament\Clusters\Wilayah\Resources\DesaResource\RelationManagers;
use App\Models\Desa;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Filament\Traits\HasResourcePermissions;

class DesaResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'data_induk';
    protected static ?string $model = Desa::class;

    protected static ?string $navigationIcon = 'heroicon-o-ellipsis-horizontal-circle';
    protected static ?string $cluster = Wilayah::class;
    protected static ?int $navigationSort = 12;
    protected static ?string $modelLabel = 'Data';
    protected static ?string $navigationLabel = 'Desa / Kelurahan';

    public static function getPluralModelLabel(): string
    {
        return 'Desa / Kelurahan';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_desa')
                    ->label('Nama Desa / Kelurahan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('kepala_desa')
                    ->label('Kepala Desa')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('provinsi_id')
                    ->label('Provinsi')
                    ->options(Provinsi::pluck('nama_provinsi', 'id')->toArray())
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set) {
                        $set('kabupaten_id', null);
                        $set('id_kecamatan', null);
                    })
                    ->afterStateHydrated(function (Select $component, $state) use ($form) {
                        $record = $form->getRecord();
                        if ($record && $record->id_kecamatan) {
                            $kecamatan = Kecamatan::with('kabupaten')->find($record->id_kecamatan);
                            if ($kecamatan && $kecamatan->kabupaten) {
                                $component->state($kecamatan->kabupaten->id_provinsi);
                            }
                        }
                    }),
                Forms\Components\Select::make('kabupaten_id')
                    ->label('Kabupaten')
                    ->options(function (callable $get) {
                        $provinsiId = $get('provinsi_id');
                        if (!$provinsiId)
                            return [];
                        return Kabupaten::where('id_provinsi', $provinsiId)->pluck('nama_kab', 'id')->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set) {
                        $set('id_kecamatan', null);
                    })
                    ->afterStateHydrated(function (Select $component, $state) use ($form) {
                        $record = $form->getRecord();
                        if ($record && $record->id_kecamatan) {
                            $kecamatan = Kecamatan::with('kabupaten')->find($record->id_kecamatan);
                            if ($kecamatan) {
                                $component->state($kecamatan->id_kabupaten);
                            }
                        }
                    }),
                Forms\Components\Select::make('id_kecamatan')
                    ->label('Kecamatan')
                    ->options(function (callable $get) {
                        $kabupatenId = $get('kabupaten_id');
                        if (!$kabupatenId) {
                            return [];
                        }
                        return Kecamatan::where('id_kabupaten', $kabupatenId)
                            ->pluck('nama_kec', 'id')
                            ->toArray();
                    })
                    ->required()
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kecamatan.nama_kec')
                    ->label('Kecamatan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_desa')
                    ->label('Desa / Kelurahan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kepala_desa')
                    ->label('Kepala Desa')
                    ->searchable()
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
            'index' => Pages\ListDesas::route('/'),
            // 'create' => Pages\CreateDesa::route('/create'),
            // 'edit' => Pages\EditDesa::route('/{record}/edit'),
        ];
    }
}
