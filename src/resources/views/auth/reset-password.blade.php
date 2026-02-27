<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="form-group">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="form-control-lg mt-1" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            </div>

            <div class="form-group">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="form-control-lg mt-1" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="form-group">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="form-control-lg mt-1" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="form-group text-end">
                <x-button class="btn btn-lg btn-primary">
                    {{ __('Reset Password') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
