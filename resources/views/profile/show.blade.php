<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->display_name }}'s Profile
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Profile Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-start space-x-6">
                        <!-- Profile Picture -->
                        <div class="flex-shrink-0">
                            <img class="h-24 w-24 rounded-full object-cover border-4 border-gray-200" 
                                 src="{{ $user->profile_picture_url }}" 
                                 alt="{{ $user->name }}">
                        </div>
                        
                        <!-- Profile Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                                    @if(!empty($user->username))
                                        <p class="text-lg text-gray-600">@{{ $user->username }}</p>
                                    @endif
                                    
                                    <!-- Account Type Badge -->
                                    @if($user->isOrganization())
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 mt-2">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-6a1 1 0 00-1-1H9a1 1 0 00-1 1v6a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"></path>
                                            </svg>
                                            Organization
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 mt-2">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                            Personal
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex space-x-3">
                                    @if($isOwnProfile)
                                        <a href="{{ route('profile.edit') }}" 
                                           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Edit Profile
                                        </a>
                                    @else
                                        @auth
                                            <!-- Follow/Unfollow Button -->
                                            <form action="{{ route('follow.toggle', $user->id) }}" method="POST" class="inline">
                                                @csrf
                                                @if(Auth::user()->isFollowing($user))
                                                    <button type="submit" 
                                                            class="follow-btn inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                        Following
                                                    </button>
                                                @else
                                                    <button type="submit" 
                                                            class="follow-btn inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                        Follow
                                                    </button>
                                                @endif
                                            </form>
                                        @endauth
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Bio -->
                            @if($user->bio)
                                <p class="mt-4 text-gray-700">{{ $user->bio }}</p>
                            @endif
                            
                            <!-- Stats -->
                            <div class="mt-4 flex space-x-6 text-sm">
                                <div class="flex items-center">
                                    <span class="font-semibold text-gray-900">{{ $user->posts_count }}</span>
                                    <span class="ml-1 text-gray-600">{{ Str::plural('post', $user->posts_count) }}</span>
                                </div>
                                <a href="{{ route('follow.followers', $user->id) }}" class="flex items-center hover:text-blue-600 transition-colors">
                                    <span class="font-semibold text-gray-900">{{ $user->followers_count }}</span>
                                    <span class="ml-1 text-gray-600">{{ Str::plural('follower', $user->followers_count) }}</span>
                                </a>
                                <a href="{{ route('follow.following', $user->id) }}" class="flex items-center hover:text-blue-600 transition-colors">
                                    <span class="font-semibold text-gray-900">{{ $user->following_count }}</span>
                                    <span class="ml-1 text-gray-600">following</span>
                                </a>
                            </div>
                            
                            <!-- Additional Info -->
                            <div class="mt-4 flex flex-wrap gap-4 text-sm text-gray-600">
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
            
            <!-- User's Posts Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Posts by {{ $user->display_name }}</h3>
                    
                    @php
                        $userPosts = $user->publishedPosts()->latest()->take(6)->get();
                    @endphp

                    @if($userPosts->count() > 0)
                        <div class="space-y-4">
                            @foreach($userPosts as $post)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-2">
                                                @if($post->category)
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium mr-2">
                                                        {{ ucfirst($post->category) }}
                                                    </span>
                                                @endif
                                                <span class="text-xs text-gray-500">{{ $post->published_at->diffForHumans() }}</span>
                                            </div>
                                            
                                            <h4 class="font-medium text-gray-900 mb-1">
                                                <a href="{{ route('posts.show', $post->id) }}" class="hover:text-blue-600">
                                                    {{ $post->title }}
                                                </a>
                                            </h4>
                                            
                                            <p class="text-sm text-gray-600 mb-2">{{ $post->excerpt(100) }}</p>
                                            
                                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                                <span>{{ $post->views }} {{ Str::plural('view', $post->views) }}</span>
                                                <span>{{ $post->reading_time }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($user->publishedPosts()->count() > 6)
                            <div class="mt-6 text-center">
                                <a href="{{ route('posts.index') }}?author={{ $user->id }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium">
                                    View all posts →
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No posts yet</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                @if($isOwnProfile)
                                    Get started by creating your first post.
                                @else
                                    {{ $user->display_name }} hasn't shared anything yet.
                                @endif
                            </p>
                            @if($isOwnProfile)
                                <div class="mt-6">
                                    <a href="{{ route('posts.create') }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Create Post
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- AJAX Follow Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const followButtons = document.querySelectorAll('.follow-btn');
            
            followButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const form = this.closest('form');
                    const url = form.action;
                    const csrfToken = form.querySelector('[name="_token"]').value;
                    
                    // Disable button temporarily
                    this.disabled = true;
                    
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Update button text and style
                        if (data.is_following) {
                            this.textContent = 'Following';
                            this.className = 'follow-btn inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150';
                        } else {
                            this.textContent = 'Follow';
                            this.className = 'follow-btn inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150';
                        }
                        
                        // Show success message (optional)
                        if (data.message) {
                            // You can add a toast notification here
                            console.log(data.message);
                        }
                        
                        // Re-enable button
                        this.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Re-enable button on error
                        this.disabled = false;
                        // Fallback to regular form submission
                        form.submit();
                    });
                });
            });
        });
    </script>
</x-app-layout>