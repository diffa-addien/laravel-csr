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

        // Apply filters from Livewire state
        if (isset($this->filters['program_id'])) {
            $query->where('program_id', $this->filters['program_id']);
        }

        // Add other filters if needed
        // if (isset($this->filters['other_filter'])) {
        //     $query->where('other_column', $this->filters['other_filter']);
        // }

        $totalData = $query->count();
        $totalPenerimaManfaat = $query->sum('jumlah_penerima');

        return [
            Stat::make('Jumlah Data', $totalData)
                ->description('Total data wilayah kegiatan'),
            Stat::make('Jumlah Penerima Manfaat', $totalPenerimaManfaat)
                ->description('Total penerima manfaat di semua wilayah')
        ];
    }
}