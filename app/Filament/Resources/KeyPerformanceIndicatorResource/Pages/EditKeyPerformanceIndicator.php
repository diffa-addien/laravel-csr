<?php

namespace App\Filament\Resources\KeyPerformanceIndicatorResource\Pages;

use App\Filament\Resources\KeyPerformanceIndicatorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKeyPerformanceIndicator extends EditRecord
{
    protected static string $resource = KeyPerformanceIndicatorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
