<?php

namespace App\Filament\Clusters\Engagement\Resources\EnggmntMetodeEngagementResource\Pages;

use App\Filament\Clusters\Engagement\Resources\EnggmntMetodeEngagementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEnggmntMetodeEngagement extends EditRecord
{
    protected static string $resource = EnggmntMetodeEngagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
