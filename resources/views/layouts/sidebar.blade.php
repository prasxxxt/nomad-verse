<div class="w-64 h-screen sticky top-0 flex flex-col border-r border-gray-200 bg-white hidden md:flex">
    <div class="p-6 mb-2">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-2xl font-black text-blue-600 tracking-tighter">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            NomadVerse
        </a>
    </div>

    <nav class="flex-1 px-4 space-y-2">
        
        <a href="{{ route('dashboard') }}" class="flex items-center gap-4 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-full transition group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="font-bold text-lg">Home</span>
        </a>

        <a href="{{ route('notifications.index') }}" class="flex items-center gap-4 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-full transition group relative">
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full border-2 border-white"></span>
                @endif
            </div>
            <span class="font-medium text-lg">Notifications</span>
        </a>

        @if(auth()->user()->profile->role !== 'viewer')
        <a href="{{ route('posts.create') }}" class="flex items-center gap-4 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-full transition group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span class="font-medium text-lg">Create</span>
        </a>
        @endif

        <a href="{{ route('users.show', auth()->user()->profile->username ?? '') }}" class="flex items-center gap-4 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-full transition group">
            <div class="h-7 w-7 rounded-full overflow-hidden border border-gray-300 group-hover:border-black transition">
                @if(auth()->user()->profile && auth()->user()->profile->profile_photo)
                    <img src="{{ asset(auth()->user()->profile->profile_photo) }}" class="h-full w-full object-cover">
                @else
                    <div class="h-full w-full bg-indigo-100 flex items-center justify-center text-xs font-bold text-indigo-700">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                @endif
            </div>
            <span class="font-medium text-lg">Profile</span>
        </a>

        <a href="{{ route('profile.edit_public') }}" class="flex items-center gap-4 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-full transition group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="font-medium text-lg">Settings</span>
        </a>

    </nav>

    <div class="p-4 border-t border-gray-100">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-4 px-4 py-3 text-red-500 hover:bg-red-50 rounded-full w-full transition font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Log Out
            </button>
        </form>
    </div>
</div>