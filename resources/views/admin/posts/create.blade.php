@extends('layouts.admin')
@section('breadcrumbs')
    <nav class="text-sm"><ol class="flex items-center gap-1.5 text-slate-400"><li><a href="{{ route('admin.posts.index') }}" class="hover:text-white">Berita</a></li><li>/</li><li class="text-white font-semibold">Tambah</li></ol></nav>
@endsection
@section('content')
<div class="max-w-3xl space-y-6">
    <h1 class="text-2xl font-bold text-white">Tambah Berita Baru</h1>
    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" class="bg-slate-900 border border-slate-800 rounded-2xl p-8 space-y-5">
        @csrf
        @if($errors->any())<div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm"><ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
        <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Judul *</label><input type="text" name="title" required value="{{ old('title') }}" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 focus:border-blue-500 rounded-xl text-white text-sm outline-none transition"></div>
        <div>
            <label class="block text-xs font-semibold text-slate-400 mb-1.5">Konten *</label>
            <textarea id="content-editor" name="content" required class="w-full px-4 py-3 bg-slate-950 border border-slate-700 focus:border-blue-500 rounded-xl text-white text-sm outline-none transition">{{ old('content') }}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Status *</label><select name="status" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none"><option value="draft">Draft</option><option value="published">Published</option></select></div>
            <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Tanggal Publikasi</label><input type="datetime-local" name="published_at" value="{{ old('published_at') }}" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none"></div>
        </div>
        <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Thumbnail (jpg,jpeg,png,webp — max 10MB, otomatis dikompres)</label><input type="file" name="thumbnail" accept=".jpg,.jpeg,.png,.webp" class="w-full text-slate-400 text-sm"></div>
        <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Meta Title (max 60)</label><input type="text" name="meta_title" value="{{ old('meta_title') }}" maxlength="60" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none"></div>
        <div><label class="block text-xs font-semibold text-slate-400 mb-1.5">Meta Description (max 160)</label><textarea name="meta_description" rows="2" maxlength="160" class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm outline-none">{{ old('meta_description') }}</textarea></div>
        <div class="flex gap-3 pt-2"><button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition">Simpan Berita</button><a href="{{ route('admin.posts.index') }}" class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white font-bold rounded-xl transition">Batal</a></div>
    </form>
</div>

<!-- Summernote Styles & Scripts -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<style>
    /* Summernote dark mode custom styles to match AdminMuda theme */
    .note-editor.note-frame {
        border: 1px solid #334155 !important; /* border-slate-700 */
        background-color: #020617 !important; /* bg-slate-950 */
        border-radius: 12px !important;
        overflow: hidden;
    }
    .note-toolbar {
        background-color: #0f172a !important; /* bg-slate-900 */
        border-bottom: 1px solid #334155 !important;
        padding: 6px 10px !important;
    }
    .note-btn {
        background-color: #1e293b !important; /* bg-slate-800 */
        border: 1px solid #334155 !important;
        color: #f8fafc !important; /* text-slate-50 */
        padding: 5px 10px !important;
        border-radius: 6px !important;
    }
    .note-btn:hover, .note-btn:focus, .note-btn.active {
        background-color: #334155 !important;
        color: #ffffff !important;
    }
    .note-btn-group .note-btn {
        border-radius: 0 !important;
    }
    .note-btn-group .note-btn:first-child {
        border-top-left-radius: 6px !important;
        border-bottom-left-radius: 6px !important;
    }
    .note-btn-group .note-btn:last-child {
        border-top-right-radius: 6px !important;
        border-bottom-right-radius: 6px !important;
    }
    .note-editable {
        background-color: #020617 !important;
        color: #f8fafc !important;
        min-height: 380px;
        font-family: inherit;
        font-size: 14px;
        line-height: 1.6;
    }
    .note-editable p {
        margin-bottom: 1rem;
    }
    .note-dropdown-menu {
        background-color: #0f172a !important;
        border: 1px solid #334155 !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5) !important;
        border-radius: 8px !important;
    }
    .note-dropdown-item {
        color: #cbd5e1 !important;
    }
    .note-dropdown-item:hover {
        background-color: #1e293b !important;
        color: #ffffff !important;
    }
    .note-modal-content {
        background-color: #0f172a !important;
        color: #f8fafc !important;
        border: 1px solid #334155 !important;
        border-radius: 16px !important;
    }
    .note-modal-header {
        border-bottom: 1px solid #334155 !important;
        padding: 15px !important;
    }
    .note-modal-title {
        color: #ffffff !important;
        font-weight: bold !important;
    }
    .note-modal-footer {
        border-top: 1px solid #334155 !important;
        padding: 15px !important;
    }
    .note-form-label {
        color: #cbd5e1 !important;
        font-size: 12px !important;
        font-weight: 600 !important;
    }
    .note-input {
        background-color: #020617 !important;
        color: #f8fafc !important;
        border: 1px solid #334155 !important;
        border-radius: 8px !important;
        padding: 8px 12px !important;
        outline: none !important;
    }
    .note-input:focus {
        border-color: #3b82f6 !important;
    }
    .note-status-output {
        color: #64748b !important;
    }
    .note-popover {
        display: none !important;
    }
</style>

<script>
    $(document).ready(function() {
        $('#content-editor').summernote({
            placeholder: 'Tulis konten berita di sini (bisa copas gambar, atur format teks, dll)...',
            tabsize: 2,
            height: 380,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onChange: function(contents) {
                    $('#content-editor').val(contents);
                },
                onImageUpload: function(files) {
                    // Upload gambar ke server, bukan base64
                    var formData = new FormData();
                    formData.append('file', files[0]);
                    formData.append('_token', '{{ csrf_token() }}');

                    $.ajax({
                        url: '/admin/upload-image',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            // Sisipkan gambar dengan URL permanen dari server
                            $('#content-editor').summernote('insertImage', response.url);
                        },
                        error: function() {
                            alert('Gagal upload gambar. Pastikan ukuran file tidak lebih dari 5MB.');
                        }
                    });
                }
            }
        });
    });
</script>
@endsection
