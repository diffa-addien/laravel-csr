<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use App\Models\StkholderPerencanaanProgramAnggaran;
use App\Models\KompumedKegiatanAnggaran;
use App\Models\PengmasRencanaProgramAnggaran;
use Illuminate\Support\Facades\DB; // Import DB facade


class BiayaTerencanaChart extends ChartWidget
{
    protected static ?string $heading = 'Rencana Anggaran Tahun 2025';

    protected static ?int $sort = 4;
    protected static ?string $maxHeight = '400px';

    protected function getData(): array
    {
        $currentYear = Carbon::now()->year;

        $stkholderBiaya = StkholderPerencanaanProgramAnggaran::whereYear('created_at', $currentYear)->sum('anggaran_pengajuan');
        $kompumedBiaya = KompumedKegiatanAnggaran::whereYear('created_at', $currentYear)
            ->sum(DB::raw('biaya * kuantitas'));
        $pengmasBiaya = PengmasRencanaProgramAnggaran::whereYear('created_at', $currentYear)->sum('pengajuan_anggaran');

        return [
            'datasets' => [
                [
                    'label' => 'Anggaran',
                    'data' => [
                        $stkholderBiaya,
                        $kompumedBiaya,
                        $pengmasBiaya,
                    ],
                    'backgroundColor' => [
                        '#17ad00', // Warna hijau
                        '#ff7f50', // Warna oranye
                        '#4682b4', // Warna biru tua
                    ],
                    'borderColor' => [
                        '#17ad00',
                        '#ff7f50',
                        '#4682b4',
                    ],
                ],
            ],
            'labels' => [
                'Pemangku Kepentingan',
                'Komunikasi, Publikasi, dan Media',
                'Pengembangan Masyarakat',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}