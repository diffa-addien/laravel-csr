<?php

namespace App\Filament\Resources\MonevPengmasResource\Pages;

use App\Filament\Resources\MonevPengmasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMonevPengmas extends ListRecords
{
    protected static string $resource = MonevPengmasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
