<div class="w-80 h-screen sticky top-0 hidden lg:block p-6 border-l border-gray-200 overflow-y-auto bg-gray-50">
    
    <div class="mb-8">
        <h3 class="text-gray-900 font-bold text-lg mb-4 tracking-tight flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
            </svg>
            Top Travellers
        </h3>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @foreach($suggestedTravellers as $traveller)
                <div class="flex items-center justify-between p-3 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition">
                    <a href="{{ route('users.show', $traveller->profile->username) }}" class="flex items-center gap-3 group">
                        <div class="relative">
                            @if($traveller->profile->profile_photo)
                                <img src="{{ asset($traveller->profile->profile_photo) }}" class="h-10 w-10 rounded-full object-cover border border-gray-200 group-hover:border-blue-300 transition">
                            @else
                                <div class="h-10 w-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold border border-indigo-100">
                                    {{ substr($traveller->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="leading-tight">
                            <p class="font-bold text-sm text-gray-900 group-hover:text-blue-600 transition">{{ $traveller->name }}</p>
                            <p class="text-xs text-gray-500">{{ $traveller->followers_count }} followers</p>
                        </div>
                    </a>
                    
                    @if(auth()->check() && !auth()->user()->follows->contains($traveller->id))
                        <button onclick="toggleFollow({{ $traveller->id }}, this)" 
                            class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-full hover:bg-blue-100 transition">
                            Follow
                        </button>
                    @endif
                </div>
            @endforeach

            @if($suggestedTravellers->isEmpty())
                <div class="p-4 text-center text-sm text-gray-500">
                    No travellers yet. Be the first!
                </div>
            @endif
        </div>
    </div>

    <div>
        <h3 class="text-gray-900 font-bold text-lg mb-4 tracking-tight flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-pink-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
            </svg>
            Trending Places
        </h3>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @foreach($trendingCountries as $country)
                <div class="flex items-center justify-between p-3 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition cursor-default">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">{{ $country->flag }}</span>
                        <div class="leading-tight">
                            <p class="font-bold text-sm text-gray-900">{{ $country->name }}</p>
                            <p class="text-xs text-gray-500">{{ $country->posts_count }} memories shared</p>
                        </div>
                    </div>
                    
                    <div class="h-6 w-6 flex items-center justify-center rounded-full bg-gray-100 text-xs font-bold text-gray-600">
                        #{{ $loop->iteration }}
                    </div>
                </div>
            @endforeach

            @if($trendingCountries->isEmpty())
                <div class="p-4 text-center text-sm text-gray-500">
                    No trending places yet.
                </div>
            @endif
        </div>
    </div>

    <div class="mt-8 text-xs text-gray-400 leading-relaxed text-center">
        &copy; {{ date('Y') }} Nomad Verse. <br>
        Designed for the travellers of the world.
    </div>

</div>