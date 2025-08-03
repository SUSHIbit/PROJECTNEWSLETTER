<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $organization->name }} - Members
            </h2>
            @auth
                @if($organization->canEdit(Auth::user()))
                    <a href="{{ route('organizations.invite', $organization->slug) }}" 
                       class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                        Invite Members
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Error Message -->
        @if (session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Organization Info Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center space-x-4">
                <img class="h-16 w-16 rounded-lg object-cover border-2 border-gray-200" 
                     src="{{ $organization->logo_url }}" 
                     alt="{{ $organization->name }}">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">{{ $organization->name }}</h1>
                    <div class="flex space-x-4 text-sm text-gray-600">
                        <span>{{ $organization->memberships->count() }} members</span>
                        <span>Owned by {{ $organization->owner->display_name }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Navigation Tabs -->
            <div class="mt-6 border-b border-gray-200">
                <nav class="flex space-x-8">
                    <a href="{{ route('organizations.show', $organization->slug) }}" 
                       class="py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Posts
                    </a>
                    <span class="py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                        Members
                    </span>
                </nav>
            </div>
        </div>

        <!-- Members List -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                Organization Members ({{ $organization->memberships->count() }})
            </h3>
            
            @if($organization->memberships->count() > 0)
                <div class="space-y-4">
                    @foreach($organization->memberships as $membership)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <img class="h-12 w-12 rounded-full object-cover" 
                                     src="{{ $membership->user->profile_picture_url }}" 
                                     alt="{{ $membership->user->name }}">
                                <div>
                                    <h4 class="font-medium text-gray-900">
                                        <a href="{{ route('profile.show', $membership->user->username ?: $membership->user->id) }}" 
                                           class="hover:text-blue-600">
                                            {{ $membership->user->display_name }}
                                        </a>
                                        @if($membership->user_id === $organization->owner_id)
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                Owner
                                            </span>
                                        @endif
                                    </h4>
                                    @if($membership->user->bio)
                                        <p class="text-sm text-gray-600">{{ Str::limit($membership->user->bio, 80) }}</p>
                                    @endif
                                    <div class="flex space-x-4 text-xs text-gray-500 mt-1">
                                        <span>Joined {{ $membership->joined_at->diffForHumans() }}</span>
                                        <span>{{ $membership->user->posts_count }} posts</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <!-- Role Badge -->
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($membership->role === 'admin') bg-red-100 text-red-800
                                    @elseif($membership->role === 'editor') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($membership->role) }}
                                </span>
                                
                                <!-- Actions for admins -->
                                @auth
                                    @if($organization->canEdit(Auth::user()) && $membership->user_id !== $organization->owner_id)
                                        <div class="flex space-x-1">
                                            <!-- Role Update Form -->
                                            @if($membership->user_id !== Auth::id())
                                                <form action="{{ route('organizations.member.role', [$organization->slug, $membership->user_id]) }}" 
                                                      method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="role" 
                                                            onchange="this.form.submit()"
                                                            class="text-xs border border-gray-300 rounded px-2 py-1">
                                                        <option value="member" {{ $membership->role === 'member' ? 'selected' : '' }}>Member</option>
                                                        <option value="editor" {{ $membership->role === 'editor' ? 'selected' : '' }}>Editor</option>
                                                        <option value="admin" {{ $membership->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                    </select>
                                                </form>
                                            @endif
                                            
                                            <!-- Remove Member -->
                                            <form action="{{ route('organizations.member.remove', [$organization->slug, $membership->user_id]) }}" 
                                                  method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('Are you sure you want to remove this member?')"
                                                        class="text-xs px-2 py-1 text-red-600 border border-red-600 rounded hover:bg-red-50">
                                                    Remove
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No members yet</h3>
                    <p class="text-gray-500 mb-4">Start building your team by inviting members to join.</p>
                    @auth
                        @if($organization->canEdit(Auth::user()))
                            <a href="{{ route('organizations.invite', $organization->slug) }}" 
                               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                Invite First Member
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>

        <!-- Roles Information -->
        <div class="bg-gray-50 rounded-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Member Roles</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-start">
                    <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium mr-3 mt-0.5">Owner</span>
                    <span class="text-gray-700">Full control over the organization, including deleting it and managing all members.</span>
                </div>
                <div class="flex items-start">
                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium mr-3 mt-0.5">Admin</span>
                    <span class="text-gray-700">Can manage organization settings, invite/remove members, and edit member roles.</span>
                </div>
                <div class="flex items-start">
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium mr-3 mt-0.5">Editor</span>
                    <span class="text-gray-700">Can create and edit posts on behalf of the organization.</span>
                </div>
                <div class="flex items-start">
                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium mr-3 mt-0.5">Member</span>
                    <span class="text-gray-700">Basic member with read access and ability to participate in discussions.</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>