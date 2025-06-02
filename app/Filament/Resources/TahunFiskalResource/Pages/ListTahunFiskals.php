<?php

namespace App\Filament\Resources\TahunFiskalResource\Pages;

use App\Filament\Resources\TahunFiskalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTahunFiskals extends ListRecords
{
    protected static string $resource = TahunFiskalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
