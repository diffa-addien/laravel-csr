<?php

namespace App\Filament\Pages\Pengmas;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use App\Models\TahunFiskal;
use App\Models\PengmasRencanaProgramAnggaran;
use App\Models\PengmasWilayahKegiatan;
use Filament\Actions\Action;
use Filament\Forms\Get;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;

class CetakLaporanKegiatan extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static bool $shouldRegisterNavigation = true; // Ubah ke true jika ingin muncul di menu

    protected static ?string $navigationLabel = 'Cetak Laporan Kegiatan';
    protected static ?string $navigationGroup = 'Pengmas';
    protected static ?string $slug = 'pengmas/cetak-laporan-kegiatan';
    protected static ?string $title = 'Cetak Laporan Kegiatan Pengembangan Masyarakat';
    // Kita bisa gunakan view yang sama
    protected static string $view = 'filament.pages.pengmas.cetak-laporan';

    public ?array $formData = [];
    public array $appliedFilters = [];

    public function mount(): void
    {
        // Set default tahun fiskal saat halaman dimuat
        $defaultTahunId = TahunFiskal::query()->orderByDesc('nama_tahun_fiskal')->value('id');
        $this->formData['tahunFiskalId'] = $defaultTahunId;
        $this->appliedFilters['tahunFiskalId'] = $defaultTahunId;
        $this->formData['programId'] = null;
        $this->appliedFilters['programId'] = null;
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
                    // Reset pilihan program jika tahun fiskal berubah
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('programId', null);
                        $this->applyFiltersAndRefreshTable();
                    }),
                Select::make('programId')
                    ->label('Pilih Program')
                    // Opsi program akan bergantung pada tahun fiskal yang dipilih
                    ->options(function (Get $get) {
                        $tahunId = $get('tahunFiskalId');
                        if ($tahunId) {
                            return PengmasRencanaProgramAnggaran::query()
                                ->where('tahun_fiskal', $tahunId)
                                ->pluck('nama_program', 'id');
                        }
                        return [];
                    })
                    ->live()
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
                ->url(url('admin/laporan-utama')) // Sesuaikan jika perlu
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
            ->action('applyFiltersAndRefreshTable');
    }

    public function getPrintAction(): Action
    {
        return Action::make('print')
            ->label('Cetak PDF')
            ->icon('heroicon-o-printer')
            ->action('exportPdf')
            // Tombol print hanya aktif jika program sudah dipilih
            ->disabled(!($this->appliedFilters['programId'] ?? null));
    }

    // --- Metode Logika ---

    public function applyFiltersAndRefreshTable(): void
    {
        $this->appliedFilters = $this->formData;
        $this->resetPage(); // Reset paginasi tabel
    }

    public function exportPdf()
    {
        $programId = $this->appliedFilters['programId'] ?? null;
        if (!$programId) return;

        // Ambil data kegiatan berdasarkan program yang dipilih
        // Kita juga hitung jumlah pelaksanaan yang terkait dengan setiap kegiatan
        $records = PengmasWilayahKegiatan::query()
            ->with(['dariProgram', 'dariProgram.dariTahunFiskal']) // Eager load relasi
            ->withCount('pelaksanaan') // Mengasumsikan ada relasi 'pelaksanaan' di model Kegiatan
            ->where('program_id', $programId)
            ->get();

        if ($records->isEmpty()) {
            \Filament\Notifications\Notification::make()->title('Tidak Ada Data Kegiatan')->warning()->send();
            return;
        }

        // Gunakan view PDF yang baru
        $view = View::make('filament.pages.pengmas.pdf-laporan-kegiatan', ['records' => $records]);
        $pdf = Pdf::loadHTML($view->render())->setPaper('a4', 'potrait');
        
        $program = $records->first()->dariProgram;
        $namaFile = 'Laporan_Kegiatan_' . \Str::slug($program->nama_program) . '.pdf';

        return response()->stream(fn() => print ($pdf->output()), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $namaFile . '"',
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PengmasWilayahKegiatan::query()
                    ->when($this->appliedFilters['programId'] ?? null, fn($query, $id) => $query->where('program_id', $id))
            )
            ->header(
                fn() => new HtmlString('<div class="text-center px-4 bg-gray-50 dark:bg-gray-900">Sekilas Isi Laporan Kegiatan</div>')
            )
            ->columns([
                TextColumn::make('nama_kegiatan')->label('Nama Kegiatan')->limit(45)->searchable(),
                TextColumn::make('anggaran')->label('Anggaran')->money('IDR'),
                TextColumn::make('rencana_mulai')->label('Tgl Mulai')->date('d M Y'),
                TextColumn::make('desa.nama_desa')->label('Desa')->limit(30),
            ])
            ->paginated();
    }
}
