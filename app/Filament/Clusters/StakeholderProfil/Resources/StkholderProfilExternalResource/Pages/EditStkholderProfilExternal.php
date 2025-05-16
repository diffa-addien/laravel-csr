<?php

namespace App\Filament\Clusters\StakeholderProfil\Resources\StkholderProfilExternalResource\Pages;

use App\Filament\Clusters\StakeholderProfil\Resources\StkholderProfilExternalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStkholderProfilExternal extends EditRecord
{
    protected static string $resource = StkholderProfilExternalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
