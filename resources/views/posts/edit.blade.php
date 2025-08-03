<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Article Details') }}
            </h2>
            @auth
                @if(Auth::id() === $post->user_id)
                    <div class="space-x-2">
                        <a href="{{ route('posts.edit', $post->id) }}" 
                           class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors">
                            Edit Post
                        </a>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to delete this post?')"
                                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                Delete Post
                            </button>
                        </form>
                    </div>
                @endif
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

        <article class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Article Header -->
            <div class="p-6 border-b border-gray-200">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                    {{ $post->title }}
                </h1>
                
                <!-- Author Information -->
                <div class="flex items-center mb-4">
                    <img class="w-12 h-12 rounded-full object-cover mr-4" 
                         src="{{ $post->user->profile_picture_url }}" 
                         alt="{{ $post->user->name }}">
                    <div>
                        <p class="font-medium text-gray-900">
                            <a href="{{ route('profile.show', $post->user->username ?: $post->user->id) }}" 
                               class="hover:text-blue-600">
                                {{ $post->user->display_name }}
                            </a>
                        </p>
                        <p class="text-sm text-gray-500">
                            Published {{ $post->published_at->format('F j, Y') }} • 
                            {{ $post->published_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                
                <!-- Post Meta -->
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    @if($post->category)
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                            {{ ucfirst($post->category) }}
                        </span>
                    @endif
                    <span>{{ $post->reading_time }}</span>
                    <span>{{ $post->views }} {{ Str::plural('view', $post->views) }}</span>
                    @if($post->status === 'draft')
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                            Draft
                        </span>
                    @endif
                </div>
            </div>

            <!-- Featured Image -->
            @if($post->featured_image)
                <div class="w-full">
                    <img src="{{ $post->featured_image }}" 
                         alt="{{ $post->title }}"
                         class="w-full h-64 md:h-80 object-cover">
                </div>
            @endif

            <!-- Article Content -->
            <div class="p-6">
                <div class="prose max-w-none">
                    <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                        {{ $post->content }}
                    </div>
                </div>
                
                <!-- Article Actions -->
                <div class="border-t border-gray-200 pt-6 mt-8">
                    <div class="flex items-center justify-between">
                        <div class="flex space-x-6">
                            <button class="flex items-center text-gray-500 hover:text-red-600 transition-colors">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span>Like (Coming in Phase 5)</span>
                            </button>
                            
                            <button class="flex items-center text-gray-500 hover:text-blue-600 transition-colors">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.003 9.003 0 01-5.34-1.74L3 21l1.36-3.66C3.53 16.38 3 14.28 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <span>Comment (Coming in Phase 5)</span>
                            </button>
                            
                            <button class="flex items-center text-gray-500 hover:text-green-600 transition-colors">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                </svg>
                                <span>Share</span>
                            </button>
                        </div>
                        
                        <a href="{{ route('posts.index') }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium">
                            ← Back to News
                        </a>
                    </div>
                </div>
            </div>
        </article>

        <!-- Comments Section Placeholder -->
        <div class="bg-white rounded-lg shadow-sm mt-6 p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Comments</h3>
            
            <!-- Comment Form Placeholder -->
            @auth
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                              rows="3" 
                              placeholder="Write a comment..."
                              disabled></textarea>
                    <div class="mt-3 flex justify-end">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 cursor-not-allowed opacity-50" 
                                disabled>
                            Post Comment
                        </button>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Comments will be functional in Phase 5</p>
                </div>
            @else
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <p class="text-gray-500 text-center py-4">
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Login</a> 
                        or 
                        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800">register</a> 
                        to leave a comment
                    </p>
                </div>
            @endauth
            
            <!-- Sample Comments Placeholder -->
            <div class="text-center py-8 text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.003 9.003 0 01-5.34-1.74L3 21l1.36-3.66C3.53 16.38 3 14.28 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <p>No comments yet. Be the first to share your thoughts!</p>
                <p class="text-sm mt-2">Comment system will be available in Phase 5.</p>
            </div>
        </div>

        <!-- Related Posts Section -->
        <div class="bg-white rounded-lg shadow-sm mt-6 p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">More from {{ $post->user->display_name }}</h3>
            <div class="text-center py-8 text-gray-500">
                <p>Related posts feature coming soon!</p>
            </div>
        </div>
    </div>
</x-app-layout>