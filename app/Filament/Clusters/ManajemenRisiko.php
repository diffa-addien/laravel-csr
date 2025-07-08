<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class ManajemenRisiko extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationGroup = 'Data Induk';
    protected static ?string $navigationLabel = 'Manajemen Risiko';
    public static ?int $navigationSort = 9;
    protected static ?string $clusterBreadcrumb = 'Manajemen Risiko';
}
