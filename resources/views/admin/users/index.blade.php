<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('User Management') }}
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

            @if (session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <form method="GET" action="{{ route('admin.users') }}" class="space-y-4 sm:space-y-0 sm:flex sm:items-center sm:space-x-4">
                        <!-- Search -->
                        <div class="flex-1">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search users by name, email, or username..." 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <!-- Account Type Filter -->
                        <div>
                            <select name="account_type" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Account Types</option>
                                <option value="personal" {{ request('account_type') === 'personal' ? 'selected' : '' }}>Personal</option>
                                <option value="organization" {{ request('account_type') === 'organization' ? 'selected' : '' }}>Organization</option>
                            </select>
                        </div>
                        
                        <!-- Admin Filter -->
                        <div>
                            <select name="is_admin" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Users</option>
                                <option value="1" {{ request('is_admin') === '1' ? 'selected' : '' }}>Admins Only</option>
                                <option value="0" {{ request('is_admin') === '0' ? 'selected' : '' }}>Non-Admins Only</option>
                            </select>
                        </div>
                        
                        <!-- Filter Button -->
                        <div class="flex space-x-2">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Filter
                            </button>
                            <a href="{{ route('admin.users') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Users Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Users ({{ $users->total() }} total)
                    </h3>
                    
                    @if($users->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stats</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($users as $user)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <img class="h-10 w-10 rounded-full object-cover" 
                                                         src="{{ $user->profile_picture_url }}" 
                                                         alt="{{ $user->name }}">
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $user->name }}
                                                            @if($user->is_admin)
                                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                                    Admin
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $user->email }}
                                                        </div>
                                                        @if($user->username)
                                                            <div class="text-sm text-gray-500">
                                                                @{{ $user->username }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($user->account_type === 'organization')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Organization
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Personal
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div>{{ $user->posts_count }} posts</div>
                                                <div>{{ $user->comments_count }} comments</div>
                                                <div>{{ $user->followers_count }} followers</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($user->email_verified_at)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Suspended
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div>{{ $user->created_at->format('M j, Y') }}</div>
                                                <div>{{ $user->created_at->diffForHumans() }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('admin.users.show', $user->id) }}" 
                                                       class="text-blue-600 hover:text-blue-900">View</a>
                                                    
                                                    @if($user->id !== Auth::id())
                                                        <form action="{{ route('admin.users.toggle-admin', $user->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            @if($user->is_admin)
                                                                <button type="submit" 
                                                                        class="text-red-600 hover:text-red-900"
                                                                        onclick="return confirm('Remove admin privileges from {{ $user->name }}?')">
                                                                    Remove Admin
                                                                </button>
                                                            @else
                                                                <button type="submit" 
                                                                        class="text-green-600 hover:text-green-900"
                                                                        onclick="return confirm('Grant admin privileges to {{ $user->name }}?')">
                                                                    Make Admin
                                                                </button>
                                                            @endif
                                                        </form>
                                                        
                                                        <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            @if($user->email_verified_at)
                                                                <button type="submit" 
                                                                        class="text-yellow-600 hover:text-yellow-900"
                                                                        onclick="return confirm('Suspend {{ $user->name }}?')">
                                                                    Suspend
                                                                </button>
                                                            @else
                                                                <button type="submit" 
                                                                        class="text-green-600 hover:text-green-900"
                                                                        onclick="return confirm('Unsuspend {{ $user->name }}?')">
                                                                    Unsuspend
                                                                </button>
                                                            @endif
                                                        </form>
                                                    @else
                                                        <span class="text-gray-400">(You)</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $users->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No users found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>