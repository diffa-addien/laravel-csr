<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Sdg extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?string $navigationGroup = 'Data Induk';
    protected static ?string $navigationLabel = 'SDGs';
    public static ?int $navigationSort = 12;
    protected static ?string $clusterBreadcrumb = 'Data SGDs';
}
