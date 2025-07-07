<?php

namespace App\Filament\Clusters\ManajemenRisiko\Resources\RisTingkatDampakResource\Pages;

use App\Filament\Clusters\ManajemenRisiko\Resources\RisTingkatDampakResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRisTingkatDampaks extends ListRecords
{
    protected static string $resource = RisTingkatDampakResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
