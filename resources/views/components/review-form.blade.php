<div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 28px; margin-top: 24px;">
    <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 18px; color: rgb(27, 42, 74); margin-bottom: 20px;">Leave a Review</h3>

    @auth
        @if ($hasPurchased ?? false)
            <form method="POST" action="{{ route('reviews.store', $note) }}" id="review-form">
                @csrf

                {{-- Star Rating --}}
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: rgb(27, 42, 74); margin-bottom: 8px;">Rating</label>
                    <div id="star-rating" style="display: flex; gap: 4px;">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" class="star-btn" data-value="{{ $i }}"
                                    onclick="setRating({{ $i }})"
                                    style="background: none; border: none; cursor: pointer; padding: 2px; transition: transform 0.15s;"
                                    onmouseover="this.style.transform='scale(1.2)'"
                                    onmouseout="this.style.transform='scale(1)'">
                                <svg id="star-{{ $i }}" style="width: 28px; height: 28px; color: rgb(91, 104, 133); transition: color 0.15s;" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" value="{{ old('rating', '') }}">
                    @error('rating')
                        <p style="font-size: 13px; color: #e74c3c; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Comment --}}
                <div style="margin-bottom: 20px;">
                    <label for="comment" style="display: block; font-size: 14px; font-weight: 600; color: rgb(27, 42, 74); margin-bottom: 6px;">Comment</label>
                    <textarea id="comment" name="comment" rows="4" required
                              placeholder="Share your thoughts on the quality and usefulness of this note..."
                              style="width: 100%; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 15px; color: rgb(27, 42, 74); outline: none; resize: vertical; font-family: 'Public Sans', sans-serif; transition: border-color 0.15s;"
                              onfocus="this.style.borderColor='rgb(138, 28, 36)'" onblur="this.style.borderColor='rgba(27, 42, 74, 0.15)'">{{ old('comment') }}</textarea>
                    @error('comment')
                        <p style="font-size: 13px; color: #e74c3c; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit"
                        style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: rgb(138, 28, 36); color: rgb(251, 248, 243); border: none; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; transition: background 0.15s;"
                        onmouseover="this.style.background='rgb(110, 20, 27)'" onmouseout="this.style.background='rgb(138, 28, 36)'">
                    Submit Review
                </button>
            </form>

            <script>
                function setRating(value) {
                    document.getElementById('rating-input').value = value;
                    for (var i = 1; i <= 5; i++) {
                        var star = document.getElementById('star-' + i);
                        if (i <= value) {
                            star.style.color = '#C08A3E';
                        } else {
                            star.style.color = 'rgb(91, 104, 133)';
                        }
                    }
                }
            </script>
        @else
            <div style="background: rgba(192, 138, 62, 0.06); border: 1px solid rgba(192, 138, 62, 0.15); border-radius: 10px; padding: 20px; text-align: center;">
                <svg style="width: 32px; height: 32px; color: #C08A3E; margin: 0 auto 8px;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                </svg>
                <p style="font-size: 14px; color: rgb(91, 104, 133);">You must unlock this note before leaving a review.</p>
            </div>
        @endif
    @else
        <div style="background: rgba(138, 28, 36, 0.04); border: 1px solid rgba(138, 28, 36, 0.1); border-radius: 10px; padding: 20px; text-align: center;">
            <p style="font-size: 14px; color: rgb(91, 104, 133);">
                <a href="{{ route('login') }}" style="color: rgb(138, 28, 36); font-weight: 600; text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='rgb(110, 20, 27)'" onmouseout="this.style.color='rgb(138, 28, 36)'">Log in</a>
                to leave a review.
            </p>
        </div>
    @endauth
</div>
