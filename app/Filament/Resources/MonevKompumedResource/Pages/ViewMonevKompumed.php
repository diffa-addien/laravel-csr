<?php

namespace App\Filament\Resources\MonevKompumedResource\Pages;

use App\Filament\Resources\MonevKompumedResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;
use App\Models\KompumedKegiatanAnggaran;

class ViewMonevKompumed extends ViewRecord
{
  protected static string $resource = MonevKompumedResource::class;

  public function getTitle(): string
  {
    return 'Detail Kegiatan dan Anggaran';
  }

  public function infolist(Infolist $infolist): Infolist
  {
    // Ambil data rincian anggaran
    $rincianAnggarans = KompumedKegiatanAnggaran::query()
      ->where('kegiatan_id', $this->record->id)
      ->get();

    return $infolist
      ->schema([
        Components\Section::make('Informasi Kegiatan')
          ->schema([
            Components\Grid::make(2)
              ->schema([
                Components\TextEntry::make('nama')
                  ->label('Kegiatan'),
                Components\TextEntry::make('regional.nama_regional')
                  ->label('Regional')
                  ->default('-'),
                Components\TextEntry::make('total_anggaran')
                  ->label('Anggaran')
                  ->label('Total Anggaran')
                  ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                Components\TextEntry::make('monevKompumed.nilai_evaluasi')
                  ->label('Evaluasi Anggaran')
                  ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.')),
              ]),
          ]), 
        Components\Section::make('Rincian Anggaran')
          ->schema([
            Components\RepeatableEntry::make('rincianAnggarans')
              ->label('Detail Rincian Anggaran')
              ->schema([
                Components\Grid::make(3)
                  ->schema([
                    Components\TextEntry::make('deskripsi')
                      ->label('Deskripsi')
                      ->columnSpan(2)
                      ->default('-'),
                    Components\TextEntry::make('frekuensi')
                      ->label('Frekuensi')
                      ->formatStateUsing(fn($state, $record) => "{$record->frekuensi} " . ucfirst($record->frekuensi_unit)),
                    Components\TextEntry::make('biaya')
                      ->label('Biaya')
                      ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                    Components\TextEntry::make('kuantitas')
                      ->label('Kuantitas')
                      ->formatStateUsing(fn($state, $record) => "{$record->kuantitas} " . ucfirst($record->kuantitas_unit)),
                    Components\TextEntry::make('jumlah')
                      ->label('Jumlah')
                      ->getStateUsing(fn($record) => $record->biaya * $record->kuantitas)
                      ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                  ]),
              ])
              ->visible()
              ->default($rincianAnggarans->toArray()),
          ]),
      ]);
  }
}
