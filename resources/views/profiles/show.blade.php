<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->name }}'s Profile
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Profile Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <div class="flex items-start gap-6">
                    @if ($user->photo ?? null)
                        <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover border-2 border-gray-200">
                    @else
                        <div class="w-24 h-24 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-3xl border-2 border-gray-200 shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <h3 class="text-xl font-bold text-gray-900">{{ $user->name }}</h3>
                        @if ($user->roll ?? null)
                            <p class="text-sm text-gray-500 mt-1">Roll: {{ $user->roll }}</p>
                        @endif
                        @if ($user->current_semester ?? null)
                            <p class="text-sm text-gray-500">{{ $user->current_semester->name ?? 'Semester ' . $user->current_semester }}</p>
                        @endif
                        <div class="flex items-center gap-4 mt-3">
                            <span class="text-sm text-gray-500">
                                <span class="font-semibold text-gray-800">{{ $user->notes()->count() }}</span> notes uploaded
                            </span>
                            <span class="text-sm text-gray-500">
                                No ratings yet
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Uploaded Notes --}}
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Uploaded Notes</h3>

                @if ($notes->isEmpty())
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <h3 class="mt-3 text-sm font-semibold text-gray-900">No notes yet</h3>
                        <p class="mt-1 text-sm text-gray-500">This user hasn't uploaded any notes yet.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($notes as $note)
                            <a href="{{ route('subjects.show', $note->subject) }}"
                               class="group block bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 hover:shadow-md hover:border-indigo-300 border border-transparent transition-all duration-200">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-9 h-9 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center shrink-0 group-hover:bg-indigo-100 transition">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-medium text-gray-800 truncate group-hover:text-indigo-600 transition">{{ $note->title }}</div>
                                        <div class="text-xs text-gray-500">{{ $note->subject->code }}</div>
                                    </div>
                                </div>
                                @if ($note->description)
                                    <p class="text-sm text-gray-500 line-clamp-2 mb-2">{{ $note->description }}</p>
                                @endif
                                <div class="text-xs text-gray-400">{{ $note->created_at->format('M d, Y') }}</div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
