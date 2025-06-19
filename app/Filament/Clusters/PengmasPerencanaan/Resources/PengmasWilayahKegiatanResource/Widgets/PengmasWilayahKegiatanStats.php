<?php

namespace App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasWilayahKegiatanResource\Widgets;

use App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasWilayahKegiatanResource;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\PengmasWilayahKegiatan;

class PengmasWilayahKegiatanStats extends BaseWidget
{
    public array $filters = [];

    public function getStats(): array
    {
        $query = PengmasWilayahKegiatan::query();

        // Terapkan filter dari state Livewire
        if (!empty($this->filters['program_id'])) {
            $query->where('program_id', $this->filters['program_id']);
        }

        // ... filter lainnya ...

        // --- Hanya SATU KALI query ke database menggunakan selectRaw ---
        $stats = $query->selectRaw('COUNT(*) as total_data, SUM(jumlah_penerima) as total_penerima')
            ->first();
        // -----------------------------------------------------------

        // Ambil nilai dan format dengan separator ribuan (titik)
        // Menggunakan `?? 0` untuk menangani kasus jika tidak ada data (hasilnya NULL)
        $totalDataFormatted = number_format($stats->total_data ?? 0, 0, ',', '.');
        $totalPenerimaManfaatFormatted = number_format($stats->total_penerima ?? 0, 0, ',', '.');

        return [
            Stat::make('Jumlah Data', $totalDataFormatted)
                ->description('Total data wilayah kegiatan'),
            Stat::make('Jumlah Penerima Manfaat', $totalPenerimaManfaatFormatted)
                ->description('Total penerima manfaat di semua wilayah')
        ];
    }
}