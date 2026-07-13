<section>
    <header>
        <h2 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 20px; color: rgb(27, 42, 74);">
            {{ __('Profile Information') }}
        </h2>

        <p style="font-size: 14px; color: rgb(91, 104, 133); margin-top: 4px;">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6" style="display: flex; flex-direction: column; gap: 24px;" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <label for="name" style="display: block; font-size: 14px; font-weight: 500; color: rgb(27, 42, 74); margin-bottom: 6px;">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" :value="old('name', $user->name)" required autofocus autocomplete="name"
                   style="width: 100%; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 14px; color: rgb(27, 42, 74); background: white; outline: none; transition: border-color 0.15s;"
                   onfocus="this.style.borderColor='rgb(138, 28, 36)'" onblur="this.style.borderColor='rgba(27, 42, 74, 0.15)'">
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" style="display: block; font-size: 14px; font-weight: 500; color: rgb(27, 42, 74); margin-bottom: 6px;">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" :value="old('email', $user->email)" readonly
                   style="width: 100%; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 14px; color: rgb(138, 150, 174); background: rgba(27, 42, 74, 0.03); cursor: not-allowed;">
            <p style="font-size: 12px; color: rgb(138, 150, 174); margin-top: 4px;">Email cannot be changed.</p>
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p style="font-size: 14px; margin-top: 8px; color: rgb(27, 42, 74);">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" style="text-decoration: underline; font-size: 14px; color: rgb(138, 28, 36); background: none; border: none; cursor: pointer;">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p style="margin-top: 8px; font-weight: 500; font-size: 14px; color: rgb(46, 125, 79);">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <label style="display: block; font-size: 14px; font-weight: 500; color: rgb(27, 42, 74); margin-bottom: 6px;">{{ __('Profile Photo') }}</label>
            <div style="display: flex; align-items: center; gap: 16px; margin-top: 4px;">
                @if ($user->photo ?? null)
                    <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}" style="width: 64px; height: 64px; border-radius: 50%; object-fit: cover; border: 1px solid rgba(27, 42, 74, 0.1);">
                @else
                    <div style="width: 64px; height: 64px; border-radius: 50%; background: rgba(138, 28, 36, 0.09); color: rgb(138, 28, 36); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 24px; font-family: 'Source Serif 4', serif; border: 1px solid rgba(27, 42, 74, 0.1);">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <input type="file" name="photo" id="photo" accept="image/*"
                           style="font-size: 13px; color: rgb(91, 104, 133);">
                    <p style="font-size: 12px; color: rgb(138, 150, 174); margin-top: 4px;">JPG, PNG. Max 2MB.</p>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('photo')" />
        </div>

        <div>
            <label for="roll" style="display: block; font-size: 14px; font-weight: 500; color: rgb(27, 42, 74); margin-bottom: 6px;">{{ __('Roll Number') }}</label>
            <input id="roll" name="roll" type="text" :value="old('roll', $user->roll)" readonly
                   style="width: 100%; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 14px; color: rgb(138, 150, 174); background: rgba(27, 42, 74, 0.03); cursor: not-allowed;">
            <p style="font-size: 12px; color: rgb(138, 150, 174); margin-top: 4px;">Roll number cannot be changed.</p>
            <x-input-error class="mt-2" :messages="$errors->get('roll')" />
        </div>

        <div>
            <label for="phone" style="display: block; font-size: 14px; font-weight: 500; color: rgb(27, 42, 74); margin-bottom: 6px;">{{ __('Phone Number') }}</label>
            <input id="phone" name="phone" type="tel" :value="old('phone', $user->phone)" readonly
                   style="width: 100%; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 14px; color: rgb(138, 150, 174); background: rgba(27, 42, 74, 0.03); cursor: not-allowed;">
            <p style="font-size: 12px; color: rgb(138, 150, 174); margin-top: 4px;">Phone number cannot be changed.</p>
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <label for="department" style="display: block; font-size: 14px; font-weight: 500; color: rgb(27, 42, 74); margin-bottom: 6px;">{{ __('Department') }}</label>
            <input id="department" name="department" type="text" :value="old('department', $user->department)" autocomplete="off" placeholder="e.g. Computer Science"
                   style="width: 100%; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 14px; color: rgb(27, 42, 74); background: white; outline: none; transition: border-color 0.15s;"
                   onfocus="this.style.borderColor='rgb(138, 28, 36)'" onblur="this.style.borderColor='rgba(27, 42, 74, 0.15)'">
            <x-input-error class="mt-2" :messages="$errors->get('department')" />
        </div>

        <div>
            <label for="batch" style="display: block; font-size: 14px; font-weight: 500; color: rgb(27, 42, 74); margin-bottom: 6px;">{{ __('Batch') }}</label>
            <input id="batch" name="batch" type="text" :value="old('batch', $user->batch)" autocomplete="off" placeholder="e.g. 2024"
                   style="width: 100%; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 14px; color: rgb(27, 42, 74); background: white; outline: none; transition: border-color 0.15s;"
                   onfocus="this.style.borderColor='rgb(138, 28, 36)'" onblur="this.style.borderColor='rgba(27, 42, 74, 0.15)'">
            <x-input-error class="mt-2" :messages="$errors->get('batch')" />
        </div>

        <div>
            <label for="current_semester_id" style="display: block; font-size: 14px; font-weight: 500; color: rgb(27, 42, 74); margin-bottom: 6px;">{{ __('Current Semester') }}</label>
            <select id="current_semester_id" name="current_semester_id"
                    style="width: 100%; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 14px; color: rgb(27, 42, 74); background: white; outline: none;">
                <option value="">Select semester</option>
                @foreach ($semesters as $semester)
                    <option value="{{ $semester->id }}" {{ old('current_semester_id', $user->current_semester_id) == $semester->id ? 'selected' : '' }}>
                        {{ $semester->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('current_semester_id')" />
        </div>

        <div style="display: flex; align-items: center; gap: 16px; margin-top: 8px;">
            <button type="submit" style="background: rgb(138, 28, 36); color: rgb(251, 248, 243); padding: 10px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; border: none; cursor: pointer; transition: background 0.15s;"
                    onmouseover="this.style.background='rgb(110, 20, 27)'" onmouseout="this.style.background='rgb(138, 28, 36)'">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   style="font-size: 14px; color: rgb(91, 104, 133);">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
