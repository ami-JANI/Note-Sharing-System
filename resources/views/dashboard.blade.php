<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 28px; color: rgb(27, 42, 74); letter-spacing: -0.02em;">
                Browse by semester
            </h2>
            <p style="font-size: 15px; color: rgb(91, 104, 133); margin-top: 4px;">Select a semester to view its subjects and materials</p>
        </div>
    </x-slot>

    <div style="padding: 48px 0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($semesters->isEmpty())
                <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 64px 32px; text-align: center;">
                    <svg style="margin: 0 auto; width: 48px; height: 48px; color: rgb(91, 104, 133);" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                    <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 18px; color: rgb(27, 42, 74); margin-top: 16px;">No semesters</h3>
                    <p style="font-size: 14px; color: rgb(91, 104, 133); margin-top: 6px;">No semesters have been added yet.</p>
                </div>
            @else
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px;">
                    @foreach ($semesters as $semester)
                        <a href="{{ route('semesters.show', $semester) }}"
                           style="display: block; background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 15px; padding: 26px; color: rgb(27, 42, 74); transition: box-shadow 0.2s, border-color 0.2s; text-decoration: none;"
                           onmouseover="this.style.boxShadow='0 8px 30px -8px rgba(27, 42, 74, 0.15)'; this.style.borderColor='rgba(138, 28, 36, 0.3)'"
                           onmouseout="this.style.boxShadow='none'; this.style.borderColor='rgba(27, 42, 74, 0.1)'">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                                <div style="width: 44px; height: 44px; border-radius: 11px; background: rgba(138, 28, 36, 0.09); color: rgb(138, 28, 36); display: flex; align-items: center; justify-content: center; font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 20px;">
                                    {{ $semester->order + 1 }}
                                </div>
                                <span style="font-size: 12px; color: rgb(91, 104, 133);">
                                    {{ $semester->subjects()->count() }} {{ Str::plural('subject', $semester->subjects()->count()) }}
                                </span>
                            </div>
                            <div style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 20px; line-height: 1.2; margin-bottom: 8px;">{{ $semester->name }}</div>
                            <div style="font-size: 14px; color: rgb(91, 104, 133);">View subjects &rarr;</div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
