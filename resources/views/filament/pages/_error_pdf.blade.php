<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Pengembangan Masyarakat</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #333; }
        .container { width: 100%; margin: 0 auto; }
        h1 { text-align: center; margin-bottom: 20px; font-size: 16px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th, table td { border: 1px solid #ccc; padding: 6px; text-align: left; vertical-align: middle; }
        table th { background-color: #e2e8f0; font-weight: bold; text-align: center; }
        tbody tr:nth-child(even) { background-color: #f9f9f9; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .error-cell { color: #dc2626; font-weight: bold; background-color: #fee2e2 !important; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Laporan Pengembangan Masyarakat</h1>
        <table>
            <thead>
                <tr>
                    <th rowspan="2" class="text-center">No</th>
                    <th rowspan="2">Bidang</th>
                    <th rowspan="2">Program</th>
                    <th colspan="3" class="text-center">Anggaran (Rp)</th>
                    <th colspan="2" class="text-center">Rencana Pelaksanaan</th>
                    <th rowspan="2">Penerima Manfaat</th>
                    <th rowspan="2">Jumlah Desa</th>
                    <th rowspan="2">Justifikasi</th>
                    <th rowspan="2">Keterangan</th>
                </tr>
                <tr>
                    <th>Pengajuan</th>
                    <th>Kesepakatan</th>
                    <th>Evaluasi</th>
                    <th>Awal</th>
                    <th>Akhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $index => $record)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $record['bidang']['nama'] ?? 'N/A' }}</td>
                        <td>{{ $record['program']['nama'] ?? 'N/A' }}</td>
                        
                        <td class="{{ !is_numeric($record['pengajuan']) ? 'error-cell' : '' }} text-right">
                            {{ is_numeric($record['pengajuan']) ? number_format($record['pengajuan'], 0, ',', '.') : $record['pengajuan'] }}
                        </td>
                        <td class="{{ !is_numeric($record['kesepakatan']) ? 'error-cell' : '' }} text-right">
                            {{ is_numeric($record['kesepakatan']) ? number_format($record['kesepakatan'], 0, ',', '.') : $record['kesepakatan'] }}
                        </td>
                        <td class="{{ !is_numeric($record['evaluasi']) ? 'error-cell' : '' }} text-right">
                            {{ is_numeric($record['evaluasi']) ? number_format($record['evaluasi'], 0, ',', '.') : $record['evaluasi'] }}
                        </td>
                        
                        <td class="{{ $record['awal'] == '00-00-0000' ? 'error-cell' : '' }}">{{ $record['awal'] ?? 'NULL' }}</td>
                        <td class="{{ is_null($record['akhir']) ? 'error-cell' : '' }}">{{ $record['akhir'] ?? 'NULL' }}</td>

                        <td class="{{ !is_numeric($record['penerima_manfaat']) || $record['penerima_manfaat'] < 0 ? 'error-cell' : '' }} text-center">{{ $record['penerima_manfaat'] }}</td>
                        
                        {{-- BARIS YANG DIPERBAIKI --}}
                        <td class="{{ !is_numeric($record['jumlah_desa']) ? 'error-cell' : '' }} text-center">
                            {{ (is_numeric($record['jumlah_desa']) && is_infinite((float)$record['jumlah_desa'])) ? 'Infinity' : $record['jumlah_desa'] }}
                        </td>
                        
                        <td>{{ $record['justifikasi'] ?? '' }}</td>
                        <td>{{ $record['keterangan'] ?? '' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center">Tidak ada data untuk ditampilkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>