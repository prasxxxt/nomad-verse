<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <script src="https://cdn.tailwindcss.com"></script>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        
        <div class="flex justify-center min-h-screen">
            
            @auth
                @include('layouts.sidebar')
            @endauth

            <main class="flex-1 max-w-[600px] w-full mx-auto">
                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>

            @auth
                @include('layouts.rightbar')
            @endauth

        </div>
        <div class="flex justify-center min-h-screen">
            
            @auth
                @include('layouts.sidebar')
            @endauth

            <main class="flex-1 max-w-[600px] w-full mx-auto pb-16 md:pb-0"> @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>

            @auth
                @include('layouts.rightbar')
            @endauth

        </div>

        @auth
            @include('layouts.navigation')
        @endauth

        <script>
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            function toggleLike(type, id, btn) {
                fetch(`/likes/${type}/${id}`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken }
                })
                .then(r => r.json())
                .then(d => {
                    if(d.success) {
                        let span = document.getElementById(`${type}-like-count-${id}`);
                        if(span) span.innerText = d.count;
                        let svg = btn.querySelector('svg');
                        if(d.liked) { svg.classList.add('text-red-500', 'fill-current'); svg.classList.remove('text-gray-600'); }
                        else { svg.classList.remove('text-red-500', 'fill-current'); svg.classList.add('text-gray-600'); }
                    }
                })
                .catch(e => console.error(e));
            }

            function toggleFollow(userId, btn) {
                btn.disabled = true;
                fetch(`/users/${userId}/follow`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" }
                })
                .then(r => r.json())
                .then(d => {
                    btn.disabled = false;
                    if(d.success) {
                        btn.innerText = d.following ? "Unfollow" : "Follow";
                        // Update Profile Count
                        let count = document.getElementById('profile-follower-count');
                        if(count) count.innerText = d.count;
                    }
                })
                .catch(e => { console.error(e); btn.disabled = false; });
            }

            function toggleCommentBox(postId) {
                const box = document.getElementById(`comment-box-${postId}`);
                box.classList.toggle('hidden');
                if(!box.classList.contains('hidden')) document.getElementById(`comment-input-${postId}`).focus();
            }

            function submitComment(event, postId) {
                event.preventDefault();
                const input = document.getElementById(`comment-input-${postId}`);
                if(!input.value.trim()) return;

                fetch(`/posts/${postId}/comments`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken },
                    body: JSON.stringify({ content: input.value })
                })
                .then(r => r.json())
                .then(d => {
                    if(d.success) {
                        input.value = '';
                        alert('Comment Posted!');
                        toggleCommentBox(postId);
                    }
                });
            }
        </script>
    </body>
</html>