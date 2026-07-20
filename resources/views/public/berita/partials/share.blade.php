<div class="border-t border-b border-gray-100 py-4 my-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-2">
        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 10.742l4.828-2.414m0 0a3 3 0 10-3.62-4.148L5.063 6.6a3 3 0 100 4.8l4.829 2.415m0 0a3 3 0 103.62-4.148L8.684 13.24m0 0a3 3 0 103.62 4.148"></path>
        </svg>
        <span class="text-sm font-semibold text-gray-700">Bagikan Artikel Ini:</span>
    </div>
    
    <div class="flex flex-wrap items-center gap-2">
        <!-- WhatsApp -->
        <a href="https://api.whatsapp.com/send?text={{ rawurlencode($post->title . ' - ' . request()->url()) }}" 
           target="_blank" 
           rel="noopener noreferrer"
           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold text-white bg-[#25D366] hover:bg-[#20ba59] active:bg-[#1da84f] transition hover:-translate-y-0.5 shadow-sm hover:shadow-md">
            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.746.953 3.71 1.458 5.704 1.459h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            <span>WhatsApp</span>
        </a>

        <!-- Facebook -->
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
           target="_blank" 
           rel="noopener noreferrer"
           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold text-white bg-[#1877F2] hover:bg-[#166fe5] active:bg-[#1465cf] transition hover:-translate-y-0.5 shadow-sm hover:shadow-md">
            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
            <span>Facebook</span>
        </a>

        <!-- X (formerly Twitter) -->
        <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(request()->url()) }}" 
           target="_blank" 
           rel="noopener noreferrer"
           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold text-white bg-[#0F1419] hover:bg-[#1d2226] active:bg-[#000000] transition hover:-translate-y-0.5 shadow-sm hover:shadow-md">
            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
            </svg>
            <span>X</span>
        </a>

        <!-- Copy Link Button -->
        <button type="button" 
                onclick="copyArticleLink(this)"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 active:bg-gray-300 transition hover:-translate-y-0.5 shadow-sm hover:shadow-md"
                data-url="{{ request()->url() }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
            </svg>
            <span class="btn-text">Salin Link</span>
        </button>
    </div>
</div>

<script>
function copyArticleLink(btn) {
    const url = btn.getAttribute('data-url');
    const textSpan = btn.querySelector('.btn-text');
    const originalText = textSpan.textContent;

    navigator.clipboard.writeText(url).then(() => {
        textSpan.textContent = 'Tersalin!';
        btn.classList.add('bg-green-50', 'text-green-700', 'border-green-200');
        
        setTimeout(() => {
            textSpan.textContent = originalText;
            btn.classList.remove('bg-green-50', 'text-green-700', 'border-green-200');
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy text: ', err);
    });
}
</script>
