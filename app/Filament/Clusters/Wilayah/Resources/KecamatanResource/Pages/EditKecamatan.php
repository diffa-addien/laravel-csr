<?php

namespace App\Filament\Clusters\Wilayah\Resources\KecamatanResource\Pages;

use App\Filament\Clusters\Wilayah\Resources\KecamatanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKecamatan extends EditRecord
{
    protected static string $resource = KecamatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
