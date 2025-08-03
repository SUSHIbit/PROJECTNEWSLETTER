<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Post') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Share Your Story</h1>
            
            <!-- Placeholder Form (will be functional in Phase 4) -->
            <form class="space-y-6">
                @csrf
                
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Article Title
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Enter your article title..."
                           required>
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                        Content
                    </label>
                    <textarea id="content" 
                              name="content" 
                              rows="12" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Write your article content here..."
                              required></textarea>
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        Category
                    </label>
                    <select id="category" 
                            name="category" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select a category</option>
                        <option value="technology">Technology</option>
                        <option value="politics">Politics</option>
                        <option value="sports">Sports</option>
                        <option value="health">Health</option>
                        <option value="science">Science</option>
                    </select>
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        Featured Image (Optional)
                    </label>
                    <input type="file" 
                           id="image" 
                           name="image" 
                           accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex items-center justify-between pt-4">
                    <div class="text-sm text-gray-500">
                        <p>Note: This form is not functional yet. It will be implemented in Phase 4.</p>
                    </div>
                    
                    <div class="space-x-3">
                        <a href="{{ route('home') }}" 
                           class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="button" 
                                class="px-4 py-2 bg-news-blue text-white rounded-lg hover:bg-blue-800 cursor-not-allowed opacity-50"
                                disabled>
                            Publish Post
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>