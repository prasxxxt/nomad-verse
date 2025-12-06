<div class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 md:hidden z-50">
    <div class="flex justify-around items-center h-16">
        
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center w-full h-full text-gray-500 hover:text-blue-600 {{ request()->routeIs('dashboard') ? 'text-blue-600' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
        </a>

        <a href="{{ route('notifications.index') }}" class="flex flex-col items-center justify-center w-full h-full text-gray-500 hover:text-blue-600 relative {{ request()->routeIs('notifications.index') ? 'text-blue-600' : '' }}">
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="absolute -top-1 -right-1 h-2.5 w-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                @endif
            </div>
        </a>

        @if(auth()->user()->profile->role !== 'viewer')
        <a href="{{ route('posts.create') }}" class="flex flex-col items-center justify-center w-full h-full">
            <div class="bg-blue-600 text-white rounded-2xl p-2 shadow-md hover:bg-blue-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </div>
        </a>
        @endif

        <a href="{{ route('profile.edit_public') }}" class="flex flex-col items-center justify-center w-full h-full text-gray-500 hover:text-blue-600 {{ request()->routeIs('profile.edit_public') ? 'text-blue-600' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </a>

        <a href="{{ route('users.show', auth()->user()->profile->username ?? '') }}" class="flex flex-col items-center justify-center w-full h-full text-gray-500 hover:text-blue-600">
            @if(auth()->user()->profile && auth()->user()->profile->profile_photo)
                <img src="{{ asset(auth()->user()->profile->profile_photo) }}" class="h-6 w-6 rounded-full object-cover border border-gray-300">
            @else
                <div class="h-6 w-6 rounded-full bg-indigo-100 flex items-center justify-center text-[10px] font-bold text-indigo-700 border border-gray-300">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            @endif
        </a>

    </div>
</div>