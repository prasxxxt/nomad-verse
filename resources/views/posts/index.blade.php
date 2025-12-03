@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 leading-tight">
                    Travel Feed
                </h2>
                <a href="{{ route('posts.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    + Create New Post
                </a>
            </div>

            <div class="space-y-6">
                @foreach ($posts as $post)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            
                            <div class="p-4 flex justify-between items-center border-b border-gray-100 bg-white">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                                {{ substr($post->user->name, 0, 1) }}
                            </div>
                            
                            <div>
                                <div class="flex items-center space-x-2">
                                    <p class="font-bold text-gray-900 text-sm">
                                        <a href="{{ route('users.show', $post->user->profile->username) }}" class="font-bold text-gray-900 text-sm hover:underline">
                                            {{ $post->user->name }}
                                        </a>
                                    </p>
                                    
                                    <span class="text-xs text-gray-500 block">
                                        {{ '@' . ($post->user->profile->username ?? 'user') }}
                                    </span>
                                    
                                    @if(auth()->id() !== $post->user_id)
                                        <form action="{{ route('users.follow', $post->user) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs font-semibold text-blue-500 hover:text-blue-700 ml-1">
                                                {{ auth()->user()->follows->contains($post->user_id) ? '• Unfollow' : '• Follow' }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        @if($post->country)
                            <div class="flex items-center space-x-1 bg-gray-50 px-3 py-1 rounded-full border border-gray-200">
                                <span class="text-xl">{{ $post->country->flag }}</span>
                                <span class="text-xs font-semibold text-gray-600 uppercase">{{ $post->country->iso_code }}</span>
                            </div>
                        @endif
                    </div>

                            <h3 class="text-xl font-bold mb-2">
                                <a href="{{ route('posts.show', $post->id) }}" class="hover:text-blue-600 hover:underline">
                                    {{ $post->title }}
                                </a>
                            </h3>
                            
                            <p class="text-gray-700 mb-4">{{ Str::limit($post->description, 150) }}</p>

                            @if($post->image)
                                <div class="mb-4">
                                    <img src="{{ $post->image }}" alt="Post image" class="w-full h-64 object-cover rounded-lg">
                                </div>
                            @endif

                            <div class="flex items-center mt-4 text-gray-500 text-sm">
                                <span>{{ $post->likes->count() }} Likes</span>
                                <span class="mx-2">&bull;</span>
                                <span>{{ $post->comments->count() }} Comments</span>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
@endsection