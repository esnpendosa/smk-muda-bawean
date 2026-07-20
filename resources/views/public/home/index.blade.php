@extends('layouts.public')

@section('content')
<!-- Hero Section -->
<section class="relative bg-slate-950 py-24 sm:py-32 overflow-hidden" aria-label="Hero">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-primary/10 via-transparent to-transparent"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center space-y-8 max-w-3xl mx-auto">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-primary/10 text-primary border border-primary/20">
                <span class="w-1.5 h-1.5 rounded-full bg-primary animate-ping"></span>
                Pendaftaran Siswa Baru (PPDB) Dibuka!
            </span>
            <h1 class="text-4xl sm:text-6xl font-extrabold text-white tracking-tight leading-none">
                Membangun Generasi <br>
                <span class="bg-gradient-to-r from-primary via-accent to-white bg-clip-text text-transparent">Cerdas & Berkarakter</span>
            </h1>
            <p class="text-lg text-slate-400 leading-relaxed">
                Selamat Datang di Portal Resmi SMK Muda Bawean. Lembaga pendidikan vokasi terbaik berkomitmen mempersiapkan lulusan profesional, tangguh, dan berdaya saing global.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('ppdb.index') }}" class="px-8 py-4 bg-primary hover:bg-secondary text-slate-950 font-bold rounded-xl transition duration-200 shadow-lg shadow-primary/20 text-center w-full sm:w-auto">
                    Daftar Sekarang
                </a>
                <a href="{{ route('profil.sejarah') }}" class="px-8 py-4 bg-slate-900 hover:bg-slate-850 text-white font-semibold rounded-xl border border-slate-800 transition duration-200 text-center w-full sm:w-auto">
                    Profil Sekolah
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Sambutan Kepala Sekolah -->
<section class="py-20 border-y border-slate-900 bg-slate-900/30" aria-label="Sambutan Kepala Sekolah">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-4 flex justify-center">
                <div class="relative w-64 h-80 rounded-2xl overflow-hidden border border-slate-800 bg-slate-950 shadow-2xl group">
                    @if($principalPhoto)
                        <img src="{{ asset('storage/' . $principalPhoto) }}" alt="Kepala Sekolah SMK Muda Bawean" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <!-- Placeholder -->
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-900 to-slate-950 text-slate-700">
                            <svg class="w-16 h-16 stroke-current" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950 p-4 text-center">
                        <p class="text-sm font-bold text-white">Kepala Sekolah</p>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-8 space-y-6">
                <h2 class="text-3xl font-extrabold text-white">Sambutan Kepala Sekolah</h2>
                <div class="text-slate-300 leading-relaxed space-y-4">
                    {!! clean($principalGreeting) !!}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Berita Terbaru -->
<section class="py-24" aria-label="Berita Terbaru">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="flex items-end justify-between">
            <div class="space-y-2">
                <h2 class="text-3xl font-extrabold text-white">Kabar & Berita Terbaru</h2>
                <p class="text-sm text-slate-400">Ikuti perkembangan terbaru dan kegiatan akademik sekolah.</p>
            </div>
            <a href="{{ route('berita.index') }}" class="hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:text-white transition duration-150">
                Lihat Semua Berita
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        @if($posts->isEmpty())
            <div class="p-12 text-center rounded-2xl border border-dashed border-slate-800 bg-slate-900/20 text-slate-400">
                <p class="text-base">Belum ada berita yang dipublikasikan.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    <x-news-card :post="$post" />
                @endforeach
            </div>
        @endif

        <div class="sm:hidden text-center pt-4">
            <a href="{{ route('berita.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:text-white transition duration-150">
                Lihat Semua Berita
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
    </div>
</section>

<!-- Pengumuman Terbaru -->
<section class="py-24 bg-slate-900/10 border-t border-slate-900" aria-label="Pengumuman Terbaru">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="flex items-end justify-between">
            <div class="space-y-2">
                <h2 class="text-3xl font-extrabold text-white">Pengumuman Resmi</h2>
                <p class="text-sm text-slate-400">Informasi resmi mengenai kegiatan, agenda, dan administrasi sekolah.</p>
            </div>
            <a href="{{ route('pengumuman.index') }}" class="hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:text-white transition duration-150">
                Lihat Semua Pengumuman
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        @if($announcements->isEmpty())
            <div class="p-12 text-center rounded-2xl border border-dashed border-slate-800 bg-slate-900/20 text-slate-400">
                <p class="text-base">Belum ada pengumuman yang dipublikasikan.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($announcements as $ann)
                    <x-announcement-item :announcement="$ann" />
                @endforeach
            </div>
        @endif

        <div class="sm:hidden text-center pt-4">
            <a href="{{ route('pengumuman.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:text-white transition duration-150">
                Lihat Semua Pengumuman
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
    </div>
</section>
@endsection
