<?php

namespace App\Filament\Clusters\Engagement\Resources\KategoriStakeholderResource\Pages;

use App\Filament\Clusters\Engagement\Resources\KategoriStakeholderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKategoriStakeholder extends EditRecord
{
    protected static string $resource = KategoriStakeholderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
