@extends('layouts.app')

@section('title', 'Semua Berita')

@section('content')
    <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800" data-aos-easing="ease-in-out">
            <h1 class="text-5xl font-extrabold text-gray-900 mb-4">Portal Berita</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Semua berita terbaru terkait CSR PT Pantai Indah Kapuk 2, Tbk</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($beritas as $berita)
                <a href="{{ route('berita.show', $berita->slug) }}"
                   class="block bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2"
                   data-aos="fade-up" data-aos-duration="800" data-aos-delay="{{ $loop->iteration * 150 }}"
                   data-aos-anchor-placement="top-bottom">
                    <img src="{{ $berita->getFirstMediaUrl('images') ?: url('assets/banner.png') }}" alt="{{ $berita->judul }}"
                         class="w-full h-56 object-cover rounded-t-xl">
                    <div class="p-6">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-3 line-clamp-2">{{ $berita->judul }}</h2>
                        <p class="text-gray-500 text-sm mb-3">Oleh: <span class="font-medium">{{ $berita->penulis }}</span></p>
                        @if($berita->kategori)
                            <span class="inline-block bg-orange-100 text-orange-600 px-3 py-1 text-xs font-semibold rounded-full">{{ $berita->kategori->nama }}</span>
                        @endif
                    </div>
                </a>
            @empty
                <p class="text-center col-span-full text-gray-500 text-lg py-10" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                    Belum ada berita yang dipublikasikan.
                </p>
            @endforelse
        </div>

        <!-- Pagination Links -->
        <div class="mt-12 flex justify-center" data-aos="fade-up" data-aos-duration="800" data-aos-delay="400">
            {{ $beritas->links('pagination::tailwind') }}
        </div>
    </section>
@endsection