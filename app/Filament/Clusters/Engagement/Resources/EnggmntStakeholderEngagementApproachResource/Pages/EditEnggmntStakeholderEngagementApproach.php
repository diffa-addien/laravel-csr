<?php

namespace App\Filament\Clusters\Engagement\Resources\EnggmntStakeholderEngagementApproachResource\Pages;

use App\Filament\Clusters\Engagement\Resources\EnggmntStakeholderEngagementApproachResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEnggmntStakeholderEngagementApproach extends EditRecord
{
    protected static string $resource = EnggmntStakeholderEngagementApproachResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
