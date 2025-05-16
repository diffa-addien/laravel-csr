<?php

namespace App\Filament\Clusters\Wilayah\Resources\DesaResource\Pages;

use App\Filament\Clusters\Wilayah\Resources\DesaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDesa extends EditRecord
{
    protected static string $resource = DesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
