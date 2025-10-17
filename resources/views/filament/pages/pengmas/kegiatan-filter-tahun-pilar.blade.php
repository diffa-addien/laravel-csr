<x-filament-panels::page>
    <x-filament::card>
        <h2 class="text-xl font-bold tracking-tight mb-2">
            Filter Laporan Pelaksanaan Kegiatan
        </h2>

        <p class="text-gray-500">
            Silakan pilih Tahun Fiskal dan Pilar (Bidang) untuk mencetak laporan daftar pelaksanaan kegiatan.
        </p>

        {{-- Ini akan merender form yang kita definisikan di file PHP --}}
        <form wire:submit.prevent="printLaporan" class="mt-6">
            {{ $this->form }}

            <div class="mt-6">
                <x-filament::button
                    type="submit"
                    icon="heroicon-o-printer"
                >
                    Cetak Laporan
                </x-filament::button>
            </div>
        </form>
    </x-filament::card>
</x-filament-panels::page>
