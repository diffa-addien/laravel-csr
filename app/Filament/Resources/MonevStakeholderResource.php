<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonevStakeholderResource\Pages;
use App\Filament\Resources\MonevStakeholderResource\RelationManagers;
use App\Models\StkholderPerencanaanProgramAnggaran;
use App\Models\MonevStakeholder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Traits\HasResourcePermissions;

class MonevStakeholderResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'monev';
    protected static ?string $model = StkholderPerencanaanProgramAnggaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Monitoring dan Evaluasi';
    protected static ?string $navigationLabel = 'Pemangku Kepentingan';
    protected static ?string $pluralModelLabel = 'Evaluasi Anggaran Program';
    protected static ?string $modelLabel = 'Data';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nilai_evaluasi')
                    ->label('Nilai Evaluasi')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(fn($record) => $record->monevStakeholder->nilai_evaluasi ?? null)
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kegiatan')
                    ->label('Kegiatan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('program.nama')
                    ->label('Program')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('anggaran_kesepakatan')
                    ->label('Kesepakatan Anggaran')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('monevStakeholder.nilai_evaluasi')
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
                        $monev = MonevStakeholder::where('anggaran_id', $record->id)->first();
                        if ($monev) {
                            $monev->update(['nilai_evaluasi' => $data['nilai_evaluasi']]);
                        } else {
                            MonevStakeholder::create([
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMonevStakeholders::route('/'),
            // 'create' => Pages\CreateMonevStakeholder::route('/create'),
            'view' => Pages\ViewMonevStakeholder::route('/{record}'),
            // 'edit' => Pages\EditMonevStakeholder::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
