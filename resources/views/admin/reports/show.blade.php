<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Report Details') }}
            </h2>
            <a href="{{ route('admin.reports') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                ← Back to Reports
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Report Details -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Report Information</h3>
                        <div class="flex items-center space-x-2">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'reviewed' => 'bg-blue-100 text-blue-800',
                                    'resolved' => 'bg-green-100 text-green-800',
                                    'dismissed' => 'bg-gray-100 text-gray-800'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$report->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($report->status) }}
                            </span>
                            <span class="text-sm text-gray-500">ID: {{ $report->id }}</span>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4">
                    <!-- Report Overview -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Report Details</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Reason</dt>
                                    <dd class="text-sm text-gray-900">{{ $report->formatted_reason }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Reported</dt>
                                    <dd class="text-sm text-gray-900">{{ $report->created_at->format('M j, Y g:i A') }}</dd>
                                    <dd class="text-xs text-gray-500">{{ $report->created_at->diffForHumans() }}</dd>
                                </div>
                                @if($report->reviewed_at)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Reviewed</dt>
                                        <dd class="text-sm text-gray-900">{{ $report->reviewed_at->format('M j, Y g:i A') }}</dd>
                                        <dd class="text-xs text-gray-500">{{ $report->reviewed_at->diffForHumans() }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>

                        <!-- Reporter Information -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Reporter</h4>
                            <div class="flex items-center">
                                <img class="h-10 w-10 rounded-full object-cover mr-3" 
                                     src="{{ $report->reporter->profile_picture_url }}" 
                                     alt="{{ $report->reporter->name }}">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('admin.users.show', $report->reporter->id) }}" class="hover:text-blue-600">
                                            {{ $report->reporter->display_name }}
                                        </a>
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $report->reporter->email }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Report Description -->
                    @if($report->description)
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Description</h4>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-700">{{ $report->description }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Reported Content -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Reported Content</h4>
                        <div class="border border-gray-200 rounded-lg p-4">
                            @if($report->reportable_type === 'App\Models\Post')
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Post
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <h5 class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('posts.show', $report->reportable->id) }}" target="_blank" class="hover:text-blue-600">
                                                {{ $report->reportable->title }}
                                            </a>
                                        </h5>
                                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($report->reportable->content, 200) }}</p>
                                        <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                            <span>By {{ $report->reportable->user->display_name }}</span>
                                            <span>{{ $report->reportable->published_at->diffForHumans() }}</span>
                                            <span>{{ $report->reportable->views }} views</span>
                                        </div>
                                    </div>
                                </div>
                            @elseif($report->reportable_type === 'App\Models\Comment')
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Comment
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-900">{{ $report->reportable->content }}</p>
                                        <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                            <span>By {{ $report->reportable->user->display_name }}</span>
                                            <span>{{ $report->reportable->created_at->diffForHumans() }}</span>
                                            <span>On post: 
                                                <a href="{{ route('posts.show', $report->reportable->post->id) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                                    {{ Str::limit($report->reportable->post->title, 50) }}
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            User
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <img class="h-8 w-8 rounded-full object-cover mr-3" 
                                                 src="{{ $report->reportable->profile_picture_url }}" 
                                                 alt="{{ $report->reportable->name }}">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    <a href="{{ route('admin.users.show', $report->reportable->id) }}" class="hover:text-blue-600">
                                                        {{ $report->reportable->display_name }}
                                                    </a>
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $report->reportable->email }}</div>
                                            </div>
                                        </div>
                                        @if($report->reportable->bio)
                                            <p class="text-sm text-gray-600 mt-2">{{ $report->reportable->bio }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Admin Review Section -->
                    @if($report->reviewer)
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Admin Review</h4>
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <img class="h-6 w-6 rounded-full object-cover mr-2" 
                                         src="{{ $report->reviewer->profile_picture_url }}" 
                                         alt="{{ $report->reviewer->name }}">
                                    <span class="text-sm font-medium text-gray-900">{{ $report->reviewer->display_name }}</span>
                                    <span class="text-xs text-gray-500 ml-2">{{ $report->reviewed_at->diffForHumans() }}</span>
                                </div>
                                @if($report->admin_notes)
                                    <p class="text-sm text-gray-700">{{ $report->admin_notes }}</p>
                                @else
                                    <p class="text-sm text-gray-500 italic">No additional notes provided.</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    @if($report->status === 'pending')
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-4">Take Action</h4>
                            
                            <form action="{{ route('admin.reports.update', $report->id) }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PATCH')
                                
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                    <select name="status" id="status" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="reviewed">Mark as Reviewed</option>
                                        <option value="resolved">Mark as Resolved</option>
                                        <option value="dismissed">Dismiss Report</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">Admin Notes (Optional)</label>
                                    <textarea name="admin_notes" id="admin_notes" rows="3" 
                                              class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                              placeholder="Add any notes about your decision..."></textarea>
                                </div>
                                
                                <div class="flex space-x-3">
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Update Report
                                    </button>
                                    <a href="{{ route('admin.reports') }}" 
                                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h4 class="text-sm font-medium text-gray-900 mb-4">Quick Actions</h4>
                        <div class="flex space-x-3">
                            @if($report->reportable_type === 'App\Models\Post')
                                <a href="{{ route('posts.show', $report->reportable->id) }}" target="_blank"
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    View Post
                                </a>
                                <a href="{{ route('admin.posts.show', $report->reportable->id) }}"
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Admin Post View
                                </a>
                            @elseif($report->reportable_type === 'App\Models\Comment')
                                <a href="{{ route('posts.show', $report->reportable->post->id) }}#comment-{{ $report->reportable->id }}" target="_blank"
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    View Comment
                                </a>
                            @endif
                            
                            @if($report->reportable_type !== 'App\Models\User')
                                <a href="{{ route('admin.users.show', $report->reportable->user->id) }}"
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    View Author
                                </a>
                            @endif
                            
                            <a href="{{ route('admin.users.show', $report->reporter->id) }}"
                               class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                View Reporter
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>