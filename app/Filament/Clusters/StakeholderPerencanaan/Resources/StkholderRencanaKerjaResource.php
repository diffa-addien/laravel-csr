<?php

namespace App\Filament\Clusters\StakeholderPerencanaan\Resources;

use App\Filament\Clusters\StakeholderPerencanaan;
use App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderRencanaKerjaResource\Pages;
use App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderRencanaKerjaResource\RelationManagers;
use App\Models\StkholderRencanaKerja;
use App\Models\TahunFiskal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Builder; // <-- Import Builder
use Illuminate\Support\HtmlString; // Tambahkan baris ini
use Filament\Notifications\Notification;

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
    private static bool $notificationSent = false;

    public static function form(Form $form): Form
    {
        return $form
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
                TextColumn::make('kegiatan.kegiatan')
                    ->label('Kegiatan')
                    ->formatStateUsing(fn($record) => "{$record->kegiatan->kegiatan} ({$record->kegiatan->regional->nama_regional} - {$record->kegiatan->program->nama})")
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
