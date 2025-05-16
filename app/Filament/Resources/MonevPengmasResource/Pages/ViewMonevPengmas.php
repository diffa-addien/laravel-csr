<?php

namespace App\Filament\Resources\MonevPengmasResource\Pages;

use App\Filament\Resources\MonevPengmasResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;
use App\Models\PengmasWilayahKegiatan;

class ViewMonevPengmas extends ViewRecord
{
  protected static string $resource = MonevPengmasResource::class;

  public function getTitle(): string
  {
    return 'Detail Kegiatan dan Anggaran';
  }

  public function infolist(Infolist $infolist): Infolist
  {
    // Ambil data rincian anggaran
    $rincianAnggarans = PengmasWilayahKegiatan::query()
      ->where('program_id', $this->record->id)
      ->get();

    return $infolist
      ->schema([
        Components\Section::make('Informasi Kegiatan')
          ->schema([
            Components\Grid::make(2)
              ->schema([
                Components\TextEntry::make('nama_program')
                  ->label('nama program'),
                Components\TextEntry::make('regional.nama_regional')
                  ->label('Regional')
                  ->default('-'),
                Components\TextEntry::make('kesepakatan_anggaran')
                  ->label('Kesepakatan Anggaran')
                  ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                Components\TextEntry::make('monevPengmas.nilai_evaluasi')
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
                    Components\TextEntry::make('desa.nama_desa')
                      ->label('Desa')
                      ->default('-'),
                    Components\TextEntry::make('desa.kecamatan.nama_kec')
                      ->label('Kecamatan')
                      ->default('-'),
                    Components\TextEntry::make('desa.kecamatan.kabupaten.nama_kab')
                      ->label('Kabupaten / Kota')
                      ->default('-'),
                    Components\TextEntry::make('jumlah_penerima')
                      ->label('Penerima Manfaat'),
                    Components\TextEntry::make('alamat')
                      ->label('Alamat')
                      ->columnSpan(2)
                      ->default('-'),
                    Components\TextEntry::make('keterangan')
                      ->label('Keterangan')
                      ->default('Tanpa Keterangan')
                      ->columnSpanFull(2),
                    
                  ]),
              ])
              ->visible()
              ->default($rincianAnggarans->toArray()),
          ]),
      ]);
  }
}
