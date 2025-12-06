<x-guest-layout>
    <div class="mb-6 text-sm text-gray-600 leading-relaxed text-center">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 font-medium text-sm text-green-600 text-center bg-green-50 p-3 rounded-lg border border-green-100">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-6 flex flex-col gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition transform active:scale-95 text-sm uppercase tracking-wide">
                {{ __('Resend Verification Email') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="text-sm text-gray-500 hover:text-gray-900 font-semibold underline decoration-2 decoration-transparent hover:decoration-gray-400 transition-all">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>