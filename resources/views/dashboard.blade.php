<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Browse by semester</h3>
                <p class="text-sm text-gray-500 mt-1">Select a semester to view its subjects and materials</p>
            </div>

            @if ($semesters->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                    <h3 class="mt-3 text-sm font-semibold text-gray-900">No semesters</h3>
                    <p class="mt-1 text-sm text-gray-500">No semesters have been added yet.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($semesters as $semester)
                        <a href="{{ route('semesters.show', $semester) }}"
                           class="group block bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 hover:shadow-md hover:border-indigo-300 border border-transparent transition-all duration-200">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center font-bold text-sm group-hover:bg-indigo-100 transition">
                                    {{ $semester->order + 1 }}
                                </div>
                                <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">
                                    {{ $semester->subjects()->count() }} {{ Str::plural('subject', $semester->subjects()->count()) }}
                                </span>
                            </div>
                            <h4 class="font-semibold text-gray-900 group-hover:text-indigo-600 transition">{{ $semester->name }}</h4>
                            <div class="flex items-center text-xs text-gray-400 mt-2">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                                View subjects
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
