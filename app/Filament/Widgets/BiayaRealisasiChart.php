<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use App\Models\StkholderPelaksanaanPpk;
use App\Models\KompumedPelaksanaanKegiatan;
use App\Models\PengmasPelaksanaanKegiatan;

class BiayaRealisasiChart extends ChartWidget
{
    protected static ?string $heading = 'Anggaran Terealiasi Tahun 2025';

    protected static ?int $sort = 5;
    protected static ?string $maxHeight = '400px';

    protected function getData(): array
    {
        $currentYear = Carbon::now()->year;

        $stkholderBiaya = StkholderPelaksanaanPpk::whereYear('tanggal_pelaksanaan', $currentYear)->sum('biaya');
        $kompumedBiaya = KompumedPelaksanaanKegiatan::whereYear('tanggal_pelaksanaan', $currentYear)->sum('biaya');
        $pengmasBiaya = PengmasPelaksanaanKegiatan::whereYear('tanggal_pelaksanaan', $currentYear)->sum('anggaran_pelaksanaan');

        return [
            'datasets' => [
                [
                    'label' => 'Pemangku Kepentingan',
                    'data' => [$stkholderBiaya],
                    'backgroundColor' => ['#17ad00'], // Warna hijau
                    'borderColor' => ['#17ad00'],
                ],
                [
                    'label' => 'Komunikasi, Publikasi, dan Media',
                    'data' => [$kompumedBiaya],
                    'backgroundColor' => ['#ff7f50'], // Warna oranye
                    'borderColor' => ['#ff7f50'],
                ],
                [
                    'label' => 'Pengembangan Masyarakat',
                    'data' => [$pengmasBiaya],
                    'backgroundColor' => ['#4682b4'], // Warna biru tua
                    'borderColor' => ['#4682b4'],
                ],
            ],
            'labels' => [
                'Anggaran',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
