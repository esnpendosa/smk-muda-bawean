@extends('layouts.public')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <x-breadcrumb :breadcrumbs="[
        ['label' => 'Pengumuman', 'url' => '']
    ]" />

    <div class="mt-8 space-y-8">
        <div class="space-y-2">
            <h1 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight">Pengumuman</h1>
            <p class="text-sm text-slate-400">Arsip pengumuman resmi dan agenda penting SMK Muda Bawean.</p>
        </div>

        @if($announcements->isEmpty())
            <div class="p-12 text-center rounded-2xl border border-dashed border-slate-800 bg-slate-900/20 text-slate-400">
                <p class="text-base">Belum ada pengumuman yang dipublikasikan.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($announcements as $ann)
                    <x-announcement-item :announcement="$ann" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-8 border-t border-slate-900">
                {{ $announcements->links('components.pagination') }}
            </div>
        @endif
    </div>
</div>
@endsection
