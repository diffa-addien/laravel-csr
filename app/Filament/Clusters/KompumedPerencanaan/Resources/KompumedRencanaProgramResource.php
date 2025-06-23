<?php

namespace App\Filament\Clusters\KompumedPerencanaan\Resources;

use App\Filament\Clusters\KompumedPerencanaan;
use App\Filament\Clusters\KompumedPerencanaan\Resources\KompumedRencanaProgramResource\Pages;
use App\Filament\Clusters\KompumedPerencanaan\Resources\KompumedRencanaProgramResource\RelationManagers;
use App\Models\KompumedRencanaProgram;
use App\Models\TahunFiskal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Placeholder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KompumedRencanaProgramResource extends Resource
{
    protected static ?string $model = KompumedRencanaProgram::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Data Program';
    protected static ?string $pluralModelLabel = 'Data Program';
    protected static ?string $modelLabel = 'Data';
    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = KompumedPerencanaan::class;

    public static function form(Form $form): Form
    {
        return $form
           ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Nama Program Baru'),
                Forms\Components\TextInput::make('keterangan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('tahun_fiskal')
                    ->label('Tahun Fiskal')
                    ->options(
                        TahunFiskal::pluck('nama_tahun_fiskal', 'id')->toArray()
                    )
                    ->required()
                    ->disabled() // Ini akan membuat field menjadi readonly
                    ->default(function () {
                        // Cari record TahunFiskal yang is_active = true
                        $activeTahunFiskal = TahunFiskal::where('is_active', true)->first();

                        // Jika ditemukan, gunakan ID-nya sebagai nilai default
                        if ($activeTahunFiskal) {
                            return $activeTahunFiskal->id;
                        }

                        // Jika tidak ada yang aktif, Anda bisa mengembalikan null atau ID default lainnya
                        // Misalnya, jika Anda ingin default ke tahun fiskal saat ini jika tidak ada yang aktif:
                        // $currentYear = date('Y');
                        // $currentTahunFiskal = TahunFiskal::where('tahun_fiskal', $currentYear)->first();
                        // return $currentTahunFiskal ? $currentTahunFiskal->id : null;
            
                        return null; // Mengembalikan null jika tidak ada tahun fiskal aktif
                    })
                    ->validationMessages([
                        'required' => 'Tahun Fiskal belum diaktifkan oleh admin'
                    ]),
                Forms\Components\Hidden::make('tahun_fiskal')->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('dariTahunFiskal.nama_tahun_fiskal')
                    ->label('Tahun Fiskal')
                    ->searchable()
                    ->limit(50),
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
            'index' => Pages\ListKompumedRencanaPrograms::route('/'),
            // 'create' => Pages\CreateKompumedRencanaProgram::route('/create'),
            // 'edit' => Pages\EditKompumedRencanaProgram::route('/{record}/edit'),
        ];
    }
}
