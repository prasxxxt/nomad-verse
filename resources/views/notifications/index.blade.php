@extends('layouts.app')

@section('content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto w-full px-4 sm:px-0">
        
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Notifications</h2>
            @if($notifications->count() > 0)
                <span class="text-xs font-semibold text-gray-500 bg-white border border-gray-200 px-2 py-1 rounded-md shadow-sm">
                    {{ $notifications->count() }} alerts
                </span>
            @endif
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden divide-y divide-gray-100">
            @forelse($notifications as $notification)
                <div class="p-4 hover:bg-gray-50 transition flex items-start gap-4 relative {{ is_null($notification->read_at) ? 'bg-blue-50/30' : '' }}">
                    
                    <div class="flex-shrink-0 relative">
                        @if(isset($notification->data['avatar']) && $notification->data['avatar'])
                            <img src="{{ asset($notification->data['avatar']) }}" class="h-10 w-10 rounded-full object-cover border border-gray-200">
                        @else
                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        @endif

                        <div class="absolute -bottom-1 -right-1 p-0.5 bg-white rounded-full">
                            @if(str_contains($notification->type, 'Like'))
                                <div class="bg-red-500 p-1 rounded-full text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @elseif(str_contains($notification->type, 'Comment'))
                                <div class="bg-blue-500 p-1 rounded-full text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @elseif(str_contains($notification->type, 'Follow'))
                                <div class="bg-purple-500 p-1 rounded-full text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900 leading-tight">
                            {{ $notification->data['message'] }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>

                    @if(isset($notification->data['link']) && $notification->data['link'] !== '#')
                        <div class="flex-shrink-0 self-center">
                            @if(str_contains($notification->type, 'Follow'))
                                <a href="{{ route('users.show', \App\Models\User::find($notification->data['follower_id'])->profile->username ?? '') }}" class="text-xs font-bold bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-md transition">
                                    View Profile
                                </a>
                            @else
                                <a href="{{ $notification->data['link'] }}" class="group block">
                                    <div class="bg-white border border-gray-200 p-2 rounded-lg hover:border-blue-300 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </a>
                            @endif
                        </div>
                    @endif

                    @if(is_null($notification->read_at))
                        <div class="absolute top-4 right-2 w-2 h-2 bg-blue-600 rounded-full"></div>
                    @endif
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="bg-gray-50 p-4 rounded-full mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium">No notifications yet.</p>
                    <p class="text-xs text-gray-400 mt-1">When someone interacts with you, it will appear here.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6 flex justify-center">
            </div>
    </div>
</div>
@endsection