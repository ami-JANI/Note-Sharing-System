<nav x-data="{ open: false }" style="background: rgba(251, 248, 243, 0.85); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(27, 42, 74, 0.1);">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-10">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('welcome') }}" class="flex items-center gap-2.5" style="color: rgb(27, 42, 74);">
                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; background: rgb(138, 28, 36); color: rgb(251, 248, 243); border-radius: 7px; font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 19px;">U</span>
                        <span style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 21px; letter-spacing: -0.01em;">UniNotes</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex sm:items-center sm:gap-8">
                    <a href="{{ route('dashboard') }}" style="font-size: 15px; font-weight: 500; color: {{ request()->routeIs('dashboard') ? 'rgb(138, 28, 36)' : 'rgb(58, 71, 98)' }}; {{ request()->routeIs('dashboard') ? 'border-bottom: 2px solid rgb(138, 28, 36); padding-bottom: 2px;' : '' }} transition">
                        Dashboard
                    </a>
                    <a href="{{ route('notes.create') }}" style="font-size: 15px; font-weight: 500; color: {{ request()->routeIs('notes.create') ? 'rgb(138, 28, 36)' : 'rgb(58, 71, 98)' }}; {{ request()->routeIs('notes.create') ? 'border-bottom: 2px solid rgb(138, 28, 36); padding-bottom: 2px;' : '' }} transition">
                        Upload
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

                <!-- Notification Bell -->
                @php
                    $unreadNotifications = Auth::user()->unreadNotifications ?? collect();
                    $notifications = Auth::user()->notifications ?? collect();
                @endphp
                <div x-data="{ notifOpen: false }" class="relative">
                    <button @click="notifOpen = !notifOpen"
                            style="position: relative; display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 8px; background: transparent; border: 1px solid rgba(27, 42, 74, 0.12); color: rgb(58, 71, 98); cursor: pointer; transition: all 0.15s;"
                            onmouseover="this.style.borderColor='rgba(27, 42, 74, 0.3)'" onmouseout="this.style.borderColor='rgba(27, 42, 74, 0.12)'">
                        <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        @if ($unreadNotifications->count() > 0)
                            <span style="position: absolute; top: -4px; right: -4px; display: inline-flex; align-items: center; justify-content: center; min-width: 18px; height: 18px; padding: 0 4px; border-radius: 9px; background: rgb(138, 28, 36); color: white; font-size: 10px; font-weight: 700;">{{ $unreadNotifications->count() > 9 ? '9+' : $unreadNotifications->count() }}</span>
                        @endif
                    </button>

                    <div x-show="notifOpen" @click.away="notifOpen = false" x-transition
                         style="position: absolute; right: 0; margin-top: 8px; width: 380px; max-height: 440px; overflow-y: auto; border-radius: 14px; box-shadow: 0 12px 40px -8px rgba(27, 42, 74, 0.2); z-index: 50; background: white; border: 1px solid rgba(27, 42, 74, 0.1);">
                        <div style="padding: 14px 16px; border-bottom: 1px solid rgba(27, 42, 74, 0.08); display: flex; align-items: center; justify-content: space-between;">
                            <span style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 15px; color: rgb(27, 42, 74);">Notifications</span>
                            @if ($unreadNotifications->count() > 0)
                                <form method="POST" action="{{ route('notifications.readAll') }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" style="font-size: 12px; font-weight: 500; color: rgb(138, 28, 36); background: none; border: none; cursor: pointer;">Mark all read</button>
                                </form>
                            @endif
                        </div>

                        @forelse ($notifications->take(10) as $notification)
                            <a href="{{ $notification->data['url'] ?? '#' }}"
                               style="display: flex; gap: 12px; padding: 12px 16px; text-decoration: none; color: rgb(27, 42, 74); border-bottom: 1px solid rgba(27, 42, 74, 0.04); transition: background 0.15s; {{ !$notification->read_at ? 'background: rgba(138, 28, 36, 0.03);' : '' }}"
                               onmouseover="this.style.background='rgba(27, 42, 74, 0.03)'" onmouseout="this.style.background='{{ !$notification->read_at ? 'rgba(138, 28, 36, 0.03)' : 'transparent' }}'">
                                {{-- Icon per type --}}
                                <div style="width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; {{ str_contains($notification->type, 'Purchase') ? 'background: rgba(46, 125, 79, 0.08); color: rgb(46, 125, 79);' : (str_contains($notification->type, 'Review') ? 'background: rgba(192, 138, 62, 0.1); color: #C08A3E;' : (str_contains($notification->type, 'Message') ? 'background: rgba(27, 42, 74, 0.06); color: rgb(58, 71, 98);' : 'background: rgba(138, 28, 36, 0.06); color: rgb(138, 28, 36);')) }}">
                                    @if (str_contains($notification->type, 'Purchase'))
                                        <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" /></svg>
                                    @elseif (str_contains($notification->type, 'Review'))
                                        <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    @elseif (str_contains($notification->type, 'Message'))
                                        <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                                    @else
                                        <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
                                    @endif
                                </div>
                                <div style="min-width: 0; flex: 1;">
                                    <p style="font-size: 13px; line-height: 1.4; color: {{ !$notification->read_at ? 'rgb(27, 42, 74); font-weight: 500;' : 'rgb(91, 104, 133);' }}">{{ $notification->data['message'] ?? 'New notification' }}</p>
                                    <p style="font-size: 11px; color: rgb(138, 150, 174); margin-top: 3px;">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                                @if (!$notification->read_at)
                                    <div style="width: 8px; height: 8px; border-radius: 50%; background: rgb(138, 28, 36); flex-shrink: 0; margin-top: 4px;"></div>
                                @endif
                            </a>
                        @empty
                            <div style="padding: 40px 16px; text-align: center;">
                                <svg style="margin: 0 auto; width: 36px; height: 36px; color: rgb(175, 182, 201);" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>
                                <p style="font-size: 14px; color: rgb(91, 104, 133); margin-top: 8px;">No notifications yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>

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
                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm" style="color: rgb(58, 71, 98);">Profile</a>
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
            <a href="{{ route('notes.create') }}" class="block px-4 py-2 text-base font-medium" style="color: {{ request()->routeIs('notes.create') ? 'rgb(138, 28, 36)' : 'rgb(58, 71, 98)' }};">
                Upload
            </a>
            <a href="{{ route('credits.buy') }}" class="block px-4 py-2 text-base font-medium" style="color: {{ request()->routeIs('credits.*') ? 'rgb(138, 28, 36)' : 'rgb(58, 71, 98)' }};">
                Buy Credits
            </a>
            @if (auth()->user()?->isAdmin())
                <a href="{{ route('admin.notes.pending') }}" class="block px-4 py-2 text-base font-medium" style="color: {{ request()->routeIs('admin.*') ? 'rgb(138, 28, 36)' : 'rgb(58, 71, 98)' }};">
                    Admin
                </a>
            @endif
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
                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-base font-medium" style="color: rgb(58, 71, 98);">Profile</a>
                <a href="{{ route('credits.history') }}" class="block px-4 py-2 text-base font-medium" style="color: rgb(58, 71, 98);">Credit History</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium" style="color: rgb(58, 71, 98);">Log Out</button>
                </form>
            </div>
        </div>
    </div>
</nav>
