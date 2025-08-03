<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen">
            <!-- Navigation -->
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 mt-12">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-gray-500 text-sm">
                        © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    </p>
                </div>
            </footer>
        </div>
    </body>
</html>
// Add this script to your main layout file (layouts/app.blade.php) before closing </body> tag

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle like button clicks with AJAX
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const form = this.closest('form');
            const url = form.action;
            const csrfToken = form.querySelector('[name="_token"]').value;
            
            // Disable button temporarily
            this.disabled = true;
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update the button appearance
                const icon = this.querySelector('svg');
                const countSpan = this.querySelector('.like-count');
                
                if (data.liked) {
                    // Liked - fill the heart
                    icon.innerHTML = '<path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>';
                    icon.classList.add('text-red-600', 'fill-current');
                    icon.classList.remove('text-gray-500');
                } else {
                    // Unliked - outline the heart
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>';
                    icon.classList.remove('text-red-600', 'fill-current');
                    icon.classList.add('text-gray-500');
                    icon.setAttribute('fill', 'none');
                    icon.setAttribute('stroke', 'currentColor');
                }
                
                // Update count
                if (countSpan) {
                    countSpan.textContent = data.likes_count;
                }
                
                // Re-enable button
                this.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                // Re-enable button on error
                this.disabled = false;
                // Fallback to regular form submission
                form.submit();
            });
        });
    });
});
</script>