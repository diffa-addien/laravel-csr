<?php

namespace App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderEngagementPlanResource\Pages;

use App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderEngagementPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStkholderEngagementPlan extends EditRecord
{
    protected static string $resource = StkholderEngagementPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
