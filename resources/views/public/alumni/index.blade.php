@extends('layouts.public')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <x-breadcrumb :breadcrumbs="[
        ['label' => 'Alumni', 'url' => '']
    ]" />

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Alumni Directory List -->
        <div class="lg:col-span-2 space-y-6">
            <div class="space-y-2">
                <h1 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight">Direktori Alumni</h1>
                <p class="text-sm text-slate-400">Temukan rekan lulusan SMK Muda Bawean dan perbarui status pekerjaan/studi Anda.</p>
            </div>

            <!-- Search & Filters -->
            <form action="{{ route('alumni.index') }}" method="GET" class="bg-slate-900 border border-slate-850 p-4 rounded-xl flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" placeholder="Cari nama alumni..." value="{{ request('search') }}"
                           class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg text-white text-sm outline-none transition">
                </div>
                <div class="w-full sm:w-48">
                    <select name="year" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg text-white text-sm outline-none transition">
                        <option value="">Semua Angkatan</option>
                        @foreach($uniqueYears as $yr)
                            <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>Angkatan {{ $yr }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-6 py-2.5 bg-primary hover:bg-secondary text-slate-950 font-bold rounded-lg text-sm transition">
                    Filter
                </button>
            </form>

            @if(session('success_search'))
                <div class="p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 text-sm">
                    {{ session('success_search') }}
                </div>
            @endif

            <!-- Alumni Cards / Table -->
            @if($alumni->isEmpty())
                <div class="p-12 text-center rounded-2xl border border-dashed border-slate-800 bg-slate-900/20 text-slate-400">
                    <p class="text-base">Tidak ditemukan data alumni.</p>
                </div>
            @else
                <div class="bg-slate-900 border border-slate-850 rounded-2xl overflow-hidden shadow-xl">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-300">
                            <thead class="bg-slate-950 text-slate-400 uppercase text-xs tracking-wider">
                                <tr>
                                    <th class="px-6 py-4 font-bold">Nama Lengkap</th>
                                    <th class="px-6 py-4 font-bold">Tahun Lulus</th>
                                    <th class="px-6 py-4 font-bold">Status Kesibukan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-850">
                                @foreach($alumni as $item)
                                    @php
                                        $latestTracer = $item->tracerStudies->first();
                                        if ($latestTracer) {
                                            $status = [];
                                            if ($latestTracer->education_status === 'kuliah') {
                                                $status[] = 'Studi/Kuliah';
                                            }
                                            if ($latestTracer->employment_status === 'bekerja') {
                                                $status[] = 'Bekerja';
                                            }
                                            $statusStr = empty($status) ? 'Mencari Kerja / Lainnya' : implode(' & ', $status);
                                        } else {
                                            $statusStr = 'Belum Mengisi Tracer';
                                        }
                                    @endphp
                                    <tr class="hover:bg-slate-850/30 transition duration-150">
                                        <td class="px-6 py-4 font-semibold text-white">{{ $item->full_name }}</td>
                                        <td class="px-6 py-4 font-mono">{{ $item->graduation_year }}</td>
                                        <td class="px-6 py-4">
                                            <span class="text-xs px-2.5 py-1 rounded-full {{ $latestTracer ? 'bg-primary/10 text-primary border border-primary/20' : 'bg-slate-800 text-slate-450 border border-slate-750' }}">
                                                {{ $statusStr }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="pt-4">
                    {{ $alumni->links('components.pagination') }}
                </div>
            @endif
        </div>

        <!-- Registration Form -->
        <div class="space-y-6">
            <div class="bg-slate-900 border border-slate-850 rounded-2xl p-6 sm:p-8 space-y-6 relative overflow-hidden shadow-2xl">
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-primary/5 via-transparent to-transparent"></div>
                
                <div class="relative z-10 space-y-4">
                    <div class="space-y-1">
                        <h2 class="text-xl font-bold text-white">Daftar Alumni Baru</h2>
                        <p class="text-xs text-slate-400">Daftarkan data Anda untuk masuk ke dalam direktori lulusan sekolah.</p>
                    </div>

                    @if(session('success'))
                        <div class="p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
                            <ul class="list-disc pl-4 space-y-1">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('alumni.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="full_name" class="block text-xs font-semibold text-slate-350 mb-1">Nama Lengkap</label>
                            <input type="text" name="full_name" id="full_name" required value="{{ old('full_name') }}"
                                   class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg text-white text-sm outline-none transition">
                        </div>
                        
                        <div>
                            <label for="graduation_year" class="block text-xs font-semibold text-slate-350 mb-1">Tahun Lulus (Angkatan)</label>
                            <input type="number" name="graduation_year" id="graduation_year" required min="1990" value="{{ old('graduation_year', date('Y')) }}"
                                   class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg text-white text-sm outline-none transition">
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-semibold text-slate-350 mb-1">Alamat Email</label>
                            <input type="email" name="email" id="email" required value="{{ old('email') }}"
                                   class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg text-white text-sm outline-none transition">
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-semibold text-slate-350 mb-1">Nomor HP / WhatsApp</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                   class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg text-white text-sm outline-none transition">
                        </div>

                        <div>
                            <label for="address" class="block text-xs font-semibold text-slate-350 mb-1">Alamat Sekarang</label>
                            <textarea name="address" id="address" rows="3"
                                      class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg text-white text-sm outline-none transition">{{ old('address') }}</textarea>
                        </div>

                        <button type="submit" class="w-full py-3 bg-primary hover:bg-secondary text-slate-950 font-bold rounded-lg text-sm transition shadow-lg shadow-primary/10">
                            Daftarkan Alumni
                        </button>
                    </form>

                    <div class="pt-4 border-t border-slate-850 text-center">
                        <a href="{{ route('alumni.tracer-study') }}" class="text-xs text-primary hover:underline font-bold">
                            Sudah Terdaftar? Isi Tracer Study Di Sini →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
