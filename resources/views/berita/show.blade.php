@extends('layouts.app')

@section('title', $berita->judul)

@section('content')
<div class="container mx-auto px-4 py-12">
    <article class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        
        <!-- Judul dan Meta -->
        <header class="mb-8">
            @if($berita->kategori)
                <p class="text-blue-500 font-semibold mb-2">{{ $berita->kategori->nama }}</p>
            @endif
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">{{ $berita->judul }}</h1>
            <div class="text-gray-500 text-sm">
                <span>Ditulis oleh <span class="font-semibold">{{ $berita->penulis }}</span></span>
                <span class="mx-2">&bull;</span>
                <span>{{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('d F Y') }}</span>
            </div>
        </header>

        <!-- Gambar Utama -->
        @if($berita->hasMedia('images'))
            <div class="mb-8">
                <img src="{{ $berita->getFirstMediaUrl('images') }}" alt="{{ $berita->judul }}" class="w-full h-auto object-cover rounded-lg shadow-md">
            </div>
        @endif

        <!-- Konten Berita dari Rich Editor -->
        <div class="prose lg:prose-xl max-w-none text-gray-800">
            {!! $berita->konten !!}
        </div>

        <!-- Galeri Gambar Tambahan -->
        @if($berita->getMedia('images')->count() > 1)
            <div class="mt-10">
                <h3 class="text-2xl font-bold mb-4">Galeri Gambar</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($berita->getMedia('images')->slice(1) as $media)
                        <a href="{{ $media->getUrl() }}" data-fancybox="gallery">
                            <img src="{{ $media->getUrl() }}" alt="Galeri gambar" class="rounded-lg shadow-md">
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Tags dan Sumber -->
        <footer class="mt-12 border-t pt-6">
            @if($berita->tags->isNotEmpty())
                <div class="mb-4">
                    <span class="font-semibold">Tags:</span>
                    @foreach($berita->tags as $tag)
                        <span class="ml-2 inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700">{{ $tag->nama }}</span>
                    @endforeach
                </div>
            @endif

            @if($berita->sumber)
                <div>
                    <span class="font-semibold">Sumber:</span>
                    <a href="{{ $berita->sumber }}" target="_blank" rel="noopener noreferrer" class="ml-2 text-blue-600 hover:underline break-all">{{ $berita->sumber }}</a>
                </div>
            @endif
        </footer>

    </article>
</div>
@endsection
