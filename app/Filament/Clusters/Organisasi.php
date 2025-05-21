<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Organisasi extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $clusterBreadcrumb = 'Organisasi'; 

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
