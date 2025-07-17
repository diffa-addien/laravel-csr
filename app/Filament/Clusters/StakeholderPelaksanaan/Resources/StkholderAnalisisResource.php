<?php

namespace App\Filament\Clusters\StakeholderPelaksanaan\Resources;

use App\Filament\Clusters\StakeholderPelaksanaan;
use App\Filament\Clusters\StakeholderPelaksanaan\Resources\StkholderAnalisisResource\Pages;
use App\Filament\Clusters\StakeholderPelaksanaan\Resources\StkholderAnalisisResource\RelationManagers;
use App\Models\StkholderAnalisis;
use App\Models\TahunFiskal;
use App\Models\StkholderPerencanaanProgramAnggaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder; // <-- Import Builder
use Illuminate\Support\HtmlString; // Tambahkan baris ini
use Filament\Notifications\Notification;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;
use App\Filament\Traits\HasResourcePermissions;

class StkholderAnalisisResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'stakeholder';
    protected static ?string $model = StkholderAnalisis::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Analisis Program Kerja';
    protected static ?string $pluralModelLabel = 'Analisis Program Kerja';
    protected static ?string $modelLabel = 'Data';
    protected static ?string $cluster = StakeholderPelaksanaan::class;
    private static bool $notificationSent = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Kegiatan Details')
                    ->schema([
                        Select::make('kegiatan_id')
                            ->label('Untuk Kegiatan Program')
                            ->relationship(
                                name: 'kegiatan',
                                titleAttribute: 'kegiatan', // Atribut untuk pencarian
                                modifyQueryUsing: function (Builder $query) {
                                    // 1. Dapatkan ID Tahun Fiskal yang aktif.
                                    $activeTahunFiskalId = TahunFiskal::where('is_active', true)->value('id');

                                    // 2. Jika tidak ada tahun fiskal aktif, jangan tampilkan opsi apa pun.
                                    if (!$activeTahunFiskalId) {
                                        // Mengembalikan query yang dijamin kosong.
                                        return $query->where('id', -1);
                                    }

                                    // 3. Filter 'kegiatan' (model StkholderPerencanaanProgramAnggaran)
                                    //    yang memiliki relasi 'program' dengan tahun_fiskal yang aktif.
                                    return $query
                                        ->whereHas('program', function (Builder $subQuery) use ($activeTahunFiskalId) {
                                        // Filter di sini diterapkan pada model Program (StkholderPerencanaanPpk)
                                        $subQuery->where('tahun_fiskal', $activeTahunFiskalId);
                                    })
                                        // Eager load relasi untuk ditampilkan di label dan meningkatkan efisiensi
                                        ->with(['regional', 'program']);
                                }
                            )
                            ->getOptionLabelFromRecordUsing(function ($record) {
                                // $record adalah instance dari model StkholderPerencanaanProgramAnggaran
                                $regionalName = $record->regional?->nama_regional ?? 'N/A';
                                $programName = $record->program?->nama ?? 'N/A';

                                return "{$record->kegiatan} ({$regionalName} - {$programName})";
                            })
                            ->required()
                            ->columnSpanFull()
                            ->preload(),
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
        $activeTahunFiskal = TahunFiskal::where('is_active', true)->first();
        $teksFiskal = $activeTahunFiskal ? 'Tahun Fiskal ' . $activeTahunFiskal->nama_tahun_fiskal : 'Tahun Fiskal Belum Diaktifkan';

        return $table
            // --- INI BAGIAN YANG DIUBAH ---
            ->header(
                fn() => new HtmlString('<div class="text-center px-4 py-2 bg-gray-50 dark:bg-gray-800 text-sm font-medium">' . $teksFiskal . '</div>')
            )
            ->modifyQueryUsing(function (Builder $query) {
                $activeTahunFiskalId = TahunFiskal::where('is_active', true)->value('id');

                if (!$activeTahunFiskalId) {
                    if (!self::$notificationSent) {
                        Notification::make()
                            ->title('Tahun fiskal belum diaktifkan')
                            ->body('Silahkan hubungi bagian admin untuk mengaktifkan tahun fiskal.')
                            ->danger()
                            ->persistent()
                            ->send();
                        self::$notificationSent = true;
                    }
                    return $query->whereRaw('1 = 0');
                }

                // Filter Rincian Anggaran berdasarkan tahun fiskal aktif melalui relasi berjenjang:
                // StkholderRincianAnggaran -> kegiatan -> program -> tahun_fiskal
                return $query->whereHas('kegiatan.program', function (Builder $programQuery) use ($activeTahunFiskalId) {
                    $programQuery->where('tahun_fiskal', $activeTahunFiskalId);
                });
            })
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
            // 'create' => Pages\CreateStkholderAnalisis::route('/create'),
            // 'edit' => Pages\EditStkholderAnalisis::route('/{record}/edit'),
        ];
    }
}
