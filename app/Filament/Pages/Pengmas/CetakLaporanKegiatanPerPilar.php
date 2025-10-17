<?php

namespace App\Filament\Pages\Pengmas;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use App\Models\TahunFiskal;
use App\Models\Bidang; // Asumsi nama model adalah Bidang
use App\Models\PengmasPelaksanaanKegiatan;
use App\Models\PengmasWilayahKegiatan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class CetakLaporanKegiatanPerPilar extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationLabel = 'Laporan Pelaksanaan';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $slug = 'pengmas/cetak-laporan-program-pilar';
    protected static ?string $title = 'Laporan Pelaksanaan Kegiatan';
    protected static string $view = 'filament.pages.pengmas.kegiatan-filter-tahun-pilar';
    protected static ?int $navigationSort = 11;

    // Properti untuk menyimpan nilai filter
    public ?int $tahun_fiskal_id = null;
    public ?int $bidang_id = null;

    // Mendefinisikan komponen form (filter)
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        Select::make('tahun_fiskal_id')
                            ->label('Pilih Tahun Fiskal')
                            ->options(TahunFiskal::pluck('nama_tahun_fiskal', 'id'))
                            ->searchable()
                            ->preload(),
                        Select::make('bidang_id')
                            ->label('Pilih Pilar (Bidang)')
                            ->options(Bidang::pluck('nama_bidang', 'id')) // Asumsi tabel 'bidangs' memiliki kolom 'nama_bidang'
                            ->searchable()
                            ->preload(),
                    ])
            ])
            ->statePath('data'); // Menggunakan state path agar tidak bentrok
    }

    // Properti 'data' akan di-mount ke form
    public ?array $data = [];
    public function mount(): void
    {
        $this->form->fill();
    }

    // Fungsi utama untuk mencetak laporan
    public function printLaporan()
    {
        $this->validate([
            'data.tahun_fiskal_id' => 'required',
            'data.bidang_id' => 'required',
        ], [
            'data.tahun_fiskal_id.required' => 'Tahun Fiskal wajib dipilih.',
            'data.bidang_id.required' => 'Pilar (Bidang) wajib dipilih.',
        ]);

        $filters = $this->form->getState();
        $tahunFiskalId = $filters['tahun_fiskal_id'];
        $bidangId = $filters['bidang_id'];

        // PENDEKATAN BARU: Menggunakan Eloquent dan Collection::map
        $rencanaKegiatans = PengmasWilayahKegiatan::query()
            // Terapkan filter pada tabel rencana
            ->where('bidang_id', $bidangId)
            ->whereHas('dariProgram', function (Builder $programQuery) use ($tahunFiskalId) {
                $programQuery->where('tahun_fiskal', $tahunFiskalId);
            })
            // Eager load relasi untuk performa yang lebih baik
            ->with(['pelaksanaan', 'desa.kecamatan'])
            ->get();

        // Transformasi data menjadi struktur flat yang dibutuhkan oleh PDF view
        $kegiatans = $rencanaKegiatans->map(function ($rencana) {
            // Ambil data pelaksanaan yang paling baru (jika ada lebih dari satu)
            $pelaksanaan = $rencana->pelaksanaan->sortByDesc('tanggal_pelaksanaan')->first();

            $images = collect(); // Default ke koleksi kosong
            if ($pelaksanaan) {
                // Ambil media PERTAMA dari koleksi 'images'
                $firstMedia = $pelaksanaan->getFirstMedia('images');
                // Jika gambar pertama ditemukan, ambil path-nya
                if ($firstMedia) {
                    $images = collect([$firstMedia->getPath()]);
                }
            }

            // Bangun objek standar yang berisi data gabungan
            return (object) [
                'nama_kegiatan' => $rencana->nama_kegiatan,
                // PERBAIKAN: Cara yang lebih aman untuk mengakses nama kecamatan
                'lokasi' => (optional($rencana->desa)->nama_desa ?? 'N/A') . ', ' . (optional(optional($rencana->desa)->kecamatan)->nama_kec ?? 'N/A'),
                'tanggal_final' => $pelaksanaan ? $pelaksanaan->tanggal_pelaksanaan : $rencana->rencana_mulai,
                'penerima_final' => $pelaksanaan ? $pelaksanaan->jumlah_penerima : $rencana->jumlah_penerima,
                'anggaran_final' => $pelaksanaan ? $pelaksanaan->anggaran_pelaksanaan : $rencana->anggaran,
                'is_terlaksana' => !is_null($pelaksanaan),
                'keterangan' => $rencana->keterangan,
                'images' => $images,
            ];
        })->sortByDesc('tanggal_final'); // Urutkan koleksi hasil transformasi

        $tahunFiskalNama = TahunFiskal::find($tahunFiskalId)->nama_tahun_fiskal;
        $bidangNama = Bidang::find($bidangId)->nama_bidang;
        
        $pdf = Pdf::loadView('filament.pages.pengmas.pdf-laporan-kegiatan-pilar', [
            'kegiatans' => $kegiatans,
            'tahunFiskal' => $tahunFiskalNama,
            'bidang' => $bidangNama,
        ]);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            "Laporan_Kegiatan_Pengmas_{$tahunFiskalNama}_{$bidangNama}.pdf"
        );
    }
}
