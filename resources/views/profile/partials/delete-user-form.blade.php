<section>
    <header>
        <h2 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 20px; color: rgb(27, 42, 74);">
            {{ __('Delete Account') }}
        </h2>

        <p style="font-size: 14px; color: rgb(91, 104, 133); margin-top: 4px;">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <div class="mt-6">
        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                style="padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; border: 1px solid rgba(180, 30, 30, 0.3); background: rgba(180, 30, 30, 0.06); color: rgb(180, 30, 30); cursor: pointer; transition: background 0.15s;"
                onmouseover="this.style.background='rgba(180, 30, 30, 0.12)'" onmouseout="this.style.background='rgba(180, 30, 30, 0.06)'">
            {{ __('Delete Account') }}
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" style="padding: 24px;">
            @csrf
            @method('delete')

            <h2 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 20px; color: rgb(27, 42, 74);">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p style="font-size: 14px; color: rgb(91, 104, 133); margin-top: 8px;">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div style="margin-top: 24px;">
                <label for="password" class="sr-only" value="{{ __('Password') }}" />

                <input id="password" name="password" type="password" placeholder="{{ __('Password') }}"
                       style="width: 100%; max-width: 320px; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 14px; color: rgb(27, 42, 74); background: white; outline: none; transition: border-color 0.15s;"
                       onfocus="this.style.borderColor='rgb(138, 28, 36)'" onblur="this.style.borderColor='rgba(27, 42, 74, 0.15)'">

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div style="margin-top: 24px; display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" x-on:click="$dispatch('close')"
                        style="padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; border: 1px solid rgba(27, 42, 74, 0.15); background: white; color: rgb(58, 71, 98); cursor: pointer; transition: background 0.15s;"
                        onmouseover="this.style.background='rgba(27, 42, 74, 0.04)'" onmouseout="this.style.background='white'">
                    {{ __('Cancel') }}
                </button>

                <button type="submit"
                        style="padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; border: 1px solid rgba(180, 30, 30, 0.3); background: rgba(180, 30, 30, 0.06); color: rgb(180, 30, 30); cursor: pointer; transition: background 0.15s;"
                        onmouseover="this.style.background='rgba(180, 30, 30, 0.12)'" onmouseout="this.style.background='rgba(180, 30, 30, 0.06)'">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
