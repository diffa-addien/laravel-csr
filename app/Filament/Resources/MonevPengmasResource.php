<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonevPengmasResource\Pages;
use App\Filament\Resources\MonevPengmasResource\RelationManagers;
use App\Models\MonevPengmas;
use App\Models\PengmasRencanaProgramAnggaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class MonevPengmasResource extends Resource
{
    protected static ?string $model = PengmasRencanaProgramAnggaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';
    protected static ?string $navigationGroup = 'Monitoring dan Evaluasi';
    protected static ?string $navigationLabel = 'Pengembangan Masyarakat';
    protected static ?string $pluralModelLabel = 'Evaluasi Anggaran Program';
    protected static ?string $modelLabel = 'Data';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nilai_evaluasi')
                    ->label('Nilai Evaluasi')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(fn($record) => $record->monevPengmas->nilai_evaluasi ?? null)
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_program')
                    ->label('Kegiatan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('keterangan')
                    ->label('Program')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('kesepakatan_anggaran')
                    ->label('Kesepakatan Anggaran')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('monevPengmas.nilai_evaluasi')
                    ->label('Nilai Evaluasi')
                    ->sortable()
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Evaluasi')
                    ->action(function ($data, $record) {
                        $monev = MonevPengmas::where('anggaran_id', $record->id)->first();
                        if ($monev) {
                            $monev->update(['nilai_evaluasi' => $data['nilai_evaluasi']]);
                        } else {
                            MonevPengmas::create([
                                'anggaran_id' => $record->id,
                                'nilai_evaluasi' => $data['nilai_evaluasi'],
                            ]);
                        }
                    }),
                Tables\Actions\ViewAction::make()->label('Monitoring'),
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
            'index' => Pages\ListMonevPengmas::route('/'),
            'create' => Pages\CreateMonevPengmas::route('/create'),
            'view' => Pages\ViewMonevPengmas::route('/{record}'),
            // 'edit' => Pages\EditMonevPengmas::route('/{record}/edit'),
        ];
    }
}
