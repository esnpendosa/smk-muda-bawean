@extends('layouts.admin')
@section('breadcrumbs')<nav class="text-sm"><ol class="flex items-center gap-1.5 text-slate-400"><li><span class="text-white font-semibold">Pendidik / Guru</span></li></ol></nav>@endsection
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between"><h1 class="text-2xl font-bold text-white">Manajemen Pendidik</h1><a href="{{ route('admin.teachers.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-lg transition">+ Tambah Pendidik</a></div>
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
        <table class="w-full text-sm text-slate-300"><thead class="bg-slate-950 text-slate-400 text-xs uppercase"><tr><th class="px-6 py-4 text-left">Nama</th><th class="px-6 py-4 text-left">Jabatan</th><th class="px-6 py-4 text-left">Urutan</th><th class="px-6 py-4"></th></tr></thead>
            <tbody class="divide-y divide-slate-800">
                @forelse($teachers as $teacher)
                <tr class="hover:bg-slate-800/30">
                    <td class="px-6 py-4 font-semibold text-white">{{ $teacher->name }}</td>
                    <td class="px-6 py-4">{{ $teacher->position }}</td>
                    <td class="px-6 py-4 font-mono">{{ $teacher->order }}</td>
                    <td class="px-6 py-4 text-right space-x-2"><a href="{{ route('admin.teachers.edit', $teacher) }}" class="text-blue-400 hover:text-blue-300 text-xs">Edit</a><form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}" class="inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="text-red-400 hover:text-red-300 text-xs">Hapus</button></form></td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-12 text-center text-slate-500">Belum ada data pendidik.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>{{ $teachers->links() }}</div>
</div>
@endsection
