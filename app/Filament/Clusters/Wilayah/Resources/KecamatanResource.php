<?php

namespace App\Filament\Clusters\Wilayah\Resources;

use App\Filament\Clusters\Wilayah;
use App\Filament\Clusters\Wilayah\Resources\KecamatanResource\Pages;
use App\Filament\Clusters\Wilayah\Resources\KecamatanResource\RelationManagers;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Filament\Traits\HasResourcePermissions;
class KecamatanResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'data_induk';
    protected static ?string $model = Kecamatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-ellipsis-horizontal-circle';

    protected static ?string $navigationLabel = 'Kecamatan';
    protected static ?string $modelLabel = 'Data';

    protected static ?int $navigationSort = 10;

    protected static ?string $cluster = Wilayah::class;

    public static function getPluralModelLabel(): string
    {
        return 'Kecamatan';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_kec')
                    ->label('Nama Kecamatan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('camat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('provinsi_id')
                    ->label('Provinsi')
                    ->options(function () {
                        return \App\Models\Provinsi::pluck('nama_provinsi', 'id')->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set) {
                        $set('id_kabupaten', null);
                    })
                    ->afterStateHydrated(function (Forms\Components\Select $component, $state) use ($form) {
                        // Ambil id_kabupaten saat form di-edit
                        $kabupatenId = $form->getRecord()->id_kabupaten ?? null;

                        if ($kabupatenId) {
                            $provinsiId = Kabupaten::where('id', $kabupatenId)->value('id_provinsi');
                            $component->state($provinsiId);
                        }
                    }),
                Forms\Components\Select::make('id_kabupaten')
                    ->label('Kabupaten')
                    ->options(function (callable $get) {
                        $provinsiId = $get('provinsi_id');
                        if (!$provinsiId) {
                            return [];
                        }
                        return Kabupaten::where('id_provinsi', $provinsiId)
                            ->pluck('nama_kab', 'id')
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
                Tables\Columns\TextColumn::make('kabupaten.nama_kab')
                    ->label('Kabupaten / Kota')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_kec')
                    ->label('Kecamatan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('camat')
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
            'index' => Pages\ListKecamatans::route('/'),
            // 'create' => Pages\CreateKecamatan::route('/create'),
            // 'edit' => Pages\EditKecamatan::route('/{record}/edit'),
        ];
    }
}
