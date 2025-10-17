<!DOCTYPE html>
<html>

<head>
    <title>Laporan Kegiatan Pengembangan Masyarakat</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .header-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .header-section h1 {
            font-size: 16px;
            margin: 0;
        }

        .header-section h2 {
            font-size: 14px;
            margin: 5px 0 0 0;
            font-weight: normal;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        tfoot {
            font-weight: bold;
        }

        tfoot td {
            background-color: #f2f2f2;
        }

        .status {
            font-size: 9px;
            font-weight: bold;
            padding: 2px 4px;
            border-radius: 3px;
            color: #fff;
        }

        .status-terlaksana {
            background-color: #28a745;
        }

        /* Hijau */
        .status-direncanakan {
            background-color: #ffc107;
            color: #333;
        }

        .dokumentasi-img {
            max-width: 200px;
            max-height: 150px;
            margin: 2px;
            border: 1px solid #ccc;
        }

        /* Kuning */
    </style>
</head>

<body>
    <div class="header-section">
        <h1>LAPORAN KEGIATAN PENGEMBANGAN MASYARAKAT</h1>
        <h2>PT Pantai Indah Kapuk 2, Tbk</h2>
        <h2>Tahun Fiskal: {{ $tahunFiskal }} | Pilar: {{ $bidang }}</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 4%;">No.</th>
                <th style="width: 10%;">Tanggal</th>
                <th>Nama Kegiatan</th>
                <th style="width: 20%;">Lokasi (Desa, Kecamatan)</th>
                <th style="width: 10%;">Jml Penerima</th>
                <th style="width: 15%;">Anggaran</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $totalPenerima = 0;
                $totalAnggaran = 0;
            @endphp
            @forelse($kegiatans as $kegiatan)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($kegiatan->tanggal_final)->format('d M Y') }}</td>
                            <td>{{ $kegiatan->nama_kegiatan }}</td>
                            <td>{{ $kegiatan->lokasi }}</td>
                            <td class="text-center">{{ number_format($kegiatan->penerima_final, 0, ',', '.') }}</td>
                            <td class="text-right">{{ 'Rp ' . number_format($kegiatan->anggaran_final, 2, ',', '.') }}</td>
                            <td class="text-center">
                                @if($kegiatan->is_terlaksana)
                                    <span class="status status-terlaksana">Terlaksana</span>
                                @else
                                    <span class="status status-direncanakan">Direncanakan</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            {{-- Sel kosong untuk kolom 'No' --}}
                            <td></td>
                            {{-- Sel keterangan yang digabung --}}
                            <td colspan="2" class="text-center">{{-- INI CARA PEMANGGILANNYA --}}
                                @foreach($kegiatan->images as $imagePath)
                                    <img src="{{ $imagePath }}" alt="Dokumentasi Error" class="dokumentasi-img">
                                @endforeach
                            </td>
                            <td colspan="4" class="keterangan-cell">
                                <span class="font-bold">Keterangan:</span> {{ $kegiatan->keterangan ?? '-' }}
                            </td>
                            
                        </tr>
                @php
                    $totalPenerima += $kegiatan->penerima_final;
                    $totalAnggaran += $kegiatan->anggaran_final;
                @endphp
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data kegiatan untuk filter yang dipilih.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-center">TOTAL</td>
                <td class="text-center">{{ number_format($totalPenerima, 0, ',', '.') }}</td>
                <td class="text-right">{{ 'Rp ' . number_format($totalAnggaran, 2, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</body>

</html>