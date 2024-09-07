<x-app-layout>
    <x-slot name="header">
        <h3 class="title">
            {{ __('Profile') }}
        </h3>
    </x-slot>
    <div class="nk-block">
        <div class="card card-bordered">
            <div class="card-aside-wrap">
                <div class="card-inner card-inner-lg">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabPersonalInformation" role="tabpanel">
                            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                                @livewire('profile.update-profile-information-form')
                            @endif
                            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                                <div class="mt-5">
                                    @livewire('profile.delete-user-form')
                                </div>
                            @endif
                        </div>
                        <div class="tab-pane" id="tabSecurity" role="tabpanel">
                            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                                @livewire('profile.update-password-form')
                            @endif
                            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                                <div class="mt-5">
                                    @livewire('profile.two-factor-authentication-form')
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane" id="tabActivity" role="tabpanel">
                            @livewire('profile.logout-other-browser-sessions-form')
                        </div>

                    </div>
                </div>
                <div class="card-aside card-aside-left user-aside toggle-slide toggle-slide-left toggle-break-lg toggle-screen-lg" data-toggle-body="true" data-content="userAside" data-toggle-screen="lg" data-toggle-overlay="true">
                    <div class="card-inner-group" data-simplebar="init">
                        <div class="simplebar-wrapper" style="margin: 0px;">
                            <div class="simplebar-height-auto-observer-wrapper">
                                <div class="simplebar-height-auto-observer"></div>
                            </div>
                            <div class="simplebar-mask">
                                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                    <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: auto; overflow: hidden;">
                                        <div class="simplebar-content" style="padding: 0px;">
                                            <div class="card-inner">
                                                <div class="user-card">
                                                    <div class="user-avatar bg-primary"><em class="icon ni ni-user-alt"></em></div>
                                                    <div class="user-info">
                                                        <span class="lead-text">{{ Auth::user()->name }}</span>
                                                        <span class="sub-text">{{ Auth::user()->email }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-inner p-0">
                                                <ul class="link-list-menu" role="tablist">
                                                    <li>
                                                        <a class="active" data-bs-toggle="tab" href="#tabPersonalInformation" aria-selected="false" role="tab" tabindex="-1">
                                                            <em class="icon ni ni-user-fill-c"></em><span>{{ __('Personal Infomation') }}</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a data-bs-toggle="tab"  href="#tabSecurity" aria-selected="false" role="tab" tabindex="-1">
                                                            <em class="icon ni ni-lock-alt-fill"></em>
                                                            <span>{{ __('Security Settings') }}</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a data-bs-toggle="tab" href="#tabActivity" aria-selected="false" role="tab" tabindex="-1">
                                                            <em class="icon ni ni-activity-round-fill"></em>
                                                            <span>{{ __('Account Activity') }}</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="simplebar-placeholder" style="width: auto; height: 504px;"></div>
                        </div>
                        <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                            <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                        </div>
                        <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
                            <div class="simplebar-scrollbar" style="height: 0px; display: none;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>
