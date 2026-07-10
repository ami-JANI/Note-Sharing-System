<section>
    <header>
        <h2 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 20px; color: rgb(27, 42, 74);">
            {{ __('Update Password') }}
        </h2>

        <p style="font-size: 14px; color: rgb(91, 104, 133); margin-top: 4px;">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6" style="display: flex; flex-direction: column; gap: 24px;">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" style="display: block; font-size: 14px; font-weight: 500; color: rgb(27, 42, 74); margin-bottom: 6px;">{{ __('Current Password') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                   style="width: 100%; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 14px; color: rgb(27, 42, 74); background: white; outline: none; transition: border-color 0.15s;"
                   onfocus="this.style.borderColor='rgb(138, 28, 36)'" onblur="this.style.borderColor='rgba(27, 42, 74, 0.15)'">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password" style="display: block; font-size: 14px; font-weight: 500; color: rgb(27, 42, 74); margin-bottom: 6px;">{{ __('New Password') }}</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                   style="width: 100%; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 14px; color: rgb(27, 42, 74); background: white; outline: none; transition: border-color 0.15s;"
                   onfocus="this.style.borderColor='rgb(138, 28, 36)'" onblur="this.style.borderColor='rgba(27, 42, 74, 0.15)'">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password_confirmation" style="display: block; font-size: 14px; font-weight: 500; color: rgb(27, 42, 74); margin-bottom: 6px;">{{ __('Confirm Password') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                   style="width: 100%; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 14px; color: rgb(27, 42, 74); background: white; outline: none; transition: border-color 0.15s;"
                   onfocus="this.style.borderColor='rgb(138, 28, 36)'" onblur="this.style.borderColor='rgba(27, 42, 74, 0.15)'">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div style="display: flex; align-items: center; gap: 16px; margin-top: 8px;">
            <button type="submit" style="background: rgb(138, 28, 36); color: rgb(251, 248, 243); padding: 10px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; border: none; cursor: pointer; transition: background 0.15s;"
                    onmouseover="this.style.background='rgb(110, 20, 27)'" onmouseout="this.style.background='rgb(138, 28, 36)'">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   style="font-size: 14px; color: rgb(91, 104, 133);">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
