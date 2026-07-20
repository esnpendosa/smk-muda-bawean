@php
    $depth = $depth ?? 0;
    $isNew = $isNew ?? false;
@endphp

<div class="comment-node mt-6 relative group transition duration-300 {{ $isNew ? 'bg-green-50/50 p-4 rounded-xl border border-green-100 animate-fade-in' : '' }}" id="comment-{{ $comment->id }}" data-depth="{{ $depth }}">
    <div class="flex items-start gap-4">
        <!-- Avatar -->
        <img class="w-10 h-10 rounded-full border border-gray-150 object-cover shadow-sm flex-shrink-0" 
             src="{{ $comment->avatar_url }}" 
             alt="{{ $comment->commenter_name }}">

        <div class="flex-grow min-w-0">
            <!-- Header -->
            <div class="flex flex-wrap items-center justify-between gap-1 mb-1">
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-gray-900 text-sm">
                        {{ $comment->commenter_name }}
                    </span>
                    @if($comment->user_id && $comment->user?->role === 'admin')
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800">
                            Staf Sekolah
                        </span>
                    @endif
                </div>
                <span class="text-xs text-gray-400">
                    {{ $comment->created_at->diffForHumans() }}
                </span>
            </div>

            <!-- Content -->
            <div class="text-gray-700 text-sm sm:text-base leading-relaxed break-words whitespace-pre-line comment-body" id="comment-body-{{ $comment->id }}">
                {{ $comment->content }}
            </div>

            <!-- Actions (Upvote, Reply, Collapse) -->
            <div class="flex items-center gap-4 mt-3 text-xs text-gray-500 font-medium">
                <!-- Upvote Button -->
                <button type="button" 
                        onclick="upvoteComment({{ $comment->id }})" 
                        id="upvote-btn-{{ $comment->id }}"
                        class="flex items-center gap-1 hover:text-green-600 transition group/btn {{ session()->has('upvoted_comments.' . $comment->id) ? 'text-green-600 font-semibold' : '' }}">
                    <svg class="w-4 h-4 transition group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                    </svg>
                    <span id="upvotes-count-{{ $comment->id }}">{{ $comment->upvotes }}</span>
                </button>

                <!-- Reply Button -->
                <button type="button" 
                        onclick="showReplyForm({{ $comment->id }})" 
                        class="flex items-center gap-1 hover:text-green-600 transition group/btn">
                    <svg class="w-4 h-4 transition group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                    </svg>
                    <span>Balas</span>
                </button>

                <!-- Collapse Thread if there are replies -->
                @if($comment->replies->count() > 0)
                    <button type="button" 
                            onclick="toggleThread({{ $comment->id }})" 
                            id="collapse-btn-{{ $comment->id }}"
                            class="text-gray-400 hover:text-gray-600 transition flex items-center gap-1">
                        <span>[-] Sembunyikan</span>
                    </button>
                @endif
            </div>

            <!-- Dynamic Reply Form Container -->
            <div id="reply-form-container-{{ $comment->id }}" class="mt-4 hidden"></div>

            <!-- Nested Replies -->
            @if($comment->replies->count() > 0)
                <div class="relative pl-4 sm:pl-6 mt-2 border-l border-gray-100 transition-all duration-300" id="replies-container-{{ $comment->id }}">
                    @foreach($comment->replies as $reply)
                        @include('public.berita.partials.comment-item', [
                            'comment' => $reply,
                            'depth' => $depth + 1
                        ])
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
