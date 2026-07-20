@extends('layouts.public')

@section('content')
<div class="max-w-md mx-auto my-20 text-center px-4">
    <div class="space-y-6">
        <h1 class="text-7xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-yellow-500 to-orange-500">410</h1>
        <h2 class="text-2xl font-bold text-white">Konten Telah Dihapus</h2>
        <p class="text-slate-400">Konten yang Anda minta sudah tidak lagi tersedia dan telah dihapus secara permanen.</p>
        <div class="pt-4">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-3 bg-primary hover:bg-secondary text-slate-950 font-bold rounded-xl transition duration-150 shadow-lg shadow-primary/20">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
