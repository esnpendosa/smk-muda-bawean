@extends('layouts.admin')
@section('breadcrumbs')<nav class="text-sm"><ol class="flex items-center gap-1.5 text-slate-400"><li><span class="text-white font-semibold">PPDB</span></li></ol></nav>@endsection
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white">Manajemen PPDB</h1>
        <a href="{{ route('admin.ppdb.export') }}{{ request('status') ? '?status='.request('status') : '' }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-bold rounded-lg transition">Export CSV</a>
    </div>
    <form method="GET" class="flex gap-3">
        <select name="status" class="px-3 py-2 bg-slate-900 border border-slate-700 text-slate-300 rounded-lg text-sm">
            <option value="">Semua Status</option>
            <option value="menunggu" {{ request('status')=='menunggu'?'selected':'' }}>Menunggu</option>
            <option value="diterima" {{ request('status')=='diterima'?'selected':'' }}>Diterima</option>
            <option value="ditolak" {{ request('status')=='ditolak'?'selected':'' }}>Ditolak</option>
        </select>
        <button class="px-4 py-2 bg-slate-700 text-sm text-white rounded-lg">Filter</button>
    </form>
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
        <table class="w-full text-sm text-slate-300"><thead class="bg-slate-950 text-slate-400 text-xs uppercase"><tr><th class="px-6 py-4 text-left">No. Registrasi</th><th class="px-6 py-4 text-left">Nama</th><th class="px-6 py-4 text-left">Asal Sekolah</th><th class="px-6 py-4 text-left">Status</th><th class="px-6 py-4"></th></tr></thead>
            <tbody class="divide-y divide-slate-800">
                @forelse($registrations as $r)
                <tr class="hover:bg-slate-800/30">
                    <td class="px-6 py-4 font-mono text-xs">{{ $r->registration_number }}</td>
                    <td class="px-6 py-4 font-semibold text-white">{{ $r->full_name }}</td>
                    <td class="px-6 py-4">{{ $r->previous_school }}</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs {{ $r->status==='diterima'?'bg-green-500/10 text-green-400':($r->status==='ditolak'?'bg-red-500/10 text-red-400':'bg-amber-500/10 text-amber-400') }}">{{ ucfirst($r->status) }}</span></td>
                    <td class="px-6 py-4 text-right"><a href="{{ route('admin.ppdb.show', $r) }}" class="text-blue-400 hover:text-blue-300 text-xs">Detail</a></td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-slate-500">Belum ada pendaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>{{ $registrations->links() }}</div>
</div>
@endsection
