@extends('layouts.admin')
@section('breadcrumbs')<nav class="text-sm"><ol class="flex items-center gap-1.5 text-slate-400"><li><span class="text-white font-semibold">Halaman Statis</span></li></ol></nav>@endsection
@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-white">Halaman Statis</h1>
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
        <table class="w-full text-sm text-slate-300"><thead class="bg-slate-950 text-slate-400 text-xs uppercase"><tr><th class="px-6 py-4 text-left">Judul</th><th class="px-6 py-4 text-left">Slug</th><th class="px-6 py-4"></th></tr></thead>
            <tbody class="divide-y divide-slate-800">
                @forelse($pages as $page)
                <tr class="hover:bg-slate-800/30">
                    <td class="px-6 py-4 font-semibold text-white">{{ $page->title }}</td>
                    <td class="px-6 py-4 font-mono text-slate-400">{{ $page->slug }}</td>
                    <td class="px-6 py-4 text-right"><a href="{{ route('admin.pages.edit', $page) }}" class="text-blue-400 hover:text-blue-300 text-xs">Edit Konten</a></td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-6 py-12 text-center text-slate-500">Belum ada halaman statis.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
