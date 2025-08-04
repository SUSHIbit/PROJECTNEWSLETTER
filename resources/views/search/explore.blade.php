<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Explore') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">🌟 Explore Our Community</h1>
            <p class="text-gray-600">Discover trending content, popular categories, and active community members</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Featured Posts -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">⭐ Featured Posts</h3>
                        <a href="{{ route('search.trending') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View Trending →
                        </a>
                    </div>
                    
                    @if($featuredPosts->count() > 0)
                        <div class="space-y-4">
                            @foreach($featuredPosts as $post)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-2">
                                                <img class="w-6 h-6 rounded-full object-cover mr-2" 
                                                     src="{{ $post->user->profile_picture_url }}" 
                                                     alt="{{ $post->user->name }}">
                                                <span class="text-sm font-medium text-gray-900">{{ $post->user->display_name }}</span>
                                                <span class="text-sm text-gray-500 ml-2">{{ $post->published_at->diffForHumans() }}</span>
                                            </div>
                                            
                                            <h4 class="font-medium text-gray-900 mb-2">
                                                <a href="{{ route('posts.show', $post->id) }}" class="hover:text-blue-600">
                                                    {{ $post->title }}
                                                </a>
                                            </h4>
                                            
                                            <p class="text-sm text-gray-600 mb-2">{{ $post->excerpt(100) }}</p>
                                            
                                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                                @if($post->category)
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
                                                        {{ ucfirst($post->category) }}
                                                    </span>
                                                @endif
                                                <span>{{ $post->likes_count }} likes</span>
                                                <span>{{ $post->comments_count }} comments</span>
                                                <span>{{ $post->views }} views</span>
                                            </div>
                                        </div>
                                        
                                        @if($post->featured_image)
                                            <div class="w-16 h-12 bg-gray-200 rounded ml-4 flex-shrink-0 overflow-hidden">
                                                <img src="{{ $post->featured_image }}" 
                                                     alt="{{ $post->title }}"
                                                     class="w-full h-full object-cover">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No featured posts available yet.</p>
                    @endif
                </div>

                <!-- Recent Posts -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">🕐 Latest Posts</h3>
                        <a href="{{ route('posts.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View All →
                        </a>
                    </div>
                    
                    @if($recentPosts->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentPosts as $post)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-2">
                                                <img class="w-6 h-6 rounded-full object-cover mr-2" 
                                                     src="{{ $post->user->profile_picture_url }}" 
                                                     alt="{{ $post->user->name }}">
                                                <span class="text-sm font-medium text-gray-900">{{ $post->user->display_name }}</span>
                                                <span class="text-sm text-gray-500 ml-2">{{ $post->published_at->diffForHumans() }}</span>
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium ml-2">
                                                    New
                                                </span>
                                            </div>
                                            
                                            <h4 class="font-medium text-gray-900 mb-2">
                                                <a href="{{ route('posts.show', $post->id) }}" class="hover:text-blue-600">
                                                    {{ $post->title }}
                                                </a>
                                            </h4>
                                            
                                            <p class="text-sm text-gray-600 mb-2">{{ $post->excerpt(100) }}</p>
                                            
                                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                                @if($post->category)
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
                                                        {{ ucfirst($post->category) }}
                                                    </span>
                                                @endif
                                                <span>{{ $post->likes_count }} likes</span>
                                                <span>{{ $post->comments_count }} comments</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No recent posts available.</p>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                
                <!-- Popular Categories -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🏷️ Popular Categories</h3>
                    
                    @if($popularCategories->count() > 0)
                        <div class="space-y-3">
                            @foreach($popularCategories as $category)
                                <div class="flex items-center justify-between">
                                    <a href="{{ route('search.index', ['category' => $category->category]) }}" 
                                       class="text-blue-600 hover:text-blue-800 font-medium">
                                        {{ ucfirst($category->category) }}
                                    </a>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">
                                        {{ $category->post_count }} {{ Str::plural('post', $category->post_count) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">No categories available yet.</p>
                    @endif
                </div>

                <!-- Popular Hashtags -->
                @if(!empty($popularHashtags))
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">🔥 Trending Hashtags</h3>
                        
                        <div class="flex flex-wrap gap-2">
                            @foreach($popularHashtags as $hashtag => $count)
                                <a href="{{ route('search.index', ['hashtag' => $hashtag]) }}" 
                                   class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm hover:bg-blue-200 transition-colors">
                                    #{{ $hashtag }}
                                    <span class="ml-1 text-xs">{{ $count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Active Users -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">👥 Active Contributors</h3>
                    
                    @if($activeUsers->count() > 0)
                        <div class="space-y-3">
                            @foreach($activeUsers as $user)
                                <div class="flex items-center space-x-3">
                                    <img class="h-8 w-8 rounded-full object-cover" 
                                         src="{{ $user->profile_picture_url }}" 
                                         alt="{{ $user->name }}">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            <a href="{{ route('profile.show', $user->username ?: $user->id) }}" 
                                               class="hover:text-blue-600">
                                                {{ $user->display_name }}
                                            </a>
                                        </p>
                                        <div class="flex items-center space-x-2 text-xs text-gray-500">
                                            <span>{{ $user->published_posts_count }} posts</span>
                                            <span>•</span>
                                            <span>{{ $user->followers_count }} followers</span>
                                        </div>
                                    </div>
                                    
                                    @auth
                                        @if(Auth::id() !== $user->id)
                                            <form action="{{ route('follow.toggle', $user->id) }}" method="POST" class="inline">
                                                @csrf
                                                @if(Auth::user()->isFollowing($user))
                                                    <button type="submit" 
                                                            class="px-2 py-1 text-xs text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                                                        Following
                                                    </button>
                                                @else
                                                    <button type="submit" 
                                                            class="px-2 py-1 text-xs text-white bg-blue-600 rounded hover:bg-blue-700">
                                                        Follow
                                                    </button>
                                                @endif
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4 text-center">
                            <a href="{{ route('follow.discover') }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Discover More People →
                            </a>
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">No active users this week.</p>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🚀 Quick Actions</h3>
                    
                    <div class="space-y-3">
                        @auth
                            <a href="{{ route('posts.create') }}" 
                               class="w-full flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                                </svg>
                                Create Post
                            </a>
                            
                            <a href="{{ route('follow.discover') }}" 
                               class="w-full flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Find People
                            </a>
                            
                            <a href="{{ route('organizations.create') }}" 
                               class="w-full flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-6a1 1 0 00-1-1H9a1 1 0 00-1 1v6a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"></path>
                                </svg>
                                Create Organization
                            </a>
                        @else
                            <a href="{{ route('register') }}" 
                               class="w-full flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                                Join Community
                            </a>
                            
                            <a href="{{ route('login') }}" 
                               class="w-full flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Sign In
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Community Stats -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Community Stats</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Posts</span>
                            <span class="font-semibold">{{ \App\Models\Post::published()->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Active Users</span>
                            <span class="font-semibold">{{ \App\Models\User::whereHas('posts', function($q) { $q->published()->where('published_at', '>=', now()->subWeek()); })->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Organizations</span>
                            <span class="font-semibold">{{ \App\Models\Organization::count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">This Week</span>
                            <span class="font-semibold">{{ \App\Models\Post::published()->where('published_at', '>=', now()->subWeek())->count() }} new posts</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>