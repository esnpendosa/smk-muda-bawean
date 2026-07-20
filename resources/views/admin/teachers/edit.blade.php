@extends('layouts.admin')
@section('breadcrumbs')<nav class="text-sm"><ol class="flex items-center gap-1.5 text-slate-400"><li><a href="{{ route('admin.teachers.index') }}" class="hover:text-white">Pendidik</a></li><li>/</li><li class="text-white font-semibold">Edit: {{ $teacher->name }}</li></ol></nav>@endsection
@section('content')
<div class="max-w-xl space-y-6">
    <h1 class="text-2xl font-bold text-white">Edit Pendidik</h1>
    <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST" enctype="multipart/form-data" class="bg-slate-900 border border-slate-800 rounded-2xl p-8 space-y-5">
        @csrf @method('PUT')
        @if($errors->any())<div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm"><ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
        <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Nama *</label><input type="text" name="name" required value="{{ old('name', $teacher->name) }}" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none"></div>
        <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Jabatan *</label><input type="text" name="position" required value="{{ old('position', $teacher->position) }}" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none"></div>
        <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Foto Baru (opsional)</label><input type="file" name="photo" accept=".jpg,.jpeg,.png,.webp" class="w-full text-slate-400 text-sm">@if($teacher->photo)<p class="text-xs text-slate-500 mt-1">Foto saat ini: {{ basename($teacher->photo) }}</p>@endif</div>
        <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Urutan</label><input type="number" name="order" value="{{ old('order', $teacher->order) }}" min="0" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none"></div>
        <div class="flex gap-3"><button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition">Simpan Perubahan</button><a href="{{ route('admin.teachers.index') }}" class="px-6 py-3 bg-slate-700 text-white font-bold rounded-xl transition">Batal</a></div>
    </form>
</div>
@endsection
