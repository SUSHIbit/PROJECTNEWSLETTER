<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $organization->name }}
            </h2>
            @auth
                @if($organization->canEdit(Auth::user()))
                    <div class="space-x-2">
                        <a href="{{ route('organizations.edit', $organization->slug) }}" 
                           class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors">
                            Edit Organization
                        </a>
                        <a href="{{ route('organizations.invite', $organization->slug) }}" 
                           class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            Invite Members
                        </a>
                    </div>
                @endif
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

        <!-- Organization Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-start space-x-6">
                <!-- Organization Logo -->
                <div class="flex-shrink-0">
                    <img class="h-24 w-24 rounded-lg object-cover border-4 border-gray-200" 
                         src="{{ $organization->logo_url }}" 
                         alt="{{ $organization->name }}">
                </div>
                
                <!-- Organization Info -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $organization->name }}</h1>
                            <p class="text-lg text-gray-600 mb-3">
                                Owned by 
                                <a href="{{ route('profile.show', $organization->owner->username ?: $organization->owner->id) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    {{ $organization->owner->display_name }}
                                </a>
                            </p>
                            
                            @if($organization->description)
                                <p class="text-gray-700 mb-4">{{ $organization->description }}</p>
                            @endif
                            
                            <!-- Organization Stats -->
                            <div class="flex space-x-6 text-sm">
                                <div class="flex items-center">
                                    <span class="font-semibold text-gray-900">{{ $organization->members_count }}</span>
                                    <span class="ml-1 text-gray-600">{{ Str::plural('member', $organization->members_count) }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-semibold text-gray-900">{{ $posts->count() }}</span>
                                    <span class="ml-1 text-gray-600">recent {{ Str::plural('post', $posts->count()) }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-600">Created {{ $organization->created_at->format('F Y') }}</span>
                                </div>
                            </div>
                            
                            <!-- Contact Info -->
                            <div class="mt-4 flex flex-wrap gap-4 text-sm text-gray-600">
                                @if($organization->website)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"></path>
                                        </svg>
                                        <a href="{{ $organization->website }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                            {{ $organization->website }}
                                        </a>
                                    </div>
                                @endif
                                
                                @if($organization->email)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                        </svg>
                                        <a href="mailto:{{ $organization->email }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $organization->email }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6">
                    <span class="py-4 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                        Posts
                    </span>
                    <a href="{{ route('organizations.members', $organization->slug) }}" 
                       class="py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Members
                    </a>
                </nav>
            </div>
        </div>

        <!-- Organization Posts -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Recent Posts</h3>
            
            @if($posts->count() > 0)
                <div class="space-y-6">
                    @foreach($posts as $post)
                        <article class="border-b border-gray-200 pb-6 last:border-b-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <!-- Author and Date -->
                                    <div class="flex items-center mb-3">
                                        <img class="w-8 h-8 rounded-full object-cover mr-3" 
                                             src="{{ $post->user->profile_picture_url }}" 
                                             alt="{{ $post->user->name }}">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $post->user->display_name }}</p>
                                            <p class="text-sm text-gray-500">{{ $post->published_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Post Title -->
                                    <h4 class="text-xl font-bold text-gray-900 mb-3 hover:text-blue-600">
                                        <a href="{{ route('posts.show', $post->id) }}">
                                            {{ $post->title }}
                                        </a>
                                    </h4>
                                    
                                    <!-- Post Excerpt -->
                                    <p class="text-gray-600 mb-4 leading-relaxed">
                                        {{ $post->excerpt(200) }}
                                    </p>
                                    
                                    <!-- Post Meta -->
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        @if($post->category)
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                                {{ ucfirst($post->category) }}
                                            </span>
                                        @endif
                                        <span>{{ $post->views }} {{ Str::plural('view', $post->views) }}</span>
                                        <span>{{ $post->reading_time }}</span>
                                    </div>
                                </div>
                                
                                <!-- Featured Image -->
                                @if($post->featured_image)
                                    <div class="w-32 h-20 bg-gray-200 rounded ml-6 flex-shrink-0 overflow-hidden">
                                        <img src="{{ $post->featured_image }}" 
                                             alt="{{ $post->title }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <!-- No Posts -->
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No posts yet</h3>
                    <p class="text-gray-500 mb-6">This organization hasn't published any posts yet.</p>
                    @auth
                        @if($organization->hasMember(Auth::user()))
                            <a href="{{ route('posts.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Create First Post
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </div>
</x-app-layout>