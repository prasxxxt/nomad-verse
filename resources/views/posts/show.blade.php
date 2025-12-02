@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                &larr; Back to Feed
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            
            @if($post->image)
                <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" class="w-full h-96 object-cover">
            @endif

            <div class="p-8">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $post->title }}</h1>
                        <p class="text-gray-500 text-sm mt-1">
                            Posted by <span class="font-semibold">{{ $post->user->name }}</span> 
                            &bull; {{ $post->created_at->diffForHumans() }}
                        </p>
                    </div>
                    
                    @if($post->country)
                        <div class="text-center bg-blue-50 px-4 py-2 rounded-lg border border-blue-100">
                            <div class="text-2xl">{{ $post->country->flag }}</div>
                            <div class="text-xs font-bold text-blue-800 uppercase tracking-wide">{{ $post->country->name }}</div>
                        </div>
                    @endif
                </div>

                <div class="mt-6 prose max-w-none text-gray-800">
                    {{ $post->description }}
                </div>

                <div class="mt-4">
                    <button 
                        onclick="toggleLike('post', {{ $post->id }})" 
                        id="like-btn-post-{{ $post->id }}" 
                        class="text-blue-500 font-bold border p-2 rounded hover:bg-blue-50">
                        {{ $post->likes->contains('user_id', auth()->id()) ? 'Unlike' : 'Like' }}
                        (<span id="like-count-post-{{ $post->id }}">{{ $post->likes->count() }}</span>)
                    </button>
                </div>

                @if(auth()->id() === $post->user_id)
                    <div class="flex gap-4 border-t pt-4 mt-6">
                        <a href="{{ route('posts.edit', $post->id) }}" class="text-yellow-600 hover:text-yellow-800 font-bold">Edit Post</a>
                        
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 font-bold">Delete Post</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-xl font-bold mb-4">Comments (<span id="comment-count">{{ $post->comments->count() }}</span>)</h3>

            <div id="comments-list" class="space-y-4 mb-6">
                @forelse($post->comments as $comment)
                    <div class="border-b border-gray-100 pb-2">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $comment->user->name }} 
                                   <span class="text-xs text-gray-500 font-normal"> - {{ $comment->created_at->diffForHumans() }}</span>
                                </p>
                                <p class="text-gray-700 mt-1">{{ $comment->content }}</p>
                            </div>
                            
                            <button 
                                onclick="toggleLike('comment', {{ $comment->id }})" 
                                id="like-btn-comment-{{ $comment->id }}"
                                class="text-xs text-blue-500 font-semibold hover:underline">
                                {{ $comment->likes->contains('user_id', auth()->id()) ? 'Unlike' : 'Like' }}
                                (<span id="like-count-comment-{{ $comment->id }}">{{ $comment->likes->count() }}</span>)
                            </button>
                        </div>
                    </div>
                @empty
                    <p id="no-comments-msg" class="text-gray-500 italic">No comments yet. Be the first!</p>
                @endforelse
            </div>

            @auth
                <form id="comment-form" class="mt-4">
                    @csrf
                    <textarea id="comment-content" class="w-full border-gray-300 rounded-lg shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" rows="3" placeholder="Add a comment..."></textarea>
                    <p id="comment-error" class="text-red-500 text-xs mt-1 hidden"></p>
                    
                    <button type="submit" class="mt-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                        Post Comment
                    </button>
                </form>
            @endauth
        </div>

    </div>
</div>

<script>
    // 1. Polymorphic Like Function
    function toggleLike(type, id) {
        fetch(`/likes/${type}/${id}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Update the specific counter for THIS item (post or comment)
                document.getElementById(`like-count-${type}-${id}`).innerText = data.count;
                
                // Update the specific button text
                let btn = document.getElementById(`like-btn-${type}-${id}`);
                let text = data.liked ? 'Unlike' : 'Like';
                
                // Use innerHTML to preserve the span inside
                btn.innerHTML = `${text} (<span id="like-count-${type}-${id}">${data.count}</span>)`;
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // 2. AJAX Comment Submission
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

                // Append new comment WITH Like Button
                let newCommentHtml = `
                    <div class="border-b border-gray-100 pb-2 bg-blue-50 p-2 rounded animate-pulse">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-bold text-gray-900">${data.user_name} 
                                   <span class="text-xs text-gray-500 font-normal"> - ${data.created_at}</span>
                                </p>
                                <p class="text-gray-700 mt-1">${data.comment.content}</p>
                            </div>
                            <button 
                                onclick="toggleLike('comment', ${data.comment.id})" 
                                id="like-btn-comment-${data.comment.id}"
                                class="text-xs text-blue-500 font-semibold hover:underline">
                                Like (<span id="like-count-comment-${data.comment.id}">0</span>)
                            </button>
                        </div>
                    </div>
                `;
                list.insertAdjacentHTML('beforeend', newCommentHtml);
                
                countSpan.innerText = parseInt(countSpan.innerText) + 1;
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>
@endsection