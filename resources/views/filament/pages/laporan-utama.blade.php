<x-filament-panels::page>
    {{-- Ini adalah container utama dari halaman --}}

    {{-- Kita buat grid di sini. 
         - grid-cols-1: 1 kolom di layar kecil
         - md:grid-cols-2: 2 kolom di layar medium dan lebih besar
         - gap-6: Jarak antar elemen grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- KARTU 1: Laporan Anggaran Fiskal --}}
        <div class="p-6 bg-white rounded-xl shadow-lg space-y-4 dark:bg-gray-800">
            <div>
                {{-- Judul Kartu --}}
                <h2 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Laporan Anggaran Fiskal
                </h2>
                <p class="mt-1 text-gray-500 dark:text-gray-400">
                    Lihat rekapitulasi dan rincian total anggaran per tahun fiskal.
                </p>
            </div>

            {{-- Tombol/Link Aksi --}}
            <div class="mt-4">
                <a href="#" wire:click="printTable" class="inline-flex items-center gap-1 px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Pilih Laporan
                </a>
            </div>
        </div>


        {{-- KARTU 2: Laporan Anggaran Program --}}
        <div class="p-6 bg-white rounded-xl shadow-lg space-y-4 dark:bg-gray-800">
            <div>
                {{-- Judul Kartu --}}
                <h2 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Laporan Anggaran Program
                </h2>
                <p class="mt-1 text-gray-500 dark:text-gray-400">
                    Analisis penggunaan anggaran berdasarkan masing-masing program.
                </p>
            </div>
            
            {{-- Tombol/Link Aksi --}}
            <div class="mt-4">
                <a href="#" wire:click="exportPdf"  class="inline-flex items-center gap-1 px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Pilih Laporan
                </a>
            </div>
        </div>

        {{-- Anda bisa menambahkan kartu lain di sini --}}

    </div>

</x-filament-panels::page>