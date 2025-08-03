<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Invite Members to {{ $organization->name }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Invite New Member</h1>
            
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

            <!-- Organization Info -->
            <div class="flex items-center space-x-4 mb-6 p-4 bg-gray-50 rounded-lg">
                <img class="h-12 w-12 rounded-lg object-cover border-2 border-gray-200" 
                     src="{{ $organization->logo_url }}" 
                     alt="{{ $organization->name }}">
                <div>
                    <h3 class="font-semibold text-gray-900">{{ $organization->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $organization->members_count }} current members</p>
                </div>
            </div>

            <!-- Invite Form -->
            <form action="{{ route('organizations.member.invite', $organization->slug) }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- User Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        User Email Address *
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Enter the user's email address..."
                           required>
                    <p class="text-sm text-gray-500 mt-1">The user must already have an account on our platform</p>
                </div>

                <!-- Role Selection -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        Member Role *
                    </label>
                    <select id="role" 
                            name="role" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">Select a role</option>
                        <option value="member" {{ old('role') == 'member' ? 'selected' : '' }}>Member</option>
                        <option value="editor" {{ old('role') == 'editor' ? 'selected' : '' }}>Editor</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <a href="{{ route('organizations.members', $organization->slug) }}" 
                       class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Send Invitation
                    </button>
                </div>
            </form>
        </div>

        <!-- Role Descriptions -->
        <div class="bg-blue-50 rounded-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">Role Permissions</h3>
            <div class="space-y-3 text-sm text-blue-800">
                <div class="flex items-start">
                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium mr-3 mt-0.5">Admin</span>
                    <div>
                        <strong>Full management access:</strong> Can edit organization settings, invite/remove members, change member roles, and create posts.
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium mr-3 mt-0.5">Editor</span>
                    <div>
                        <strong>Content creation:</strong> Can create and edit posts on behalf of the organization, but cannot manage members.
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium mr-3 mt-0.5">Member</span>
                    <div>
                        <strong>Basic access:</strong> Can view organization content and participate in discussions, but cannot create posts or manage settings.
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Members Quick View -->
        <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Members</h3>
            
            @if($organization->memberships->count() > 0)
                <div class="space-y-2">
                    @foreach($organization->memberships->take(5) as $membership)
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center space-x-3">
                                <img class="h-8 w-8 rounded-full object-cover" 
                                     src="{{ $membership->user->profile_picture_url }}" 
                                     alt="{{ $membership->user->name }}">
                                <div>
                                    <span class="text-sm font-medium text-gray-900">{{ $membership->user->display_name }}</span>
                                    @if($membership->user_id === $organization->owner_id)
                                        <span class="ml-2 text-xs text-purple-600">(Owner)</span>
                                    @endif
                                </div>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if($membership->role === 'admin') bg-red-100 text-red-800
                                @elseif($membership->role === 'editor') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($membership->role) }}
                            </span>
                        </div>
                    @endforeach
                    
                    @if($organization->memberships->count() > 5)
                        <div class="pt-2 text-center">
                            <a href="{{ route('organizations.members', $organization->slug) }}" 
                               class="text-sm text-blue-600 hover:text-blue-800">
                                View all {{ $organization->memberships->count() }} members →
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <p class="text-gray-500 text-sm">No members yet. This will be your first invitation!</p>
            @endif
        </div>
    </div>
</x-app-layout>