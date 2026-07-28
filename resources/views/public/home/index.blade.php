@extends('layouts.public')

@section('content')

{{-- Dynamic Hero Slider --}}
<section class="relative bg-gray-900 overflow-hidden" aria-label="Hero Slider">
    @php
        // Helper: resolve setting image path to proper URL
        // Support: images/ (seeder), uploads/ (new), storage/uploads/ (old symlink)
        $resolveImg = function($val, $fallback) {
            if (!$val) return asset($fallback);
            if (str_starts_with($val, 'images/')) return asset($val);
            if (str_starts_with($val, 'uploads/')) return asset($val);
            return asset('storage/' . $val);
        };

        $slide1_bg = \App\Models\Setting::get('slider_slide1_bg');
        $slide1_bg_url = $resolveImg($slide1_bg, 'images/artikel-ujian-digital.png');

        $slide2_bg = \App\Models\Setting::get('slider_slide2_bg');
        $slide2_bg_url = $resolveImg($slide2_bg, 'images/artikel-ukk.png');

        $slide3_bg = \App\Models\Setting::get('slider_slide3_bg');
        $slide3_bg_url = $resolveImg($slide3_bg, 'images/artikel-parenting-ai.png');
    @endphp
    <!-- Slider Container -->
    <div id="hero-slider" class="relative h-[550px] sm:h-[600px] w-full overflow-hidden">
        <!-- Slides Wrapper -->
        <div id="slides-wrapper" class="flex h-full transition-transform duration-700 ease-out" style="width: 300%; transform: translateX(0%);">
            
            <!-- Slide 1: PPDB -->
            <div class="w-1/3 h-full flex-shrink-0 relative text-white flex items-center bg-cover bg-center" style="background-image: url('{{ $slide1_bg_url }}');">
                <div class="absolute inset-0 bg-slate-950/70 z-0 pointer-events-none"></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,_var(--tw-gradient-stops))] from-white/10 via-transparent to-transparent z-0 pointer-events-none"></div>
                <div class="absolute -top-16 -left-16 w-80 h-80 bg-white/5 rounded-full blur-2xl z-0 pointer-events-none"></div>
                <div class="absolute -bottom-16 -right-16 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl z-0 pointer-events-none"></div>
                
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-8 space-y-6 text-left">
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold bg-white/20 backdrop-blur border border-white/30">
                                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                                {{ \App\Models\Setting::get('slider_slide1_title', 'Sistem Penerimaan Murid Baru') }}
                            </span>
                            <h2 class="text-3xl sm:text-5xl font-extrabold leading-tight tracking-tight">
                                {{ \App\Models\Setting::get('slider_slide1_title', 'Sistem Penerimaan Murid Baru') }}<br>
                                <span class="text-green-400">{{ \App\Models\Setting::get('slider_slide1_highlight', 'Tahun Ajaran 2026-2027') }}</span>
                            </h2>
                            <p class="text-sm sm:text-base text-green-100 max-w-xl leading-relaxed">
                                {{ \App\Models\Setting::get('slider_slide1_desc', 'Mari bergabung dengan SMK Muhammadiyah 4 Sangkapura Bawean. Dapatkan promo gratis seragam olahraga dan gratis SPP 2 bulan untuk pendaftar awal pada Gelombang I & II.') }}
                            </p>
                            <div class="flex flex-wrap gap-3 pt-2">
                                <a href="{{ \App\Models\Setting::get('slider_slide1_btn1_link', '/ppdb') }}" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition shadow-md hover:shadow-lg text-sm">
                                    {{ \App\Models\Setting::get('slider_slide1_btn1_text', 'Daftar Siswa Baru') }}
                                </a>
                                <a href="{{ \App\Models\Setting::get('slider_slide1_btn2_link', 'https://wa.me/6285333245454') }}" target="_blank" rel="noopener" class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl border border-white/30 transition text-sm">
                                    {{ \App\Models\Setting::get('slider_slide1_btn2_text', 'Hubungi Panitia') }}
                                </a>
                            </div>
                        </div>
                        <div class="hidden lg:flex lg:col-span-4 justify-center">
                            <img src="{{ asset('images/logo-smk.png') }}" alt="Logo SMK MUDA" class="w-60 h-60 object-contain drop-shadow-2xl animate-bounce-slow">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2: Visi Misi -->
            <div class="w-1/3 h-full flex-shrink-0 relative text-white flex items-center bg-cover bg-center" style="background-image: url('{{ $slide2_bg_url }}');">
                <div class="absolute inset-0 bg-slate-950/70 z-0 pointer-events-none"></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_80%,_var(--tw-gradient-stops))] from-white/10 via-transparent to-transparent z-0 pointer-events-none"></div>
                <div class="absolute top-10 right-10 w-72 h-72 bg-teal-400/5 rounded-full blur-2xl z-0 pointer-events-none"></div>
                
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-8 space-y-6 text-left">
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold bg-white/20 backdrop-blur border border-white/30">
                                {{ \App\Models\Setting::get('slider_slide2_title', 'Membentuk Generasi Unggul') }}
                            </span>
                            <h2 class="text-3xl sm:text-5xl font-extrabold leading-tight tracking-tight">
                                {{ \App\Models\Setting::get('slider_slide2_title', 'Membentuk Generasi Unggul') }}<br>
                                <span class="text-green-400">{{ \App\Models\Setting::get('slider_slide2_highlight', 'Siap Kerja & Berakhlak Mulia') }}</span>
                            </h2>
                            <p class="text-sm sm:text-base text-green-100 max-w-xl leading-relaxed">
                                {{ \App\Models\Setting::get('slider_slide2_desc', 'Kami membekali para siswa dengan perpaduan ilmu pengetahuan praktis dan iman yang kuat sebagai kunci utama meraih masa depan yang gemilang.') }}
                            </p>
                            <div class="flex flex-wrap gap-3 pt-2">
                                <a href="{{ \App\Models\Setting::get('slider_slide2_btn1_link', '/profil/sejarah') }}" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition shadow-md text-sm">
                                    {{ \App\Models\Setting::get('slider_slide2_btn1_text', 'Profil Sekolah') }}
                                </a>
                                <a href="{{ \App\Models\Setting::get('slider_slide2_btn2_link', '/profil/visi-misi') }}" class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl border border-white/30 transition text-sm">
                                    {{ \App\Models\Setting::get('slider_slide2_btn2_text', 'Visi & Misi') }}
                                </a>
                            </div>
                        </div>
                        <div class="hidden lg:flex lg:col-span-4 justify-center">
                            <div class="w-60 h-60 rounded-full bg-white/5 border border-white/10 flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-28 h-28 text-green-400 stroke-current" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3: Fasilitas -->
            <div class="w-1/3 h-full flex-shrink-0 relative text-white flex items-center bg-cover bg-center" style="background-image: url('{{ $slide3_bg_url }}');">
                <div class="absolute inset-0 bg-slate-950/70 z-0 pointer-events-none"></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,_var(--tw-gradient-stops))] from-white/10 via-transparent to-transparent z-0 pointer-events-none"></div>
                <div class="absolute bottom-5 left-1/4 w-80 h-80 bg-cyan-500/5 rounded-full blur-3xl z-0 pointer-events-none"></div>
                
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-8 space-y-6 text-left">
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold bg-white/20 backdrop-blur border border-white/30">
                                {{ \App\Models\Setting::get('slider_slide3_title', 'Fasilitas Praktik Lengkap') }}
                            </span>
                            <h2 class="text-3xl sm:text-5xl font-extrabold leading-tight tracking-tight">
                                {{ \App\Models\Setting::get('slider_slide3_title', 'Fasilitas Praktik Lengkap') }}<br>
                                <span class="text-green-400">{{ \App\Models\Setting::get('slider_slide3_highlight', 'Standar Industri Nasional') }}</span>
                            </h2>
                            <p class="text-sm sm:text-base text-green-100 max-w-xl leading-relaxed">
                                {{ \App\Models\Setting::get('slider_slide3_desc', 'Didukung dengan Laboratorium Komputer modern, workshop TKRO terstandarisasi, perpustakaan luas, serta lapangan olahraga representatif.') }}
                            </p>
                            <div class="flex flex-wrap gap-3 pt-2">
                                <a href="{{ \App\Models\Setting::get('slider_slide3_btn1_link', '#fasilitas-section') }}" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition shadow-md text-sm">
                                    {{ \App\Models\Setting::get('slider_slide3_btn1_text', 'Lihat Fasilitas') }}
                                </a>
                                <a href="{{ \App\Models\Setting::get('slider_slide3_btn2_link', '/kelulusan') }}" class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl border border-white/30 transition text-sm">
                                    {{ \App\Models\Setting::get('slider_slide3_btn2_text', 'Info Kelulusan') }}
                                </a>
                            </div>
                        </div>
                        <div class="hidden lg:flex lg:col-span-4 justify-center">
                            <div class="w-60 h-60 rounded-full bg-white/5 border border-white/10 flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-28 h-28 text-green-400 stroke-current" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Slider Navigation Controls -->
        <button type="button" id="prev-slide" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/20 hover:bg-black/40 border border-white/25 flex items-center justify-center text-white transition focus:outline-none z-20" aria-label="Previous Slide">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        <button type="button" id="next-slide" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/20 hover:bg-black/40 border border-white/25 flex items-center justify-center text-white transition focus:outline-none z-20" aria-label="Next Slide">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>

        <!-- Slider Pagination Dots -->
        <div class="absolute bottom-6 inset-x-0 flex justify-center gap-2.5 z-20">
            <button type="button" class="slider-dot w-3 h-3 rounded-full bg-white transition-all duration-300 focus:outline-none" data-slide="0" aria-label="Go to slide 1"></button>
            <button type="button" class="slider-dot w-3 h-3 rounded-full bg-white/40 transition-all duration-300 focus:outline-none" data-slide="1" aria-label="Go to slide 2"></button>
            <button type="button" class="slider-dot w-3 h-3 rounded-full bg-white/40 transition-all duration-300 focus:outline-none" data-slide="2" aria-label="Go to slide 3"></button>
        </div>
    </div>
