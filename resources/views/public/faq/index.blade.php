@extends('layouts.public')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12">
    <!-- Breadcrumb -->
    <x-breadcrumb :breadcrumbs="[
        ['label' => 'FAQ', 'url' => '']
    ]" />

    <div class="mt-8 space-y-8">
        <div class="space-y-2">
            <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 tracking-tight">Pertanyaan Umum (FAQ)</h1>
            <p class="text-sm text-gray-500">Temukan jawaban atas berbagai pertanyaan umum tentang SMK Muda Bawean.</p>
        </div>

        <!-- Search Bar -->
        <form action="{{ route('faq.index') }}" method="GET" class="bg-white border border-gray-150 p-4 rounded-xl flex gap-4 shadow-sm">
            <input type="text" name="search" placeholder="Cari pertanyaan atau jawaban..." value="{{ request('search') }}"
                   class="flex-1 px-4 py-2.5 bg-gray-50 border border-gray-200 focus:border-green-600 focus:ring-1 focus:ring-green-600 rounded-lg text-gray-800 text-sm outline-none transition">
            <button type="submit" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg text-sm transition">
                Cari
            </button>
        </form>

        @if($faqs->isEmpty())
            <div class="p-12 text-center rounded-2xl border border-dashed border-gray-200 bg-white text-gray-500">
                <p class="text-base">Tidak ditemukan data FAQ.</p>
            </div>
        @else
            <!-- Accordion Details Summary List -->
            <div class="space-y-4">
                @foreach($faqs as $faq)
                    <details class="group bg-white border border-gray-150 rounded-2xl overflow-hidden [&_summary::-webkit-details-marker]:hidden transition-all duration-300 shadow-sm">
                        <summary class="flex items-center justify-between px-6 py-5 cursor-pointer text-gray-800 font-bold hover:bg-gray-50/50 outline-none select-none transition">
                            <span class="pr-4">{{ $faq->question }}</span>
                            <span class="transition duration-300 group-open:-rotate-180 text-green-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </span>
                        </summary>
                        <div class="px-6 pb-6 pt-2 text-gray-650 text-base leading-relaxed border-t border-gray-150 bg-gray-50/50">
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
