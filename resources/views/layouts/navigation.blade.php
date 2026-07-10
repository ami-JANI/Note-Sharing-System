<nav x-data="{ open: false }" style="background: rgba(251, 248, 243, 0.85); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(27, 42, 74, 0.1);">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-10">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5" style="color: rgb(27, 42, 74);">
                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; background: rgb(138, 28, 36); color: rgb(251, 248, 243); border-radius: 7px; font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 19px;">U</span>
                        <span style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 21px; letter-spacing: -0.01em;">UniNotes</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex sm:items-center sm:gap-8">
                    <a href="{{ route('dashboard') }}" style="font-size: 15px; font-weight: 500; color: {{ request()->routeIs('dashboard') ? 'rgb(138, 28, 36)' : 'rgb(58, 71, 98)' }}; {{ request()->routeIs('dashboard') ? 'border-bottom: 2px solid rgb(138, 28, 36); padding-bottom: 2px;' : '' }} transition">
                        Dashboard
                    </a>
                    <a href="{{ route('credits.buy') }}" style="font-size: 15px; font-weight: 500; color: {{ request()->routeIs('credits.*') ? 'rgb(138, 28, 36)' : 'rgb(58, 71, 98)' }}; {{ request()->routeIs('credits.*') ? 'border-bottom: 2px solid rgb(138, 28, 36); padding-bottom: 2px;' : '' }} transition">
                        Buy Credits
                    </a>
                    @if (auth()->user()?->isAdmin())
                        <a href="{{ route('admin.notes.pending') }}" style="font-size: 15px; font-weight: 500; color: {{ request()->routeIs('admin.*') ? 'rgb(138, 28, 36)' : 'rgb(58, 71, 98)' }}; {{ request()->routeIs('admin.*') ? 'border-bottom: 2px solid rgb(138, 28, 36); padding-bottom: 2px;' : '' }} transition">
                            Admin
                        </a>
                    @endif
                </div>
            </div>

            <!-- Right Side -->
            <div class="hidden sm:flex sm:items-center sm:gap-5">
                <!-- Credits Badge -->
                <a href="{{ route('credits.history') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 100px; font-size: 13px; font-weight: 600; color: #C08A3E; background: rgba(192, 138, 62, 0.08); border: 1px solid rgba(192, 138, 62, 0.2); transition: background 0.15s;">
                    <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                    </svg>
                    {{ number_format(Auth::user()->credits ?? 0) }} credits
                </a>

                <!-- User Dropdown -->
                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition" style="color: rgb(58, 71, 98); background: transparent; border: 1px solid rgba(27, 42, 74, 0.12);">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold" style="background: rgb(138, 28, 36); color: rgb(251, 248, 243);">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>

                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition
                         class="absolute right-0 mt-2 w-48 rounded-xl shadow-lg py-1 z-50"
                         style="background: white; border: 1px solid rgba(27, 42, 74, 0.1);">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm" style="color: rgb(58, 71, 98);">Profile</a>
                        <a href="{{ route('credits.history') }}" class="block px-4 py-2 text-sm" style="color: rgb(58, 71, 98);">Credit History</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm" style="color: rgb(58, 71, 98);">Log Out</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md transition" style="color: rgb(58, 71, 98);">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden" style="background: rgba(251, 248, 243, 0.95); border-top: 1px solid rgba(27, 42, 74, 0.08);">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-base font-medium" style="color: {{ request()->routeIs('dashboard') ? 'rgb(138, 28, 36)' : 'rgb(58, 71, 98)' }};">
                Dashboard
            </a>
            <a href="{{ route('credits.buy') }}" class="block px-4 py-2 text-base font-medium" style="color: {{ request()->routeIs('credits.*') ? 'rgb(138, 28, 36)' : 'rgb(58, 71, 98)' }};">
                Buy Credits
            </a>
        </div>

        <div class="pt-4 pb-1" style="border-top: 1px solid rgba(27, 42, 74, 0.08);">
            <div class="px-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold" style="background: rgb(138, 28, 36); color: rgb(251, 248, 243);">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-medium text-base" style="color: rgb(27, 42, 74);">{{ Auth::user()->name }}</div>
                        <div class="text-sm" style="color: rgb(91, 104, 133);">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <a href="{{ route('credits.history') }}" class="inline-flex items-center gap-1.5 mt-1 px-3 py-1.5 text-xs font-semibold rounded-full" style="color: #C08A3E; background: rgba(192, 138, 62, 0.08);">
                    {{ number_format(Auth::user()->credits ?? 0) }} credits
                </a>
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-base font-medium" style="color: rgb(58, 71, 98);">Profile</a>
                <a href="{{ route('credits.history') }}" class="block px-4 py-2 text-base font-medium" style="color: rgb(58, 71, 98);">Credit History</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium" style="color: rgb(58, 71, 98);">Log Out</button>
                </form>
            </div>
        </div>
    </div>
</nav>
