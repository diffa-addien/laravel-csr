<?php

namespace App\Filament\Resources\StrategiResource\Pages;

use App\Filament\Resources\StrategiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStrategis extends ListRecords
{
    protected static string $resource = StrategiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
