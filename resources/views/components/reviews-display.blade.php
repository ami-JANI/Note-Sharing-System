@php
    $reviews = $reviews ?? collect([
        (object) [
            'id' => 1,
            'rating' => 5,
            'comment' => 'Excellent notes! Very clear and well-organized. Helped me ace my midterm.',
            'user' => (object) ['id' => 2, 'name' => 'Daniel Reyes'],
            'created_at' => now()->subDays(1),
        ],
        (object) [
            'id' => 2,
            'rating' => 4,
            'comment' => 'Good coverage of the main concepts. Could use more examples in the elasticity section.',
            'user' => (object) ['id' => 3, 'name' => 'Priya Nair'],
            'created_at' => now()->subDays(2),
        ],
    ]);
    $averageRating = $reviews->avg('rating') ?? 0;
    $reviewCount = $reviews->count();
@endphp

<div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 28px; margin-top: 24px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
        <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 18px; color: rgb(27, 42, 74);">Reviews</h3>
        @if ($reviewCount > 0)
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="display: flex; gap: 2px;">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg style="width: 16px; height: 16px; {{ $i <= round($averageRating) ? 'color: #C08A3E;' : 'color: rgb(175, 182, 201);' }}" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    @endfor
                </div>
                <span style="font-size: 14px; font-weight: 600; color: #C08A3E;">{{ number_format($averageRating, 1) }}</span>
                <span style="font-size: 13px; color: rgb(91, 104, 133);">({{ $reviewCount }} {{ Str::plural('review', $reviewCount) }})</span>
            </div>
        @endif
    </div>

    @if ($reviewCount === 0)
        <div style="padding: 32px; text-align: center;">
            <svg style="width: 40px; height: 40px; color: rgb(175, 182, 201); margin: 0 auto 12px;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
            </svg>
            <p style="font-size: 14px; color: rgb(91, 104, 133);">No reviews yet. Be the first to review!</p>
        </div>
    @else
        <div style="display: flex; flex-direction: column; gap: 16px;">
            @foreach ($reviews as $review)
                <div style="padding: 16px; background: rgba(27, 42, 74, 0.02); border: 1px solid rgba(27, 42, 74, 0.06); border-radius: 10px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(138, 28, 36, 0.08); display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 13px; color: rgb(138, 28, 36);">
                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <span style="font-size: 14px; font-weight: 600; color: rgb(27, 42, 74);">{{ $review->user->name }}</span>
                                <span style="font-size: 12px; color: rgb(91, 104, 133); margin-left: 8px;">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div style="display: flex; gap: 2px;">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg style="width: 14px; height: 14px; {{ $i <= $review->rating ? 'color: #C08A3E;' : 'color: rgb(175, 182, 201);' }}" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <p style="font-size: 14px; line-height: 1.6; color: rgb(91, 104, 133);">{{ $review->comment }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
