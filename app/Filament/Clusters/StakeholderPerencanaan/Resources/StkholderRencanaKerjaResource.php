<?php

namespace App\Filament\Clusters\StakeholderPerencanaan\Resources;

use App\Filament\Clusters\StakeholderPerencanaan;
use App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderRencanaKerjaResource\Pages;
use App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderRencanaKerjaResource\RelationManagers;
use App\Models\StkholderRencanaKerja;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Traits\HasResourcePermissions;

class StkholderRencanaKerjaResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'stakeholder';
    protected static ?string $model = StkholderRencanaKerja::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationLabel = 'Rencana Kerja';
    protected static ?string $pluralModelLabel = 'Rencana Kerja';
    protected static ?int $navigationSort = 4;
    protected static ?string $modelLabel = 'Data';

    protected static ?string $cluster = StakeholderPerencanaan::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kegiatan_id')
                    ->label('Untuk Kegiatan Program')
                    ->relationship('kegiatan', 'kegiatan', fn($query) => $query->with(['regional', 'program'])->selectRaw('stkholder_perencanaan_program_anggarans.id, CONCAT(stkholder_perencanaan_program_anggarans.kegiatan, " (", regionals.nama_regional, " - ", stkholder_perencanaan_ppks.nama, ")") as kegiatan')->join('regionals', 'stkholder_perencanaan_program_anggarans.regional_id', '=', 'regionals.id')->join('stkholder_perencanaan_ppks', 'stkholder_perencanaan_program_anggarans.program_id', '=', 'stkholder_perencanaan_ppks.id'))
                    ->required()
                    ->searchable()
                    ->columnSpanFull()
                    ->preload(),
                DatePicker::make('tanggal_mulai')
                    ->required(),
                DatePicker::make('tanggal_selesai')
                    ->required()
                    ->afterOrEqual('tanggal_mulai'),
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
                    ->formatStateUsing(fn ($record) => "{$record->kegiatan->kegiatan} ({$record->kegiatan->regional->nama_regional} - {$record->kegiatan->program->nama})")
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tanggal_mulai')
                    ->date()
                    ->sortable(),
                TextColumn::make('tanggal_selesai')
                    ->date()
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
            'index' => Pages\ListStkholderRencanaKerjas::route('/'),
            // 'create' => Pages\CreateStkholderRencanaKerja::route('/create'),
            // 'edit' => Pages\EditStkholderRencanaKerja::route('/{record}/edit'),
        ];
    }
}
