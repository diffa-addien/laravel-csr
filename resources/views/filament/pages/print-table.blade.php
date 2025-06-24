<x-filament-panels::page>
    <h1 class="text-2xl">Data Anggaran Pengembagan Masyarakat</h1>
    <x-filament::button wire:click="printTable">
        Cetak Laporan (PDF)
    </x-filament::button>
    {{-- <table>
        <thead>
            <tr class="text-left">
                <th>Regional</th>
                <th>Program</th>
                <th>Kegiatan</th>
                <th>Pengajuan Anggaran</th>
                <th>Kesepakatan Anggaran</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $record)
                <tr>
                    <td>{{ $record['regional']['nama_regional'] ?? '-' }}</td>
                    <td>{{ $record['program']['nama'] ?? '-' }}</td>
                    <td>{{ $record['kegiatan'] }}</td>
                    <td>{{ 'Rp. ' .number_format($record['anggaran_pengajuan'], 0, ',', '.') }}</td>
                    <td>{{ 'Rp. ' .number_format($record['anggaran_kesepakatan'], 0, ',', '.') }}</td> 
                    <td>{{ $record['keterangan'] }}</td></tr>
            @endforeach
        </tbody>
    </table> --}}
</x-filament-panels::page>