<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Nomad Verse - Share Your Journey</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="antialiased bg-gray-50 text-gray-800">
        
        <div class="min-h-screen flex flex-col justify-center items-center relative overflow-hidden">
            
            <div class="absolute top-0 left-0 w-full h-full bg-white z-0">
                <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
                <div class="absolute top-[-10%] right-[-10%] w-96 h-96 bg-purple-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
                <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
            </div>

            <div class="relative z-10 w-full max-w-md px-6 text-center">
                
                <div class="flex justify-center mb-8">
                    <div class="bg-blue-600 p-4 rounded-2xl shadow-lg transform -rotate-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <h1 class="text-5xl font-black mb-2 tracking-tight text-gray-900">Nomad Verse</h1>
                <p class="text-lg text-gray-600 mb-10">Connect, Share, and Explore with travelers around the world.</p>

                @if (Route::has('login'))
                    <div class="space-y-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="block w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-md transition transform hover:scale-105">
                                Go to Feed
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="block w-full py-3 px-4 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-lg shadow-md transition">
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="block w-full py-3 px-4 bg-white hover:bg-gray-50 text-gray-900 border border-gray-300 font-bold rounded-lg shadow-sm transition">
                                    Create Account
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif

                <div class="mt-12 pt-6 border-t border-gray-200">
                    <p class="text-xs text-gray-400 font-medium">© 2025 Nomad Verse. Crafted for Travellers.</p>
                </div>
            </div>
        </div>

        <style>
            @keyframes blob {
                0% { transform: translate(0px, 0px) scale(1); }
                33% { transform: translate(30px, -50px) scale(1.1); }
                66% { transform: translate(-20px, 20px) scale(0.9); }
                100% { transform: translate(0px, 0px) scale(1); }
            }
            .animate-blob {
                animation: blob 7s infinite;
            }
            .animation-delay-2000 {
                animation-delay: 2s;
            }
            .animation-delay-4000 {
                animation-delay: 4s;
            }
        </style>
    </body>
</html>