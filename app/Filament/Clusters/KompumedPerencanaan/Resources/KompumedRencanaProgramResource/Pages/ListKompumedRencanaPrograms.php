<?php

namespace App\Filament\Clusters\KompumedPerencanaan\Resources\KompumedRencanaProgramResource\Pages;

use App\Filament\Clusters\KompumedPerencanaan\Resources\KompumedRencanaProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKompumedRencanaPrograms extends ListRecords
{
    protected static string $resource = KompumedRencanaProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
