@extends('layouts.app')

@section('content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto sm:px-0">
        
        <div class="flex justify-between items-center mb-6 px-4">
            <h2 class="text-xl font-bold text-gray-800 tracking-tight">Nomad Feed</h2>
            @if(auth()->user()->profile->role !== 'viewer')
            <a href="{{ route('posts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full shadow-md transition text-sm flex items-center gap-2">
                <span>+</span> New Post
            </a>
            @endif
        </div>

        <div class="space-y-6">
            @foreach ($posts as $post)
                <div class="bg-white border border-gray-200 sm:rounded-xl shadow-sm overflow-hidden">
                    
                    <div class="p-3 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('users.show', $post->user->profile->username) }}" class="block">
                                @if($post->user->profile->profile_photo)
                                    <img src="{{ asset($post->user->profile->profile_photo) }}" alt="{{ $post->user->name }}" class="h-9 w-9 rounded-full object-cover border border-gray-200">
                                @else
                                    <div class="h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border border-indigo-200 text-sm">
                                        {{ substr($post->user->name, 0, 1) }}
                                    </div>
                                @endif
                            </a>
                            
                            <div>
                                <a href="{{ route('users.show', $post->user->profile->username) }}" class="font-bold text-gray-900 text-sm hover:underline block leading-tight">
                                    {{ $post->user->profile->username }}
                                </a>
                                @if($post->country)
                                    <span class="text-xs text-gray-500 block leading-tight">
                                        {{ $post->country->name }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if(auth()->id() !== $post->user_id)
                            <button 
                                onclick="toggleFollow({{ $post->user_id }}, this)"
                                class="text-xs font-bold transition px-3 py-1 rounded-md {{ auth()->user()->follows->contains($post->user_id) ? 'bg-gray-100 text-gray-600' : 'bg-blue-50 text-blue-600' }}">
                                {{ auth()->user()->follows->contains($post->user_id) ? 'Unfollow' : 'Follow' }}
                            </button>
                        @endif
                    </div>

                    <div class="px-3 pb-2">
                        <a href="{{ route('posts.show', $post->id) }}" class="font-bold text-gray-900 text-lg hover:text-blue-600 transition block leading-tight">
                            {{ $post->title }}
                        </a>
                    </div>

                    @if($post->media->count() > 0)
                        <div class="relative bg-white group" style="height: 500px; width: 100%; position: relative;">
                            
                            <div id="carousel-{{ $post->id }}" 
                                 class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth"
                                 style="height: 100%; width: 100%; display: flex; overflow-x: auto; scroll-behavior: smooth; scrollbar-width: none;">
                                
                                @foreach($post->media as $media)
                                    <div class="snap-center relative flex items-center justify-center bg-white"
                                         style="min-width: 100%; flex: 0 0 100%; height: 100%;">
                                        
                                        <a href="{{ route('posts.show', $post->id) }}" class="w-full h-full flex items-center justify-center">
                                            @if($media->file_type === 'video')
                                                <video controls style="width: 100%; height: 100%; object-fit: contain;">
                                                    <source src="{{ asset($media->file_path) }}" type="video/mp4">
                                                </video>
                                            @else
                                                <img src="{{ asset($media->file_path) }}" 
                                                     alt="Post media" 
                                                     style="width: 100%; height: 100%; object-fit: cover;">
                                            @endif
                                        </a>
                                    </div>
                                @endforeach
                            </div>

                            @if($post->media->count() > 1)
                                <button onclick="scrollCarousel({{ $post->id }}, -1)" 
                                    style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); z-index: 50; background: rgba(0,0,0,0.6); color: white; border-radius: 50%; padding: 8px; cursor: pointer;">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="height: 20px; width: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>

                                <button onclick="scrollCarousel({{ $post->id }}, 1)" 
                                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); z-index: 50; background: rgba(0,0,0,0.6); color: white; border-radius: 50%; padding: 8px; cursor: pointer;">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="height: 20px; width: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>

                                <div style="position: absolute; top: 15px; right: 15px; background: rgba(0,0,0,0.7); color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; z-index: 50;">
                                    <span id="counter-{{ $post->id }}">1</span>/{{ $post->media->count() }}
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="p-3">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-4">
                                <button onclick="toggleLike('post', {{ $post->id }}, this)" class="focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 transition {{ $post->likes->contains('user_id', auth()->id()) ? 'text-red-500 fill-current' : 'text-gray-900 hover:text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </button>

                                <button onclick="toggleCommentBox({{ $post->id }})" class="focus:outline-none text-gray-900 hover:text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="font-bold text-sm text-gray-900 mb-1">
                            <span id="post-like-count-{{ $post->id }}">{{ $post->likes->count() }}</span> likes
                        </div>

                        <div class="mb-2 text-sm leading-snug">
                            <a href="{{ route('users.show', $post->user->profile->username) }}" class="font-bold text-gray-900 mr-1 hover:underline">
                                {{ $post->user->name }}
                            </a>
                            <span class="text-gray-800">{{ Str::limit($post->description, 150) }}</span>
                        </div>

                        <a href="{{ route('posts.show', $post->id) }}" class="text-gray-500 text-sm mb-2 block" id="view-comments-link-{{ $post->id }}">
                            View all <span id="post-comment-count-{{ $post->id }}">{{ $post->comments->count() }}</span> comments
                        </a>
                        
                        <p class="text-[10px] text-gray-400 uppercase tracking-wide mb-3">
                            {{ $post->created_at->diffForHumans() }}
                        </p>

                        <div id="comment-box-{{ $post->id }}" class="hidden pt-2 border-t border-gray-100">
                            <form onsubmit="submitComment(event, {{ $post->id }})" class="flex gap-2">
                                <input type="text" id="comment-input-{{ $post->id }}" 
                                    class="flex-1 bg-transparent text-sm border-none focus:ring-0 px-0 placeholder-gray-400 h-8" 
                                    placeholder="Add a comment..." autocomplete="off">
                                <button type="submit" class="text-blue-500 font-semibold text-sm hover:text-blue-700 disabled:opacity-50">Post</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 px-4">
            {{ $posts->links() }}
        </div>
    </div>
