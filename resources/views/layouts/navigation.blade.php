<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <div class="w-8 h-8 bg-news-blue rounded-lg mr-2"></div>
                        <span class="font-bold text-xl text-news-blue">{{ config('app.name') }}</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('posts.index')" :active="request()->routeIs('posts.index')">
                        {{ __('News') }}
                    </x-nav-link>
                    <x-nav-link :href="route('search.trending')" :active="request()->routeIs('search.trending')">
                        {{ __('Trending') }}
                    </x-nav-link>
                    <x-nav-link :href="route('search.explore')" :active="request()->routeIs('search.explore')">
                        {{ __('Explore') }}
                    </x-nav-link>
                    <x-nav-link :href="route('organizations.index')" :active="request()->routeIs('organizations.*')">
                        {{ __('Organizations') }}
                    </x-nav-link>
                    @auth
                        <x-nav-link :href="route('follow.feed')" :active="request()->routeIs('follow.feed')">
                            {{ __('Feed') }}
                        </x-nav-link>
                        <x-nav-link :href="route('follow.discover')" :active="request()->routeIs('follow.discover')">
                            {{ __('Discover') }}
                        </x-nav-link>
                        <x-nav-link :href="route('posts.create')" :active="request()->routeIs('posts.create')">
                            {{ __('Create Post') }}
                        </x-nav-link>
                        
                        {{-- Admin Navigation Link --}}
                        @if(Auth::user()->isAdmin())
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                                <span class="text-red-600 font-semibold">{{ __('Admin') }}</span>
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Search Bar (Desktop) -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <form action="{{ route('search.index') }}" method="GET" class="relative mr-4">
                    <input type="text" 
                           name="q" 
                           placeholder="Search..." 
                           value="{{ request('q') }}"
                           class="w-64 px-4 py-2 pl-10 pr-4 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </form>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <!-- User Avatar -->
                                <img class="h-8 w-8 rounded-full object-cover mr-2" 
                                     src="{{ Auth::user()->profile_picture_url }}" 
                                     alt="{{ Auth::user()->name }}">
                                <div>
                                    {{ Auth::user()->display_name }}
                                    @if(Auth::user()->isAdmin())
                                        <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Admin
                                        </span>
                                    @endif
                                </div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.show', Auth::user()->username ?: Auth::user()->id)">
                                {{ __('My Profile') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('posts.my-posts')">
                                {{ __('My Posts') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('dashboard')">
                                {{ __('Dashboard') }}
                            </x-dropdown-link>

                            <div class="border-t border-gray-100"></div>

                            <x-dropdown-link :href="route('follow.feed')">
                                {{ __('My Feed') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('follow.discover')">
                                {{ __('Discover People') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('reports.my-reports')">
                                {{ __('My Reports') }}
                            </x-dropdown-link>

                            <div class="border-t border-gray-100"></div>

                            <x-dropdown-link :href="route('search.index')">
                                {{ __('Search') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('search.trending')">
                                {{ __('Trending') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('search.explore')">
                                {{ __('Explore') }}
                            </x-dropdown-link>

                            <div class="border-t border-gray-100"></div>

                            <x-dropout-link :href="route('organizations.index')">
                                {{ __('Organizations') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('organizations.create')">
                                {{ __('Create Organization') }}
                            </x-dropdown-link>

                            {{-- Admin Dropdown Links --}}
                            @if(Auth::user()->isAdmin())
                                <div class="border-t border-gray-100"></div>
                                
                                <x-dropdown-link :href="route('admin.dashboard')" class="text-red-600 font-semibold">
                                    {{ __('Admin Dashboard') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('admin.users')" class="text-red-600">
                                    {{ __('Manage Users') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('admin.posts')" class="text-red-600">
                                    {{ __('Manage Posts') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('admin.reports')" class="text-red-600">
                                    {{ __('Review Reports') }}
                                    @php
                                        $pendingReports = \App\Models\Report::pending()->count();
                                    @endphp
                                    @if($pendingReports > 0)
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ $pendingReports }}
                                        </span>
                                    @endif
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('admin.analytics')" class="text-red-600">
                                    {{ __('Analytics') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-100"></div>

                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile Settings') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="space-x-4">
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800">Login</a>
                        <a href="{{ route('register') }}" class="bg-news-blue text-white px-4 py-2 rounded-lg hover:bg-blue-800">Register</a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        
        <!-- Mobile Search -->
        <div class="pt-2 pb-3 border-b border-gray-200">
            <div class="px-4">
                <form action="{{ route('search.index') }}" method="GET" class="relative">
                    <input type="text" 
                           name="q" 
                           placeholder="Search..." 
                           value="{{ request('q') }}"
                           class="w-full px-4 py-2 pl-10 pr-4 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </form>
            </div>
        </div>

        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('posts.index')" :active="request()->routeIs('posts.index')">
                {{ __('News') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('search.trending')" :active="request()->routeIs('search.trending')">
                {{ __('Trending') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('search.explore')" :active="request()->routeIs('search.explore')">
                {{ __('Explore') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('organizations.index')" :active="request()->routeIs('organizations.*')">
                {{ __('Organizations') }}
            </x-responsive-nav-link>
            @auth
                <x-responsive-nav-link :href="route('follow.feed')" :active="request()->routeIs('follow.feed')">
                    {{ __('Feed') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('follow.discover')" :active="request()->routeIs('follow.discover')">
                    {{ __('Discover') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('posts.create')" :active="request()->routeIs('posts.create')">
                    {{ __('Create Post') }}
                </x-responsive-nav-link>
                
                {{-- Admin Mobile Links --}}
                @if(Auth::user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                        <span class="text-red-600 font-semibold">{{ __('Admin Panel') }}</span>
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="flex items-center">
                        <img class="h-10 w-10 rounded-full object-cover mr-3" 
                             src="{{ Auth::user()->profile_picture_url }}" 
                             alt="{{ Auth::user()->name }}">
                        <div>
                            <div class="font-medium text-base text-gray-800">
                                {{ Auth::user()->name }}
                                @if(Auth::user()->isAdmin())
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Admin
                                    </span>
                                @endif
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.show', Auth::user()->username ?: Auth::user()->id)">
                        {{ __('My Profile') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('posts.my-posts')">
                        {{ __('My Posts') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('reports.my-reports')">
                        {{ __('My Reports') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('search.index')">
                        {{ __('Search') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('organizations.index')">
                        {{ __('Organizations') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('organizations.create')">
                        {{ __('Create Organization') }}
                    </x-responsive-nav-link>

                    {{-- Admin Mobile Links --}}
                    @if(Auth::user()->isAdmin())
                        <x-responsive-nav-link :href="route('admin.dashboard')">
                            <span class="text-red-600 font-semibold">{{ __('Admin Dashboard') }}</span>
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('admin.users')">
                            <span class="text-red-600">{{ __('Manage Users') }}</span>
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('admin.posts')">
                            <span class="text-red-600">{{ __('Manage Posts') }}</span>
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('admin.reports')">
                            <span class="text-red-600">{{ __('Review Reports') }}</span>
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('admin.analytics')">
                            <span class="text-red-600">{{ __('Analytics') }}</span>
                        </x-responsive-nav-link>
                    @endif

                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile Settings') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Login') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        @endauth
    </div>
</nav>