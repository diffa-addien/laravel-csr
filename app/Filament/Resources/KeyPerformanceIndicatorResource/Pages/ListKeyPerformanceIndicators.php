<?php

namespace App\Filament\Resources\KeyPerformanceIndicatorResource\Pages;

use App\Filament\Resources\KeyPerformanceIndicatorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKeyPerformanceIndicators extends ListRecords
{
    protected static string $resource = KeyPerformanceIndicatorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