</div>

<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // 1. CAROUSEL
    function scrollCarousel(postId, direction) {
        const container = document.getElementById(`carousel-${postId}`);
        const counter = document.getElementById(`counter-${postId}`);
        const scrollAmount = container.clientWidth;
        
        container.scrollBy({ left: scrollAmount * direction, behavior: 'smooth' });

        setTimeout(() => {
            const index = Math.round(container.scrollLeft / container.clientWidth) + 1;
            if(counter) {
                const total = parseInt(counter.nextSibling.textContent.replace('/', ''));
                let validIndex = Math.max(1, Math.min(index, total));
                counter.innerText = validIndex;
            }
        }, 300);
    }

    // 2. TOGGLE COMMENT BOX (Visual)
    function toggleCommentBox(postId) {
        const box = document.getElementById(`comment-box-${postId}`);
        const input = document.getElementById(`comment-input-${postId}`);
        box.classList.toggle('hidden');
        if (!box.classList.contains('hidden')) {
            input.focus();
        }
    }

    // 3. TOGGLE LIKE (AJAX)
    function toggleLike(type, id, btn) {
        btn.disabled = true;
        fetch(`/likes/${type}/${id}`, {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken }
        })
        .then(r => r.json())
        .then(d => {
            btn.disabled = false;
            if(d.success) {
                let countSpan = document.getElementById(`post-like-count-${id}`);
                if(countSpan) countSpan.innerText = d.count;
                
                let svg = btn.querySelector('svg');
                if(d.liked) {
                    svg.classList.remove('text-gray-900', 'hover:text-gray-600');
                    svg.classList.add('text-red-500', 'fill-current');
                } else {
                    svg.classList.remove('text-red-500', 'fill-current');
                    svg.classList.add('text-gray-900');
                }
            }
        });
    }

    // 4. TOGGLE FOLLOW (AJAX)
    function toggleFollow(userId, btn) {
        btn.disabled = true;
        fetch(`/users/${userId}/follow`, {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" }
        })
        .then(r => r.json())
        .then(d => {
            btn.disabled = false;
            if (d.success) {
                if (d.following) {
                    btn.innerText = "Unfollow";
                    btn.classList.remove('bg-blue-50', 'text-blue-600');
                    btn.classList.add('bg-gray-100', 'text-gray-600');
                } else {
                    btn.innerText = "Follow";
                    btn.classList.remove('bg-gray-100', 'text-gray-600');
                    btn.classList.add('bg-blue-50', 'text-blue-600');
                }
            }
        });
    }

    // 5. SUBMIT COMMENT (AJAX) - This was missing!
    function submitComment(event, postId) {
        event.preventDefault();
        const input = document.getElementById(`comment-input-${postId}`);
        const content = input.value;
        const countSpan = document.getElementById(`post-comment-count-${postId}`);

        if(content.trim() === '') return;

        fetch(`/posts/${postId}/comments`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({ content: content })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                input.value = '';
                // Update count instantly
                if(countSpan) {
                    countSpan.innerText = parseInt(countSpan.innerText) + 1;
                }
                // Visual Feedback
                let originalPlaceholder = input.placeholder;
                input.placeholder = "Comment posted!";
                setTimeout(() => input.placeholder = originalPlaceholder, 2000);
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endsection