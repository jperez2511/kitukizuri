<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="nk-block-des">
            <div class="mb-2">
                {{ __('Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success mt-3">
                    {{ __('A new verification link has been sent to the email address you provided in your profile settings.') }}
                </div>
            @endif
        </div>

        <div class="form-group d-flex align-items-center justify-content-between flex-wrap gap-2">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <x-button class="btn btn-primary" type="submit">
                    {{ __('Resend Verification Email') }}
                </x-button>
            </form>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('profile.show') }}" class="link link-primary link-sm">{{ __('Edit Profile') }}</a>

                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf

                    <button type="submit" class="btn btn-danger">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </x-authentication-card>
</x-guest-layout>
