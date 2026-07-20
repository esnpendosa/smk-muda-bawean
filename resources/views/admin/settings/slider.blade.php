@extends('layouts.admin')
@section('breadcrumbs')
<nav class="text-sm">
    <ol class="flex items-center gap-1.5 text-slate-400">
        <li><a href="{{ route('admin.settings.index') }}" class="hover:text-white">Pengaturan</a></li>
        <li>/</li>
        <li class="text-white font-semibold">Hero Slider</li>
    </ol>
</nav>
@endsection
@section('content')
<div class="max-w-4xl space-y-8">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white">Pengaturan Hero Slider</h1>
    </div>
    
    <!-- Sub Nav -->
    <div class="flex flex-wrap gap-2 border-b border-slate-800 pb-4">
        <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-bold rounded-lg transition">Info Sekolah</a>
        <a href="{{ route('admin.settings.seo') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-bold rounded-lg transition">SEO</a>
        <a href="{{ route('admin.settings.theme') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-bold rounded-lg transition">Tema Warna</a>
        <a href="{{ route('admin.settings.slider') }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg">Hero Slider</a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.slider.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Slide 1 Card -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-2.5">
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-yellow-400"></span>
                        Slide 1
                    </h3>
                </div>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 mb-1">Gambar Background</label>
                        @php
                            $bg1 = $settings['slider_slide1_bg'] ?? '';
                            $bg1_url = $bg1 ? (str_starts_with($bg1, 'images/') ? asset($bg1) : asset('storage/' . $bg1)) : asset('images/artikel-ujian-digital.png');
                        @endphp
                        <div class="mb-2 relative rounded-lg overflow-hidden border border-slate-800 bg-slate-950 aspect-video">
                            <img src="{{ $bg1_url }}" class="w-full h-full object-cover" alt="Slide 1 Preview">
                        </div>
                        <input type="file" name="slider_slide1_bg" class="w-full text-xs text-slate-400 bg-slate-950 border border-slate-700 rounded-lg file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-[10px] file:font-semibold file:bg-blue-600 file:text-white file:hover:bg-blue-700 cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 mb-1">Judul Atas (Kecil)</label>
                        <input type="text" name="slider_slide1_title" value="{{ old('slider_slide1_title', $settings['slider_slide1_title'] ?? 'Sistem Penerimaan Murid Baru') }}" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-white text-xs outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 mb-1">Judul Utama (Kuning)</label>
                        <input type="text" name="slider_slide1_highlight" value="{{ old('slider_slide1_highlight', $settings['slider_slide1_highlight'] ?? 'Tahun Ajaran 2026-2027') }}" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-white text-xs outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 mb-1">Deskripsi</label>
                        <textarea name="slider_slide1_desc" rows="3" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-white text-xs outline-none focus:border-blue-500">{{ old('slider_slide1_desc', $settings['slider_slide1_desc'] ?? 'Mari bergabung dengan SMK Muhammadiyah 4 Sangkapura Bawean. Dapatkan promo gratis seragam olahraga dan gratis SPP 2 bulan untuk pendaftar awal.') }}</textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 mb-1">Tombol 1 Teks</label>
                            <input type="text" name="slider_slide1_btn1_text" value="{{ old('slider_slide1_btn1_text', $settings['slider_slide1_btn1_text'] ?? 'Daftar Siswa Baru') }}" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-white text-xs outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 mb-1">Tombol 1 Link</label>
                            <input type="text" name="slider_slide1_btn1_link" value="{{ old('slider_slide1_btn1_link', $settings['slider_slide1_btn1_link'] ?? '/ppdb') }}" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-white text-xs outline-none">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 mb-1">Tombol 2 Teks</label>
                            <input type="text" name="slider_slide1_btn2_text" value="{{ old('slider_slide1_btn2_text', $settings['slider_slide1_btn2_text'] ?? 'Hubungi Panitia') }}" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-white text-xs outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 mb-1">Tombol 2 Link</label>
                            <input type="text" name="slider_slide1_btn2_link" value="{{ old('slider_slide1_btn2_link', $settings['slider_slide1_btn2_link'] ?? 'https://wa.me/6285333245454') }}" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-white text-xs outline-none">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 Card -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-2.5">
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                        Slide 2
                    </h3>
                </div>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 mb-1">Gambar Background</label>
                        @php
                            $bg2 = $settings['slider_slide2_bg'] ?? '';
                            $bg2_url = $bg2 ? (str_starts_with($bg2, 'images/') ? asset($bg2) : asset('storage/' . $bg2)) : asset('images/artikel-fortasi.png');
                        @endphp
                        <div class="mb-2 relative rounded-lg overflow-hidden border border-slate-805 bg-slate-950 aspect-video">
                            <img src="{{ $bg2_url }}" class="w-full h-full object-cover" alt="Slide 2 Preview">
                        </div>
                        <input type="file" name="slider_slide2_bg" class="w-full text-xs text-slate-400 bg-slate-950 border border-slate-700 rounded-lg file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-[10px] file:font-semibold file:bg-blue-600 file:text-white file:hover:bg-blue-700 cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 mb-1">Judul Atas (Kecil)</label>
                        <input type="text" name="slider_slide2_title" value="{{ old('slider_slide2_title', $settings['slider_slide2_title'] ?? 'Membentuk Generasi Unggul') }}" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-white text-xs outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 mb-1">Judul Utama (Kuning)</label>
                        <input type="text" name="slider_slide2_highlight" value="{{ old('slider_slide2_highlight', $settings['slider_slide2_highlight'] ?? 'Siap Kerja & Berakhlak Mulia') }}" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-white text-xs outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 mb-1">Deskripsi</label>
                        <textarea name="slider_slide2_desc" rows="3" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-white text-xs outline-none focus:border-blue-500">{{ old('slider_slide2_desc', $settings['slider_slide2_desc'] ?? 'Kami membekali para siswa dengan perpaduan ilmu pengetahuan praktis dan iman yang kuat sebagai kunci utama meraih masa depan yang gemilang.') }}</textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 mb-1">Tombol 1 Teks</label>
                            <input type="text" name="slider_slide2_btn1_text" value="{{ old('slider_slide2_btn1_text', $settings['slider_slide2_btn1_text'] ?? 'Profil Sekolah') }}" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-white text-xs outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 mb-1">Tombol 1 Link</label>
                            <input type="text" name="slider_slide2_btn1_link" value="{{ old('slider_slide2_btn1_link', $settings['slider_slide2_btn1_link'] ?? '/profil/sejarah') }}" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-white text-xs outline-none">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 mb-1">Tombol 2 Teks</label>
                            <input type="text" name="slider_slide2_btn2_text" value="{{ old('slider_slide2_btn2_text', $settings['slider_slide2_btn2_text'] ?? 'Visi & Misi') }}" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-white text-xs outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 mb-1">Tombol 2 Link</label>
                            <input type="text" name="slider_slide2_btn2_link" value="{{ old('slider_slide2_btn2_link', $settings['slider_slide2_btn2_link'] ?? '/profil/visi-misi') }}" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-white text-xs outline-none">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3 Card -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-2.5">
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-cyan-400"></span>
                        Slide 3
                    </h3>
                </div>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 mb-1">Gambar Background</label>
                        @php
                            $bg3 = $settings['slider_slide3_bg'] ?? '';
                            $bg3_url = $bg3 ? (str_starts_with($bg3, 'images/') ? asset($bg3) : asset('storage/' . $bg3)) : asset('images/artikel-parenting-ai.png');
                        @endphp
                        <div class="mb-2 relative rounded-lg overflow-hidden border border-slate-805 bg-slate-950 aspect-video">
                            <img src="{{ $bg3_url }}" class="w-full h-full object-cover" alt="Slide 3 Preview">
                        </div>
                        <input type="file" name="slider_slide3_bg" class="w-full text-xs text-slate-400 bg-slate-950 border border-slate-700 rounded-lg file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-[10px] file:font-semibold file:bg-blue-600 file:text-white file:hover:bg-blue-700 cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 mb-1">Judul Atas (Kecil)</label>
                        <input type="text" name="slider_slide3_title" value="{{ old('slider_slide3_title', $settings['slider_slide3_title'] ?? 'Fasilitas Praktik Lengkap') }}" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-white text-xs outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 mb-1">Judul Utama (Kuning)</label>
                        <input type="text" name="slider_slide3_highlight" value="{{ old('slider_slide3_highlight', $settings['slider_slide3_highlight'] ?? 'Standar Industri Nasional') }}" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-white text-xs outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 mb-1">Deskripsi</label>
                        <textarea name="slider_slide3_desc" rows="3" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-white text-xs outline-none focus:border-blue-500">{{ old('slider_slide3_desc', $settings['slider_slide3_desc'] ?? 'Didukung dengan Laboratorium Komputer modern, workshop TKRO terstandarisasi, perpustakaan luas, serta lapangan olahraga representatif.') }}</textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 mb-1">Tombol 1 Teks</label>
                            <input type="text" name="slider_slide3_btn1_text" value="{{ old('slider_slide3_btn1_text', $settings['slider_slide3_btn1_text'] ?? 'Lihat Fasilitas') }}" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-white text-xs outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 mb-1">Tombol 1 Link</label>
                            <input type="text" name="slider_slide3_btn1_link" value="{{ old('slider_slide3_btn1_link', $settings['slider_slide3_btn1_link'] ?? '#fasilitas-section') }}" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-white text-xs outline-none">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 mb-1">Tombol 2 Teks</label>
                            <input type="text" name="slider_slide3_btn2_text" value="{{ old('slider_slide3_btn2_text', $settings['slider_slide3_btn2_text'] ?? 'Info Kelulusan') }}" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-white text-xs outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 mb-1">Tombol 2 Link</label>
                            <input type="text" name="slider_slide3_btn2_link" value="{{ old('slider_slide3_btn2_link', $settings['slider_slide3_btn2_link'] ?? '/kelulusan') }}" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-white text-xs outline-none">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="px-8 py-3.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition shadow-lg text-sm">
                Simpan Hero Slider
            </button>
        </div>
    </form>
</div>
@endsection
