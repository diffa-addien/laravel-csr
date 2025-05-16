<?php

namespace App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderPerencanaanProgramAnggaranResource\Pages;

use App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderPerencanaanProgramAnggaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStkholderPerencanaanProgramAnggarans extends ListRecords
{
    protected static string $resource = StkholderPerencanaanProgramAnggaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
