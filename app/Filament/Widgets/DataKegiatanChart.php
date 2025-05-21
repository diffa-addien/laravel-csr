<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class DataKegiatanChart extends ChartWidget
{
    protected static ?string $heading = 'Program Komunikasi dan Publikasi per Bulan';

    protected static ?string $pollingInterval = null;
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $startDate = $this->filterFormData['start_date'] ?? Carbon::now()->startOfMonth()->toDateString();
        $endDate = $this->filterFormData['end_date'] ?? Carbon::now()->endOfMonth()->toDateString();

        $activities = \App\Models\KompumedKegiatan::query()
            // ->whereBetween('tanggal_mulai', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('tanggal_mulai')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->tanggal_mulai)->format('Y-m');
            });

        $datasets = [];
        $labels = [];

        foreach ($activities as $month => $activityGroup) {
            $labels[] = Carbon::parse($month)->format('M Y');
            $datasets[] = $activityGroup->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Kegiatan Program',
                    'data' => $datasets,
                    'backgroundColor' => '#4F46E5',
                    'borderColor' => '#4F46E5',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFormSchema(): array
    {
        return [
            \Filament\Forms\Components\DatePicker::make('start_date')
                ->label('Tanggal Mulai')
                ->displayFormat('Y-m-d')
                ->default(Carbon::now()->startOfMonth()->toDateString()),
            \Filament\Forms\Components\DatePicker::make('end_date')
                ->label('Tanggal Selesai')
                ->displayFormat('Y-m-d')
                ->default(Carbon::now()->endOfMonth()->toDateString()),
        ];
    }

    public function getActiveFilters(): array
    {
        return [
            'start_date' => $this->filterFormData['start_date'] ?? Carbon::now()->startOfMonth()->toDateString(),
            'end_date' => $this->filterFormData['end_date'] ?? Carbon::now()->endOfMonth()->toDateString(),
        ];
    }
}
