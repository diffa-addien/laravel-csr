<?php

namespace App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasWilayahKegiatanResource\Widgets;

use App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasWilayahKegiatanResource;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\Widget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\PengmasWilayahKegiatan;
use App\Models\TahunFiskal;
use Illuminate\Database\Eloquent\Builder; // <-- Import Builder
use Livewire\Attributes\On;

class PengmasWilayahKegiatanStats extends Widget
{
    // protected static string $view = 'filament.widgets.pengmas-kegiatan-wilayah';

    public $totalDataFormatted;
    public $totalPenerimaManfaatFormatted;
    public $totalAnggaran;

    protected static string $view = 'filament.widgets.pengmas-kegiatan-wilayah';

    #[On('refreshWidget')]
    public function refreshStats(): void
    {
        $query = PengmasWilayahKegiatan::query();
        $activeTahunFiskalId = TahunFiskal::where('is_active', true)->value('id');

        if (!$activeTahunFiskalId) {
            $this->totalDataFormatted = '0';
            $this->totalPenerimaManfaatFormatted = '0';
            $this->totalAnggaran = 'Rp 0';
            return;
        }

        $query->whereHas('dariProgram', function (Builder $programQuery) use ($activeTahunFiskalId) {
            $programQuery->where('tahun_fiskal', $activeTahunFiskalId);
        });

        $stats = $query
            ->selectRaw('COUNT(*) as total_data, SUM(jumlah_penerima) as total_penerima, SUM(anggaran) as total_anggaran')
            ->first();

        $this->totalDataFormatted = number_format($stats->total_data ?? 0, 0, ',', '.');
        $this->totalPenerimaManfaatFormatted = number_format($stats->total_penerima ?? 0, 0, ',', '.');
        $this->totalAnggaran = 'Rp ' . number_format($stats->total_anggaran ?? 0, 0, ',', '.');
    }

    public function mount(): void
    {
        $this->refreshStats();
    }

    public function getColumnSpan(): int|string
    {
        return 'full';
    }

}