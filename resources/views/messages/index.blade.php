<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 28px; color: rgb(27, 42, 74); letter-spacing: -0.02em;">
                Messages
            </h2>
            <p style="font-size: 15px; color: rgb(91, 104, 133); margin-top: 4px;">Your conversations</p>
        </div>
    </x-slot>

    <div style="padding: 48px 0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="max-width: 720px; margin: 0 auto;">

            {{-- Falls back to dummy data if the backend didn't pass any conversations --}}
            @php
                $conversations = $messages ?? collect([
                    (object) [
                        'id' => 1,
                        'partner' => (object) ['id' => 2, 'name' => 'Daniel Reyes', 'photo' => null],
                        'latest_message' => (object) ['body' => 'Hey, do you have the solutions for Problem Set 3? I am stuck on question 5.', 'created_at' => now()->subMinutes(15)],
                        'unread_count' => 2,
                    ],
                    (object) [
                        'id' => 2,
                        'partner' => (object) ['id' => 3, 'name' => 'Priya Nair', 'photo' => null],
                        'latest_message' => (object) ['body' => 'Thanks for sharing those notes, they were really helpful for the exam!', 'created_at' => now()->subHours(3)],
                        'unread_count' => 0,
                    ],
                    (object) [
                        'id' => 3,
                        'partner' => (object) ['id' => 4, 'name' => 'Amir Hassan', 'photo' => null],
                        'latest_message' => (object) ['body' => 'I uploaded the organic chem notes you asked about.', 'created_at' => now()->subDays(1)],
                        'unread_count' => 0,
                    ],
                ]);
            @endphp

            @if ($conversations->isEmpty())
                <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 64px 32px; text-align: center;">
                    <svg style="margin: 0 auto; width: 48px; height: 48px; color: rgb(91, 104, 133);" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                    <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 18px; color: rgb(27, 42, 74); margin-top: 16px;">No messages yet</h3>
                    <p style="font-size: 14px; color: rgb(91, 104, 133); margin-top: 6px;">Start a conversation by messaging someone from their profile.</p>
                </div>
            @else
                <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; overflow: hidden;">
                    @foreach ($conversations as $conversation)
                        <a href="{{ route('messages.show', $conversation->partner) }}"
                           style="display: flex; align-items: center; gap: 14px; padding: 16px 20px; text-decoration: none; color: rgb(27, 42, 74); border-bottom: 1px solid rgba(27, 42, 74, 0.06); transition: background 0.15s; {{ $loop->last ? 'border-bottom: none;' : '' }}"
                           onmouseover="this.style.background='rgba(27, 42, 74, 0.02)'" onmouseout="this.style.background='transparent'">
                            {{-- Avatar --}}
                            @if ($conversation->partner->photo)
                                <img src="{{ Storage::url($conversation->partner->photo) }}" alt="{{ $conversation->partner->name }}"
                                     style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; flex-shrink: 0;">
                            @else
                                <div style="width: 48px; height: 48px; border-radius: 50%; background: rgba(138, 28, 36, 0.09); color: rgb(138, 28, 36); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 18px; font-family: 'Source Serif 4', serif; flex-shrink: 0;">
                                    {{ strtoupper(substr($conversation->partner->name, 0, 1)) }}
                                </div>
                            @endif

                            {{-- Content --}}
                            <div style="min-width: 0; flex: 1;">
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 4px;">
                                    <span style="font-weight: 600; font-size: 15px; color: rgb(27, 42, 74); {{ $conversation->unread_count > 0 ? '' : '' }}">{{ $conversation->partner->name }}</span>
                                    <span style="font-size: 12px; color: rgb(138, 150, 174); white-space: nowrap;">{{ $conversation->latest_message->created_at->diffForHumans() }}</span>
                                </div>
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                    <p style="font-size: 14px; color: {{ $conversation->unread_count > 0 ? 'rgb(27, 42, 74)' : 'rgb(91, 104, 133)' }}; {{ $conversation->unread_count > 0 ? 'font-weight: 500;' : '' }} overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Str::limit($conversation->latest_message->body, 80) }}</p>
                                    @if ($conversation->unread_count > 0)
                                        <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 22px; height: 22px; padding: 0 6px; border-radius: 11px; background: rgb(138, 28, 36); color: white; font-size: 11px; font-weight: 700; flex-shrink: 0;">{{ $conversation->unread_count }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
