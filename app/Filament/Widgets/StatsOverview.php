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
                ->icon('heroicon-o-clipboard-document-list')
                ->color('info') // Warna hijau
                ->extraAttributes([
                    'class' => 'border rounded-2xl shadow-lg'
                ]),
            Stat::make('Jumlah Pemangku Kepentingan (Internal)', $this->getTotalHolderInternal($startDate, $endDate))
                ->description('Orang ')
                ->icon('heroicon-o-user-group')
                ->color('info') // Warna kuning
                ->extraAttributes([
                    'class' => 'border rounded-2xl shadow-lg'
                ]),
            Stat::make('Jumlah Pemangku Kepentingan (External)', $this->getTotalHolderExternal($startDate, $endDate))
                ->description('Orang ')
                ->icon('heroicon-o-globe-alt')
                ->color('info') // Warna merah
                ->extraAttributes([
                    'class' => 'border rounded-2xl shadow-lg'
                ]),
        ];
    }

    protected function getTotalHolderInternal(string $startDate, string $endDate): int
    {
        return \App\Models\StkholderProfilInternal::count();
    }

    protected function getUsers(string $startDate, string $endDate): int
    {
        return \App\Models\User::count();
    }

    protected function getTotalHolderExternal(string $startDate, string $endDate): int
    {
        return \App\Models\StkholderProfilExternal::count();
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
