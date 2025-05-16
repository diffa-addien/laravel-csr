<?php

namespace App\Filament\Clusters\StakeholderPelaksanaan\Resources\StkholderPelaksanaanPpkResource\Pages;

use App\Filament\Clusters\StakeholderPelaksanaan\Resources\StkholderPelaksanaanPpkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStkholderPelaksanaanPpk extends EditRecord
{
    protected static string $resource = StkholderPelaksanaanPpkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
