<?php

namespace App\Filament\Widgets;

use App\Models\PengmasRencanaProgramAnggaran;
use App\Models\TahunFiskal; // Pastikan model ini ada
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AnggaranProgramPengmas extends ChartWidget
{

    protected static ?string $heading = 'Anggaran Program Pengembangan Masyarakat';

    protected static ?int $sort = 3;
    protected static ?string $maxHeight = '400px';

    protected string|int|array $columnSpan = 1;

    public array $records = [];

    public static function canView(): bool
    {
        return false;
    }
    protected function getData(): array
    {
        // 1. Ambil 4 tahun fiskal terakhir, diurutkan dari yang terlama ke terbaru.
        $lastFourFiscalYears = TahunFiskal::query()
            ->orderBy('nama_tahun_fiskal', 'desc')
            ->limit(5)
            ->get()
            ->sortBy('nama_tahun_fiskal'); // Urutkan kembali agar chart menampilkan tahun secara kronologis (misal: 2022, 2023, 2024, 2025)

        if ($lastFourFiscalYears->isEmpty()) {
            return []; // Kembalikan data kosong jika tidak ada tahun fiskal
        }

        // 2. Ambil ID dari tahun-tahun tersebut untuk filtering query
        $fiscalYearIds = $lastFourFiscalYears->pluck('id');

        // 3. Query data anggaran, gabungkan dengan tahun fiskal, dan kelompokkan
        $budgetData = PengmasRencanaProgramAnggaran::query()
            ->join('tahun_fiskals', 'pengmas_rencana_program_anggarans.tahun_fiskal', '=', 'tahun_fiskals.id')
            ->whereIn('tahun_fiskal', $fiscalYearIds)
            ->select(
                'tahun_fiskal',
                'tahun_fiskals.nama_tahun_fiskal',
                DB::raw('SUM(pengajuan_anggaran) as total_anggaran')
            )
            ->groupBy('tahun_fiskal', 'tahun_fiskals.nama_tahun_fiskal')
            ->orderBy('tahun_fiskals.nama_tahun_fiskal', 'asc')
            ->get()
            ->keyBy('tahun_fiskal'); // Jadikan 'tahun_fiskal' sebagai key untuk pencarian mudah

        // 4. Siapkan data untuk chart, pastikan setiap tahun ada nilainya (meskipun 0)
        $labels = [];
        $data = [];

        foreach ($lastFourFiscalYears as $fiscalYear) {
            // Ambil nama tahun sebagai label
            $labels[] = $fiscalYear->nama_tahun_fiskal;

            // Cek apakah ada data anggaran untuk tahun ini, jika tidak, anggap 0
            $data[] = $budgetData->get($fiscalYear->id)->total_anggaran ?? 0;
        }

        // 5. Kembalikan struktur data yang sesuai untuk ChartWidget
        return [
            'datasets' => [
                [
                    'label' => 'Total Anggaran (Rp)',
                    'data' => $data,
                    'backgroundColor' => 'rgba(0, 96, 141, 1)', // Menggunakan warna biru yang sama
                    'borderColor' => 'rgba(0, 96, 141, 1)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        // 'bar' cocok untuk perbandingan antar tahun.
        // Anda juga bisa menggunakan 'line' untuk melihat tren.
        return 'bar';
    }
}