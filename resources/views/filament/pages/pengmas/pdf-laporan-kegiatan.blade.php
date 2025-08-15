<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Kegiatan</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }
        .container { 
            width: 100%; 
            margin: 0 auto; 
        }
        h1, h2 { 
            text-align: center; 
            margin-bottom: 5px; 
            font-weight: normal;
        }
        h1 { 
            font-size: 18px; 
            font-weight: bold;
        }
        h2 { 
            font-size: 15px;
            margin-bottom: 20px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; /* Kunci untuk border grid yang rapi */
            margin-bottom: 20px;
        }
        /* Style untuk SEMUA cell (header dan data) */
        table th, table td { 
            border: 1px solid #ccc; /* Border abu-abu muda yang konsisten di semua sisi */
            padding: 8px;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word; /* Memastikan teks panjang tidak merusak layout */
        }
        /* Style khusus untuk header tabel */
        table th { 
            background-color: #f2f2f2;
            font-weight: bold; 
            text-align: center;
            vertical-align: middle;
        }
        /* Style untuk baris keterangan agar tetap menonjol */
        .keterangan-cell {
            background-color: #f9f9f9; /* Latar sedikit berbeda untuk memisahkan secara visual */
        }
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
                PT PANTAI INDAH KAPUK 2, TBK
            </h1>
            <h2>
                PROGRAM: {{ $program->nama_program }} <br>
                TAHUN FISKAL {{ $tahunFiskal->nama_tahun_fiskal }}
            </h2>
        @endif

        <table>
            <thead>
                {{-- Menggunakan struktur header asli yang lebih robust dengan rowspan & colspan --}}
                <tr>
                    <th rowspan="2" style="width: 4%;">No</th>
                    <th rowspan="2" style="width: 20%;">Nama Kegiatan</th>
                    <th rowspan="2" style="width: 10%;">Pilar</th>
                    <th rowspan="2" style="width: 15%;">Desa</th>
                    <th rowspan="2" style="width: 12%;">Anggaran (Rp)</th>
                    <th colspan="2">Rencana Waktu</th>
                    <th rowspan="2" style="width: 8%;">Jumlah Penerima</th>
                </tr>
                <tr>
                    <th style="width: 7%;">Mulai</th>
                    <th style="width: 7%;">Selesai</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $index => $record)
                    <tr>
                        <td class="text-center" rowspan="2">{{ $index + 1 }}</td>
                        <td>{{ $record->nama_kegiatan ?? '-' }}</td>
                        <td>{{ $record->dariBidang->nama_bidang ?? '-' }}</td>
                        <td>{{ $record->desa->nama_desa ?? '-' }}</td>
                        <td class="text-right">{{ number_format($record->anggaran ?? 0, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $record->rencana_mulai ? \Carbon\Carbon::parse($record->rencana_mulai)->format('d/m/y') : '-' }}</td>
                        <td class="text-center">{{ $record->rencana_selesai ? \Carbon\Carbon::parse($record->rencana_selesai)->format('d/m/y') : '-' }}</td>
                        <td class="text-center">{{ $record->jumlah_penerima ?? '0' }}</td>
                    </tr>
                    <tr>
                        <td colspan="7" class="">
                            <b>Keterangan:</b> {{ $record->keterangan ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center" style="padding: 20px;">Tidak ada data kegiatan untuk program yang dipilih.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>