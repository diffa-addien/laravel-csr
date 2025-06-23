<?php

namespace App\Filament\Clusters\PengmasPerencanaan\Resources;

use App\Filament\Clusters\PengmasPerencanaan;
use App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasRencanaProgramAnggaranResource\Pages;
use App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasRencanaProgramAnggaranResource\RelationManagers;
use App\Models\PengmasRencanaProgramAnggaran;
use App\Models\TahunFiskal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;

use Filament\Tables\Filters\SelectFilter;


use Illuminate\Database\QueryException; // <-- 1. TAMBAHKAN USE STATEMENT INI
use Filament\Notifications\Notification;

class PengmasRencanaProgramAnggaranResource extends Resource
{
    protected static ?string $model = PengmasRencanaProgramAnggaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Rencana Program Anggaran';
    protected static ?string $pluralModelLabel = 'Rencana Program dan Anggaran';
    protected static ?string $modelLabel = 'Data';
    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = PengmasPerencanaan::class;
    // Properti ini akan berfungsi sebagai penanda dalam satu request.
    private static bool $notificationSent = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('regional_id')
                    ->label('Regional')
                    ->relationship('regional', 'nama_regional')
                    ->required()
                    // ->searchable()
                    ->preload(),
                TextInput::make('nama_program')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->placeholder('Nama Program Baru'),
                TextInput::make('pengajuan_anggaran')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2),
                TextInput::make('kesepakatan_anggaran')
                    ->numeric()
                    ->prefix('Rp')
                    ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2)
                    ->nullable(),
                DatePicker::make('rencana_mulai')
                    ->required(),
                DatePicker::make('rencana_selesai')
                    ->required()
                    ->afterOrEqual('rencana_mulai'),
                TextInput::make('output')
                    ->required()
                    ->maxLength(255),
                TextInput::make('output_unit')
                    ->label('Bentuk Output')
                    ->required()
                    ->maxLength(255),
                Textarea::make('tujuan_utama')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('tujuan_khusus')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('justifikasi')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('keterangan')
                    ->nullable()
                    ->columnSpanFull(),
                Select::make('tahun_fiskal')
                    ->label('Tahun Fiskal')
                    ->options(
                        TahunFiskal::pluck('tahun_fiskal', 'id')->toArray()
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

    // public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    // {
    //     // Panggil query asli dari parent class
    //     $query = parent::getEloquentQuery();

    //     // Ambil ID dari tahun fiskal yang aktif
    //     $activeTahunFiskalId = \App\Models\TahunFiskal::where('is_active', true)->value('id');

    //     // Jika tidak ada tahun fiskal yang aktif, jangan tampilkan data apa pun.
    //     if (!$activeTahunFiskalId) {

    //         // Kirim notifikasi error ke pengguna/developer
    //         if (!self::$notificationSent) {
    //             Notification::make()
    //                 ->title('Tahun Fiskal 2022')
    //                 // ->body('Kolom "tahun_fiskal_id" tidak ditemukan pada tabel. Mohon periksa file migrasi database.')
    //                 // ->danger()
    //                 // ->persistent()
    //                 ->send();

    //             // 3. SETELAH DIKIRIM, UBAH FLAG MENJADI TRUE
    //             // Ini akan mencegah pengiriman notifikasi lagi pada panggilan method berikutnya.
    //             self::$notificationSent = true;
    //         }
    //         return $query->whereRaw('1 = 0'); // Trik mengembalikan query kosong

    //     }

    //     // Terapkan filter permanen
    //     return $query->where('tahun_fiskal_id', $activeTahunFiskalId);
    // }

    public static function table(Table $table): Table
    {
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

        return $table
            ->columns([
                TextColumn::make('regional.nama_regional')
                    ->label('Regional')
                    ->sortable()
                    ->searchable(),
                // TextColumn::make('bidang.nama_bidang')
                //     ->label('Pilar')
                //     ->sortable()
                //     ->searchable(),
                TextColumn::make('nama_program')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('output')
                    ->formatStateUsing(fn($record) => "{$record->output} {$record->output_unit}")
                    ->sortable(),
                TextColumn::make('keterangan')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('dariTahunFiskal.tahun_fiskal')
                    ->label('Tahun Fiskal')
                    ->searchable()
                    ->limit(50),
            ])
            ->filters([
                // INI BAGIAN UTAMANYA
                SelectFilter::make('tahun_fiskal_id')
                    ->label('Tahun Fiskal')
                    ->relationship('dariTahunFiskal', 'tahun_fiskal') // 'nama_tahun' adalah kolom yang ingin ditampilkan di dropdown
                    ->searchable()
                    ->preload()
                    ->default(function () {
                        return TahunFiskal::where('is_active', true)->value('id');
                    }),
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
            'index' => Pages\ListPengmasRencanaProgramAnggarans::route('/'),
            // 'create' => Pages\CreatePengmasRencanaProgramAnggaran::route('/create'),
            // 'edit' => Pages\EditPengmasRencanaProgramAnggaran::route('/{record}/edit'),
        ];
    }
}
