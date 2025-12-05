<section class="space-y-6">
    <header>
        <h2 class="text-lg font-bold text-gray-900">
            {{ __('Delete Account') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <div class="flex justify-end pt-2 border-t border-gray-100">
        <button 
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition transform active:scale-95"
        >
            {{ __('Delete Account') }}
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-white rounded-lg">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-gray-900 mb-2">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="text-sm text-gray-600 mb-6">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <label for="password" class="sr-only">{{ __('Password') }}</label>

                <input 
                    id="password"
                    name="password"
                    type="password"
                    class="w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-red-500 focus:ring-red-500"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button 
                    type="button" 
                    x-on:click="$dispatch('close')" 
                    class="bg-white text-gray-700 font-bold py-2 px-4 rounded-lg border border-gray-300 hover:bg-gray-50 transition"
                >
                    {{ __('Cancel') }}
                </button>

                <button 
                    type="submit" 
                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition"
                >
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>