<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Credit History
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-4">
                    <span class="text-lg font-medium text-gray-900">Balance: {{ $userCredits }} credits</span>
                </div>

                <ul class="divide-y divide-gray-200">
                    @forelse ($transactions as $tx)
                        <li class="py-3 flex items-center justify-between">
                            <div>
                                <div class="font-medium text-gray-800">{{ $tx['description'] }}</div>
                                <div class="text-sm text-gray-500">{{ $tx['date']->format('M d, Y h:i A') }}</div>
                            </div>
                            <span class="font-medium {{ $tx['amount'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $tx['amount'] > 0 ? '+' : '' }}{{ $tx['amount'] }}
                            </span>
                        </li>
                    @empty
                        <li class="py-3 text-gray-500 text-sm">No transactions yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
