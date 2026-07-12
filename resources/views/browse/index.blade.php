<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 28px; color: rgb(27, 42, 74); letter-spacing: -0.02em;">
                Browse Notes
            </h2>
            <p style="font-size: 15px; color: rgb(91, 104, 133); margin-top: 4px;">Find notes shared by students across all courses</p>
        </div>
    </x-slot>

    <div style="padding: 48px 0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Search Bar --}}
            <form method="GET" action="{{ route('browse.index') }}" style="margin-bottom: 32px;">
                <div style="position: relative; max-width: 560px;">
                    <svg style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: rgb(91, 104, 133);" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by note name, course, or topic..."
                           style="width: 100%; padding: 12px 16px 12px 42px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 10px; font-size: 15px; color: rgb(27, 42, 74); background: white; outline: none; transition: border-color 0.15s;"
                           onfocus="this.style.borderColor='rgb(138, 28, 36)'" onblur="this.style.borderColor='rgba(27, 42, 74, 0.15)'">
                    @if (request('q'))
                        <a href="{{ route('browse.index') }}" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: rgb(91, 104, 133); text-decoration: none; font-size: 13px; transition: color 0.15s;" onmouseover="this.style.color='rgb(138, 28, 36)'" onmouseout="this.style.color='rgb(91, 104, 133)'">Clear</a>
                    @endif
                </div>
            </form>

            {{-- Dummy data until backend NOTE-102 merges --}}
            @php
                $query = request('q', '');
                $notes = collect([
                    (object) [
                        'id' => 1,
                        'title' => 'Elasticity & Market Demand',
                        'course_no' => 'ECON 201',
                        'course_title' => 'Microeconomics',
                        'credit_price' => 0,
                        'preview_image_path' => null,
                        'uploader' => (object) ['id' => 1, 'name' => 'Maya Okonkwo'],
                        'created_at' => now()->subDays(3),
                    ],
                    (object) [
                        'id' => 2,
                        'title' => 'Recursion & Big-O Notation',
                        'course_no' => 'CS 101',
                        'course_title' => 'Intro to Computer Science',
                        'credit_price' => 8,
                        'preview_image_path' => null,
                        'uploader' => (object) ['id' => 2, 'name' => 'Daniel Reyes'],
                        'created_at' => now()->subDays(1),
                    ],
                    (object) [
                        'id' => 3,
                        'title' => 'Cell Division & Mitosis',
                        'course_no' => 'BIO 240',
                        'course_title' => 'Human Physiology',
                        'credit_price' => 0,
                        'preview_image_path' => null,
                        'uploader' => (object) ['id' => 3, 'name' => 'Priya Nair'],
                        'created_at' => now()->subDays(5),
                    ],
                    (object) [
                        'id' => 4,
                        'title' => 'Supply & Demand Curves',
                        'course_no' => 'ECON 201',
                        'course_title' => 'Microeconomics',
                        'credit_price' => 5,
                        'preview_image_path' => null,
                        'uploader' => (object) ['id' => 1, 'name' => 'Maya Okonkwo'],
                        'created_at' => now()->subDays(2),
                    ],
                    (object) [
                        'id' => 5,
                        'title' => 'Linear Transformations',
                        'course_no' => 'MATH 220',
                        'course_title' => 'Linear Algebra',
                        'credit_price' => 0,
                        'preview_image_path' => null,
                        'uploader' => (object) ['id' => 4, 'name' => 'Amir Hassan'],
                        'created_at' => now()->subDays(7),
                    ],
                    (object) [
                        'id' => 6,
                        'title' => 'Organic Reactions Overview',
                        'course_no' => 'CHEM 130',
                        'course_title' => 'Organic Chemistry I',
                        'credit_price' => 12,
                        'preview_image_path' => null,
                        'uploader' => (object) ['id' => 5, 'name' => 'Sara Khan'],
                        'created_at' => now()->subDays(4),
                    ],
                ]);

                if ($query) {
                    $notes = $notes->filter(function ($note) use ($query) {
                        return str_contains(strtolower($note->title), strtolower($query))
                            || str_contains(strtolower($note->course_no), strtolower($query))
                            || str_contains(strtolower($note->course_title), strtolower($query));
                    })->values();
                }
            @endphp

            @if ($query && $notes->isEmpty())
                <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 64px 32px; text-align: center;">
                    <svg style="margin: 0 auto; width: 48px; height: 48px; color: rgb(91, 104, 133);" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 18px; color: rgb(27, 42, 74); margin-top: 16px;">No results for "{{ $query }}"</h3>
                    <p style="font-size: 14px; color: rgb(91, 104, 133); margin-top: 6px;">Try a different search term.</p>
                </div>
            @elseif ($notes->isEmpty())
                <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 64px 32px; text-align: center;">
                    <svg style="margin: 0 auto; width: 48px; height: 48px; color: rgb(91, 104, 133);" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 18px; color: rgb(27, 42, 74); margin-top: 16px;">No notes yet</h3>
                    <p style="font-size: 14px; color: rgb(91, 104, 133); margin-top: 6px;">Be the first to share notes with your campus.</p>
                </div>
            @else
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 22px;">
                    @foreach ($notes as $note)
                        <x-note-card :note="$note" />
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
