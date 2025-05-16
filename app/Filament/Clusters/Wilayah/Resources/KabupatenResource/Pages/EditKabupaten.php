<?php

namespace App\Filament\Clusters\Wilayah\Resources\KabupatenResource\Pages;

use App\Filament\Clusters\Wilayah\Resources\KabupatenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKabupaten extends EditRecord
{
    protected static string $resource = KabupatenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
