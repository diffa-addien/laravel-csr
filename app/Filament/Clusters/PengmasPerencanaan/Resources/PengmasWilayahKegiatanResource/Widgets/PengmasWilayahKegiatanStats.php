<?php

namespace App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasWilayahKegiatanResource\Widgets;

use App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasWilayahKegiatanResource;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\PengmasWilayahKegiatan;
use App\Models\TahunFiskal;
use Illuminate\Database\Eloquent\Builder; // <-- Import Builder

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

        $activeTahunFiskalId = TahunFiskal::where('is_active', true)->value('id');

        // 2. Jika tidak ada tahun fiskal yang aktif, jangan tampilkan apa pun.
        if (!$activeTahunFiskalId) {
            $query->whereRaw('1 = 0');
            return [];
        }

        // 3. Terapkan filter whereHas dengan relasi yang sesuai.
        //    ASUMSI: Model ini punya relasi 'kegiatan', dan model Kegiatan punya relasi 'dariProgram'
        //    Dot notation 'kegiatan.dariProgram' digunakan untuk nested relationship.
        $query->whereHas('dariProgram', function (Builder $programQuery) use ($activeTahunFiskalId) {
            $programQuery->where('tahun_fiskal', $activeTahunFiskalId);
        });



        // --- Hanya SATU KALI query ke database menggunakan selectRaw ---
        $stats = $query->selectRaw('COUNT(*) as total_data, SUM(jumlah_penerima) as total_penerima, SUM(anggaran) as total_anggaran')
            ->first();
        // -----------------------------------------------------------

        // Ambil nilai dan format dengan separator ribuan (titik)
        // Menggunakan `?? 0` untuk menangani kasus jika tidak ada data (hasilnya NULL)
        $totalDataFormatted = number_format($stats->total_data ?? 0, 0, ',', '.');
        $totalPenerimaManfaatFormatted = number_format($stats->total_penerima ?? 0, 0, ',', '.');
        $totalAnggaran = number_format($stats->total_anggaran ?? 0, 0, ',', '.');

        return [
            Stat::make('Jumlah Data', $totalDataFormatted)
                ->description('Total data wilayah kegiatan'),
            Stat::make('Jumlah Penerima Manfaat', $totalPenerimaManfaatFormatted)
                ->description('Total penerima manfaat di semua wilayah'),
            Stat::make('Jumlah Anggaran', $totalAnggaran)
                ->description('Rupiah')
        ];
    }
}