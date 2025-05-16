<?php

namespace App\Filament\Pages;

use App\Models\StkholderPerencanaanProgramAnggaran;
use Filament\Pages\Page;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;

class PrintTable extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-printer';
    
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Laporan Kegiatan Stakeholder';
    protected static string $view = 'filament.pages.print-table';

    public array $records = [];

    public function mount(): void
    {
        $this->records = StkholderPerencanaanProgramAnggaran::with(['regional', 'program'])->get()->toArray();
    }

    public function printTable(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $view = View::make('filament.pages.print-table-pdf', ['records' => $this->records]);
        $pdf = Pdf::loadHTML($view->render());
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'tabel_stakeholder.pdf');
    }
}