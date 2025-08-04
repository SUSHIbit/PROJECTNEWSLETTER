<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Trending Posts') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">🔥 Trending This Week</h1>
                    <p class="text-gray-600">Most popular posts from the last 7 days</p>
                </div>
                <div class="text-sm text-gray-500">
                    Updated {{ now()->format('M j, Y') }}
                </div>
            </div>
        </div>

        @if($posts->count() > 0)
            <div class="space-y-6">
                @foreach($posts as $index => $post)
                    <article class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <!-- Trending Rank -->
                                    <div class="flex items-center mb-3">
                                        <span class="flex items-center justify-center w-8 h-8 bg-gradient-to-r from-orange-400 to-red-500 text-white rounded-full text-sm font-bold mr-3">
                                            {{ $index + 1 }}
                                        </span>
                                        
                                        <!-- Author and Date -->
                                        <div class="flex items-center">
                                            <img class="w-8 h-8 rounded-full object-cover mr-3" 
                                                 src="{{ $post->user->profile_picture_url }}" 
                                                 alt="{{ $post->user->name }}">
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $post->user->display_name }}</p>
                                                <p class="text-sm text-gray-500">{{ $post->published_at->diffForHumans() }}</p>
                                            </div>
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
                                    
                                    <!-- Trending Stats -->
                                    <div class="flex items-center space-x-6 text-sm text-gray-500 mb-4">
                                        @if($post->category)
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                                {{ ucfirst($post->category) }}
                                            </span>
                                        @endif
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $post->likes_count }} {{ Str::plural('like', $post->likes_count) }}
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.003 9.003 0 01-5.34-1.74L3 21l1.36-3.66C3.53 16.38 3 14.28 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            {{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }}
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            {{ $post->views }} {{ Str::plural('view', $post->views) }}
                                        </span>
                                        <span>{{ $post->reading_time }}</span>
                                    </div>
                                    
                                    <!-- Actions -->
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
                                                    Like
                                                </button>
                                            </form>
                                        @endauth
                                        
                                        <!-- Comment Link -->
                                        <a href="{{ route('posts.show', $post->id) }}#comments" class="flex items-center text-sm text-gray-500 hover:text-blue-600 transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.003 9.003 0 01-5.34-1.74L3 21l1.36-3.66C3.53 16.38 3 14.28 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            Comment
                                        </a>
                                        
                                        <!-- Read More Link -->
                                        <a href="{{ route('posts.show', $post->id) }}" 
                                           class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                            Read Full Article →
                                        </a>
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
                        
                        <!-- Trending Indicator -->
                        @if($index < 3)
                            <div class="bg-gradient-to-r from-orange-400 to-red-500 h-1"></div>
                        @endif
                    </article>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No trending posts yet</h3>
                <p class="text-gray-500 mb-6">Check back later to see what's trending in the community!</p>
                <div class="space-x-3">
                    <a href="{{ route('posts.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Browse All Posts
                    </a>
                    <a href="{{ route('search.explore') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Explore Content
                    </a>
                </div>
            </div>
        @endif

        <!-- Call to Action -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-6 mt-8 text-white text-center">
            <h3 class="text-xl font-bold mb-2">Want to see your post trending?</h3>
            <p class="mb-4">Create engaging content that sparks conversations in our community!</p>
            @auth
                <a href="{{ route('posts.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-white text-blue-600 rounded-lg hover:bg-gray-100 transition-colors font-semibold">
                    Create New Post
                </a>
            @else
                <div class="space-x-3">
                    <a href="{{ route('register') }}" 
                       class="inline-flex items-center px-6 py-3 bg-white text-blue-600 rounded-lg hover:bg-gray-100 transition-colors font-semibold">
                        Join Community
                    </a>
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center px-6 py-3 border border-white text-white rounded-lg hover:bg-white hover:text-blue-600 transition-colors font-semibold">
                        Sign In
                    </a>
                </div>
            @endauth
        </div>
    </div>
</x-app-layout>