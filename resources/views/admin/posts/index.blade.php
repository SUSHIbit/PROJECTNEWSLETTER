<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Posts Management') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                ← Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <form method="GET" action="{{ route('admin.posts') }}" class="space-y-4 sm:space-y-0 sm:flex sm:items-center sm:space-x-4">
                        <!-- Search -->
                        <div class="flex-1">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search posts by title or content..." 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $posts->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No posts found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>>
                        
                        <!-- Status Filter -->
                        <div>
                            <select name="status" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Statuses</option>
                                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>
                        
                        <!-- Category Filter -->
                        <div>
                            <select name="category" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                                        {{ ucfirst($category) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Filter Button -->
                        <div class="flex space-x-2">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Filter
                            </button>
                            <a href="{{ route('admin.posts') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Posts Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Posts ({{ $posts->total() }} total)
                    </h3>
                    
                    @if($posts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Post Details</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stats</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($posts as $post)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4">
                                                <div class="flex items-start space-x-3">
                                                    @if($post->featured_image)
                                                        <img class="h-12 w-12 rounded object-cover" 
                                                             src="{{ $post->featured_image }}" 
                                                             alt="{{ $post->title }}">
                                                    @else
                                                        <div class="h-12 w-12 bg-gray-200 rounded flex items-center justify-center">
                                                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    <div class="flex-1 min-w-0">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <a href="{{ route('posts.show', $post->id) }}" class="hover:text-blue-600" target="_blank">
                                                                {{ Str::limit($post->title, 60) }}
                                                            </a>
                                                        </div>
                                                        <div class="text-sm text-gray-500 mt-1">
                                                            {{ Str::limit(strip_tags($post->content), 100) }}
                                                        </div>
                                                        @if($post->category)
                                                            <div class="mt-2">
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                    {{ ucfirst($post->category) }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <img class="h-8 w-8 rounded-full object-cover" 
                                                         src="{{ $post->user->profile_picture_url }}" 
                                                         alt="{{ $post->user->name }}">
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $post->user->display_name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $post->user->email }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div>{{ $post->views }} views</div>
                                                <div>{{ $post->likes_count }} likes</div>
                                                <div>{{ $post->comments_count }} comments</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($post->status === 'published')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Published
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Draft
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div>{{ $post->created_at->format('M j, Y') }}</div>
                                                <div>{{ $post->created_at->diffForHumans() }}</div>
                                                @if($post->published_at && $post->published_at != $post->created_at)
                                                    <div class="text-xs text-green-600 mt-1">
                                                        Published {{ $post->published_at->diffForHumans() }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('posts.show', $post->id) }}" 
                                                       class="text-blue-600 hover:text-blue-900" target="_blank">View</a>
                                                    
                                                    <a href="{{ route('admin.posts.show', $post->id) }}" 
                                                       class="text-green-600 hover:text-green-900">Details</a>
                                                    
                                                    <form action="{{ route('admin.posts.delete', $post->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-900"
                                                                onclick="return confirm('Are you sure you want to delete this post? This action cannot be undone.')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div