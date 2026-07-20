@extends('layouts.admin')
@section('breadcrumbs')<nav class="text-sm"><ol class="flex items-center gap-1.5 text-slate-400"><li><a href="{{ route('admin.graduations.index') }}" class="hover:text-white">Kelulusan</a></li><li>/</li><li class="text-white font-semibold">Import CSV</li></ol></nav>@endsection
@section('content')
<div class="max-w-xl space-y-6">
    <h1 class="text-2xl font-bold text-white">Import Data Kelulusan (CSV)</h1>
    <form action="{{ route('admin.graduations.import') }}" method="POST" enctype="multipart/form-data" class="bg-slate-900 border border-slate-800 rounded-2xl p-8 space-y-5">
        @csrf
        @if($errors->any())<div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm"><ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
        <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Tahun Ajaran (misal: 2024/2025) *</label><input type="text" name="academic_year" required value="{{ old('academic_year') }}" placeholder="2024/2025" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none"></div>
        <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">File CSV *</label><input type="file" name="csv_file" required accept=".csv,.txt" class="w-full text-slate-400 text-sm"><p class="text-xs text-slate-500 mt-1">Kolom wajib: nama_siswa, nomor_peserta, program_keahlian, status_kelulusan</p></div>
        <div class="flex gap-3"><button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition">Import</button><a href="{{ route('admin.graduations.index') }}" class="px-6 py-3 bg-slate-700 text-white font-bold rounded-xl transition">Batal</a></div>
    </form>
</div>
@endsection
