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
            <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 tracking-tight">Pendidik & Staff</h1>
            <p class="text-sm text-gray-550">Daftar guru dan staff profesional pengajar SMK Muda Bawean.</p>
        </div>

        @if($teachers->isEmpty())
            <div class="p-12 text-center rounded-2xl border border-dashed border-gray-200 bg-white text-gray-500">
                <p class="text-base">Belum ada data pendidik yang tersedia.</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($teachers as $teacher)
                    <div class="bg-white border border-gray-150 rounded-2xl overflow-hidden hover:border-green-200 transition duration-300 flex flex-col group shadow-sm">
                        <div class="aspect-[3/4] w-full overflow-hidden bg-gray-50 relative border-b border-gray-100">
                            @if($teacher->photo)
                                @php
                                    // Support both old storage path and new public/uploads path
                                    $photoUrl = str_starts_with($teacher->photo, 'uploads/')
                                        ? asset($teacher->photo)
                                        : asset('storage/' . $teacher->photo);
                                @endphp
                                <img src="{{ $photoUrl }}" 
                                     alt="Foto Pendidik: {{ $teacher->name }}" 
                                     loading="lazy" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <!-- Premium SVG Avatar Placeholder -->
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-green-50 to-emerald-100 text-green-300">
                                    <svg class="w-16 h-16 stroke-current" fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-4 text-center space-y-1">
                            <h3 class="font-bold text-gray-900 text-base truncate">{{ $teacher->name }}</h3>
                            <p class="text-xs text-green-700 font-bold truncate">{{ $teacher->position }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
