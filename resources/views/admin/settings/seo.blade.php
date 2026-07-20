@extends('layouts.admin')
@section('breadcrumbs')<nav class="text-sm"><ol class="flex items-center gap-1.5 text-slate-400"><li><a href="{{ route('admin.settings.index') }}" class="hover:text-white">Pengaturan</a></li><li>/</li><li class="text-white font-semibold">SEO</li></ol></nav>@endsection
@section('content')
<div class="max-w-2xl space-y-8">
    <h1 class="text-2xl font-bold text-white">Pengaturan SEO</h1>
    <div class="flex gap-3 border-b border-slate-800 pb-4">
        <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-bold rounded-lg transition">Info Sekolah</a>
        <a href="{{ route('admin.settings.seo') }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg">SEO</a>
        <a href="{{ route('admin.settings.theme') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-bold rounded-lg transition">Tema Warna</a>
    </div>
    <form action="{{ route('admin.settings.seo.update') }}" method="POST" class="bg-slate-900 border border-slate-800 rounded-2xl p-8 space-y-5">
        @csrf
        <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Meta Description Default</label><textarea name="meta_description" rows="3" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none">{{ old('meta_description', $settings['meta_description'] ?? '') }}</textarea></div>
        <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">robots.txt</label><textarea name="robots_txt" rows="6" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white font-mono text-xs outline-none">{{ old('robots_txt', $settings['robots_txt'] ?? "User-agent: *\nAllow: /\nDisallow: /admin/") }}</textarea></div>
        <div><button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition">Simpan SEO</button></div>
    </form>
</div>
@endsection
