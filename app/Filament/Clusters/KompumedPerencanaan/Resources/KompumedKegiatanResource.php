<?php

namespace App\Filament\Clusters\KompumedPerencanaan\Resources;

use App\Filament\Clusters\KompumedPerencanaan;
use App\Filament\Clusters\KompumedPerencanaan\Resources\KompumedKegiatanResource\Pages;
use App\Filament\Clusters\KompumedPerencanaan\Resources\KompumedKegiatanResource\RelationManagers;
use App\Models\KompumedKegiatan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KompumedKegiatanResource extends Resource
{
    protected static ?string $model = KompumedKegiatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Data Kegiatan';
    protected static ?string $pluralModelLabel = 'Data Kegiatan';
    protected static ?string $modelLabel = 'Kegiatan';

    protected static ?string $cluster = KompumedPerencanaan::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Kegiatan Details')
                    ->schema([
                        Select::make('regional_id')
                            ->label('Regional')
                            ->required()
                            ->relationship('regional', 'nama_regional', fn ($query) => $query->selectRaw('id, nama_regional')->whereNotNull('nama_regional'))
                            ->columnSpanFull(),
                        Select::make('program_id')
                            ->label('Program')
                            ->required()
                            ->relationship('program', 'nama', fn ($query) => $query->selectRaw('id, nama')->whereNotNull('nama'))
                            ->columnSpanFull(),
                        TextInput::make('nama')
                            ->label('Nama Kegiatan')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->rows(4)
                            ->columnSpanFull(),
                        DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->required()
                            ->displayFormat('d/m/Y'),
                        DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->required()
                            ->displayFormat('d/m/Y')
                            ->minDate(fn ($get) => $get('tanggal_mulai')),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('regional.nama_regional')
                    ->label('Regional')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('program.nama')
                    ->label('Program')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nama')
                    ->label('Nama Kegiatan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->date()
                    ->sortable(),
                TextColumn::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
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
            'index' => Pages\ListKompumedKegiatans::route('/'),
            'create' => Pages\CreateKompumedKegiatan::route('/create'),
            'edit' => Pages\EditKompumedKegiatan::route('/{record}/edit'),
        ];
    }
}
