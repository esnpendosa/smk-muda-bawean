@extends('layouts.public')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 py-20 text-center">
    <x-breadcrumb :breadcrumbs="[
        ['label' => 'Kelulusan', 'url' => '']
    ]" />

    <div class="mt-8 bg-slate-900 border border-slate-850 rounded-2xl p-8 sm:p-12 space-y-8 relative overflow-hidden shadow-2xl">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-primary/5 via-transparent to-transparent"></div>
        
        <div class="relative z-10 space-y-6">
            <div class="w-16 h-16 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary mx-auto">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            
            <div class="space-y-2">
                <h1 class="text-3xl font-extrabold text-white">Verifikasi Kelulusan</h1>
                <p class="text-sm text-slate-400">Masukkan 10 digit NISN Anda untuk memeriksa status kelulusan tahun ajaran ini.</p>
            </div>

            @if(session('error'))
                <div role="alert" class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div role="alert" class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm text-left">
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
                    <label for="nisn" class="block text-sm font-semibold text-slate-350 mb-2">NISN Siswa</label>
                    <input type="text" name="nisn" id="nisn" placeholder="Contoh: 0072345678" value="{{ old('nisn') }}" required
                           class="w-full px-4 py-3 bg-slate-950 border border-slate-800 focus:border-primary focus:ring-1 focus:ring-primary rounded-xl text-white outline-none transition duration-150">
                </div>

                <button type="submit" class="w-full py-4 bg-primary hover:bg-secondary text-slate-950 font-bold rounded-xl transition duration-150 shadow-lg shadow-primary/20">
                    Cek Status
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
