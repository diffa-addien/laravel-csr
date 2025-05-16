<?php

namespace App\Filament\Resources\MonevKompumedResource\Pages;

use App\Filament\Resources\MonevKompumedResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMonevKompumeds extends ListRecords
{
    protected static string $resource = MonevKompumedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
