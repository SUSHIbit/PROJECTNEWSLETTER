<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Reports Management') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                ← Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success Messages -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <form method="GET" action="{{ route('admin.reports') }}" class="space-y-4 sm:space-y-0 sm:flex sm:items-center sm:space-x-4">
                        <!-- Status Filter -->
                        <div>
                            <select name="status" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="dismissed" {{ request('status') === 'dismissed' ? 'selected' : '' }}>Dismissed</option>
                            </select>
                        </div>
                        
                        <!-- Reason Filter -->
                        <div>
                            <select name="reason" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Reasons</option>
                                <option value="spam" {{ request('reason') === 'spam' ? 'selected' : '' }}>Spam</option>
                                <option value="inappropriate" {{ request('reason') === 'inappropriate' ? 'selected' : '' }}>Inappropriate</option>
                                <option value="harassment" {{ request('reason') === 'harassment' ? 'selected' : '' }}>Harassment</option>
                                <option value="fake_news" {{ request('reason') === 'fake_news' ? 'selected' : '' }}>Misinformation</option>
                                <option value="copyright" {{ request('reason') === 'copyright' ? 'selected' : '' }}>Copyright</option>
                                <option value="other" {{ request('reason') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        
                        <!-- Filter Button -->
                        <div class="flex space-x-2">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Filter
                            </button>
                            <a href="{{ route('admin.reports') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Reports Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Reports ({{ $reports->total() }} total)
                    </h3>
                    
                    @if($reports->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Content</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reporter</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($reports as $report)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $report->formatted_reason }}
                                                    </div>
                                                    @if($report->description)
                                                        <div class="text-sm text-gray-500">
                                                            {{ Str::limit($report->description, 50) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">
                                                    @if($report->reportable_type === 'App\Models\Post')
                                                        <div class="font-medium">Post:</div>
                                                        <div class="text-gray-600">{{ Str::limit($report->reportable->title, 60) }}</div>
                                                        <div class="text-xs text-gray-500">by {{ $report->reportable->user->display_name }}</div>
                                                    @elseif($report->reportable_type === 'App\Models\Comment')
                                                        <div class="font-medium">Comment:</div>
                                                        <div class="text-gray-600">{{ Str::limit($report->reportable->content, 60) }}</div>
                                                        <div class="text-xs text-gray-500">by {{ $report->reportable->user->display_name }}</div>
                                                    @else
                                                        <div class="font-medium">User:</div>
                                                        <div class="text-gray-600">{{ $report->reportable->display_name }}</div>
                                                        <div class="text-xs text-gray-500">{{ $report->reportable->email }}</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <img class="h-8 w-8 rounded-full object-cover" 
                                                         src="{{ $report->reporter->profile_picture_url }}" 
                                                         alt="{{ $report->reporter->name }}">
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $report->reporter->display_name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $report->reporter->email }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'reviewed' => 'bg-blue-100 text-blue-800',
                                                        'resolved' => 'bg-green-100 text-green-800',
                                                        'dismissed' => 'bg-gray-100 text-gray-800'
                                                    ];
                                                @endphp
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$report->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($report->status) }}
                                                </span>
                                                @if($report->reviewer)
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        by {{ $report->reviewer->display_name }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div>{{ $report->created_at->format('M j, Y') }}</div>
                                                <div>{{ $report->created_at->diffForHumans() }}</div>
                                                @if($report->reviewed_at)
                                                    <div class="text-xs text-green-600 mt-1">
                                                        Reviewed {{ $report->reviewed_at->diffForHumans() }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('admin.reports.show', $report->id) }}" 
                                                       class="text-blue-600 hover:text-blue-900">View</a>
                                                    
                                                    @if($report->status === 'pending')
                                                        <form action="{{ route('admin.reports.update', $report->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="resolved">
                                                            <button type="submit" 
                                                                    class="text-green-600 hover:text-green-900"
                                                                    onclick="return confirm('Mark this report as resolved?')">
                                                                Resolve
                                                            </button>
                                                        </form>
                                                        
                                                        <form action="{{ route('admin.reports.update', $report->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="dismissed">
                                                            <button type="submit" 
                                                                    class="text-red-600 hover:text-red-900"
                                                                    onclick="return confirm('Dismiss this report?')">
                                                                Dismiss
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $reports->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No reports found</h3>
                            <p class="mt-1 text-sm text-gray-500">No reports match your current filters.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>