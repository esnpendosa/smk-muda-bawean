<div class="mt-12 bg-white border border-gray-150 rounded-2xl p-6 sm:p-10 shadow-sm relative overflow-hidden" id="comments-section">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-green-500/5 via-transparent to-transparent pointer-events-none"></div>
    
    <div class="relative z-10">
        <!-- Section Header -->
        <div class="flex items-center justify-between border-b border-gray-100 pb-5 mb-8">
            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 flex items-center gap-2.5">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <span>Diskusi ({{ $post->comments()->count() + $post->comments()->has('replies')->count() }})</span>
            </h3>
        </div>

        <!-- Alert Notification -->
        <div id="comment-alert" class="hidden mb-6 p-4 rounded-xl text-sm transition-all duration-300"></div>

        <!-- Root Comment Form -->
        <div class="mb-10 bg-gray-50/50 p-5 rounded-2xl border border-gray-100">
            <h4 class="text-sm font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                Tulis Komentar
            </h4>
            
            <form id="root-comment-form" onsubmit="submitComment(event)" action="{{ route('berita.comments.store', $post->slug) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="parent_id" value="">

                @guest
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                            <input type="text" name="name" required placeholder="Masukkan nama Anda" 
                                   class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:border-green-500 focus:ring-1 focus:ring-green-500 text-sm transition shadow-sm">
                            <span class="text-xs text-red-500 mt-1 hidden field-error" data-field="name"></span>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Email (tidak dipublikasikan)</label>
                            <input type="email" name="email" required placeholder="name@example.com" 
                                   class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:border-green-500 focus:ring-1 focus:ring-green-500 text-sm transition shadow-sm">
                            <span class="text-xs text-red-500 mt-1 hidden field-error" data-field="email"></span>
                        </div>
                    </div>
                @else
                    <div class="text-sm text-gray-600 flex items-center gap-2 mb-2">
                        <span class="font-medium text-gray-800">Komentar sebagai:</span>
                        <span class="px-2 py-0.5 rounded-md bg-green-50 text-green-700 font-semibold">{{ Auth::user()->name }}</span>
                    </div>
                @endguest

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Komentar Anda</label>
                    <textarea name="content" rows="4" required placeholder="Tulis tanggapan atau opini Anda secara santun di sini..." 
                              class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:border-green-500 focus:ring-1 focus:ring-green-500 text-sm transition shadow-sm resize-none"></textarea>
                    <span class="text-xs text-red-500 mt-1 hidden field-error" data-field="content"></span>
                </div>

                <div class="flex justify-end">
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white text-sm font-semibold rounded-xl transition shadow-md hover:shadow-lg disabled:opacity-50">
                        <span>Kirim Komentar</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Comments List -->
        <div class="space-y-6" id="comments-list-container">
            @forelse($post->comments as $comment)
                @include('public.berita.partials.comment-item', ['comment' => $comment])
            @empty
                <div class="text-center py-12" id="no-comments-placeholder">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-gray-500 text-sm">Belum ada komentar. Jadilah yang pertama memberikan tanggapan!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Inline template for replies -->
<template id="reply-form-template">
    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 mt-3 animate-fade-in">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Balas Komentar</span>
            <button type="button" onclick="cancelReply(this)" class="text-xs text-red-500 hover:text-red-700 font-semibold">Batal</button>
        </div>
        <form onsubmit="submitComment(event)" action="{{ route('berita.comments.store', $post->slug) }}" method="POST" class="space-y-3">
            @csrf
            <input type="hidden" name="parent_id" value="">
            
            @guest
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <input type="text" name="name" required placeholder="Nama Lengkap" 
                               class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg focus:border-green-500 focus:ring-1 focus:ring-green-500 text-xs transition">
                        <span class="text-xs text-red-500 mt-1 hidden field-error" data-field="name"></span>
                    </div>
                    <div>
                        <input type="email" name="email" required placeholder="Email Anda" 
                               class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg focus:border-green-500 focus:ring-1 focus:ring-green-500 text-xs transition">
                        <span class="text-xs text-red-500 mt-1 hidden field-error" data-field="email"></span>
                    </div>
                </div>
            @endguest

            <div>
                <textarea name="content" rows="3" required placeholder="Tulis balasan Anda..." 
                          class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg focus:border-green-500 focus:ring-1 focus:ring-green-500 text-xs transition resize-none"></textarea>
                <span class="text-xs text-red-500 mt-1 hidden field-error" data-field="content"></span>
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition shadow disabled:opacity-50">
                    Kirim Balasan
                </button>
            </div>
        </form>
    </div>
</template>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fadeIn 0.4s ease forwards;
}
</style>

<script>
function showAlert(message, type = 'success') {
    const alertBox = document.getElementById('comment-alert');
    alertBox.innerHTML = message;
    alertBox.className = `p-4 rounded-xl text-sm transition-all duration-300 ${
        type === 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200'
    }`;
    alertBox.classList.remove('hidden');
    
    // Auto scroll to alert
    alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    
    setTimeout(() => {
        alertBox.classList.add('hidden');
    }, 5000);
}

