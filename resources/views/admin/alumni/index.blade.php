@extends('layouts.admin')
@section('breadcrumbs')<nav class="text-sm"><ol class="flex items-center gap-1.5 text-slate-400"><li><span class="text-white font-semibold">Alumni</span></li></ol></nav>@endsection
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white">Direktori Alumni</h1>
        <a href="{{ route('admin.tracer-studies.index') }}" class="px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white text-sm font-bold rounded-lg transition">Statistik Tracer Study</a>
    </div>
    <form method="GET" class="flex gap-3"><input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama..." class="px-3 py-2 bg-slate-900 border border-slate-700 text-white rounded-lg text-sm flex-1"><button class="px-4 py-2 bg-slate-700 text-sm text-white rounded-lg">Cari</button></form>
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
        <table class="w-full text-sm text-slate-300"><thead class="bg-slate-950 text-slate-400 text-xs uppercase"><tr><th class="px-6 py-4 text-left">Nama</th><th class="px-6 py-4 text-left">Tahun Lulus</th><th class="px-6 py-4 text-left">Email</th><th class="px-6 py-4"></th></tr></thead>
            <tbody class="divide-y divide-slate-800">
                @forelse($alumni as $item)
                <tr class="hover:bg-slate-800/30">
                    <td class="px-6 py-4 font-semibold text-white">{{ $item->full_name }}</td>
                    <td class="px-6 py-4">{{ $item->graduation_year }}</td>
                    <td class="px-6 py-4 text-slate-400">{{ $item->email }}</td>
                    <td class="px-6 py-4 text-right">
                        @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.alumni.show', $item) }}" class="text-blue-400 hover:text-blue-300 text-xs">Detail</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-12 text-center text-slate-500">Belum ada alumni.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>{{ $alumni->links() }}</div>
</div>
@endsection
