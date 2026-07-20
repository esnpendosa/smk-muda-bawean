@extends('layouts.admin')
@section('breadcrumbs')<nav class="text-sm"><ol class="flex items-center gap-1.5 text-slate-400"><li><span class="text-white font-semibold">Pengaturan</span></li></ol></nav>@endsection
@section('content')
<div class="max-w-2xl space-y-8">
    <h1 class="text-2xl font-bold text-white">Pengaturan Sekolah</h1>
    <!-- Sub nav -->
    <div class="flex flex-wrap gap-2 border-b border-slate-800 pb-4">
        <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg">Info Sekolah</a>
        <a href="{{ route('admin.settings.seo') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-bold rounded-lg transition">SEO</a>
        <a href="{{ route('admin.settings.theme') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-bold rounded-lg transition">Tema Warna</a>
        <a href="{{ route('admin.settings.slider') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-bold rounded-lg transition">Hero Slider</a>
    </div>
    <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-slate-900 border border-slate-800 rounded-2xl p-8 space-y-5">
        @csrf
        @if($errors->any())<div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm"><ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
        <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Nama Sekolah</label><input type="text" name="school_name" value="{{ old('school_name', $settings['school_name'] ?? '') }}" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none"></div>
        <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Alamat</label><textarea name="school_address" rows="3" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none">{{ old('school_address', $settings['school_address'] ?? '') }}</textarea></div>
        <div class="grid grid-cols-2 gap-4">
            <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Telepon</label><input type="text" name="school_phone" value="{{ old('school_phone', $settings['school_phone'] ?? '') }}" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none"></div>
            <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Email</label><input type="email" name="school_email" value="{{ old('school_email', $settings['school_email'] ?? '') }}" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none"></div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Latitude</label><input type="text" name="school_geo_lat" value="{{ old('school_geo_lat', $settings['school_geo_lat'] ?? '') }}" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none"></div>
            <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Longitude</label><input type="text" name="school_geo_lng" value="{{ old('school_geo_lng', $settings['school_geo_lng'] ?? '') }}" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none"></div>
        </div>
        <div><button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition">Simpan Pengaturan</button></div>
    </form>
</div>
@endsection
