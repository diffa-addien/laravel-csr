<?php

namespace App\Filament\Widgets;

use App\Models\TahunFiskal;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class AnggaranTahunFiskal extends ChartWidget
{
    // Judul yang akan tampil di atas chart
    protected static ?string $heading = 'Anggaran CSR (PT Pantai Indah Kapuk Dua)';

    // Atur tinggi chart jika diperlukan
    protected static ?string $maxHeight = '300px';
    
    // Memberikan urutan pada widget di dashboard (opsional)
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // 1. Dapatkan daftar 4 tahun terakhir (misal: [2022, 2023, 2024, 2025])
        $currentYear = Carbon::now()->year;
        $years = [];
        for ($i = 3; $i >= 0; $i--) {
            $years[] = $currentYear - $i;
        }

        // 2. Ambil data anggaran dari database untuk tahun-tahun tersebut
        // Menggunakan pluck() untuk membuat array asosiatif [tahun => anggaran]
        $anggaranData = TahunFiskal::whereIn('nama_tahun_fiskal', $years)
                                   ->pluck('anggaran', 'nama_tahun_fiskal');

        // 3. Siapkan array data untuk chart, pastikan urutannya benar
        // Jika data tahun tidak ada di DB, anggarannya akan menjadi 0
        $data = [];
        foreach ($years as $year) {
            $data[] = $anggaranData[$year] ?? 0;
        }

        // 4. Kembalikan data dalam format yang dibutuhkan oleh Chart.js
        return [
            'datasets' => [
                [
                    'label' => 'Anggaran',
                    'data' => $data,
                    // Anda bisa sesuaikan warna agar mirip dengan contoh gambar
                    'backgroundColor' => 'rgba(0, 124, 12, 0.88)',
                    'borderColor' => 'rgba(0, 124, 12, 0.88)',
                ],
            ],
            'labels' => $years, // Label untuk sumbu-X
        ];
    }

    protected function getType(): string
    {
        // Tipe chart yang diinginkan adalah 'bar'
        return 'bar';
    }
}