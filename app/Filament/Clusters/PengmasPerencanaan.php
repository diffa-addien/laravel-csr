<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class PengmasPerencanaan extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-document-minus';
    protected static ?string $navigationGroup = 'Pengembangan Masyarakat';
    protected static ?string $navigationLabel = "Perencanaan";
    protected static ?string $clusterBreadcrumb = 'Perencanaan';
    protected static ?int $navigationSort = 1;
}