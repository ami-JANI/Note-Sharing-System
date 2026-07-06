<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $semester->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Subjects</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach ($subjects as $subject)
                        <a href="{{ route('subjects.show', $subject) }}"
                           class="block border rounded-lg p-4 hover:shadow-md transition">
                            <div class="font-semibold text-gray-800">{{ $subject->code }}</div>
                            <div class="text-sm text-gray-500">{{ $subject->name }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
