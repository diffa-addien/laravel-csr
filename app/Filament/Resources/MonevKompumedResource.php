<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonevKompumedResource\Pages;
use App\Filament\Resources\MonevKompumedResource\RelationManagers;
use App\Models\MonevKompumed;
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
use Filament\Tables\Columns\TextColumn;

class MonevKompumedResource extends Resource
{
    protected static ?string $model = KompumedKegiatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Monitoring dan Evaluasi';
    protected static ?string $navigationLabel = 'Komunikasi, Publikasi, Dan Media';
    protected static ?string $pluralModelLabel = 'Evaluasi Anggaran Program';
    protected static ?string $modelLabel = 'Data';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nilai_evaluasi')
                    ->label('Nilai Evaluasi')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(fn($record) => $record->MonevKompumed->nilai_evaluasi ?? null)
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Kegiatan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('program.nama')
                    ->label('Program')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('total_anggaran')
                    ->label('Anggaran')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('monevKompumed.nilai_evaluasi')
                    ->label('Evaluasi')
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
                        $monev = MonevKompumed::where('anggaran_id', $record->id)->first();
                        if ($monev) {
                            $monev->update(['nilai_evaluasi' => $data['nilai_evaluasi']]);
                        } else {
                            MonevKompumed::create([
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
            'index' => Pages\ListMonevKompumeds::route('/'),
            // 'create' => Pages\CreateMonevKompumed::route('/create'),
            // 'view' => Pages\ViewMonevKompumed::route('/{record}'),
            // 'edit' => Pages\EditMonevKompumed::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
