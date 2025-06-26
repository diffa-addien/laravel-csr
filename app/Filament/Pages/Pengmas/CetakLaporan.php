<?php

namespace App\Filament\Pages\Pengmas;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use App\Models\TahunFiskal;
use App\Models\PengmasRencanaProgramAnggaran;
use Filament\Actions\Action;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString; // Tambahkan baris ini


class CetakLaporan extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-printer';
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationLabel = 'Cetak Laporan Program';
    protected static ?string $navigationGroup = 'Pengmas';
    protected static ?string $slug = 'pengmas/cetak-laporan';
    protected static ?string $title = 'Cetak Laporan Program Pengembangan Masyarakat';
    protected static string $view = 'filament.pages.pengmas.cetak-laporan';

    public ?array $formData = [];
    public array $appliedFilters = [];

    public function mount(): void
    {
        $defaultTahunId = TahunFiskal::query()->orderByDesc('nama_tahun_fiskal')->value('id');
        $this->formData['tahunFiskalId'] = $defaultTahunId;
        $this->appliedFilters['tahunFiskalId'] = $defaultTahunId;
        $this->form->fill($this->formData);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tahunFiskalId')
                    ->label('Pilih Tahun Fiskal')
                    ->options(TahunFiskal::query()->pluck('nama_tahun_fiskal', 'id'))
                    ->live()
                    // PEMICU 1: Panggil metode jantung setelah live update.
                    ->afterStateUpdated(fn() => $this->applyFiltersAndRefreshTable()),
            ])
            ->statePath('formData');
    }

    // --- Tombol Aksi ---

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Kembali')
                ->url(url('admin/laporan-utama'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
        ];
    }

    public function getApplyAction(): Action
    {
        return Action::make('applyFilters')
            ->label('Terapkan')
            ->icon('heroicon-o-check-circle')
            ->color('success')
            // PEMICU 2: Panggil metode jantung yang sama saat tombol diklik.
            ->action('applyFiltersAndRefreshTable');
        // Tombol hanya aktif jika ada perubahan yang belum diterapkan.
        // ->disabled(fn (): bool => $this->formData === $this->appliedFilters);
    }

    public function getPrintAction(): Action
    {
        return Action::make('print')
            ->label('Cetak PDF')
            ->icon('heroicon-o-printer')
            ->action('exportPdf')
            ->disabled(!($this->appliedFilters['tahunFiskalId'] ?? null));
    }

    // --- Metode Logika ---

    /**
     * METODE JANTUNG: Satu-satunya tempat di mana filter diterapkan dan tabel di-refresh.
     */
    public function applyFiltersAndRefreshTable(): void
    {
        $this->appliedFilters = $this->formData;
        $this->resetPage();
    }

    public function exportPdf()
    {
        $tahunId = $this->appliedFilters['tahunFiskalId'] ?? null;
        if (!$tahunId)
            return;

        // --- PERUBAHAN DI SINI ---
        // Kita tambahkan withCount('rincianAnggarans') untuk menghitung relasi hasMany.
        // Eloquent akan secara otomatis menambahkan atribut 'rincian_anggarans_count' pada setiap record.
        // Kita juga tambahkan with('bidang') agar data bidang ikut ter-load dengan efisien.
        $records = PengmasRencanaProgramAnggaran::withCount('rincianAnggarans')
            ->where('tahun_fiskal', $tahunId)
            ->get();
        // -------------------------

        if ($records->isEmpty()) {
            \Filament\Notifications\Notification::make()->title('Tidak Ada Data')->warning()->send();
            return;
        }

        // Path ke view PDF Anda
        $view = View::make('filament.pages.pengmas.pdf-laporan-program', ['records' => $records]);
        $pdf = Pdf::loadHTML($view->render())->setPaper('a4', 'landscape');
        $namaFile = 'Laporan_Program_Pengmas' . TahunFiskal::find($tahunId)->nama_tahun_fiskal . '.pdf';

        return response()->stream(fn() => print ($pdf->output()), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $namaFile . '"',
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PengmasRencanaProgramAnggaran::query()
                    ->when($this->appliedFilters['tahunFiskalId'] ?? null, fn($query, $id) => $query->where('tahun_fiskal', $id))
                    ->limit(5)
            )
            ->header(
                fn() => new HtmlString('<div class="text-center px-4 bg-gray-50 dark:bg-gray-900">Sekilas Isi Laporan</div>')
            )
            ->columns([
                TextColumn::make('nama_program')->label('Nama Program')->searchable(),
                TextColumn::make('kesepakatan_anggaran')->label('Anggaran Disepakati')->money('IDR'),
                TextColumn::make('rencana_mulai')->label('Tgl Mulai')->date('d M Y'),
                TextColumn::make('dariTahunFiskal.nama_tahun_fiskal')->label('Tahun Fiskal'),
            ])
            ->paginated(false);
    }
}