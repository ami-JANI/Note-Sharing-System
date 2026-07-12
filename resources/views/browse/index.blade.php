<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Browse Notes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('browse.index') }}" class="flex gap-2">
                    <input type="text" name="q" placeholder="Search by title, course no, or course title..."
                           value="{{ request('q') }}"
                           class="border-gray-300 rounded-md shadow-sm w-full">
                    <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 shrink-0">
                        Search
                    </button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    {{ request('q') ? 'Search results' : 'Recent notes' }}
                    ({{ $notes->total() }})
                </h3>

                <ul class="divide-y divide-gray-200">
                    @forelse ($notes as $note)
                        <li class="py-3 flex items-center justify-between">
                            <div>
                                <div class="font-medium text-gray-800">{{ $note->title }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $note->course_no }} - {{ $note->course_title }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    by <a href="{{ route('profiles.show', $note->uploader) }}"
                                          class="text-indigo-600 hover:text-indigo-800">{{ $note->uploader->name }}</a>
                                    in {{ $note->subject->name }}
                                </div>
                            </div>
                            <a href="{{ route('notes.download', $note) }}"
                               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Download</a>
                        </li>
                    @empty
                        <li class="py-3 text-gray-500 text-sm">No notes found.</li>
                    @endforelse
                </ul>

                <div class="mt-4">
                    {{ $notes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
