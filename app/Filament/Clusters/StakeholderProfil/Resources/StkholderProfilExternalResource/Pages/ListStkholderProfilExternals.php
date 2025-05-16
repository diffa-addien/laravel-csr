<?php

namespace App\Filament\Clusters\StakeholderProfil\Resources\StkholderProfilExternalResource\Pages;

use App\Filament\Clusters\StakeholderProfil\Resources\StkholderProfilExternalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStkholderProfilExternals extends ListRecords
{
    protected static string $resource = StkholderProfilExternalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
