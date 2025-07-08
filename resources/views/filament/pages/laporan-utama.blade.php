<x-filament-panels::page>
    {{-- Ini adalah container utama dari halaman --}}

    {{-- Kita buat grid di sini --}}
    <div class="my-0 pt-2 text-xl font-bold border-t border-gray-600">Modul Pemangku Kepentingan</div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="p-6 bg-white rounded-xl shadow-lg space-y-4 dark:bg-gray-800">
            <div>
                {{-- Judul Kartu --}}
                <h2 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Laporan Anggaran 5 Tahun Fiskal
                </h2>
                <p class="mt-1 text-gray-500 dark:text-gray-400">
                    Lihat total anggaran yang dipakai untuk program (Pemangku Kepentingan) dari 5 tahun fiskal
                    terakhir.
                </p>
            </div>

            {{-- Tombol/Link Aksi --}}
            <div class="mt-4">
                <a href="#" wire:click="printTable"
                    class="inline-flex items-center gap-1 px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <x-heroicon-o-printer class="w-4 h-4" /> Cetak PDF
                </a>
            </div>
        </div>
    </div>

    {{-- Kita buat grid di sini --}}
    <div class="my-0 pt-2 text-xl font-bold border-t border-gray-600">Modul Komunikasi, Publikasi, dan Media</div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-6 bg-white rounded-xl shadow-lg space-y-4 dark:bg-gray-800">
            <div>
                {{-- Judul Kartu --}}
                <h2 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Laporan Anggaran 5 Tahun Fiskal
                </h2>
                <p class="mt-1 text-gray-500 dark:text-gray-400">
                    Lihat total anggaran yang dipakai untuk program (Komunikasi, Publikasi, dan Media) dari 5 tahun fiskal
                    terakhir.
                </p>
            </div>

            {{-- Tombol/Link Aksi --}}
            <div class="mt-4">
                <a href="#" wire:click="printTable"
                    class="inline-flex items-center gap-1 px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <x-heroicon-o-printer class="w-4 h-4" /> Cetak PDF
                </a>
            </div>
        </div>

    </div>

    {{-- Kita buat grid di sini --}}
    <div class="my-0 pt-2 text-xl font-bold border-t border-gray-600">Modul Pengembangan Masyarakat</div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- KARTU 1: Laporan Anggaran Fiskal --}}
        <div class="p-6 bg-white rounded-xl shadow-lg space-y-4 dark:bg-gray-800">
            <div>
                {{-- Judul Kartu --}}
                <h2 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Laporan Anggaran 5 Tahun Fiskal
                </h2>
                <p class="mt-1 text-gray-500 dark:text-gray-400">
                    Lihat total anggaran yang dipakai untuk program (Pengembangan Masyarakat) dari 5 tahun fiskal
                    terakhir.
                </p>
            </div>

            {{-- Tombol/Link Aksi --}}
            <div class="mt-4">
                <a href="#" wire:click="printTable"
                    class="inline-flex items-center gap-1 px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <x-heroicon-o-printer class="w-4 h-4" /> Cetak PDF
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
                    Lihat rekapitulasi dan rincian total anggaran program per tahun fiskal.
                </p>
            </div>

            {{-- Tombol/Link Aksi --}}
            <div class="mt-4">
                <a href="{{ url('admin/pengmas/cetak-laporan-program') }}"
                    class="inline-flex items-center gap-1 px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <x-heroicon-o-magnifying-glass class="w-4 h-4" /> Pilih Laporan
                </a>
            </div>
        </div>

        {{-- KARTU 2: Laporan Anggaran Program --}}
        <div class="p-6 bg-white rounded-xl shadow-lg space-y-4 dark:bg-gray-800">
            <div>
                {{-- Judul Kartu --}}
                <h2 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Laporan Anggaran Kegiatan
                </h2>
                <p class="mt-1 text-gray-500 dark:text-gray-400">
                    Lihat rekapitulasi dan rincian kegiatan berdasarkan masing-masing program per tahun fiskal.
                </p>
            </div>

            {{-- Tombol/Link Aksi --}}
            <div class="mt-4">
                <a href="{{ url('admin/pengmas/cetak-laporan-kegiatan') }}"
                    class="inline-flex items-center gap-1 px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <x-heroicon-o-magnifying-glass class="w-4 h-4" /> Pilih Laporan
                </a>
            </div>
        </div>

        {{-- Anda bisa menambahkan kartu lain di sini --}}

    </div>

</x-filament-panels::page>