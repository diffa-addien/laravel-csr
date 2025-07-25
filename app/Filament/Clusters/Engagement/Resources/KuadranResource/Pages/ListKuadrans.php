<?php

namespace App\Filament\Clusters\Engagement\Resources\KuadranResource\Pages;

use App\Filament\Clusters\Engagement\Resources\KuadranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKuadrans extends ListRecords
{
    protected static string $resource = KuadranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
