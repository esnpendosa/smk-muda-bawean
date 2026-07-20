@extends('layouts.public')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 py-20">
    <x-breadcrumb :breadcrumbs="[
        ['label' => 'Alumni', 'url' => route('alumni.index')],
        ['label' => 'Tracer Study', 'url' => '']
    ]" />

    <div class="mt-8 bg-white border border-gray-150 rounded-2xl p-8 sm:p-12 space-y-8 relative overflow-hidden shadow-sm">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-green-500/5 via-transparent to-transparent pointer-events-none"></div>
        
        <div class="relative z-10 space-y-6">
            <div class="w-16 h-16 rounded-2xl bg-green-50 border border-green-100 flex items-center justify-center text-green-600 mx-auto">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>

            <div class="text-center space-y-2">
                <h1 class="text-3xl font-extrabold text-gray-900">Formulir Tracer Study</h1>
                <p class="text-sm text-gray-500">Silakan isi formulir penelusuran studi & pekerjaan lulusan di bawah ini.</p>
            </div>

            @if(session('success'))
                <div class="p-4 rounded-xl bg-green-50 border border-green-250 text-green-700 text-sm text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm text-center">
                    {{ session('error') }}
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

            <form action="{{ route('alumni.tracer-study.store') }}" method="POST" class="space-y-5 text-left">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email Terdaftar</label>
                    <input type="email" name="email" id="email" required placeholder="Email yang Anda daftarkan sebagai alumni" value="{{ old('email') }}"
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-green-600 focus:ring-1 focus:ring-green-600 rounded-xl text-gray-800 outline-none transition duration-150">
                </div>

                <div>
                    <label for="education_status" class="block text-sm font-semibold text-gray-700 mb-2">Status Pendidikan Tinggi</label>
                    <select name="education_status" id="education_status" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-green-600 focus:ring-1 focus:ring-green-600 rounded-xl text-gray-800 outline-none transition duration-150">
                        <option value="tidak_kuliah" {{ old('education_status') == 'tidak_kuliah' ? 'selected' : '' }}>Tidak / Belum Kuliah</option>
                        <option value="kuliah" {{ old('education_status') == 'kuliah' ? 'selected' : '' }}>Sedang Kuliah / Lulus Kuliah</option>
                    </select>
                </div>

                <div>
                    <label for="employment_status" class="block text-sm font-semibold text-gray-700 mb-2">Status Pekerjaan</label>
                    <select name="employment_status" id="employment_status" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-green-600 focus:ring-1 focus:ring-green-600 rounded-xl text-gray-800 outline-none transition duration-150">
                        <option value="tidak_bekerja" {{ old('employment_status') == 'tidak_bekerja' ? 'selected' : '' }}>Tidak Bekerja / Sedang Mencari Kerja</option>
                        <option value="bekerja" {{ old('employment_status') == 'bekerja' ? 'selected' : '' }}>Bekerja / Berwirausaha</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition duration-150 shadow-sm">
                    Kirim Data Tracer
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
