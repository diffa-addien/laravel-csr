<?php

namespace App\Filament\Resources\PengmasPelaksanaanKegiatanResource\Pages;

use App\Filament\Resources\PengmasPelaksanaanKegiatanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengmasPelaksanaanKegiatan extends EditRecord
{
    protected static string $resource = PengmasPelaksanaanKegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
