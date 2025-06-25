<?php

namespace App\Filament\Pages;

use App\Models\StkholderPerencanaanProgramAnggaran;
use Filament\Pages\Page;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\PengmasRencanaProgramAnggaran;
use App\Models\TahunFiskal;
use Illuminate\Support\Facades\DB;
use Livewire\Component; // atau class dasar komponen Filament Anda

class LaporanAnggaran extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-printer';
    protected static bool $shouldRegisterNavigation = true;
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Laporan Anggaran';
    protected static ?string $pluralModelLabel = 'Laporan';
    protected static string $view = 'filament.pages.print-table';

    public array $records = [];

    public function mount(): void
    {

        // 1. Ambil 4 tahun fiskal terakhir, diurutkan dari yang terlama ke terbaru.
        // Query ini untuk menentukan rentang tahun yang akan kita proses.
        $lastFourFiscalYears = TahunFiskal::query()
            ->orderBy('nama_tahun_fiskal', 'desc')
            ->limit(4)
            ->get()
            // Urutkan kembali agar data yang ditampilkan kronologis (misal: 2022, 2023, 2024, 2025)
            ->sortBy('nama_tahun_fiskal');

        // Jika tidak ada data tahun fiskal sama sekali, hentikan proses.
        if ($lastFourFiscalYears->isEmpty()) {
            $this->records = []; // Pastikan $records adalah array kosong
            return;
        }

        // 2. Ambil ID dari tahun-tahun tersebut untuk filtering query utama
        $fiscalYearIds = $lastFourFiscalYears->pluck('id');

        // 3. Query data utama: hitung total anggaran per tahun fiskal
        $budgetData = PengmasRencanaProgramAnggaran::query()
            // Join dengan tabel tahun fiskal untuk mendapatkan nama tahun
            ->join('tahun_fiskals', 'pengmas_rencana_program_anggarans.tahun_fiskal', '=', 'tahun_fiskals.id')
            // Filter hanya untuk 4 tahun fiskal yang sudah kita tentukan
            ->whereIn('pengmas_rencana_program_anggarans.tahun_fiskal', $fiscalYearIds)
            // Pilih kolom yang dibutuhkan dan hitung total anggaran
            ->select(
                'pengmas_rencana_program_anggarans.tahun_fiskal',
                'tahun_fiskals.nama_tahun_fiskal',
                DB::raw('SUM(pengajuan_anggaran) as total_anggaran')
            )
            // Kelompokkan hasil berdasarkan ID dan nama tahun
            ->groupBy('pengmas_rencana_program_anggarans.tahun_fiskal', 'tahun_fiskals.nama_tahun_fiskal')
            ->orderBy('tahun_fiskals.nama_tahun_fiskal', 'asc')
            ->get()
            // Jadikan ID tahun fiskal sebagai key array untuk akses yang mudah
            ->keyBy('tahun_fiskal');

        // 4. Assign hasil query ke properti public $records
        // Menggunakan toArray() untuk memastikan tipe data sesuai dengan deklarasi `public array $records`
        $this->records = $budgetData->toArray();
    }

    public function printTable(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $view = View::make('filament.pages.print-table-pdf', ['records' => $this->records]);
        $pdf = Pdf::loadHTML($view->render());
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Laporan_Pengembangan_Masyarakat.pdf');
    }
}