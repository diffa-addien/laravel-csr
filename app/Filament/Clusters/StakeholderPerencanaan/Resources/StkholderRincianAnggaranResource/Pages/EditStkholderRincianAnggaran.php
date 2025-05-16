<?php

namespace App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderRincianAnggaranResource\Pages;

use App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderRincianAnggaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStkholderRincianAnggaran extends EditRecord
{
    protected static string $resource = StkholderRincianAnggaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
