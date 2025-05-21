<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = null;
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $startDate = $this->filterFormData['start_date'] ?? Carbon::now()->startOfMonth()->toDateString();
        $endDate = $this->filterFormData['end_date'] ?? Carbon::now()->endOfMonth()->toDateString();

        return [
            Stat::make('Jumlah Akun Pengguna', $this->getUsers($startDate, $endDate))
                ->description('Akun untuk mengakses sistem')
                ->icon('heroicon-o-clipboard-document-list'),
            Stat::make('Jumlah Program Pemangku Kepentingan', $this->getTotalStakeholders($startDate, $endDate))
                ->description('Periode ' . Carbon::parse($startDate)->format('d M Y') . ' - ' . Carbon::parse($endDate)->format('d M Y'))
                ->icon('heroicon-o-user-group'),
            Stat::make('Jumlah Kegiatan Kompumed', $this->getTotalKegiatanKompumed($startDate, $endDate))
                ->description('Periode ' . Carbon::parse($startDate)->format('d M Y') . ' - ' . Carbon::parse($endDate)->format('d M Y'))
                ->icon('heroicon-o-calendar'),
        ];
    }

    protected function getTotalStakeholders(string $startDate, string $endDate): int
    {
        return \App\Models\StkholderPerencanaanPpk::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->count();
    }

    protected function getUsers(string $startDate, string $endDate): int
    {
        return \App\Models\User::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->count();
    }

    protected function getTotalKegiatanKompumed(string $startDate, string $endDate): int
    {
        return \App\Models\KompumedKegiatan::whereBetween('tanggal_mulai', [$startDate, $endDate])
            ->whereBetween('tanggal_selesai', [$startDate, $endDate])
            ->count();
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
}