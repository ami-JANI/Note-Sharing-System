<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 28px; color: rgb(27, 42, 74); letter-spacing: -0.02em;">
                Buy Credits
            </h2>
            <p style="font-size: 15px; color: rgb(91, 104, 133); margin-top: 4px;">Purchase credits to unlock premium notes</p>
        </div>
    </x-slot>

    <div style="padding: 48px 0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="max-width: 500px; margin: 0 auto;">

            <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 32px; margin-bottom: 24px;">
                <div style="text-align: center; margin-bottom: 28px;">
                    <div style="display: inline-flex; align-items: center; justify-content: center; width: 56px; height: 56px; border-radius: 50%; background: rgba(192, 138, 62, 0.08); margin-bottom: 12px;">
                        <svg style="width: 28px; height: 28px; color: #C08A3E;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                        </svg>
                    </div>
                    <h3 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 22px; color: rgb(27, 42, 74);">{{ number_format(Auth::user()->credits ?? 0) }} <span style="font-size: 14px; font-weight: 500; color: rgb(91, 104, 133);">credits</span></h3>
                    <p style="font-size: 14px; color: rgb(91, 104, 133); margin-top: 4px;">Your current balance</p>
                </div>

                <form method="POST" action="{{ route('credits.purchase') }}">
                    @csrf
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 500; color: rgb(27, 42, 74); margin-bottom: 8px;">Select amount</label>
                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                                @foreach ([50, 100, 250] as $amount)
                                    <label style="cursor: pointer;">
                                        <input type="radio" name="amount" value="{{ $amount }}" class="peer sr-only"
                                               x-data x-model="$refs.amountInput.value = ''" @click="$refs.amountInput.value = '{{ $amount }}'">
                                        <div style="border: 2px solid rgba(27, 42, 74, 0.1); border-radius: 10px; padding: 14px 8px; text-align: center; transition: all 0.15s;"
                                             class="peer-checked:!border-[rgb(138,28,36)] peer-checked:!bg-[rgba(138,28,36,0.04)]">
                                            <div style="font-family: 'Source Serif 4', serif; font-size: 22px; font-weight: 700; color: rgb(27, 42, 74);">{{ $amount }}</div>
                                            <div style="font-size: 12px; color: rgb(91, 104, 133);">credits</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('amount')" />
                        </div>

                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 500; color: rgb(27, 42, 74); margin-bottom: 6px;">Or enter custom amount</label>
                            <input type="number" name="amount" x-ref="amountInput" min="1"
                                   placeholder="Enter amount" value="{{ old('amount') }}"
                                   style="width: 100%; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 14px; color: rgb(27, 42, 74); background: white; outline: none; transition: border-color 0.15s;"
                                   onfocus="this.style.borderColor='rgb(138, 28, 36)'" onblur="this.style.borderColor='rgba(27, 42, 74, 0.15)'">
                        </div>

                        <button type="submit"
                                style="width: 100%; background: rgb(138, 28, 36); color: rgb(251, 248, 243); padding: 12px 24px; border-radius: 8px; font-size: 15px; font-weight: 600; border: none; cursor: pointer; transition: background 0.15s;"
                                onmouseover="this.style.background='rgb(110, 20, 27)'" onmouseout="this.style.background='rgb(138, 28, 36)'">
                            Confirm Purchase
                        </button>
                    </div>
                </form>
            </div>

            <p style="font-size: 12px; color: rgb(138, 150, 174); text-align: center;">Credits are simulated for demonstration purposes.</p>
        </div>
    </div>
</x-app-layout>
