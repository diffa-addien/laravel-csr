<?php

namespace App\Filament\Resources\StrategiResource\Pages;

use App\Filament\Resources\StrategiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStrategi extends EditRecord
{
    protected static string $resource = StrategiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
