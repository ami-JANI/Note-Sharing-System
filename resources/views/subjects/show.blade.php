<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $subject->code }} - {{ $subject->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
                <a href="{{ route('semesters.show', $subject->semester) }}" class="hover:text-indigo-600 transition">{{ $subject->semester->name }}</a>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
                <span class="text-gray-900 font-medium">{{ $subject->code }}</span>
            </nav>

            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm mb-6">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Tabs --}}
            <div x-data="{ activeTab: 'notes' }">
                <div class="border-b border-gray-200 mb-6">
                    <nav class="flex gap-6">
                        <button @click="activeTab = 'notes'"
                                :class="activeTab === 'notes' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                class="pb-3 text-sm font-medium border-b-2 transition">
                            Notes
                            <span class="ml-1 text-xs px-1.5 py-0.5 rounded-full"
                                  :class="activeTab === 'notes' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-500'">
                                {{ $notes->count() }}
                            </span>
                        </button>
                        <button @click="activeTab = 'pq'"
                                :class="activeTab === 'pq' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                class="pb-3 text-sm font-medium border-b-2 transition">
                            Previous Questions
                            <span class="ml-1 text-xs px-1.5 py-0.5 rounded-full"
                                  :class="activeTab === 'pq' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-500'">
                                {{ $previousQuestions->count() }}
                            </span>
                        </button>
                    </nav>
                </div>

                {{-- Notes Tab --}}
                <div x-show="activeTab === 'notes'" x-transition>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                        @if ($notes->isEmpty())
                            <div class="text-center py-8">
                                <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No notes uploaded yet.</p>
                            </div>
                        @else
                            <ul class="divide-y divide-gray-100 mb-6">
                                @foreach ($notes as $note)
                                    <li class="py-3 flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-9 h-9 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center shrink-0">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="font-medium text-gray-800 truncate flex items-center gap-2">
                                                    {{ $note->title }}
                                                    @if ($note->credit_price > 0)
                                                        <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">
                                                            {{ $note->credit_price }} cr
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    <a href="{{ route('profiles.show', $note->uploader) }}" class="hover:text-indigo-600 transition">{{ $note->uploader->name }}</a> &middot; {{ $note->created_at->format('M d, Y') }}
                                                </div>
                                            </div>
                                        </div>
                                        @if ($note->credit_price > 0 && !($note->is_unlocked ?? false))
                                            <form method="POST" action="{{ route('credits.unlock', $note) }}" class="shrink-0">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-amber-700 bg-amber-50 rounded-lg hover:bg-amber-100 transition border border-amber-200">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                                                    </svg>
                                                    Unlock for {{ $note->credit_price }} credits
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('notes.download', $note) }}"
                                               class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
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
                        <div class="border-t border-gray-100 pt-5">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Upload a note</h4>
                            <form method="POST" action="{{ route('notes.store') }}" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                                <input type="text" name="title" placeholder="Title" required
                                       class="border-gray-300 rounded-md shadow-sm w-full text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <textarea name="description" placeholder="Description (optional)" rows="2"
                                          class="border-gray-300 rounded-md shadow-sm w-full text-sm focus:ring-indigo-500 focus:border-indigo-500 resize-none"></textarea>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Credit price (0 = free)</label>
                                    <input type="number" name="credit_price" value="0" min="0"
                                           class="border-gray-300 rounded-md shadow-sm w-full text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div class="flex items-center gap-3">
                                    <label class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                        </svg>
                                        Choose file
                                        <input type="file" name="file" required class="hidden" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip"
                                               x-data="{ name: '' }" @change="name = $event.target.files[0]?.name || ''"
                                               x-ref="noteFile" @input="$el.closest('.flex').querySelector('span').textContent = name || 'No file chosen'">
                                    </label>
                                    <span class="text-xs text-gray-400">No file chosen</span>
                                </div>
                                <p class="text-xs text-gray-400">PDF, DOC, DOCX, PPT, PPTX, ZIP (max 20MB)</p>
                                <button type="submit"
                                        class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 transition">
                                    Upload note
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Previous Questions Tab --}}
                <div x-show="activeTab === 'pq'" x-transition>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                        @if ($previousQuestions->isEmpty())
                            <div class="text-center py-8">
                                <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No previous questions uploaded yet.</p>
                            </div>
                        @else
                            <ul class="divide-y divide-gray-100 mb-6">
                                @foreach ($previousQuestions as $pq)
                                    <li class="py-3 flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-9 h-9 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center shrink-0">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="font-medium text-gray-800">{{ $pq->year }}</div>
                                                <div class="text-xs text-gray-500">
                                                    <a href="{{ route('profiles.show', $pq->uploader) }}" class="hover:text-indigo-600 transition">{{ $pq->uploader->name }}</a> &middot; {{ $pq->created_at->format('M d, Y') }}
                                                </div>
                                            </div>
                                        </div>
                                        <a href="{{ route('previous-questions.download', $pq) }}"
                                           class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                            </svg>
                                            Download
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        {{-- Upload Form --}}
                        <div class="border-t border-gray-100 pt-5">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Upload a previous question</h4>
                            <form method="POST" action="{{ route('previous-questions.store') }}" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                                <input type="text" name="year" placeholder="Year (e.g. 2024)" required
                                       class="border-gray-300 rounded-md shadow-sm w-full text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <div class="flex items-center gap-3">
                                    <label class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                        </svg>
                                        Choose file
                                        <input type="file" name="file" required class="hidden" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip">
                                    </label>
                                    <span class="text-xs text-gray-400">No file chosen</span>
                                </div>
                                <p class="text-xs text-gray-400">PDF, DOC, DOCX, PPT, PPTX, ZIP (max 20MB)</p>
                                <button type="submit"
                                        class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 transition">
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
