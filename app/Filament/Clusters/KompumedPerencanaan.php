<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class KompumedPerencanaan extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-document-minus';

    protected static ?string $navigationGroup = 'Komunikasi, Publikasi, dan Hubungan Media';
    protected static ?string $navigationLabel = "Perencanaan";
    protected static ?string $clusterBreadcrumb = 'Perencanaan';
    protected static ?int $navigationSort = 1;
}
