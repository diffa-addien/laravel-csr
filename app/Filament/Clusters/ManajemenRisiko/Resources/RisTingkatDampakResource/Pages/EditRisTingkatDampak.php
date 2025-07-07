<?php

namespace App\Filament\Clusters\ManajemenRisiko\Resources\RisTingkatDampakResource\Pages;

use App\Filament\Clusters\ManajemenRisiko\Resources\RisTingkatDampakResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRisTingkatDampak extends EditRecord
{
    protected static string $resource = RisTingkatDampakResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
