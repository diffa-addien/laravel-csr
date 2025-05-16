<?php

namespace App\Filament\Clusters\Sdg\Resources\SdgTargetResource\Pages;

use App\Filament\Clusters\Sdg\Resources\SdgTargetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSdgTargets extends ListRecords
{
    protected static string $resource = SdgTargetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
