<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Head Component -->
    @include('components.seo-head', ['seo' => $seo ?? null])

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Theme CSS Variables mapping to Tailwind colors -->
    <style>
        :root {
            --color-primary: {{ theme_colors()['color_primary'] ?? '#16a34a' }};
            --color-secondary: {{ theme_colors()['color_secondary'] ?? '#15803d' }};
            --color-accent: {{ theme_colors()['color_accent'] ?? '#bbf7d0' }};
        }
        body {
            font-family: 'Outfit', sans-serif;
        }
        .glass-header {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: 'var(--color-primary)',
                        secondary: 'var(--color-secondary)',
                        accent: 'var(--color-accent)',
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full flex flex-col bg-slate-950 text-slate-100 selection:bg-primary selection:text-slate-950">

    <!-- Accessibility Skip Link -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-primary focus:text-slate-950 focus:font-bold focus:rounded-lg focus:outline-none focus:ring-2 focus:ring-accent">
        Lewati Navigasi
    </a>

    <!-- Header Banner -->
    <header role="banner" class="sticky top-0 z-40 glass-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Branding -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5" aria-label="SMK Muda Bawean - Halaman Utama">
                        <picture>
                            <source srcset="{{ asset('images/logo-smk.png') }}" type="image/png">
                            <img src="{{ asset('images/logo-smk.svg') }}"
                                 alt="Logo SMK Muhammadiyah 4 Sangkapura Bawean"
                                 class="h-12 w-12 object-contain"
                                 loading="eager">
                        </picture>
                        <span class="text-xl font-black tracking-tight text-white leading-tight">
                            SMK <span class="bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent">MUDA</span><br>
                            <span class="text-xs font-semibold text-slate-400 tracking-wide">Muhammadiyah 4 Sangkapura</span>
                        </span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav role="navigation" aria-label="Navigasi utama" class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg text-sm font-medium hover:text-white hover:bg-slate-900 transition duration-200">Home</a>
                    
                    <!-- Profil Dropdown -->
                    <div class="relative group">
                        <button type="button" aria-expanded="false" class="px-3 py-2 rounded-lg text-sm font-medium flex items-center gap-1 hover:text-white hover:bg-slate-900 transition duration-200">
                            Profil <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="absolute left-0 mt-1 w-48 rounded-xl bg-slate-900 border border-slate-800 shadow-xl opacity-0 scale-95 pointer-events-none group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto transition-all duration-200 z-50">
                            <div class="p-1.5 space-y-1">
                                <a href="{{ route('profil.sejarah') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-slate-800 transition duration-200">Sejarah</a>
                                <a href="{{ route('profil.visi-misi') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-slate-800 transition duration-200">Visi & Misi</a>
                                <a href="{{ route('profil.pendidik') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-slate-800 transition duration-200">Pendidik</a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('berita.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium hover:text-white hover:bg-slate-900 transition duration-200">Berita</a>
                    <a href="{{ route('pengumuman.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium hover:text-white hover:bg-slate-900 transition duration-200">Pengumuman</a>
                    <a href="{{ route('kelulusan.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium hover:text-white hover:bg-slate-900 transition duration-200">Kelulusan</a>

                    <!-- Alumni Dropdown -->
                    <div class="relative group">
                        <button type="button" aria-expanded="false" class="px-3 py-2 rounded-lg text-sm font-medium flex items-center gap-1 hover:text-white hover:bg-slate-900 transition duration-200">
                            Alumni <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="absolute left-0 mt-1 w-48 rounded-xl bg-slate-900 border border-slate-800 shadow-xl opacity-0 scale-95 pointer-events-none group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto transition-all duration-200 z-50">
                            <div class="p-1.5 space-y-1">
                                <a href="{{ route('alumni.index') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-slate-800 transition duration-200">Pendaftaran Alumni</a>
                                <a href="{{ route('alumni.tracer-study') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-slate-800 transition duration-200">Tracer Study</a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('ppdb.index') }}" class="ml-4 px-4 py-2 bg-primary hover:bg-secondary text-slate-950 font-bold rounded-lg transition duration-200 shadow-lg shadow-primary/20">
                        PPDB Online
                    </a>
                </nav>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="mobile-menu-btn" aria-expanded="false" aria-label="Buka Menu" type="button" class="inline-flex items-center justify-center p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-900 focus:outline-none transition duration-200">
                        <svg class="h-6 h-6 block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        <svg class="h-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-slate-950/95 border-b border-slate-900 px-4 pt-2 pb-4 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-base font-medium hover:bg-slate-900">Home</a>
            <div class="border-t border-slate-900 my-1"></div>
            <p class="px-3 py-1 text-xs font-semibold text-slate-500 uppercase tracking-wider">Profil</p>
            <a href="{{ route('profil.sejarah') }}" class="block pl-6 px-3 py-2 rounded-lg text-base font-medium hover:bg-slate-900">Sejarah</a>
            <a href="{{ route('profil.visi-misi') }}" class="block pl-6 px-3 py-2 rounded-lg text-base font-medium hover:bg-slate-900">Visi & Misi</a>
            <a href="{{ route('profil.pendidik') }}" class="block pl-6 px-3 py-2 rounded-lg text-base font-medium hover:bg-slate-900">Pendidik</a>
            <div class="border-t border-slate-900 my-1"></div>
            <a href="{{ route('berita.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium hover:bg-slate-900">Berita</a>
            <a href="{{ route('pengumuman.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium hover:bg-slate-900">Pengumuman</a>
            <a href="{{ route('kelulusan.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium hover:bg-slate-900">Kelulusan</a>
            <div class="border-t border-slate-900 my-1"></div>
            <p class="px-3 py-1 text-xs font-semibold text-slate-500 uppercase tracking-wider">Alumni</p>
            <a href="{{ route('alumni.index') }}" class="block pl-6 px-3 py-2 rounded-lg text-base font-medium hover:bg-slate-900">Pendaftaran</a>
            <a href="{{ route('alumni.tracer-study') }}" class="block pl-6 px-3 py-2 rounded-lg text-base font-medium hover:bg-slate-900">Tracer Study</a>
            <div class="pt-4 px-3">
                <a href="{{ route('ppdb.index') }}" class="block w-full py-3 bg-primary hover:bg-secondary text-slate-950 font-bold rounded-xl text-center shadow-lg transition duration-200">
                    PPDB Online
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main id="main-content" role="main" tabindex="-1" class="flex-grow focus:outline-none">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer role="contentinfo" class="bg-slate-950 border-t border-slate-900 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <!-- About -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <picture>
                            <source srcset="{{ asset('images/logo-smk.png') }}" type="image/png">
                            <img src="{{ asset('images/logo-smk.svg') }}" alt="Logo SMK MUDA" class="h-14 w-14 object-contain" loading="lazy">
                        </picture>
                        <span class="text-lg font-extrabold text-white leading-tight">SMK MUDA<br><span class="text-xs font-normal text-slate-400">Muhammadiyah 4 Sangkapura</span></span>
                    </div>
                    <p class="text-sm text-slate-400">Menjadikan sekolah yang Mandiri, Unggul, Disiplin, dan Agamis.</p>
                </div>
                
                <!-- Quick links -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Navigasi Cepat</h3>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="{{ route('profil.sejarah') }}" class="hover:text-primary transition duration-150">Sejarah Sekolah</a></li>
                        <li><a href="{{ route('profil.visi-misi') }}" class="hover:text-primary transition duration-150">Visi & Misi</a></li>
                        <li><a href="{{ route('berita.index') }}" class="hover:text-primary transition duration-150">Kabar & Berita</a></li>
                        <li><a href="{{ route('pengumuman.index') }}" class="hover:text-primary transition duration-150">Pengumuman Resmi</a></li>
                    </ul>
                </div>

                <!-- Academic -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Layanan Akademik</h3>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="{{ route('ppdb.index') }}" class="hover:text-primary transition duration-150">PPDB Online</a></li>
                        <li><a href="{{ route('kelulusan.index') }}" class="hover:text-primary transition duration-150">Status Kelulusan</a></li>
                        <li><a href="{{ route('alumni.index') }}" class="hover:text-primary transition duration-150">Pendaftaran Alumni</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Hubungi Kami</h3>
                    <ul class="space-y-1.5 text-sm text-slate-400">
                        <li>📍 JL. KH. Ahmad Dahlan No. 01, Daun, Sangkapura, Bawean</li>
                        <li>📞 +62 853-3324-5454</li>
                        <li>✉️ smkmuda4bawean@gmail.com</li>
                        <li class="flex gap-3 pt-1">
                            <a href="https://www.facebook.com/profile.php?id=100086197102754" target="_blank" rel="noopener" class="hover:text-primary transition">Facebook</a>
                            <a href="https://www.instagram.com/smkmudabawean" target="_blank" rel="noopener" class="hover:text-primary transition">Instagram</a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-slate-900 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} SMK Muda Bawean. Semua Hak Cipta Dilindungi.</p>
                <div class="flex gap-4">
                    <a href="{{ route('sitemap') }}" class="hover:text-slate-300">Sitemap</a>
                    <a href="{{ route('robots') }}" class="hover:text-slate-300">Robots.txt</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Hamburger menu Vanilla JS -->
    <script>
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        
        btn.addEventListener('click', () => {
            const isExpanded = btn.getAttribute('aria-expanded') === 'true';
            btn.setAttribute('aria-expanded', !isExpanded);
            menu.classList.toggle('hidden');
            
            // Toggle close/open SVG icons
            const icons = btn.querySelectorAll('svg');
            icons[0].classList.toggle('hidden');
            icons[1].classList.toggle('hidden');
        });
    </script>

    <!-- Schema Markup Component rendering -->
    @stack('schema')

</body>
</html>
