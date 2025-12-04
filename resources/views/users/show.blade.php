@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" x-data="{ expandedMap: false }">
    
    <div class="relative w-full bg-blue-900 overflow-hidden transition-all duration-700 ease-in-out group"
         :class="expandedMap ? 'h-[60vh]' : 'h-80'">
        
        <div id="world-map" class="w-full h-full opacity-80 hover:opacity-100 transition duration-700"></div>
        
        <div class="absolute inset-0 bg-gradient-to-t from-gray-50 via-transparent to-transparent pointer-events-none transition-opacity duration-500"
             :class="expandedMap ? 'opacity-0' : 'opacity-100'"></div>

        <div class="absolute top-6 left-6 z-30 hidden md:block">
            <div class="bg-white/90 backdrop-blur-md border border-blue-100 rounded-xl shadow-lg p-3 flex items-center gap-3 transition hover:scale-105">
                <div>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Explored</p>
                    <div class="flex items-baseline gap-1">
                        <span class="text-xl font-black text-gray-900">{{ count($visitedCountries) }}</span>
                        <span class="text-xs text-gray-400 font-medium">/ 195</span>
                        
                        @php $percentage = round((count($visitedCountries) / 195) * 100, 1); @endphp
                        <span class="ml-1 text-xs font-bold text-green-600 bg-green-100 px-1.5 py-0.5 rounded-full">
                            {{ $percentage }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute top-4 right-4 z-30">
            <button @click="expandedMap = !expandedMap; setTimeout(() => window.dispatchEvent(new Event('resize')), 100)" 
                class="bg-white/80 text-gray-700 font-bold px-3 py-1.5 rounded-full shadow-sm text-xs hover:bg-white transition flex items-center gap-1.5 border border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                </svg>
                <span x-text="expandedMap ? 'Close' : 'Map'"></span>
            </button>
        </div>
    </div>

    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 -mt-20 relative z-20 transition-transform duration-500"
         :class="expandedMap ? 'translate-y-[5vh]' : 'translate-y-0'">
        
        <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden mb-8 border border-gray-100">
            <div class="p-6 sm:p-10 flex flex-col md:flex-row items-center md:items-start gap-8">
                
                <div class="flex-shrink-0 relative">
                    <div class="absolute -inset-1 bg-gradient-to-tr from-blue-500 to-cyan-400 rounded-full blur opacity-30"></div>
                    @if($user->profile && $user->profile->profile_photo)
                        <img src="{{ asset($user->profile->profile_photo) }}" alt="{{ $user->name }}" class="relative h-32 w-32 rounded-full object-cover border-4 border-white shadow-lg">
                    @else
                        <div class="relative h-32 w-32 rounded-full bg-indigo-50 flex items-center justify-center text-5xl text-indigo-500 font-bold border-4 border-white shadow-lg">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                </div>

                <div class="flex-1 text-center md:text-left">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-black text-gray-900 tracking-tight">{{ $user->name }}</h1>
                            <p class="text-indigo-600 font-bold mb-2">{{ '@' . ($user->profile->username ?? 'user') }}</p>
                            @if($user->profile)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize">
                                    {{ $user->profile->role }}
                                </span>
                            @endif
                        </div>

                        @if(auth()->id() !== $user->id)
                            <div class="mt-4 md:mt-0">
                                <button 
                                    onclick="toggleFollow({{ $user->id }}, this)"
                                    class="px-8 py-2.5 rounded-full font-bold shadow-md transition transform hover:-translate-y-0.5 {{ auth()->user()->follows->contains($user->id) ? 'bg-gray-100 text-gray-600 hover:bg-gray-200' : 'bg-blue-600 text-white hover:bg-blue-700' }}">
                                    {{ auth()->user()->follows->contains($user->id) ? 'Unfollow' : 'Follow' }}
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 max-w-2xl">
                        <p class="text-gray-600 leading-relaxed text-sm">
                            {{ $user->profile->bio ?? 'Ready to explore the world.' }}
                        </p>
                    </div>
                    
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-6 mt-4 text-sm text-gray-500">
                        @if($user->profile->country)
                            <div class="flex items-center gap-1">
                                <span class="text-lg">{{ $user->profile->country->flag }}</span>
                                <span>Based in <span class="font-semibold text-gray-700">{{ $user->profile->country->name }}</span></span>
                            </div>
                        @endif

                        @php $socials = json_decode($user->profile->social_links, true); @endphp
                        @if(!empty($socials['twitter']) || !empty($socials['instagram']))
                            <div class="flex gap-3">
                                @if(!empty($socials['twitter']))
                                    <a href="{{ $socials['twitter'] }}" target="_blank" class="hover:text-blue-500 transition font-medium">Twitter</a>
                                @endif
                                @if(!empty($socials['instagram']))
                                    <a href="{{ $socials['instagram'] }}" target="_blank" class="hover:text-pink-600 transition font-medium">Instagram</a>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="mt-8 flex justify-center md:justify-start gap-10 border-t border-gray-100 pt-6">
                        <div class="text-center md:text-left">
                            <span class="block font-black text-2xl text-gray-900">{{ $user->posts->count() }}</span>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wide">Memories</span>
                        </div>
                        <div class="text-center md:text-left">
                            <span id="profile-follower-count" class="block font-black text-2xl text-gray-900">{{ $user->followers->count() }}</span>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wide">Followers</span>
                        </div>
                        <div class="text-center md:text-left">
                            <span class="block font-black text-2xl text-gray-900">{{ $user->follows->count() }}</span>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wide">Following</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="text-xl font-bold text-gray-800 mb-6 px-2 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
            </svg>
            Recent Memories
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-12">
            @forelse ($posts as $post)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl hover:shadow-lg transition group border border-gray-100">
                    
                    @php $firstMedia = $post->media->first(); @endphp
                    
                    @if($firstMedia)
                        <a href="{{ route('posts.show', $post->id) }}" class="block relative h-64 overflow-hidden bg-gray-100">
                            @if($firstMedia->file_type === 'video')
                                <video class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition">
                                    <source src="{{ asset($firstMedia->file_path) }}" type="video/mp4">
                                </video>
                                <div class="absolute top-3 right-3 bg-black/50 text-white p-1.5 rounded-full backdrop-blur-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                </div>
                            @else
                                <img src="{{ asset($firstMedia->file_path) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transform transition duration-700 group-hover:scale-105">
                            @endif

                            @if($post->media->count() > 1)
                                <div class="absolute top-3 right-3 bg-black/50 text-white p-1.5 rounded-full backdrop-blur-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                            @endif
                        </a>
                    @endif

                    <div class="p-5">
                        <h4 class="font-bold text-lg mb-1 truncate text-gray-900 group-hover:text-blue-600 transition">
                            <a href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a>
                        </h4>
                        <p class="text-gray-400 text-xs mb-3 font-medium uppercase tracking-wider">{{ $post->created_at->diffForHumans() }}</p>
                        <p class="text-gray-600 text-sm line-clamp-2 leading-relaxed">{{ $post->description }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-16 bg-white rounded-xl border-2 border-dashed border-gray-200">
                    <div class="inline-block p-4 rounded-full bg-gray-50 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium">No memories shared yet.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6 pb-12">
            {{ $posts->links() }}
        </div>

    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap/dist/css/jsvectormap.min.css" />
<script src="https://cdn.jsdelivr.net/npm/jsvectormap/dist/js/jsvectormap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsvectormap/dist/maps/world.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const visitedCodes = @json($visitedCountries ?? []);

        if(document.getElementById('world-map')) {
            const map = new jsVectorMap({
                selector: '#world-map',
                map: 'world',
                backgroundColor: '#1e3a8a', // Dark Blue Sea
                draggable: true,
                zoomButtons: false,
                zoomOnScroll: false,
                regionStyle: {
                    initial: {
                        fill: '#f8fafc', // White Land
                        stroke: '#94a3b8',
                        strokeWidth: 0.15,
                        fillOpacity: 1
                    },
                    hover: { fillOpacity: 0.9 }
                },
                series: {
                    regions: [{
                        attribute: 'fill',
                        scale: { visited: '#ef4444' }, // Red Highlight
                        values: visitedCodes.reduce((acc, code) => { 
                            acc[code.toUpperCase()] = 'visited'; 
                            return acc; 
                        }, {})
                    }]
                }
            });
        }
    });
</script>
@endsection