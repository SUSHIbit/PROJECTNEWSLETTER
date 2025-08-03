<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Article Details') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <article class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Article Header -->
            <div class="p-6 border-b border-gray-200">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                    Sample News Article {{ $id }}
                </h1>
                
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gray-300 rounded-full mr-4"></div>
                    <div>
                        <p class="font-medium text-gray-900">John Doe</p>
                        <p class="text-sm text-gray-500">Published {{ now()->subHours(2)->diffForHumans() }}</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <span>Technology</span>
                    <span>•</span>
                    <span>5 min read</span>
                    <span>•</span>
                    <span>{{ rand(50, 200) }} views</span>
                </div>
            </div>

            <!-- Featured Image Placeholder -->
            <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                <span class="text-gray-500">Featured Image Placeholder</span>
            </div>

            <!-- Article Content -->
            <div class="p-6">
                <div class="prose max-w-none">
                    <p class="text-lg text-gray-700 mb-6 leading-relaxed">
                        This is a sample article content. In future phases, this will be replaced with actual content from the database. 
                        The content will support rich text formatting, images, and other media elements.
                    </p>
                    
                    <p class="text-gray-700 mb-4 leading-relaxed">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
                        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    </p>
                    
                    <p class="text-gray-700 mb-4 leading-relaxed">
                        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
                        Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                    
                    <p class="text-gray-700 mb-6 leading-relaxed">
                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, 
                        totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                    </p>
                </div>
                
                <!-- Article Actions -->
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <div class="flex items-center justify-between">
                        <div class="flex space-x-6">
                            <button class="flex items-center text-gray-500 hover:text-red-600 transition-colors">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span>{{ rand(20, 100) }} Likes</span>
                            </button>
                            
                            <button class="flex items-center text-gray-500 hover:text-blue-600 transition-colors">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.003 9.003 0 01-5.34-1.74L3 21l1.36-3.66C3.53 16.38 3 14.28 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <span>{{ rand(5, 30) }} Comments</span>
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

        <!-- Comments Section (Placeholder) -->
        <div class="bg-white rounded-lg shadow-sm mt-6 p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Comments</h3>
            
            <!-- Comment Form (Placeholder) -->
            @auth
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                              rows="3" 
                              placeholder="Write a comment..."
                              disabled></textarea>
                    <div class="mt-3 flex justify-end">
                        <button class="px-4 py-2 bg-news-blue text-white rounded-lg hover:bg-blue-800 cursor-not-allowed opacity-50" 
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
            
            <!-- Sample Comments -->
            <div class="space-y-4">
                @for ($i = 1; $i <= 3; $i++)
                    <div class="flex space-x-3">
                        <div class="w-10 h-10 bg-gray-300 rounded-full flex-shrink-0"></div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <span class="font-medium text-gray-900">Commenter {{ $i }}</span>
                                <span class="text-sm text-gray-500">{{ now()->subMinutes($i * 15)->diffForHumans() }}</span>
                            </div>
                            <p class="text-gray-700">
                                This is a sample comment. Comments will be fully functional in Phase 5 of the development.
                            </p>
                            <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                                <button class="hover:text-blue-600">Like</button>
                                <button class="hover:text-blue-600">Reply</button>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</x-app-layout>