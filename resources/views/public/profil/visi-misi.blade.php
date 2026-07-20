@extends('layouts.public')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <x-breadcrumb :breadcrumbs="[
        ['label' => 'Profil', 'url' => ''],
        ['label' => 'Visi & Misi', 'url' => '']
    ]" />

    <div class="mt-8 bg-white border border-gray-150 rounded-2xl p-8 sm:p-12 space-y-8 relative overflow-hidden shadow-sm hover:shadow-md transition-shadow">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-green-500/5 via-transparent to-transparent pointer-events-none"></div>
        
        <div class="relative z-10 space-y-6">
            <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 tracking-tight leading-tight">
                {{ $page->title }}
            </h1>
            <div class="prose max-w-none text-gray-700 leading-relaxed text-base sm:text-lg space-y-6">
                {!! clean($page->content) !!}
            </div>
        </div>
    </div>
</div>
@endsection

@push('schema')
    <x-schema-markup :schema="$schema" />
@endpush
