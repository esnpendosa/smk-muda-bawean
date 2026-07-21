@extends('layouts.admin')
@section('breadcrumbs')
    <nav class="text-sm"><ol class="flex items-center gap-1.5 text-slate-400"><li><span class="text-white font-semibold">Manajemen Berita</span></li></ol></nav>
@endsection
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white">Manajemen Berita</h1>
        <a href="{{ route('admin.posts.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-lg transition">+ Tambah Berita</a>
    </div>
    <!-- Filter & Search -->
    <form method="GET" class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
        <div class="relative flex-grow max-w-md">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berita..." class="w-full pl-10 pr-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-300 placeholder-slate-500 text-sm focus:border-blue-500 outline-none transition">
        </div>
        <select name="status" class="px-3 py-2 bg-slate-900 border border-slate-700 text-slate-300 rounded-lg text-sm outline-none focus:border-blue-500 transition">
            <option value="">Semua Status</option>
            <option value="published" {{ request('status')=='published'?'selected':'' }}>Published</option>
            <option value="draft" {{ request('status')=='draft'?'selected':'' }}>Draft</option>
        </select>
        <button class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-sm font-bold text-white rounded-lg transition">Cari & Filter</button>
        @if(request()->filled('search') || request()->filled('status'))
            <a href="{{ route('admin.posts.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-sm font-semibold text-slate-300 rounded-lg transition text-center">Reset</a>
        @endif
    </form>
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
        <table class="w-full text-sm text-slate-300">
            <thead class="bg-slate-950 text-slate-400 text-xs uppercase">
                <tr><th class="px-6 py-4 text-left">Judul</th><th class="px-6 py-4 text-left">Status</th><th class="px-6 py-4 text-left">Tanggal</th><th class="px-6 py-4"></th></tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                @forelse($posts as $post)
                <tr class="hover:bg-slate-800/30">
                    <td class="px-6 py-4 font-semibold text-white">{{ $post->title }}<br><span class="text-xs text-slate-500">{{ $post->slug }}</span></td>
                    <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs {{ $post->status==='published' ? 'bg-green-500/10 text-green-400' : 'bg-slate-700 text-slate-400' }}">{{ $post->status }}</span>{{ $post->trashed() ? '<span class="ml-2 px-2 py-1 rounded-full text-xs bg-red-500/10 text-red-400">Dihapus</span>' : '' }}</td>
                    <td class="px-6 py-4 text-slate-400">{{ $post->published_at?->format('d M Y') ?? '-' }}</td>
                    <td class="px-6 py-4 text-right space-x-2">
                        @if(!$post->trashed())
                        <a href="{{ route('admin.posts.edit', $post) }}" class="text-blue-400 hover:text-blue-300 text-xs">Edit</a>
                        <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" class="inline" onsubmit="return confirm('Hapus berita ini?')">@csrf @method('DELETE')<button class="text-red-400 hover:text-red-300 text-xs">Hapus</button></form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-12 text-center text-slate-500">Belum ada berita.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>{{ $posts->links() }}</div>
</div>
@endsection
