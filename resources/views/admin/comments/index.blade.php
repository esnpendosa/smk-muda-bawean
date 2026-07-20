@extends('layouts.admin')

@section('breadcrumbs')
<div class="text-sm text-slate-400 flex items-center gap-2">
    <a href="{{ route('admin.dashboard') }}" class="hover:text-white transition">Dashboard</a>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
    <span class="text-slate-200">Komentar</span>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-white">Moderasi Komentar</h1>
            <p class="text-slate-400 text-sm mt-1">Kelola, setujui, filter, dan hapus komentar dari artikel berita.</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="flex items-center gap-2 border-b border-slate-800 pb-4">
        <a href="{{ route('admin.comments.index') }}" 
           class="px-4 py-2 rounded-xl text-xs font-semibold tracking-wider uppercase transition {{ !$status ? 'bg-blue-600 text-white shadow-lg' : 'bg-slate-900 text-slate-400 hover:text-white hover:bg-slate-800' }}">
            Semua
        </a>
        <a href="{{ route('admin.comments.index', ['status' => 'pending']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-semibold tracking-wider uppercase transition {{ $status === 'pending' ? 'bg-yellow-600 text-white shadow-lg' : 'bg-slate-900 text-slate-400 hover:text-white hover:bg-slate-800' }}">
            Tertunda (Pending)
        </a>
        <a href="{{ route('admin.comments.index', ['status' => 'approved']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-semibold tracking-wider uppercase transition {{ $status === 'approved' ? 'bg-green-600 text-white shadow-lg' : 'bg-slate-900 text-slate-400 hover:text-white hover:bg-slate-800' }}">
            Disetujui
        </a>
        <a href="{{ route('admin.comments.index', ['status' => 'spam']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-semibold tracking-wider uppercase transition {{ $status === 'spam' ? 'bg-red-600 text-white shadow-lg' : 'bg-slate-900 text-slate-400 hover:text-white hover:bg-slate-800' }}">
            Spam
        </a>
    </div>

    <!-- Comments Table Card -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-950/40 text-xs font-bold uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-4">Penulis</th>
                        <th class="px-6 py-4">Komentar</th>
                        <th class="px-6 py-4">Artikel</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850 text-sm text-slate-300">
                    @forelse($comments as $comment)
                        <tr class="hover:bg-slate-850/40 transition">
                            <!-- Commenter -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $comment->avatar_url }}" alt="{{ $comment->commenter_name }}" class="w-8 h-8 rounded-full border border-slate-700 bg-slate-800">
                                    <div>
                                        <div class="font-semibold text-white flex items-center gap-1.5">
                                            {{ $comment->commenter_name }}
                                            @if($comment->user_id && $comment->user?->role === 'admin')
                                                <span class="px-1 py-0.5 rounded text-[9px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Staf</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-slate-500">{{ $comment->user ? $comment->user->email : $comment->email }}</div>
                                        <div class="text-[10px] text-slate-600 mt-0.5">{{ $comment->ip_address }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Comment Content -->
                            <td class="px-6 py-4 max-w-xs sm:max-w-md">
                                <div class="line-clamp-3 text-slate-300 break-words whitespace-pre-line">
                                    {{ $comment->content }}
                                </div>
                            </td>

                            <!-- Post Title -->
                            <td class="px-6 py-4 max-w-[180px]">
                                <a href="{{ route('berita.show', $comment->post->slug) }}" target="_blank" class="text-blue-400 hover:text-blue-300 transition hover:underline font-medium block truncate">
                                    {{ $comment->post->title }}
                                </a>
                            </td>

                            <!-- Status Badge -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($comment->status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-500/10 text-green-400 border border-green-500/20">
                                        Disetujui
                                    </span>
                                @elseif($comment->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                                        Tertunda
                                    </span>
                                @elseif($comment->status === 'spam')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/20">
                                        Spam
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-500/10 text-slate-400 border border-slate-500/20">
                                        Sampah
                                    </span>
                                @endif
                            </td>

                            <!-- Date -->
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">
                                {{ $comment->created_at->format('d M Y H:i') }}
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Approve button -->
                                    @if($comment->status !== 'approved')
                                        <form action="{{ route('admin.comments.update', $comment->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-md transition">
                                                Setujui
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Spam button -->
                                    @if($comment->status !== 'spam')
                                        <form action="{{ route('admin.comments.update', $comment->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="spam">
                                            <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-yellow-600/20 hover:bg-yellow-600 text-yellow-400 hover:text-white font-semibold transition border border-yellow-600/30">
                                                Spam
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Delete button -->
                                    <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus komentar ini secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold shadow-md transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                <svg class="w-12 h-12 text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <span>Tidak ada komentar yang ditemukan.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($comments->hasPages())
            <div class="px-6 py-4 bg-slate-950/20 border-t border-slate-800">
                {{ $comments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
