<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
  <!-- Navbar -->
  <nav class="bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16 items-center">
        <!-- Logo -->
        <div class="flex-shrink-0">

          <a href="#" class="text-2xl font-bold text-indigo-600">
            <img class="max-h-14" src="{{ url('assets/pik2-logo.png') }}">
          </a>
        </div>

        <!-- Menu Desktop -->
        <div class="hidden md:flex gap-6">
          <span class="py-2 px-3 text-gray-700">
            <span id="clockDisplay"></span> - <span id="dateDisplay"></span>
          </span>
          <a href="{{ url('admin') }}" class="bg-orange-500 py-2 px-4 rounded text-orange-100 font-bold hover:text-orange-900">Login</a>
        </div>

        <!-- Menu Mobile (Hamburger Icon) -->
        <div class="md:hidden">
          <button id="menu-btn" class="text-gray-700 hover:text-indigo-600 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Menu Dropdown -->
    <div id="mobile-menu" class="hidden md:hidden">
      <div class="px-2 pt-2 pb-3 space-y-1">
        <a href="{{ url('admin') }}" class="block text-gray-700 hover:text-indigo-600">Login</a>
      </div>
    </div>
  </nav>

  <main class="min-h-[99vh]">
    <div class="">
      @yield('content')
    </div>
  </main>

  <footer class="bg-gray-950 text-white text-center py-4">
    &copy; {{ date('Y') }} PT Pantai Indah Kapuk 2, Tbk (PANI).
  </footer>

  <!-- JavaScript untuk Toggle Mobile Menu -->
  <script>
    const menuBtn = document.getElementById('menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    menuBtn.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
  </script>

  <script>
    function updateClock() {
      const now = new Date(); // Dapatkan objek Date saat ini

      // --- Untuk Waktu ---
      const hours = String(now.getHours()).padStart(2, '0'); // Dapatkan jam, format 2 digit
      const minutes = String(now.getMinutes()).padStart(2, '0'); // Dapatkan menit, format 2 digit
      const seconds = String(now.getSeconds()).padStart(2, '0'); // Dapatkan detik, format 2 digit

      const timeString = `${hours}:${minutes}:${seconds}`;
      document.getElementById('clockDisplay').textContent = timeString;

      // --- Untuk Tanggal ---
      const optionsDate = {
        weekday: 'long', // Nama hari (contoh: "Jumat")
        year: 'numeric', // Tahun (contoh: "2025")
        month: 'long',   // Nama bulan (contoh: "Juni")
        day: 'numeric'   // Tanggal (contoh: "13")
      };
      // `toLocaleDateString()` adalah cara yang bagus untuk format tanggal sesuai lokal pengguna
      const dateString = now.toLocaleDateString('id-ID', optionsDate); // Menggunakan 'id-ID' untuk format Indonesia
      document.getElementById('dateDisplay').textContent = dateString;
    }

    // Panggil fungsi `updateClock` setiap 1000 milidetik (1 detik)
    setInterval(updateClock, 1000);

    // Panggil juga sekali saat halaman pertama kali dimuat agar langsung tampil
    updateClock();
  </script>

</body>

</html>