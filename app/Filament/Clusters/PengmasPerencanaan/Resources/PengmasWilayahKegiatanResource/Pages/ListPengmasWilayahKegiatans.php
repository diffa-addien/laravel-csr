<?php

namespace App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasWilayahKegiatanResource\Pages;

use App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasWilayahKegiatanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengmasWilayahKegiatans extends ListRecords
{
    protected static string $resource = PengmasWilayahKegiatanResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PengmasWilayahKegiatanResource\Widgets\PengmasWilayahKegiatanStats::class,
        ];
    }
}
