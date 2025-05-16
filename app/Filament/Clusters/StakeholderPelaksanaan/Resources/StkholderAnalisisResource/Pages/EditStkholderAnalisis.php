<?php

namespace App\Filament\Clusters\StakeholderPelaksanaan\Resources\StkholderAnalisisResource\Pages;

use App\Filament\Clusters\StakeholderPelaksanaan\Resources\StkholderAnalisisResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStkholderAnalisis extends EditRecord
{
    protected static string $resource = StkholderAnalisisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
