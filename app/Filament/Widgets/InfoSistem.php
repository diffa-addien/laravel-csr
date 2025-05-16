<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InfoSistem extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Versi Aplikasi', 'v1.0.1')
                ->description('Update terbaru Mei 2025')
                ->icon('heroicon-o-code-bracket'),

            Stat::make('Developer', 'PT Tomo Teknologi')
                ->description('Kontak: support@tomoteknologi.id')
                ->icon('heroicon-o-user-group'),
        ];
    }

    protected function getColumns(): int
    {
        return 2; // Total kolom = 2 (otomatis 50%)
    }
}
