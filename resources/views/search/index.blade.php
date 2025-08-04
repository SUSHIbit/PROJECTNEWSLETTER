<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if($query)
                Search Results for "{{ $query }}"
            @elseif($category)
                {{ ucfirst($category) }} Posts
            @elseif($hashtag)
                #{{ $hashtag }} Posts
            @else
                Search
            @endif
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto">
        <!-- Search Form -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <form action="{{ route('search.index') }}" method="GET" class="space-y-4">
                <!-- Search Input -->
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <input type="text" 
                               name="q" 
                               value="{{ $query }}"
                               placeholder="Search posts, users, organizations..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               id="search-input">
                    </div>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Search Filters -->
                <div class="flex flex-wrap gap-4">
                    <!-- Search Type -->
                    <select name="type" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All Results</option>
                        <option value="posts" {{ $type === 'posts' ? 'selected' : '' }}>Posts Only</option>
                        <option value="users" {{ $type === 'users' ? 'selected' : '' }}>Users Only</option>
                        <option value="organizations" {{ $type === 'organizations' ? 'selected' : '' }}>Organizations Only</option>
                    </select>
                    
                    <!-- Category Filter -->
                    <select name="category" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Categories</option>
                        @foreach(['technology', 'politics', 'sports', 'health', 'science', 'business', 'entertainment', 'lifestyle', 'education', 'travel'] as $cat)
                            <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                        @endforeach
                    </select>
                    
                    <!-- Clear Filters -->
                    @if($query || $category || $hashtag)
                        <a href="{{ route('search.index') }}" 
                           class="px-3 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Clear Filters
                        </a>
                    @endif
                </div>
                
                <!-- Hidden inputs -->
                @if($hashtag)
                    <input type="hidden" name="hashtag" value="{{ $hashtag }}">
                @endif
            </form>
        </div>

        <!-- Search Suggestions (Auto-complete) -->
        <div id="search-suggestions" class="hidden bg-white rounded-lg shadow-lg border border-gray-200 mb-6">
            <!-- Dynamic suggestions will appear here -->
        </div>

        @if($query || $category || $hashtag)
            <!-- Results Summary -->
            <div class="bg-blue-50 rounded-lg p-4 mb-6">
                <p class="text-blue-800">
                    @if($query)
                        Showing results for "<strong>{{ $query }}</strong>"
                    @elseif($category)
                        Showing posts in <strong>{{ ucfirst($category) }}</strong> category
                    @elseif($hashtag)
                        Showing posts with hashtag <strong>#{{ $hashtag }}</strong>
                    @endif
                    
                    @if($type !== 'all')
                        ({{ $type }} only)
                    @endif
                </p>
            </div>

            <!-- Posts Results -->
            @if($posts && $posts->count() > 0 && in_array($type, ['all', 'posts']))
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Posts ({{ $posts->total() }})
                    </h3>
                    
                    <div class="space-y-6">
                        @foreach($posts as $post)
                            <article class="border-b border-gray-200 pb-6 last:border-b-0">
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
                                        <h4 class="text-xl font-bold text-gray-900 mb-3 hover:text-blue-600">
                                            <a href="{{ route('posts.show', $post->id) }}">
                                                {{ $post->title }}
                                            </a>
                                        </h4>
                                        
                                        <!-- Post Excerpt -->
                                        <p class="text-gray-600 mb-4 leading-relaxed">
                                            {{ $post->excerpt(200) }}
                                        </p>
                                        
                                        <!-- Post Meta -->
                                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                                            @if($post->category)
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                                    {{ ucfirst($post->category) }}
                                                </span>
                                            @endif
                                            <span>{{ $post->views }} {{ Str::plural('view', $post->views) }}</span>
                                            <span>{{ $post->likes_count }} {{ Str::plural('like', $post->likes_count) }}</span>
                                            <span>{{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }}</span>
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
                            </article>
                        @endforeach
                    </div>
                    
                    <!-- Posts Pagination -->
                    @if($posts->hasPages())
                        <div class="mt-6">
                            {{ $posts->appends(request()->except('posts_page'))->links() }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- Users Results -->
            @if($users && $users->count() > 0 && in_array($type, ['all', 'users']))
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Users ({{ $users->total() }})
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($users as $user)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center space-x-4">
                                    <img class="h-12 w-12 rounded-full object-cover" 
                                         src="{{ $user->profile_picture_url }}" 
                                         alt="{{ $user->name }}">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">
                                            <a href="{{ route('profile.show', $user->username ?: $user->id) }}" 
                                               class="hover:text-blue-600">
                                                {{ $user->display_name }}
                                            </a>
                                        </h4>
                                        @if($user->bio)
                                            <p class="text-sm text-gray-600">{{ Str::limit($user->bio, 80) }}</p>
                                        @endif
                                        <div class="flex space-x-4 text-xs text-gray-500 mt-1">
                                            <span>{{ $user->published_posts_count }} posts</span>
                                            <span>{{ $user->followers_count }} followers</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Users Pagination -->
                    @if($users->hasPages())
                        <div class="mt-6">
                            {{ $users->appends(request()->except('users_page'))->links() }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- Organizations Results -->
            @if($organizations && $organizations->count() > 0 && in_array($type, ['all', 'organizations']))
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Organizations ({{ $organizations->total() }})
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($organizations as $organization)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center space-x-4">
                                    <img class="h-12 w-12 rounded-lg object-cover" 
                                         src="{{ $organization->logo_url }}" 
                                         alt="{{ $organization->name }}">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">
                                            <a href="{{ route('organizations.show', $organization->slug) }}" 
                                               class="hover:text-blue-600">
                                                {{ $organization->name }}
                                            </a>
                                        </h4>
                                        @if($organization->description)
                                            <p class="text-sm text-gray-600">{{ Str::limit($organization->description, 80) }}</p>
                                        @endif
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $organization->members_count }} members
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Organizations Pagination -->
                    @if($organizations->hasPages())
                        <div class="mt-6">
                            {{ $organizations->appends(request()->except('orgs_page'))->links() }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- No Results -->
            @if(($type === 'all' || $type === 'posts') && (!$posts || $posts->count() === 0) &&
                ($type === 'all' || $type === 'users') && (!$users || $users->count() === 0) &&
                ($type === 'all' || $type === 'organizations') && (!$organizations || $organizations->count() === 0))
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">No results found</h3>
                    <p class="text-gray-500 mb-6">
                        @if($query)
                            We couldn't find anything matching "{{ $query }}". Try different keywords or browse our categories.
                        @elseif($category)
                            No posts found in the {{ $category }} category yet.
                        @elseif($hashtag)
                            No posts found with the hashtag #{{ $hashtag }} yet.
                        @endif
                    </p>
                    <div class="space-x-3">
                        <a href="{{ route('search.explore') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Explore Content
                        </a>
                        <a href="{{ route('posts.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Browse All Posts
                        </a>
                    </div>
                </div>
            @endif
        @else
            <!-- Empty Search State -->
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h3 class="text-xl font-medium text-gray-900 mb-2">Search Our Community</h3>
                <p class="text-gray-500 mb-6">Find posts, users, and organizations by entering keywords above.</p>
                <div class="space-x-3">
                    <a href="{{ route('search.trending') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        View Trending
                    </a>
                    <a href="{{ route('search.explore') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Explore
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Search Autocomplete Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const suggestionsContainer = document.getElementById('search-suggestions');
            let timeoutId;

            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                clearTimeout(timeoutId);
                
                if (query.length < 2) {
                    suggestionsContainer.classList.add('hidden');
                    return;
                }

                timeoutId = setTimeout(() => {
                    fetch(`{{ route('search.suggestions') }}?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(suggestions => {
                            if (suggestions.length > 0) {
                                let html = '<div class="p-4"><h4 class="text-sm font-medium text-gray-900 mb-2">Suggestions</h4><div class="space-y-1">';
                                
                                suggestions.forEach(suggestion => {
                                    const icon = suggestion.type === 'user' ? '👤' : 
                                               suggestion.type === 'post' ? '📄' : 
                                               suggestion.type === 'category' ? '🏷️' : '📄';
                                    
                                    html += `<a href="${suggestion.url}" class="block p-2 hover:bg-gray-50 rounded">
                                        <span class="text-sm">${icon} ${suggestion.text}</span>
                                    </a>`;
                                });
                                
                                html += '</div></div>';
                                suggestionsContainer.innerHTML = html;
                                suggestionsContainer.classList.remove('hidden');
                            } else {
                                suggestionsContainer.classList.add('hidden');
                            }
                        })
                        .catch(() => {
                            suggestionsContainer.classList.add('hidden');
                        });
                }, 300);
            });

            // Hide suggestions when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !suggestionsContainer.contains(e.target)) {
                    suggestionsContainer.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>