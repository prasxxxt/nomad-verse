<div class="w-80 h-screen sticky top-0 hidden lg:block p-8 border-l border-gray-200">
    
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            @if(auth()->user()->profile && auth()->user()->profile->profile_photo)
                <img src="{{ asset(auth()->user()->profile->profile_photo) }}" class="h-12 w-12 rounded-full object-cover">
            @else
                <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            @endif
            <div class="leading-tight">
                <p class="font-bold text-sm text-gray-900">{{ auth()->user()->profile->username }}</p>
                <p class="text-xs text-gray-500">{{ auth()->user()->name }}</p>
            </div>
        </div>
        <a href="{{ route('profile.edit_public') }}" class="text-xs font-bold text-blue-500 hover:text-blue-700">Switch</a>
    </div>

    <div class="mb-4 flex justify-between items-center">
        <span class="text-sm font-bold text-gray-500">Suggested for you</span>
        <span class="text-xs font-semibold text-gray-800 cursor-pointer">See All</span>
    </div>

    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-8 w-8 rounded-full bg-gray-200"></div>
                <div class="flex flex-col">
                    <span class="text-xs font-bold text-gray-800">travel_guide</span>
                    <span class="text-[10px] text-gray-500">New to Nomad</span>
                </div>
            </div>
            <button class="text-xs font-bold text-blue-500">Follow</button>
        </div>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-8 w-8 rounded-full bg-gray-200"></div>
                <div class="flex flex-col">
                    <span class="text-xs font-bold text-gray-800">world_explorer</span>
                    <span class="text-[10px] text-gray-500">Suggested</span>
                </div>
            </div>
            <button class="text-xs font-bold text-blue-500">Follow</button>
        </div>
    </div>

    <div class="mt-10 text-[11px] text-gray-400 leading-relaxed">
        <p>About • Help • Press • API • Jobs • Privacy • Terms</p>
        <p class="mt-4">© 2025 NOMAD VERSE FROM PRASHANT</p>
    </div>

</div>