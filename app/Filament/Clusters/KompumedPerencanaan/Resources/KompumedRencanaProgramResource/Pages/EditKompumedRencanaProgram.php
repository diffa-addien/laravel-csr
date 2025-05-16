<?php

namespace App\Filament\Clusters\KompumedPerencanaan\Resources\KompumedRencanaProgramResource\Pages;

use App\Filament\Clusters\KompumedPerencanaan\Resources\KompumedRencanaProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKompumedRencanaProgram extends EditRecord
{
    protected static string $resource = KompumedRencanaProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
