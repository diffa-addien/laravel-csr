<?php

namespace App\Filament\Clusters\Wilayah\Resources\ProvinsiResource\Pages;

use App\Filament\Clusters\Wilayah\Resources\ProvinsiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProvinsi extends EditRecord
{
    protected static string $resource = ProvinsiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
