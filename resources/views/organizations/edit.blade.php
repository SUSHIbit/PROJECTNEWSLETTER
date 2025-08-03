<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit {{ $organization->name }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Organization</h1>
            
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

            <!-- Organization Edit Form -->
            <form action="{{ route('organizations.update', $organization->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Current Logo Display -->
                <div class="flex items-center space-x-6 p-4 bg-gray-50 rounded-lg">
                    <img class="h-20 w-20 rounded-lg object-cover border-2 border-gray-200" 
                         src="{{ $organization->logo_url }}" 
                         alt="{{ $organization->name }}">
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $organization->name }}</h3>
                        <p class="text-sm text-gray-600">Current organization logo</p>
                    </div>
                </div>

                <!-- Organization Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Organization Name *
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $organization->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Enter your organization name..."
                           required>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Describe your organization...">{{ old('description', $organization->description) }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Tell people what your organization is about</p>
                </div>

                <!-- Logo -->
                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                        Update Organization Logo (Optional)
                    </label>
                    <input type="file" 
                           id="logo" 
                           name="logo" 
                           accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Leave empty to keep current logo. Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB</p>
                </div>

                <!-- Website -->
                <div>
                    <label for="website" class="block text-sm font-medium text-gray-700 mb-2">
                        Website (Optional)
                    </label>
                    <input type="url" 
                           id="website" 
                           name="website" 
                           value="{{ old('website', $organization->website) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="https://yourorganization.com">
                </div>

                <!-- Contact Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Contact Email (Optional)
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $organization->email) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="contact@yourorganization.com">
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-500">
                        <p>* Required fields</p>
                    </div>
                    
                    <div class="space-x-3">
                        <a href="{{ route('organizations.show', $organization->slug) }}" 
                           class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Update Organization
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Danger Zone -->
        @if(Auth::id() === $organization->owner_id)
            <div class="bg-white rounded-lg shadow-sm p-6 mt-6 border border-red-200">
                <h3 class="text-lg font-semibold text-red-900 mb-3">Danger Zone</h3>
                <p class="text-sm text-red-700 mb-4">
                    Once you delete an organization, there is no going back. Please be certain.
                </p>
                <form action="{{ route('organizations.destroy', $organization->slug) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('Are you sure you want to delete this organization? This action cannot be undone!')"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Delete Organization
                    </button>
                </form>
            </div>
        @endif

        <!-- Organization Stats -->
        <div class="bg-gray-50 rounded-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Organization Statistics</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div>
                    <div class="text-2xl font-bold text-blue-600">{{ $organization->members_count }}</div>
                    <div class="text-sm text-gray-600">Members</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-green-600">
                        {{ $organization->members()->whereHas('posts', function($q) { $q->published(); })->count() }}
                    </div>
                    <div class="text-sm text-gray-600">Active Writers</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-purple-600">
                        {{ \App\Models\Post::whereIn('user_id', $organization->members->pluck('id'))->published()->count() }}
                    </div>
                    <div class="text-sm text-gray-600">Total Posts</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-orange-600">{{ $organization->created_at->diffInDays(now()) }}</div>
                    <div class="text-sm text-gray-600">Days Active</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>