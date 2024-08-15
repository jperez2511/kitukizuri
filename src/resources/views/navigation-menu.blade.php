<div x-data="{ open: false }" class="nk-header-wrap">
    <div class="nk-menu-trigger d-xl-none ms-n1">
        <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
    </div>

    <div class="nk-header-tools">
        <ul class="nk-quick-nav">
            <li class="dropdown user-dropdown">
                <a href="#" class="dropdown-toggle me-n1" data-bs-toggle="dropdown">
                    <div class="user-toggle">
                        <div class="user-avatar sm">
                            <em class="icon ni ni-user-alt"></em>
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-md dropdown-menu-end">
                    
                    <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                        <div class="user-card">
                            <div class="user-avatar">
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                    <div class="shrink-0 me-3">
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                    </div>
                                @endif
                            </div>
                            <div class="user-info">
                                <span class="lead-text">{{ Auth::user()->name }}</span>
                                <span class="sub-text">{{ Auth::user()->email }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-inner">
                        <ul class="link-list">
                            <li>
                                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                                    <em class="icon ni ni-user-alt"></em><span>{{ __('Profile') }}</span>
                                </x-responsive-nav-link>
                            </li>
                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <li>
                                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                                        <em class="icon ni ni-setting-alt"></em><span>{{ __('API Tokens') }}</span>
                                    </x-responsive-nav-link>
                                </li>
                            @endif
                            <li><a class="dark-switch" href="#"><em class="icon ni ni-moon"></em><span>Dark Mode</span></a></li>
                        </ul>
                    </div>
                    <div class="dropdown-inner">
                        <ul class="link-list">
                            <li>
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                        <em class="icon ni ni-signout"></em><span>{{ __('Log Out') }}</span>
                                    </x-responsive-nav-link>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
