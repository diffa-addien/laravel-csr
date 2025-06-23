{{-- resources/views/filament/widgets/pengmas-kegiatan-wilayah.blade.php --}}
<x-filament::widget>
    <div class="col-span-full">
        <x-filament::card>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="flex flex-col space-y-1">
                    <div class="text-sm font-medium text-gray-500">Jumlah Data</div>
                    <div class="text-lg font-semibold text-gray-800">{{ $totalDataFormatted }}</div>
                    <div class="text-xs text-gray-400">Total data wilayah kegiatan</div>
                </div>
                <div class="flex flex-col space-y-1">
                    <div class="text-sm font-medium text-gray-500">Jumlah Penerima Manfaat</div>
                    <div class="text-lg font-semibold text-gray-800">{{ $totalPenerimaManfaatFormatted }}</div>
                    <div class="text-xs text-gray-400">Total penerima manfaat di semua wilayah</div>
                </div>
                <div class="flex flex-col space-y-1">
                    <div class="text-sm font-medium text-gray-500">Jumlah Anggaran</div>
                    <div class="text-lg font-semibold text-gray-800">{{ $totalAnggaran }}</div>
                    <div class="text-xs text-gray-400">Total anggaran per tahun fiskal</div>
                </div>
            </div>
        </x-filament::card>
    </div>
</x-filament::widget>