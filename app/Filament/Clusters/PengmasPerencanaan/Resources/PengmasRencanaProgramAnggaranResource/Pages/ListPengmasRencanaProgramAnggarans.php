<?php

namespace App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasRencanaProgramAnggaranResource\Pages;

use App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasRencanaProgramAnggaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengmasRencanaProgramAnggarans extends ListRecords
{
    protected static string $resource = PengmasRencanaProgramAnggaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
