<?php

namespace App\Filament\Widgets;

use App\Models\KompumedRencanaProgram;
use App\Models\PengmasRencanaProgramAnggaran;
use App\Models\StkholderPerencanaanPpk;
use App\Models\TahunFiskal;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class JumlahAnggaranProgram extends ChartWidget
{
    /**
     * Judul widget chart.
     */
    protected static ?string $heading = 'Jumlah Anggaran Program Terencana';

    /**
     * Urutan sorting widget di dashboard.
     */
    protected static ?int $sort = 2;

    /**
     * Tinggi maksimum widget.
     */
    protected static ?string $maxHeight = '400px';

    /**
     * Menentukan apakah widget dapat dilihat.
     */
    public static function canView(): bool
    {
        return true;
    }

    /**
     * Fungsi utama untuk mengambil dan memformat data untuk chart.
     *
     * @return array
     */
    protected function getData(): array
    {
        // 1. Ambil 5 tahun fiskal terakhir, diurutkan dari yang terlama ke terbaru.
        $lastFiveFiscalYears = TahunFiskal::query()
            ->orderBy('nama_tahun_fiskal', 'desc')
            ->limit(5)
            ->get()
            ->sortBy('nama_tahun_fiskal'); // Urutkan kembali agar chart menampilkan tahun secara kronologis

        if ($lastFiveFiscalYears->isEmpty()) {
            return []; // Kembalikan data kosong jika tidak ada tahun fiskal
        }

        // 2. Siapkan label untuk sumbu-X chart dari nama tahun fiskal.
        $fiscalYearIds = $lastFiveFiscalYears->pluck('id');
        $labels = $lastFiveFiscalYears->pluck('nama_tahun_fiskal')->toArray();

        // 3. Buat sebuah fungsi pembantu (helper) untuk mengambil data anggaran per model.
        // Ini membantu menghindari duplikasi kode.
        $fetchBudgetData = function (string $model, string $budgetColumn, $yearIds) {
            return $model::query()
                ->whereIn('tahun_fiskal', $yearIds)
                ->select(
                    'tahun_fiskal',
                    DB::raw("SUM({$budgetColumn}) as total_anggaran")
                )
                ->groupBy('tahun_fiskal')
                ->get()
                ->pluck('total_anggaran', 'tahun_fiskal'); // Hasilnya: [tahun_fiskal_id => total_anggaran]
        };

        // 4. Panggil fungsi helper untuk setiap model.
        // PENTING: Pastikan nama kolom 'anggaran' dan 'pengajuan_anggaran' sudah benar
        // sesuai dengan nama kolom di tabel database Anda.
        $stkholderBudgets = $fetchBudgetData(StkholderPerencanaanPpk::class, 'pengajuan_anggaran', $fiscalYearIds);
        $kompumedBudgets = $fetchBudgetData(KompumedRencanaProgram::class, 'pengajuan_anggaran', $fiscalYearIds);
        $pengmasBudgets = $fetchBudgetData(PengmasRencanaProgramAnggaran::class, 'pengajuan_anggaran', $fiscalYearIds);

        // 5. Siapkan array data untuk setiap dataset, pastikan setiap tahun ada nilainya (meskipun 0).
        $stkholderData = [];
        $kompumedData = [];
        $pengmasData = [];

        foreach ($lastFiveFiscalYears as $fiscalYear) {
            $yearId = $fiscalYear->id;
            // Gunakan `->get($key, $default)` untuk mendapatkan nilai atau 0 jika tidak ada.
            $stkholderData[] = $stkholderBudgets->get($yearId, 0);
            $kompumedData[] = $kompumedBudgets->get($yearId, 0);
            $pengmasData[] = $pengmasBudgets->get($yearId, 0);
        }

        // 6. Kembalikan struktur data yang sesuai untuk ChartWidget.
        return [
            'datasets' => [
                [
                    'label' => 'Pemangku Kepentingan',
                    'data' => $stkholderData,
                    'backgroundColor' => '#17ad00', // Warna hijau
                    'borderColor' => '#17ad00',
                ],
                [
                    'label' => 'Komunikasi, Publikasi, & Media',
                    'data' => $kompumedData,
                    'backgroundColor' => '#ff7f50', // Warna oranye
                    'borderColor' => '#ff7f50',
                ],
                [
                    'label' => 'Pengembangan Masyarakat',
                    'data' => $pengmasData,
                    'backgroundColor' => '#4682b4', // Warna biru tua
                    'borderColor' => '#4682b4',
                ],
            ],
            'labels' => $labels,
        ];
    }

    /**
     * Menentukan tipe chart.
     *
     * @return string
     */
    protected function getType(): string
    {
        return 'bar';
    }
}
