<?php

namespace App\Filament\Resources\MonevPengmasResource\Pages;

use App\Filament\Resources\MonevPengmasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMonevPengmas extends EditRecord
{
    protected static string $resource = MonevPengmasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
