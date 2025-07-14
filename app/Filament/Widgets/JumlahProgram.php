<?php

namespace App\Filament\Widgets;

use App\Models\KompumedRencanaProgram;
use App\Models\PengmasRencanaProgramAnggaran;
use App\Models\StkholderPerencanaanPpk;
use App\Models\TahunFiskal;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class JumlahProgram extends ChartWidget
{
    /**
     * Judul widget chart.
     */
    protected static ?string $heading = 'Jumlah Program Terencana';

    /**
     * Urutan sorting widget di dashboard. Letakkan setelah widget anggaran.
     */
    protected static ?int $sort = 6;

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
            ->sortBy('nama_tahun_fiskal');

        if ($lastFiveFiscalYears->isEmpty()) {
            return [];
        }

        // 2. Siapkan label untuk sumbu-X chart dari nama tahun fiskal.
        $fiscalYearIds = $lastFiveFiscalYears->pluck('id');
        $labels = $lastFiveFiscalYears->pluck('nama_tahun_fiskal')->toArray();

        // 3. Buat fungsi pembantu (helper) untuk MENGHITUNG jumlah data per model.
        $fetchCountData = function (string $model, $yearIds) {
            return $model::query()
                ->whereIn('tahun_fiskal', $yearIds)
                ->select(
                    'tahun_fiskal',
                    // PERUBAHAN UTAMA: Menggunakan COUNT(*) untuk menghitung jumlah baris/kegiatan
                    DB::raw("COUNT(*) as total_program")
                )
                ->groupBy('tahun_fiskal')
                ->get()
                ->pluck('total_program', 'tahun_fiskal'); // Hasilnya: [tahun_fiskal_id => jumlah_kegiatan]
        };

        // 4. Panggil fungsi helper untuk setiap model.
        $stkholderCounts = $fetchCountData(StkholderPerencanaanPpk::class, $fiscalYearIds);
        $kompumedCounts = $fetchCountData(KompumedRencanaProgram::class, $fiscalYearIds);
        $pengmasCounts = $fetchCountData(PengmasRencanaProgramAnggaran::class, $fiscalYearIds);

        // 5. Siapkan array data untuk setiap dataset, pastikan setiap tahun ada nilainya (meskipun 0).
        $stkholderData = [];
        $kompumedData = [];
        $pengmasData = [];

        foreach ($lastFiveFiscalYears as $fiscalYear) {
            $yearId = $fiscalYear->id;
            // Gunakan `->get($key, $default)` untuk mendapatkan nilai atau 0 jika tidak ada.
            $stkholderData[] = $stkholderCounts->get($yearId, 0);
            $kompumedData[] = $kompumedCounts->get($yearId, 0);
            $pengmasData[] = $pengmasCounts->get($yearId, 0);
        }

        // 6. Kembalikan struktur data yang sesuai untuk ChartWidget.
        return [
            'datasets' => [
                [
                    'label' => 'Pemangku Kepentingan (Jumlah)',
                    'data' => $stkholderData,
                    'backgroundColor' => '#17ad00', // Warna hijau
                    'borderColor' => '#17ad00',
                ],
                [
                    'label' => 'Komunikasi, Publikasi, & Media (Jumlah)',
                    'data' => $kompumedData,
                    'backgroundColor' => '#ff7f50', // Warna oranye
                    'borderColor' => '#ff7f50',
                ],
                [
                    'label' => 'Pengembangan Masyarakat (Jumlah)',
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