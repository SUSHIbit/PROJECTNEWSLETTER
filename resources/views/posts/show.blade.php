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
                            <!-- Like Button -->
                            @auth
                                <form action="{{ route('posts.like', $post->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="flex items-center text-gray-500 hover:text-red-600 transition-colors">
                                        @php
                                            $userLikedPost = Auth::user()->hasLikedSimple($post);
                                        @endphp
                                        @if($userLikedPost)
                                            <svg class="w-6 h-6 mr-2 text-red-600 fill-current" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                        @endif
                                        <span>{{ $post->likes_count }}</span>
                                        <span class="ml-1">{{ $post->likes_count == 1 ? 'Like' : 'Likes' }}</span>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="flex items-center text-gray-500 hover:text-red-600 transition-colors">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    <span>{{ $post->likes_count }} {{ $post->likes_count == 1 ? 'Like' : 'Likes' }}</span>
                                </a>
                            @endauth
                            
                            <!-- Comment Count -->
                            <div class="flex items-center text-gray-500">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.003 9.003 0 01-5.34-1.74L3 21l1.36-3.66C3.53 16.38 3 14.28 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <span>{{ $post->comments_count }} {{ $post->comments_count == 1 ? 'Comment' : 'Comments' }}</span>
                            </div>
                            
                            <!-- Share Button -->
                            <button onclick="sharePost()" class="flex items-center text-gray-500 hover:text-green-600 transition-colors">
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

        <!-- Comments Section -->
        <div class="bg-white rounded-lg shadow-sm mt-6 p-6" id="comments">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Comments ({{ $post->comments_count }})</h3>
            
            <!-- Comment Form -->
            @auth
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <form action="{{ route('comments.store', $post->id) }}" method="POST">
                        @csrf
                        <div class="flex space-x-3">
                            <img class="h-10 w-10 rounded-full object-cover" 
                                 src="{{ Auth::user()->profile_picture_url }}" 
                                 alt="{{ Auth::user()->name }}">
                            <div class="flex-1">
                                <textarea name="content" 
                                          rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Write a comment..." 
                                          required></textarea>
                                <div class="mt-3 flex justify-end">
                                    <button type="submit" 
                                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        Post Comment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
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
            
            <!-- Comments List -->
            @php
                // Get comments with replies
                $comments = $post->topLevelComments()
                    ->with(['user', 'replies.user'])
                    ->withCount('likes')
                    ->latest()
                    ->get();
                    
                // Load likes count for replies too
                foreach($comments as $comment) {
                    $comment->replies->load('likes');
                    foreach($comment->replies as $reply) {
                        $reply->likes_count = $reply->likes()->count();
                    }
                }
            @endphp

            @if($comments->count() > 0)
                <div class="space-y-6">
                    @foreach($comments as $comment)
                        <!-- Top Level Comment -->
                        <div class="comment">
                            <div class="flex space-x-3">
                                <img class="h-10 w-10 rounded-full object-cover" 
                                     src="{{ $comment->user->profile_picture_url }}" 
                                     alt="{{ $comment->user->name }}">
                                <div class="flex-1">
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center space-x-2">
                                                <span class="font-medium text-gray-900">{{ $comment->user->display_name }}</span>
                                                <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            @auth
                                                @if(Auth::id() === $comment->user_id)
                                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                onclick="return confirm('Are you sure you want to delete this comment?')"
                                                                class="text-red-600 hover:text-red-800 text-xs">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            @endauth
                                        </div>
                                        <p class="text-gray-700">{{ $comment->content }}</p>
                                    </div>
                                    
                                    <!-- Comment Actions -->
                                    <div class="flex items-center space-x-4 mt-2">
                                        @auth
                                            <!-- Like Comment -->
                                            <form action="{{ route('comments.like', $comment->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="flex items-center text-sm text-gray-500 hover:text-red-600 transition-colors">
                                                    @php
                                                        $userLikedComment = Auth::user()->hasLikedSimple($comment);
                                                    @endphp
                                                    @if($userLikedComment)
                                                        <svg class="w-4 h-4 mr-1 text-red-600 fill-current" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                        </svg>
                                                    @endif
                                                    {{ $comment->likes_count }}
                                                </button>
                                            </form>
                                            
                                            <!-- Reply Button -->
                                            <button onclick="toggleReplyForm('{{ $comment->id }}')" 
                                                    class="text-sm text-gray-500 hover:text-blue-600 transition-colors">
                                                Reply
                                            </button>
                                        @endauth
                                    </div>
                                    
                                    <!-- Reply Form (Hidden by default) -->
                                    @auth
                                        <div id="reply-form-{{ $comment->id }}" class="hidden mt-4">
                                            <form action="{{ route('comments.store', $post->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                <div class="flex space-x-3">
                                                    <img class="h-8 w-8 rounded-full object-cover" 
                                                         src="{{ Auth::user()->profile_picture_url }}" 
                                                         alt="{{ Auth::user()->name }}">
                                                    <div class="flex-1">
                                                        <textarea name="content" 
                                                                  rows="2"
                                                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                                                  placeholder="Write a reply..." 
                                                                  required></textarea>
                                                        <div class="mt-2 flex justify-end space-x-2">
                                                            <button type="button" 
                                                                    onclick="toggleReplyForm('{{ $comment->id }}')"
                                                                    class="px-3 py-1 text-sm text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                                                                Cancel
                                                            </button>
                                                            <button type="submit" 
                                                                    class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                                                                Reply
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    @endauth
                                    
                                    <!-- Replies -->
                                    @if($comment->replies->count() > 0)
                                        <div class="mt-4 space-y-4">
                                            @foreach($comment->replies as $reply)
                                                <div class="flex space-x-3 ml-6">
                                                    <img class="h-8 w-8 rounded-full object-cover" 
                                                         src="{{ $reply->user->profile_picture_url }}" 
                                                         alt="{{ $reply->user->name }}">
                                                    <div class="flex-1">
                                                        <div class="bg-gray-50 rounded-lg p-3">
                                                            <div class="flex items-center justify-between mb-1">
                                                                <div class="flex items-center space-x-2">
                                                                    <span class="font-medium text-gray-900 text-sm">{{ $reply->user->display_name }}</span>
                                                                    <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                                                </div>
                                                                @auth
                                                                    @if(Auth::id() === $reply->user_id)
                                                                        <form action="{{ route('comments.destroy', $reply->id) }}" method="POST" class="inline">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" 
                                                                                    onclick="return confirm('Are you sure you want to delete this reply?')"
                                                                                    class="text-red-600 hover:text-red-800 text-xs">
                                                                                Delete
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                @endauth
                                                            </div>
                                                            <p class="text-gray-700 text-sm">{{ $reply->content }}</p>
                                                        </div>
                                                        
                                                        @auth
                                                            <!-- Like Reply -->
                                                            <div class="mt-1">
                                                                <form action="{{ route('comments.like', $reply->id) }}" method="POST" class="inline">
                                                                    @csrf
                                                                    <button type="submit" class="flex items-center text-xs text-gray-500 hover:text-red-600 transition-colors">
                                                                        @php
                                                                            $userLikedReply = Auth::user()->hasLikedSimple($reply);
                                                                        @endphp
                                                                        @if($userLikedReply)
                                                                            <svg class="w-3 h-3 mr-1 text-red-600 fill-current" fill="currentColor" viewBox="0 0 24 24">
                                                                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                                            </svg>
                                                                        @else
                                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                                            </svg>
                                                                        @endif
                                                                        {{ $reply->likes_count }}
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @endauth
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.003 9.003 0 01-5.34-1.74L3 21l1.36-3.66C3.53 16.38 3 14.28 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p>No comments yet. Be the first to share your thoughts!</p>
                </div>
            @endif
        </div>

        <!-- Related Posts Section -->
        <div class="bg-white rounded-lg shadow-sm mt-6 p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">More from {{ $post->user->display_name }}</h3>
            @php
                $relatedPosts = $post->user->publishedPosts()
                    ->where('id', '!=', $post->id)
                    ->latest()
                    ->take(3)
                    ->get();
            @endphp
            
            @if($relatedPosts->count() > 0)
                <div class="space-y-4">
                    @foreach($relatedPosts as $relatedPost)
                        <div class="border-b border-gray-200 pb-4 last:border-b-0">
                            <h4 class="font-medium text-gray-900 hover:text-blue-600">
                                <a href="{{ route('posts.show', $relatedPost->id) }}">
                                    {{ $relatedPost->title }}
                                </a>
                            </h4>
                            <p class="text-sm text-gray-600 mt-1">{{ $relatedPost->excerpt(100) }}</p>
                            <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                <span>{{ $relatedPost->published_at->diffForHumans() }}</span>
                                <span>{{ $relatedPost->views }} views</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <p>No other posts from this author yet.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Toggle reply form visibility
        function toggleReplyForm(commentId) {
            const replyForm = document.getElementById('reply-form-' + commentId);
            replyForm.classList.toggle('hidden');
        }

        // Share post function
        function sharePost() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ addslashes($post->title) }}',
                    text: '{{ addslashes($post->excerpt(100)) }}',
                    url: window.location.href
                });
            } else {
                // Fallback: copy URL to clipboard
                navigator.clipboard.writeText(window.location.href).then(function() {
                    alert('Post URL copied to clipboard!');
                });
            }
        }
    </script>
</x-app-layout>