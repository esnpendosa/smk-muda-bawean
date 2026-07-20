@extends('layouts.admin')
@section('breadcrumbs')<nav class="text-sm"><ol class="flex items-center gap-1.5 text-slate-400"><li><a href="{{ route('admin.alumni.index') }}" class="hover:text-white">Alumni</a></li><li>/</li><li class="text-white font-semibold">{{ $alumni->full_name }}</li></ol></nav>@endsection
@section('content')
<div class="max-w-2xl space-y-6">
    <h1 class="text-2xl font-bold text-white">Detail Alumni</h1>
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 space-y-4">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><p class="text-xs text-slate-400">Nama Lengkap</p><p class="font-bold text-white">{{ $alumni->full_name }}</p></div>
            <div><p class="text-xs text-slate-400">Tahun Lulus</p><p class="font-bold text-white">{{ $alumni->graduation_year }}</p></div>
            <div><p class="text-xs text-slate-400">Email</p><p class="text-white">{{ $alumni->email }}</p></div>
            <div><p class="text-xs text-slate-400">Telepon</p><p class="text-white">{{ $alumni->phone ?? '-' }}</p></div>
            <div class="col-span-2"><p class="text-xs text-slate-400">Alamat</p><p class="text-white">{{ $alumni->address ?? '-' }}</p></div>
        </div>
        @if($alumni->tracerStudies->isNotEmpty())
        <div class="border-t border-slate-800 pt-4">
            <p class="text-xs text-slate-400 mb-2">Tracer Study</p>
            @foreach($alumni->tracerStudies as $ts)
            <div class="text-sm text-white">Pendidikan: {{ $ts->education_status }} | Pekerjaan: {{ $ts->employment_status }}</div>
            @endforeach
        </div>
        @endif
        <div class="pt-4"><a href="{{ route('admin.alumni.index') }}" class="px-4 py-2 bg-slate-700 text-white text-sm font-bold rounded-lg">← Kembali</a></div>
    </div>
</div>
@endsection
