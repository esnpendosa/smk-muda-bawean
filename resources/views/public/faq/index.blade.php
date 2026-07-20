@extends('layouts.public')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12">
    <!-- Breadcrumb -->
    <x-breadcrumb :breadcrumbs="[
        ['label' => 'FAQ', 'url' => '']
    ]" />

    <div class="mt-8 space-y-8">
        <div class="space-y-2">
            <h1 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight">Pertanyaan Umum (FAQ)</h1>
            <p class="text-sm text-slate-400">Temukan jawaban atas berbagai pertanyaan umum tentang SMK Muda Bawean.</p>
        </div>

        <!-- Search Bar -->
        <form action="{{ route('faq.index') }}" method="GET" class="bg-slate-900 border border-slate-850 p-4 rounded-xl flex gap-4">
            <input type="text" name="search" placeholder="Cari pertanyaan atau jawaban..." value="{{ request('search') }}"
                   class="flex-1 px-4 py-2.5 bg-slate-950 border border-slate-800 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg text-white text-sm outline-none transition">
            <button type="submit" class="px-6 py-2.5 bg-primary hover:bg-secondary text-slate-950 font-bold rounded-lg text-sm transition">
                Cari
            </button>
        </form>

        @if($faqs->isEmpty())
            <div class="p-12 text-center rounded-2xl border border-dashed border-slate-800 bg-slate-900/20 text-slate-400">
                <p class="text-base">Tidak ditemukan data FAQ.</p>
            </div>
        @else
            <!-- Accordion Details Summary List -->
            <div class="space-y-4">
                @foreach($faqs as $faq)
                    <details class="group bg-slate-900 border border-slate-850 rounded-2xl overflow-hidden [&_summary::-webkit-details-marker]:hidden transition-all duration-300">
                        <summary class="flex items-center justify-between px-6 py-5 cursor-pointer text-white font-bold hover:bg-slate-850/50 outline-none select-none transition">
                            <span class="pr-4">{{ $faq->question }}</span>
                            <span class="transition duration-300 group-open:-rotate-180 text-primary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </span>
                        </summary>
                        <div class="px-6 pb-6 pt-2 text-slate-350 text-base leading-relaxed border-t border-slate-850 bg-slate-900/40">
                            {!! clean($faq->answer) !!}
                        </div>
                    </details>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@push('schema')
    <x-schema-markup :schema="$schema" />
@endpush
