@extends('layouts.public')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <x-breadcrumb :breadcrumbs="[
        ['label' => 'Berita', 'url' => route('berita.index')],
        ['label' => $post->title, 'url' => '']
    ]" />

    <article class="mt-8 space-y-8 bg-white border border-gray-150 rounded-2xl p-6 sm:p-10 shadow-sm relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-green-500/5 via-transparent to-transparent pointer-events-none"></div>
        
        <header class="space-y-4 relative z-10">
            <!-- Meta -->
            <div class="flex items-center gap-4 text-sm text-gray-500">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    {{ $post->author?->name ?? 'Tim Redaksi' }}
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ $post->published_at?->format('d M Y') ?? $post->created_at->format('d M Y') }}
                </span>
            </div>
            
            <!-- Title -->
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight leading-tight">
                {{ $post->title }}
            </h1>
        </header>

        <!-- Thumbnail Image -->
        @if($post->thumbnail_url)
            <div class="aspect-video w-full rounded-xl overflow-hidden bg-gray-50 border border-gray-150 relative z-10">
                <img src="{{ $post->thumbnail_url }}" 
                     alt="Cover: {{ $post->title }}" 
                     class="w-full h-full object-cover">
            </div>
        @endif

        <!-- Body Content -->
        <div class="prose prose-green max-w-none text-gray-700 leading-relaxed text-base sm:text-lg space-y-6 relative z-10 [&>p]:text-justify [&>p]:hyphens-auto">
            {!! clean($post->content) !!}
        </div>

        <!-- Social Media Share -->
        @include('public.berita.partials.share')
    </article>

    <!-- Comments System -->
    @include('public.berita.partials.comments')
</div>
@endsection

@push('schema')
    <x-schema-markup :schema="$schema" />
@endpush
