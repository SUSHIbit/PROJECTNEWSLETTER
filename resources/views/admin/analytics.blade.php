<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Analytics & Statistics') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                ← Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- User Statistics -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">User Statistics</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-3xl font-bold text-blue-600">{{ number_format($user_stats['total_users']) }}</div>
                            <div class="text-sm text-blue-600 font-medium">Total Users</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-3xl font-bold text-green-600">{{ number_format($user_stats['personal_accounts']) }}</div>
                            <div class="text-sm text-green-600 font-medium">Personal Accounts</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-3xl font-bold text-purple-600">{{ number_format($user_stats['organization_accounts']) }}</div>
                            <div class="text-sm text-purple-600 font-medium">Organization Accounts</div>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <div class="text-3xl font-bold text-yellow-600">{{ number_format($user_stats['verified_users']) }}</div>
                            <div class="text-sm text-yellow-600 font-medium">Verified Users</div>
                        </div>
                        <div class="text-center p-4 bg-red-50 rounded-lg">
                            <div class="text-3xl font-bold text-red-600">{{ number_format($user_stats['users_this_month']) }}</div>
                            <div class="text-sm text-red-600 font-medium">New This Month</div>
                        </div>
                        <div class="text-center p-4 bg-indigo-50 rounded-lg">
                            <div class="text-3xl font-bold text-indigo-600">{{ number_format($user_stats['active_users']) }}</div>
                            <div class="text-sm text-indigo-600 font-medium">Active This Week</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Statistics -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Content Statistics</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-3xl font-bold text-blue-600">{{ number_format($content_stats['total_posts']) }}</div>
                            <div class="text-sm text-blue-600 font-medium">Total Posts</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-3xl font-bold text-green-600">{{ number_format($content_stats['published_posts']) }}</div>
                            <div class="text-sm text-green-600 font-medium">Published Posts</div>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <div class="text-3xl font-bold text-yellow-600">{{ number_format($content_stats['draft_posts']) }}</div>
                            <div class="text-sm text-yellow-600 font-medium">Draft Posts</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-3xl font-bold text-purple-600">{{ number_format($content_stats['posts_this_month']) }}</div>
                            <div class="text-sm text-purple-600 font-medium">Posts This Month</div>
                        </div>
                        <div class="text-center p-4 bg-red-50 rounded-lg">
                            <div class="text-3xl font-bold text-red-600">{{ number_format($content_stats['total_comments']) }}</div>
                            <div class="text-sm text-red-600 font-medium">Total Comments</div>
                        </div>
                        <div class="text-center p-4 bg-indigo-50 rounded-lg">
                            <div class="text-3xl font-bold text-indigo-600">{{ number_format($content_stats['comments_this_month']) }}</div>
                            <div class="text-sm text-indigo-600 font-medium">Comments This Month</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Popular Categories -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Popular Categories</h3>
                    </div>
                    <div class="p-6">
                        @if($popular_categories->count() > 0)
                            <div class="space-y-3">
                                @foreach($popular_categories as $category)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ ucfirst($category->category) }}
                                            </span>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $category->count }} posts</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No categories found</p>
                        @endif
                    </div>
                </div>

                <!-- Top Authors -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Top Authors</h3>
                    </div>
                    <div class="p-6">
                        @if($top_authors->count() > 0)
                            <div class="space-y-3">
                                @foreach($top_authors as $author)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <img class="h-8 w-8 rounded-full object-cover mr-3" 
                                                 src="{{ $author->profile_picture_url }}" 
                                                 alt="{{ $author->name }}">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    <a href="{{ route('admin.users.show', $author->id) }}" class="hover:text-blue-600">
                                                        {{ $author->display_name }}
                                                    </a>
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $author->email }}</div>
                                            </div>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $author->published_posts_count }} posts</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No authors found</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Daily Registrations Chart - SIMPLIFIED -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Daily Registrations (Last 30 Days)</h3>
                </div>
                <div class="p-6">
                    @if($daily_registrations->count() > 0)
                        <div class="space-y-2">
                            @foreach($daily_registrations as $day)
                                <div class="flex items-center">
                                    <div class="w-20 text-xs text-gray-500">
                                        {{ date('M j', strtotime($day->date)) }}
                                    </div>
                                    <div class="flex-1 ml-4">
                                        <div class="bg-gray-200 rounded-full h-3">
                                            <div class="bg-blue-600 h-3 rounded-full" style="width: {{ min(100, $day->count * 10) }}%"></div>
                                        </div>
                                    </div>
                                    <div class="w-12 text-right text-sm font-medium text-gray-900">{{ $day->count }}</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No registration data available</p>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('admin.users') }}" 
                           class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Manage Users
                        </a>
                        
                        <a href="{{ route('admin.posts') }}" 
                           class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"></path>
                            </svg>
                            Manage Posts
                        </a>
                        
                        <a href="{{ route('admin.reports') }}" 
                           class="inline-flex items-center justify-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Review Reports
                        </a>
                        
                        <a href="{{ route('admin.settings') }}" 
                           class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                            </svg>
                            Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>