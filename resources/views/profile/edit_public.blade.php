@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Your Passport</h2>

                <form action="{{ route('profile.update_public') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="bio">About Me</label>
                        <textarea name="bio" id="bio" rows="4" 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            placeholder="Tell us about your travels...">{{ old('bio', $user->profile->bio) }}</textarea>
                        @error('bio') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="country_id">Home Base</label>
                        <select name="country_id" id="country_id" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select a Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ (old('country_id', $user->profile->country_id) == $country->id) ? 'selected' : '' }}>
                                    {{ $country->flag }} {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('country_id') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>

                    @php
                        $socials = json_decode($user->profile->social_links, true);
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="twitter">Twitter / X URL</label>
                            <input type="url" name="twitter" id="twitter" value="{{ old('twitter', $socials['twitter'] ?? '') }}" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="https://twitter.com/username">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="instagram">Instagram URL</label>
                            <input type="url" name="instagram" id="instagram" value="{{ old('instagram', $socials['instagram'] ?? '') }}" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="https://instagram.com/username">
                        </div>
                    </div>

                    <div class="mb-6 border-t pt-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Profile Photo</label>
                        
                        <div class="flex items-center gap-4 mb-4">
                            @if($user->profile->profile_photo)
                                <div class="relative">
                                    <img src="{{ asset($user->profile->profile_photo) }}" alt="Current Profile" class="h-20 w-20 rounded-full object-cover border shadow-sm">
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="remove_photo" id="remove_photo" value="1" class="mr-2">
                                    <label for="remove_photo" class="text-sm text-red-600 font-semibold cursor-pointer">Delete current photo?</label>
                                </div>
                            @else
                                <div class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-xs">No Photo</div>
                            @endif
                        </div>

                        <input type="file" name="profile_photo" class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 text-blue-700
                            hover:file:bg-blue-100">
                        @error('profile_photo') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-end border-t pt-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full shadow transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection