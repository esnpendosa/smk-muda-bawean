@extends('layouts.admin')
@section('breadcrumbs')<nav class="text-sm"><ol class="flex items-center gap-1.5 text-slate-400"><li><a href="{{ route('admin.ppdb.index') }}" class="hover:text-white">PPDB</a></li><li>/</li><li class="text-white font-semibold">Detail Pendaftar</li></ol></nav>@endsection
@section('content')
<div class="max-w-2xl space-y-6">
    <h1 class="text-2xl font-bold text-white">Detail Pendaftar PPDB</h1>
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 space-y-5">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><p class="text-xs text-slate-400">No. Registrasi</p><p class="font-mono font-bold text-white">{{ $registration->registration_number }}</p></div>
            <div><p class="text-xs text-slate-400">Status</p><p class="font-bold"><span class="px-2 py-1 rounded-full text-xs {{ $registration->status==='diterima'?'bg-green-500/10 text-green-400':($registration->status==='ditolak'?'bg-red-500/10 text-red-400':'bg-amber-500/10 text-amber-400') }}">{{ ucfirst($registration->status) }}</span></p></div>
            <div><p class="text-xs text-slate-400">Nama Lengkap</p><p class="font-bold text-white">{{ $registration->full_name }}</p></div>
            <div><p class="text-xs text-slate-400">Asal Sekolah</p><p class="text-white">{{ $registration->previous_school }}</p></div>
            <div><p class="text-xs text-slate-400">Tempat / Tanggal Lahir</p><p class="text-white">{{ $registration->birth_place }}, {{ $registration->birth_date->format('d M Y') }}</p></div>
            <div><p class="text-xs text-slate-400">Nama Orang Tua</p><p class="text-white">{{ $registration->parent_name }}</p></div>
            <div><p class="text-xs text-slate-400">Telepon</p><p class="text-white">{{ $registration->phone }}</p></div>
            <div><p class="text-xs text-slate-400">Tanggal Mendaftar</p><p class="text-white">{{ $registration->created_at->format('d M Y H:i') }}</p></div>
        </div>
        <div class="border-t border-slate-800 pt-5">
            <form action="{{ route('admin.ppdb.update', $registration) }}" method="POST" class="flex items-end gap-4">
                @csrf @method('PUT')
                <div class="flex-1"><label class="block text-xs font-semibold text-slate-400 mb-1.5">Ubah Status</label><select name="status" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none"><option value="menunggu" {{ $registration->status==='menunggu'?'selected':'' }}>Menunggu</option><option value="diterima" {{ $registration->status==='diterima'?'selected':'' }}>Diterima</option><option value="ditolak" {{ $registration->status==='ditolak'?'selected':'' }}>Ditolak</option></select></div>
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition text-sm">Simpan Status</button>
            </form>
        </div>
        <div><a href="{{ route('admin.ppdb.index') }}" class="text-sm text-slate-400 hover:text-white">← Kembali ke Daftar</a></div>
    </div>
</div>
@endsection
