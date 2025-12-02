@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Your Notifications</h2>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                @forelse($notifications as $notification)
                    <div class="flex items-center justify-between border-b border-gray-100 py-3 {{ $notification->read_at ? 'opacity-50' : 'bg-blue-50' }}">
                        <div>
                            <p class="text-sm text-gray-800">{{ $notification->data['message'] }}</p>
                            <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        <a href="{{ $notification->data['link'] }}" class="text-blue-600 hover:text-blue-800 text-sm font-bold">
                            View Post
                        </a>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">You have no notifications.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection