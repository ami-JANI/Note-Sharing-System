<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; align-items: center; gap: 12px;">
            <a href="{{ route('messages.index') }}" style="display: inline-flex; align-items: center; gap: 4px; color: rgb(91, 104, 133); text-decoration: none; font-size: 14px; transition: color 0.15s;"
               onmouseover="this.style.color='rgb(138, 28, 36)'" onmouseout="this.style.color='rgb(91, 104, 133)'">
                <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </a>
            <div style="display: flex; align-items: center; gap: 12px;">
                @if ($partner->photo ?? null)
                    <img src="{{ Storage::url($partner->photo) }}" alt="{{ $partner->name }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                @else
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(138, 28, 36, 0.09); color: rgb(138, 28, 36); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px; font-family: 'Source Serif 4', serif;">
                        {{ strtoupper(substr($partner->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h2 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 20px; color: rgb(27, 42, 74); line-height: 1.2;">{{ $partner->name }}</h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div style="padding: 0 0 24px;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="max-width: 720px; margin: 0 auto;">

            {{-- Dummy data until sazzathrafee's backend merges --}}
            @php
                $partner = $partner ?? (object) ['id' => 2, 'name' => 'Daniel Reyes', 'photo' => null];
                $messages = $messages ?? collect([
                    (object) ['id' => 1, 'sender_id' => 2, 'body' => 'Hey, do you have the solutions for Problem Set 3?', 'created_at' => now()->subHours(2)],
                    (object) ['id' => 2, 'sender_id' => auth()->id(), 'body' => 'Yeah, I finished it last night. Which question are you stuck on?', 'created_at' => now()->subHours(1)->addMinutes(45)],
                    (object) ['id' => 3, 'sender_id' => 2, 'body' => 'Question 5 mainly — the recursion part. I cannot figure out the base case.', 'created_at' => now()->subMinutes(30)],
                    (object) ['id' => 4, 'sender_id' => auth()->id(), 'body' => 'The base case is when n <= 1, return n. Then for the recursive step you call fib(n-1) + fib(n-2). I can send you my full working if you want.', 'created_at' => now()->subMinutes(20)],
                    (object) ['id' => 5, 'sender_id' => 2, 'body' => 'That would be amazing, thanks!', 'created_at' => now()->subMinutes(15)],
                ]);
            @endphp

            {{-- Messages thread --}}
            <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; overflow: hidden;">
                <div style="padding: 24px; min-height: 400px; max-height: 60vh; overflow-y: auto; display: flex; flex-direction: column; gap: 12px;">
                    @forelse ($messages as $msg)
                        @php $isMine = $msg->sender_id === auth()->id(); @endphp
                        <div style="display: flex; {{ $isMine ? 'justify-content: flex-end;' : 'justify-content: flex-start;' }}">
                            <div style="max-width: 75%; padding: 12px 16px; border-radius: 14px; {{ $isMine ? 'background: rgb(138, 28, 36); color: rgb(251, 248, 243); border-bottom-right-radius: 4px;' : 'background: rgba(27, 42, 74, 0.06); color: rgb(27, 42, 74); border-bottom-left-radius: 4px;' }}">
                                <p style="font-size: 14px; line-height: 1.5; white-space: pre-wrap;">{{ $msg->body }}</p>
                                <p style="font-size: 11px; margin-top: 6px; {{ $isMine ? 'color: rgba(251, 248, 243, 0.6);' : 'color: rgb(138, 150, 174);' }}">{{ $msg->created_at->format('g:i A') }}</p>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 48px 0;">
                            <p style="font-size: 14px; color: rgb(91, 104, 133);">No messages yet. Say hello!</p>
                        </div>
                    @endforelse
                </div>

                {{-- Reply box --}}
                <div style="padding: 16px 24px; border-top: 1px solid rgba(27, 42, 74, 0.08);">
                    <form method="POST" action="{{ route('messages.store') }}" style="display: flex; gap: 10px; align-items: flex-end;">
                        @csrf
                        <input type="hidden" name="recipient_id" value="{{ $partner->id }}">
                        <textarea name="body" rows="1" required placeholder="Type a message..."
                                  style="flex: 1; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 10px; font-size: 14px; color: rgb(27, 42, 74); outline: none; resize: none; font-family: 'Public Sans', sans-serif; max-height: 120px; transition: border-color 0.15s;"
                                  onfocus="this.style.borderColor='rgb(138, 28, 36)'" onblur="this.style.borderColor='rgba(27, 42, 74, 0.15)'"
                                  onkeydown="if(event.key==='Enter' && !event.shiftKey){event.preventDefault();this.form.submit();}"></textarea>
                        <button type="submit"
                                style="display: inline-flex; align-items: center; justify-content: center; width: 42px; height: 42px; background: rgb(138, 28, 36); color: rgb(251, 248, 243); border: none; border-radius: 10px; cursor: pointer; flex-shrink: 0; transition: background 0.15s;"
                                onmouseover="this.style.background='rgb(110, 20, 27)'" onmouseout="this.style.background='rgb(138, 28, 36)'">
                            <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
