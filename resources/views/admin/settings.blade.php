<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('System Settings') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                ← Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- System Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">System Information</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Application Name</dt>
                                    <dd class="text-sm text-gray-900">{{ config('app.name') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Environment</dt>
                                    <dd class="text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ config('app.env') === 'production' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst(config('app.env')) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Debug Mode</dt>
                                    <dd class="text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ config('app.debug') ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                            {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">URL</dt>
                                    <dd class="text-sm text-gray-900">{{ config('app.url') }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Laravel Version</dt>
                                    <dd class="text-sm text-gray-900">{{ app()->version() }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">PHP Version</dt>
                                    <dd class="text-sm text-gray-900">{{ PHP_VERSION }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Database</dt>
                                    <dd class="text-sm text-gray-900">{{ ucfirst(config('database.default')) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Timezone</dt>
                                    <dd class="text-sm text-gray-900">{{ config('app.timezone') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Settings -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Application Settings</h3>
                    <p class="mt-1 text-sm text-gray-600">Configure basic application settings and features.</p>
                </div>
                <div class="px-6 py-4">
                    <form class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Site Name -->
                            <div>
                                <label for="site_name" class="block text-sm font-medium text-gray-700">Site Name</label>
                                <input type="text" 
                                       name="site_name" 
                                       id="site_name" 
                                       value="{{ config('app.name') }}"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <!-- Registration -->
                            <div>
                                <label for="allow_registration" class="block text-sm font-medium text-gray-700">User Registration</label>
                                <select name="allow_registration" 
                                        id="allow_registration" 
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="1">Allow Registration</option>
                                    <option value="0">Disable Registration</option>
                                </select>
                            </div>
                            
                            <!-- Posts per page -->
                            <div>
                                <label for="posts_per_page" class="block text-sm font-medium text-gray-700">Posts per Page</label>
                                <input type="number" 
                                       name="posts_per_page" 
                                       id="posts_per_page" 
                                       value="10"
                                       min="5" 
                                       max="50"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <!-- Comment Moderation -->
                            <div>
                                <label for="comment_moderation" class="block text-sm font-medium text-gray-700">Comment Moderation</label>
                                <select name="comment_moderation" 
                                        id="comment_moderation" 
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="0">No Moderation</option>
                                    <option value="1">Moderate New Comments</option>
                                    <option value="2">Moderate All Comments</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-6">
                            <div class="flex justify-end">
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Save Settings
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Content Management -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Content Management</h3>
                    <p class="mt-1 text-sm text-gray-600">Manage content policies and automated moderation.</p>
                </div>
                <div class="px-6 py-4">
                    <div class="space-y-6">
                        <!-- Auto-approve posts -->
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Auto-approve Posts</h4>
                                <p class="text-sm text-gray-500">Automatically approve posts from verified users</p>
                            </div>
                            <button type="button" 
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-gray-200 transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    role="switch" 
                                    aria-checked="false">
                                <span class="translate-x-0 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                        </div>
                        
                        <!-- Email notifications -->
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Email Notifications</h4>
                                <p class="text-sm text-gray-500">Send email notifications for new registrations and reports</p>
                            </div>
                            <button type="button" 
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-blue-600 transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    role="switch" 
                                    aria-checked="true">
                                <span class="translate-x-5 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                        </div>
                        
                        <!-- Spam protection -->
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Spam Protection</h4>
                                <p class="text-sm text-gray-500">Enable automatic spam detection and filtering</p>
                            </div>
                            <button type="button" 
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-blue-600 transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    role="switch" 
                                    aria-checked="true">
                                <span class="translate-x-5 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cache Management -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Cache Management</h3>
                    <p class="mt-1 text-sm text-gray-600">Clear application caches to improve performance.</p>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <button type="button" 
                                class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Clear All Cache
                        </button>
                        
                        <button type="button" 
                                class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Clear Views
                        </button>
                        
                        <button type="button" 
                                class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Clear Config
                        </button>
                        
                        <button type="button" 
                                class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Optimize
                        </button>
                    </div>
                </div>
            </div>

            <!-- Database Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Database Information</h3>
                    <p class="mt-1 text-sm text-gray-600">Current database statistics and information.</p>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ \App\Models\User::count() }}</div>
                            <div class="text-sm text-blue-600 font-medium">Total Users</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ \App\Models\Post::count() }}</div>
                            <div class="text-sm text-green-600 font-medium">Total Posts</div>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600">{{ \App\Models\Comment::count() }}</div>
                            <div class="text-sm text-yellow-600 font-medium">Total Comments</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">{{ \App\Models\Organization::count() }}</div>
                            <div class="text-sm text-purple-600 font-medium">Organizations</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Maintenance -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">System Maintenance</h3>
                    <p class="mt-1 text-sm text-gray-600">Perform system maintenance tasks and cleanup operations.</p>
                </div>
                <div class="px-6 py-4">
                    <div class="space-y-4">
                        <!-- Backup Database -->
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Database Backup</h4>
                                <p class="text-sm text-gray-500">Create a backup of the current database</p>
                            </div>
                            <button type="button" 
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                </svg>
                                Create Backup
                            </button>
                        </div>

                        <!-- Clean Old Sessions -->
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Clean Old Sessions</h4>
                                <p class="text-sm text-gray-500">Remove expired user sessions from the database</p>
                            </div>
                            <button type="button" 
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Clean Sessions
                            </button>
                        </div>

                        <!-- Optimize Images -->
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Optimize Images</h4>
                                <p class="text-sm text-gray-500">Compress and optimize uploaded images</p>
                            </div>
                            <button type="button" 
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Optimize Images
                            </button>
                        </div>

                        <!-- Generate Sitemap -->
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Generate Sitemap</h4>
                                <p class="text-sm text-gray-500">Generate XML sitemap for search engines</p>
                            </div>
                            <button type="button" 
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Generate Sitemap
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Logs -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">System Logs</h3>
                    <p class="mt-1 text-sm text-gray-600">View recent system activity and error logs.</p>
                </div>
                <div class="px-6 py-4">
                    <div class="space-y-3">
                        @php
                            $logs = [
                                ['level' => 'info', 'message' => 'User registration completed successfully', 'time' => '2 minutes ago'],
                                ['level' => 'warning', 'message' => 'High memory usage detected', 'time' => '15 minutes ago'],
                                ['level' => 'info', 'message' => 'Daily backup completed', 'time' => '1 hour ago'],
                                ['level' => 'error', 'message' => 'Failed to send email notification', 'time' => '2 hours ago'],
                                ['level' => 'info', 'message' => 'Cache cleared successfully', 'time' => '3 hours ago'],
                            ];
                        @endphp
                        
                        @foreach($logs as $log)
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                <div class="flex items-center">
                                    @if($log['level'] === 'error')
                                        <div class="flex-shrink-0 w-2 h-2 bg-red-500 rounded-full mr-3"></div>
                                    @elseif($log['level'] === 'warning')
                                        <div class="flex-shrink-0 w-2 h-2 bg-yellow-500 rounded-full mr-3"></div>
                                    @else
                                        <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    @endif
                                    <div>
                                        <p class="text-sm text-gray-900">{{ $log['message'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $log['time'] }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $log['level'] === 'error' ? 'bg-red-100 text-red-800' : ($log['level'] === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                    {{ ucfirst($log['level']) }}
                                </span>
                            </div>
                        @endforeach
                        
                        <div class="text-center pt-4">
                            <button type="button" 
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View All Logs →
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Quick Admin Actions</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('admin.users') }}" 
                           class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Manage Users
                        </a>
                        
                        <a href="{{ route('admin.posts') }}" 
                           class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"></path>
                            </svg>
                            Manage Posts
                        </a>
                        
                        <a href="{{ route('admin.reports') }}" 
                           class="inline-flex items-center justify-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Review Reports
                        </a>
                        
                        <a href="{{ route('admin.analytics') }}" 
                           class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            View Analytics
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>