<?php

namespace App\Filament\Clusters\StakeholderPelaksanaan\Resources\StkholderAnalisisResource\Pages;

use App\Filament\Clusters\StakeholderPelaksanaan\Resources\StkholderAnalisisResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStkholderAnalisis extends ListRecords
{
    protected static string $resource = StkholderAnalisisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