</section>

<!-- Custom Animations for Slider -->
<style>
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
    .animate-bounce-slow {
        animation: bounce-slow 4s ease-in-out infinite;
    }
</style>

<!-- Slider JavaScript functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const wrapper = document.getElementById('slides-wrapper');
        const dots = document.querySelectorAll('.slider-dot');
        const prevBtn = document.getElementById('prev-slide');
        const nextBtn = document.getElementById('next-slide');
        let currentSlide = 0;
        const totalSlides = 3;
        let autoplayTimer = null;

        function updateSlider() {
            wrapper.style.transform = `translateX(-${(currentSlide * 100) / totalSlides}%)`;
            dots.forEach((dot, index) => {
                if (index === currentSlide) {
                    dot.classList.remove('bg-white/40');
                    dot.classList.add('bg-white', 'w-6');
                } else {
                    dot.classList.remove('bg-white', 'w-6');
                    dot.classList.add('bg-white/40');
                }
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlider();
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateSlider();
        }

        function startAutoplay() {
            stopAutoplay();
            autoplayTimer = setInterval(nextSlide, 6000);
        }

        function stopAutoplay() {
            if (autoplayTimer) {
                clearInterval(autoplayTimer);
            }
        }

        nextBtn.addEventListener('click', () => {
            nextSlide();
            startAutoplay();
        });

        prevBtn.addEventListener('click', () => {
            prevSlide();
            startAutoplay();
        });

        dots.forEach(dot => {
            dot.addEventListener('click', () => {
                currentSlide = parseInt(dot.getAttribute('data-slide'));
                updateSlider();
                startAutoplay();
            });
        });

        // Initialize active state and start
        updateSlider();
        startAutoplay();
    });
