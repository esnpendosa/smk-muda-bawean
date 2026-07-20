@props(['post'])

<article class="flex flex-col bg-slate-900 border border-slate-850 hover:border-slate-700 rounded-2xl overflow-hidden transition-all duration-300 group shadow-lg hover:shadow-primary/5">
    <!-- Thumbnail -->
    <div class="aspect-video w-full overflow-hidden bg-slate-950 relative">
        @if($post->thumbnail)
            <img src="{{ asset('storage/' . $post->thumbnail) }}" 
                 alt="Thumbnail: {{ $post->title }}" 
                 loading="lazy" 
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
            <!-- Premium SVG Placeholder -->
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-900 to-slate-950 text-slate-700">
                <svg class="w-12 h-12 stroke-current" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 00-2-2m-2 3h2a2 2 0 002-2V8a2 2 0 00-2-2h-2m-9 5h4m-4 4h1m1-4l2 2m-2-2v4"></path>
                </svg>
            </div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
    </div>

    <!-- Content -->
    <div class="p-6 flex-grow flex flex-col justify-between">
        <div class="space-y-3">
            <!-- Meta -->
            <div class="flex items-center gap-4 text-xs text-slate-400">
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    {{ $post->author?->name ?? 'Admin' }}
                </span>
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ $post->published_at?->format('d M Y') ?? $post->created_at->format('d M Y') }}
                </span>
            </div>
            
            <!-- Title -->
            <h3 class="text-lg font-bold text-white group-hover:text-primary transition-colors duration-200 line-clamp-2">
                <a href="{{ route('berita.show', $post->slug) }}">
                    {{ $post->title }}
                </a>
            </h3>
            
            <!-- Excerpt -->
            <p class="text-sm text-slate-400 line-clamp-3 leading-relaxed">
                {{ Str::limit(strip_tags($post->content), 120) }}
            </p>
        </div>

        <div class="pt-5 mt-auto">
            <a href="{{ route('berita.show', $post->slug) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:text-white group-hover:translate-x-1 transition-all duration-200">
                Baca Selengkapnya
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
    </div>
</article>
