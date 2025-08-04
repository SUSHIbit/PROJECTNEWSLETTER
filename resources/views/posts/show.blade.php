<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $post->title }}
            </h2>
            <a href="{{ route('posts.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                ← Back to Posts
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Main Post -->
            <article class="bg-white shadow rounded-lg overflow-hidden mb-8">
                <!-- Post Header -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <img class="h-10 w-10 rounded-full object-cover" 
                                 src="{{ $post->user->profile_picture_url }}" 
                                 alt="{{ $post->user->name }}">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">
                                    <a href="{{ route('profile.show', $post->user->username ?: $post->user->id) }}" 
                                       class="hover:text-blue-600">
                                        {{ $post->user->display_name }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ $post->published_at ? $post->published_at->diffForHumans() : $post->created_at->diffForHumans() }}
                                    @if($post->published_at)
                                        • {{ $post->reading_time }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Post Actions for Owner -->
                        @auth
                            @if(Auth::id() === $post->user_id)
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('posts.edit', $post->id) }}" 
                                       class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        Edit
                                    </a>
                                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1 border border-red-300 shadow-sm text-xs font-medium rounded-md text-red-700 bg-white hover:bg-red-50"
                                                onclick="return confirm('Are you sure you want to delete this post?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>

                    <!-- Post Status and Category -->
                    <div class="mt-3 flex items-center space-x-2">
                        @if($post->status === 'published')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Published
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Draft
                            </span>
                        @endif
                        
                        @if($post->category)
                            <a href="{{ route('search.index', ['category' => $post->category]) }}" 
                               class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors">
                                {{ ucfirst($post->category) }}
                            </a>
                        @endif
                        
                        <span class="text-xs text-gray-500">{{ $post->views }} {{ Str::plural('view', $post->views) }}</span>
                    </div>
                </div>

                <!-- Featured Image -->
                @if($post->featured_image)
                    <div class="w-full">
                        <img src="{{ $post->featured_image }}" 
                             alt="{{ $post->title }}" 
                             class="w-full h-64 sm:h-80 object-cover">
                    </div>
                @endif

                <!-- Post Content -->
                <div class="px-6 py-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>
                    <div class="prose prose-lg max-w-none text-gray-700">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </div>

                <!-- Post Interaction Bar -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-6">
                            <!-- Like Button -->
                            @auth
                                <form action="{{ route('posts.like', $post->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="like-btn flex items-center text-sm transition-colors
                                        {{ Auth::user()->hasLiked($post) ? 'text-red-600' : 'text-gray-500 hover:text-red-600' }}">
                                        <svg class="w-5 h-5 mr-1 {{ Auth::user()->hasLiked($post) ? 'fill-current' : '' }}" 
                                             fill="{{ Auth::user()->hasLiked($post) ? 'currentColor' : 'none' }}" 
                                             stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        <span class="like-count">{{ $post->likes_count }}</span>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="flex items-center text-sm text-gray-500 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    {{ $post->likes_count }}
                                </a>
                            @endauth

                            <!-- Comment Count -->
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.003 9.003 0 01-5.34-1.74L3 21l1.36-3.66C3.53 16.38 3 14.28 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                {{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }}
                            </div>

                            <!-- Share Button -->
                            <button onclick="sharePost()" class="flex items-center text-sm text-gray-500 hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                </svg>
                                Share
                            </button>
                        </div>

                        <!-- Report Button -->
                        @auth
                            @if(Auth::id() !== $post->user_id)
                                <a href="{{ route('reports.post', $post->id) }}" 
                                   class="flex items-center text-sm text-gray-500 hover:text-red-600 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    Report Post
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </article>

            <!-- Comments Section -->
            <div class="bg-white shadow rounded-lg overflow-hidden" id="comments">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        Comments ({{ $post->comments_count }})
                    </h3>
                </div>

                <!-- Comment Form -->
                @auth
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <form action="{{ route('comments.store', $post->id) }}" method="POST">
                            @csrf
                            <div class="flex space-x-3">
                                <img class="h-8 w-8 rounded-full object-cover" 
                                     src="{{ Auth::user()->profile_picture_url }}" 
                                     alt="{{ Auth::user()->name }}">
                                <div class="flex-1">
                                    <textarea name="content" 
                                              rows="3" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                              placeholder="Write a comment..."
                                              required>{{ old('content') }}</textarea>
                                    <x-input-error :messages="$errors->get('content')" class="mt-2" />
                                    <div class="mt-2 flex justify-end">
                                        <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                            Post Comment
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <p class="text-gray-600 text-center">
                            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium">Login</a> 
                            or 
                            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium">register</a> 
                            to post a comment.
                        </p>
                    </div>
                @endauth

                <!-- Comments List -->
                <div class="divide-y divide-gray-200">
                    @php
                        $comments = $post->topLevelComments()
                                        ->with(['user', 'replies.user'])
                                        ->withCount('likes')
                                        ->latest()
                                        ->get();
                    @endphp

                    @forelse($comments as $comment)
                        <div class="px-6 py-4">
                            <!-- Main Comment -->
                            <div class="flex space-x-3">
                                <img class="h-8 w-8 rounded-full object-cover" 
                                     src="{{ $comment->user->profile_picture_url }}" 
                                     alt="{{ $comment->user->name }}">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <h4 class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('profile.show', $comment->user->username ?: $comment->user->id) }}" 
                                               class="hover:text-blue-600">
                                                {{ $comment->user->display_name }}
                                            </a>
                                        </h4>
                                        <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-700">{{ $comment->content }}</p>
                                    
                                    <!-- Comment Actions -->
                                    <div class="mt-2 flex items-center space-x-4">
                                        <!-- Like Comment -->
                                        @auth
                                            <form action="{{ route('comments.like', $comment->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="flex items-center text-xs transition-colors
                                                    {{ Auth::user()->hasLiked($comment) ? 'text-red-600' : 'text-gray-500 hover:text-red-600' }}">
                                                    <svg class="w-3 h-3 mr-1 {{ Auth::user()->hasLiked($comment) ? 'fill-current' : '' }}" 
                                                         fill="{{ Auth::user()->hasLiked($comment) ? 'currentColor' : 'none' }}" 
                                                         stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                    </svg>
                                                    {{ $comment->likes_count }}
                                                </button>
                                            </form>
                                        @else
                                            <span class="flex items-center text-xs text-gray-500">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                                {{ $comment->likes_count }}
                                            </span>
                                        @endauth

                                        <!-- Reply Button -->
                                        @auth
                                            <button onclick="toggleReplyForm('{{ $comment->id }}')" 
                                                    class="text-xs text-gray-500 hover:text-blue-600 transition-colors">
                                                Reply
                                            </button>
                                        @endauth

                                        <!-- Report Comment -->
                                        @auth
                                            @if(Auth::id() !== $comment->user_id)
                                                <a href="{{ route('reports.comment', $comment->id) }}" 
                                                   class="text-xs text-gray-500 hover:text-red-600 transition-colors">
                                                    Report
                                                </a>
                                            @endif
                                        @endauth

                                        <!-- Delete Comment (for comment owner) -->
                                        @auth
                                            @if(Auth::id() === $comment->user_id)
                                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-xs text-red-500 hover:text-red-700 transition-colors"
                                                            onclick="return confirm('Are you sure you want to delete this comment?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>

                                    <!-- Reply Form (Hidden by default) -->
                                    @auth
                                        <div id="reply-form-{{ $comment->id }}" class="mt-3 hidden">
                                            <form action="{{ route('comments.store', $post->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                <div class="flex space-x-2">
                                                    <img class="h-6 w-6 rounded-full object-cover" 
                                                         src="{{ Auth::user()->profile_picture_url }}" 
                                                         alt="{{ Auth::user()->name }}">
                                                    <div class="flex-1">
                                                        <textarea name="content" 
                                                                  rows="2" 
                                                                  class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                  placeholder="Write a reply..."
                                                                  required></textarea>
                                                        <div class="mt-1 flex justify-end space-x-2">
                                                            <button type="button" 
                                                                    onclick="toggleReplyForm('{{ $comment->id }}')"
                                                                    class="px-2 py-1 text-xs text-gray-600 hover:text-gray-800">
                                                                Cancel
                                                            </button>
                                                            <button type="submit" 
                                                                    class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                                                Reply
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    @endauth

                                    <!-- Comment Replies -->
                                    @if($comment->replies->count() > 0)
                                        <div class="mt-4 space-y-3">
                                            @foreach($comment->replies as $reply)
                                                <div class="flex space-x-3">
                                                    <img class="h-6 w-6 rounded-full object-cover" 
                                                         src="{{ $reply->user->profile_picture_url }}" 
                                                         alt="{{ $reply->user->name }}">
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-2">
                                                            <h5 class="text-sm font-medium text-gray-900">
                                                                <a href="{{ route('profile.show', $reply->user->username ?: $reply->user->id) }}" 
                                                                   class="hover:text-blue-600">
                                                                    {{ $reply->user->display_name }}
                                                                </a>
                                                            </h5>
                                                            <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="mt-1 text-sm text-gray-700">{{ $reply->content }}</p>
                                                        
                                                        <!-- Reply Actions -->
                                                        <div class="mt-1 flex items-center space-x-3">
                                                            <!-- Like Reply -->
                                                            @auth
                                                                <form action="{{ route('comments.like', $reply->id) }}" method="POST" class="inline">
                                                                    @csrf
                                                                    <button type="submit" class="flex items-center text-xs transition-colors
                                                                        {{ Auth::user()->hasLiked($reply) ? 'text-red-600' : 'text-gray-500 hover:text-red-600' }}">
                                                                        <svg class="w-3 h-3 mr-1 {{ Auth::user()->hasLiked($reply) ? 'fill-current' : '' }}" 
                                                                             fill="{{ Auth::user()->hasLiked($reply) ? 'currentColor' : 'none' }}" 
                                                                             stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                                        </svg>
                                                                        {{ $reply->likes()->count() }}
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <span class="flex items-center text-xs text-gray-500">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                                    </svg>
                                                                    {{ $reply->likes()->count() }}
                                                                </span>
                                                            @endauth

                                                            <!-- Report Reply -->
                                                            @auth
                                                                @if(Auth::id() !== $reply->user_id)
                                                                    <a href="{{ route('reports.comment', $reply->id) }}" 
                                                                       class="text-xs text-gray-500 hover:text-red-600 transition-colors">
                                                                        Report
                                                                    </a>
                                                                @endif
                                                            @endauth

                                                            <!-- Delete Reply -->
                                                            @auth
                                                                @if(Auth::id() === $reply->user_id)
                                                                    <form action="{{ route('comments.destroy', $reply->id) }}" method="POST" class="inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" 
                                                                                class="text-xs text-red-500 hover:text-red-700 transition-colors"
                                                                                onclick="return confirm('Are you sure you want to delete this reply?')">
                                                                            Delete
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            @endauth
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.003 9.003 0 01-5.34-1.74L3 21l1.36-3.66C3.53 16.38 3 14.28 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No comments yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Be the first to share your thoughts!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Interactive Features -->
    <script>
        // Toggle reply form visibility
        function toggleReplyForm(commentId) {
            const form = document.getElementById('reply-form-' + commentId);
            if (form.classList.contains('hidden')) {
                form.classList.remove('hidden');
                form.querySelector('textarea').focus();
            } else {
                form.classList.add('hidden');
            }
        }

        // Share post functionality
        function sharePost() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $post->title }}',
                    text: '{{ Str::limit($post->excerpt, 100) }}',
                    url: window.location.href
                }).catch(console.error);
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('Post URL copied to clipboard!');
                }).catch(() => {
                    // Final fallback: show URL in prompt
                    prompt('Copy this URL to share:', window.location.href);
                });
            }
        }

        // Enhanced like button handling with AJAX
        document.addEventListener('DOMContentLoaded', function() {
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
                            icon.setAttribute('fill', 'currentColor');
                            icon.classList.add('fill-current');
                            this.classList.remove('text-gray-500', 'hover:text-red-600');
                            this.classList.add('text-red-600');
                        } else {
                            // Unliked - outline the heart
                            icon.setAttribute('fill', 'none');
                            icon.classList.remove('fill-current');
                            this.classList.remove('text-red-600');
                            this.classList.add('text-gray-500', 'hover:text-red-600');
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
</x-app-layout>