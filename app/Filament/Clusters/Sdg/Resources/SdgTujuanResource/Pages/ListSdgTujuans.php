<?php

namespace App\Filament\Clusters\Sdg\Resources\SdgTujuanResource\Pages;

use App\Filament\Clusters\Sdg\Resources\SdgTujuanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSdgTujuans extends ListRecords
{
    protected static string $resource = SdgTujuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
