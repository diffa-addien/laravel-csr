<?php

namespace App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasWilayahKegiatanResource\Pages;

use App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasWilayahKegiatanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengmasWilayahKegiatan extends EditRecord
{
    protected static string $resource = PengmasWilayahKegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
