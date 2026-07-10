<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 28px; color: rgb(27, 42, 74); letter-spacing: -0.02em;">
                {{ $subject->code }} - {{ $subject->name }}
            </h2>
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
                <a href="{{ route('semesters.show', $subject->semester) }}" style="color: rgb(91, 104, 133); text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='rgb(138, 28, 36)'" onmouseout="this.style.color='rgb(91, 104, 133)'">{{ $subject->semester->name }}</a>
                <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
                <span style="font-weight: 600; color: rgb(27, 42, 74);">{{ $subject->code }}</span>
            </nav>

            @if (session('status'))
                <div style="background: rgba(46, 125, 79, 0.06); border: 1px solid rgba(46, 125, 79, 0.2); color: rgb(46, 125, 79); padding: 12px 16px; border-radius: 10px; font-size: 14px; margin-bottom: 24px;">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Tabs --}}
            <div x-data="{ activeTab: 'notes' }">
                <div style="border-bottom: 1px solid rgba(27, 42, 74, 0.1); margin-bottom: 28px;">
                    <nav style="display: flex; gap: 28px;">
                        <button @click="activeTab = 'notes'"
                                :style="activeTab === 'notes' ? 'color: rgb(138, 28, 36); border-bottom: 2px solid rgb(138, 28, 36);' : 'color: rgb(91, 104, 133); border-bottom: 2px solid transparent;'"
                                style="padding-bottom: 12px; font-size: 15px; font-weight: 600; border-bottom-width: 2px; border-bottom-style: solid; transition: all 0.15s; background: none; border-top: none; border-left: none; border-right: none; cursor: pointer;">
                            Notes
                            <span style="margin-left: 6px; font-size: 12px; padding: 2px 8px; border-radius: 100px; font-weight: 500;"
                                  :style="activeTab === 'notes' ? 'background: rgba(138, 28, 36, 0.09); color: rgb(138, 28, 36);' : 'background: rgba(27, 42, 74, 0.07); color: rgb(91, 104, 133);'">
                                {{ $notes->count() }}
                            </span>
                        </button>
                        <button @click="activeTab = 'pq'"
                                :style="activeTab === 'pq' ? 'color: rgb(138, 28, 36); border-bottom: 2px solid rgb(138, 28, 36);' : 'color: rgb(91, 104, 133); border-bottom: 2px solid transparent;'"
                                style="padding-bottom: 12px; font-size: 15px; font-weight: 600; border-bottom-width: 2px; border-bottom-style: solid; transition: all 0.15s; background: none; border-top: none; border-left: none; border-right: none; cursor: pointer;">
                            Previous Questions
                            <span style="margin-left: 6px; font-size: 12px; padding: 2px 8px; border-radius: 100px; font-weight: 500;"
                                  :style="activeTab === 'pq' ? 'background: rgba(138, 28, 36, 0.09); color: rgb(138, 28, 36);' : 'background: rgba(27, 42, 74, 0.07); color: rgb(91, 104, 133);'">
                                {{ $previousQuestions->count() }}
                            </span>
                        </button>
                    </nav>
                </div>

                {{-- Notes Tab --}}
                <div x-show="activeTab === 'notes'" x-transition>
                    <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 28px;">

                        @if ($notes->isEmpty())
                            <div style="text-align: center; padding: 48px 0;">
                                <svg style="margin: 0 auto; width: 40px; height: 40px; color: rgb(91, 104, 133);" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                <p style="font-size: 14px; color: rgb(91, 104, 133); margin-top: 12px;">No notes uploaded yet.</p>
                            </div>
                        @else
                            <ul style="margin-bottom: 28px; padding: 0; list-style: none;">
                                @foreach ($notes as $note)
                                    <li style="padding: 14px 0; {{ !$loop->last ? 'border-bottom: 1px solid rgba(27, 42, 74, 0.06);' : '' }} display: flex; align-items: center; justify-content: space-between; gap: 16px;">
                                        <div style="display: flex; align-items: center; gap: 12px; min-width: 0; flex: 1;">
                                            <div style="width: 38px; height: 38px; background: rgba(138, 28, 36, 0.06); color: rgb(138, 28, 36); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                </svg>
                                            </div>
                                            <div style="min-width: 0; flex: 1;">
                                                <div style="font-weight: 600; color: rgb(27, 42, 74); font-size: 14px; display: flex; align-items: center; gap: 8px;">
                                                    {{ $note->title }}
                                                    @if ($note->credit_price > 0)
                                                        <span style="flex-shrink: 0; display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; background: rgba(192, 138, 62, 0.1); color: #C08A3E;">
                                                            {{ $note->credit_price }} cr
                                                        </span>
                                                    @endif
                                                </div>
                                                <div style="font-size: 13px; color: rgb(91, 104, 133); margin-top: 2px;">
                                                    <a href="{{ route('profiles.show', $note->uploader) }}" style="color: rgb(91, 104, 133); text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='rgb(138, 28, 36)'" onmouseout="this.style.color='rgb(91, 104, 133)'">{{ $note->uploader->name }}</a> &middot; {{ $note->created_at->format('M d, Y') }}
                                                </div>
                                            </div>
                                        </div>
                                        @if ($note->credit_price > 0 && !$note->isUnlockedBy(auth()->user()))
                                            <form method="POST" action="{{ route('notes.unlock', $note) }}" style="flex-shrink: 0;">
                                                @csrf
                                                <button type="submit"
                                                        style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; font-size: 13px; font-weight: 600; color: #C08A3E; background: rgba(192, 138, 62, 0.08); border: 1px solid rgba(192, 138, 62, 0.2); border-radius: 8px; cursor: pointer; transition: background 0.15s;">
                                                    <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                                                    </svg>
                                                    Unlock for {{ $note->credit_price }} credits
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('notes.download', $note) }}"
                                               style="flex-shrink: 0; display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; font-size: 13px; font-weight: 600; color: rgb(138, 28, 36); background: rgba(138, 28, 36, 0.06); border-radius: 8px; text-decoration: none; transition: background 0.15s;"
                                               onmouseover="this.style.background='rgba(138, 28, 36, 0.12)'" onmouseout="this.style.background='rgba(138, 28, 36, 0.06)'">
                                                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                </svg>
                                                Download
                                            </a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        {{-- Upload Form --}}
                        <div style="border-top: 1px solid rgba(27, 42, 74, 0.06); padding-top: 24px;">
                            <h4 style="font-size: 14px; font-weight: 600; color: rgb(27, 42, 74); margin-bottom: 12px;">Upload a note</h4>
                            <form method="POST" action="{{ route('notes.store') }}" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 12px;">
                                @csrf
                                <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                                <input type="text" name="title" placeholder="Title" required
                                       style="width: 100%; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 14px; color: rgb(27, 42, 74); background: white; outline: none;"
                                       onfocus="this.style.borderColor='rgb(138, 28, 36)'" onblur="this.style.borderColor='rgba(27, 42, 74, 0.15)'">
                                <textarea name="description" placeholder="Description (optional)" rows="2"
                                          style="width: 100%; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 14px; color: rgb(27, 42, 74); background: white; outline: none; resize: none;"
                                          onfocus="this.style.borderColor='rgb(138, 28, 36)'" onblur="this.style.borderColor='rgba(27, 42, 74, 0.15)'"></textarea>
                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 600; color: rgb(91, 104, 133); margin-bottom: 4px;">Credit price (0 = free)</label>
                                    <input type="number" name="credit_price" value="0" min="0"
                                           style="width: 100%; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 14px; color: rgb(27, 42, 74); background: white; outline: none;"
                                           onfocus="this.style.borderColor='rgb(138, 28, 36)'" onblur="this.style.borderColor='rgba(27, 42, 74, 0.15)'">
                                </div>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <label style="flex-shrink: 0; display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; font-size: 13px; font-weight: 600; color: rgb(58, 71, 98); background: rgba(27, 42, 74, 0.04); border: 1px solid rgba(27, 42, 74, 0.12); border-radius: 8px; cursor: pointer; transition: background 0.15s;">
                                        <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                        </svg>
                                        Choose file
                                        <input type="file" name="file" required class="hidden" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip"
                                               x-data="{ name: '' }" @change="name = $event.target.files[0]?.name || ''"
                                               x-ref="noteFile" @input="$el.closest('[style*=flex]').querySelector('span:last-child').textContent = name || 'No file chosen'">
                                    </label>
                                    <span style="font-size: 13px; color: rgb(91, 104, 133);">No file chosen</span>
                                </div>
                                <p style="font-size: 12px; color: rgb(138, 150, 174);">PDF, DOC, DOCX, PPT, PPTX, ZIP (max 20MB)</p>
                                <button type="submit"
                                        style="align-self: flex-start; background: rgb(138, 28, 36); color: rgb(251, 248, 243); padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; border: none; cursor: pointer; transition: background 0.15s;"
                                        onmouseover="this.style.background='rgb(110, 20, 27)'" onmouseout="this.style.background='rgb(138, 28, 36)'">
                                    Upload note
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Previous Questions Tab --}}
                <div x-show="activeTab === 'pq'" x-transition>
                    <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 28px;">

                        @if ($previousQuestions->isEmpty())
                            <div style="text-align: center; padding: 48px 0;">
                                <svg style="margin: 0 auto; width: 40px; height: 40px; color: rgb(91, 104, 133);" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                <p style="font-size: 14px; color: rgb(91, 104, 133); margin-top: 12px;">No previous questions uploaded yet.</p>
                            </div>
                        @else
                            <ul style="margin-bottom: 28px; padding: 0; list-style: none;">
                                @foreach ($previousQuestions as $pq)
                                    <li style="padding: 14px 0; {{ !$loop->last ? 'border-bottom: 1px solid rgba(27, 42, 74, 0.06);' : '' }} display: flex; align-items: center; justify-content: space-between; gap: 16px;">
                                        <div style="display: flex; align-items: center; gap: 12px; min-width: 0; flex: 1;">
                                            <div style="width: 38px; height: 38px; background: rgba(192, 138, 62, 0.08); color: #C08A3E; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                                </svg>
                                            </div>
                                            <div style="min-width: 0;">
                                                <div style="font-weight: 600; color: rgb(27, 42, 74); font-size: 14px;">{{ $pq->year }}</div>
                                                <div style="font-size: 13px; color: rgb(91, 104, 133); margin-top: 2px;">
                                                    <a href="{{ route('profiles.show', $pq->uploader) }}" style="color: rgb(91, 104, 133); text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='rgb(138, 28, 36)'" onmouseout="this.style.color='rgb(91, 104, 133)'">{{ $pq->uploader->name }}</a> &middot; {{ $pq->created_at->format('M d, Y') }}
                                                </div>
                                            </div>
                                        </div>
                                        <a href="{{ route('previous-questions.download', $pq) }}"
                                           style="flex-shrink: 0; display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; font-size: 13px; font-weight: 600; color: rgb(138, 28, 36); background: rgba(138, 28, 36, 0.06); border-radius: 8px; text-decoration: none; transition: background 0.15s;"
                                           onmouseover="this.style.background='rgba(138, 28, 36, 0.12)'" onmouseout="this.style.background='rgba(138, 28, 36, 0.06)'">
                                            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                            </svg>
                                            Download
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        {{-- Upload Form --}}
                        <div style="border-top: 1px solid rgba(27, 42, 74, 0.06); padding-top: 24px;">
                            <h4 style="font-size: 14px; font-weight: 600; color: rgb(27, 42, 74); margin-bottom: 12px;">Upload a previous question</h4>
                            <form method="POST" action="{{ route('previous-questions.store') }}" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 12px;">
                                @csrf
                                <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                                <input type="text" name="year" placeholder="Year (e.g. 2024)" required
                                       style="width: 100%; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 14px; color: rgb(27, 42, 74); background: white; outline: none;"
                                       onfocus="this.style.borderColor='rgb(138, 28, 36)'" onblur="this.style.borderColor='rgba(27, 42, 74, 0.15)'">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <label style="flex-shrink: 0; display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; font-size: 13px; font-weight: 600; color: rgb(58, 71, 98); background: rgba(27, 42, 74, 0.04); border: 1px solid rgba(27, 42, 74, 0.12); border-radius: 8px; cursor: pointer; transition: background 0.15s;">
                                        <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                        </svg>
                                        Choose file
                                        <input type="file" name="file" required class="hidden" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip">
                                    </label>
                                    <span style="font-size: 13px; color: rgb(91, 104, 133);">No file chosen</span>
                                </div>
                                <p style="font-size: 12px; color: rgb(138, 150, 174);">PDF, DOC, DOCX, PPT, PPTX, ZIP (max 20MB)</p>
                                <button type="submit"
                                        style="align-self: flex-start; background: rgb(138, 28, 36); color: rgb(251, 248, 243); padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; border: none; cursor: pointer; transition: background 0.15s;"
                                        onmouseover="this.style.background='rgb(110, 20, 27)'" onmouseout="this.style.background='rgb(138, 28, 36)'">
                                    Upload previous question
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
