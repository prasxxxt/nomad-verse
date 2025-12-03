@extends('layouts.app')

@section('content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-[470px] mx-auto w-full">
        
        <div class="flex justify-between items-center mb-6 px-1">
            <h2 class="text-xl font-bold text-gray-800 tracking-tight">Nomad Feed</h2>
            <a href="{{ route('posts.create') }}" class="text-blue-500 font-semibold text-sm hover:text-blue-700">
                + Create Post
            </a>
        </div>

        <div class="space-y-4">
            @foreach ($posts as $post)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    
                    <div class="flex items-center justify-between p-3">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('users.show', $post->user->profile->username) }}" class="block relative">
                                <div class="p-[2px] rounded-full bg-gradient-to-tr from-yellow-400 to-fuchsia-600">
                                    @if($post->user->profile->profile_photo)
                                        <img src="{{ asset($post->user->profile->profile_photo) }}" alt="{{ $post->user->name }}" class="h-8 w-8 rounded-full object-cover border-2 border-white bg-white">
                                    @else
                                        <div class="h-8 w-8 rounded-full bg-white flex items-center justify-center text-xs font-bold text-gray-700 border-2 border-white">
                                            {{ substr($post->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                            </a>
                            
                            <div class="leading-tight">
                                <a href="{{ route('users.show', $post->user->profile->username) }}" class="font-semibold text-sm text-gray-900 hover:opacity-70">
                                    {{ $post->user->profile->username ?? $post->user->name }}
                                </a>
                                @if($post->country)
                                    <p class="text-[11px] text-gray-500 truncate max-w-[150px]">
                                        {{ $post->country->name }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        @if(auth()->id() !== $post->user_id)
                            <button 
                                onclick="toggleFollow({{ $post->user_id }}, this)"
                                class="text-xs font-semibold {{ auth()->user()->follows->contains($post->user_id) ? 'text-gray-400' : 'text-blue-500' }}">
                                {{ auth()->user()->follows->contains($post->user_id) ? 'Following' : 'Follow' }}
                            </button>
                        @endif
                    </div>

                    @if($post->media->count() > 0)
                        <div class="relative group bg-black w-full h-[500px]">
                            
                            <div id="carousel-{{ $post->id }}" 
                                 class="flex h-full w-full overflow-x-auto snap-x snap-mandatory scroll-smooth scrollbar-hide">
                                
                                @foreach($post->media as $media)
                                    <div class="min-w-full w-full h-full flex-shrink-0 snap-center flex items-center justify-center relative">
                                        @if($media->file_type === 'video')
                                            <video controls class="max-w-full max-h-full object-contain">
                                                <source src="{{ asset($media->file_path) }}" type="video/mp4">
                                            </video>
                                        @else
                                            <img src="{{ asset($media->file_path) }}" 
                                                 alt="Post media" 
                                                 class="max-w-full max-h-full object-contain">
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            @if($post->media->count() > 1)
                                <button onclick="scrollCarousel({{ $post->id }}, -1)" 
                                    class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/70 hover:bg-white text-gray-800 rounded-full p-1.5 shadow-lg transition opacity-0 group-hover:opacity-100 z-10 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>

                                <button onclick="scrollCarousel({{ $post->id }}, 1)" 
                                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/70 hover:bg-white text-gray-800 rounded-full p-1.5 shadow-lg transition opacity-0 group-hover:opacity-100 z-10 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>

                                <div class="absolute top-3 right-3 bg-black/70 text-white text-[10px] font-bold px-2 py-1 rounded-full backdrop-blur-sm pointer-events-none z-10">
                                    <span id="counter-{{ $post->id }}">1</span>/{{ $post->media->count() }}
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="p-3 pb-1">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-4">
                                <button onclick="toggleLike('post', {{ $post->id }}, this)" class="group focus:outline-none transition transform active:scale-125">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 {{ $post->likes->contains('user_id', auth()->id()) ? 'text-red-500 fill-current' : 'text-gray-800 hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </button>

                                <button onclick="toggleCommentBox({{ $post->id }})" class="focus:outline-none hover:opacity-60">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="font-bold text-sm text-gray-900 mb-1">
                            <span id="post-like-count-{{ $post->id }}">{{ $post->likes->count() }}</span> likes
                        </div>

                        <div class="mb-1 text-sm">
                            <span class="font-bold text-gray-900 mr-1">{{ $post->user->profile->username ?? $post->user->name }}</span>
                            <span class="text-gray-800">{{ $post->description }}</span>
                        </div>

                        @if($post->comments->count() > 0)
                            <a href="{{ route('posts.show', $post->id) }}" class="text-gray-500 text-sm mb-1 block">
                                View all {{ $post->comments->count() }} comments
                            </a>
                        @endif
                        
                        <p class="text-[10px] text-gray-400 uppercase tracking-wide mb-3">
                            {{ $post->created_at->diffForHumans() }}
                        </p>

                        <div id="comment-box-{{ $post->id }}" class="hidden pt-2 border-t border-gray-100">
                            <form onsubmit="submitComment(event, {{ $post->id }})" class="flex items-center gap-2">
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
    /* Hide scrollbar logic */
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
    function scrollCarousel(postId, direction) {
        const container = document.getElementById(`carousel-${postId}`);
        const counter = document.getElementById(`counter-${postId}`);
        
        // Scroll exactly one container width
        const scrollAmount = container.clientWidth;
        
        container.scrollBy({ left: scrollAmount * direction, behavior: 'smooth' });

        // Update Counter
        setTimeout(() => {
            const index = Math.round(container.scrollLeft / container.clientWidth) + 1;
            if(counter) {
                const total = parseInt(counter.nextSibling.textContent.replace('/', ''));
                let validIndex = Math.max(1, Math.min(index, total));
                counter.innerText = validIndex;
            }
        }, 300);
    }
</script>
@endsection