<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Discover People') }}
        </h2>
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
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Discover People</h1>
            <p class="text-gray-600">Find interesting people to follow and expand your network</p>
        </div>

        <!-- Suggested Users -->
        @if($suggestedUsers->count() > 0)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Suggested for you</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($suggestedUsers as $user)
                        <div class="border border-gray-200 rounded-lg p-6 text-center hover:shadow-md transition-shadow">
                            <img class="h-20 w-20 rounded-full object-cover mx-auto mb-4" 
                                 src="{{ $user->profile_picture_url }}" 
                                 alt="{{ $user->name }}">
                            
                            <h4 class="font-semibold text-gray-900 mb-1">
                                <a href="{{ route('profile.show', $user->username ?: $user->id) }}" 
                                   class="hover:text-blue-600">
                                    {{ $user->display_name }}
                                </a>
                            </h4>
                            
                            @if($user->bio)
                                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($user->bio, 60) }}</p>
                            @endif
                            
                            <div class="flex justify-center space-x-4 text-xs text-gray-500 mb-4">
                                <span>{{ $user->followers_count }} followers</span>
                                <span>{{ $user->published_posts_count }} posts</span>
                            </div>
                            
                            <!-- Account Type Badge -->
                            @if($user->isOrganization())
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mb-3">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-6a1 1 0 00-1-1H9a1 1 0 00-1 1v6a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"></path>
                                    </svg>
                                    Organization
                                </span>
                            @endif
                            
                            <form action="{{ route('follow.toggle', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="w-full px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                    Follow
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No suggestions available</h3>
                <p class="text-gray-500">You're already following everyone on the platform!</p>
            </div>
        @endif
    </div>
</x-app-layout>