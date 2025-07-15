<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Engagement extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $navigationGroup = 'Data Induk';
    protected static ?string $navigationLabel = "Engagement";
    protected static ?string $clusterBreadcrumb = 'Engagement';
    protected static ?int $navigationSort = 9;
    
}
