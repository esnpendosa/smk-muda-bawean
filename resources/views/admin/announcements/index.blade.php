@extends('layouts.admin')
@section('breadcrumbs')
    <nav class="text-sm"><ol class="flex items-center gap-1.5 text-slate-400"><li><span class="text-white font-semibold">Pengumuman</span></li></ol></nav>
@endsection
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white">Manajemen Pengumuman</h1>
        <a href="{{ route('admin.announcements.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-lg transition">+ Tambah Pengumuman</a>
    </div>
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
        <table class="w-full text-sm text-slate-300">
            <thead class="bg-slate-950 text-slate-400 text-xs uppercase"><tr><th class="px-6 py-4 text-left">Judul</th><th class="px-6 py-4 text-left">Status</th><th class="px-6 py-4 text-left">Tanggal</th><th class="px-6 py-4"></th></tr></thead>
            <tbody class="divide-y divide-slate-800">
                @forelse($announcements as $ann)
                <tr class="hover:bg-slate-800/30">
                    <td class="px-6 py-4 font-semibold text-white">{{ $ann->title }}</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs {{ $ann->status==='published' ? 'bg-green-500/10 text-green-400' : 'bg-slate-700 text-slate-400' }}">{{ $ann->status }}</span></td>
                    <td class="px-6 py-4 text-slate-400">{{ $ann->published_at?->format('d M Y') ?? '-' }}</td>
                    <td class="px-6 py-4 text-right space-x-2">
                        @if(!$ann->trashed())
                        <a href="{{ route('admin.announcements.edit', $ann) }}" class="text-blue-400 hover:text-blue-300 text-xs">Edit</a>
                        <form method="POST" action="{{ route('admin.announcements.destroy', $ann) }}" class="inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="text-red-400 hover:text-red-300 text-xs">Hapus</button></form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-12 text-center text-slate-500">Belum ada pengumuman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>{{ $announcements->links() }}</div>
</div>
@endsection
