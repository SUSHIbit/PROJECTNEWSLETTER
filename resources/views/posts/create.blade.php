<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Post') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Share Your Story</h1>
            
            <!-- Display validation errors -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded">
                    <h4 class="font-medium">Please fix the following errors:</h4>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Post Creation Form -->
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Article Title *
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Enter your article title..."
                           required>
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                        Content *
                    </label>
                    <textarea id="content" 
                              name="content" 
                              rows="15" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Write your article content here..."
                              required>{{ old('content') }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">You can use plain text or basic HTML formatting</p>
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        Category
                    </label>
                    <select id="category" 
                            name="category" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select a category</option>
                        <option value="technology" {{ old('category') == 'technology' ? 'selected' : '' }}>Technology</option>
                        <option value="politics" {{ old('category') == 'politics' ? 'selected' : '' }}>Politics</option>
                        <option value="sports" {{ old('category') == 'sports' ? 'selected' : '' }}>Sports</option>
                        <option value="health" {{ old('category') == 'health' ? 'selected' : '' }}>Health</option>
                        <option value="science" {{ old('category') == 'science' ? 'selected' : '' }}>Science</option>
                        <option value="business" {{ old('category') == 'business' ? 'selected' : '' }}>Business</option>
                        <option value="entertainment" {{ old('category') == 'entertainment' ? 'selected' : '' }}>Entertainment</option>
                        <option value="lifestyle" {{ old('category') == 'lifestyle' ? 'selected' : '' }}>Lifestyle</option>
                        <option value="education" {{ old('category') == 'education' ? 'selected' : '' }}>Education</option>
                        <option value="travel" {{ old('category') == 'travel' ? 'selected' : '' }}>Travel</option>
                    </select>
                </div>

                <!-- Featured Image -->
                <div>
                    <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                        Featured Image (Optional)
                    </label>
                    <input type="file" 
                           id="featured_image" 
                           name="featured_image" 
                           accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB</p>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="status" 
                                   value="published" 
                                   class="mr-2 text-blue-600" 
                                   {{ old('status', 'published') == 'published' ? 'checked' : '' }}>
                            <span class="text-sm">Publish immediately</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="status" 
                                   value="draft" 
                                   class="mr-2 text-blue-600"
                                   {{ old('status') == 'draft' ? 'checked' : '' }}>
                            <span class="text-sm">Save as draft</span>
                        </label>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-500">
                        <p>* Required fields</p>
                    </div>
                    
                    <div class="space-x-3">
                        <a href="{{ route('posts.index') }}" 
                           class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Create Post
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Writing Tips -->
        <div class="bg-blue-50 rounded-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">Writing Tips</h3>
            <ul class="space-y-2 text-sm text-blue-800">
                <li class="flex items-start">
                    <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                    <span>Write a compelling title that clearly describes your article</span>
                </li>
                <li class="flex items-start">
                    <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                    <span>Break up long paragraphs to make your content easier to read</span>
                </li>
                <li class="flex items-start">
                    <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                    <span>Choose a relevant category to help readers find your content</span>
                </li>
                <li class="flex items-start">
                    <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                    <span>Add a featured image to make your post more engaging</span>
                </li>
                <li class="flex items-start">
                    <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                    <span>Save as draft if you want to review and edit later</span>
                </li>
            </ul>
        </div>
    </div>
</x-app-layout>