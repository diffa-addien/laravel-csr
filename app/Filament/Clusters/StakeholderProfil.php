<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class StakeholderProfil extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Pemangku Kepentingan';
    protected static ?string $navigationLabel = "Profil";
    protected static ?string $clusterBreadcrumb = 'Stakeholder Profil';
    protected static ?int $navigationSort = 1;
}
