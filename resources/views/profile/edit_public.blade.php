@extends('layouts.app')

@section('content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto w-full px-4 sm:px-0 space-y-6">
        
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Settings</h2>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-6">
                <header class="mb-6">
                    <h2 class="text-lg font-bold text-gray-900">Public Passport</h2>
                    <p class="mt-1 text-sm text-gray-500">Update your public profile details and account type.</p>
                </header>

                <form action="{{ route('profile.update_public') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1" for="name">Display Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                class="w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1" for="email">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                                class="w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1" for="username">Username</label>
                        <div class="flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">@</span>
                            <input type="text" name="username" id="username" value="{{ old('username', $user->profile->username) }}" 
                                class="block w-full rounded-none rounded-r-lg border-gray-300 bg-white text-gray-900 focus:border-blue-500 focus:ring-blue-500" required>
                        </div>
                        @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Account Type</label>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold px-2 py-1 rounded-full uppercase {{ $user->profile->role === 'viewer' ? 'bg-gray-200 text-gray-600' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $user->profile->role }}
                                </span>
                            </div>

                            @if($user->profile->role === 'viewer')
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="become_traveller" id="become_traveller" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                    <label for="become_traveller" class="text-sm text-gray-900 font-bold cursor-pointer">Upgrade to Traveller</label>
                                </div>
                            @else
                                <span class="text-xs text-green-600 font-bold flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    Posting Enabled
                                </span>
                            @endif
                        </div>
                        @if($user->profile->role === 'viewer')
                            <p class="text-xs text-gray-500 mt-2">
                                Check the box to enable posting features. <span class="text-red-500 font-semibold">This change is permanent.</span>
                            </p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1" for="bio">About Me</label>
                        <textarea name="bio" id="bio" rows="4" class="w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('bio', $user->profile->bio) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1" for="country_id">Home Base</label>
                        <select name="country_id" id="country_id" class="w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select a Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ (old('country_id', $user->profile->country_id) == $country->id) ? 'selected' : '' }}>
                                    {{ $country->flag }} {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @php $socials = json_decode($user->profile->social_links, true); @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1" for="twitter">Twitter / X</label>
                            <input type="url" name="twitter" id="twitter" value="{{ old('twitter', $socials['twitter'] ?? '') }}" class="w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1" for="instagram">Instagram</label>
                            <input type="url" name="instagram" id="instagram" value="{{ old('instagram', $socials['instagram'] ?? '') }}" class="w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Profile Photo</label>
                        <div class="flex items-center gap-6">
                            @if($user->profile->profile_photo)
                                <img src="{{ asset($user->profile->profile_photo) }}" class="h-16 w-16 rounded-full object-cover border border-gray-200 shadow-sm">
                                <div class="flex items-center">
                                    <input type="checkbox" name="remove_photo" id="remove_photo" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                    <label for="remove_photo" class="ml-2 text-sm text-red-600 font-medium cursor-pointer">Remove photo</label>
                                </div>
                            @else
                                <div class="h-16 w-16 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-500 border border-indigo-100">
                                    <span class="text-xl font-bold">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div class="flex-1">
                                <input type="file" name="profile_photo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-100">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition transform active:scale-95">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-6">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-6">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</div>
@endsection