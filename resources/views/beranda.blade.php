@extends('layouts.app')

@section('title', 'CSR PT PANI, Tbk')

@section('content')
    <!-- Hero Section -->
    <section class="bg-cover bg-center min-h-screen flex items-center justify-center bg-gray-950 text-white"
        style="background-image: url('https://source.unsplash.com/random/1600x900/?hero')">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <!-- Teks Hero -->
                <div class="md:w-1/2 py-4 mb-8 md:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold leading-tight">Sistem Informasi CSR PT Pantai Indah Kapuk 2,
                        Tbk</h1>
                    <p class="mt-4 text-lg text-gray-300">
                        Pantau langsung rencana dan pelaksanaan kegiatan perusahaan
                    </p>
                    <!-- Tombol Call-to-Action -->
                    <div class="mt-6 flex space-x-4">
                        <a href="https://www.youtube.com/@CSRPIK2" target="blank" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded">
                            Lihat kanal Youtube</a>
                        <a href="#"
                            class="border border-orange-500 hover:border-orange-600 text-orange-500 font-bold py-3 px-6 rounded">
                            Lihat berita</a>
                    </div>
                </div>

                <!-- Gambar/Kartu Visual -->
                <div class="md:w-1/2">
                    <img class="w-full px-8" src="{{ url('assets/banart.png') }}">
                </div>
            </div>
        </div>
    </section>

    <div class="container mx-auto px-4 mt-8 mb-10">
        <div class="text-center mt-12 mb-4">
            <h1 class="text-4xl font-bold  mb-4">Portal Berita</h1>
            <p class="text-gray-600">Berita terbaru terkait CSR PT Pantai Indah Kapuk 2, Tbk</p>
        </div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <img src="{{ url('assets/banner.png') }}" alt="News Image"
                        class="w-full h-48 object-cover mb-4 rounded-lg">
                    <h2 class="text-xl font-bold mb-2">Judul Berita 1</h2>
                    <p class="text-gray-500 text-sm mb-2">Oleh: <span class="font-semibold">Penulis 3</span></p>
                    <!-- Kategori -->
                    <span class="bg-yellow-500 text-white px-2 py-1 text-xs font-semibold rounded">Kategori 3</span>

                </div>

                <!-- Card 2 -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <img src="{{ url('assets/banner.png') }}" alt="News Image"
                        class="w-full h-48 object-cover mb-4 rounded-lg">
                    <h2 class="text-xl font-bold mb-2">Judul Berita 2</h2>
                    <p class="text-gray-500 text-sm mb-2">Oleh: <span class="font-semibold">Penulis 3</span></p>
                    <!-- Kategori -->
                    <span class="bg-yellow-500 text-white px-2 py-1 text-xs font-semibold rounded">Kategori 3</span>

                </div>

                <!-- Card 3 -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <img src="{{ url('assets/banner.png') }}" alt="News Image"
                        class="w-full h-48 object-cover mb-4 rounded-lg">
                    <h2 class="text-xl font-bold mb-2">Judul Berita 3</h2>
                    <p class="text-gray-500 text-sm mb-2">Oleh: <span class="font-semibold">Penulis 3</span></p>
                    <!-- Kategori -->
                    <span class="bg-yellow-500 text-white px-2 py-1 text-xs font-semibold rounded">Kategori 3</span>
                </div>
            </div>
        </div>
    </div>

@endsection