<x-guest-layout>
    <div class="mb-6 text-sm text-gray-600 leading-relaxed text-center">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 mb-1">Email Address</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus 
                class="w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-11"
                placeholder="nomad@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="flex items-center justify-end">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition transform active:scale-95 text-sm uppercase tracking-wide">
                {{ __('Email Password Reset Link') }}
            </button>
        </div>

        <div class="text-center mt-4 text-sm">
            <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-900 font-semibold transition">
                &larr; Back to Login
            </a>
        </div>
    </form>
</x-guest-layout>