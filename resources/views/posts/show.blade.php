@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        
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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    
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
                                class="text-xs font-bold transition px-3 py-1 rounded-full border {{ auth()->user()->follows->contains($post->user_id) ? 'text-gray-500 border-gray-300 hover:border-red-300 hover:text-red-500' : 'text-blue-600 border-blue-200 bg-blue-50 hover:bg-blue-100' }}">
                                {{ auth()->user()->follows->contains($post->user_id) ? 'Unfollow' : 'Follow' }}
                            </button>
                        @endif
                    </div>

                    @if($post->image)
                        <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" class="w-full h-auto object-cover">
                    @endif

                    <div class="p-4">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-4">
                                <button onclick="toggleLike('post', {{ $post->id }}, this)" class="group flex items-center gap-1 focus:outline-none">
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

                        <div class="font-bold text-gray-900 mb-2">
                            <span id="post-like-count-{{ $post->id }}">{{ $post->likes->count() }}</span> likes
                        </div>

                        <h1 class="text-xl font-bold text-gray-900 mb-2">{{ $post->title }}</h1>
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $post->description }}</p>

                        @if($post->country)
                            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center gap-2">
                                <div class="bg-gray-50 rounded-lg p-2 border border-gray-200 flex items-center gap-2">
                                    <span class="text-2xl">{{ $post->country->flag }}</span>
                                    <div class="text-xs font-bold text-gray-700 uppercase tracking-wide">{{ $post->country->name }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @can('update', $post)
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 flex justify-between items-center">
                        <span class="text-sm text-gray-500">Manage this post</span>
                        <div class="flex gap-3">
                            <a href="{{ route('posts.edit', $post->id) }}" class="text-sm font-bold text-gray-600 hover:text-indigo-600 border border-gray-300 px-3 py-1 rounded hover:bg-gray-50 transition">Edit</a>
                            
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-sm font-bold text-red-500 hover:text-red-700 border border-red-200 px-3 py-1 rounded hover:bg-red-50 transition">Delete</button>
                            </form>
                        </div>
                    </div>
                @endcan
            </div>

            <div class="md:col-span-1">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm h-full flex flex-col sticky top-6">
                    <div class="p-4 border-b border-gray-100">
                        <h3 class="font-bold text-gray-800">Comments (<span id="comment-count">{{ $post->comments->count() }}</span>)</h3>
                    </div>

                    @auth
                        <div class="p-4 border-b border-gray-100 bg-gray-50">
                            <form id="comment-form" class="relative">
                                @csrf
                                <input type="text" id="comment-content" 
                                    class="w-full bg-white border border-gray-300 rounded-full pl-4 pr-14 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none shadow-sm" 
                                    placeholder="Add a comment..." autocomplete="off">
                                
                                <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-blue-600 font-bold text-sm hover:text-blue-800 px-2">
                                    Post
                                </button>
                            </form>
                            <p id="comment-error" class="text-red-500 text-xs mt-2 hidden text-center"></p>
                        </div>
                    @endauth

                    <div id="comments-list" class="flex-1 p-4 space-y-4 overflow-y-auto max-h-[500px]">
                        @forelse($post->comments as $comment)
                            <div class="flex gap-3">
                                <a href="{{ route('users.show', $comment->user->profile->username) }}" class="flex-shrink-0">
                                    @if($comment->user->profile->profile_photo)
                                        <img src="{{ asset($comment->user->profile->profile_photo) }}" class="h-8 w-8 rounded-full object-cover">
                                    @else
                                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                            {{ substr($comment->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </a>
                                <div class="flex-1">
                                    <div class="bg-gray-50 rounded-2xl rounded-tl-none p-3 text-sm text-gray-800">
                                        <a href="{{ route('users.show', $comment->user->profile->username) }}" class="font-bold hover:underline block mb-1">{{ $comment->user->name }}</a>
                                        {{ $comment->content }}
                                    </div>
                                    
                                    <div class="flex items-center gap-4 mt-1 pl-2">
                                        <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                        <button 
                                            onclick="toggleLike('comment', {{ $comment->id }}, this)" 
                                            class="text-xs font-semibold hover:text-red-500 flex items-center gap-1 {{ $comment->likes->contains('user_id', auth()->id()) ? 'text-red-500' : 'text-gray-500' }}">
                                            <span class="like-text">{{ $comment->likes->contains('user_id', auth()->id()) ? 'Unlike' : 'Like' }}</span>
                                            <span id="comment-like-count-{{ $comment->id }}" class="{{ $comment->likes->count() > 0 ? '' : 'hidden' }}">{{ $comment->likes->count() }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p id="no-comments-msg" class="text-center text-gray-400 text-sm py-10">No comments yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
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

                // Append new comment HTML
                let newCommentHtml = `
                    <div class="flex gap-3 animate-pulse bg-blue-50 p-2 rounded transition-all duration-1000">
                        <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-xs font-bold text-indigo-700 flex-shrink-0">
                            ${data.user_name.charAt(0)}
                        </div>
                        <div class="flex-1">
                            <div class="bg-white rounded-2xl rounded-tl-none p-3 text-sm text-gray-800 shadow-sm">
                                <span class="font-bold block mb-1">${data.user_name}</span>
                                ${data.comment.content}
                            </div>
                            <div class="flex items-center gap-4 mt-1 pl-2">
                                <span class="text-xs text-gray-400">Just now</span>
                                <button onclick="toggleLike('comment', ${data.comment.id}, this)" class="text-xs font-semibold text-gray-500 hover:text-red-500">
                                    Like <span id="comment-like-count-${data.comment.id}" class="hidden">0</span>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                list.insertAdjacentHTML('beforeend', newCommentHtml);
                list.scrollTop = list.scrollHeight; // Auto-scroll to bottom
                countSpan.innerText = parseInt(countSpan.innerText) + 1;
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>
@endsection