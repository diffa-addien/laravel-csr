<!DOCTYPE html>
<html>

<head>
    <title>Laporan Anggaran Pengembangan Masyarakat</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }
        h1, h2, h3 {
            font-size: 20px;
            padding: 0;
            margin: 0;
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Laporan</h1>
    <h2>Pengembangan Masyarakat</h2>
    <h2 style="margin-bottom: 20px!important">PT Pantai Indah Kapuk 2, Tbk</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Tahun</th>
                <th>Biaya</th>
                {{-- <th>Dibuat Pada</th> --}}
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $total = 0;
            @endphp
            @foreach($records as $record)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>Pengembangan Masyarakat Tahun {{ $record['nama_tahun_fiskal'] ?? '-' }}</td>
                    <td>{{ 'Rp ' . number_format($record['total_anggaran'], 0, ',', '.') }}</td> {{-- Pastikan fungsi ini
                    tersedia --}}
                    {{-- <td>{{ \Carbon\Carbon::parse($record['created_at'])->format('Y-m-d H:i:s') }}</td> --}}
                </tr>
                @php
                    $total += $record['total_anggaran'];
                @endphp
            @endforeach
            
        </tbody>
        <tfoot>
            <td colspan="2">Total</td>
            <td>{{ 'Rp ' . number_format($total, 0, ',', '.') }}</td>
        </tfoot>
    </table>
</body>

</html>