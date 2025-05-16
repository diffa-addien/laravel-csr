<?php

namespace App\Filament\Resources\MonevKompumedResource\Pages;

use App\Filament\Resources\MonevKompumedResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMonevKompumed extends EditRecord
{
    protected static string $resource = MonevKompumedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
