@extends('layouts.admin')
@section('breadcrumbs')<nav class="text-sm"><ol class="flex items-center gap-1.5 text-slate-400"><li><a href="{{ route('admin.alumni.index') }}" class="hover:text-white">Alumni</a></li><li>/</li><li class="text-white font-semibold">Tracer Study</li></ol></nav>@endsection
@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-white">Statistik Tracer Study</h1>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6"><p class="text-4xl font-black text-white">{{ $total }}</p><p class="text-sm text-slate-400 mt-1">Total Responden</p></div>
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6"><p class="text-4xl font-black text-white">{{ $pctKuliah }}%</p><p class="text-sm text-slate-400 mt-1">Melanjutkan Pendidikan</p></div>
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6"><p class="text-4xl font-black text-white">{{ $pctBekerja }}%</p><p class="text-sm text-slate-400 mt-1">Bekerja / Wirausaha</p></div>
    </div>
</div>
@endsection
