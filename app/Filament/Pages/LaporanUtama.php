<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\PengmasRencanaProgramAnggaran;
use App\Models\TahunFiskal;

use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class LaporanUtama extends Page
{
  // Icon dari Heroicons (https://heroicons.com/)
  protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

  protected static bool $shouldRegisterNavigation = true;

  // Judul halaman yang akan muncul di menu navigasi
  protected static ?string $navigationLabel = 'Laporan';

  // Grup navigasi (opsional, untuk mengelompokkan menu)
  protected static ?string $navigationGroup = 'Laporan';

  // Judul yang akan tampil di header halaman
  protected static ?string $title = 'Pusat Laporan';


  protected static ?int $navigationSort = 10;
  // Tentukan file view yang akan digunakan
  protected static string $view = 'filament.pages.laporan-utama';

  public function printTable(): \Symfony\Component\HttpFoundation\StreamedResponse
  {
    $lastFourFiscalYears = TahunFiskal::query()
      ->orderBy('nama_tahun_fiskal', 'desc')
      ->limit(4)
      ->get()
      // Urutkan kembali agar data yang ditampilkan kronologis (misal: 2022, 2023, 2024, 2025)
      ->sortBy('nama_tahun_fiskal');

    // Jika tidak ada data tahun fiskal sama sekali, hentikan proses.
    if ($lastFourFiscalYears->isEmpty()) {
      $this->records = []; // Pastikan $records adalah array kosong
    } else {
      // 2. Ambil ID dari tahun-tahun tersebut untuk filtering query utama
      $fiscalYearIds = $lastFourFiscalYears->pluck('id');

      // 3. Query data utama: hitung total anggaran per tahun fiskal
      $budgetData = PengmasRencanaProgramAnggaran::query()
        // Join dengan tabel tahun fiskal untuk mendapatkan nama tahun
        ->join('tahun_fiskals', 'pengmas_rencana_program_anggarans.tahun_fiskal', '=', 'tahun_fiskals.id')
        // Filter hanya untuk 4 tahun fiskal yang sudah kita tentukan
        ->whereIn('pengmas_rencana_program_anggarans.tahun_fiskal', $fiscalYearIds)
        // Pilih kolom yang dibutuhkan dan hitung total anggaran
        ->select(
          'pengmas_rencana_program_anggarans.tahun_fiskal',
          'tahun_fiskals.nama_tahun_fiskal',
          DB::raw('SUM(pengajuan_anggaran) as total_anggaran')
        )
        // Kelompokkan hasil berdasarkan ID dan nama tahun
        ->groupBy('pengmas_rencana_program_anggarans.tahun_fiskal', 'tahun_fiskals.nama_tahun_fiskal')
        ->orderBy('tahun_fiskals.nama_tahun_fiskal', 'asc')
        ->get()
        // Jadikan ID tahun fiskal sebagai key array untuk akses yang mudah
        ->keyBy('tahun_fiskal');

      // 4. Assign hasil query ke properti public $records
      // Menggunakan toArray() untuk memastikan tipe data sesuai dengan deklarasi `public array $records`
      $this->records = $budgetData->toArray();
    }

    $view = View::make('filament.pages.print-table-pdf', ['records' => $this->records]);
    $pdf = Pdf::loadHTML($view->render());
    return response()->streamDownload(function () use ($pdf) {
      echo $pdf->output();
    }, 'Laporan_Pengembangan_Masyarakat.pdf');

  }

  public function exportPdf()
  {
    // 1. Ambil data asli dari sumber statis (berupa array).
    $originalRecords = $this->getStaticDummyData();

    $corruptedRecords = [];

    // 2. Loop setiap record dan "rusak" datanya.
    foreach ($originalRecords as $record) {
      // DIUBAH: Tidak lagi menggunakan clone, cukup assignment biasa untuk menyalin array.
      $corruptedRecord = $record;

      // DIUBAH: Menggunakan sintaks array ['...'] untuk mengubah nilai.
      if (rand(1, 4) == 1)
        $corruptedRecord['pengajuan'] = 'NaN';
      if (rand(1, 4) == 1)
        $corruptedRecord['kesepakatan'] = 9999999999999;
      if (rand(1, 4) == 1)
        $corruptedRecord['evaluasi'] = 'ERR_CALC';
      if (rand(1, 3) == 1)
        $corruptedRecord['awal'] = '00-00-0000';
      if (rand(1, 3) == 1)
        $corruptedRecord['akhir'] = null;
      if (rand(1, 2) == 1)
        $corruptedRecord['penerima_manfaat'] = -1;
      if (rand(1, 2) == 1) {
        try {
          $corruptedRecord['jumlah_desa'] = 1 / 0;
        } catch (\Throwable $th) {
          $corruptedRecord['jumlah_desa'] = 'DIV_ZERO';
        }
      }
      if (rand(1, 5) == 1)
        $corruptedRecord['justifikasi'] = '<lookup_failed>';

      $corruptedRecords[] = $corruptedRecord;
    }

    // 3. Render view dengan data yang sudah dirusak (tidak ada perubahan di sini).
    $view = View::make('filament.pages._error_pdf', ['records' => $corruptedRecords]);
    $pdf = Pdf::loadHTML($view->render())->setPaper('a4', 'landscape');

    // 4. Kirim PDF ke browser (tidak ada perubahan di sini).
    return response()->streamDownload(function () use ($pdf) {
      echo $pdf->output();
    }, 'Laporan_Error_Simulasi_Static.pdf');
  }

  private function getStaticDummyData(): array
  {
    // Sekarang kita membuat array asosiatif, bukan objek
    return [
      [
        'bidang' => ['nama' => 'Pendidikan'],
        'program' => ['nama' => 'Beasiswa Anak Berprestasi'],
        'pengajuan' => 120000000,
        'kesepakatan' => 110000000,
        'evaluasi' => 105000000,
        'awal' => '10/01/2025',
        'akhir' => '15/12/2025',
        'penerima_manfaat' => 50,
        'jumlah_desa' => 5,
        'justifikasi' => 'Meningkatkan kualitas pendidikan lokal.',
        'keterangan' => 'Penyaluran setiap 3 bulan.',
      ],
      [
        'bidang' => ['nama' => 'Kesehatan'],
        'program' => ['nama' => 'Posyandu Lansia Sehat'],
        'pengajuan' => 75000000,
        'kesepakatan' => 75000000,
        'evaluasi' => 74500000,
        'awal' => '01/02/2025',
        'akhir' => '30/11/2025',
        'penerima_manfaat' => 120,
        'jumlah_desa' => 8,
        'justifikasi' => 'Pemeriksaan kesehatan rutin untuk lansia.',
        'keterangan' => 'Termasuk pemberian vitamin.',
      ],
      // ...dan seterusnya untuk data lain
    ];
  }

}