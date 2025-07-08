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
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Traits\HasResourcePermissions;

class KompumedRencanaProgramResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'komunikasi_media';
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
                // PENYESUAIAN: Menggunakan dehydrated() dan menghapus Hidden field
                Forms\Components\Select::make('tahun_fiskal')
                    ->label('Tahun Fiskal')
                    ->options(TahunFiskal::pluck('nama_tahun_fiskal', 'id')->toArray())
                    ->required()
                    ->disabled()
                    ->dehydrated() 
                    ->default(fn () => TahunFiskal::where('is_active', true)->first()?->id)
                    ->validationMessages(['required' => 'Tahun Fiskal belum diaktifkan oleh admin']),
                Forms\Components\Hidden::make('tahun_fiskal')->required(),
                
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Nama Program Baru')
                    ->columnSpanFull(),

                // PENAMBAHAN: Field untuk memilih Strategi dengan filter tahun fiskal aktif
                Forms\Components\Select::make('strategis')
                    ->label('Pilih Strategi Terkait')
                    ->relationship(
                        name: 'strategis',
                        titleAttribute: 'nama',
                        modifyQueryUsing: function (Builder $query) {
                            $activeTahunFiskalId = TahunFiskal::where('is_active', true)->value('id');
                            if (!$activeTahunFiskalId) {
                                // PERBAIKAN: Secara eksplisit menentukan tabel untuk kolom 'id'
                                // untuk menghindari ambiguitas pada query JOIN.
                                $tableName = $query->getModel()->getTable();
                                return $query->where("{$tableName}.id", -1);
                            }
                            return $query->where('tahun_fiskal', $activeTahunFiskalId)->with('dariTahunFiskal');
                        }
                    )
                    // PERBAIKAN: Menggunakan fungsi anonim tradisional untuk kompatibilitas PHP yang lebih luas
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        $tahun = $record->dariTahunFiskal ? $record->dariTahunFiskal->nama_tahun_fiskal : 'N/A';
                        return "{$record->nama} ({$tahun})";
                    })
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->columnSpanFull(),
                
                // PENAMBAHAN: Field Anggaran dengan format currencyMask
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('pengajuan_anggaran')
                        ->label('Pengajuan Anggaran')
                        ->prefix('Rp')
                        ->numeric()
                        ->required()
                        ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2),
                    
                    Forms\Components\TextInput::make('kesepakatan_anggaran')
                        ->label('Kesepakatan Anggaran')
                        ->prefix('Rp')
                        ->numeric()
                        ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2),
                ]),

                // PENAMBAHAN: Field Tanggal Rencana
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\DatePicker::make('rencana_mulai')->label('Rencana Mulai'),
                    Forms\Components\DatePicker::make('rencana_selesai')->label('Rencana Selesai'),
                ]),

                Forms\Components\Textarea::make('keterangan')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->label('Nama Program')->searchable(),
                
                // PENAMBAHAN: Menampilkan data relasi dan data baru
                Tables\Columns\TextColumn::make('strategis.nama')->label('Strategi')->badge(),
                Tables\Columns\TextColumn::make('pengajuan_anggaran')->label('Pengajuan')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('kesepakatan_anggaran')->label('Kesepakatan')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('rencana_mulai')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('rencana_selesai')->date('d M Y')->sortable(),

                Tables\Columns\TextColumn::make('dariTahunFiskal.nama_tahun_fiskal')
                    ->label('Tahun Fiskal')
                    ->searchable()
                    ->badge(),
            ])
            ->filters([
                // PENAMBAHAN: Filter tabel berdasarkan tahun fiskal sesuai permintaan Anda
                Tables\Filters\SelectFilter::make('tahun_fiskal')
                    ->label('Tahun Fiskal')
                    ->relationship('dariTahunFiskal', 'nama_tahun_fiskal')
                    ->searchable()
                    ->preload()
                    ->default(fn () => TahunFiskal::where('is_active', true)->value('id')),
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
            // PENYESUAIAN: Mengaktifkan halaman create dan edit
            // 'create' => Pages\CreateKompumedRencanaProgram:: route('/create'),
            // 'edit' => Pages\EditKompumedRencanaProgram::route('/{record}/edit'),
        ];
    }
}