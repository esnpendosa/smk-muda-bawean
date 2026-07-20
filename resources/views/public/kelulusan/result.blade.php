@extends('layouts.public')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-20">
    <x-breadcrumb :breadcrumbs="[
        ['label' => 'Kelulusan', 'url' => route('kelulusan.index')],
        ['label' => 'Hasil', 'url' => '']
    ]" />

    <div class="mt-8 bg-slate-900 border border-slate-850 rounded-2xl p-8 sm:p-12 space-y-8 relative overflow-hidden shadow-2xl">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-primary/5 via-transparent to-transparent"></div>
        
        <div class="relative z-10 space-y-6">
            <div class="text-center space-y-2">
                <h1 class="text-3xl font-extrabold text-white">Hasil Verifikasi Kelulusan</h1>
                <p class="text-sm text-slate-400">Status kelulusan resmi siswa berdasarkan database sekolah.</p>
            </div>

            <!-- Details Card -->
            <div class="bg-slate-950/50 border border-slate-850 rounded-xl p-6 space-y-4">
                <div class="flex justify-between border-b border-slate-850 pb-3">
                    <span class="text-slate-450 text-sm">Nama Lengkap</span>
                    <span class="text-white font-bold text-sm text-right">{{ $graduation->nama }}</span>
                </div>
                <div class="flex justify-between border-b border-slate-850 pb-3">
                    <span class="text-slate-450 text-sm">NISN Siswa</span>
                    <span class="text-white font-mono text-sm text-right">{{ $graduation->nisn }}</span>
                </div>
                <div class="flex justify-between border-b border-slate-850 pb-3">
                    <span class="text-slate-450 text-sm">Program Keahlian</span>
                    <span class="text-white font-bold text-sm text-right">{{ $graduation->program_keahlian }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-450 text-sm">Status Kelulusan</span>
                    @if(strtoupper($graduation->status_kelulusan) === 'LULUS')
                        <span class="px-3 py-1 bg-green-500/10 text-green-400 border border-green-500/20 rounded-full text-xs font-black">
                            LULUS
                        </span>
                    @else
                        <span class="px-3 py-1 bg-red-500/10 text-red-400 border border-red-500/20 rounded-full text-xs font-black">
                            TIDAK LULUS
                        </span>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3 pt-4">
                @if(strtoupper($graduation->status_kelulusan) === 'LULUS')
                    <a href="{{ route('kelulusan.download', $graduation->exam_number) }}" class="w-full inline-flex items-center justify-center gap-2 py-4 bg-primary hover:bg-secondary text-slate-950 font-bold rounded-xl transition duration-150 shadow-lg shadow-primary/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Unduh Surat Kelulusan (PDF)
                    </a>
                @endif
                <a href="{{ route('kelulusan.index') }}" class="w-full inline-flex items-center justify-center py-4 bg-slate-950 hover:bg-slate-900 border border-slate-800 text-white font-semibold rounded-xl transition duration-150">
                    Cek NISN Lain
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
