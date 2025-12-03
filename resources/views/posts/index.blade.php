@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-6 px-4 sm:px-0">
            <h2 class="text-2xl font-bold text-gray-800">Nomad Feed</h2>
            <a href="{{ route('posts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full shadow transition text-sm flex items-center gap-2">
                <span>+</span> Create Post
            </a>
        </div>

        <div class="space-y-8">
            @foreach ($posts as $post)
                <div class="bg-white border border-gray-200 sm:rounded-xl shadow-sm overflow-hidden">
                    
                    <div class="p-4 flex justify-between items-center">
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
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <span>{{ $post->created_at->diffForHumans() }}</span>
                                    @if($post->country)
                                        <span>&bull;</span>
                                        <span class="flex items-center gap-1">
                                            <span>{{ $post->country->flag }}</span>
                                            <span>{{ $post->country->name }}</span>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if(auth()->id() !== $post->user_id)
                            <button 
                                onclick="toggleFollow({{ $post->user_id }}, this)"
                                class="text-xs font-bold transition {{ auth()->user()->follows->contains($post->user_id) ? 'text-gray-500 hover:text-red-500' : 'text-blue-500 hover:text-blue-700' }}">
                                {{ auth()->user()->follows->contains($post->user_id) ? 'Unfollow' : 'Follow' }}
                            </button>
                        @endif
                    </div>

                    @if($post->image)
                        <a href="{{ route('posts.show', $post->id) }}" class="block">
                            <img src="{{ asset($post->image) }}" alt="Post image" class="w-full h-auto object-cover max-h-[500px]">
                        </a>
                    @endif

                    <div class="p-4">
                        <div class="flex items-center gap-4 mb-3">
                            <button onclick="toggleLike('post', {{ $post->id }}, this)" class="group flex items-center gap-1 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 transition {{ $post->likes->contains('user_id', auth()->id()) ? 'text-red-500 fill-current' : 'text-gray-600 hover:text-gray-800' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>

                            <button onclick="toggleCommentBox({{ $post->id }})" class="text-gray-600 hover:text-blue-600 transition focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </button>
                        </div>

                        <div class="mb-2 text-sm text-gray-900 font-medium">
                            <span id="post-like-count-{{ $post->id }}">{{ $post->likes->count() }}</span> likes
                            &bull; 
                            <span id="post-comment-count-{{ $post->id }}">{{ $post->comments->count() }}</span> comments
                        </div>

                        <div class="mb-2">
                            <span class="font-bold text-sm text-gray-900 mr-1">{{ $post->user->name }}</span>
                            <span class="text-sm text-gray-800">{{ Str::limit($post->description, 150) }}</span>
                        </div>

                        <div id="comment-box-{{ $post->id }}" class="hidden mt-3 pt-3 border-t border-gray-100">
                            <form onsubmit="submitComment(event, {{ $post->id }})">
                                <div class="flex gap-2">
                                    <input type="text" id="comment-input-{{ $post->id }}" 
                                        class="w-full bg-gray-50 border-0 rounded-full px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:bg-white transition" 
                                        placeholder="Add a comment...">
                                    <button type="submit" class="text-blue-600 font-bold text-sm px-2 hover:text-blue-800">Post</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection