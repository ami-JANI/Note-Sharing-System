<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
            <h2 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 28px; color: rgb(27, 42, 74); letter-spacing: -0.02em;">
                {{ $user->name }}'s Profile
            </h2>
            @auth
                @if (auth()->id() !== $user->id)
                    @php($isFavorited = auth()->user()->hasFavorited($user))
                    <form method="POST" action="{{ route('users.favorite', $user) }}">
                        @csrf
                        <button type="submit"
                                style="display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.15s; {{ $isFavorited ? 'background: rgba(138, 28, 36, 0.09); color: rgb(138, 28, 36); border: 1px solid rgba(138, 28, 36, 0.3);' : 'background: rgb(138, 28, 36); color: rgb(251, 248, 243); border: 1px solid rgb(138, 28, 36);' }}">
                            <svg style="width: 15px; height: 15px;" viewBox="0 0 24 24" fill="{{ $isFavorited ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.562.562 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                            </svg>
                            {{ $isFavorited ? 'Favorited' : 'Favorite' }}
                        </button>
                    </form>
                @endif
            @endauth
        </div>
    </x-slot>

    <div style="padding: 48px 0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="max-width: 800px; margin: 0 auto;">

            @if (session('status'))
                <div style="background: rgba(46, 125, 79, 0.08); color: rgb(46, 125, 79); border: 1px solid rgba(46, 125, 79, 0.2); border-radius: 12px; padding: 12px 16px; margin-bottom: 20px; font-size: 14px; font-weight: 500;">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Profile Card --}}
            <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 32px; margin-bottom: 28px;">
                <div style="display: flex; align-items: flex-start; gap: 24px;">
                    @if ($user->photo)
                        <img src="{{ Storage::url($user->photo) }}" alt="{{ $user->name }}" style="width: 96px; height: 96px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(27, 42, 74, 0.1); flex-shrink: 0;">
                    @else
                        <div style="width: 96px; height: 96px; border-radius: 50%; background: rgba(138, 28, 36, 0.09); color: rgb(138, 28, 36); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 36px; font-family: 'Source Serif 4', serif; border: 2px solid rgba(27, 42, 74, 0.1); flex-shrink: 0;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div style="min-width: 0;">
                        <h3 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 24px; color: rgb(27, 42, 74);">{{ $user->name }}</h3>
                        @if ($user->roll)
                            <p style="font-size: 14px; color: rgb(91, 104, 133); margin-top: 4px;">Roll: {{ $user->roll }}</p>
                        @endif
                        @if ($user->currentSemester)
                            <p style="font-size: 14px; color: rgb(91, 104, 133);">{{ $user->currentSemester->name }}</p>
                        @endif
                        @if ($user->department)
                            <p style="font-size: 14px; color: rgb(91, 104, 133);">{{ $user->department }}</p>
                        @endif
                        <div style="display: flex; align-items: center; gap: 16px; margin-top: 12px;">
                            <span style="font-size: 14px; color: rgb(91, 104, 133);">
                                <span style="font-weight: 700; color: rgb(27, 42, 74);">{{ $notes->count() }}</span> notes uploaded
                            </span>
                            <span style="font-size: 14px; color: rgb(91, 104, 133);">
                                Rating: {{ number_format($averageRating, 1) }}/5
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Uploaded Notes --}}
            <div>
                <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 20px; color: rgb(27, 42, 74); margin-bottom: 16px;">Uploaded Notes</h3>

                @if ($notes->isEmpty())
                    <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 64px 32px; text-align: center;">
                        <svg style="margin: 0 auto; width: 48px; height: 48px; color: rgb(91, 104, 133);" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 18px; color: rgb(27, 42, 74); margin-top: 16px;">No notes yet</h3>
                        <p style="font-size: 14px; color: rgb(91, 104, 133); margin-top: 6px;">This user hasn't uploaded any notes yet.</p>
                    </div>
                @else
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px;">
                        @foreach ($notes as $note)
                            <a href="{{ route('notes.show', $note) }}"
                               style="display: block; background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 15px; padding: 22px; color: rgb(27, 42, 74); text-decoration: none; transition: box-shadow 0.2s, border-color 0.2s;"
                               onmouseover="this.style.boxShadow='0 8px 30px -8px rgba(27, 42, 74, 0.15)'; this.style.borderColor='rgba(138, 28, 36, 0.3)'"
                               onmouseout="this.style.boxShadow='none'; this.style.borderColor='rgba(27, 42, 74, 0.1)'">
                                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                                    <div style="width: 36px; height: 36px; background: rgba(138, 28, 36, 0.06); color: rgb(138, 28, 36); border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </div>
                                    <div style="min-width: 0;">
                                        <div style="font-weight: 600; font-size: 14px; color: rgb(27, 42, 74); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $note->title }}</div>
                                        <div style="font-size: 12px; color: rgb(91, 104, 133);">{{ $note->course_no }}</div>
                                    </div>
                                </div>
                                @if ($note->description)
                                    <p style="font-size: 13px; color: rgb(91, 104, 133); overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; margin-bottom: 8px;">{{ $note->description }}</p>
                                @endif
                                <div style="font-size: 12px; color: rgb(138, 150, 174);">{{ $note->created_at->format('M d, Y') }}</div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
