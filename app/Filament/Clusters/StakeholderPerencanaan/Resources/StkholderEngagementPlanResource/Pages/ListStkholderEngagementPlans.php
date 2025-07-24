<?php

namespace App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderEngagementPlanResource\Pages;

use App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderEngagementPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStkholderEngagementPlans extends ListRecords
{
    protected static string $resource = StkholderEngagementPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
