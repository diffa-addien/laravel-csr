<?php

namespace App\Filament\Resources\KompumedPelaksanaanKegiatanResource\Pages;

use App\Filament\Resources\KompumedPelaksanaanKegiatanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKompumedPelaksanaanKegiatans extends ListRecords
{
    protected static string $resource = KompumedPelaksanaanKegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
