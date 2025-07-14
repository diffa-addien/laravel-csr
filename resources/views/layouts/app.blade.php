<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.corp') }}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- AOS CSS CDN -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>

<body class="bg-gray-50 font-sans">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg fixed w-full z-50" data-aos="fade-down" data-aos-duration="200" data-aos-easing="ease-in-out">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ url('/') }}" class="flex items-center">
                        <!-- <img class="h-12 w-auto" src="{{ url('assets/pik2-logo.png') }}" alt="PIK2 Logo"> -->
                        <h1 class="font-bold text-2xl">CSR 1.1</h1>
                    </a>
                </div>

                <!-- Menu Desktop -->
                <div class="hidden md:flex items-center gap-8">
                    <span class="text-gray-600 text-sm font-medium" data-aos="fade-left" data-aos-delay="200">
                        <span id="clockDisplay"></span> - <span id="dateDisplay"></span>
                    </span>
                    <a href="{{ url('admin') }}"
                       class="bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold py-2 px-6 rounded-full hover:from-orange-600 hover:to-orange-700 transition-all duration-300 transform hover:scale-105"
                       data-aos="fade-left" data-aos-delay="400">
                        Login
                    </a>
                </div>

                <!-- Menu Mobile (Hamburger Icon) -->
                <div class="md:hidden">
                    <button id="menu-btn" class="text-gray-600 hover:text-orange-600 focus:outline-none" aria-label="Toggle menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobile-menu" class="hidden md:hidden bg-white shadow-md">
            <div class="px-4 pt-2 pb-4 space-y-2" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                <span class="block text-gray-600 text-sm font-medium">
                    <span id="clockDisplayMobile"></span> - <span id="dateDisplayMobile"></span>
                </span>
                <a href="{{ url('admin') }}"
                   class="block text-gray-700 hover:text-orange-600 font-semibold py-2">Login</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen pt-16">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-950 text-white text-center py-6">
        <p class="text-sm">© {{ date('Y').' '.config('app.corp') }}. All rights reserved.</p>
    </footer>

    <!-- JavaScript for Toggle Mobile Menu -->
    <script>
        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>

    <!-- JavaScript for Clock and Date -->
    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeString = `${hours}:${minutes}:${seconds}`;
            document.getElementById('clockDisplay').textContent = timeString;
            document.getElementById('clockDisplayMobile').textContent = timeString;

            const optionsDate = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateString = now.toLocaleDateString('id-ID', optionsDate);
            document.getElementById('dateDisplay').textContent = dateString;
            document.getElementById('dateDisplayMobile').textContent = dateString;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>

    <!-- AOS JS CDN -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Initialize AOS -->
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
        });
    </script>
</body>

</html>