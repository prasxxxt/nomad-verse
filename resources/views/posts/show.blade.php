@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="group inline-flex items-center text-gray-500 hover:text-blue-600 transition">
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
                        
                        <button 
                            onclick="toggleLike('post', {{ $post->id }}, this)" 
                            id="like-btn-post-{{ $post->id }}" 
                            class="group flex items-center gap-1 focus:outline-none transition transform active:scale-125">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                 class="h-8 w-8 transition-colors duration-300 {{ $post->likes->contains('user_id', auth()->id()) ? 'text-red-500 fill-current' : 'text-gray-600 group-hover:text-gray-800' }}" 
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
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
                    <span id="like-count-post-{{ $post->id }}">{{ $post->likes->count() }}</span> likes
                </div>

                <h1 class="text-2xl font-bold text-gray-900 mb-3">{{ $post->title }}</h1>
                <p class="text-gray-700 leading-relaxed whitespace-pre-line text-base mb-6">{{ $post->description }}</p>

                @if($post->country)
                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center gap-2">
                        <div class="bg-blue-50 rounded-lg px-3 py-1.5 border border-blue-100 flex items-center gap-2">
                            <span class="text-2xl">{{ $post->country->flag }}</span>
                            <div class="text-xs font-bold text-blue-900 uppercase tracking-wide">{{ $post->country->name }}</div>
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

            <div id="comments-list" class="space-y-6">
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

    function toggleLike(type, id, btn) {
        btn.disabled = true;

        fetch(`/likes/${type}/${id}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            if(data.success) {
                let countSpan = document.getElementById(`like-count-${type}-${id}`);
                if(countSpan) {
                    countSpan.innerText = data.count;
                    if(data.count > 0) countSpan.classList.remove('hidden');
                }

                let svg = btn.querySelector('svg');
                if(data.liked) {
                    svg.classList.remove('text-gray-600', 'text-gray-400');
                    svg.classList.add('text-red-500', 'fill-current');
                } else {
                    svg.classList.remove('text-red-500', 'fill-current');
                    svg.classList.add('text-gray-600'); // Default for Post
                    if(type === 'comment') svg.classList.add('text-gray-400'); // Default for Comment
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            btn.disabled = false;
        });
    }

    // 2. FOLLOW FUNCTION
    function toggleFollow(userId, btn) {
        btn.disabled = true;
        fetch(`/users/${userId}/follow`, {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" }
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            if (data.success) {
                if (data.following) {
                    btn.innerText = "Unfollow";
                    btn.classList.remove('text-blue-600', 'border-blue-200', 'bg-blue-50', 'hover:bg-blue-100');
                    btn.classList.add('text-gray-500', 'border-gray-300', 'hover:border-red-300', 'hover:text-red-500'); 
                } else {
                    btn.innerText = "Follow";
                    btn.classList.remove('text-gray-500', 'border-gray-300', 'hover:border-red-300', 'hover:text-red-500');
                    btn.classList.add('text-blue-600', 'border-blue-200', 'bg-blue-50', 'hover:bg-blue-100');
                }
            }
        })
        .catch(error => { console.error('Error:', error); btn.disabled = false; });
    }

    // 3. COMMENT SUBMIT
    document.getElementById('comment-form').addEventListener('submit', function(e) {
        e.preventDefault();

        let content = document.getElementById('comment-content').value;
        let list = document.getElementById('comments-list');
        let errorMsg = document.getElementById('comment-error');
        let noCommentsMsg = document.getElementById('no-comments-msg');
        let countSpan = document.getElementById('comment-count');

        if(content.trim() === '') {
            errorMsg.innerText = "Comment cannot be empty.";
            errorMsg.classList.remove('hidden');
            return;
        }

        fetch("{{ route('comments.store', $post->id) }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ content: content })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                document.getElementById('comment-content').value = '';
                errorMsg.classList.add('hidden');
                if(noCommentsMsg) noCommentsMsg.remove();

                let newCommentHtml = `
                    <div class="flex gap-4 animate-pulse bg-blue-50 p-3 rounded-lg transition-all duration-1000">
                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-xs font-bold text-indigo-700 flex-shrink-0">
                            ${data.user_name.charAt(0)}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-baseline justify-between">
                                <span class="font-bold text-gray-900 text-sm">${data.user_name}</span>
                                <span class="text-xs text-gray-400">Just now</span>
                            </div>
                            <p class="text-gray-700 text-sm mt-1 leading-relaxed">${data.comment.content}</p>
                            
                            <div class="mt-2">
                                <button onclick="toggleLike('comment', ${data.comment.id}, this)" id="like-btn-comment-${data.comment.id}" class="text-xs font-semibold flex items-center gap-1 hover:text-red-500 transition text-gray-400 group">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    <span id="like-count-comment-${data.comment.id}" class="hidden">0</span>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                list.insertAdjacentHTML('afterbegin', newCommentHtml);
                countSpan.innerText = parseInt(countSpan.innerText) + 1;
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>
@endsection