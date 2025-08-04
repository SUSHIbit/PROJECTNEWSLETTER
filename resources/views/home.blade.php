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
                <p class="text-gray-600 text-lg mb-4">Stay informed with the latest news and stories from around the world.</p>
                
                <!-- Quick Search -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <form action="{{ route('search.index') }}" method="GET" class="flex space-x-3">
                        <input type="text" 
                               name="q" 
                               placeholder="Search posts, users, or topics..." 
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>
                    <div class="flex space-x-4 mt-3">
                        <a href="{{ route('search.trending') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            🔥 Trending
                        </a>
                        <a href="{{ route('search.explore') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            🌟 Explore
                        </a>
                        <a href="{{ route('search.index', ['category' => 'technology']) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            💻 Technology
                        </a>
                        <a href="{{ route('search.index', ['category' => 'politics']) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            🏛️ Politics
                        </a>
                    </div>
                </div>
            </div>

            <!-- Latest Posts -->
            @if($posts->count() > 0)
                <div class="space-y-6">
                    @foreach($posts as $post)
                        <article class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex items-center mb-4">
                                    <img class="w-10 h-10 rounded-full object-cover mr-3" 
                                         src="{{ $post->user->profile_picture_url }}" 
                                         alt="{{ $post->user->name }}">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $post->user->display_name }}</p>
                                        <p class="text-sm text-gray-500">{{ $post->published_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                
                                <h2 class="text-xl font-bold text-gray-900 mb-3 hover:text-blue-600">
                                    <a href="{{ route('posts.show', $post->id) }}">
                                        {{ $post->title }}
                                    </a>
                                </h2>
                                
                                <p class="text-gray-600 mb-4">
                                    {{ $post->excerpt(200) }}
                                </p>
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex space-x-4 text-sm text-gray-500">
                                        @if($post->category)
                                            <a href="{{ route('search.index', ['category' => $post->category]) }}" 
                                               class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium hover:bg-blue-200 transition-colors">
                                                {{ ucfirst($post->category) }}
                                            </a>
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
                                        
                                        <a href="{{ route('posts.show', $post->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">Read more →</a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- View All Posts Link -->
                <div class="mt-6 text-center">
                    <a href="{{ route('posts.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        View All Posts
                        <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
            @else
                <!-- No Posts Yet -->
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
                            Create First Post
                        </a>
                    @else
                        <div class="space-x-3">
                            <a href="{{ route('login') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                Login
                            </a>
                            <a href="{{ route('register') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Join to Post
                            </a>
                        </div>
                    @endauth
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            
            <!-- Search & Discovery -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-bold text-lg text-gray-900 mb-4">🔍 Discover</h3>
                <div class="space-y-3">
                    <a href="{{ route('search.trending') }}" 
                       class="block w-full bg-gradient-to-r from-orange-500 to-red-500 text-white text-center py-2 px-4 rounded-lg hover:from-orange-600 hover:to-red-600 transition-colors font-medium">
                        🔥 Trending Posts
                    </a>
                    <a href="{{ route('search.explore') }}" 
                       class="block w-full bg-gradient-to-r from-blue-500 to-purple-500 text-white text-center py-2 px-4 rounded-lg hover:from-blue-600 hover:to-purple-600 transition-colors font-medium">
                        🌟 Explore Community
                    </a>
                    <a href="{{ route('search.index') }}" 
                       class="block w-full bg-gray-100 text-gray-700 text-center py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors">
                        Advanced Search
                    </a>
                </div>
            </div>

            <!-- Trending Categories -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-bold text-lg text-gray-900 mb-4">📂 Browse by Category</h3>
                <div class="space-y-2">
                    @foreach(['technology', 'politics', 'sports', 'health', 'science', 'business', 'entertainment'] as $category)
                        <a href="{{ route('search.index', ['category' => $category]) }}" 
                           class="block text-blue-600 hover:text-blue-800 text-sm py-1 px-2 rounded hover:bg-blue-50 transition-colors">
                            {{ ucfirst($category) }}
                        </a>
                    @endforeach
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('search.index') }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View all categories →
                    </a>
                </div>
            </div>

            <!-- Popular Hashtags -->
            @php
                // Get some sample hashtags from recent posts
                $recentPosts = \App\Models\Post::published()
                    ->where('published_at', '>=', now()->subDays(7))
                    ->pluck('content');
                
                $hashtags = [];
                foreach ($recentPosts as $content) {
                    preg_match_all('/#([a-zA-Z0-9_]+)/', $content, $matches);
                    foreach ($matches[1] as $hashtag) {
                        $hashtag = strtolower($hashtag);
                        if (isset($hashtags[$hashtag])) {
                            $hashtags[$hashtag]++;
                        } else {
                            $hashtags[$hashtag] = 1;
                        }
                    }
                }
                arsort($hashtags);
                $topHashtags = array_slice($hashtags, 0, 6, true);
            @endphp

            @if(!empty($topHashtags))
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="font-bold text-lg text-gray-900 mb-4">🏷️ Trending Hashtags</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($topHashtags as $hashtag => $count)
                            <a href="{{ route('search.index', ['hashtag' => $hashtag]) }}" 
                               class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm hover:bg-blue-200 transition-colors">
                                #{{ $hashtag }}
                                <span class="ml-1 text-xs">{{ $count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-bold text-lg text-gray-900 mb-4">🚀 Quick Actions</h3>
                <div class="space-y-3">
                    @auth
                        <a href="{{ route('posts.create') }}" class="block w-full bg-blue-600 text-white text-center py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                            Create Post
                        </a>
                        <a href="{{ route('posts.my-posts') }}" class="block w-full bg-gray-100 text-gray-700 text-center py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors">
                            My Posts
                        </a>
                        <a href="{{ route('follow.discover') }}" class="block w-full bg-gray-100 text-gray-700 text-center py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors">
                            Discover People
                        </a>
                        <a href="{{ route('dashboard') }}" class="block w-full bg-gray-100 text-gray-700 text-center py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="block w-full bg-blue-600 text-white text-center py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                            Join Community
                        </a>
                        <a href="{{ route('login') }}" class="block w-full bg-gray-100 text-gray-700 text-center py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors">
                            Sign In
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Recent Stats -->
            @if($posts->count() > 0)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="font-bold text-lg text-gray-900 mb-4">📊 Community Stats</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Posts</span>
                            <span class="font-semibold">{{ \App\Models\Post::published()->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Active Writers</span>
                            <span class="font-semibold">{{ \App\Models\User::whereHas('posts', function($q) { $q->published(); })->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">This Week</span>
                            <span class="font-semibold">{{ \App\Models\Post::published()->where('published_at', '>=', now()->subWeek())->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Comments</span>
                            <span class="font-semibold">{{ \App\Models\Comment::count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Likes</span>
                            <span class="font-semibold">{{ \App\Models\Like::count() }}</span>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('search.explore') }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View detailed stats →
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>