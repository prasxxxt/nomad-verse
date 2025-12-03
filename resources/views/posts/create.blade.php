@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-4">Create a New Memory</h2>

                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4"
                         x-data="{
                             search: '',
                             open: false,
                             selectedId: '{{ old('country_id') }}',
                             // Pass PHP data to JS
                             countries: {{ $countries->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'flag' => $c->flag])->values()->toJson() }},
                             
                             get filteredCountries() {
                                 if (this.search === '') return this.countries;
                                 return this.countries.filter(c => c.name.toLowerCase().includes(this.search.toLowerCase()));
                             },
                             
                             select(country) {
                                 this.selectedId = country.id;
                                 this.search = country.flag + ' ' + country.name;
                                 this.open = false;
                             },
                             
                             init() {
                                 if (this.selectedId) {
                                     const found = this.countries.find(c => c.id == this.selectedId);
                                     if (found) this.search = found.flag + ' ' + found.name;
                                 }
                             }
                         }"
                         @click.outside="open = false">

                        <label class="block text-gray-700 text-sm font-bold mb-2">Where was this?</label>

                        <div class="relative">
                            <input type="text"
                                   x-model="search"
                                   @focus="open = true"
                                   @input="open = true"
                                   placeholder="Search for a country..."
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   autocomplete="off">

                            <input type="hidden" name="country_id" :value="selectedId">

                            <div x-show="open"
                                 class="absolute z-50 w-full bg-white border border-gray-300 mt-1 max-h-60 overflow-y-auto rounded shadow-lg"
                                 style="display: none;">
                                
                                <template x-for="country in filteredCountries" :key="country.id">
                                    <div @click="select(country)"
                                         class="px-4 py-2 hover:bg-blue-50 cursor-pointer flex items-center gap-2 transition">
                                        <span x-text="country.flag" class="text-xl"></span>
                                        <span x-text="country.name" class="text-gray-700 font-medium"></span>
                                    </div>
                                </template>
                                
                                <div x-show="filteredCountries.length === 0" class="px-4 py-2 text-gray-500 italic">
                                    No countries found.
                                </div>
                            </div>
                        </div>
                        @error('country_id') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="title">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('title') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>
                        <textarea name="description" id="description" rows="4" 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Photos & Videos</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="media" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-gray-500">SVG, PNG, JPG, MP4 (MAX. 10MB)</p>
                                </div>
                                <input id="media" name="media[]" type="file" multiple class="hidden" />
                            </label>
                        </div>
                        @error('media') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        @error('media.*') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('posts.index') }}" class="text-gray-500 hover:text-gray-800 font-bold text-sm">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full shadow focus:outline-none focus:shadow-outline">
                            Post Memory
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection