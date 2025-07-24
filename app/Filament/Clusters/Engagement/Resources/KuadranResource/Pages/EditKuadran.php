<?php

namespace App\Filament\Clusters\Engagement\Resources\KuadranResource\Pages;

use App\Filament\Clusters\Engagement\Resources\KuadranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKuadran extends EditRecord
{
    protected static string $resource = KuadranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
