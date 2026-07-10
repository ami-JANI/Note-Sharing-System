<x-guest-layout>
    <div class="mb-6" style="text-align: center;">
        <h2 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 26px; color: rgb(27, 42, 74); margin-bottom: 6px;">Verify Email</h2>
        <p style="font-size: 14px; color: rgb(91, 104, 133);">{{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?') }}</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div style="background: rgba(46, 125, 79, 0.06); border: 1px solid rgba(46, 125, 79, 0.2); color: rgb(46, 125, 79); padding: 12px 16px; border-radius: 10px; font-size: 14px; margin-bottom: 20px; text-align: center;">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div style="display: flex; flex-direction: column; gap: 16px;">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" style="width: 100%; background: rgb(138, 28, 36); color: rgb(251, 248, 243); padding: 12px 24px; border-radius: 8px; font-size: 15px; font-weight: 600; border: none; cursor: pointer; transition: background 0.15s;"
                    onmouseover="this.style.background='rgb(110, 20, 27)'" onmouseout="this.style.background='rgb(138, 28, 36)'">
                {{ __('Resend Verification Email') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="width: 100%; background: none; border: 1px solid rgba(27, 42, 74, 0.15); color: rgb(91, 104, 133); padding: 12px 24px; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; transition: background 0.15s;"
                    onmouseover="this.style.background='rgba(27, 42, 74, 0.04)'" onmouseout="this.style.background='none'">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
