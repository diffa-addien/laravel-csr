<?php

namespace App\Filament\Clusters\Sdg\Resources\SdgIndikatorResource\Pages;

use App\Filament\Clusters\Sdg\Resources\SdgIndikatorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSdgIndikators extends ListRecords
{
    protected static string $resource = SdgIndikatorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
