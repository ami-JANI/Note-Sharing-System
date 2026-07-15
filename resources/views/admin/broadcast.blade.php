<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 28px; color: rgb(27, 42, 74); letter-spacing: -0.02em;">
                Admin &mdash; Broadcast Notification
            </h2>
        </div>
    </x-slot>

    <div style="padding: 48px 0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="max-width: 900px; margin: 0 auto;">

            {{-- Navigation tabs --}}
            <nav style="display: flex; gap: 28px; margin-bottom: 28px; border-bottom: 1px solid rgba(27, 42, 74, 0.1); padding-bottom: 0;">
                <a href="{{ route('admin.users') }}" style="padding-bottom: 12px; font-size: 15px; font-weight: 600; color: rgb(91, 104, 133); border-bottom: 2px solid transparent; text-decoration: none; transition: all 0.15s;">Users</a>
                <a href="{{ route('admin.notes.index') }}" style="padding-bottom: 12px; font-size: 15px; font-weight: 600; color: rgb(91, 104, 133); border-bottom: 2px solid transparent; text-decoration: none; transition: all 0.15s;">All Uploads</a>
                <a href="{{ route('admin.notes.pending') }}" style="padding-bottom: 12px; font-size: 15px; font-weight: 600; color: rgb(91, 104, 133); border-bottom: 2px solid transparent; text-decoration: none; transition: all 0.15s;">Pending Notes</a>
                <span style="padding-bottom: 12px; font-size: 15px; font-weight: 600; color: rgb(138, 28, 36); border-bottom: 2px solid rgb(138, 28, 36);">Broadcast</span>
            </nav>

            @if (session('status'))
                <div style="background: rgba(46, 125, 79, 0.06); border: 1px solid rgba(46, 125, 79, 0.2); color: rgb(46, 125, 79); padding: 12px 16px; border-radius: 10px; font-size: 14px; margin-bottom: 24px;">
                    {{ session('status') }}
                </div>
            @endif

            <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 28px;" x-data="{ target: 'all', showUserSelect: false }">
                <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 20px; color: rgb(27, 42, 74); margin-bottom: 20px;">Send a notification</h3>

                <form method="POST" action="{{ route('admin.notifications.broadcast') }}">
                    @csrf

                    {{-- Message --}}
                    <div style="margin-bottom: 24px;">
                        <label for="message" style="display: block; font-size: 14px; font-weight: 600; color: rgb(27, 42, 74); margin-bottom: 6px;">Message</label>
                        <textarea id="message" name="message" rows="4" required placeholder="Type your broadcast message..."
                                  style="width: 100%; padding: 12px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 15px; color: rgb(27, 42, 74); outline: none; resize: vertical; font-family: 'Public Sans', sans-serif; transition: border-color 0.15s;"
                                  onfocus="this.style.borderColor='rgb(138, 28, 36)'" onblur="this.style.borderColor='rgba(27, 42, 74, 0.15)'">{{ old('message') }}</textarea>
                        @error('message')
                            <p style="font-size: 13px; color: #e74c3c; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Target --}}
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-size: 14px; font-weight: 600; color: rgb(27, 42, 74); margin-bottom: 10px;">Send to</label>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <label style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: rgb(27, 42, 74); cursor: pointer;"
                                   @click="showUserSelect = false; target = 'all'">
                                <input type="radio" name="target" value="all" x-model="target"
                                       style="accent-color: rgb(138, 28, 36);">
                                All users
                            </label>
                            <label style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: rgb(27, 42, 74); cursor: pointer;"
                                   @click="showUserSelect = true; target = 'specific'">
                                <input type="radio" name="target" value="specific" x-model="target"
                                       style="accent-color: rgb(138, 28, 36);">
                                Specific users
                            </label>
                        </div>
                        @error('target')
                            <p style="font-size: 13px; color: #e74c3c; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- User Multi-Select --}}
                    <div x-show="showUserSelect" x-transition x-cloak style="margin-bottom: 24px;">
                        <label for="user_ids" style="display: block; font-size: 14px; font-weight: 600; color: rgb(27, 42, 74); margin-bottom: 6px;">Select users</label>
                        <select id="user_ids" name="user_ids[]" multiple required
                                style="width: 100%; min-height: 160px; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 14px; color: rgb(27, 42, 74); background: white; outline: none;">
                            @if (isset($users))
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            @else
                                <option value="1">Maya Okonkwo (maya@university.edu)</option>
                                <option value="2">Daniel Reyes (daniel@university.edu)</option>
                                <option value="3">Priya Nair (priya@university.edu)</option>
                                <option value="4">Amir Hassan (amir@university.edu)</option>
                            @endif
                        </select>
                        <p style="font-size: 12px; color: rgb(138, 150, 174); margin-top: 4px;">Hold Ctrl/Cmd to select multiple users.</p>
                        @error('user_ids')
                            <p style="font-size: 13px; color: #e74c3c; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: rgb(138, 28, 36); color: rgb(251, 248, 243); border: none; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; transition: background 0.15s;"
                            onmouseover="this.style.background='rgb(110, 20, 27)'" onmouseout="this.style.background='rgb(138, 28, 36)'">
                        <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        Send Broadcast
                    </button>
                </form>
            </div>

        </div>
    </div>

    <style>[x-cloak] { display: none !important; }</style>
</x-app-layout>
