<?php

namespace App\Filament\Clusters\StakeholderPerencanaan\Resources;

use App\Filament\Clusters\StakeholderPerencanaan;
use App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderPerencanaanPpkResource\Pages;
use App\Models\StkholderPerencanaanPpk;
use App\Models\TahunFiskal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder; // <-- PASTIKAN IMPORT INI ADA

use App\Filament\Traits\HasResourcePermissions;

class StkholderPerencanaanPpkResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'stakeholder';
    protected static ?string $model = StkholderPerencanaanPpk::class;
    protected static ?string $navigationIcon = 'heroicon-o-bars-arrow-up';
    protected static ?string $navigationLabel = 'Perencanaan Program PPK';
    protected static ?string $pluralModelLabel = 'Perencanaan Program PPK';
    protected static ?string $modelLabel = 'Data';
    protected static ?string $cluster = StakeholderPerencanaan::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tahun_fiskal')
                    ->label('Tahun Fiskal')
                    ->options(
                        TahunFiskal::pluck('nama_tahun_fiskal', 'id')->toArray()
                    )
                    ->required()
                    ->disabled()
                    ->default(function () {
                        $activeTahunFiskal = TahunFiskal::where('is_active', true)->first();
                        if ($activeTahunFiskal) {
                            return $activeTahunFiskal->id;
                        }
            
                        return null; // Mengembalikan null jika tidak ada tahun fiskal aktif
                    })
                    ->validationMessages([
                        'required' => 'Tahun Fiskal belum diaktifkan oleh admin'
                    ]),
                Forms\Components\Hidden::make('tahun_fiskal')
                    ->required(),

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

                Forms\Components\TextInput::make('nama')
                    ->label('Nama PPK')
                    ->required()
                    ->placeholder('Nama PPK Baru')
                    ->maxLength(255)
                    ->columnSpanFull(),

                // NEW: Anggaran fields in a grid
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('pengajuan_anggaran')
                        ->label('Pengajuan Anggaran')
                        ->numeric()
                        ->prefix('Rp')
                        ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2),

                    Forms\Components\TextInput::make('kesepakatan_anggaran')
                        ->label('Kesepakatan Anggaran')
                        ->numeric()
                        ->prefix('Rp')
                        ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2),
                ]),

                // NEW: Date fields in a grid
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\DatePicker::make('rencana_mulai')
                        ->label('Rencana Mulai'),

                    Forms\Components\DatePicker::make('rencana_selesai')
                        ->label('Rencana Selesai'),
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
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama PPK')
                    ->searchable(),

                // NEW: Display selected strategies as badges
                Tables\Columns\TextColumn::make('strategis.nama')
                    ->label('Strategi')
                    ->badge() // Display as nice-looking badges
                    ->searchable(),

                // NEW: Display budget columns with currency format
                Tables\Columns\TextColumn::make('pengajuan_anggaran')
                    ->label('Pengajuan')
                    ->money('IDR') // Format as Indonesian Rupiah
                    ->sortable(),

                Tables\Columns\TextColumn::make('kesepakatan_anggaran')
                    ->label('Kesepakatan')
                    ->money('IDR')
                    ->sortable(),

                // NEW: Display date columns
                Tables\Columns\TextColumn::make('rencana_mulai')
                    ->label('Mulai')
                    ->date('d M Y') // Format the date
                    ->sortable(),

                Tables\Columns\TextColumn::make('rencana_selesai')
                    ->label('Selesai')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('dariTahunFiskal.nama_tahun_fiskal')
                    ->label('Tahun Fiskal')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tahun_fiskal')
                    ->label('Tahun Fiskal')
                    ->relationship('dariTahunFiskal', 'nama_tahun_fiskal') // 'nama_tahun' adalah kolom yang ingin ditampilkan di dropdown
                    ->searchable()
                    ->preload()
                    ->default(function () {
                        return TahunFiskal::where('is_active', true)->value('id');
                    }),
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
            'index' => Pages\ListStkholderPerencanaanPpks::route('/'),
            // 'create' => Pages\CreateStkholderPerencanaanPpk::route('/create'),
            // 'edit' => Pages\EditStkholderPerencanaanPpk::route('/{record}/edit'),
        ];
    }
}