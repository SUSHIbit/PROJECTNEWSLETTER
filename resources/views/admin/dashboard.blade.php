<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Admin Dashboard') }}
            </h2>
            <div class="text-sm text-gray-600">
                Welcome back, {{ Auth::user()->name }}!
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Users -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_users']) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <span class="text-green-600 font-medium">+{{ $stats['new_users_this_week'] }}</span>
                            <span class="text-gray-500">this week</span>
                        </div>
                    </div>
                </div>

                <!-- Total Posts -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Posts</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_posts']) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <span class="text-blue-600 font-medium">{{ $stats['published_posts'] }} published</span>
                            <span class="text-gray-500">{{ $stats['draft_posts'] }} drafts</span>
                        </div>
                    </div>
                </div>

                <!-- Total Comments -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Comments</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_comments']) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <span class="text-green-600 font-medium">{{ $stats['total_organizations'] }}</span>
                            <span class="text-gray-500">organizations</span>
                        </div>
                    </div>
                </div>

                <!-- Pending Reports -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Pending Reports</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['pending_reports']) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <a href="{{ route('admin.reports') }}" class="text-red-600 font-medium hover:text-red-800">
                                Review reports →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Users -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Users</h3>
                        <div class="space-y-3">
                            @foreach($recent_users as $user)
                                <div class="flex items-center space-x-3">
                                    <img class="h-8 w-8 rounded-full object-cover" 
                                         src="{{ $user->profile_picture_url }}" 
                                         alt="{{ $user->name }}">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $user->name }}
                                            @if($user->is_admin)
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                    Admin
                                                </span>
                                            @endif
                                        </p>
                                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $user->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.users') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                View all users →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Posts -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Posts</h3>
                        <div class="space-y-3">
                            @foreach($recent_posts as $post)
                                <div class="flex items-start space-x-3">
                                    <img class="h-8 w-8 rounded-full object-cover" 
                                         src="{{ $post->user->profile_picture_url }}" 
                                         alt="{{ $post->user->name }}">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('posts.show', $post->id) }}" class="hover:text-blue-600">
                                                {{ Str::limit($post->title, 60) }}
                                            </a>
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            by {{ $post->user->display_name }} • {{ $post->created_at->diffForHumans() }}
                                        </p>
                                        <div class="flex items-center space-x-2 mt-1">
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
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.posts') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                View all posts →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Pending Reports -->
                @if($pending_reports->count() > 0)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Pending Reports</h3>
                        <div class="space-y-3">
                            @foreach($pending_reports as $report)
                                <div class="flex items-start space-x-3">
                                    <img class="h-8 w-8 rounded-full object-cover" 
                                         src="{{ $report->reporter->profile_picture_url }}" 
                                         alt="{{ $report->reporter->name }}">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $report->formatted_reason }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Reported by {{ $report->reporter->display_name }} • {{ $report->created_at->diffForHumans() }}
                                        </p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            @if($report->reportable_type === 'App\Models\Post')
                                                Post: {{ Str::limit($report->reportable->title, 50) }}
                                            @elseif($report->reportable_type === 'App\Models\Comment')
                                                Comment: {{ Str::limit($report->reportable->content, 50) }}
                                            @else
                                                User: {{ $report->reportable->display_name }}
                                            @endif
                                        </p>
                                    </div>
                                    <a href="{{ route('admin.reports.show', $report->id) }}" 
                                       class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                        Review
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.reports') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                View all reports →
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Popular Posts -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Popular Posts</h3>
                        <div class="space-y-3">
                            @foreach($popular_posts as $post)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 text-sm text-gray-500 font-medium w-12">
                                        #{{ $loop->iteration }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('posts.show', $post->id) }}" class="hover:text-blue-600">
                                                {{ Str::limit($post->title, 60) }}
                                            </a>
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $post->views }} views • {{ $post->likes_count }} likes • {{ $post->comments_count }} comments
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.analytics') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                View analytics →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('admin.users') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Manage Users
                        </a>
                        
                        <a href="{{ route('admin.posts') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"></path>
                            </svg>
                            Manage Posts
                        </a>
                        
                        <a href="{{ route('admin.reports') }}" 
                           class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Review Reports
                            @if($stats['pending_reports'] > 0)
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ $stats['pending_reports'] }}
                                </span>
                            @endif
                        </a>
                        
                        <a href="{{ route('admin.analytics') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            View Analytics
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>