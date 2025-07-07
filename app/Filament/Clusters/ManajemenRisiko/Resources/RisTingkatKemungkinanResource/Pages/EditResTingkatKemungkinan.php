<?php

namespace App\Filament\Clusters\ManajemenRisiko\Resources\RisTingkatKemungkinanResource\Pages;

use App\Filament\Clusters\ManajemenRisiko\Resources\RisTingkatKemungkinanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResTingkatKemungkinan extends EditRecord
{
    protected static string $resource = RisTingkatKemungkinanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
