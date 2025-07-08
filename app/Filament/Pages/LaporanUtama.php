<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\TahunFiskal;
use App\Models\PengmasRencanaProgramAnggaran;
use App\Models\StkholderPerencanaanPpk;
use App\Models\KompumedRencanaProgram;

use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class LaporanUtama extends Page
{
  // Icon dari Heroicons (https://heroicons.com/)
  protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

  protected static bool $shouldRegisterNavigation = true;

  // Judul halaman yang akan muncul di menu navigasi
  protected static ?string $navigationLabel = 'Laporan';

  // Grup navigasi (opsional, untuk mengelompokkan menu)
  protected static ?string $navigationGroup = 'Laporan';

  // Judul yang akan tampil di header halaman
  protected static ?string $title = 'Pusat Laporan';

  protected static ?int $navigationSort = 10;
  // Tentukan file view yang akan digunakan
  protected static string $view = 'filament.pages.laporan-utama';

  public function printLaporanPengmas(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $title = 'Laporan Total Anggaran Proggram (Pengembangan Masyarakat)';
        $records = $this->generateReportData(PengmasRencanaProgramAnggaran::class);
        
        $pdf = Pdf::loadView('filament.pages.pdf.laporan-konsolidasi-pdf', ['records' => $records, 'title' => $title]);
        return response()->streamDownload(fn() => print($pdf->output()), 'Laporan_Pengmas.pdf');
    }

    /**
     * Fungsi utama untuk mencetak laporan Perencanaan PPK Stakeholder.
     */
    public function printLaporanStakeholder(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $title = 'Laporan Total Anggaran Program (Pemangku Kepentingan)';
        $records = $this->generateReportData(StkholderPerencanaanPpk::class);

        $pdf = Pdf::loadView('filament.pages.pdf.laporan-konsolidasi-pdf', ['records' => $records, 'title' => $title]);
        return response()->streamDownload(fn() => print($pdf->output()), 'Laporan_Stakeholder_PPK.pdf');
    }

    /**
     * Fungsi utama untuk mencetak laporan Program Komunikasi & Media.
     */
    public function printLaporanKompumed(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $title = 'Laporan Total Anggaran Program (Komunikasi, Publikasi, dan Media)';
        $records = $this->generateReportData(KompumedRencanaProgram::class);

        $pdf = Pdf::loadView('filament.pages.pdf.laporan-konsolidasi-pdf', ['records' => $records, 'title' => $title]);
        return response()->streamDownload(fn() => print($pdf->output()), 'Laporan_Kompumed.pdf');
    }

    /**
     * Fungsi helper untuk mengambil dan memproses data laporan.
     * Dapat digunakan kembali untuk model yang berbeda.
     *
     * @param string $modelClass Kelas model yang akan di-query.
     * @return array Data yang siap untuk ditampilkan di laporan.
     */
    private function generateReportData(string $modelClass): array
    {
        // PERBAIKAN: Buat instance model untuk mendapatkan nama tabel secara dinamis.
        $modelInstance = new $modelClass;
        $tableName = $modelInstance->getTable();

        // 1. Ambil 5 tahun fiskal terakhir.
        $lastFiveFiscalYears = TahunFiskal::query()
            ->orderBy('nama_tahun_fiskal', 'desc')
            ->limit(5)
            ->get()
            ->sortBy('nama_tahun_fiskal'); // Urutkan kembali secara ascending

        if ($lastFiveFiscalYears->isEmpty()) {
            return []; // Kembalikan array kosong jika tidak ada tahun fiskal.
        }

        $fiscalYearIds = $lastFiveFiscalYears->pluck('id');

        // 2. Query data utama berdasarkan model yang diberikan.
        $budgetData = $modelClass::query()
            // Gunakan variabel $tableName untuk JOIN dan SELECT
            ->join('tahun_fiskals', "{$tableName}.tahun_fiskal", '=', 'tahun_fiskals.id')
            ->whereIn("{$tableName}.tahun_fiskal", $fiscalYearIds)
            ->select(
                "{$tableName}.tahun_fiskal",
                'tahun_fiskals.nama_tahun_fiskal',
                DB::raw('SUM(kesepakatan_anggaran) as total_kesepakatan')
            )
            ->groupBy("{$tableName}.tahun_fiskal", 'tahun_fiskals.nama_tahun_fiskal')
            ->orderBy('tahun_fiskals.nama_tahun_fiskal', 'asc')
            ->get()
            ->keyBy('tahun_fiskal');

        // 3. Gabungkan data tahun fiskal dengan data anggaran untuk memastikan semua 5 tahun ada di laporan.
        $reportRecords = [];
        foreach ($lastFiveFiscalYears as $year) {
            $reportRecords[$year->id] = [
                'nama_tahun_fiskal' => $year->nama_tahun_fiskal,
                // Jika tidak ada data anggaran untuk tahun tersebut, default-nya adalah 0.
                'total_kesepakatan' => $budgetData[$year->id]['total_kesepakatan'] ?? 0,
            ];
        }
        
        return $reportRecords;
    }

}