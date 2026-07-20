@extends('layouts.public')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <x-breadcrumb :breadcrumbs="[
        ['label' => 'Profil', 'url' => ''],
        ['label' => 'Sejarah', 'url' => '']
    ]" />

    <div class="mt-8 bg-slate-900 border border-slate-850 rounded-2xl p-8 sm:p-12 space-y-8 relative overflow-hidden shadow-xl">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-primary/5 via-transparent to-transparent"></div>
        
        <div class="relative z-10 space-y-6">
            <h1 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight">
                {{ $page->title }}
            </h1>
            <div class="prose prose-invert max-w-none text-slate-350 leading-relaxed text-base sm:text-lg space-y-6">
                {!! clean($page->content) !!}
            </div>
        </div>
    </div>
</div>
@endsection

@push('schema')
    <x-schema-markup :schema="$schema" />
@endpush
