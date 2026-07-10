<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Buy Credits
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="max-w-lg mx-auto">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-amber-50 text-amber-600 mb-3">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Your balance: {{ number_format(Auth::user()->credits ?? 0) }} credits</h3>
                        <p class="text-sm text-gray-500 mt-1">Purchase credits to unlock premium notes</p>
                    </div>

                    <form method="POST" action="{{ route('credits.purchase') }}">
                        @csrf
                        <div class="space-y-4">
                            {{-- Preset amounts --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select amount</label>
                                <div class="grid grid-cols-3 gap-3">
                                    @foreach ([50, 100, 250] as $amount)
                                        <label class="relative cursor-pointer">
                                            <input type="radio" name="amount" value="{{ $amount }}" class="peer sr-only"
                                                   x-data x-model="$refs.amountInput.value = ''" @click="$refs.amountInput.value = '{{ $amount }}'">
                                            <div class="border-2 border-gray-200 rounded-lg p-3 text-center peer-checked:border-indigo-600 peer-checked:bg-indigo-50 hover:border-gray-300 transition">
                                                <div class="text-lg font-bold text-gray-900">{{ $amount }}</div>
                                                <div class="text-xs text-gray-500">credits</div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('amount')" />
                            </div>

                            {{-- Custom amount --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Or enter custom amount</label>
                                <input type="number" name="amount" x-ref="amountInput" min="1"
                                       placeholder="Enter amount" value="{{ old('amount') }}"
                                       class="border-gray-300 rounded-md shadow-sm w-full text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <button type="submit"
                                    class="w-full bg-indigo-600 text-white px-4 py-2.5 rounded-md text-sm font-medium hover:bg-indigo-700 transition">
                                Confirm Purchase
                            </button>
                        </div>
                    </form>
                </div>

                <p class="text-xs text-gray-400 text-center">Credits are simulated for demonstration purposes.</p>
            </div>
        </div>
    </div>
</x-app-layout>
