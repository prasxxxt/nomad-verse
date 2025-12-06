@extends('layouts.app')

@section('content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-0">
        
        <div class="flex items-center justify-between mb-2 px-1">
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">New Memory</h2>
            <a href="{{ route('posts.index') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-900">
                Cancel
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-6">
                
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="relative"
                         x-data="{
                             search: '',
                             open: false,
                             selectedId: '{{ old('country_id') }}',
                             countries: {{ $countries->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'flag' => $c->flag])->values()->toJson() }},
                             get filtered() {
                                 return this.search === '' 
                                    ? this.countries 
                                    : this.countries.filter(c => c.name.toLowerCase().includes(this.search.toLowerCase()))
                             },
                             select(c) {
                                 this.selectedId = c.id;
                                 this.search = c.flag + ' ' + c.name;
                                 this.open = false;
                             },
                             init() {
                                 if(this.selectedId) {
                                     const found = this.countries.find(c => c.id == this.selectedId);
                                     if(found) this.search = found.flag + ' ' + found.name;
                                 }
                             }
                         }"
                         @click.outside="open = false">
                        
                        <label class="block text-sm font-bold text-gray-700 mb-1">Location</label>
                        
                        <div class="relative">
                            <input type="text" x-model="search" @focus="open = true" @input="open = true"
                                placeholder="Where was this taken?"
                                class="w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 pl-10 h-10"
                                autocomplete="off">
                            
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>

                            <input type="hidden" name="country_id" :value="selectedId">

                            <div x-show="open" 
                                 class="absolute z-50 w-full bg-white border border-gray-200 mt-1 max-h-60 overflow-y-auto rounded-lg shadow-xl"
                                 style="display: none;">
                                <template x-for="country in filtered" :key="country.id">
                                    <div @click="select(country)" class="px-4 py-2 hover:bg-blue-50 cursor-pointer flex items-center gap-2 transition border-b border-gray-50 last:border-0">
                                        <span x-text="country.flag" class="text-lg"></span>
                                        <span x-text="country.name" class="text-sm font-medium text-gray-700"></span>
                                    </div>
                                </template>
                                <div x-show="filtered.length === 0" class="px-4 py-3 text-sm text-gray-500 text-center">No countries found.</div>
                            </div>
                        </div>
                        @error('country_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Photos & Videos</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="media" class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition group">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <div class="p-3 bg-white rounded-full shadow-sm mb-3 group-hover:scale-110 transition">
                                        <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <p class="mb-1 text-sm text-gray-600"><span class="font-bold text-blue-600">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-gray-400">JPG, PNG, MP4, MOV (Max 10MB)</p>
                                </div>
                                <input id="media" name="media[]" type="file" multiple class="hidden" accept="image/*,video/mp4,video/quicktime" />
                            </label>
                        </div>
                        @error('media') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        @error('media.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1" for="title">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" 
                            class="w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                            placeholder="e.g., Sunset in Kyoto" required>
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1" for="description">Caption</label>
                        <textarea name="description" id="description" rows="4" 
                            class="w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Share the story behind this moment...">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition transform active:scale-95 text-sm flex items-center gap-2">
                            <span>Share Memory</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection