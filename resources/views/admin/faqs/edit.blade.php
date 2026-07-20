@extends('layouts.admin')
@section('breadcrumbs')<nav class="text-sm"><ol class="flex items-center gap-1.5 text-slate-400"><li><a href="{{ route('admin.faqs.index') }}" class="hover:text-white">FAQ</a></li><li>/</li><li class="text-white font-semibold">Edit</li></ol></nav>@endsection
@section('content')
<div class="max-w-2xl space-y-6">
    <h1 class="text-2xl font-bold text-white">Edit FAQ</h1>
    <form action="{{ route('admin.faqs.update', $faq) }}" method="POST" class="bg-slate-900 border border-slate-800 rounded-2xl p-8 space-y-5">
        @csrf @method('PUT')
        @if($errors->any())<div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm"><ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
        <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Pertanyaan *</label><textarea name="question" required rows="3" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none">{{ old('question', $faq->question) }}</textarea></div>
        <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Jawaban *</label><textarea name="answer" required rows="6" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none">{{ old('answer', $faq->answer) }}</textarea></div>
        <div class="grid grid-cols-2 gap-4">
            <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Urutan</label><input type="number" name="order" value="{{ old('order', $faq->order) }}" min="0" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none"></div>
            <div class="flex items-center gap-3 pt-5"><input type="checkbox" id="is_active" name="is_active" value="1" {{ $faq->is_active ? 'checked' : '' }} class="w-4 h-4 accent-blue-500"><label for="is_active" class="text-sm text-slate-300 select-none">Aktifkan FAQ ini</label></div>
        </div>
        <div class="flex gap-3"><button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition">Simpan Perubahan</button><a href="{{ route('admin.faqs.index') }}" class="px-6 py-3 bg-slate-700 text-white font-bold rounded-xl transition">Batal</a></div>
    </form>
</div>
@endsection
