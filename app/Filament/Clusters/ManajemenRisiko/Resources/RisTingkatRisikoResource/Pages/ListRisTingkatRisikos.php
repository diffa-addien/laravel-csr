<?php

namespace App\Filament\Clusters\ManajemenRisiko\Resources\RisTingkatRisikoResource\Pages;

use App\Filament\Clusters\ManajemenRisiko\Resources\RisTingkatRisikoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRisTingkatRisikos extends ListRecords
{
    protected static string $resource = RisTingkatRisikoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
