<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main>
                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>
        </div>

        <script>
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Toggle Like
            function toggleLike(type, id, btn) {
                fetch(`/likes/${type}/${id}`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        let countSpan = document.getElementById(`${type}-like-count-${id}`);
                        if(countSpan) countSpan.innerText = data.count;

                        let svg = btn.querySelector('svg');
                        if(data.liked) {
                            svg.classList.add('text-red-500', 'fill-current');
                            svg.classList.remove('text-gray-600', 'text-gray-400');
                        } else {
                            svg.classList.remove('text-red-500', 'fill-current');
                            svg.classList.add('text-gray-600');
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
            }

            // Toggle Follow
            function toggleFollow(userId, btn) {
                btn.disabled = true;
                fetch(`/users/${userId}/follow`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" }
                })
                .then(response => response.json())
                .then(data => {
                    btn.disabled = false;
                    if (data.success) {
                        if (data.following) {
                            btn.innerText = "Unfollow";
                            btn.classList.remove('text-blue-500', 'hover:text-blue-700');
                            btn.classList.add('text-gray-500', 'hover:text-red-500'); 
                        } else {
                            btn.innerText = "Follow";
                            btn.classList.remove('text-gray-500', 'hover:text-red-500');
                            btn.classList.add('text-blue-500', 'hover:text-blue-700');
                        }
                        let countSpan = document.getElementById('profile-follower-count');
                        if (countSpan) countSpan.innerText = data.count;
                    }
                })
                .catch(error => { console.error('Error:', error); btn.disabled = false; });
            }

            // Toggle Comment Form (New)
            function toggleCommentBox(postId) {
                const box = document.getElementById(`comment-box-${postId}`);
                box.classList.toggle('hidden');
                // Focus input when opened
                if(!box.classList.contains('hidden')) {
                    document.getElementById(`comment-input-${postId}`).focus();
                }
            }

            // Submit Comment from Feed (New)
            function submitComment(event, postId) {
                event.preventDefault();
                const input = document.getElementById(`comment-input-${postId}`);
                const content = input.value;
                const countSpan = document.getElementById(`post-comment-count-${postId}`);

                if(!content.trim()) return;

                fetch(`/posts/${postId}/comments`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify({ content: content })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        input.value = ''; // Clear input
                        // Update count
                        if(countSpan) countSpan.innerText = parseInt(countSpan.innerText) + 1;
                        
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        </script>
    </body>
</html>