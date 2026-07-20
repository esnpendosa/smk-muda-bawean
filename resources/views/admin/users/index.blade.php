@extends('layouts.admin')
@section('breadcrumbs')<nav class="text-sm"><ol class="flex items-center gap-1.5 text-slate-400"><li><span class="text-white font-semibold">Pengguna</span></li></ol></nav>@endsection
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between"><h1 class="text-2xl font-bold text-white">Manajemen Pengguna</h1><a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-lg transition">+ Tambah Pengguna</a></div>
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
        <table class="w-full text-sm text-slate-300"><thead class="bg-slate-950 text-slate-400 text-xs uppercase"><tr><th class="px-6 py-4 text-left">Nama</th><th class="px-6 py-4 text-left">Email</th><th class="px-6 py-4 text-left">Role</th><th class="px-6 py-4"></th></tr></thead>
            <tbody class="divide-y divide-slate-800">
                @forelse($users as $user)
                <tr class="hover:bg-slate-800/30">
                    <td class="px-6 py-4 font-semibold text-white">{{ $user->name }}{{ $user->id === auth()->id() ? ' (Anda)' : '' }}</td>
                    <td class="px-6 py-4">{{ $user->email }}</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs {{ $user->role==='admin' ? 'bg-blue-500/10 text-blue-400' : 'bg-slate-700 text-slate-400' }}">{{ ucfirst($user->role) }}</span></td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-400 hover:text-blue-300 text-xs">Edit</a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Hapus pengguna ini?')">@csrf @method('DELETE')<button class="text-red-400 hover:text-red-300 text-xs">Hapus</button></form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-12 text-center text-slate-500">Belum ada pengguna.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>{{ $users->links() }}</div>
</div>
@endsection
