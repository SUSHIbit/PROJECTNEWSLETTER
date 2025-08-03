<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All News Posts') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Latest News</h1>
            <p class="text-gray-600">Browse all news posts from our community</p>
        </div>

        <!-- Posts List (Placeholder) -->
        <div class="space-y-4">
            @for ($i = 1; $i <= 5; $i++)
                <article class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <div class="w-8 h-8 bg-gray-300 rounded-full mr-2"></div>
                                <span class="font-medium text-gray-900">Author {{ $i }}</span>
                                <span class="text-gray-500 mx-2">•</span>
                                <span class="text-sm text-gray-500">{{ now()->subHours($i * 2)->diffForHumans() }}</span>
                            </div>
                            
                            <h2 class="text-lg font-semibold text-gray-900 mb-2">
                                <a href="{{ route('posts.show', $i) }}" class="hover:text-blue-600">
                                    Sample News Headline {{ $i }}
                                </a>
                            </h2>
                            
                            <p class="text-gray-600 mb-3">
                                This is a sample excerpt from a news article. It provides a brief overview of what the full article contains...
                            </p>
                            
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span>{{ rand(10, 100) }} likes</span>
                                <span>{{ rand(5, 25) }} comments</span>
                                <span>Technology</span>
                            </div>
                        </div>
                        
                        <div class="w-24 h-16 bg-gray-200 rounded ml-4 flex-shrink-0"></div>
                    </div>
                </article>
            @endfor
        </div>

        <!-- Pagination Placeholder -->
        <div class="mt-8 flex justify-center">
            <div class="bg-white px-4 py-3 rounded-lg shadow-sm">
                <span class="text-gray-500">More posts will be loaded here with pagination</span>
            </div>
        </div>
    </div>
</x-app-layout>