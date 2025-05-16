<?php

namespace App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasAnalisisProgramResource\Pages;

use App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasAnalisisProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengmasAnalisisProgram extends EditRecord
{
    protected static string $resource = PengmasAnalisisProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
