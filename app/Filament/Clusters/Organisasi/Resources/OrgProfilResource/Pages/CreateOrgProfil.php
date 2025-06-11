<?php

namespace App\Filament\Clusters\Organisasi\Resources\OrgProfilResource\Pages;

use App\Filament\Clusters\Organisasi\Resources\OrgProfilResource;
use Filament\Actions;
use App\Models\OrgProfil;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Form;

class CreateOrgProfil extends CreateRecord
{
    protected static string $resource = OrgProfilResource::class;

    protected function getFormActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalSubmitAction(false) // Menonaktifkan tombol 'Buat & buat lainnya'
                ->using(function (array $data, Form $form): OrgProfil {
                    return static::getModel()::create($data);
                }),
        ];
    }
}