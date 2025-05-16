<x-filament-panels::page>
    <h1>Data Stakeholder Perencanaan Program Anggaran</h1>
    <table>
        <thead>
            <tr>
                <th>Regional</th>
                <th>Program</th>
                <th>Kegiatan</th>
                <th>Anggaran Pengajuan</th>
                <th>Anggaran Kesepakatan</th>
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
                    <td>{{ format_uang($record['anggaran_pengajuan']) }}</td> {{-- Pastikan fungsi ini tersedia --}}
                    <td>{{ format_uang($record['anggaran_kesepakatan']) }}</td> {{-- Pastikan fungsi ini tersedia --}}
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