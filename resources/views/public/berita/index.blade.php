@extends('layouts.public')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <x-breadcrumb :breadcrumbs="[
        ['label' => 'Berita', 'url' => '']
    ]" />

    <div class="mt-8 space-y-8">
        <div class="space-y-2">
            <h1 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight">Kabar & Berita</h1>
            <p class="text-sm text-slate-400">Arsip berita dan publikasi kegiatan SMK Muda Bawean.</p>
        </div>

        @if($posts->isEmpty())
            <div class="p-12 text-center rounded-2xl border border-dashed border-slate-800 bg-slate-900/20 text-slate-400">
                <p class="text-base">Belum ada berita yang dipublikasikan.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    <x-news-card :post="$post" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-8 border-t border-slate-900">
                {{ $posts->links('components.pagination') }}
            </div>
        @endif
    </div>
</div>
@endsection
