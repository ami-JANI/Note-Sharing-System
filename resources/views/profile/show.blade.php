<x-app-layout>
    <x-slot name="header">
        <h2 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 28px; color: rgb(27, 42, 74); letter-spacing: -0.02em;">
            My Profile
        </h2>
    </x-slot>

    <div style="padding: 48px 0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="max-width: 820px; margin: 0 auto;">

            @if (session('status'))
                <div style="background: rgba(46, 125, 79, 0.08); color: rgb(46, 125, 79); border: 1px solid rgba(46, 125, 79, 0.2); border-radius: 12px; padding: 12px 16px; margin-bottom: 20px; font-size: 14px; font-weight: 500;">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Info card --}}
            <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 32px; margin-bottom: 28px;">
                <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 24px; flex-wrap: wrap;">
                    <div style="display: flex; align-items: flex-start; gap: 24px;">
                        @if ($user->photo)
                            <img src="{{ Storage::url($user->photo) }}" alt="{{ $user->name }}" style="width: 88px; height: 88px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(27, 42, 74, 0.1);">
                        @else
                            <div style="width: 88px; height: 88px; border-radius: 50%; background: rgba(138, 28, 36, 0.09); color: rgb(138, 28, 36); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 34px; font-family: 'Source Serif 4', serif;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <h3 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 22px; color: rgb(27, 42, 74);">{{ $user->name }}</h3>
                            <div style="margin-top: 10px; display: grid; grid-template-columns: auto 1fr; gap: 4px 16px; font-size: 14px;">
                                <span style="color: rgb(91, 104, 133);">Email</span><span style="color: rgb(27, 42, 74);">{{ $user->email }}</span>
                                <span style="color: rgb(91, 104, 133);">Roll</span><span style="color: rgb(27, 42, 74);">{{ $user->roll ?: 'Not set' }}</span>
                                <span style="color: rgb(91, 104, 133);">Phone</span><span style="color: rgb(27, 42, 74);">{{ $user->phone ?: 'Not set' }}</span>
                                <span style="color: rgb(91, 104, 133);">Department</span><span style="color: rgb(27, 42, 74);">{{ $user->department ?: 'Not set' }}</span>
                                <span style="color: rgb(91, 104, 133);">Batch</span><span style="color: rgb(27, 42, 74);">{{ $user->batch ?: 'Not set' }}</span>
                                <span style="color: rgb(91, 104, 133);">Semester</span><span style="color: rgb(27, 42, 74);">{{ optional($user->currentSemester)->name ?: 'Not set' }}</span>
                                <span style="color: rgb(91, 104, 133);">Credits</span><span style="color: rgb(27, 42, 74); font-weight: 600;">{{ number_format($user->credits ?? 0) }}</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}"
                       style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; background: rgb(138, 28, 36); color: rgb(251, 248, 243); border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; transition: background 0.15s;"
                       onmouseover="this.style.background='rgb(110, 20, 27)'" onmouseout="this.style.background='rgb(138, 28, 36)'">
                        <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                        </svg>
                        Edit Profile
                    </a>
                </div>
            </div>

            {{-- Upload progress --}}
            @php
                $uploadCount = $notes->count();
                $cycle = $uploadCount % 5;
            @endphp
            <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 24px 28px; margin-bottom: 28px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                    <span style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 16px; color: rgb(27, 42, 74);">Upload progress</span>
                    <span style="font-size: 14px; font-weight: 600; color: {{ $cycle > 0 ? 'rgb(138, 28, 36)' : 'rgb(91, 104, 133)' }};">{{ $cycle }}/5</span>
                </div>
                <div style="width: 100%; height: 10px; background: rgba(27, 42, 74, 0.06); border-radius: 5px; overflow: hidden;">
                    <div style="width: {{ ($cycle / 5) * 100 }}%; height: 100%; background: rgb(138, 28, 36); border-radius: 5px; transition: width 0.3s ease;"></div>
                </div>
                @if ($cycle === 0 && $uploadCount > 0)
                    <p style="font-size: 13px; color: rgb(91, 104, 133); margin-top: 8px;">You have completed a cycle — keep uploading!</p>
                @else
                    <p style="font-size: 13px; color: rgb(91, 104, 133); margin-top: 8px;">{{ 5 - $cycle }} more {{ Str::plural('upload', 5 - $cycle) }} to complete this cycle.</p>
                @endif
            </div>

            {{-- My uploads --}}
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 20px; color: rgb(27, 42, 74);">My Uploaded Notes</h3>
                <a href="{{ route('notes.create') }}" style="font-size: 14px; font-weight: 600; color: rgb(138, 28, 36); text-decoration: none;">+ Upload a note</a>
            </div>

            @forelse ($notes as $note)
                @php
                    [$label, $bg, $fg] = match (true) {
                        $note->status === 'pending' => ['Pending approval', 'rgba(192, 138, 62, 0.1)', '#C08A3E'],
                        $note->status === 'rejected' => ['Rejected', 'rgba(180, 30, 30, 0.08)', 'rgb(180, 30, 30)'],
                        $note->hidden => ['Hidden', 'rgba(27, 42, 74, 0.08)', 'rgb(91, 104, 133)'],
                        default => ['Live', 'rgba(46, 125, 79, 0.1)', 'rgb(46, 125, 79)'],
                    };
                @endphp
                <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 14px; padding: 18px 20px; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
                    <div style="min-width: 0;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                            <span style="font-weight: 600; font-size: 15px; color: rgb(27, 42, 74);">{{ $note->title }}</span>
                            <span style="display: inline-flex; padding: 2px 10px; border-radius: 100px; font-size: 11px; font-weight: 600; background: {{ $bg }}; color: {{ $fg }};">{{ $label }}</span>
                        </div>
                        <div style="font-size: 13px; color: rgb(91, 104, 133);">{{ $note->course_no }} &middot; {{ $note->course_title }} &middot; {{ $note->credit_price > 0 ? $note->credit_price . ' credits' : 'Free' }}</div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        @if ($note->status === 'approved' && ! $note->hidden)
                            <a href="{{ route('notes.show', $note) }}" style="font-size: 13px; font-weight: 600; color: rgb(58, 71, 98); text-decoration: none; padding: 6px 12px;">View</a>
                        @endif
                        @if ($note->status === 'approved')
                            <form method="POST" action="{{ route('notes.visibility', $note) }}" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="font-size: 13px; font-weight: 600; color: rgb(58, 71, 98); background: rgba(27, 42, 74, 0.05); border: 1px solid rgba(27, 42, 74, 0.12); border-radius: 8px; padding: 6px 12px; cursor: pointer;">
                                    {{ $note->hidden ? 'Unhide' : 'Hide' }}
                                </button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('notes.destroy', $note) }}" style="display: inline;" onsubmit="return confirm('Delete this note permanently?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="font-size: 13px; font-weight: 600; color: rgb(180, 30, 30); background: rgba(180, 30, 30, 0.06); border: 1px solid rgba(180, 30, 30, 0.2); border-radius: 8px; padding: 6px 12px; cursor: pointer;">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 48px 32px; text-align: center;">
                    <p style="font-size: 14px; color: rgb(91, 104, 133);">You haven't uploaded any notes yet.</p>
                </div>
            @endforelse

            {{-- Downloaded notes --}}
            <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 20px; color: rgb(27, 42, 74); margin: 40px 0 16px;">Downloaded Notes</h3>
            @forelse ($downloadedNotes as $note)
                <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 14px; padding: 16px 20px; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
                    <div style="min-width: 0;">
                        <div style="font-weight: 600; font-size: 15px; color: rgb(27, 42, 74);">{{ $note->title }}</div>
                        <div style="font-size: 13px; color: rgb(91, 104, 133);">{{ $note->course_no }} &middot; by {{ optional($note->uploader)->name ?? 'Unknown' }} &middot; downloaded {{ $note->pivot->created_at?->diffForHumans() }}</div>
                    </div>
                    @if ($note->status === 'approved' && ! $note->hidden)
                        <a href="{{ route('notes.show', $note) }}" style="font-size: 13px; font-weight: 600; color: rgb(58, 71, 98); text-decoration: none; padding: 6px 12px;">View</a>
                    @endif
                </div>
            @empty
                <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 40px 32px; text-align: center;">
                    <p style="font-size: 14px; color: rgb(91, 104, 133);">You haven't downloaded any notes yet.</p>
                </div>
            @endforelse

            {{-- Favorite uploaders --}}
            <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 20px; color: rgb(27, 42, 74); margin: 40px 0 16px;">Favorite Uploaders</h3>
            @forelse ($favoriteUploaders as $uploader)
                <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 14px; padding: 16px 20px; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 14px; min-width: 0;">
                        @if ($uploader->photo)
                            <img src="{{ Storage::url($uploader->photo) }}" alt="{{ $uploader->name }}" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover;">
                        @else
                            <div style="width: 44px; height: 44px; border-radius: 50%; background: rgba(138, 28, 36, 0.09); color: rgb(138, 28, 36); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 18px; font-family: 'Source Serif 4', serif;">
                                {{ strtoupper(substr($uploader->name, 0, 1)) }}
                            </div>
                        @endif
                        <div style="min-width: 0;">
                            <div style="font-weight: 600; font-size: 15px; color: rgb(27, 42, 74);">{{ $uploader->name }}</div>
                            <div style="font-size: 13px; color: rgb(91, 104, 133);">{{ $uploader->notes_count }} {{ Str::plural('note', $uploader->notes_count) }} &middot; {{ $uploader->department ?: 'No department' }}</div>
                        </div>
                    </div>
                    <a href="{{ route('profiles.show', $uploader) }}" style="font-size: 13px; font-weight: 600; color: rgb(58, 71, 98); text-decoration: none; padding: 6px 12px;">View profile</a>
                </div>
            @empty
                <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 40px 32px; text-align: center;">
                    <p style="font-size: 14px; color: rgb(91, 104, 133);">You haven't favorited any uploaders yet.</p>
                </div>
            @endforelse

            {{-- Reviews given --}}
            <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 20px; color: rgb(27, 42, 74); margin: 40px 0 16px;">Reviews You've Given</h3>
            @forelse ($reviewsGiven as $review)
                <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 14px; padding: 16px 20px; margin-bottom: 12px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                        <div style="font-weight: 600; font-size: 15px; color: rgb(27, 42, 74);">
                            @if ($review->note)
                                <a href="{{ route('notes.show', $review->note) }}" style="color: rgb(27, 42, 74); text-decoration: none;">{{ $review->note->title }}</a>
                            @else
                                <span style="color: rgb(91, 104, 133);">(note removed)</span>
                            @endif
                        </div>
                        <div style="color: rgb(192, 138, 62); font-size: 14px; letter-spacing: 2px;">
                            {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                        </div>
                    </div>
                    @if ($review->comment)
                        <p style="font-size: 14px; color: rgb(58, 71, 98); margin-top: 8px;">{{ $review->comment }}</p>
                    @endif
                    <div style="font-size: 12px; color: rgb(138, 150, 174); margin-top: 8px;">{{ $review->created_at->format('M d, Y') }}</div>
                </div>
            @empty
                <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 40px 32px; text-align: center;">
                    <p style="font-size: 14px; color: rgb(91, 104, 133);">You haven't reviewed any notes yet.</p>
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>
