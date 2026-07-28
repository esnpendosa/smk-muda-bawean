<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('components.seo-head', ['seo' => $seo ?? null])
    <link rel="icon" type="image/png" href="{{ asset('images/logo-smk.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-smk.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primary:   {{ theme_colors()['color_primary']   ?? '#16a34a' }};
            --color-secondary: {{ theme_colors()['color_secondary'] ?? '#15803d' }};
            --color-accent:    {{ theme_colors()['color_accent']    ?? '#bbf7d0' }};
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Justify konten artikel dari rich text editor */
        .prose p {
            text-align: justify;
            hyphens: auto;
            -webkit-hyphens: auto;
            word-break: break-word;
        }
        /* Pastikan gambar di konten artikel tidak meluber */
        .prose img {
            max-width: 100%;
            height: auto;
            border-radius: 0.75rem;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
        /* Ukuran thumbnail yang direkomendasikan: 1200x630px (rasio 1.91:1) untuk OG */
    </style>
</head>
<body class="h-full flex flex-col bg-gray-50 text-gray-800 selection:bg-green-100 selection:text-green-800">

    <!-- Skip link -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-green-600 focus:text-white focus:font-bold focus:rounded-lg">
        Lewati Navigasi
    </a>

    <!-- Header -->
    <header role="banner" class="sticky top-0 z-40 bg-white header-shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-4">

                <!-- Branding -->
                <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="SMK Muda Bawean - Halaman Utama">
                    <picture>
                        <source srcset="{{ asset('images/logo-smk.png') }}" type="image/png">
                        <img src="{{ asset('images/logo-smk.svg') }}"
                             alt="Logo SMK Muhammadiyah 4 Sangkapura"
                             class="h-12 w-12 object-contain" loading="eager">
                    </picture>
                    <div class="leading-tight">
                        <span class="block text-base font-extrabold text-green-700 tracking-tight">SMK MUDA Bawean</span>
                        <span class="block text-xs text-gray-450 font-semibold">Muhammadiyah 4 Sangkapura</span>
                    </div>
                </a>

                <!-- Desktop Nav -->
                <nav role="navigation" aria-label="Navigasi utama" class="hidden md:flex items-center gap-2 lg:gap-3">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-active' : '' }}">Home</a>

                    <!-- Profil dropdown -->
                    <div class="relative group">
                        <button type="button" aria-expanded="false"
                            class="nav-link flex items-center gap-1 {{ request()->routeIs('profil.*') ? 'nav-active' : '' }}">
                            Profil 
                            <svg class="w-3.5 h-3.5 group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="absolute left-0 mt-1 w-48 rounded-xl bg-white border border-gray-100 shadow-lg opacity-0 scale-95 pointer-events-none group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto transition-all duration-200 z-50">
                            <div class="p-1.5 space-y-0.5">
                                <a href="{{ route('profil.sejarah') }}"   class="block px-3 py-2 text-sm rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('profil.sejarah') ? 'bg-green-50 text-green-700 font-bold' : '' }}">Sejarah</a>
                                <a href="{{ route('profil.visi-misi') }}" class="block px-3 py-2 text-sm rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('profil.visi-misi') ? 'bg-green-50 text-green-700 font-bold' : '' }}">Visi &amp; Misi</a>
                                <a href="{{ route('profil.pendidik') }}"  class="block px-3 py-2 text-sm rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('profil.pendidik') ? 'bg-green-50 text-green-700 font-bold' : '' }}">Pendidik</a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('berita.index') }}"     class="nav-link {{ request()->routeIs('berita.*') ? 'nav-active' : '' }}">Berita</a>
                    <a href="{{ route('pengumuman.index') }}" class="nav-link {{ request()->routeIs('pengumuman.*') ? 'nav-active' : '' }}">Pengumuman</a>
                    <a href="{{ route('kelulusan.index') }}"  class="nav-link {{ request()->routeIs('kelulusan.*') ? 'nav-active' : '' }}">Kelulusan</a>

                    <!-- Alumni dropdown -->
                    <div class="relative group">
                        <button type="button" aria-expanded="false"
                            class="nav-link flex items-center gap-1 {{ request()->routeIs('alumni.*') ? 'nav-active' : '' }}">
                            Alumni 
                            <svg class="w-3.5 h-3.5 group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="absolute left-0 mt-1 w-52 rounded-xl bg-white border border-gray-100 shadow-lg opacity-0 scale-95 pointer-events-none group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto transition-all duration-200 z-50">
                            <div class="p-1.5 space-y-0.5">
                                <a href="{{ route('alumni.index') }}"        class="block px-3 py-2 text-sm rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('alumni.index') ? 'bg-green-50 text-green-700 font-bold' : '' }}">Pendaftaran Alumni</a>
                                <a href="{{ route('alumni.tracer-study') }}" class="block px-3 py-2 text-sm rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('alumni.tracer-study') ? 'bg-green-50 text-green-700 font-bold' : '' }}">Tracer Study</a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('ppdb.index') }}"
                       class="ml-3 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition duration-200 shadow-sm text-sm {{ request()->routeIs('ppdb.index') ? 'ring-2 ring-offset-2 ring-green-600' : '' }}">
                        PPDB Online
                    </a>
                </nav>

                <!-- Mobile button -->
                <div class="md:hidden">
                    <button id="mobile-menu-btn" aria-expanded="false" aria-label="Buka Menu" type="button"
                        class="inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-green-700 hover:bg-green-50 transition duration-200">
                        <svg class="h-6 w-6 block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg class="h-6 w-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 px-4 pt-2 pb-4 space-y-1">
            <a href="{{ route('home') }}"             class="block px-3 py-2 rounded-lg text-gray-700 font-medium hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('home') ? 'bg-green-50 text-green-700 font-bold' : '' }}">Home</a>
            
            <p class="px-3 pt-2 pb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">Profil</p>
            <a href="{{ route('profil.sejarah') }}"   class="block pl-5 px-3 py-2 rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('profil.sejarah') ? 'bg-green-50 text-green-700 font-bold' : '' }}">Sejarah</a>
            <a href="{{ route('profil.visi-misi') }}" class="block pl-5 px-3 py-2 rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('profil.visi-misi') ? 'bg-green-50 text-green-700 font-bold' : '' }}">Visi &amp; Misi</a>
            <a href="{{ route('profil.pendidik') }}"  class="block pl-5 px-3 py-2 rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('profil.pendidik') ? 'bg-green-50 text-green-700 font-bold' : '' }}">Pendidik</a>
            
            <a href="{{ route('berita.index') }}"     class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('berita.*') ? 'bg-green-50 text-green-700 font-bold' : '' }}">Berita</a>
            <a href="{{ route('pengumuman.index') }}" class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('pengumuman.*') ? 'bg-green-50 text-green-700 font-bold' : '' }}">Pengumuman</a>
            <a href="{{ route('kelulusan.index') }}"  class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('kelulusan.*') ? 'bg-green-50 text-green-700 font-bold' : '' }}">Kelulusan</a>
            
            <p class="px-3 pt-2 pb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">Alumni</p>
            <a href="{{ route('alumni.index') }}"        class="block pl-5 px-3 py-2 rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('alumni.index') ? 'bg-green-50 text-green-700 font-bold' : '' }}">Pendaftaran</a>
            <a href="{{ route('alumni.tracer-study') }}" class="block pl-5 px-3 py-2 rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('alumni.tracer-study') ? 'bg-green-50 text-green-700 font-bold' : '' }}">Tracer Study</a>
            
            <div class="pt-3 px-1">
                <a href="{{ route('ppdb.index') }}" class="block w-full py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl text-center transition duration-200">
                    PPDB Online
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main id="main-content" role="main" tabindex="-1" class="flex-grow focus:outline-none">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer role="contentinfo" class="bg-green-800 text-white pt-14 pb-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">

                <!-- About -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <picture>
                            <source srcset="{{ asset('images/logo-smk.png') }}" type="image/png">
                            <img src="{{ asset('images/logo-smk.svg') }}" alt="Logo SMK MUDA" class="h-14 w-14 object-contain bg-white rounded-lg p-1" loading="lazy">
                        </picture>
                        <div>
                            <p class="font-extrabold text-white text-base leading-tight">SMK MUDA Bawean</p>
                            <p class="text-xs text-green-200">Muhammadiyah 4 Sangkapura</p>
                        </div>
                    </div>
                    <p class="text-sm text-green-200 leading-relaxed">Menjadikan sekolah yang Mandiri, Unggul, Disiplin, dan Agamis.</p>
                </div>

                <!-- Quick links -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-green-100 uppercase tracking-wider">Navigasi Cepat</h3>
                    <ul class="space-y-2 text-sm text-green-200">
                        <li><a href="{{ route('profil.sejarah') }}"   class="hover:text-white transition">Sejarah Sekolah</a></li>
                        <li><a href="{{ route('profil.visi-misi') }}" class="hover:text-white transition">Visi &amp; Misi</a></li>
                        <li><a href="{{ route('berita.index') }}"     class="hover:text-white transition">Kabar &amp; Berita</a></li>
                        <li><a href="{{ route('pengumuman.index') }}" class="hover:text-white transition">Pengumuman Resmi</a></li>
                    </ul>
                </div>

                <!-- Academic -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-green-100 uppercase tracking-wider">Layanan Akademik</h3>
                    <ul class="space-y-2 text-sm text-green-200">
                        <li><a href="{{ route('ppdb.index') }}"      class="hover:text-white transition">PPDB Online</a></li>
                        <li><a href="{{ route('kelulusan.index') }}" class="hover:text-white transition">Status Kelulusan</a></li>
                        <li><a href="{{ route('alumni.index') }}"    class="hover:text-white transition">Pendaftaran Alumni</a></li>
                        <li><a href="{{ route('faq.index') }}"       class="hover:text-white transition">FAQ</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-green-100 uppercase tracking-wider">Hubungi Kami</h3>
                    <ul class="space-y-3.5 text-sm text-green-200">
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-green-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>JL. KH. Ahmad Dahlan No. 01, Daun, Sangkapura, Bawean</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span>+62 853-3324-5454</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span>smkmuda4bawean@gmail.com</span>
                        </li>
                        <li class="flex gap-4 pt-2">
                            <a href="https://www.facebook.com/profile.php?id=100086197102754" target="_blank" rel="noopener" class="hover:text-white transition font-semibold text-xs flex items-center gap-1">
                                <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8H7v3h2v9h3v-9h2.72L15 8h-3V6.12C12 5.29 12.12 5 13 5h1.5V2H12c-2.3 0-3 1.12-3 2.5V8z"></path></svg>
                                Facebook
                            </a>
                            <a href="https://www.instagram.com/smkmudabawean" target="_blank" rel="noopener" class="hover:text-white transition font-semibold text-xs flex items-center gap-1">
                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                                Instagram
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-green-700 pt-8 flex items-center justify-center text-xs text-green-300">
                <p>&copy; {{ date('Y') }} SMK Muhammadiyah 4 Sangkapura Bawean. Developed by <a href="https://kangdigital.web.id" target="_blank" rel="noopener" class="hover:text-green-150 transition font-medium">Kang Digital</a>.</p>
            </div>
        </div>
    </footer>

    <script>
        const btn  = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        btn.addEventListener('click', () => {
            const open = btn.getAttribute('aria-expanded') === 'true';
            btn.setAttribute('aria-expanded', !open);
            menu.classList.toggle('hidden');
            btn.querySelectorAll('svg').forEach(s => s.classList.toggle('hidden'));
        });
    </script>

    @stack('schema')
</body>
</html>
