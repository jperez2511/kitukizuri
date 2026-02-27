<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" class="form-control-lg mt-1" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="form-group">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="form-control-lg mt-1" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="form-group">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="form-control-lg mt-1" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="form-group">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="form-control-lg mt-1" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="form-group">
                    <x-label for="terms">
                        <div class="form-check">
                            <x-checkbox name="terms" id="terms" required />
                            <div class="form-check-label ms-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="link link-primary">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="link link-primary">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="form-group d-flex align-items-center justify-content-between">
                <a class="link link-primary" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="btn btn-lg btn-primary">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
