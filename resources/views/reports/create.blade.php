<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Report Content') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Error Messages -->
            @if (session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                            Report 
                            @if($reportable_type === 'post')
                                Post
                            @elseif($reportable_type === 'comment')
                                Comment
                            @else
                                User
                            @endif
                        </h3>
                        <p class="text-sm text-gray-600">
                            Help us maintain a safe and respectful community by reporting inappropriate content or behavior.
                        </p>
                    </div>

                    <!-- Content Being Reported -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-2">Content being reported:</h4>
                        
                        @if($reportable_type === 'post')
                            <div class="border-l-4 border-blue-500 pl-4">
                                <h5 class="font-medium text-gray-900">{{ $post->title }}</h5>
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit(strip_tags($post->content), 200) }}</p>
                                <p class="text-xs text-gray-500 mt-2">
                                    By {{ $post->user->display_name }} • {{ $post->created_at->diffForHumans() }}
                                </p>
                            </div>
                        @elseif($reportable_type === 'comment')
                            <div class="border-l-4 border-green-500 pl-4">
                                <p class="text-sm text-gray-900">{{ $comment->content }}</p>
                                <p class="text-xs text-gray-500 mt-2">
                                    On post: "{{ Str::limit($comment->post->title, 60) }}"
                                </p>
                                <p class="text-xs text-gray-500">
                                    By {{ $comment->user->display_name }} • {{ $comment->created_at->diffForHumans() }}
                                </p>
                            </div>
                        @else
                            <div class="border-l-4 border-purple-500 pl-4">
                                <div class="flex items-center space-x-3">
                                    <img class="h-10 w-10 rounded-full object-cover" 
                                         src="{{ $user->profile_picture_url }}" 
                                         alt="{{ $user->name }}">
                                    <div>
                                        <h5 class="font-medium text-gray-900">{{ $user->name }}</h5>
                                        @if($user->username)
                                            <p class="text-sm text-gray-600">@{{ $user->username }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500">{{ $user->posts_count }} posts • Joined {{ $user->created_at->format('M Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Report Form -->
                    <form action="{{ route('reports.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Hidden Fields -->
                        @if($reportable_type === 'post')
                            <input type="hidden" name="reportable_type" value="App\Models\Post">
                            <input type="hidden" name="reportable_id" value="{{ $post->id }}">
                        @elseif($reportable_type === 'comment')
                            <input type="hidden" name="reportable_type" value="App\Models\Comment">
                            <input type="hidden" name="reportable_id" value="{{ $comment->id }}">
                        @else
                            <input type="hidden" name="reportable_type" value="App\Models\User">
                            <input type="hidden" name="reportable_id" value="{{ $user->id }}">
                        @endif

                        <!-- Reason Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Why are you reporting this?
                            </label>
                            <div class="space-y-3">
                                <label class="flex items-start">
                                    <input type="radio" name="reason" value="spam" 
                                           class="mt-1 focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" required>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">Spam</div>
                                        <div class="text-sm text-gray-500">Repetitive, unwanted, or promotional content</div>
                                    </div>
                                </label>
                                
                                <label class="flex items-start">
                                    <input type="radio" name="reason" value="inappropriate" 
                                           class="mt-1 focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" required>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">Inappropriate Content</div>
                                        <div class="text-sm text-gray-500">Contains offensive or adult content</div>
                                    </div>
                                </label>
                                
                                <label class="flex items-start">
                                    <input type="radio" name="reason" value="harassment" 
                                           class="mt-1 focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" required>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">Harassment or Bullying</div>
                                        <div class="text-sm text-gray-500">Targeting, intimidating, or threatening behavior</div>
                                    </div>
                                </label>
                                
                                <label class="flex items-start">
                                    <input type="radio" name="reason" value="fake_news" 
                                           class="mt-1 focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" required>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">Misinformation</div>
                                        <div class="text-sm text-gray-500">False or misleading information</div>
                                    </div>
                                </label>
                                
                                <label class="flex items-start">
                                    <input type="radio" name="reason" value="copyright" 
                                           class="mt-1 focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" required>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">Copyright Violation</div>
                                        <div class="text-sm text-gray-500">Unauthorized use of copyrighted material</div>
                                    </div>
                                </label>
                                
                                <label class="flex items-start">
                                    <input type="radio" name="reason" value="other" 
                                           class="mt-1 focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" required>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">Other</div>
                                        <div class="text-sm text-gray-500">Something else that violates our community guidelines</div>
                                    </div>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                        </div>

                        <!-- Additional Details -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Additional Details (Optional)
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Please provide any additional context that might help us understand your report...">{{ old('description') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">
                                Provide specific details about why this content violates our community guidelines.
                            </p>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Community Guidelines Notice -->
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">
                                        About Reports
                                    </h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Reports are reviewed by our moderation team</li>
                                            <li>False reports may result in account restrictions</li>
                                            <li>We'll take appropriate action if violations are found</li>
                                            <li>You'll be notified of the outcome when possible</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-between pt-4">
                            <a href="javascript:history.back()" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Submit Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>