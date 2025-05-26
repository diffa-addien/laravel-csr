<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Wilayah extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-view-columns';

    protected static ?string $navigationGroup = 'Data Induk';
    protected static ?string $navigationParentItem = 'Regional';
    public static ?int $navigationGroupSort = 8;

    protected static ?string $clusterBreadcrumb = 'Data Wilayah';

}
