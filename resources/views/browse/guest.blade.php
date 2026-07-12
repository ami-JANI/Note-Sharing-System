<x-guest-layout>
    {{-- Header --}}
    <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 32px; color: rgb(27, 42, 74); letter-spacing: -0.02em;">
            Browse Notes
        </h1>
        <p style="font-size: 16px; color: rgb(91, 104, 133); margin-top: 8px;">Find notes shared by students across all courses</p>
        @if (auth()->guest())
            <p style="font-size: 14px; color: rgb(138, 28, 36); margin-top: 12px; font-weight: 500;">
                <a href="{{ route('login') }}" style="color: rgb(138, 28, 36); text-decoration: underline;">Log in</a> to unlock premium notes and start earning credits.
            </p>
        @endif
    </div>

    @if ($notes->isEmpty())
        <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 64px 32px; text-align: center;">
            <svg style="margin: 0 auto; width: 48px; height: 48px; color: rgb(91, 104, 133);" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
            <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 18px; color: rgb(27, 42, 74); margin-top: 16px;">No notes yet</h3>
            <p style="font-size: 14px; color: rgb(91, 104, 133); margin-top: 6px;">Be the first to share notes with your campus.</p>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px;">
            @foreach ($notes as $note)
                <a href="{{ auth()->guest() ? route('login') : route('notes.show', $note) }}"
                   style="display: block; background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 15px; overflow: hidden; color: rgb(27, 42, 74); text-decoration: none; transition: box-shadow 0.2s, border-color 0.2s;"
                   onmouseover="this.style.boxShadow='0 8px 30px -8px rgba(27, 42, 74, 0.15)'; this.style.borderColor='rgba(138, 28, 36, 0.3)'"
                   onmouseout="this.style.boxShadow='none'; this.style.borderColor='rgba(27, 42, 74, 0.1)'">
                    {{-- Preview Image --}}
                    <div style="width: 100%; height: 140px; background: rgba(27, 42, 74, 0.04); display: flex; align-items: center; justify-content: center; border-bottom: 1px solid rgba(27, 42, 74, 0.06);">
                        @if ($note->preview_image_path ?? null)
                            <img src="{{ Storage::url($note->preview_image_path) }}" alt="{{ $note->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <svg style="width: 32px; height: 32px; color: rgb(91, 104, 133);" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        @endif
                    </div>
                    {{-- Details --}}
                    <div style="padding: 14px;">
                        <div style="font-weight: 600; font-size: 14px; color: rgb(27, 42, 74); margin-bottom: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $note->title }}</div>
                        <div style="font-size: 12px; color: rgb(91, 104, 133); margin-bottom: 8px;">{{ $note->course_no }} &middot; {{ $note->course_title }}</div>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            @if (($note->credit_price ?? 0) > 0)
                                <span style="display: inline-flex; padding: 2px 8px; border-radius: 100px; font-size: 11px; font-weight: 600; background: rgba(192, 138, 62, 0.08); color: #C08A3E;">
                                    {{ $note->credit_price }} credits
                                </span>
                            @else
                                <span style="display: inline-flex; padding: 2px 8px; border-radius: 100px; font-size: 11px; font-weight: 600; background: rgba(46, 125, 79, 0.08); color: rgb(46, 125, 79);">
                                    Free
                                </span>
                            @endif
                            @if (auth()->guest())
                                <span style="font-size: 11px; color: rgb(138, 28, 36); font-weight: 500;">Log in to unlock</span>
                            @else
                                <span style="font-size: 12px; color: rgb(91, 104, 133);">by {{ $note->uploader->name }}</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div style="margin-top: 24px;">
            {{ $notes->links() }}
        </div>
    @endif
</x-guest-layout>
