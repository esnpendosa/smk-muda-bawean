@extends('layouts.public')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 py-20 text-center">
    <x-breadcrumb :breadcrumbs="[
        ['label' => 'Kelulusan', 'url' => '']
    ]" />

    <div class="mt-8 bg-white border border-gray-150 rounded-2xl p-8 sm:p-12 space-y-8 relative overflow-hidden shadow-sm">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-green-500/5 via-transparent to-transparent pointer-events-none"></div>
        
        <div class="relative z-10 space-y-6">
            <div class="w-16 h-16 rounded-2xl bg-green-50 border border-green-100 flex items-center justify-center text-green-600 mx-auto">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            
            <div class="space-y-2">
                <h1 class="text-3xl font-extrabold text-gray-900">Verifikasi Kelulusan</h1>
                <p class="text-sm text-gray-500">Masukkan 10 digit NISN Anda untuk memeriksa status kelulusan tahun ajaran ini.</p>
            </div>

            @if(session('error'))
                <div role="alert" class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div role="alert" class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm text-left">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('kelulusan.search') }}" method="POST" class="space-y-4 text-left">
                @csrf
                <div>
                    <label for="nisn" class="block text-sm font-semibold text-gray-700 mb-2">NISN Siswa</label>
                    <input type="text" name="nisn" id="nisn" placeholder="Contoh: 0072345678" value="{{ old('nisn') }}" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-green-600 focus:ring-1 focus:ring-green-600 rounded-xl text-gray-800 outline-none transition duration-150">
                </div>

                <button type="submit" class="w-full py-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition duration-150 shadow-sm">
                    Cek Status
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
