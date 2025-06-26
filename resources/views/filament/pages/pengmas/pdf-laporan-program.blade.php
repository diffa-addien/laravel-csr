<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Program</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            /* Ukuran font sedikit dikecilkan agar muat */
            color: #333;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
            font-size: 18px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: normal;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            border: 1px solid #999;
            /* Border sedikit ditebalkan */
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }

        table th {
            background-color: #dbeafe;
            /* Warna biru muda seperti di contoh */
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="uppercase">
            LAPORAN PROGRAM PENGEMBANGAN MASYARAKAT<br />
            PT PANTAI INDAH KAPUK 2, TBK<br />
            @if($records->isNotEmpty())
                TAHUN FISKAL {{ $records->first()->dariTahunFiskal->nama_tahun_fiskal }}
            @endif
        </h1>

        {{-- Menampilkan nama tahun fiskal yang dipilih di sub-judul --}}


        <table>
            <thead>
                {{-- STRUKTUR HEADER TABEL SESUAI GAMBAR --}}
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Program</th>
                    <th colspan="3">Anggaran (Rp)</th>
                    <th colspan="3">Rencana Pelaksanaan</th>
                    <th rowspan="2">Justifikasi</th>
                    <th rowspan="2">Keterangan</th>
                </tr>
                <tr>
                    <th>Pengajuan</th>
                    <th>Kesepakatan</th>
                    <th>Evaluasi</th>
                    <th>Awal</th>
                    <th>Akhir</th>
                    <th>Jumlah Kegiatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $index => $record)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        {{-- Mengakses data menggunakan object '->' --}}
                        {{-- Anda mungkin perlu menambahkan relasi 'bidang' di model Anda jika belum ada --}}
                        <td>{{ $record->nama_program ?? '' }}</td>

                        {{-- Anggaran --}}
                        <td class="text-right">{{ number_format($record->pengajuan_anggaran ?? 0, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($record->kesepakatan_anggaran ?? 0, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($record->evaluasi ?? 0, 0, ',', '.') }}</td>

                        {{-- Rencana Pelaksanaan --}}
                        <td class="text-center">
                            {{ $record->rencana_mulai ? \Carbon\Carbon::parse($record->rencana_mulai)->format('d/m/Y') : '' }}
                        </td>
                        <td class="text-center">
                            {{ $record->rencana_selesai ? \Carbon\Carbon::parse($record->rencana_selesai)->format('d/m/Y') : '' }}
                        </td>
                        {{-- Sesuaikan nama kolom jika berbeda, misal 'jumlah_penerima' --}}
                        <td class="text-center">{{ $record->rincian_anggarans_count }}</td>

                        <td>{{ $record->justifikasi ?? '' }}</td>
                        <td>{{ $record->keterangan ?? '' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center">Tidak ada data untuk tahun fiskal yang dipilih.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>