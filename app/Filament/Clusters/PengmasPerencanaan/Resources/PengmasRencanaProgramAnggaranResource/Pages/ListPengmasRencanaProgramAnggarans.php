<?php

namespace App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasRencanaProgramAnggaranResource\Pages;

use App\Filament\Clusters\PengmasPerencanaan\Resources\PengmasRencanaProgramAnggaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use App\Models\TahunFiskal; // <-- 1. Import model TahunFiskal
use Illuminate\Database\Eloquent\Builder; // <-- Import Builder


class ListPengmasRencanaProgramAnggarans extends ListRecords
{
    protected static string $resource = PengmasRencanaProgramAnggaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // Letakkan ini di dalam kelas ListPengmasRencanaProgramAnggarans

protected function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
{
    // Panggil query asli dari parent class
    $query = parent::getEloquentQuery();

    // PAKSA QUERY INI UNTUK TIDAK MENGEMBALIKAN HASIL APAPUN
    // Ini adalah cara standar untuk membuat query 'where false'.
    return $query->whereRaw('1 = 0');
}

}
