<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <div class="form-label-group">
                    <x-label class="form-label" for="name" value="{{ __('Name') }}" />
                </div>
                <div class="form-control-wrap">
                    <x-input id="name" class="form-control form-control-lg" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                </div>
            </div>

            <div class="form-group">
                <div class="form-label-group">
                    <x-label class="form-label" for="email" value="{{ __('Email') }}" />
                </div>
                <div class="form-control-wrap">
                    <x-input id="email" class="form-control form-control-lg" type="email" name="email" :value="old('email')" required autocomplete="username" />
                </div>
            </div>

            <div class="form-group">
                <div class="form-label-group">
                    <x-label class="form-label" for="password" value="{{ __('Password') }}" />
                </div>
                <div class="form-control-wrap">
                    <x-input id="password" class="form-control form-control-lg" type="password" name="password" required autocomplete="new-password" />
                </div>
            </div>

            <div class="form-group">
                <div class="form-label-group">
                    <x-label class="form-label" for="password_confirmation" value="{{ __('Confirm Password') }}" />
                </div>
                <div class="form-control-wrap">
                    <x-input id="password_confirmation" class="form-control form-control-lg" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="form-group">
                    <x-label class="form-label" for="terms">
                        <div class="custom-control custom-control-xs custom-checkbox">
                            <x-checkbox class="custom-control-input" name="terms" id="terms" required />

                            <x-label class="custom-control-label">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </x-label>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="form-group">
                <x-button class="btn btn-lg btn-primary btn-block">
                    {{ __('Register') }}
                </x-button>
            </div>

            <div class="form-note-s2 text-center pt-4">
                <a href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>    
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
