<?php

namespace App\Filament\Clusters\KompumedPerencanaan\Resources\KompumedKegiatanResource\Pages;

use App\Filament\Clusters\KompumedPerencanaan\Resources\KompumedKegiatanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKompumedKegiatan extends EditRecord
{
    protected static string $resource = KompumedKegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
