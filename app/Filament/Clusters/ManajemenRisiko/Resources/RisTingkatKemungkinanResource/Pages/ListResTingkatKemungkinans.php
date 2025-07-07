<?php

namespace App\Filament\Clusters\ManajemenRisiko\Resources\RisTingkatKemungkinanResource\Pages;

use App\Filament\Clusters\ManajemenRisiko\Resources\RisTingkatKemungkinanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResTingkatKemungkinans extends ListRecords
{
    protected static string $resource = RisTingkatKemungkinanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
