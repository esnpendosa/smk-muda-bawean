@extends('layouts.public')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-12">
    <!-- Breadcrumb -->
    <x-breadcrumb :breadcrumbs="[
        ['label' => 'PPDB', 'url' => '']
    ]" />

    <div class="mt-8 bg-white border border-gray-150 rounded-2xl p-6 sm:p-10 space-y-8 relative overflow-hidden shadow-sm">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-green-500/5 via-transparent to-transparent pointer-events-none"></div>
        
        <div class="relative z-10 space-y-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-50 border border-green-100 flex items-center justify-center text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Penerimaan Peserta Didik Baru (PPDB)</h1>
                    <p class="text-xs text-gray-500">Pendaftaran online calon siswa-siswi SMK Muda Bawean.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="p-6 rounded-2xl bg-green-50 border border-green-200 space-y-4">
                    <div class="flex items-center gap-2 text-green-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-bold text-base">{{ session('success') }}</span>
                    </div>
                    @if(session('registration'))
                        @php $reg = session('registration'); @endphp
                        <div class="text-sm text-gray-700 space-y-2 border-t border-green-200 pt-4">
                            <p>Simpan nomor pendaftaran Anda untuk melakukan cek status pendaftaran berkala:</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-xl border border-gray-150">
                                <div>
                                    <span class="text-xs text-gray-400">Nomor Registrasi</span>
                                    <p class="font-mono font-bold text-green-700 text-base">{{ $reg->registration_number }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-400">Nama Pendaftar</span>
                                    <p class="font-bold text-gray-800 text-base">{{ $reg->full_name }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-400">Asal Sekolah</span>
                                    <p class="text-gray-600 text-sm">{{ $reg->previous_school }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-400">Status Awal</span>
                                    <p class="text-gray-600 text-sm capitalize">{{ $reg->status }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('ppdb.store') }}" method="POST" class="space-y-6 text-left">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="full_name" class="block text-xs font-semibold text-gray-600 mb-2">Nama Lengkap Calon Siswa</label>
                        <input type="text" name="full_name" id="full_name" required value="{{ old('full_name') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-green-600 focus:ring-1 focus:ring-green-600 rounded-xl text-gray-800 text-sm outline-none transition">
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-semibold text-gray-600 mb-2">Nomor HP / WhatsApp Aktif</label>
                        <input type="text" name="phone" id="phone" required value="{{ old('phone') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-green-600 focus:ring-1 focus:ring-green-600 rounded-xl text-gray-800 text-sm outline-none transition">
                    </div>

                    <div>
                        <label for="birth_place" class="block text-xs font-semibold text-gray-600 mb-2">Tempat Lahir</label>
                        <input type="text" name="birth_place" id="birth_place" required value="{{ old('birth_place') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-green-600 focus:ring-1 focus:ring-green-600 rounded-xl text-gray-800 text-sm outline-none transition">
                    </div>

                    <div>
                        <label for="birth_date" class="block text-xs font-semibold text-gray-600 mb-2">Tanggal Lahir</label>
                        <input type="date" name="birth_date" id="birth_date" required value="{{ old('birth_date') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-green-600 focus:ring-1 focus:ring-green-600 rounded-xl text-gray-800 text-sm outline-none transition">
                    </div>

                    <div>
                        <label for="previous_school" class="block text-xs font-semibold text-gray-600 mb-2">Asal Sekolah (SMP / MTs)</label>
                        <input type="text" name="previous_school" id="previous_school" required value="{{ old('previous_school') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-green-600 focus:ring-1 focus:ring-green-600 rounded-xl text-gray-800 text-sm outline-none transition">
                    </div>

                    <div>
                        <label for="parent_name" class="block text-xs font-semibold text-gray-600 mb-2">Nama Orang Tua / Wali</label>
                        <input type="text" name="parent_name" id="parent_name" required value="{{ old('parent_name') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-green-600 focus:ring-1 focus:ring-green-600 rounded-xl text-gray-800 text-sm outline-none transition">
                    </div>
                </div>

                <button type="submit" class="w-full py-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition duration-150 shadow-sm">
                    Kirim Pendaftaran PPDB
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
