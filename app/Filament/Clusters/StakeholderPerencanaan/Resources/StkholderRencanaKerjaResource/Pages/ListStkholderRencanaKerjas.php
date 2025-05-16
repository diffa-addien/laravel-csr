<?php

namespace App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderRencanaKerjaResource\Pages;

use App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderRencanaKerjaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStkholderRencanaKerjas extends ListRecords
{
    protected static string $resource = StkholderRencanaKerjaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
