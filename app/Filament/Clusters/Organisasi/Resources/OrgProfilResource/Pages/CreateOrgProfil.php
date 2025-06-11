<?php

namespace App\Filament\Clusters\Organisasi\Resources\OrgProfilResource\Pages;

use App\Filament\Clusters\Organisasi\Resources\OrgProfilResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrgProfil extends CreateRecord
{
    protected static string $resource = OrgProfilResource::class;

    protected function getFormActions(): array
    {
        return [
            Actions\CreateAction::make()->disableCreateAnother()
            ->label('Simpan Data Organisasi CSR'),
        ];
    }
}