</script>

{{-- Sambutan Kepala Sekolah --}}
<section class="py-20 bg-white" aria-label="Sambutan Kepala Sekolah">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-4 flex justify-center">
                <div class="relative w-56 h-72 rounded-2xl overflow-hidden border-4 border-green-100 shadow-xl group">
                    @if($principalPhoto)
                        <img src="{{ asset($principalPhoto) }}"
                             alt="Kepala Sekolah SMK Muda Bawean"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-green-50 to-emerald-100 text-green-300">
                            <svg class="w-16 h-16 stroke-current" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    @endif
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-green-800/90 p-4 text-center">
                        <p class="text-xs font-bold text-white">{{ \App\Models\Setting::get('principal_name', 'Kepala Sekolah') }}</p>
                        <p class="text-xs text-green-200">Kepala SMK Muhammadiyah 4</p>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-8 space-y-5">
                <div>
                    <span class="text-xs font-bold text-green-600 uppercase tracking-wider">Sambutan</span>
                    <h2 class="text-3xl font-extrabold text-gray-900 mt-1">Kata Kepala Sekolah</h2>
                </div>
                <div class="prose prose-green max-w-none text-gray-600 leading-relaxed">
                    {!! clean($principalGreeting) !!}
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Fasilitas highlight --}}
<section id="fasilitas-section" class="py-16 bg-green-50 scroll-mt-20" aria-label="Fasilitas Sekolah">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="text-xs font-bold text-green-600 uppercase tracking-wider">Fasilitas</span>
            <h2 class="text-2xl font-extrabold text-gray-900 mt-1">Sarana &amp; Prasarana Unggulan</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6">
            <!-- Lab Komputer -->
            <div class="bg-white rounded-2xl p-6 text-center shadow-sm border border-gray-100 hover:border-green-300 hover:shadow-md transition-all duration-300 group">
                <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <p class="text-sm font-bold text-gray-800 leading-tight">Lab Komputer</p>
            </div>

            <!-- Perpustakaan -->
            <div class="bg-white rounded-2xl p-6 text-center shadow-sm border border-gray-100 hover:border-green-300 hover:shadow-md transition-all duration-300 group">
                <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <p class="text-sm font-bold text-gray-800 leading-tight">Perpustakaan</p>
            </div>

            <!-- Lab TKRO -->
            <div class="bg-white rounded-2xl p-6 text-center shadow-sm border border-gray-100 hover:border-green-300 hover:shadow-md transition-all duration-300 group">
                <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <p class="text-sm font-bold text-gray-800 leading-tight">Lab TKRO</p>
            </div>

            <!-- Lapangan Olahraga -->
            <div class="bg-white rounded-2xl p-6 text-center shadow-sm border border-gray-100 hover:border-green-300 hover:shadow-md transition-all duration-300 group">
                <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.5A1.5 1.5 0 0018 10.5V9a2 2 0 00-2-2h-1a2 2 0 01-2-2v-.935M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-sm font-bold text-gray-800 leading-tight">Lapangan Olahraga</p>
            </div>

            <!-- Kantin Sekolah -->
            <div class="bg-white rounded-2xl p-6 text-center shadow-sm border border-gray-100 hover:border-green-300 hover:shadow-md transition-all duration-300 group">
                <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l-.707.707M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-sm font-bold text-gray-800 leading-tight">Kantin Sekolah</p>
            </div>

            <!-- Ruang Kelas -->
            <div class="bg-white rounded-2xl p-6 text-center shadow-sm border border-gray-100 hover:border-green-300 hover:shadow-md transition-all duration-300 group">
                <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <p class="text-sm font-bold text-gray-800 leading-tight">Ruang Kelas</p>
            </div>
        </div>
    </div>
