<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('User Details') }} - {{ $user->display_name }}
            </h2>
            <a href="{{ route('admin.users') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                ← Back to Users
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

            <!-- User Profile -->
            <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">User Profile</h3>
                        <div class="flex items-center space-x-2">
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Suspended
                                </span>
                            @endif
                            
                            @if($user->is_admin)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Admin
                                </span>
                            @endif
                            
                            @if($user->account_type === 'organization')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Organization
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Personal
                                </span>
                            @endif
                            
                            <span class="text-sm text-gray-500">ID: {{ $user->id }}</span>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4">
                    <div class="flex items-start space-x-6">
                        <!-- Profile Picture -->
                        <div class="flex-shrink-0">
                            <img class="h-20 w-20 rounded-full object-cover border-2 border-gray-200" 
                                 src="{{ $user->profile_picture_url }}" 
                                 alt="{{ $user->name }}">
                        </div>
                        
                        <!-- User Info -->
                        <div class="flex-1">
                            <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                            @if($user->username)
                                <p class="text-gray-600">@{{ $user->username }}</p>
                            @endif
                            <p class="text-gray-600">{{ $user->email }}</p>
                            
                            @if($user->bio)
                                <p class="text-gray-700 mt-2">{{ $user->bio }}</p>
                            @endif
                            
                            <!-- Additional Info -->
                            <div class="flex flex-wrap gap-4 mt-3 text-sm text-gray-600">
                                @if($user->location)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $user->location }}
                                    </div>
                                @endif
                                
                                @if($user->website)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"></path>
                                        </svg>
                                        <a href="{{ $user->website }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                            {{ $user->website }}
                                        </a>
                                    </div>
                                @endif
                                
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    Joined {{ $user->created_at->format('F Y') }}
                                </div>
                                
                                @if($user->last_active_at)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                        Last active {{ $user->last_active_at->diffForHumans() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Statistics -->
            <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Statistics</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $user->posts_count }}</div>
                            <div class="text-sm text-blue-600">Posts</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $user->comments_count }}</div>
                            <div class="text-sm text-green-600">Comments</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">{{ $user->followers_count }}</div>
                            <div class="text-sm text-purple-600">Followers</div>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600">{{ $user->following_count }}</div>
                            <div class="text-sm text-yellow-600">Following</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Timestamps -->
            <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Account Information</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <div class="font-medium text-gray-700">Created</div>
                            <div class="text-gray-600">{{ $user->created_at->format('M j, Y g:i A') }}</div>
                            <div class="text-gray-500">{{ $user->created_at->diffForHumans() }}</div>
                        </div>
                        <div>
                            <div class="font-medium text-gray-700">Email Verified</div>
                            @if($user->email_verified_at)
                                <div class="text-gray-600">{{ $user->email_verified_at->format('M j, Y g:i A') }}</div>
                                <div class="text-gray-500">{{ $user->email_verified_at->diffForHumans() }}</div>
                            @else
                                <div class="text-red-600">Not verified</div>
                            @endif
                        </div>
                        <div>
                            <div class="font-medium text-gray-700">Last Updated</div>
                            <div class="text-gray-600">{{ $user->updated_at->format('M j, Y g:i A') }}</div>
                            <div class="text-gray-500">{{ $user->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Posts -->
            @if($recent_posts->count() > 0)
                <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Recent Posts ({{ $user->posts_count }} total)</h3>
                    </div>
                    <div class="px-6 py-4">
                        <div class="space-y-3">
                            @foreach($recent_posts as $post)
                                <div class="border border-gray-200 rounded-lg p-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center space-x-2">
                                            @if($post->status === 'published')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    Published
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Draft
                                                </span>
                                            @endif
                                            @if($post->category)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ ucfirst($post->category) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('posts.show', $post->id) }}" target="_blank" 
                                               class="text-blue-600 hover:text-blue-800 text-sm">View</a>
                                            <a href="{{ route('admin.posts.show', $post->id) }}" 
                                               class="text-green-600 hover:text-green-800 text-sm">Admin View</a>
                                        </div>
                                    </div>
                                    
                                    <h4 class="font-medium text-gray-900 mb-1">
                                        {{ $post->title }}
                                    </h4>
                                    
                                    <p class="text-sm text-gray-600 mb-2">{{ Str::limit(strip_tags($post->content), 150) }}</p>
                                    
                                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                                        @if($post->published_at)
                                            <span>Published {{ $post->published_at->diffForHumans() }}</span>
                                        @else
                                            <span>Created {{ $post->created_at->diffForHumans() }}</span>
                                        @endif
                                        <span>{{ $post->views }} views</span>
                                        <span>{{ $post->likes()->count() }} likes</span>
                                        <span>{{ $post->comments()->count() }} comments</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($user->posts_count > 5)
                            <div class="mt-4 text-center">
                                <a href="{{ route('admin.posts') }}?author={{ $user->id }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    View all {{ $user->posts_count }} posts →
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Recent Comments -->
            @if($recent_comments->count() > 0)
                <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Recent Comments ({{ $user->comments_count }} total)</h3>
                    </div>
                    <div class="px-6 py-4">
                        <div class="space-y-3">
                            @foreach($recent_comments as $comment)
                                <div class="border border-gray-200 rounded-lg p-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                        <a href="{{ route('posts.show', $comment->post->id) }}#comment-{{ $comment->id }}" target="_blank" 
                                           class="text-blue-600 hover:text-blue-800 text-sm">View Comment</a>
                                    </div>
                                    
                                    <p class="text-sm text-gray-900 mb-2">{{ $comment->content }}</p>
                                    
                                    <div class="text-xs text-gray-500">
                                        On post: <a href="{{ route('posts.show', $comment->post->id) }}" target="_blank" class="text-blue-600 hover:text-blue-800">{{ Str::limit($comment->post->title, 60) }}</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($user->comments_count > 5)
                            <div class="mt-4 text-center">
                                <span class="text-gray-500 text-sm">
                                    Showing 5 of {{ $user->comments_count }} total comments
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Admin Actions -->
            @if($user->id !== Auth::id())
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Admin Actions</h3>
                    </div>
                    <div class="px-6 py-4">
                        <div class="flex flex-wrap gap-3">
                            <!-- Toggle Admin Status -->
                            <form action="{{ route('admin.users.toggle-admin', $user->id) }}" method="POST" class="inline">
                                @csrf
                                @if($user->is_admin)
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50"
                                            onclick="return confirm('Remove admin privileges from {{ $user->name }}?')">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        Remove Admin
                                    </button>
                                @else
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 border border-green-300 text-sm font-medium rounded-md text-green-700 bg-white hover:bg-green-50"
                                            onclick="return confirm('Grant admin privileges to {{ $user->name }}?')">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        Make Admin
                                    </button>
                                @endif
                            </form>
                            
                            <!-- Toggle Account Status -->
                            <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="inline">
                                @csrf
                                @if($user->email_verified_at)
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 border border-yellow-300 text-sm font-medium rounded-md text-yellow-700 bg-white hover:bg-yellow-50"
                                            onclick="return confirm('Suspend {{ $user->name }}? This will prevent them from accessing their account.')">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Suspend User
                                    </button>
                                @else
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 border border-green-300 text-sm font-medium rounded-md text-green-700 bg-white hover:bg-green-50"
                                            onclick="return confirm('Unsuspend {{ $user->name }}? This will restore their account access.')">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Unsuspend User
                                    </button>
                                @endif
                            </form>
                            
                            <!-- View Public Profile -->
                            <a href="{{ route('profile.show', $user->username ?: $user->id) }}" target="_blank"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                </svg>
                                View Public Profile
                            </a>
                            
                            <!-- Report User -->
                            <a href="{{ route('reports.user', $user->id) }}"
                               class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Create Report
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-yellow-800 text-sm">
                            This is your own account. Admin actions are not available for your own account.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>