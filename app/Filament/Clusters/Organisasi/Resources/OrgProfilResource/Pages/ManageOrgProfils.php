<?php

namespace App\Filament\Clusters\Organisasi\Resources\OrgProfilResource\Pages;

use App\Filament\Clusters\Organisasi\Resources\OrgProfilResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageOrgProfils extends ManageRecords
{
    protected static string $resource = OrgProfilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
