<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center gap-6">
                    @if ($user->photo)
                        <img src="{{ Storage::url($user->photo) }}" alt="{{ $user->name }}"
                             class="w-20 h-20 rounded-full object-cover">
                    @else
                        <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-2xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                        @if ($user->roll)
                            <p class="text-sm text-gray-500">Roll: {{ $user->roll }}</p>
                        @endif
                        @if ($user->currentSemester)
                            <p class="text-sm text-gray-500">{{ $user->currentSemester->name }}</p>
                        @endif
                        @if ($user->department)
                            <p class="text-sm text-gray-500">{{ $user->department }}</p>
                        @endif
                        <p class="text-sm text-gray-500">Rating: {{ number_format($averageRating, 1) }}/5</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Uploaded Notes</h3>
                <ul class="divide-y divide-gray-200">
                    @forelse ($notes as $note)
                        <li class="py-3 flex items-center justify-between">
                            <div>
                                <div class="font-medium text-gray-800">{{ $note->title }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $note->subject->code }} - {{ $note->subject->name }}
                                </div>
                            </div>
                            <a href="{{ route('notes.download', $note) }}"
                               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Download</a>
                        </li>
                    @empty
                        <li class="py-3 text-gray-500 text-sm">No notes uploaded yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
