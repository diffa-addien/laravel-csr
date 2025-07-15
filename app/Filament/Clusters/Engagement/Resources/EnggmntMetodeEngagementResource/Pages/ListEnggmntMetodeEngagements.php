<?php

namespace App\Filament\Clusters\Engagement\Resources\EnggmntMetodeEngagementResource\Pages;

use App\Filament\Clusters\Engagement\Resources\EnggmntMetodeEngagementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEnggmntMetodeEngagements extends ListRecords
{
    protected static string $resource = EnggmntMetodeEngagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