function showReplyForm(commentId) {
    // Hide any other active reply form container first
    document.querySelectorAll('[id^="reply-form-container-"]').forEach(container => {
        container.innerHTML = '';
        container.classList.add('hidden');
    });

    const container = document.getElementById(`reply-form-container-${commentId}`);
    const template = document.getElementById('reply-form-template');
    
    if (container && template) {
        const clone = template.content.cloneNode(true);
        // Set the parent_id value inside the cloned form
        clone.querySelector('input[name="parent_id"]').value = commentId;
        
        container.appendChild(clone);
        container.classList.remove('hidden');
        
        // Focus on the content textarea inside the form
        container.querySelector('textarea[name="content"]').focus();
    }
}

function cancelReply(button) {
    const container = button.closest('[id^="reply-form-container-"]');
    if (container) {
        container.innerHTML = '';
        container.classList.add('hidden');
    }
}

function toggleThread(commentId) {
    const repliesContainer = document.getElementById(`replies-container-${commentId}`);
    const btn = document.getElementById(`collapse-btn-${commentId}`);
    if (repliesContainer && btn) {
        if (repliesContainer.classList.contains('hidden')) {
            repliesContainer.classList.remove('hidden');
            btn.innerHTML = '<span>[-] Sembunyikan</span>';
        } else {
            repliesContainer.classList.add('hidden');
            btn.innerHTML = '<span>[+] Tampilkan Balasan</span>';
        }
    }
}

function upvoteComment(commentId) {
    const btn = document.getElementById(`upvote-btn-${commentId}`);
    const countSpan = document.getElementById(`upvotes-count-{{ $comment->id ?? 'xxx' }}`.replace('xxx', commentId));
    
    if (!btn || !countSpan) return;

    fetch(`/comments/${commentId}/upvote`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            countSpan.textContent = data.upvotes;
            btn.classList.add('text-green-600', 'font-semibold');
            showAlert(data.message, 'success');
        } else {
            showAlert(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error upvoting:', error);
        showAlert('Terjadi kesalahan saat memberikan upvote.', 'error');
    });
}

function submitComment(event) {
    event.preventDefault();
    const form = event.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const formData = new FormData(form);
    
    // Clear errors
    form.querySelectorAll('.field-error').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });
    
    if (submitBtn) submitBtn.disabled = true;
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        if (submitBtn) submitBtn.disabled = false;
        
        if (response.status === 422) {
            return response.json().then(data => {
                // Show validation errors
                Object.keys(data.errors).forEach(field => {
                    const errorSpan = form.querySelector(`.field-error[data-field="${field}"]`);
                    if (errorSpan) {
                        errorSpan.textContent = data.errors[field][0];
                        errorSpan.classList.remove('hidden');
                    }
                });
                throw new Error('Validation failed');
            });
        }
        
        if (!response.ok) {
            throw new Error('Server error');
        }
        
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Clear inputs
            form.querySelector('textarea[name="content"]').value = '';
            
            // Check if this is a reply or top-level comment
            const parentId = form.querySelector('input[name="parent_id"]').value;
            
            // Hide reply form if it is a reply
            if (parentId) {
                cancelReply(form);
            }
            
            // Append the new comment HTML to the DOM
            if (parentId) {
                let repliesContainer = document.getElementById(`replies-container-${parentId}`);
                if (!repliesContainer) {
                    // Create a replies container if it doesn't exist
                    const parentNode = document.querySelector(`#comment-${parentId} .flex-grow`);
                    repliesContainer = document.createElement('div');
                    repliesContainer.id = `replies-container-${parentId}`;
                    repliesContainer.className = 'relative pl-4 sm:pl-6 mt-2 border-l border-gray-100 transition-all duration-300';
                    parentNode.appendChild(repliesContainer);
                    
                    // Add collapse button to the parent actions
                    const actionsDiv = parentNode.querySelector('.text-xs.text-gray-500.font-medium');
                    const collapseBtn = document.createElement('button');
                    collapseBtn.type = 'button';
                    collapseBtn.id = `collapse-btn-${parentId}`;
                    collapseBtn.onclick = () => toggleThread(parentId);
                    collapseBtn.className = 'text-gray-400 hover:text-gray-600 transition flex items-center gap-1';
                    collapseBtn.innerHTML = '<span>[-] Sembunyikan</span>';
                    actionsDiv.appendChild(collapseBtn);
                }
                repliesContainer.insertAdjacentHTML('beforeend', data.html);
            } else {
                // Top-level comment
                const listContainer = document.getElementById('comments-list-container');
                const placeholder = document.getElementById('no-comments-placeholder');
                if (placeholder) {
                    placeholder.remove();
                }
                listContainer.insertAdjacentHTML('afterbegin', data.html);
            }
            
            showAlert(data.message, 'success');
        } else {
            showAlert(data.message, 'error');
        }
    })
    .catch(error => {
        if (submitBtn) submitBtn.disabled = false;
        if (error.message !== 'Validation failed') {
            console.error('Error submitting comment:', error);
            showAlert('Gagal mengirim komentar. Silakan coba beberapa saat lagi.', 'error');
        }
    });
}
</script>
