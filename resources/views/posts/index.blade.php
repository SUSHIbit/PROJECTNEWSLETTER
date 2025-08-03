<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('All News Posts') }}
            </h2>
            @auth
                <a href="{{ route('posts.create') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Create Post
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Latest News</h1>
            <p class="text-gray-600">Browse all news posts from our community</p>
        </div>

        <!-- Posts List -->
        @if($posts->count() > 0)
            <div class="space-y-6">
                @foreach($posts as $post)
                    <article class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <!-- Author and Date -->
                                    <div class="flex items-center mb-3">
                                        <img class="w-8 h-8 rounded-full object-cover mr-3" 
                                             src="{{ $post->user->profile_picture_url }}" 
                                             alt="{{ $post->user->name }}">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $post->user->display_name }}</p>
                                            <p class="text-sm text-gray-500">{{ $post->published_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Post Title -->
                                    <h2 class="text-xl font-bold text-gray-900 mb-3 hover:text-blue-600">
                                        <a href="{{ route('posts.show', $post->id) }}">
                                            {{ $post->title }}
                                        </a>
                                    </h2>
                                    
                                    <!-- Post Excerpt -->
                                    <p class="text-gray-600 mb-4 leading-relaxed">
                                        {{ $post->excerpt(200) }}
                                    </p>
                                    
                                    <!-- Post Meta and Actions -->
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                                            @if($post->category)
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                                    {{ ucfirst($post->category) }}
                                                </span>
                                            @endif
                                            <span>{{ $post->views }} {{ Str::plural('view', $post->views) }}</span>
                                            <span>{{ $post->reading_time }}</span>
                                        </div>
                                        
                                        <!-- Post Actions -->
                                        <div class="flex items-center space-x-4">
                                            <!-- Like Button -->
                                            @auth
                                                <form action="{{ route('posts.like', $post->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="flex items-center text-sm text-gray-500 hover:text-red-600 transition-colors">
                                                        @if(Auth::user()->hasLiked($post))
                                                            <svg class="w-4 h-4 mr-1 text-red-600 fill-current" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                            </svg>
                                                        @else
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                            </svg>
                                                        @endif
                                                        {{ $post->likes_count }}
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('login') }}" class="flex items-center text-sm text-gray-500 hover:text-red-600 transition-colors">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                    </svg>
                                                    {{ $post->likes_count }}
                                                </a>
                                            @endauth
                                            
                                            <!-- Comment Count -->
                                            <a href="{{ route('posts.show', $post->id) }}#comments" class="flex items-center text-sm text-gray-500 hover:text-blue-600 transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.003 9.003 0 01-5.34-1.74L3 21l1.36-3.66C3.53 16.38 3 14.28 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                {{ $post->comments_count }}
                                            </a>
                                            
                                            <!-- Read More Link -->
                                            <a href="{{ route('posts.show', $post->id) }}" 
                                               class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                                Read more →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Featured Image -->
                                @if($post->featured_image)
                                    <div class="w-32 h-20 bg-gray-200 rounded ml-6 flex-shrink-0 overflow-hidden">
                                        <img src="{{ $post->featured_image }}" 
                                             alt="{{ $post->title }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No posts yet</h3>
                <p class="text-gray-500 mb-6">Be the first to share a story with the community!</p>
                @auth
                    <a href="{{ route('posts.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                        </svg>
                        Create Your First Post
                    </a>
                @else
                    <div class="space-x-3">
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Register to Post
                        </a>
                    </div>
                @endauth
            </div>
        @endif
    </div>
</x-app-layout>