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
            border-collapse: collapse;
            /* Tambahkan ini untuk membantu renderer PDF menangani page break */
            page-break-inside: auto; 
        }
        tr {
            /* Mencegah baris terpotong di tengah */
            page-break-inside: avoid; 
            page-break-after: auto;
        }
        /* Style untuk SEMUA cell (header dan data) */
        table th, table td { 
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word;
        }
        table th { 
            background-color: #f2f2f2;
            font-weight: bold; 
            text-align: center;
            vertical-align: middle;
        }
        /* Style baru untuk sel keterangan */
        .keterangan-cell {
            padding: 8px;
            /* border-top: none; Menghilangkan border atas agar menyatu dengan baris data */
            text-align: justify; /* Membuat teks rata kiri-kanan */
        }
        /* Style untuk baris data utama agar border bawahnya menyatu dengan keterangan */
        .main-data-row td {
            border-bottom: none; /* Menghilangkan border bawah dari sel data utama */
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .uppercase { text-transform: uppercase; }
        .no-border { border: none; }
        .font-bold { font-weight: bold; }
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
                    {{-- Baris untuk data utama --}}
                    <tr class="main-data-row">
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $record->nama_kegiatan ?? '-' }}</td>
                        <td>{{ $record->dariBidang->nama_bidang ?? '-' }}</td>
                        <td>{{ $record->desa->nama_desa ?? '-' }}</td>
                        <td class="text-right">{{ number_format($record->anggaran ?? 0, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $record->rencana_mulai ? \Carbon\Carbon::parse($record->rencana_mulai)->format('d/m/y') : '-' }}</td>
                        <td class="text-center">{{ $record->rencana_selesai ? \Carbon\Carbon::parse($record->rencana_selesai)->format('d/m/y') : '-' }}</td>
                        <td class="text-center">{{ $record->jumlah_penerima ?? '0' }}</td>
                    </tr>
                    {{-- Baris KHUSUS untuk Keterangan --}}
                    <tr>
                        {{-- Sel kosong untuk kolom 'No' --}}
                        <td></td> 
                        {{-- Sel keterangan yang digabung --}}
                        <td colspan="7" class="keterangan-cell">
                            <span class="font-bold">Keterangan:</span> {{ $record->keterangan ?? '-' }}
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