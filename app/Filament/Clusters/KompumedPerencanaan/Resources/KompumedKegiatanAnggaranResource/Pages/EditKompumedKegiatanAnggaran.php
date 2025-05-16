<?php

namespace App\Filament\Clusters\KompumedPerencanaan\Resources\KompumedKegiatanAnggaranResource\Pages;

use App\Filament\Clusters\KompumedPerencanaan\Resources\KompumedKegiatanAnggaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKompumedKegiatanAnggaran extends EditRecord
{
    protected static string $resource = KompumedKegiatanAnggaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
