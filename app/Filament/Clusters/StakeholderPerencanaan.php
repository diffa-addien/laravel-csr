<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class StakeholderPerencanaan extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-document-minus';

    protected static ?string $navigationGroup = 'Pemangku Kepentingan';
    protected static ?string $navigationLabel = "Perencanaan";
    protected static ?string $clusterBreadcrumb = 'Perencanaan';
    protected static ?int $navigationSort = 2;
}
