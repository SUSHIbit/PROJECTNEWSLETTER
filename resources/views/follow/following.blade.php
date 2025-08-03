<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->display_name }} Following
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <!-- User Info Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center space-x-4">
                <img class="h-16 w-16 rounded-full object-cover" 
                     src="{{ $user->profile_picture_url }}" 
                     alt="{{ $user->name }}">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">{{ $user->display_name }}</h1>
                    <div class="flex space-x-4 text-sm text-gray-600">
                        <span>{{ $user->followers_count }} followers</span>
                        <span>{{ $user->following_count }} following</span>
                        <span>{{ $user->posts_count }} posts</span>
                    </div>
                </div>
            </div>
            
            <!-- Navigation Tabs -->
            <div class="mt-6 border-b border-gray-200">
                <nav class="flex space-x-8">
                    <a href="{{ route('profile.show', $user->username ?: $user->id) }}" 
                       class="py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Posts
                    </a>
                    <a href="{{ route('follow.followers', $user->id) }}" 
                       class="py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Followers
                    </a>
                    <span class="py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                        Following
                    </span>
                </nav>
            </div>
        </div>

        <!-- Following List -->
        @if($following->count() > 0)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Following ({{ $following->total() }})
                </h3>
                
                <div class="space-y-4">
                    @foreach($following as $followedUser)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <img class="h-12 w-12 rounded-full object-cover" 
                                     src="{{ $followedUser->profile_picture_url }}" 
                                     alt="{{ $followedUser->name }}">
                                <div>
                                    <h4 class="font-medium text-gray-900">
                                        <a href="{{ route('profile.show', $followedUser->username ?: $followedUser->id) }}" 
                                           class="hover:text-blue-600">
                                            {{ $followedUser->display_name }}
                                        </a>
                                    </h4>
                                    @if($followedUser->bio)
                                        <p class="text-sm text-gray-600">{{ Str::limit($followedUser->bio, 80) }}</p>
                                    @endif
                                    <div class="flex space-x-4 text-xs text-gray-500 mt-1">
                                        <span>{{ $followedUser->followers_count }} followers</span>
                                        <span>{{ $followedUser->posts_count }} posts</span>
                                    </div>
                                </div>
                            </div>
                            
                            @auth
                                @if(Auth::id() !== $followedUser->id)
                                    <form action="{{ route('follow.toggle', $followedUser->id) }}" method="POST" class="inline">
                                        @csrf
                                        @if(Auth::user()->isFollowing($followedUser))
                                            <button type="submit" 
                                                    class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition-colors">
                                                Following
                                            </button>
                                        @else
                                            <button type="submit" 
                                                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                                Follow
                                            </button>
                                        @endif
                                    </form>
                                @endif
                            @endauth
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($following->hasPages())
                    <div class="mt-6">
                        {{ $following->links() }}
                    </div>
                @endif
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Not following anyone</h3>
                <p class="text-gray-500 mb-4">
                    {{ $user->id === Auth::id() ? 'You\'re not following anyone yet.' : $user->display_name . ' isn\'t following anyone yet.' }}
                </p>
                @if($user->id === Auth::id())
                    <a href="{{ route('follow.discover') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Discover People
                    </a>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>