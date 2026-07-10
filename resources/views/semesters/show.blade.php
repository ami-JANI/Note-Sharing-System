<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 28px; color: rgb(27, 42, 74); letter-spacing: -0.02em;">
                {{ $semester->name }}
            </h2>
            <p style="font-size: 15px; color: rgb(91, 104, 133); margin-top: 4px;">{{ $subjects->count() }} {{ Str::plural('subject', $subjects->count()) }} in this semester</p>
        </div>
    </x-slot>

    <div style="padding: 48px 0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: rgb(91, 104, 133); margin-bottom: 28px;">
                <a href="{{ route('dashboard') }}" style="color: rgb(91, 104, 133); text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='rgb(138, 28, 36)'" onmouseout="this.style.color='rgb(91, 104, 133)'">Dashboard</a>
                <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
                <span style="font-weight: 600; color: rgb(27, 42, 74);">{{ $semester->name }}</span>
            </nav>

            @if ($subjects->isEmpty())
                <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 64px 32px; text-align: center;">
                    <svg style="margin: 0 auto; width: 48px; height: 48px; color: rgb(91, 104, 133);" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                    <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 18px; color: rgb(27, 42, 74); margin-top: 16px;">No subjects</h3>
                    <p style="font-size: 14px; color: rgb(91, 104, 133); margin-top: 6px;">No subjects have been added to this semester yet.</p>
                </div>
            @else
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px;">
                    @foreach ($subjects as $subject)
                        <a href="{{ route('subjects.show', $subject) }}"
                           style="display: block; background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 15px; padding: 26px; color: rgb(27, 42, 74); transition: box-shadow 0.2s, border-color 0.2s; text-decoration: none;"
                           onmouseover="this.style.boxShadow='0 8px 30px -8px rgba(27, 42, 74, 0.15)'; this.style.borderColor='rgba(138, 28, 36, 0.3)'"
                           onmouseout="this.style.boxShadow='none'; this.style.borderColor='rgba(27, 42, 74, 0.1)'">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                                <span style="font-size: 13px; font-weight: 700; color: rgb(138, 28, 36); letter-spacing: 0.03em; font-family: 'Source Serif 4', serif;">{{ $subject->code }}</span>
                                <svg style="width: 16px; height: 16px; color: rgb(91, 104, 133);" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </div>
                            <div style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 20px; line-height: 1.2; margin-bottom: 12px;">{{ $subject->name }}</div>
                            <div style="display: flex; gap: 16px; font-size: 13px; color: rgb(91, 104, 133);">
                                <span style="display: flex; align-items: center; gap: 5px;">
                                    <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                    {{ $subject->notes->count() }} {{ Str::plural('note', $subject->notes->count()) }}
                                </span>
                                <span style="display: flex; align-items: center; gap: 5px;">
                                    <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                    {{ $subject->previousQuestions->count() }} {{ Str::plural('PQ', $subject->previousQuestions->count()) }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
