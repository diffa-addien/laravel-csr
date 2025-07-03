<?php

namespace App\Filament\Resources\ManajemenIsuResource\Pages;

use App\Filament\Resources\ManajemenIsuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManajemenIsus extends ListRecords
{
    protected static string $resource = ManajemenIsuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
