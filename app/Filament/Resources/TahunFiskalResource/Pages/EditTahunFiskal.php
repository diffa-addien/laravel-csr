<?php

namespace App\Filament\Resources\TahunFiskalResource\Pages;

use App\Filament\Resources\TahunFiskalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTahunFiskal extends EditRecord
{
    protected static string $resource = TahunFiskalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
