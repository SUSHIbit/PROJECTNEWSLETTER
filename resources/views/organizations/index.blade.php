<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Organizations') }}
            </h2>
            @auth
                <a href="{{ route('organizations.create') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Create Organization
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto">
        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Organizations</h1>
            <p class="text-gray-600">Discover and join organizations in our community</p>
        </div>

        <!-- Organizations Grid -->
        @if($organizations->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($organizations as $organization)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <!-- Organization Logo -->
                            <div class="flex items-center mb-4">
                                <img class="h-16 w-16 rounded-lg object-cover mr-4 border-2 border-gray-200" 
                                     src="{{ $organization->logo_url }}" 
                                     alt="{{ $organization->name }}">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                        <a href="{{ route('organizations.show', $organization->slug) }}" 
                                           class="hover:text-blue-600">
                                            {{ $organization->name }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        by {{ $organization->owner->display_name }}
                                    </p>
                                </div>
                            </div>

                            <!-- Organization Description -->
                            @if($organization->description)
                                <p class="text-gray-600 mb-4 line-clamp-3">
                                    {{ Str::limit($organization->description, 120) }}
                                </p>
                            @endif

                            <!-- Organization Stats -->
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <span>{{ $organization->members_count }} {{ Str::plural('member', $organization->members_count) }}</span>
                                <span>Created {{ $organization->created_at->diffForHumans() }}</span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                <a href="{{ route('organizations.show', $organization->slug) }}" 
                                   class="flex-1 text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    View Details
                                </a>
                                @auth
                                    @if($organization->canEdit(Auth::user()))
                                        <a href="{{ route('organizations.edit', $organization->slug) }}" 
                                           class="px-4 py-2 text-yellow-600 border border-yellow-600 rounded-lg hover:bg-yellow-50 transition-colors">
                                            Edit
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $organizations->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No organizations yet</h3>
                <p class="text-gray-500 mb-6">Be the first to create an organization and build your community!</p>
                @auth
                    <a href="{{ route('organizations.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                        </svg>
                        Create First Organization
                    </a>
                @else
                    <div class="space-x-3">
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Register to Create
                        </a>
                    </div>
                @endauth
            </div>
        @endif
    </div>
</x-app-layout>