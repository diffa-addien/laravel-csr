<?php

namespace App\Http\Controllers;

use App\Models\Berita; // 1. Import model Berita
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    /**
     * Menampilkan halaman beranda dengan 6 berita terbaru.
     */
    public function index()
    {
        // Ambil 6 berita terbaru
        $beritas = Berita::where('is_published', true)
            ->latest()
            ->take(6)
            ->get();

        // Hitung total semua berita yang sudah publish
        $totalBerita = Berita::where('is_published', true)->count();

        return view('beranda', [
            'beritas' => $beritas,
            'totalBerita' => $totalBerita // Kirim variabel ini ke view
        ]);
    }

    /**
     * Menampilkan semua berita dengan paginasi.
     */
    public function semuaBerita()
    {
        $semua_berita = Berita::where('is_published', true)
            ->with('kategori', 'media')
            ->latest()
            ->paginate(9); // Misal 9 berita per halaman

        return view('berita.index', [
            'beritas' => $semua_berita
        ]);
    }

    /**
     * Menampilkan satu berita penuh berdasarkan slug.
     */
    public function showBerita($slug)
    {
        $berita = Berita::where('slug', $slug)
            ->where('is_published', true)
            ->with(['kategori', 'tags', 'media']) // Load semua relasi
            ->firstOrFail(); // Otomatis 404 jika tidak ditemukan

        return view('berita.show', [
            'berita' => $berita
        ]);
    }
}