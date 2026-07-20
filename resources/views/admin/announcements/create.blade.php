@extends('layouts.admin')
@section('breadcrumbs')
    <nav class="text-sm"><ol class="flex items-center gap-1.5 text-slate-400"><li><a href="{{ route('admin.announcements.index') }}" class="hover:text-white">Pengumuman</a></li><li>/</li><li class="text-white font-semibold">Tambah</li></ol></nav>
@endsection
@section('content')
<div class="max-w-3xl space-y-6">
    <h1 class="text-2xl font-bold text-white">Tambah Pengumuman</h1>
    <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data" class="bg-slate-900 border border-slate-800 rounded-2xl p-8 space-y-5">
        @csrf
        @if($errors->any())<div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm"><ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
        <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Judul *</label><input type="text" name="title" required value="{{ old('title') }}" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 focus:border-blue-500 rounded-xl text-white text-sm outline-none transition"></div>
        <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Konten</label><textarea name="content" rows="8" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none">{{ old('content') }}</textarea></div>
        <div class="grid grid-cols-2 gap-4">
            <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Status *</label><select name="status" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none"><option value="draft">Draft</option><option value="published">Published</option></select></div>
            <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Tanggal Publikasi</label><input type="datetime-local" name="published_at" value="{{ old('published_at') }}" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none"></div>
        </div>
        <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Lampiran PDF (max 2MB)</label><input type="file" name="attachment" accept=".pdf" class="w-full text-slate-400 text-sm"></div>
        <div class="flex gap-3 pt-2"><button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition">Simpan</button><a href="{{ route('admin.announcements.index') }}" class="px-6 py-3 bg-slate-700 text-white font-bold rounded-xl transition">Batal</a></div>
    </form>
</div>
@endsection
