<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->name }}'s Profile
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-start gap-6">
                    @if ($user->photo)
                        <img src="{{ Storage::url($user->photo) }}" alt="{{ $user->name }}"
                             class="w-24 h-24 rounded-full object-cover border-2 border-gray-200">
                    @else
                        <div class="w-24 h-24 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-3xl border-2 border-gray-200 shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <h3 class="text-xl font-bold text-gray-900">{{ $user->name }}</h3>
                        @if ($user->roll)
                            <p class="text-sm text-gray-500 mt-1">Roll: {{ $user->roll }}</p>
                        @endif
                        @if ($user->currentSemester)
                            <p class="text-sm text-gray-500">{{ $user->currentSemester->name }}</p>
                        @endif
                        @if ($user->department)
                            <p class="text-sm text-gray-500">{{ $user->department }}</p>
                        @endif
                        <div class="flex items-center gap-4 mt-3">
                            <span class="text-sm text-gray-500">
                                <span class="font-semibold text-gray-800">{{ $notes->count() }}</span> notes uploaded
                            </span>
                            <span class="text-sm text-gray-500">
                                Rating: {{ number_format($averageRating, 1) }}/5
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Uploaded Notes</h3>

                @if ($notes->isEmpty())
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12 text-center">
                        <h3 class="text-sm font-semibold text-gray-900">No notes yet</h3>
                        <p class="mt-1 text-sm text-gray-500">This user hasn't uploaded any notes yet.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($notes as $note)
                            <a href="{{ route('subjects.show', $note->subject) }}"
                               class="group block bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 hover:shadow-md hover:border-indigo-300 border border-transparent transition-all duration-200">
                                <div class="font-medium text-gray-800 truncate group-hover:text-indigo-600 transition">{{ $note->title }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ $note->subject->code }}</div>
                                @if ($note->description)
                                    <p class="text-sm text-gray-500 line-clamp-2 mt-2">{{ $note->description }}</p>
                                @endif
                                <div class="text-xs text-gray-400 mt-2">{{ $note->created_at->format('M d, Y') }}</div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
