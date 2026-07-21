@extends('layouts.public')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-20">
    <x-breadcrumb :breadcrumbs="[
        ['label' => 'Kelulusan', 'url' => route('kelulusan.index')],
        ['label' => 'Hasil', 'url' => '']
    ]" />

    <div class="mt-8 bg-white border border-gray-150 rounded-2xl p-8 sm:p-12 space-y-8 relative overflow-hidden shadow-sm">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-green-500/5 via-transparent to-transparent pointer-events-none"></div>
        
        <div class="relative z-10 space-y-6">
            <div class="text-center space-y-2">
                <h1 class="text-3xl font-extrabold text-gray-900">Hasil Verifikasi Kelulusan</h1>
                <p class="text-sm text-gray-500">Status kelulusan resmi siswa berdasarkan database sekolah.</p>
            </div>

            <!-- Details Card -->
            <div class="bg-gray-50 border border-gray-150 rounded-xl p-6 space-y-4">
                <div class="flex justify-between border-b border-gray-200 pb-3">
                    <span class="text-gray-500 text-sm">Nama Lengkap</span>
                    <span class="text-gray-900 font-bold text-sm text-right">{{ $graduation->student_name }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-3">
                    <span class="text-gray-500 text-sm">NISN Siswa</span>
                    <span class="text-gray-900 font-mono text-sm text-right">{{ $graduation->exam_number }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-3">
                    <span class="text-gray-500 text-sm">Program Keahlian</span>
                    <span class="text-gray-900 font-bold text-sm text-right">{{ $graduation->program_keahlian }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500 text-sm">Status Kelulusan</span>
                    @if(strtoupper($graduation->status_kelulusan) === 'LULUS')
                        <span class="px-3 py-1 bg-green-50 text-green-750 border border-green-200 rounded-full text-xs font-black">
                            LULUS
                        </span>
                    @else
                        <span class="px-3 py-1 bg-red-50 text-red-700 border border-red-200 rounded-full text-xs font-black">
                            TIDAK LULUS
                        </span>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3 pt-4">
                @if(strtoupper($graduation->status_kelulusan) === 'LULUS')
                    <a href="{{ route('kelulusan.download', ['nisn' => $graduation->exam_number]) }}" class="w-full inline-flex items-center justify-center gap-2 py-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition duration-150 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Unduh Surat Kelulusan (PDF)
                    </a>
                @endif
                <a href="{{ route('kelulusan.index') }}" class="w-full inline-flex items-center justify-center py-4 bg-white hover:bg-gray-50 border border-gray-250 text-gray-800 font-semibold rounded-xl transition duration-150 shadow-sm">
                    Cek NISN Lain
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
