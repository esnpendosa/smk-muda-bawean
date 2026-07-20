@props(['post'])

<article class="flex flex-col bg-white border border-gray-100 hover:border-green-200 rounded-2xl overflow-hidden transition-all duration-300 group shadow-sm hover:shadow-lg">
    {{-- Thumbnail --}}
    <div class="aspect-video w-full overflow-hidden bg-green-50 relative">
        @if($post->thumbnail_url)
            <img src="{{ $post->thumbnail_url }}"
                 alt="Thumbnail: {{ $post->title }}"
                 loading="lazy"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-green-50 to-emerald-100 text-green-300">
                <svg class="w-12 h-12 stroke-current" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
    </div>

    {{-- Content --}}
    <div class="p-5 flex-grow flex flex-col justify-between">
        <div class="space-y-3">
            {{-- Meta --}}
            <div class="flex items-center gap-3 text-xs text-gray-400">
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    {{ $post->author?->name ?? 'Tim Redaksi' }}
                </span>
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ $post->published_at?->format('d M Y') ?? $post->created_at->format('d M Y') }}
                </span>
            </div>

            {{-- Title --}}
            <h3 class="text-base font-bold text-gray-900 group-hover:text-green-700 transition-colors duration-200 line-clamp-2 leading-snug">
                <a href="{{ route('berita.show', $post->slug) }}">{{ $post->title }}</a>
            </h3>

            {{-- Excerpt --}}
            <p class="text-sm text-gray-500 line-clamp-3 leading-relaxed">
                {{ Str::limit(strip_tags($post->content), 120) }}
            </p>
        </div>

        <div class="pt-4 mt-auto">
            <a href="{{ route('berita.show', $post->slug) }}"
               class="inline-flex items-center gap-1.5 text-sm font-semibold text-green-600 hover:text-green-800 group-hover:translate-x-1 transition-all duration-200">
                Baca Selengkapnya
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</article>
