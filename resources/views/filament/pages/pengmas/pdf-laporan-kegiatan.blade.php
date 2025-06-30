<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Kegiatan</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
        }
        .container { width: 100%; margin: 0 auto; }
        h1, h2 { text-align: center; margin-bottom: 5px; }
        h1 { font-size: 16px; }
        h2 { font-size: 14px; font-weight: normal; margin-bottom: 20px;}
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; table-layout: fixed; /* <-- PERUBAHAN 1: Memaksa tabel mengikuti lebar yang ada */}
        table th, table td { border: 1px solid #999; padding: 5px; text-align: left; vertical-align: top; word-wrap: break-word; /* <-- PERUBAHAN 2: Memaksa teks panjang untuk turun baris */}
        table th { background-color: #dbeafe; font-weight: bold; text-align: center; vertical-align: middle; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .uppercase { text-transform: uppercase; }
    </style>
</head>

<body>
    <div class="container">
        @if($records->isNotEmpty())
            @php
                $program = $records->first()->dariProgram;
                $tahunFiskal = $program->dariTahunFiskal;
            @endphp
            <h1 class="uppercase">
                LAPORAN KEGIATAN PENGEMBANGAN MASYARAKAT<br />
                PT PANTAI INDAH KAPUK 2, TBK<br />
            </h1>
            <h2>
                PROGRAM: {{ $program->nama_program }} <br>
                TAHUN FISKAL {{ $tahunFiskal->nama_tahun_fiskal }}
            </h2>
        @endif

        <table>
            <thead>
                {{-- PERUBAHAN STRUKTUR HEADER DIMULAI DI SINI --}}
                <tr>
                    <th rowspan="2" style="width: 4%;">No</th>
                    <th rowspan="2" style="width: 18%;">Nama Kegiatan</th>
                    <th rowspan="2" style="width: 9%;">Pilar</th>
                    <th rowspan="2" style="width: 13%;">Desa</th>
                    <th rowspan="2" style="width: 10%;">Anggaran (Rp)</th>
                    <th colspan="2" >Rencana Waktu</th>
                    <th rowspan="2" style="width: 7%;">Jumlah Penerima</th>
                    <th rowspan="2" style="width: 10%;">Keterangan</th>
                </tr>
                <tr>
                    <th style="width: 7%;">Mulai</th>
                    <th style="width: 7%;">Selesai</th>
                </tr>
                 {{-- AKHIR PERUBAHAN STRUKTUR HEADER --}}
            </thead>
            <tbody>
                @forelse ($records as $index => $record)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $record->nama_kegiatan ?? '-' }}</td>
                        <td>{{ $record->dariBidang->nama_bidang ?? '-' }}</td>
                        <td>{{ $record->desa->nama_desa ?? '-' }}</td>
                        <td class="text-right">{{ number_format($record->anggaran ?? 0, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $record->rencana_mulai ? \Carbon\Carbon::parse($record->rencana_mulai)->format('d/m/y') : '-' }}</td>
                        <td class="text-center">{{ $record->rencana_selesai ? \Carbon\Carbon::parse($record->rencana_selesai)->format('d/m/y') : '-' }}</td>
                        <td class="text-center">{{ $record->jumlah_penerima ?? '0' }}</td>
                        <!-- <td class="text-center">{{ $record->pelaksanaan_count }}</td> -->
                        <td>{{ $record->keterangan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">Tidak ada data kegiatan untuk program yang dipilih.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
