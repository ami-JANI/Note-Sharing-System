<x-public-layout>
    <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 32px; color: rgb(27, 42, 74); letter-spacing: -0.02em;">
            Browse Notes
        </h1>
        <p style="font-size: 16px; color: rgb(91, 104, 133); margin-top: 8px;">Find notes shared by students across all courses</p>
        @if (auth()->guest())
            <p style="font-size: 14px; color: rgb(138, 28, 36); margin-top: 12px; font-weight: 500;">
                <a href="{{ route('login') }}" style="color: rgb(138, 28, 36); text-decoration: underline;">Log in</a> to unlock premium notes and start earning credits.
            </p>
        @endif
    </div>

    {{-- Search Bar --}}
    <form method="GET" action="{{ route('browse.index') }}" style="margin-bottom: 32px;">
        <div style="position: relative; max-width: 560px; margin: 0 auto;">
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

    @php
        $query = request('q', '');
        $activeFilterCount = collect([request('department'), request('course'), request('semester_id'), request('price'), request('min_rating')])->filter()->count();
    @endphp

    {{-- Two-column layout: filters left, cards right --}}
    <form method="GET" action="{{ route('browse.index') }}" id="filter-form" x-data="{ filtersOpen: false }">
        {{-- Preserve search query --}}
        @if ($query)
            <input type="hidden" name="q" value="{{ $query }}">
        @endif

        {{-- Mobile-only filter toggle --}}
        <button type="button" @click="filtersOpen = !filtersOpen" class="mobile-filter-toggle"
                style="display: none; align-items: center; gap: 8px; width: 100%; margin-bottom: 16px; padding: 12px 16px; background: white; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 10px; font-size: 14px; font-weight: 600; color: rgb(27, 42, 74); cursor: pointer;">
            <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
            Filters
            @if ($activeFilterCount > 0)
                <span style="display: inline-flex; align-items: center; justify-content: center; width: 20px; height: 20px; border-radius: 50%; background: rgb(138, 28, 36); color: white; font-size: 11px; font-weight: 700;">{{ $activeFilterCount }}</span>
            @endif
        </button>

        <div style="display: grid; grid-template-columns: 280px 1fr; gap: 28px; align-items: start;">

            {{-- Filter Panel --}}
            <div class="filter-panel" :class="{ 'filters-collapsed': !filtersOpen }" style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 24px; position: sticky; top: 100px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                    <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 16px; color: rgb(27, 42, 74); display: flex; align-items: center; gap: 8px;">
                        Filters
                        @if ($activeFilterCount > 0)
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 20px; height: 20px; border-radius: 50%; background: rgb(138, 28, 36); color: white; font-size: 11px; font-weight: 700; font-family: 'Public Sans', sans-serif;">{{ $activeFilterCount }}</span>
                        @endif
                    </h3>
                    @if ($activeFilterCount > 0)
                        <a href="{{ route('browse.index', array_merge(request()->except(['department', 'course', 'semester_id', 'price', 'min_rating']), request()->has('q') ? ['q' => request('q')] : [])) }}"
                           style="font-size: 13px; font-weight: 500; color: rgb(138, 28, 36); text-decoration: none; transition: color 0.15s;"
                           onmouseover="this.style.color='rgb(110, 20, 27)'" onmouseout="this.style.color='rgb(138, 28, 36)'">Reset</a>
                    @endif
                </div>

                {{-- Department --}}
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: rgb(27, 42, 74); margin-bottom: 6px;">Department</label>
                    <select name="department"
                            style="width: 100%; padding: 8px 12px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 14px; color: rgb(27, 42, 74); background: white; outline: none; transition: border-color 0.15s;"
                            onchange="document.getElementById('filter-form').submit()">
                        <option value="">All departments</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Course --}}
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: rgb(27, 42, 74); margin-bottom: 6px;">Course</label>
                    <select name="course"
                            style="width: 100%; padding: 8px 12px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 14px; color: rgb(27, 42, 74); background: white; outline: none; transition: border-color 0.15s;"
                            onchange="document.getElementById('filter-form').submit()">
                        <option value="">All courses</option>
                        @foreach ($courses as $courseNo)
                            <option value="{{ $courseNo }}" {{ request('course') == $courseNo ? 'selected' : '' }}>{{ $courseNo }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Semester --}}
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: rgb(27, 42, 74); margin-bottom: 6px;">Semester</label>
                    <select name="semester_id"
                            style="width: 100%; padding: 8px 12px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 14px; color: rgb(27, 42, 74); background: white; outline: none; transition: border-color 0.15s;"
                            onchange="document.getElementById('filter-form').submit()">
                        <option value="">All semesters</option>
                        @foreach ($semesters as $semester)
                            <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                                {{ $semester->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Min Rating --}}
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: rgb(27, 42, 74); margin-bottom: 6px;">Minimum rating</label>
                    <select name="min_rating"
                            style="width: 100%; padding: 8px 12px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 14px; color: rgb(27, 42, 74); background: white; outline: none; transition: border-color 0.15s;"
                            onchange="document.getElementById('filter-form').submit()">
                        <option value="">Any rating</option>
                        @foreach ([5, 4, 3, 2, 1] as $rating)
                            <option value="{{ $rating }}" {{ request('min_rating') == $rating ? 'selected' : '' }}>{{ $rating }}+ stars</option>
                        @endforeach
                    </select>
                </div>

                {{-- Price --}}
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: rgb(27, 42, 74); margin-bottom: 6px;">Price</label>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: rgb(27, 42, 74); cursor: pointer;">
                            <input type="radio" name="price" value="" {{ request('price', '') === '' ? 'checked' : '' }}
                                   onchange="document.getElementById('filter-form').submit()"
                                   style="accent-color: rgb(138, 28, 36);">
                            Any
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: rgb(27, 42, 74); cursor: pointer;">
                            <input type="radio" name="price" value="free" {{ request('price') === 'free' ? 'checked' : '' }}
                                   onchange="document.getElementById('filter-form').submit()"
                                   style="accent-color: rgb(138, 28, 36);">
                            Free only
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: rgb(27, 42, 74); cursor: pointer;">
                            <input type="radio" name="price" value="paid" {{ request('price') === 'paid' ? 'checked' : '' }}
                                   onchange="document.getElementById('filter-form').submit()"
                                   style="accent-color: rgb(138, 28, 36);">
                            Paid only
                        </label>
                    </div>
                </div>

                {{-- Apply button (for desktop, auto-submit on change already handles it) --}}
                <button type="submit"
                        style="width: 100%; padding: 10px; background: rgb(138, 28, 36); color: rgb(251, 248, 243); border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: background 0.15s;"
                        onmouseover="this.style.background='rgb(110, 20, 27)'" onmouseout="this.style.background='rgb(138, 28, 36)'">
                    Apply Filters
                </button>
            </div>

            {{-- Notes Grid --}}
            <div>
                @if ($query && $notes->isEmpty())
                    <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 64px 32px; text-align: center;">
                        <svg style="margin: 0 auto; width: 48px; height: 48px; color: rgb(91, 104, 133);" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                        <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 18px; color: rgb(27, 42, 74); margin-top: 16px;">No results for "{{ $query }}"</h3>
                        <p style="font-size: 14px; color: rgb(91, 104, 133); margin-top: 6px;">Try a different search term or adjust filters.</p>
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
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px;">
                        @foreach ($notes as $note)
                            <a href="{{ auth()->guest() ? route('login') : route('notes.show', $note) }}"
                               style="display: block; background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 15px; overflow: hidden; color: rgb(27, 42, 74); text-decoration: none; transition: box-shadow 0.2s, border-color 0.2s;"
                               onmouseover="this.style.boxShadow='0 8px 30px -8px rgba(27, 42, 74, 0.15)'; this.style.borderColor='rgba(138, 28, 36, 0.3)'"
                               onmouseout="this.style.boxShadow='none'; this.style.borderColor='rgba(27, 42, 74, 0.1)'">
                                {{-- Preview Image --}}
                                <div style="width: 100%; height: 140px; background: rgba(27, 42, 74, 0.04); display: flex; align-items: center; justify-content: center; border-bottom: 1px solid rgba(27, 42, 74, 0.06); overflow: hidden;">
                                    @if ($note->preview_image_path ?? null)
                                        <img src="{{ Storage::url($note->preview_image_path) }}" alt="{{ $note->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <svg style="width: 32px; height: 32px; color: rgb(91, 104, 133);" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    @endif
                                </div>
                                {{-- Details --}}
                                <div style="padding: 14px;">
                                    <div style="font-weight: 600; font-size: 14px; color: rgb(27, 42, 74); margin-bottom: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $note->title }}</div>
                                    <div style="font-size: 12px; color: rgb(91, 104, 133); margin-bottom: 8px;">{{ $note->course_no }} &middot; {{ $note->course_title }}</div>
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        @if (($note->credit_price ?? 0) > 0)
                                            <span style="display: inline-flex; padding: 2px 8px; border-radius: 100px; font-size: 11px; font-weight: 600; background: rgba(192, 138, 62, 0.08); color: #C08A3E;">
                                                {{ $note->credit_price }} credits
                                            </span>
                                        @else
                                            <span style="display: inline-flex; padding: 2px 8px; border-radius: 100px; font-size: 11px; font-weight: 600; background: rgba(46, 125, 79, 0.08); color: rgb(46, 125, 79);">
                                                Free
                                            </span>
                                        @endif
                                        @if (auth()->guest())
                                            <span style="font-size: 11px; color: rgb(138, 28, 36); font-weight: 500;">Log in to unlock</span>
                                        @else
                                            <span style="font-size: 12px; color: rgb(91, 104, 133);">by {{ $note->uploader->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div style="margin-top: 24px;">
                        {{ $notes->links() }}
                    </div>
                @endif
            </div>

        </div>
    </form>

    <style>
        @media (max-width: 768px) {
            form > div[style*="grid-template-columns: 280px"] {
                grid-template-columns: 1fr !important;
            }
            .mobile-filter-toggle {
                display: flex !important;
            }
            .filter-panel.filters-collapsed {
                display: none !important;
            }
        }
    </style>
</x-public-layout>
