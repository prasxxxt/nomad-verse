<section>
    <header class="mb-6">
        <h2 class="text-lg font-bold text-gray-900">
            {{ __('Update Password') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1" for="update_password_current_password">
                {{ __('Current Password') }}
            </label>
            <input 
                id="update_password_current_password" 
                name="current_password" 
                type="password" 
                class="w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                autocomplete="current-password" 
            />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1" for="update_password_password">
                {{ __('New Password') }}
            </label>
            <input 
                id="update_password_password" 
                name="password" 
                type="password" 
                class="w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                autocomplete="new-password" 
            />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1" for="update_password_password_confirmation">
                {{ __('Confirm Password') }}
            </label>
            <input 
                id="update_password_password_confirmation" 
                name="password_confirmation" 
                type="password" 
                class="w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                autocomplete="new-password" 
            />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-bold"
                >{{ __('Saved.') }}</p>
            @endif

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition transform active:scale-95">
                {{ __('Save') }}
            </button>
        </div>
    </form>
</section>