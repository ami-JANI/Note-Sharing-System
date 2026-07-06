<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $subject->code }} - {{ $subject->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="bg-green-100 text-green-800 rounded-lg p-4">{{ session('status') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Notes</h3>

                <ul class="divide-y divide-gray-200 mb-6">
                    @forelse ($notes as $note)
                        <li class="py-3 flex items-center justify-between">
                            <div>
                                <div class="font-medium text-gray-800">{{ $note->title }}</div>
                                <div class="text-sm text-gray-500">
                                    by {{ $note->uploader->name }} on {{ $note->created_at->format('M d, Y') }}
                                </div>
                            </div>
                            <a href="{{ route('notes.download', $note) }}"
                               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Download</a>
                        </li>
                    @empty
                        <li class="py-3 text-gray-500 text-sm">No notes uploaded yet.</li>
                    @endforelse
                </ul>

                <form method="POST" action="{{ route('notes.store') }}" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                    <input type="text" name="title" placeholder="Title" required
                           class="border-gray-300 rounded-md shadow-sm w-full">
                    <textarea name="description" placeholder="Description (optional)"
                              class="border-gray-300 rounded-md shadow-sm w-full"></textarea>
                    <input type="file" name="file" required class="w-full">
                    <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        Upload note
                    </button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Previous Questions</h3>

                <ul class="divide-y divide-gray-200 mb-6">
                    @forelse ($previousQuestions as $pq)
                        <li class="py-3 flex items-center justify-between">
                            <div>
                                <div class="font-medium text-gray-800">{{ $pq->year }}</div>
                                <div class="text-sm text-gray-500">
                                    by {{ $pq->uploader->name }} on {{ $pq->created_at->format('M d, Y') }}
                                </div>
                            </div>
                            <a href="{{ route('previous-questions.download', $pq) }}"
                               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Download</a>
                        </li>
                    @empty
                        <li class="py-3 text-gray-500 text-sm">No previous questions uploaded yet.</li>
                    @endforelse
                </ul>

                <form method="POST" action="{{ route('previous-questions.store') }}" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                    <input type="text" name="year" placeholder="Year (e.g. 2024)" required
                           class="border-gray-300 rounded-md shadow-sm w-full">
                    <input type="file" name="file" required class="w-full">
                    <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        Upload previous question
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
