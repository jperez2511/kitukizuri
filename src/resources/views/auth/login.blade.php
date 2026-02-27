<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 nk-block-des">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <div class="form-label-group">
                    <x-label class="form-label-group" for="email" value="{{ __('Email') }}" />
                </div>
                <div class="form-control-wrap">
                    <x-input id="email" class="form-control form-control-lg" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                </div>
            </div>

            <div class="form-group">
                <x-label for="password" value="{{ __('Password') }}" />
                <div class="form-control-wrap">
                    <x-input id="password" class="form-control form-control-lg" type="password" name="password" required autocomplete="current-password" />
                </div>
            </div>

            <div class="form-group">
                <div class="form-label-group">
                    <div class="form-check">
                        <x-checkbox id="remember_me" name="remember" />
                        <label for="remember_me" class="form-check-label">{{ __('Remember me') }}</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a class="link link-primary link-sm" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <x-button class="btn btn-lg btn-primary w-100">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
