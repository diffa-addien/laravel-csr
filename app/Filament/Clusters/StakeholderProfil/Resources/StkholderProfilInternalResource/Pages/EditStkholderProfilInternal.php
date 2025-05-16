<?php

namespace App\Filament\Clusters\StakeholderProfil\Resources\StkholderProfilInternalResource\Pages;

use App\Filament\Clusters\StakeholderProfil\Resources\StkholderProfilInternalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStkholderProfilInternal extends EditRecord
{
    protected static string $resource = StkholderProfilInternalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
