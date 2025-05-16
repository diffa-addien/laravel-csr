<?php

namespace App\Filament\Clusters\StakeholderPelaksanaan\Resources;

use App\Filament\Clusters\StakeholderPelaksanaan;
use App\Filament\Clusters\StakeholderPelaksanaan\Resources\StkholderAnalisisResource\Pages;
use App\Filament\Clusters\StakeholderPelaksanaan\Resources\StkholderAnalisisResource\RelationManagers;
use App\Models\StkholderAnalisis;

use App\Models\StkholderPerencanaanProgramAnggaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;

class StkholderAnalisisResource extends Resource
{
    protected static ?string $model = StkholderAnalisis::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Analisis Program Kerja';
    protected static ?string $pluralModelLabel = 'Analisis Program Kerja';
    protected static ?string $modelLabel = 'Analisis';

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
                Section::make('Analisis Details')
                    ->schema([
                        Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->columnSpanFull(),
                        Textarea::make('target_hasil')
                            ->label('Target Hasil')
                            ->rows(4)
                            ->columnSpanFull(),
                        Textarea::make('indikator_berhasil')
                            ->label('Indikator Berhasil')
                            ->rows(4)
                            ->columnSpanFull(),
                        Textarea::make('asumsi_or_risiko')
                            ->label('Asumsi atau Risiko')
                            ->rows(4)
                            ->columnSpanFull(),
                        Textarea::make('pendukung_hasil')
                            ->label('Pendukung Hasil')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kegiatan.program.nama')
                    ->label('Program')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('kegiatan.kegiatan')
                    ->label('Kegiatan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('target_hasil')
                    ->label('Target Hasil')
                    ->limit(50)
                    ->searchable(),
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
            'index' => Pages\ListStkholderAnalisis::route('/'),
            'create' => Pages\CreateStkholderAnalisis::route('/create'),
            'edit' => Pages\EditStkholderAnalisis::route('/{record}/edit'),
        ];
    }
}
