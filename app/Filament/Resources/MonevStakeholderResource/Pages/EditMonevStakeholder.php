<?php

namespace App\Filament\Resources\MonevStakeholderResource\Pages;

use App\Filament\Resources\MonevStakeholderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMonevStakeholder extends EditRecord
{
    protected static string $resource = MonevStakeholderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