</section>

{{-- Berita Terbaru --}}
<section class="py-20 bg-white" aria-label="Berita Terbaru">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        <div class="flex items-end justify-between">
            <div>
                <span class="text-xs font-bold text-green-600 uppercase tracking-wider">Terkini</span>
                <h2 class="text-2xl font-extrabold text-gray-900 mt-1">Kabar &amp; Berita Sekolah</h2>
            </div>
            <a href="{{ route('berita.index') }}" class="hidden sm:inline-flex items-center gap-1 text-sm font-semibold text-green-600 hover:text-green-800 transition">
                Lihat Semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if($posts->isEmpty())
            <div class="p-12 text-center rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 text-gray-400">
                <p>Belum ada berita yang dipublikasikan.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($posts as $post)
                    <x-news-card :post="$post" />
                @endforeach
            </div>
        @endif

        <div class="sm:hidden text-center">
            <a href="{{ route('berita.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-green-600 hover:text-green-800">
                Lihat Semua Berita <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- Pengumuman --}}
<section class="py-20 bg-gray-50 border-t border-gray-100" aria-label="Pengumuman Terbaru">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        <div class="flex items-end justify-between">
            <div>
                <span class="text-xs font-bold text-green-600 uppercase tracking-wider">Resmi</span>
                <h2 class="text-2xl font-extrabold text-gray-900 mt-1">Pengumuman Sekolah</h2>
            </div>
            <a href="{{ route('pengumuman.index') }}" class="hidden sm:inline-flex items-center gap-1 text-sm font-semibold text-green-600 hover:text-green-800 transition">
                Lihat Semua <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if($announcements->isEmpty())
            <div class="p-12 text-center rounded-2xl border-2 border-dashed border-gray-200 bg-white text-gray-400">
                <p>Belum ada pengumuman yang dipublikasikan.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($announcements as $ann)
                    <x-announcement-item :announcement="$ann" />
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- Jam Operasional --}}
<section class="py-16 bg-green-700 text-white" aria-label="Jam Operasional">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-8">
        <div>
            <span class="text-xs font-bold text-green-200 uppercase tracking-wider">Pelayanan</span>
            <h2 class="text-2xl font-extrabold mt-1">Jam Operasional Sekolah</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-lg mx-auto">
            <div class="bg-white/15 rounded-2xl p-5 border border-white/20">
                <p class="font-bold text-green-300">Senin – Kamis</p>
                <p class="text-2xl font-extrabold mt-1">07:00 – 14:00</p>
            </div>
            <div class="bg-white/15 rounded-2xl p-5 border border-white/20">
                <p class="font-bold text-green-300">Jum'at</p>
                <p class="text-2xl font-extrabold mt-1">07:00 – 10:00</p>
            </div>
        </div>
        <p class="text-sm text-green-200">Pelayanan administrasi dibuka setiap hari Senin–Jum'at.</p>
    </div>
</section>

@endsection
