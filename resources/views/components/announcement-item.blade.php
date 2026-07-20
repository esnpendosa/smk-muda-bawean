@props(['announcement'])

<div class="p-5 bg-white border border-gray-150 rounded-xl hover:border-green-200 hover:shadow-md transition duration-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="space-y-1">
        <span class="text-xs text-green-600 font-bold uppercase tracking-wider">
            {{ $announcement->published_at?->format('d M Y') ?? $announcement->created_at->format('d M Y') }}
        </span>
        <h3 class="text-base font-bold text-gray-900 hover:text-green-700 transition duration-150">
            <a href="{{ route('pengumuman.show', $announcement->slug) }}">
                {{ $announcement->title }}
            </a>
        </h3>
    </div>
    
    <div class="flex items-center gap-3">
        @if($announcement->attachment)
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-red-50 text-red-700 border border-red-100">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                PDF Lampiran
            </span>
        @endif
        <a href="{{ route('pengumuman.show', $announcement->slug) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-gray-500 hover:text-green-650 transition duration-150">
            Detail
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>
</div>
