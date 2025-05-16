<?php

namespace App\Filament\Clusters\Organisasi\Resources\OrgPenugasanResource\Pages;

use App\Filament\Clusters\Organisasi\Resources\OrgPenugasanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrgPenugasan extends EditRecord
{
    protected static string $resource = OrgPenugasanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
