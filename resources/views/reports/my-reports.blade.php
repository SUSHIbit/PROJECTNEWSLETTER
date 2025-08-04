<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Reports') }}
            </h2>
            <a href="{{ route('dashboard') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                ← Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
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

            <!-- Reports List -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Your Reports ({{ $reports->total() }} total)
                    </h3>
                    
                    @if($reports->count() > 0)
                        <div class="space-y-6">
                            @foreach($reports as $report)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-gray-300 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <!-- Report Header -->
                                            <div class="flex items-center space-x-3 mb-3">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                    @if($report->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($report->status === 'reviewed') bg-blue-100 text-blue-800
                                                    @elseif($report->status === 'resolved') bg-green-100 text-green-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst($report->status) }}
                                                </span>
                                                
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    {{ $report->formatted_reason }}
                                                </span>
                                                
                                                <span class="text-sm text-gray-500">
                                                    {{ $report->created_at->diffForHumans() }}
                                                </span>
                                            </div>

                                            <!-- Reported Content -->
                                            <div class="mb-3">
                                                <h4 class="text-sm font-medium text-gray-900 mb-2">Reported Content:</h4>
                                                <div class="bg-gray-50 rounded-lg p-3">
                                                    @if($report->reportable_type === 'App\Models\Post')
                                                        <div class="flex items-start space-x-3">
                                                            @if($report->reportable->featured_image)
                                                                <img class="h-10 w-10 rounded object-cover" 
                                                                     src="{{ $report->reportable->featured_image }}" 
                                                                     alt="{{ $report->reportable->title }}">
                                                            @endif
                                                            <div class="flex-1">
                                                                <p class="text-sm font-medium text-gray-900">
                                                                    Post: 
                                                                    <a href="{{ route('posts.show', $report->reportable->id) }}" 
                                                                       class="text-blue-600 hover:text-blue-800" target="_blank">
                                                                        {{ $report->reportable->title }}
                                                                    </a>
                                                                </p>
                                                                <p class="text-sm text-gray-600 mt-1">
                                                                    {{ Str::limit(strip_tags($report->reportable->content), 100) }}
                                                                </p>
                                                                <p class="text-xs text-gray-500 mt-1">
                                                                    By {{ $report->reportable->user->display_name }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @elseif($report->reportable_type === 'App\Models\Comment')
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-900">
                                                                Comment on: 
                                                                <a href="{{ route('posts.show', $report->reportable->post->id) }}#comments" 
                                                                   class="text-blue-600 hover:text-blue-800" target="_blank">
                                                                    {{ Str::limit($report->reportable->post->title, 50) }}
                                                                </a>
                                                            </p>
                                                            <p class="text-sm text-gray-700 mt-1 bg-white p-2 rounded border-l-4 border-gray-300">
                                                                "{{ $report->reportable->content }}"
                                                            </p>
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                By {{ $report->reportable->user->display_name }}
                                                            </p>
                                                        </div>
                                                    @else
                                                        <div class="flex items-center space-x-3">
                                                            <img class="h-10 w-10 rounded-full object-cover" 
                                                                 src="{{ $report->reportable->profile_picture_url }}" 
                                                                 alt="{{ $report->reportable->name }}">
                                                            <div>
                                                                <p class="text-sm font-medium text-gray-900">
                                                                    User: 
                                                                    <a href="{{ route('profile.show', $report->reportable->username ?: $report->reportable->id) }}" 
                                                                       class="text-blue-600 hover:text-blue-800" target="_blank">
                                                                        {{ $report->reportable->display_name }}
                                                                    </a>
                                                                </p>
                                                                <p class="text-sm text-gray-600">{{ $report->reportable->email }}</p>
                                                                @if($report->reportable->bio)
                                                                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($report->reportable->bio, 100) }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Report Description -->
                                            @if($report->description)
                                                <div class="mb-3">
                                                    <h4 class="text-sm font-medium text-gray-900 mb-1">Your Description:</h4>
                                                    <p class="text-sm text-gray-700 bg-blue-50 p-3 rounded">
                                                        {{ $report->description }}
                                                    </p>
                                                </div>
                                            @endif

                                            <!-- Admin Response -->
                                            @if($report->status !== 'pending' && $report->admin_notes)
                                                <div class="mb-3">
                                                    <h4 class="text-sm font-medium text-gray-900 mb-1">Admin Response:</h4>
                                                    <div class="bg-green-50 border border-green-200 rounded p-3">
                                                        <p class="text-sm text-green-800">{{ $report->admin_notes }}</p>
                                                        @if($report->reviewer)
                                                            <p class="text-xs text-green-600 mt-1">
                                                                Reviewed by {{ $report->reviewer->display_name }} • {{ $report->reviewed_at->diffForHumans() }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Status Information -->
                                            <div class="text-xs text-gray-500">
                                                @if($report->status === 'pending')
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Report is being reviewed by our moderation team
                                                    </div>
                                                @elseif($report->status === 'reviewed')
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Report has been reviewed
                                                    </div>
                                                @elseif($report->status === 'resolved')
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Report has been resolved - appropriate action was taken
                                                    </div>
                                                @else
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Report was dismissed - no violation found
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $reports->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No reports yet</h3>
                            <p class="mt-1 text-sm text-gray-500">You haven't submitted any reports. Help keep our community safe by reporting inappropriate content.</p>
                            <div class="mt-6">
                                <a href="{{ route('posts.index') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Browse Posts
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Help Section -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-medium text-blue-900 mb-2">About Reports</h3>
                <div class="text-sm text-blue-700 space-y-2">
                    <p><strong>Report Status Meanings:</strong></p>
                    <ul class="list-disc list-inside space-y-1 ml-4">
                        <li><strong>Pending:</strong> Your report is in queue and will be reviewed by our moderation team</li>
                        <li><strong>Reviewed:</strong> A moderator has looked at your report and is taking appropriate action</li>
                        <li><strong>Resolved:</strong> We found a violation and took action (content removed, user warned, etc.)</li>
                        <li><strong>Dismissed:</strong> We reviewed the content but didn't find a policy violation</li>
                    </ul>
                    <p class="mt-3">
                        <strong>Note:</strong> We review all reports seriously and take appropriate action when violations are found. 
                        For privacy reasons, we may not always share specific details about actions taken.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>