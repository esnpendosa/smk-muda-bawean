@extends('layouts.public')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <x-breadcrumb :breadcrumbs="[
        ['label' => 'Profil', 'url' => ''],
        ['label' => 'Pendidik', 'url' => '']
    ]" />

    <div class="mt-8 space-y-8">
        <div class="space-y-2">
            <h1 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight">Pendidik & Staff</h1>
            <p class="text-sm text-slate-400">Daftar guru dan staff profesional pengajar SMK Muda Bawean.</p>
        </div>

        @if($teachers->isEmpty())
            <div class="p-12 text-center rounded-2xl border border-dashed border-slate-800 bg-slate-900/20 text-slate-400">
                <p class="text-base">Belum ada data pendidik yang tersedia.</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($teachers as $teacher)
                    <div class="bg-slate-900 border border-slate-850 rounded-2xl overflow-hidden hover:border-slate-700 transition duration-300 flex flex-col group">
                        <div class="aspect-[3/4] w-full overflow-hidden bg-slate-950 relative">
                            @if($teacher->photo)
                                <img src="{{ asset('storage/' . $teacher->photo) }}" 
                                     alt="Foto Pendidik: {{ $teacher->name }}" 
                                     loading="lazy" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <!-- Premium SVG Avatar Placeholder -->
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-900 to-slate-950 text-slate-700">
                                    <svg class="w-16 h-16 stroke-current" fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-4 text-center space-y-1">
                            <h3 class="font-bold text-white text-base truncate">{{ $teacher->name }}</h3>
                            <p class="text-xs text-primary font-medium truncate">{{ $teacher->position }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
