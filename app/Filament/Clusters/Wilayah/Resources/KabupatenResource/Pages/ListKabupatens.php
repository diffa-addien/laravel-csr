<?php

namespace App\Filament\Clusters\Wilayah\Resources\KabupatenResource\Pages;

use App\Filament\Clusters\Wilayah\Resources\KabupatenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKabupatens extends ListRecords
{
    protected static string $resource = KabupatenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
