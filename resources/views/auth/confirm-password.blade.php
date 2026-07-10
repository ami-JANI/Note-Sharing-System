<x-guest-layout>
    <div class="mb-6" style="text-align: center;">
        <h2 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 26px; color: rgb(27, 42, 74); margin-bottom: 6px;">Confirm Password</h2>
        <p style="font-size: 14px; color: rgb(91, 104, 133);">{{ __('This is a secure area of the application. Please confirm your password before continuing.') }}</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div>
            <label for="password" style="display: block; font-size: 14px; font-weight: 500; color: rgb(27, 42, 74); margin-bottom: 6px;">{{ __('Password') }}</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   style="width: 100%; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 14px; color: rgb(27, 42, 74); background: white; outline: none; transition: border-color 0.15s;"
                   onfocus="this.style.borderColor='rgb(138, 28, 36)'" onblur="this.style.borderColor='rgba(27, 42, 74, 0.15)'">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-6">
            <button type="submit" style="width: 100%; background: rgb(138, 28, 36); color: rgb(251, 248, 243); padding: 12px 24px; border-radius: 8px; font-size: 15px; font-weight: 600; border: none; cursor: pointer; transition: background 0.15s;"
                    onmouseover="this.style.background='rgb(110, 20, 27)'" onmouseout="this.style.background='rgb(138, 28, 36)'">
                {{ __('Confirm') }}
            </button>
        </div>
    </form>
</x-guest-layout>
