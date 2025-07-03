<?php

namespace App\Filament\Clusters\PengmasPerencanaan\Resources;

use App\Filament\Traits\HasResourcePermissions;

use App\Filament\Clusters\PengmasPerencanaan;
use App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasAnalisisProgramResource\Pages;
use App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasAnalisisProgramResource\RelationManagers;
use App\Models\PengmasAnalisisProgram;
use App\Models\TahunFiskal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder; // <-- Import Builder
use Illuminate\Support\HtmlString; // Tambahkan baris ini
use Filament\Notifications\Notification;

class PengmasAnalisisProgramResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'pengembangan_masyarakat';
    protected static ?string $model = PengmasAnalisisProgram::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass-circle';
    protected static ?string $navigationLabel = 'Analisis Program';
    protected static ?string $pluralModelLabel = 'Analisis Program Kerja';
    protected static ?string $modelLabel = 'Data';
    protected static ?int $navigationSort = 3;

    protected static ?string $cluster = PengmasPerencanaan::class;
    private static bool $notificationSent = false;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('id_strategi')
                    ->label('Strategi')
                    ->relationship('dariStrategi', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('id_program')
                    ->label('Dari Program')
                    ->relationship(
                        name: 'dariProgram',
                        titleAttribute: 'nama_program', // Biarkan ini sebagai default
                        modifyQueryUsing: function (Builder $query) {
                            // Logika filter Anda tetap sama
                            $activeTahunFiskalId = TahunFiskal::where('is_active', true)->value('id');
                            if (!$activeTahunFiskalId) {
                                return $query->where('id', -1);
                            }
                            // Penting: Eager load relasi tahunFiskal agar tidak terjadi N+1 problem
                            return $query->where('tahun_fiskal', $activeTahunFiskalId)->with('dariTahunFiskal');
                        }
                    )
                    // --- TAMBAHKAN METHOD INI ---
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        // $record adalah objek model Program untuk setiap pilihan
                        $tahun = $record->dariTahunFiskal?->nama_tahun_fiskal ?? 'N/A';
                        return "{$record->nama_program} ({$tahun})";
                    })
                    ->required()
                    // ->searchable()
                    ->preload(),
                Textarea::make('target_hasil')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('indikator_berhasil')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('asumsi_or_risiko')
                    ->label('Asumsi / Resiko')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('pendukung_hasil')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        $activeTahunFiskalId = TahunFiskal::where('is_active', true)->first();
        $teksFiskal = "";

        if (!$activeTahunFiskalId) {
            $teksFiskal = "Tahun Fiskal belum diaktifkan";
        } else {
            $teksFiskal = "Tahun fiskal " . $activeTahunFiskalId->nama_tahun_fiskal;
        }

        return $table
            ->header(
                fn() => new HtmlString('<div class="text-center px-4 bg-gray-50 dark:bg-gray-900">' . $teksFiskal . '</div>')
            )
            ->modifyQueryUsing(function (Builder $query) {
                // 1. Dapatkan ID dari tahun fiskal yang aktif.
                $activeTahunFiskalId = TahunFiskal::where('is_active', true)->value('id');

                // 2. Jika tidak ada tahun fiskal yang aktif, jangan tampilkan data apa pun (best practice).
                if (!$activeTahunFiskalId) {
                    if (!self::$notificationSent) {
                        $cek = TahunFiskal::where('is_active', true)->value('id');
                        if (!$cek) {
                            Notification::make()
                                ->title('Tahun fiskal belum diaktifkan')
                                ->body('Silahkan hubungi bagian admin untuk mengaktifkan tahun fiskal')
                                ->danger()
                                ->persistent()
                                ->send();
                        }
                        self::$notificationSent = true;
                    }
                    // Menggunakan trik query kosong untuk tidak mengembalikan hasil.
                    $query->whereRaw('1 = 0');
                    return; // Hentikan eksekusi lebih lanjut dari fungsi ini.
    
                }

                // 3. Terapkan filter whereHas.
                //    Ini akan memfilter 'Kegiatan' yang memiliki relasi 'dariProgram',
                //    di mana 'tahun_fiskal_id' pada program tersebut sama dengan ID tahun fiskal aktif kita.
                $query->whereHas('dariProgram', function (Builder $programQuery) use ($activeTahunFiskalId) {
                    $programQuery->where('tahun_fiskal', $activeTahunFiskalId);
                });
            })
            ->columns([
                TextColumn::make('dariStrategi.nama')
                    ->label('Strategi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('dariProgram.nama_program')
                    ->label('Dari Program')
                    ->formatStateUsing(function ($state, $record) {
                        $tahun = $record->dariProgram?->dariTahunFiskal?->nama_tahun_fiskal ?? '';
                        return $tahun ? "{$state} ({$tahun})" : $state;
                    })
                    ->sortable()
                    ->searchable(),
                TextColumn::make('target_hasil')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('indikator_berhasil')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('asumsi_or_risiko')
                    ->label('Asumsi / Resiko')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('pendukung_hasil')
                    ->limit(50)
                    ->searchable(),
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
            'index' => Pages\ListPengmasAnalisisPrograms::route('/'),
            //     'create' => Pages\CreatePengmasAnalisisProgram::route('/create'),
            //     'edit' => Pages\EditPengmasAnalisisProgram::route('/{record}/edit'),
        ];
    }
}
