@extends('layouts.app')

@section('title', 'CSR')

@section('content')
    <!-- Hero Section -->
    <section class="bg-cover bg-center min-h-screen flex items-center justify-center bg-gray-950 text-white relative overflow-hidden"
        style="background-image: linear-gradient(to bottom, rgba(0,0,0,0.5), rgba(0,0,0,0.7)), url('https://source.unsplash.com/random/1600x900/?hero')">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <!-- Hero Text -->
                <div class="md:w-1/2 py-8 mb-8 md:mb-0" data-aos="fade-right" data-aos-duration="1000" data-aos-easing="ease-in-out">
                    <h1 class="text-4xl md:text-6xl font-extrabold leading-tight tracking-tight">Sistem Informasi CSR</h1>
                    <p class="mt-6 text-xl text-gray-200 max-w-lg">
                        Pantau rencana dan pelaksanaan kegiatan perusahaan secara real-time
                    </p>
                    <!-- Call-to-Action Buttons -->
                    <div class="mt-8 flex space-x-4">
                        <a href="https://www.youtube.com/" target="_blank" class="bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold py-3 px-8 rounded-full hover:from-orange-600 hover:to-orange-700 transition-all duration-300 transform hover:scale-105" data-aos="zoom-in" data-aos-delay="200">
                            Lihat Kanal YouTube
                        </a>
                        <a href="{{ route('berita.index') }}"
                            class="border-2 border-orange-500 text-orange-500 font-semibold py-3 px-8 rounded-full hover:bg-orange-500 hover:text-white transition-all duration-300 transform hover:scale-105" data-aos="zoom-in" data-aos-delay="400">
                            Lihat Semua Berita
                        </a>
                    </div>
                </div>
                <!-- Hero Image -->
                <div class="md:w-1/2" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="300" data-aos-easing="ease-in-out">
                    <img class="w-full px-8 drop-shadow-2xl" src="{{ url('assets/splash-art.png') }}" alt="Hero Image">
                </div>
            </div>
            <div class="text-center" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300" data-aos-easing="ease-in-out">
                Developed by {{ config('app.corp') }}
            </div>
        </div>
    </section>

    <!-- News Portal Section -->
    <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 bg-gray-50">
        <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
            <h1 class="text-5xl font-extrabold text-gray-900 mb-4">Portal Berita (Contoh)</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Berita terbaru terkait CSR {{ config('app.corp') }}</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @forelse ($beritas as $berita)
                <a href="{{ route('berita.show', $berita->slug) }}"
                   class="block bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2"
                   data-aos="fade-up" data-aos-duration="800" data-aos-delay="{{ $loop->iteration * 150 }}"
                   data-aos-anchor-placement="top-bottom">
                    <img src="{{ $berita->getFirstMediaUrl('images') ?: url('assets/banner.png') }}" alt="{{ $berita->judul }}"
                         class="w-full object-cover rounded-t-xl">
                    <div class="p-6">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-3 line-clamp-2">{{ $berita->judul }}</h2>
                        <p class="text-gray-500 text-sm mb-3">Oleh: <span class="font-medium">{{ $berita->penulis }}</span></p>
                        @if($berita->kategori)
                            <span class="inline-block bg-orange-100 text-orange-600 px-3 py-1 text-xs font-semibold rounded-full">{{ $berita->kategori->nama }}</span>
                        @endif
                    </div>
                </a>
            @empty
                <p class="text-center col-span-3 text-gray-500 text-lg" data-aos="fade-up" data-aos-delay="200">Belum ada berita yang dipublikasikan.</p>
            @endforelse
        </div>

        <!-- View All News Button -->
        @if(isset($totalBerita) && $totalBerita > 6)
            <div class="text-center mt-12" data-aos="zoom-in" data-aos-duration="800" data-aos-delay="400">
                <a href="{{ route('berita.index') }}"
                   class="inline-block bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold py-3 px-10 rounded-full shadow-md hover:from-orange-600 hover:to-orange-700 transition-all duration-300 transform hover:scale-105">
                    Lihat Seluruh Berita
                </a>
            </div>
        @endif
    </section>
@endsection