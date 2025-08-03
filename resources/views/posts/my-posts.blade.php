<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Posts') }}
            </h2>
            <a href="{{ route('posts.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Create New Post
            </a>
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
            <h1 class="text-2xl font-bold text-gray-900 mb-2">My Posts</h1>
            <p class="text-gray-600">Manage and view all your published and draft posts</p>
        </div>

        <!-- Posts List -->
        @if($posts->count() > 0)
            <div class="space-y-4">
                @foreach($posts as $post)
                    <article class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <!-- Post Status -->
                                <div class="flex items-center mb-2">
                                    @if($post->status === 'published')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium mr-3">
                                            Published
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium mr-3">
                                            Draft
                                        </span>
                                    @endif
                                    @if($post->category)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                            {{ ucfirst($post->category) }}
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Post Title -->
                                <h2 class="text-lg font-semibold text-gray-900 mb-2">
                                    <a href="{{ route('posts.show', $post->id) }}" class="hover:text-blue-600">
                                        {{ $post->title }}
                                    </a>
                                </h2>
                                
                                <!-- Post Excerpt -->
                                <p class="text-gray-600 mb-3">
                                    {{ $post->excerpt(150) }}
                                </p>
                                
                                <!-- Post Meta -->
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    @if($post->published_at)
                                        <span>Published {{ $post->published_at->diffForHumans() }}</span>
                                    @else
                                        <span>Last updated {{ $post->updated_at->diffForHumans() }}</span>
                                    @endif
                                    <span>{{ $post->views }} {{ Str::plural('view', $post->views) }}</span>
                                    <span>{{ $post->reading_time }}</span>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex space-x-2 ml-4">
                                <a href="{{ route('posts.show', $post->id) }}" 
                                   class="px-3 py-1 text-blue-600 border border-blue-600 rounded hover:bg-blue-50 transition-colors text-sm">
                                    View
                                </a>
                                <a href="{{ route('posts.edit', $post->id) }}" 
                                   class="px-3 py-1 text-yellow-600 border border-yellow-600 rounded hover:bg-yellow-50 transition-colors text-sm">
                                    Edit
                                </a>
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Are you sure you want to delete this post?')"
                                            class="px-3 py-1 text-red-600 border border-red-600 rounded hover:bg-red-50 transition-colors text-sm">
                                        Delete
                                    </button>
                                </form>
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
                <p class="text-gray-500 mb-6">Start sharing your stories and insights with the community!</p>
                <a href="{{ route('posts.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    Create Your First Post
                </a>
            </div>
        @endif
    </div>
</x-app-layout>