<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Profile Picture Section -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Profile Picture') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Update your profile picture. This will be visible to other users.') }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('profile.picture.update') }}" enctype="multipart/form-data" class="mt-6">
                            @csrf
                            @method('patch')

                            <!-- Current Profile Picture -->
                            <div class="flex items-center space-x-6">
                                <div class="shrink-0">
                                    <img class="h-20 w-20 object-cover rounded-full border-2 border-gray-300" 
                                         src="{{ $user->profile_picture_url }}" 
                                         alt="{{ $user->name }}">
                                </div>
                                <div class="flex-1">
                                    <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-2">
                                        Choose new picture
                                    </label>
                                    <input type="file" 
                                           id="profile_picture" 
                                           name="profile_picture" 
                                           accept="image/*"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="mt-1 text-xs text-gray-500">PNG, JPG up to 2MB</p>
                                    <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
                                </div>
                            </div>

                            <div class="flex items-center gap-4 mt-6">
                                <x-primary-button>{{ __('Update Picture') }}</x-primary-button>
                                
                                @if (session('picture-updated'))
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">
                                        {{ __('Picture updated.') }}
                                    </p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            <!-- Basic Profile Information -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Additional Profile Information -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Additional Information') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("Tell others more about yourself.") }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('profile.additional.update') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            <!-- Bio -->
                            <div>
                                <x-input-label for="bio" :value="__('Bio')" />
                                <textarea id="bio" 
                                          name="bio" 
                                          rows="4" 
                                          class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                          placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                                <p class="mt-1 text-sm text-gray-500">Brief description for your profile</p>
                                <x-input-error class="mt-2" :messages="$errors->get('bio')" />
                            </div>

                            <!-- Location -->
                            <div>
                                <x-input-label for="location" :value="__('Location')" />
                                <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location', $user->location)" placeholder="City, Country" />
                                <x-input-error class="mt-2" :messages="$errors->get('location')" />
                            </div>

                            <!-- Website -->
                            <div>
                                <x-input-label for="website" :value="__('Website')" />
                                <x-text-input id="website" name="website" type="url" class="mt-1 block w-full" :value="old('website', $user->website)" placeholder="https://your-website.com" />
                                <x-input-error class="mt-2" :messages="$errors->get('website')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>

                                @if (session('additional-updated'))
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">
                                        {{ __('Information updated.') }}
                                    </p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            <!-- Update Password -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>