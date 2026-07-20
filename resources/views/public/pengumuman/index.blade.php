@extends('layouts.public')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <x-breadcrumb :breadcrumbs="[
        ['label' => 'Pengumuman', 'url' => '']
    ]" />

    <div class="mt-8 space-y-8">
        <div class="space-y-2">
            <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 tracking-tight">Pengumuman</h1>
            <p class="text-sm text-gray-600">Arsip pengumuman resmi dan agenda penting SMK Muda Bawean.</p>
        </div>

        @if($announcements->isEmpty())
            <div class="p-12 text-center rounded-2xl border border-dashed border-gray-200 bg-white text-gray-500 shadow-sm">
                <p class="text-base">Belum ada pengumuman yang dipublikasikan.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($announcements as $ann)
                    <x-announcement-item :announcement="$ann" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-8 border-t border-gray-200">
                {{ $announcements->links('components.pagination') }}
            </div>
        @endif
    </div>
</div>
@endsection
