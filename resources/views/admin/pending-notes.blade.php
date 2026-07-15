<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 28px; color: rgb(27, 42, 74); letter-spacing: -0.02em;">
                Admin &mdash; Pending Notes
            </h2>
        </div>
    </x-slot>

    <div style="padding: 48px 0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="max-width: 900px; margin: 0 auto;">

            {{-- Navigation tabs --}}
            <nav style="display: flex; gap: 28px; margin-bottom: 28px; border-bottom: 1px solid rgba(27, 42, 74, 0.1); padding-bottom: 0;">
                <a href="{{ route('admin.users') }}" style="padding-bottom: 12px; font-size: 15px; font-weight: 600; color: rgb(91, 104, 133); border-bottom: 2px solid transparent; text-decoration: none; transition: all 0.15s;">Users</a>
                <a href="{{ route('admin.notes.index') }}" style="padding-bottom: 12px; font-size: 15px; font-weight: 600; color: rgb(91, 104, 133); border-bottom: 2px solid transparent; text-decoration: none; transition: all 0.15s;">All Uploads</a>
                <span style="padding-bottom: 12px; font-size: 15px; font-weight: 600; color: rgb(138, 28, 36); border-bottom: 2px solid rgb(138, 28, 36);">Pending Notes</span>
                <a href="{{ route('admin.broadcast') }}" style="padding-bottom: 12px; font-size: 15px; font-weight: 600; color: rgb(91, 104, 133); border-bottom: 2px solid transparent; text-decoration: none; transition: all 0.15s;">Broadcast</a>
            </nav>

            @if (session('status'))
                <div style="background: rgba(46, 125, 79, 0.06); border: 1px solid rgba(46, 125, 79, 0.2); color: rgb(46, 125, 79); padding: 12px 16px; border-radius: 10px; font-size: 14px; margin-bottom: 24px;">
                    {{ session('status') }}
                </div>
            @endif

            <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 28px;">
                <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 20px; color: rgb(27, 42, 74); margin-bottom: 20px;">Notes awaiting approval</h3>

                @if ($pendingNotes->isEmpty())
                    <div style="text-align: center; padding: 48px 0;">
                        <svg style="margin: 0 auto; width: 48px; height: 48px; color: rgb(91, 104, 133);" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 18px; color: rgb(27, 42, 74); margin-top: 16px;">All caught up</h3>
                        <p style="font-size: 14px; color: rgb(91, 104, 133); margin-top: 6px;">No notes are pending approval.</p>
                    </div>
                @else
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="border-bottom: 1px solid rgba(27, 42, 74, 0.08);">
                                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: rgb(91, 104, 133); text-transform: uppercase; letter-spacing: 0.05em;">Note</th>
                                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: rgb(91, 104, 133); text-transform: uppercase; letter-spacing: 0.05em;">Subject</th>
                                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: rgb(91, 104, 133); text-transform: uppercase; letter-spacing: 0.05em;">Uploader</th>
                                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: rgb(91, 104, 133); text-transform: uppercase; letter-spacing: 0.05em;">Submitted</th>
                                    <th style="padding: 12px 16px; text-align: right; font-size: 12px; font-weight: 600; color: rgb(91, 104, 133); text-transform: uppercase; letter-spacing: 0.05em;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendingNotes as $note)
                                    <tr style="border-bottom: 1px solid rgba(27, 42, 74, 0.06);">
                                        <td style="padding: 14px 16px;">
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <div style="width: 36px; height: 36px; background: rgba(138, 28, 36, 0.06); color: rgb(138, 28, 36); border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                    <svg style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                    </svg>
                                                </div>
                                                <div style="min-width: 0;">
                                                    <div style="font-weight: 600; font-size: 14px; color: rgb(27, 42, 74); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $note->title }}</div>
                                                    @if ($note->description)
                                                        <div style="font-size: 12px; color: rgb(91, 104, 133); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 240px;">{{ $note->description }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding: 14px 16px; font-size: 14px; color: rgb(91, 104, 133);">
                                            {{ $note->subject->code ?? '—' }}
                                        </td>
                                        <td style="padding: 14px 16px; font-size: 14px; color: rgb(91, 104, 133);">
                                            {{ $note->uploader->name ?? '—' }}
                                        </td>
                                        <td style="padding: 14px 16px; font-size: 14px; color: rgb(91, 104, 133);">
                                            {{ $note->created_at->format('M d, Y') }}
                                        </td>
                                        <td style="padding: 14px 16px; text-align: right;">
                                            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px;">
                                                <form method="POST" action="{{ route('admin.notes.approve', $note) }}">
                                                    @csrf
                                                    <button type="submit"
                                                            style="display: inline-flex; align-items: center; gap: 5px; padding: 6px 12px; font-size: 12px; font-weight: 600; color: rgb(46, 125, 79); background: rgba(46, 125, 79, 0.06); border: 1px solid rgba(46, 125, 79, 0.2); border-radius: 8px; cursor: pointer; transition: background 0.15s;">
                                                        <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                        </svg>
                                                        Approve
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.notes.reject', $note) }}">
                                                    @csrf
                                                    <button type="submit"
                                                            style="display: inline-flex; align-items: center; gap: 5px; padding: 6px 12px; font-size: 12px; font-weight: 600; color: rgb(180, 30, 30); background: rgba(180, 30, 30, 0.06); border: 1px solid rgba(180, 30, 30, 0.2); border-radius: 8px; cursor: pointer; transition: background 0.15s;">
                                                        <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Reject
                                                    </button>
                                                </form>
                                            </div>
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
