<?php

namespace App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderPerencanaanPpkResource\Pages;

use App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderPerencanaanPpkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStkholderPerencanaanPpk extends EditRecord
{
    protected static string $resource = StkholderPerencanaanPpkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
