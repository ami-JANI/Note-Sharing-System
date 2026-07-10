<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 28px; color: rgb(27, 42, 74); letter-spacing: -0.02em;">
                Admin &mdash; Users
            </h2>
        </div>
    </x-slot>

    <div style="padding: 48px 0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="max-width: 1000px; margin: 0 auto;">

            {{-- Navigation tabs --}}
            <nav style="display: flex; gap: 28px; margin-bottom: 28px; border-bottom: 1px solid rgba(27, 42, 74, 0.1); padding-bottom: 0;">
                <span style="padding-bottom: 12px; font-size: 15px; font-weight: 600; color: rgb(138, 28, 36); border-bottom: 2px solid rgb(138, 28, 36);">Users</span>
                <a href="{{ route('admin.notes.pending') }}" style="padding-bottom: 12px; font-size: 15px; font-weight: 600; color: rgb(91, 104, 133); border-bottom: 2px solid transparent; text-decoration: none; transition: all 0.15s;">Pending Notes</a>
            </nav>

            @if (session('status'))
                <div style="background: rgba(46, 125, 79, 0.06); border: 1px solid rgba(46, 125, 79, 0.2); color: rgb(46, 125, 79); padding: 12px 16px; border-radius: 10px; font-size: 14px; margin-bottom: 24px;">
                    {{ session('status') }}
                </div>
            @endif

            <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 28px;">
                <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 20px; color: rgb(27, 42, 74); margin-bottom: 20px;">All Users</h3>

                @if ($users->isEmpty())
                    <div style="text-align: center; padding: 48px 0;">
                        <svg style="margin: 0 auto; width: 48px; height: 48px; color: rgb(91, 104, 133);" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 18px; color: rgb(27, 42, 74); margin-top: 16px;">No users</h3>
                        <p style="font-size: 14px; color: rgb(91, 104, 133); margin-top: 6px;">No users found.</p>
                    </div>
                @else
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="border-bottom: 1px solid rgba(27, 42, 74, 0.08);">
                                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: rgb(91, 104, 133); text-transform: uppercase; letter-spacing: 0.05em;">User</th>
                                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: rgb(91, 104, 133); text-transform: uppercase; letter-spacing: 0.05em;">Department</th>
                                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: rgb(91, 104, 133); text-transform: uppercase; letter-spacing: 0.05em;">Credits</th>
                                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: rgb(91, 104, 133); text-transform: uppercase; letter-spacing: 0.05em;">Status</th>
                                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: rgb(91, 104, 133); text-transform: uppercase; letter-spacing: 0.05em;">Joined</th>
                                    <th style="padding: 12px 16px; text-align: right; font-size: 12px; font-weight: 600; color: rgb(91, 104, 133); text-transform: uppercase; letter-spacing: 0.05em;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr style="border-bottom: 1px solid rgba(27, 42, 74, 0.06);">
                                        <td style="padding: 14px 16px;">
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                @if ($user->photo ?? null)
                                                    <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 1px solid rgba(27, 42, 74, 0.1);">
                                                @else
                                                    <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(138, 28, 36, 0.09); color: rgb(138, 28, 36); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; font-family: 'Source Serif 4', serif; border: 1px solid rgba(27, 42, 74, 0.1);">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div style="min-width: 0;">
                                                    <div style="font-weight: 600; font-size: 14px; color: rgb(27, 42, 74); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $user->name }}</div>
                                                    <div style="font-size: 12px; color: rgb(91, 104, 133);">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding: 14px 16px; font-size: 14px; color: rgb(91, 104, 133);">
                                            {{ $user->department ?? '—' }}
                                        </td>
                                        <td style="padding: 14px 16px; font-size: 14px; font-weight: 600; color: rgb(27, 42, 74);">
                                            {{ number_format($user->credits ?? 0) }}
                                        </td>
                                        <td style="padding: 14px 16px;">
                                            @if ($user->is_suspended ?? false)
                                                <span style="display: inline-flex; padding: 3px 10px; border-radius: 100px; font-size: 12px; font-weight: 600; background: rgba(180, 30, 30, 0.06); color: rgb(180, 30, 30);">
                                                    Suspended
                                                </span>
                                            @else
                                                <span style="display: inline-flex; padding: 3px 10px; border-radius: 100px; font-size: 12px; font-weight: 600; background: rgba(46, 125, 79, 0.08); color: rgb(46, 125, 79);">
                                                    Active
                                                </span>
                                            @endif
                                        </td>
                                        <td style="padding: 14px 16px; font-size: 14px; color: rgb(91, 104, 133);">
                                            {{ $user->created_at->format('M d, Y') }}
                                        </td>
                                        <td style="padding: 14px 16px; text-align: right;">
                                            @if ($user->id !== auth()->id())
                                                <form method="POST" action="{{ $user->is_suspended ?? false ? route('admin.users.unsuspend', $user) : route('admin.users.suspend', $user) }}" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            style="display: inline-flex; align-items: center; gap: 5px; padding: 6px 12px; font-size: 12px; font-weight: 600; border-radius: 8px; cursor: pointer; transition: background 0.15s; {{ ($user->is_suspended ?? false)
                                                                ? 'color: rgb(46, 125, 79); background: rgba(46, 125, 79, 0.06); border: 1px solid rgba(46, 125, 79, 0.2);'
                                                                : 'color: rgb(180, 30, 30); background: rgba(180, 30, 30, 0.06); border: 1px solid rgba(180, 30, 30, 0.2);' }}">
                                                        @if ($user->is_suspended ?? false)
                                                            <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            Unsuspend
                                                        @else
                                                            <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                            </svg>
                                                            Suspend
                                                        @endif
                                                    </button>
                                                </form>
                                            @else
                                                <span style="font-size: 12px; color: rgb(138, 150, 174);">You</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
