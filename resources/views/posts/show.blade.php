@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-6 flex items-center">
            <a href="{{ route('dashboard') }}" class="group flex items-center text-gray-500 hover:text-blue-600 transition">
                <div class="bg-white p-2 rounded-full shadow-sm group-hover:shadow-md border border-gray-200 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span class="font-semibold">Back to Feed</span>
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-8">
            
            <div class="p-4 flex justify-between items-center border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <a href="{{ route('users.show', $post->user->profile->username) }}" class="block">
                        @if($post->user->profile->profile_photo)
                            <img src="{{ asset($post->user->profile->profile_photo) }}" alt="{{ $post->user->name }}" class="h-10 w-10 rounded-full object-cover border border-gray-200">
                        @else
                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border border-indigo-200">
                                {{ substr($post->user->name, 0, 1) }}
                            </div>
                        @endif
                    </a>
                    <div>
                        <a href="{{ route('users.show', $post->user->profile->username) }}" class="font-bold text-gray-900 text-sm hover:underline">
                            {{ $post->user->name }}
                        </a>
                        <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                @if(auth()->id() !== $post->user_id)
                    <button 
                        onclick="toggleFollow({{ $post->user_id }}, this)"
                        class="text-xs font-bold transition px-4 py-1.5 rounded-full border {{ auth()->user()->follows->contains($post->user_id) ? 'text-gray-500 border-gray-300 hover:border-red-300 hover:text-red-500' : 'text-blue-600 border-blue-200 bg-blue-50 hover:bg-blue-100' }}">
                        {{ auth()->user()->follows->contains($post->user_id) ? 'Unfollow' : 'Follow' }}
                    </button>
                @endif
            </div>

            @if($post->media->count() > 0)
                <div class="space-y-1 bg-gray-100">
                    @foreach($post->media as $media)
                        <div class="w-full bg-black flex items-center justify-center">
                            @if($media->file_type === 'video')
                                <video controls class="w-full max-h-[600px] object-contain">
                                    <source src="{{ asset($media->file_path) }}" type="video/mp4">
                                </video>
                            @else
                                <img src="{{ asset($media->file_path) }}" 
                                     alt="Post media" 
                                     class="w-full max-h-[600px] object-contain">
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-4">
                        <button onclick="toggleLike('post', {{ $post->id }}, this)" id="like-btn-post-{{ $post->id }}" class="group flex items-center gap-1 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 transition {{ $post->likes->contains('user_id', auth()->id()) ? 'text-red-500 fill-current' : 'text-gray-600 group-hover:text-gray-800' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                        <button onclick="document.getElementById('comment-content').focus()" class="text-gray-600 hover:text-blue-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="font-bold text-gray-900 mb-2 text-lg">
                    <span id="post-like-count-{{ $post->id }}">{{ $post->likes->count() }}</span> likes
                </div>

                <h1 class="text-2xl font-bold text-gray-900 mb-3">{{ $post->title }}</h1>
                <p class="text-gray-700 leading-relaxed whitespace-pre-line text-base mb-6">{{ $post->description }}</p>

                @if($post->country && isset($countryIntel))
                    <div class="mt-6 border border-gray-100 rounded-xl overflow-hidden shadow-sm">
                        
                        <div class="bg-gradient-to-r from-violet-50 to-purple-50 p-5 relative overflow-hidden">
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-20 h-20 bg-purple-200 rounded-full blur-2xl opacity-40"></div>
                            
                            <div class="relative z-10">
                                <div class="flex items-center gap-2 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-violet-600 animate-pulse" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 4.5a.75.75 0 01.721.544l.813 2.846a3.75 3.75 0 002.576 2.576l2.846.813a.75.75 0 010 1.442l-2.846.813a3.75 3.75 0 00-2.576 2.576l-.813 2.846a.75.75 0 01-1.442 0l-.813-2.846a3.75 3.75 0 00-2.576-2.576l-2.846-.813a.75.75 0 010-1.442l2.846-.813a3.75 3.75 0 002.576-2.576l.813-2.846A.75.75 0 019 4.5zM6.97 11.03a.75.75 0 111.06 1.06l-1.06 1.06a.75.75 0 11-1.06-1.06l1.06-1.06z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-xs font-bold text-violet-700 uppercase tracking-wider">Gemini Insight: {{ $post->country->name }}</span>
                                </div>
                                
                                <p class="text-gray-800 text-sm leading-relaxed font-medium italic">
                                    "{{ $countryIntel['summary'] }}"
                                </p>
                            </div>
                        </div>

                        <div class="bg-white p-4 border-t border-gray-100 grid grid-cols-3 gap-4">
                            <div class="text-center">
                                <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Capital</p>
                                <p class="text-sm font-bold text-gray-900">{{ $countryIntel['capital'] }}</p>
                            </div>
                            
                            <div class="text-center border-l border-gray-100">
                                <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Population</p>
                                <p class="text-sm font-bold text-gray-900">{{ $countryIntel['population'] }}</p>
                            </div>

                            <div class="text-center border-l border-gray-100">
                                <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Currency</p>
                                <p class="text-sm font-bold text-gray-900">
                                    @if(!empty($countryIntel['currency']))
                                        {{ $countryIntel['currency']['code'] }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @can('update', $post)
                    <div class="mt-8 pt-4 border-t border-gray-100 flex justify-end gap-3">
                        <a href="{{ route('posts.edit', $post->id) }}" class="text-sm font-bold text-gray-600 hover:text-indigo-600 border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50 transition">Edit Post</a>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-sm font-bold text-red-500 hover:text-red-700 border border-red-200 px-4 py-2 rounded-lg hover:bg-red-50 transition">Delete</button>
                        </form>
                    </div>
                @endcan
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <span>Comments</span>
                <span class="bg-gray-100 text-gray-600 text-sm px-2 py-0.5 rounded-full" id="comment-count">{{ $post->comments->count() }}</span>
            </h3>

            @auth
                <div class="mb-8 flex gap-3">
                    <div class="flex-shrink-0">
                        @if(auth()->user()->profile->profile_photo)
                            <img src="{{ asset(auth()->user()->profile->profile_photo) }}" class="h-10 w-10 rounded-full object-cover">
                        @else
                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <form id="comment-form" class="flex-1">
                        @csrf
                        <textarea id="comment-content" class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" rows="2" placeholder="Add a comment..."></textarea>
                        <div class="flex justify-end mt-2">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full shadow-sm text-sm">Post</button>
                        </div>
                    </form>
                </div>
            @endauth

            <div id="comments-list" class="space-y-6 max-h-[600px] overflow-y-auto">
                @forelse($post->comments as $comment)
                    <div class="flex gap-4">
                        <a href="{{ route('users.show', $comment->user->profile->username) }}" class="flex-shrink-0">
                            @if($comment->user->profile->profile_photo)
                                <img src="{{ asset($comment->user->profile->profile_photo) }}" class="h-10 w-10 rounded-full object-cover">
                            @else
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                    {{ substr($comment->user->name, 0, 1) }}
                                </div>
                            @endif
                        </a>
                        <div class="flex-1">
                            <div class="flex items-baseline justify-between">
                                <a href="{{ route('users.show', $comment->user->profile->username) }}" class="font-bold text-gray-900 text-sm hover:underline">{{ $comment->user->name }}</a>
                                <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-gray-700 text-sm mt-1 leading-relaxed">{{ $comment->content }}</p>
                            
                            <div class="mt-2">
                                <button 
                                    onclick="toggleLike('comment', {{ $comment->id }}, this)" 
                                    id="like-btn-comment-{{ $comment->id }}"
                                    class="group flex items-center gap-1.5 text-xs font-semibold transition hover:text-red-500 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                         class="h-4 w-4 transition-colors duration-300 {{ $comment->likes->contains('user_id', auth()->id()) ? 'text-red-500 fill-current' : 'text-gray-400 group-hover:text-red-500' }}" 
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    <span id="like-count-comment-{{ $comment->id }}" class="text-gray-500 {{ $comment->likes->count() > 0 ? '' : 'hidden' }}">
                                        {{ $comment->likes->count() }}
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <p id="no-comments-msg" class="text-center text-gray-400 text-sm italic py-8">No comments yet. Be the first to share your thoughts!</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // 1. LIKE FUNCTION
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
                let countSpan = document.getElementById(`like-count-${type}-${id}`);
                if(countSpan) {
                    countSpan.innerText = d.count;
                    if(d.count > 0) countSpan.classList.remove('hidden');
                }
                let svg = btn.querySelector('svg');
                if(d.liked) {
                    svg.classList.remove('text-gray-600', 'text-gray-400');
                    svg.classList.add('text-red-500', 'fill-current');
                } else {
                    svg.classList.remove('text-red-500', 'fill-current');
                    svg.classList.add(type === 'post' ? 'text-gray-600' : 'text-gray-400');
                }
            }
        });
    }

    // 2. FOLLOW FUNCTION
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
                    btn.classList.remove('text-blue-600', 'border-blue-200', 'bg-blue-50', 'hover:bg-blue-100');
                    btn.classList.add('text-gray-500', 'border-gray-300', 'hover:border-red-300', 'hover:text-red-500'); 
                } else {
                    btn.innerText = "Follow";
                    btn.classList.remove('text-gray-500', 'border-gray-300', 'hover:border-red-300', 'hover:text-red-500');
                    btn.classList.add('text-blue-600', 'border-blue-200', 'bg-blue-50', 'hover:bg-blue-100');
                }
            }
        });
    }

    // 3. COMMENT SUBMIT
    document.getElementById('comment-form').addEventListener('submit', function(e) {
        e.preventDefault();
        let content = document.getElementById('comment-content').value;
        let list = document.getElementById('comments-list');
        let errorMsg = document.getElementById('comment-error');
        let noCommentsMsg = document.getElementById('no-comments-msg');
        let countSpan = document.getElementById('comment-count');

        if(content.trim() === '') return;

        fetch("{{ route('comments.store', $post->id) }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken },
            body: JSON.stringify({ content: content })
        })
        .then(r => r.json())
        .then(d => {
            if(d.success) {
                document.getElementById('comment-content').value = '';
                if(noCommentsMsg) noCommentsMsg.remove();

                let avatarHtml = '';
                if(d.user_avatar) {
                    avatarHtml = `<img src="${d.user_avatar}" class="h-10 w-10 rounded-full object-cover">`;
                } else {
                    avatarHtml = `<div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-xs font-bold text-indigo-700 flex-shrink-0">${d.user_name.charAt(0)}</div>`;
                }

                let newCommentHtml = `
                    <div class="flex gap-4 animate-pulse bg-blue-50 p-3 rounded-lg transition-all duration-1000">
                        ${avatarHtml}
                        <div class="flex-1">
                            <div class="flex items-baseline justify-between">
                                <span class="font-bold text-gray-900 text-sm">${d.user_name}</span>
                                <span class="text-xs text-gray-400">Just now</span>
                            </div>
                            <p class="text-gray-700 text-sm mt-1 leading-relaxed">${d.comment.content}</p>
                            <div class="mt-2">
                                <button onclick="toggleLike('comment', ${d.comment.id}, this)" id="like-btn-comment-${d.comment.id}" class="text-xs font-semibold flex items-center gap-1 hover:text-red-500 transition text-gray-400 group">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                    <span id="like-count-comment-${d.comment.id}" class="hidden">0</span>
                                </button>
                            </div>
                        </div>
                    </div>`;
                
                list.insertAdjacentHTML('afterbegin', newCommentHtml);
                countSpan.innerText = parseInt(countSpan.innerText) + 1;
            }
        });
    });
</script>
@endsection