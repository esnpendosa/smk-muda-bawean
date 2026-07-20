@extends('layouts.admin')
@section('breadcrumbs')<nav class="text-sm"><ol class="flex items-center gap-1.5 text-slate-400"><li><a href="{{ route('admin.pages.index') }}" class="hover:text-white">Halaman Statis</a></li><li>/</li><li class="text-white font-semibold">Edit: {{ $page->title }}</li></ol></nav>@endsection
@section('content')
<div class="max-w-3xl space-y-6">
    <h1 class="text-2xl font-bold text-white">Edit Halaman Statis: {{ $page->title }}</h1>
    <form action="{{ route('admin.pages.update', $page) }}" method="POST" class="bg-slate-900 border border-slate-800 rounded-2xl p-8 space-y-5">
        @csrf @method('PUT')
        @if($errors->any())<div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm"><ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
        <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Judul *</label><input type="text" name="title" required value="{{ old('title', $page->title) }}" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none"></div>
        <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Konten *</label><textarea name="content" rows="15" required class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none font-mono text-xs leading-relaxed">{{ old('content', $page->content) }}</textarea></div>
        <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Meta Title (max 60)</label><input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" maxlength="60" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none"></div>
        <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Meta Description (max 160)</label><textarea name="meta_description" rows="2" maxlength="160" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none">{{ old('meta_description', $page->meta_description) }}</textarea></div>
        <div class="flex gap-3"><button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition">Simpan Perubahan</button><a href="{{ route('admin.pages.index') }}" class="px-6 py-3 bg-slate-700 text-white font-bold rounded-xl transition">Batal</a></div>
    </form>
</div>
@endsection
