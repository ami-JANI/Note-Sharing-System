<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 28px; color: rgb(27, 42, 74); letter-spacing: -0.02em;">
                {{ $note->title }}
            </h2>
            <p style="font-size: 15px; color: rgb(91, 104, 133); margin-top: 4px;">{{ $note->course_no }} &middot; {{ $note->course_title }}</p>
        </div>
    </x-slot>

    <div style="padding: 48px 0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="max-width: 800px; margin: 0 auto;">

            {{-- Breadcrumb --}}
            <nav style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: rgb(91, 104, 133); margin-bottom: 28px;">
                <a href="{{ route('dashboard') }}" style="color: rgb(91, 104, 133); text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='rgb(138, 28, 36)'" onmouseout="this.style.color='rgb(91, 104, 133)'">Browse</a>
                <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
                <span style="font-weight: 600; color: rgb(27, 42, 74);">{{ $note->title }}</span>
            </nav>

            {{-- Dummy data until backend merges --}}
            @php
                $note = $note ?? (object) [
                    'id' => 1,
                    'title' => 'Elasticity & Market Demand',
                    'course_no' => 'ECON 201',
                    'course_title' => 'Microeconomics',
                    'credit_price' => 0,
                    'preview_image_path' => null,
                    'description' => 'Comprehensive notes covering elasticity concepts, market demand curves, and price elasticity of demand with real-world examples.',
                    'uploader' => (object) ['id' => 1, 'name' => 'Maya Okonkwo'],
                    'created_at' => now()->subDays(3),
                ];
                $isUnlocked = ($note->credit_price ?? 0) === 0;
            @endphp

            {{-- Preview Images --}}
            <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; overflow: hidden; margin-bottom: 24px;">
                <div style="padding: 24px;">
                    <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 18px; color: rgb(27, 42, 74); margin-bottom: 16px;">Preview</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        {{-- Page 1 placeholder --}}
                        <div style="width: 100%; aspect-ratio: 3/4; background: rgba(27, 42, 74, 0.04); border: 1px solid rgba(27, 42, 74, 0.06); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 8px;">
                            <svg style="width: 32px; height: 32px; color: rgb(91, 104, 133);" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <span style="font-size: 12px; color: rgb(91, 104, 133);">Page 1</span>
                        </div>
                        {{-- Page 2 placeholder --}}
                        <div style="width: 100%; aspect-ratio: 3/4; background: rgba(27, 42, 74, 0.04); border: 1px solid rgba(27, 42, 74, 0.06); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 8px;">
                            <svg style="width: 32px; height: 32px; color: rgb(91, 104, 133);" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <span style="font-size: 12px; color: rgb(91, 104, 133);">Page 2</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Note Info --}}
            <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 28px; margin-bottom: 24px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                    <div>
                        <div style="font-size: 13px; color: rgb(91, 104, 133); margin-bottom: 4px;">Uploaded by</div>
                        <a href="{{ route('profiles.show', $note->uploader) }}" style="font-weight: 600; font-size: 15px; color: rgb(27, 42, 74); text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='rgb(138, 28, 36)'" onmouseout="this.style.color='rgb(27, 42, 74)'">
                            {{ $note->uploader->name }}
                        </a>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 13px; color: rgb(91, 104, 133); margin-bottom: 4px;">Uploaded</div>
                        <div style="font-size: 14px; color: rgb(27, 42, 74);">{{ $note->created_at->format('M d, Y') }}</div>
                    </div>
                </div>

                @if ($note->description ?? null)
                    <p style="font-size: 14px; line-height: 1.7; color: rgb(91, 104, 133);">{{ $note->description }}</p>
                @endif
            </div>

            {{-- Action Buttons --}}
            <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 28px;">
                @if ($isUnlocked)
                    <a href="{{ route('notes.download', $note) }}"
                       style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: rgb(138, 28, 36); color: rgb(251, 248, 243); border-radius: 8px; font-size: 15px; font-weight: 600; text-decoration: none; transition: background 0.15s;"
                       onmouseover="this.style.background='rgb(110, 20, 27)'" onmouseout="this.style.background='rgb(138, 28, 36)'">
                        <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Download Note
                    </a>
                @else
                    <form method="POST" action="{{ route('notes.unlock', $note) }}" style="display: inline;">
                        @csrf
                        <button type="submit"
                                style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: rgba(192, 138, 62, 0.08); color: #C08A3E; border: 1px solid rgba(192, 138, 62, 0.2); border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; transition: background 0.15s;"
                                onmouseover="this.style.background='rgba(192, 138, 62, 0.15)'" onmouseout="this.style.background='rgba(192, 138, 62, 0.08)'">
                            <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                            </svg>
                            Unlock for {{ $note->credit_price }} credits
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
