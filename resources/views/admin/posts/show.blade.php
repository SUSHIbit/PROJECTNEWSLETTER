<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Post Details') }}
            </h2>
            <a href="{{ route('admin.posts') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                ← Back to Posts
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Post Details -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Post Information</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('posts.show', $post->id) }}" target="_blank"
                               class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                View Post
                            </a>
                            <form action="{{ route('admin.posts.delete', $post->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700"
                                        onclick="return confirm('Are you sure you want to delete this post? This action cannot be undone.')">
                                    Delete Post
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4">
                    <!-- Post Header -->
                    <div class="mb-6">
                        @if($post->featured_image)
                            <img class="w-full h-64 object-cover rounded-lg mb-4" 
                                 src="{{ $post->featured_image }}" 
                                 alt="{{ $post->title }}">
                        @endif
                        
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                @if($post->status === 'published')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-2">
                                        Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mr-2">
                                        Draft
                                    </span>
                                @endif
                                
                                @if($post->category)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($post->category) }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="text-sm text-gray-500">
                                ID: {{ $post->id }}
                            </div>
                        </div>
                        
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $post->title }}</h1>
                    </div>

                    <!-- Author Information -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Author Information</h4>
                        <div class="flex items-center">
                            <img class="h-10 w-10 rounded-full object-cover mr-3" 
                                 src="{{ $post->user->profile_picture_url }}" 
                                 alt="{{ $post->user->name }}">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    <a href="{{ route('admin.users.show', $post->user->id) }}" class="hover:text-blue-600">
                                        {{ $post->user->name }}
                                    </a>
                                    @if($post->user->is_admin)
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                            Admin
                                        </span>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500">{{ $post->user->email }}</div>
                                <div class="text-sm text-gray-500">@if($post->user->username)@{{ $post->user->username }}@else No username @endif</div>
                            </div>
                        </div>
                    </div>

                    <!-- Post Statistics -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $post->views }}</div>
                            <div class="text-sm text-blue-600">Views</div>
                        </div>
                        <div class="bg-red-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-red-600">{{ $post->likes_count }}</div>
                            <div class="text-sm text-red-600">Likes</div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $post->comments_count }}</div>
                            <div class="text-sm text-green-600">Comments</div>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ $post->reading_time }}</div>
                            <div class="text-sm text-purple-600">Read Time</div>
                        </div>
                    </div>

                    <!-- Post Timestamps -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Timestamps</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <div class="font-medium text-gray-700">Created</div>
                                <div class="text-gray-600">{{ $post->created_at->format('M j, Y g:i A') }}</div>
                                <div class="text-gray-500">{{ $post->created_at->diffForHumans() }}</div>
                            </div>
                            @if($post->published_at)
                                <div>
                                    <div class="font-medium text-gray-700">Published</div>
                                    <div class="text-gray-600">{{ $post->published_at->format('M j, Y g:i A') }}</div>
                                    <div class="text-gray-500">{{ $post->published_at->diffForHumans() }}</div>
                                </div>
                            @endif
                            <div>
                                <div class="font-medium text-gray-700">Last Updated</div>
                                <div class="text-gray-600">{{ $post->updated_at->format('M j, Y g:i A') }}</div>
                                <div class="text-gray-500">{{ $post->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Post Content -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Content</h4>
                        <div class="prose max-w-none border border-gray-200 rounded-lg p-4 bg-gray-50">
                            {!! nl2br(e($post->content)) !!}
                        </div>
                    </div>

                    <!-- Comments Section -->
                    @if($post->comments_count > 0)
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Recent Comments ({{ $post->comments_count }} total)</h4>
                            <div class="space-y-3">
                                @foreach($post->comments()->with('user')->latest()->take(5)->get() as $comment)
                                    <div class="border border-gray-200 rounded-lg p-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center">
                                                <img class="h-6 w-6 rounded-full object-cover mr-2" 
                                                     src="{{ $comment->user->profile_picture_url }}" 
                                                     alt="{{ $comment->user->name }}">
                                                <span class="text-sm font-medium text-gray-900">{{ $comment->user->display_name }}</span>
                                            </div>
                                            <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-700">{{ $comment->content }}</p>
                                    </div>
                                @endforeach
                                @if($post->comments_count > 5)
                                    <div class="text-center">
                                        <a href="{{ route('posts.show', $post->id) }}#comments" target="_blank" 
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View all {{ $post->comments_count }} comments →
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>