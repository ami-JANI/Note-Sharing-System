<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 28px; color: rgb(27, 42, 74); letter-spacing: -0.02em;">
                Manage Reviews
            </h2>
            <p style="font-size: 15px; color: rgb(91, 104, 133); margin-top: 4px;">Moderate student reviews</p>
        </div>
    </x-slot>

    <div style="padding: 48px 0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">



            <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <thead>
                        <tr style="background: rgba(27, 42, 74, 0.03); border-bottom: 1px solid rgba(27, 42, 74, 0.1);">
                            <th style="text-align: left; padding: 14px 16px; font-weight: 600; color: rgb(27, 42, 74); font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em;">User</th>
                            <th style="text-align: left; padding: 14px 16px; font-weight: 600; color: rgb(27, 42, 74); font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em;">Note</th>
                            <th style="text-align: left; padding: 14px 16px; font-weight: 600; color: rgb(27, 42, 74); font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em;">Rating</th>
                            <th style="text-align: left; padding: 14px 16px; font-weight: 600; color: rgb(27, 42, 74); font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em;">Comment</th>
                            <th style="text-align: left; padding: 14px 16px; font-weight: 600; color: rgb(27, 42, 74); font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em;">Date</th>
                            <th style="text-align: right; padding: 14px 16px; font-weight: 600; color: rgb(27, 42, 74); font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reviews as $review)
                            <tr style="border-bottom: 1px solid rgba(27, 42, 74, 0.06); transition: background 0.15s;"
                                onmouseover="this.style.background='rgba(27, 42, 74, 0.02)'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 14px 16px; color: rgb(27, 42, 74); font-weight: 500;">{{ $review->user->name }}</td>
                                <td style="padding: 14px 16px; color: rgb(91, 104, 133); max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $review->note->title }}</td>
                                <td style="padding: 14px 16px;">
                                    <div style="display: flex; gap: 2px;">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg style="width: 14px; height: 14px; {{ $i <= $review->rating ? 'color: #C08A3E;' : 'color: rgb(175, 182, 201);' }}" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </td>
                                <td style="padding: 14px 16px; color: rgb(91, 104, 133); max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $review->comment }}</td>
                                <td style="padding: 14px 16px; color: rgb(91, 104, 133); white-space: nowrap;">{{ $review->created_at->format('M d, Y') }}</td>
                                <td style="padding: 14px 16px; text-align: right;">
                                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px;">
                                        <form method="POST" action="{{ route('admin.reviews.hide', $review) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit"
                                                    style="padding: 6px 12px; background: rgba(192, 138, 62, 0.08); color: #C08A3E; border: 1px solid rgba(192, 138, 62, 0.2); border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; transition: background 0.15s;"
                                                    onmouseover="this.style.background='rgba(192, 138, 62, 0.15)'" onmouseout="this.style.background='rgba(192, 138, 62, 0.08)'">
                                                Hide
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.reviews.delete', $review) }}" style="display: inline;"
                                              onsubmit="return confirm('Delete this review permanently?')">
                                            @csrf
                                            <button type="submit"
                                                    style="padding: 6px 12px; background: rgba(231, 76, 60, 0.08); color: #e74c3c; border: 1px solid rgba(231, 76, 60, 0.2); border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; transition: background 0.15s;"
                                                    onmouseover="this.style.background='rgba(231, 76, 60, 0.15)'" onmouseout="this.style.background='rgba(231, 76, 60, 0.08)'">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 48px; text-align: center; color: rgb(91, 104, 133);">No reviews to moderate.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
