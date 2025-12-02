@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        
        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden mb-6">
            <div class="p-6 sm:p-10 flex flex-col md:flex-row items-center md:items-start gap-6">
                
                <div class="flex-shrink-0">
                    @if($user->profile && $user->profile->profile_photo)
                        <img src="{{ asset($user->profile->profile_photo) }}" alt="{{ $user->name }}" class="h-32 w-32 rounded-full object-cover border-4 border-indigo-100 shadow-sm">
                    @else
                        <div class="h-32 w-32 rounded-full bg-indigo-100 flex items-center justify-center text-4xl text-indigo-600 font-bold border-4 border-white shadow-sm">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                </div>

                <div class="flex-1 text-center md:text-left">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                            <p class="text-indigo-600 font-semibold mb-1">
                                {{ '@' . ($user->profile->username ?? 'user') }}
                            </p>
                            <p class="text-sm text-gray-500 font-medium">Joined {{ $user->created_at->format('M Y') }}</p>
                            @if($user->profile)
                                <span class="inline-block mt-2 px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">
                                    {{ $user->profile->role }}
                                </span>
                            @endif
                        </div>

                        @if(auth()->id() !== $user->id)
                            <div class="mt-4 md:mt-0">
                                <form action="{{ route('users.follow', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-6 py-2 rounded-full font-bold shadow-sm transition {{ auth()->user()->follows->contains($user->id) ? 'bg-gray-200 text-gray-700 hover:bg-gray-300' : 'bg-blue-600 text-white hover:bg-blue-700' }}">
                                        {{ auth()->user()->follows->contains($user->id) ? 'Unfollow' : 'Follow' }}
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 max-w-2xl">
                        <p class="text-gray-700 leading-relaxed">
                            {{ $user->profile->bio ?? 'This traveler has not written a bio yet.' }}
                        </p>
                    </div>

                    <div class="mt-6 flex justify-center md:justify-start gap-8 text-sm">
                        <div class="text-center">
                            <span class="block font-bold text-xl text-gray-900">{{ $user->posts->count() }}</span>
                            <span class="text-gray-500">Posts</span>
                        </div>
                        <div class="text-center">
                            <span class="block font-bold text-xl text-gray-900">{{ $user->followers->count() }}</span>
                            <span class="text-gray-500">Followers</span>
                        </div>
                        <div class="text-center">
                            <span class="block font-bold text-xl text-gray-900">{{ $user->follows->count() }}</span>
                            <span class="text-gray-500">Following</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="text-xl font-bold text-gray-800 mb-4 px-2">Recent Memories</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse ($posts as $post)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    @if($post->image)
                        <a href="{{ route('posts.show', $post->id) }}">
                            <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                        </a>
                    @endif
                    <div class="p-4">
                        <h4 class="font-bold text-lg mb-1">
                            <a href="{{ route('posts.show', $post->id) }}" class="hover:text-blue-600">{{ $post->title }}</a>
                        </h4>
                        <p class="text-gray-500 text-sm mb-3">{{ $post->created_at->diffForHumans() }}</p>
                        <p class="text-gray-700 text-sm line-clamp-2">{{ $post->description }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-12 bg-white rounded-lg border border-dashed border-gray-300">
                    <p class="text-gray-500">No posts shared yet.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $posts->links() }}
        </div>

    </div>
</div>
@endsection