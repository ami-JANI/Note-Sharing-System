@props(['note'])

{{--
    Note: this is a <div>, not an <a>, because the "by uploader" link below
    must not be nested inside another anchor (invalid HTML that browsers
    silently "fix" by breaking the DOM apart, fracturing the card visually).
--}}
<div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 15px; overflow: hidden; transition: box-shadow 0.2s, border-color 0.2s;"
     onmouseover="this.style.boxShadow='0 8px 30px -8px rgba(27, 42, 74, 0.15)'; this.style.borderColor='rgba(138, 28, 36, 0.3)'"
     onmouseout="this.style.boxShadow='none'; this.style.borderColor='rgba(27, 42, 74, 0.1)'">
    <a href="{{ route('notes.show', $note) }}" style="display: block; color: rgb(27, 42, 74); text-decoration: none;">
        {{-- Preview Image --}}
        <div style="width: 100%; height: 160px; background: rgba(27, 42, 74, 0.04); display: flex; align-items: center; justify-content: center; border-bottom: 1px solid rgba(27, 42, 74, 0.06);">
            @if ($note->preview_image_path ?? null)
                <img src="{{ Storage::url($note->preview_image_path) }}" alt="{{ $note->title }}" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <svg style="width: 36px; height: 36px; color: rgb(91, 104, 133);" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            @endif
        </div>
        <div style="padding: 16px 16px 0 16px;">
            <div style="font-weight: 600; font-size: 15px; color: rgb(27, 42, 74); margin-bottom: 6px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $note->title }}</div>
            <div style="font-size: 13px; color: rgb(91, 104, 133); margin-bottom: 10px;">{{ $note->course_no }} &middot; {{ $note->course_title }}</div>
        </div>
    </a>
    {{-- Price + uploader (outside the note link so the uploader link isn't nested inside it) --}}
    <div style="padding: 0 16px 16px 16px; display: flex; align-items: center; justify-content: space-between;">
        @if (($note->credit_price ?? 0) > 0)
            <span style="display: inline-flex; padding: 3px 10px; border-radius: 100px; font-size: 12px; font-weight: 600; background: rgba(192, 138, 62, 0.08); color: #C08A3E;">
                {{ $note->credit_price }} credits
            </span>
        @else
            <span style="display: inline-flex; padding: 3px 10px; border-radius: 100px; font-size: 12px; font-weight: 600; background: rgba(46, 125, 79, 0.08); color: rgb(46, 125, 79);">
                Free
            </span>
        @endif
        <a href="{{ route('profiles.show', $note->uploader) }}" style="font-size: 12px; color: rgb(91, 104, 133); text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='rgb(138, 28, 36)'" onmouseout="this.style.color='rgb(91, 104, 133)'">
            by {{ $note->uploader->name }}
        </a>
    </div>
</div>
