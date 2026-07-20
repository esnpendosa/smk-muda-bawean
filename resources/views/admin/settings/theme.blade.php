@extends('layouts.admin')
@section('breadcrumbs')<nav class="text-sm"><ol class="flex items-center gap-1.5 text-slate-400"><li><a href="{{ route('admin.settings.index') }}" class="hover:text-white">Pengaturan</a></li><li>/</li><li class="text-white font-semibold">Tema Warna</li></ol></nav>@endsection
@section('content')
<div class="max-w-2xl space-y-8">
    <h1 class="text-2xl font-bold text-white">Tema Warna</h1>
    <div class="flex gap-3 border-b border-slate-800 pb-4">
        <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-bold rounded-lg transition">Info Sekolah</a>
        <a href="{{ route('admin.settings.seo') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-bold rounded-lg transition">SEO</a>
        <a href="{{ route('admin.settings.theme') }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg">Tema Warna</a>
    </div>
    <form action="{{ route('admin.settings.theme.update') }}" method="POST" class="bg-slate-900 border border-slate-800 rounded-2xl p-8 space-y-5">
        @csrf
        @if($errors->any())<div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm"><ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
        <div>
            <label for="color_primary" class="block text-xs font-semibold text-slate-400 mb-1.5">Warna Primer (Hex)</label>
            <div class="flex items-center gap-3">
                <input type="color" id="color_primary_picker" value="{{ $settings['color_primary'] ?? '#facc15' }}" class="w-12 h-10 rounded cursor-pointer border-0">
                <input type="text" id="color_primary" name="color_primary" data-color-preview="--color-primary" value="{{ old('color_primary', $settings['color_primary'] ?? '#facc15') }}" pattern="#[0-9A-Fa-f]{6}" class="flex-1 px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none font-mono">
            </div>
        </div>
        <div>
            <label for="color_secondary" class="block text-xs font-semibold text-slate-400 mb-1.5">Warna Sekunder (Hex)</label>
            <div class="flex items-center gap-3">
                <input type="color" id="color_secondary_picker" value="{{ $settings['color_secondary'] ?? '#eab308' }}" class="w-12 h-10 rounded cursor-pointer border-0">
                <input type="text" id="color_secondary" name="color_secondary" data-color-preview="--color-secondary" value="{{ old('color_secondary', $settings['color_secondary'] ?? '#eab308') }}" pattern="#[0-9A-Fa-f]{6}" class="flex-1 px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none font-mono">
            </div>
        </div>
        <div>
            <label for="color_accent" class="block text-xs font-semibold text-slate-400 mb-1.5">Warna Aksen (Hex)</label>
            <div class="flex items-center gap-3">
                <input type="color" id="color_accent_picker" value="{{ $settings['color_accent'] ?? '#f59e0b' }}" class="w-12 h-10 rounded cursor-pointer border-0">
                <input type="text" id="color_accent" name="color_accent" data-color-preview="--color-accent" value="{{ old('color_accent', $settings['color_accent'] ?? '#f59e0b') }}" pattern="#[0-9A-Fa-f]{6}" class="flex-1 px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none font-mono">
            </div>
        </div>
        <!-- Live Preview -->
        <div class="p-6 rounded-xl bg-slate-950 border border-slate-700 space-y-3">
            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Preview Komponen</p>
            <button id="preview-btn" type="button" style="background-color: {{ $settings['color_primary'] ?? '#facc15' }};" class="px-6 py-2.5 font-bold rounded-lg text-slate-950 text-sm transition">Tombol Primer</button>
        </div>
        <div><button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition">Simpan Tema</button></div>
    </form>
</div>
<script>
// Live color preview
document.querySelectorAll('[data-color-preview]').forEach(input => {
    const variable = input.dataset.colorPreview;
    const pickerId = input.id + '_picker';
    const picker = document.getElementById(pickerId);
    const previewBtn = document.getElementById('preview-btn');
    const syncAll = () => {
        const val = input.value;
        if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
            document.documentElement.style.setProperty(variable, val);
            if (picker) picker.value = val;
            if (variable === '--color-primary' && previewBtn) previewBtn.style.backgroundColor = val;
        }
    };
    input.addEventListener('input', syncAll);
    if (picker) {
        picker.addEventListener('input', () => { input.value = picker.value; syncAll(); });
    }
});
</script>
@endsection
