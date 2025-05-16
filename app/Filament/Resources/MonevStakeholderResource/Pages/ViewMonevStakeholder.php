<?php

namespace App\Filament\Resources\MonevStakeholderResource\Pages;

use App\Filament\Resources\MonevStakeholderResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;
use App\Models\StkholderRincianAnggaran;

class ViewMonevStakeholder extends ViewRecord
{
  protected static string $resource = MonevStakeholderResource::class;

  public function getTitle(): string
  {
    return 'Detail Kegiatan dan Anggaran';
  }

  public function infolist(Infolist $infolist): Infolist
  {
    // Ambil data rincian anggaran
    $rincianAnggarans = StkholderRincianAnggaran::query()
      ->where('kegiatan_id', $this->record->id)
      ->with('pelaksana')
      ->get();

    return $infolist
      ->schema([
        Components\Section::make('Informasi Kegiatan')
          ->schema([
            Components\Grid::make(2)
              ->schema([
                Components\TextEntry::make('kegiatan')
                  ->label('Kegiatan'),
                Components\TextEntry::make('regional.nama_regional')
                  ->label('Regional')
                  ->default('-'),
                Components\TextEntry::make('anggaran_kesepakatan')
                  ->label('Kesepakatan Anggaran')
                  ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                Components\TextEntry::make('monevStakeholder.nilai_evaluasi')
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
                    Components\TextEntry::make('pelaksana.nama')
                      ->label('Pelaksana')
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
                    Components\TextEntry::make('keterangan')
                      ->label('Keterangan')
                      ->default('-'),
                  ]),
              ])
              ->visible()
              ->default($rincianAnggarans->toArray()),
          ]),
      ]);
  }
}
