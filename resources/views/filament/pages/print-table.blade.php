<x-filament-panels::page>
    <h1>Data Stakeholder Perencanaan Program Anggaran</h1>
    <table>
        <thead>
            <tr>
                <th>Regional</th>
                <th>Program</th>
                <th>Kegiatan</th>
                <th>Pengajuan Anggaran</th>
                <th>Kesepakatan Anggaran</th>
                <th>Keterangan</th>
                {{-- <th>Dibuat Pada</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $record)
                <tr>
                    <td>{{ $record['regional']['nama_regional'] ?? '-' }}</td>
                    <td>{{ $record['program']['nama'] ?? '-' }}</td>
                    <td>{{ $record['kegiatan'] }}</td>
                    <td>{{ 'Rp. ' .number_format($record['anggaran_pengajuan'], 0, ',', '.') }}</td> {{-- Pastikan fungsi ini tersedia --}}
                    <td>{{ 'Rp. ' .number_format($record['anggaran_kesepakatan'], 0, ',', '.') }}</td> {{-- Pastikan fungsi ini tersedia --}}
                    <td>{{ $record['keterangan'] }}</td>
                    {{-- <td>{{ \Carbon\Carbon::parse($record['created_at'])->format('Y-m-d H:i:s') }}</td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>

    <x-filament::button wire:click="printTable">
        Cetak PDF
    </x-filament::button>
</x-filament-panels::page>