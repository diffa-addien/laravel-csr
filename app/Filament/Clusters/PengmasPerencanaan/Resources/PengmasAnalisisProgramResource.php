<?php

namespace App\Filament\Clusters\PengmasPerencanaan\Resources;

use App\Filament\Clusters\PengmasPerencanaan;
use App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasAnalisisProgramResource\Pages;
use App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasAnalisisProgramResource\RelationManagers;
use App\Models\PengmasAnalisisProgram;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class PengmasAnalisisProgramResource extends Resource
{
    protected static ?string $model = PengmasAnalisisProgram::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass-circle';
    protected static ?string $navigationLabel = 'Analisis Program';
    protected static ?string $pluralModelLabel = 'Analisis Program Kerja';
    protected static ?string $modelLabel = 'Data';
    protected static ?int $navigationSort = 3;

    protected static ?string $cluster = PengmasPerencanaan::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('id_strategi')
                    ->label('Strategi')
                    ->relationship('dariStrategi', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('id_program')
                    ->label('Dari Program')
                    ->relationship('dariProgram', 'nama_program', fn ($query) => $query->with(['bidang'])->selectRaw('pengmas_rencana_program_anggarans.id, CONCAT(bidangs.nama_bidang, " - ", pengmas_rencana_program_anggarans.nama_program) as nama_program')->join('bidangs', 'pengmas_rencana_program_anggarans.bidang_id', '=', 'bidangs.id'))
                    ->required()
                    ->searchable()
                    ->preload(),
                Textarea::make('target_hasil')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('indikator_berhasil')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('asumsi_or_risiko')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('pendukung_hasil')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dariStrategi.nama')
                    ->label('Strategi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('dariProgram.nama_program')
                    ->label('Program')
                    ->formatStateUsing(fn ($record) => "{$record->dariProgram->bidang->nama_bidang} - {$record->dariProgram->nama_program}")
                    ->sortable()
                    ->searchable(),
                TextColumn::make('target_hasil')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('indikator_berhasil')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('asumsi_or_risiko')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('pendukung_hasil')
                    ->limit(50)
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
            'index' => Pages\ListPengmasAnalisisPrograms::route('/'),
        //     'create' => Pages\CreatePengmasAnalisisProgram::route('/create'),
        //     'edit' => Pages\EditPengmasAnalisisProgram::route('/{record}/edit'),
        ];
    }
}
