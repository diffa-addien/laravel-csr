<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class StakeholderPelaksanaan extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $navigationGroup = 'Pemangku Kepentingan';
    protected static ?string $navigationLabel = "Pelaksanaan";
    protected static ?string $clusterBreadcrumb = 'Pelaksanaan';
    protected static ?int $navigationSort = 3;
}
