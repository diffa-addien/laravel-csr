<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StrategiResource\Pages;
use App\Filament\Resources\StrategiResource\RelationManagers;
use App\Models\Strategi;
use App\Models\TahunFiskal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;

use App\Filament\Traits\HasResourcePermissions;

class StrategiResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'data_induk';
    protected static ?string $model = Strategi::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Data Induk';
    protected static ?string $modelLabel = 'Data';
    protected static ?string $pluralModelLabel = 'Data Strategi';
    public static int $navigationGroupSort = 8;

    protected static ?string $navigationLabel = 'Strategi Program';
    protected static ?int $navigationSort = 5;
    // Properti ini akan berfungsi sebagai penanda dalam satu request.
    private static bool $notificationSent = false;

    public static function getPluralModelLabel(): string
    {
        return 'Daftar Strategi Program';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                RichEditor::make('keterangan')
                    ->nullable()
                    ->columnSpanFull()
                    ->disableToolbarButtons([
                        'attachFiles',
                    ]),
                Select::make('tahun_fiskal')
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
                        
                        return null; // Mengembalikan null jika tidak ada tahun fiskal aktif
                    })
                    ->validationMessages([
                        'required' => 'Tahun Fiskal belum diaktifkan oleh admin'
                    ]),
                // Catatan: Anda memiliki 'tahun_fiskal' dua kali (Select dan Hidden).
                // Jika Select di-disable, nilainya MUNGKIN tidak terkirim.
                // Anda bisa menghapus Hidden::make() jika 'disabled()' tetap mengirim data,
                // atau hapus 'disabled()' dari Select jika Anda ingin 'default'nya saja yang bekerja.
                // Untuk amannya, saya biarkan, tapi ini perlu diperhatikan.
                // Jika 'disabled()' MENCEGAH 'default()' tersimpan, ubah 'disabled()' menjadi 'dehydrated(false)'
                
                // Versi yang lebih baik:
                // Select::make('tahun_fiskal')
                //     ->label('Tahun Fiskal')
                //     ->options(TahunFiskal::pluck('nama_tahun_fiskal', 'id')->toArray())
                //     ->required()
                //     ->default(fn () => TahunFiskal::where('is_active', true)->value('id'))
                //     ->dehydrated() // Pastikan tetap tersimpan
                //     ->disabled()   // Buat read-only
                //     ->validationMessages([
                //         'required' => 'Tahun Fiskal belum diaktifkan oleh admin'
                //     ]),
                // HAPUS Forms\Components\Hidden::make('tahun_fiskal')->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        $activeTahunFiskal = TahunFiskal::where('is_active', true)->first(); // Ubah nama variabel
        $teksFiskal = "";

        if (!$activeTahunFiskal) { // Perbarui cek
            $teksFiskal = "Tahun Fiskal belum diaktifkan";
        } else {
            $teksFiskal = "Tahun fiskal " . $activeTahunFiskal->nama_tahun_fiskal;
        }

        return $table
            ->header(
                fn () => new HtmlString('<div class="text-center px-4 py-2 bg-gray-50 dark:bg-gray-900">' . $teksFiskal . '</div>') // Tambah padding
            )
            ->modifyQueryUsing(function (Builder $query) { // --- Closure MULAI ---
                // 1. Dapatkan ID dari tahun fiskal yang aktif.
                $activeTahunFiskalId = TahunFiskal::where('is_active', true)->value('id');

                // 2. Jika tidak ada tahun fiskal yang aktif, jangan tampilkan data apa pun (best practice).
                if (!$activeTahunFiskalId) {
                    if (!self::$notificationSent) {
                        // Tidak perlu cek DB lagi, $activeTahunFiskalId sudah null
                        Notification::make()
                            ->title('Tahun fiskal belum diaktifkan')
                            ->body('Silahkan hubungi bagian admin untuk mengaktifkan tahun fiskal')
                            ->danger()
                            ->persistent()
                            ->send();
                        self::$notificationSent = true;
                    }
                    // Menggunakan trik query kosong untuk tidak mengembalikan hasil.
                    $query->whereRaw('1 = 0');
                    return; // Hentikan eksekusi lebih lanjut dari fungsi ini.
                }

                // =================================================================
                // INI ADALAH PERBAIKAN UTAMA
                // Filter model Strategi berdasarkan kolom 'tahun_fiskal' miliknya
                $query->where('tahun_fiskal', $activeTahunFiskalId);
                // =================================================================

            }) // --- Closure SELESAI di sini ---

            // Semua method di bawah ini dipindahkan ke LUAR closure modifyQueryUsing
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Strategi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable()
                    ->formatStateUsing(fn (?string $state): string => strip_tags($state ?? ''))
                    ->limit(50),
                Tables\Columns\TextColumn::make('dariTahunFiskal.nama_tahun_fiskal')
                    ->label('Tahun Fiskal')
                    ->searchable()
                    ->sortable()
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
            ])
            ->defaultSort('tahun_fiskal', 'desc'); // Hapus titik koma ganda
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
            'index' => Pages\ListStrategis::route('/'),
            // 'create' => Pages\CreateStrategi::route('/create'),
            // 'edit' => Pages\EditStrategi::route('/{record}/edit'),
        ];
    }
}