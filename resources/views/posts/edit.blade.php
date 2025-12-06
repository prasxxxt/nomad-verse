@extends('layouts.app')

@section('content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-0">
        
        <div class="flex items-center justify-between mb-2 px-1">
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Edit Memory</h2>
            <a href="{{ route('posts.show', $post->id) }}" class="text-sm font-semibold text-gray-500 hover:text-gray-900">
                Cancel
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-6">
                
                <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="relative"
                         x-data="{
                             search: '',
                             open: false,
                             selectedId: '{{ old('country_id', $post->country_id) }}',
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
                            </div>
                        </div>
                        @error('country_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    @if($post->media->count() > 0)
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Current Media</label>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($post->media as $media)
                                    <div class="relative h-24 bg-black rounded-lg overflow-hidden border border-gray-200">
                                        @if($media->file_type === 'video')
                                            <video class="w-full h-full object-cover opacity-50">
                                                <source src="{{ asset($media->file_path) }}">
                                            </video>
                                            <div class="absolute inset-0 flex items-center justify-center text-white">
                                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                                            </div>
                                        @else
                                            <img src="{{ asset($media->file_path) }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Uploading new files will append to this list.</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Add More Photos/Videos</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="media" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition group">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <div class="p-2 bg-white rounded-full shadow-sm mb-2 group-hover:scale-110 transition">
                                        <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-500">Click to add files</p>
                                </div>
                                <input id="media" name="media[]" type="file" multiple class="hidden" accept="image/*,video/mp4,video/quicktime" />
                            </label>
                        </div>
                        @error('media') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1" for="title">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" 
                            class="w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1" for="description">Caption</label>
                        <textarea name="description" id="description" rows="4" 
                            class="w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $post->description) }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition transform active:scale-95 text-sm">
                            Update Memory
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection