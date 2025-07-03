<?php

namespace App\Filament\Resources\ManajemenIsuResource\Pages;

use App\Filament\Resources\ManajemenIsuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManajemenIsu extends EditRecord
{
    protected static string $resource = ManajemenIsuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
