<?php

namespace App\Filament\Resources\RegionalResource\Pages;

use App\Filament\Resources\RegionalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRegionals extends ListRecords
{
    protected static string $resource = RegionalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
