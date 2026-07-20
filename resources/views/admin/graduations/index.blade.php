@extends('layouts.admin')
@section('breadcrumbs')<nav class="text-sm"><ol class="flex items-center gap-1.5 text-slate-400"><li><span class="text-white font-semibold">Kelulusan</span></li></ol></nav>@endsection
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white">Manajemen Kelulusan</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.graduations.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-lg transition">Import CSV</a>
            <a href="{{ route('admin.graduations.export') }}?academic_year={{ request('year', date('Y')) }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-bold rounded-lg transition">Export CSV</a>
        </div>
    </div>
    <form method="GET" class="flex gap-3">
        <select name="year" class="px-3 py-2 bg-slate-900 border border-slate-700 text-slate-300 rounded-lg text-sm">
            <option value="">Semua Tahun</option>
            @foreach($uniqueYears as $yr)<option value="{{ $yr }}" {{ request('year')==$yr?'selected':'' }}>{{ $yr }}</option>@endforeach
        </select>
        <button class="px-4 py-2 bg-slate-700 text-sm text-white rounded-lg">Filter</button>
    </form>
    @if(session('import_errors'))
    <div class="p-4 rounded-xl bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 text-sm">
        <p class="font-bold">Berikut baris yang gagal diimpor:</p>
        <ul class="list-disc pl-4 mt-1 space-y-1">@foreach(session('import_errors') as $err)<li>{{ $err }}</li>@endforeach</ul>
    </div>
    @endif
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
        <table class="w-full text-sm text-slate-300"><thead class="bg-slate-950 text-slate-400 text-xs uppercase"><tr><th class="px-6 py-4 text-left">Nama Siswa</th><th class="px-6 py-4 text-left">Nomor Peserta</th><th class="px-6 py-4 text-left">Program</th><th class="px-6 py-4 text-left">Tahun</th><th class="px-6 py-4 text-left">Status</th></tr></thead>
            <tbody class="divide-y divide-slate-800">
                @forelse($graduations as $g)
                <tr class="hover:bg-slate-800/30">
                    <td class="px-6 py-4 font-semibold text-white">{{ $g->student_name }}</td>
                    <td class="px-6 py-4 font-mono">{{ $g->exam_number }}</td>
                    <td class="px-6 py-4">{{ $g->program_keahlian }}</td>
                    <td class="px-6 py-4">{{ $g->academic_year }}</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs {{ $g->status_kelulusan==='LULUS' ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400' }}">{{ $g->status_kelulusan }}</span></td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-slate-500">Belum ada data kelulusan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>{{ $graduations->links() }}</div>
</div>
@endsection
