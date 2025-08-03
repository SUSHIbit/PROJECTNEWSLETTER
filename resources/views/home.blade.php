<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Latest News') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content Area -->
        <div class="lg:col-span-2">
            <!-- Hero Section -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h1 class="text-3xl font-bold text-news-blue mb-2">Welcome to {{ config('app.name') }}</h1>
                <p class="text-gray-600 text-lg">Stay informed with the latest news and stories from around the world.</p>
            </div>

            <!-- Sample News Cards (Placeholder) -->
            <div class="space-y-6">
                @for ($i = 1; $i <= 3; $i++)
                    <article class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-gray-300 rounded-full mr-3"></div>
                                <div>
                                    <p class="font-medium text-gray-900">Sample Author {{ $i }}</p>
                                    <p class="text-sm text-gray-500">{{ now()->subHours($i)->diffForHumans() }}</p>
                                </div>
                            </div>
                            
                            <h2 class="text-xl font-bold text-gray-900 mb-3">
                                Sample News Article {{ $i }}
                            </h2>
                            
                            <p class="text-gray-600 mb-4">
                                This is a sample news article excerpt. In the future phases, this will be replaced with real content from the database.
                            </p>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex space-x-4">
                                    <button class="flex items-center text-gray-500 hover:text-blue-600">
                                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        Like
                                    </button>
                                    <button class="flex items-center text-gray-500 hover:text-blue-600">
                                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.003 9.003 0 01-5.34-1.74L3 21l1.36-3.66C3.53 16.38 3 14.28 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        Comment
                                    </button>
                                </div>
                                <a href="{{ route('posts.show', $i) }}" class="text-blue-600 hover:text-blue-800">Read more</a>
                            </div>
                        </div>
                    </article>
                @endfor
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Trending Topics (Placeholder) -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-bold text-lg text-gray-900 mb-4">Trending Topics</h3>
                <div class="space-y-2">
                    @foreach(['Technology', 'Politics', 'Sports', 'Health', 'Science'] as $topic)
                        <a href="#" class="block text-blue-600 hover:text-blue-800">#{{ $topic }}</a>
                    @endforeach
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-bold text-lg text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    @auth
                        <a href="{{ route('posts.create') }}" class="block w-full bg-news-blue text-white text-center py-2 px-4 rounded-lg hover:bg-blue-800">
                            Create Post
                        </a>
                        <a href="{{ route('dashboard') }}" class="block w-full bg-gray-100 text-gray-700 text-center py-2 px-4 rounded-lg hover:bg-gray-200">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="block w-full bg-news-blue text-white text-center py-2 px-4 rounded-lg hover:bg-blue-800">
                            Join Community
                        </a>
                        <a href="{{ route('login') }}" class="block w-full bg-gray-100 text-gray-700 text-center py-2 px-4 rounded-lg hover:bg-gray-200">
                            Sign In
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>