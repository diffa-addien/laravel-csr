<?php

namespace App\Filament\Clusters\Engagement\Resources\EnggmntMaterialKomunikasiResource\Pages;

use App\Filament\Clusters\Engagement\Resources\EnggmntMaterialKomunikasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEnggmntMaterialKomunikasi extends EditRecord
{
    protected static string $resource = EnggmntMaterialKomunikasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
