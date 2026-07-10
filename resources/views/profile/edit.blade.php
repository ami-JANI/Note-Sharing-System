<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 28px; color: rgb(27, 42, 74); letter-spacing: -0.02em;">
                Profile
            </h2>
            <p style="font-size: 15px; color: rgb(91, 104, 133); margin-top: 4px;">Manage your account settings</p>
        </div>
    </x-slot>

    <div style="padding: 48px 0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="max-width: 720px; margin: 0 auto;">
            <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 32px; margin-bottom: 24px;">
                <div style="max-width: 560px;">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 32px; margin-bottom: 24px;">
                <div style="max-width: 560px;">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 32px;">
                <div style="max-width: 560px;">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
