@extends('layouts.public')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <x-breadcrumb :breadcrumbs="[
        ['label' => 'Pengumuman', 'url' => route('pengumuman.index')],
        ['label' => $announcement->title, 'url' => '']
    ]" />

    <article class="mt-8 space-y-8 bg-white border border-gray-150 rounded-2xl p-6 sm:p-10 shadow-sm relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-green-500/5 via-transparent to-transparent pointer-events-none"></div>
        
        <header class="space-y-4 relative z-10">
            <!-- Meta -->
            <div class="flex items-center gap-4 text-sm text-gray-500">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ $announcement->published_at?->format('d M Y') ?? $announcement->created_at->format('d M Y') }}
                </span>
            </div>
            
            <!-- Title -->
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight leading-tight">
                {{ $announcement->title }}
            </h1>
        </header>

        <!-- Body Content -->
        <div class="prose prose-green max-w-none text-gray-700 leading-relaxed text-base sm:text-lg space-y-6 relative z-10">
            {!! clean($announcement->content) !!}
        </div>

        <!-- Attachment Download Link -->
        @if($announcement->attachment)
            <div class="pt-6 border-t border-gray-150 flex flex-col sm:flex-row items-center justify-between gap-4 relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center text-red-650">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">Dokumen Lampiran</p>
                        <p class="text-xs text-gray-500">Format file PDF Resmi</p>
                    </div>
                </div>
                
                <a href="{{ route('pengumuman.download', $announcement->slug) }}" class="inline-flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition duration-150 shadow-sm w-full sm:w-auto">
                    Unduh Lampiran
                </a>
            </div>
        @endif
    </article>
</div>
@endsection

@push('schema')
    <x-schema-markup :schema="$schema" />
@endpush
