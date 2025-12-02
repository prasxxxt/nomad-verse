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
                            
                            <div class="flex justify-between items-center mb-4">
                                <div class="flex items-center">
                                    <div class="font-bold text-lg">{{ $post->user->name }}</div>
                                    <span class="mx-2 text-gray-400">&bull;</span>
                                    <span class="text-gray-500 text-sm">{{ $post->created_at->diffForHumans() }}</span>
                                </div>
                                @if($post->country)
                                    <span class="bg-gray-100 text-gray-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded border border-gray-500">
                                        {{ $post->country->flag }} {{ $post->country->name }}
                                    </span>
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