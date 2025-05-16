<?php

namespace App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderPerencanaanProgramAnggaranResource\Pages;

use App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderPerencanaanProgramAnggaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStkholderPerencanaanProgramAnggaran extends EditRecord
{
    protected static string $resource = StkholderPerencanaanProgramAnggaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
