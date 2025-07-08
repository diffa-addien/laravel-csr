<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'Laporan Konsolidasi' }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .header-section {
            text-align: center;
            margin-bottom: 25px;
        }
        .header-section h1 {
            font-size: 18px;
            margin: 0;
            padding: 0;
        }
        .header-section h2 {
            font-size: 16px;
            margin: 0;
            padding: 0;
            font-weight: normal;
        }
        .text-right {
            text-align: right;
        }
        tfoot {
            font-weight: bold;
        }
        tfoot td {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="header-section">
        <h1>{{ $title ?? 'Laporan Konsolidasi' }}</h1>
        <h2>PT Pantai Indah Kapuk 2, Tbk</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">No.</th>
                <th>Tahun Fiskal</th>
                <th style="width: 40%;">Total Kesepakatan Anggaran</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $grandTotal = 0;
            @endphp
            @forelse($records as $record)
                <tr>
                    <td style="text-align: center;">{{ $no++ }}</td>
                    <td>Tahun {{ $record['nama_tahun_fiskal'] ?? '-' }}</td>
                    <td class="text-right">{{ 'Rp ' . number_format($record['total_kesepakatan'], 2, ',', '.') }}</td>
                </tr>
                @php
                    $grandTotal += $record['total_kesepakatan'];
                @endphp
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Tidak ada data untuk ditampilkan.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align: center;">TOTAL KESELURUHAN</td>
                <td class="text-right">{{ 'Rp ' . number_format($grandTotal, 2, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
