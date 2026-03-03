@php
    $navLayout = dashliteNavigationContext();
    $layout = $navLayout['layout'];
    $variant = $navLayout['variant'];
    $isDemo1 = $navLayout['isDemo1'];
    $isDemo2 = $navLayout['isDemo2'];
    $isDemo5 = $navLayout['isDemo5'];
    $isDemo6 = $navLayout['isDemo6'];
    $isDemo8 = $navLayout['isDemo8'];
    $isDemo9 = $navLayout['isDemo9'];
    $isAsideLayout = $navLayout['isAsideLayout'];
    $menuTarget = $navLayout['menuTarget'];
    $triggerClass = $navLayout['triggerClass'];
@endphp

<div x-data="{ open: false }" class="nk-header-wrap">
    @if (!$isAsideLayout && !$isDemo6 && !$isDemo8 && !$isDemo9)
        <div class="nk-menu-trigger {{ $triggerClass }}">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="{{ $menuTarget }}"><em class="icon ni ni-menu"></em></a>
        </div>
        @if ($isDemo1 || $isDemo5)
            <div class="nk-header-brand d-xl-none">
                <a href="{{ route('home.index') }}" class="logo-link">
                    <x-application-mark style="width:30px;" />
                </a>
            </div>
            <div class="nk-header-news d-none d-xl-block">
                <div class="nk-news-list">
                    <a class="nk-news-item" href="#">
                        <div class="nk-news-icon">
                            <em class="icon ni ni-card-view"></em>
                        </div>
                        <div class="nk-news-text">
                            <p>Do you know the latest update of 2022? <span>A overview of our is now available on YouTube</span></p>
                            <em class="icon ni ni-external"></em>
                        </div>
                    </a>
                </div>
            </div>
        @elseif ($isDemo2)
            <div class="nk-header-brand d-xl-none">
                <a href="{{ route('home.index') }}" class="logo-link">
                    <x-application-mark style="width:30px;" />
                </a>
            </div>
            <div class="nk-header-search ms-3 ms-xl-0">
                <em class="icon ni ni-search"></em>
                <input type="text" class="form-control border-transparent form-focus-none" placeholder="{{ __('Search anything') }}">
            </div>
        @endif
    @endif
    @if ($isDemo9)
        <div class="nk-menu-trigger d-xl-none ms-n1 me-3">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="{{ $menuTarget }}"><em class="icon ni ni-menu"></em></a>
        </div>
        <div class="nk-header-brand d-xl-none">
            <a href="{{ route('home.index') }}" class="logo-link">
                <x-application-logo class="logo-light logo-img" style="height:30px;" />
                <x-application-logo class="logo-dark logo-img" style="height:30px;" />
            </a>
        </div>
        <div class="nk-header-menu is-light">
            <div class="nk-header-menu-inner">
                <ul class="nk-menu nk-menu-main">
                    <li class="nk-menu-item has-sub{{ request()->routeIs('home.index') ? ' active current-page' : '' }}">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-text">{{ __('Dashboards') }}</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="{{ route('home.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-text">{{ __('Default Dashboard') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nk-menu-item">
                        <a href="#" class="nk-menu-link">
                            <span class="nk-menu-text">{{ __('Apps') }}</span>
                        </a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="#" class="nk-menu-link">
                            <span class="nk-menu-text">{{ __('Components') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    @endif
    @if ($isDemo6 || $isDemo8)
        <div class="nk-menu-trigger me-sm-2 d-lg-none">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="headerNav"><em class="icon ni ni-menu"></em></a>
        </div>
        <div class="nk-header-brand">
            <a href="{{ route('home.index') }}" class="logo-link">
                <x-application-mark style="width:34px;" />
            </a>
        </div>
        <div class="nk-header-menu{{ $isDemo8 ? ' ms-auto' : '' }}" data-content="headerNav">
            <div class="nk-header-mobile">
                <div class="nk-header-brand">
                    <a href="{{ route('home.index') }}" class="logo-link">
                        <x-application-mark style="width:34px;" />
                    </a>
                </div>
                <div class="nk-menu-trigger me-n2">
                    <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="headerNav"><em class="icon ni ni-arrow-left"></em></a>
                </div>
            </div>
            {!! session('menu') !!}
        </div>
    @endif
    @if ($isAsideLayout)
        <div class="nk-header-brand">
            <a href="{{ route('home.index') }}" class="logo-link">
                <x-application-mark style="width:30px;" />
            </a>
        </div>
        <div class="nk-header-menu">
            <ul class="nk-menu nk-menu-main">
                <li class="nk-menu-item">
                    <a href="{{ route('home.index') }}" class="nk-menu-link">
                        <span class="nk-menu-text">{{ __('Overview') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    @endif

    <div class="nk-header-tools">
        <ul class="nk-quick-nav">
            @if ($isDemo1 || $isDemo2 || $isDemo5 || $isDemo6 || $isDemo8 || $isDemo9)
                @if (!$isDemo6 && !$isDemo8 && !$isDemo9)
                    <li class="dropdown language-dropdown d-none d-sm-block me-n1">
                        <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-bs-toggle="dropdown">
                            <div class="quick-icon border border-light">
                                <em class="icon ni ni-globe"></em>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-s1">
                            <ul class="language-list">
                                <li><a href="#" class="language-item"><span class="language-name">English</span></a></li>
                                <li><a href="#" class="language-item"><span class="language-name">Español</span></a></li>
                            </ul>
                        </div>
                    </li>
                @endif
                @if ($isDemo2 || $isDemo8 || $isDemo9)
                    <li class="dropdown chats-dropdown {{ $isDemo9 ? 'hide-mb-sm' : 'hide-mb-xs' }}">
                        <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-bs-toggle="dropdown">
                            <div class="icon-status icon-status-na"><em class="icon ni {{ $isDemo8 ? 'ni-chat' : 'ni-comments' }}"></em></div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-s1">
                            <ul class="link-list">
                                <li><a href="#">{{ __('No new chats') }}</a></li>
                            </ul>
                        </div>
                    </li>
                @endif
                @if (!$isDemo5 && !$isDemo8)
                    <li class="dropdown notification-dropdown">
                        <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-bs-toggle="dropdown">
                            <div class="icon-status icon-status-info"><em class="icon ni ni-bell"></em></div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end dropdown-menu-s1">
                            <div class="dropdown-head">
                                <span class="sub-title nk-dropdown-title">Notifications</span>
                            </div>
                            <div class="dropdown-body">
                                <div class="nk-notification">
                                    <div class="nk-notification-item dropdown-inner">
                                        <div class="nk-notification-icon">
                                            <em class="icon icon-circle bg-success-dim ni ni-curve-down-left"></em>
                                        </div>
                                        <div class="nk-notification-content">
                                            <div class="nk-notification-text">Your <span>Deposit Order</span> is placed</div>
                                            <div class="nk-notification-time">2 hrs ago</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endif
                @if ($isDemo9)
                    <li class="dropdown language-dropdown d-none d-sm-block me-n1">
                        <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-bs-toggle="dropdown">
                            <div class="quick-icon border border-light">
                                <em class="icon ni ni-globe"></em>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-s1">
                            <ul class="language-list">
                                <li><a href="#" class="language-item"><span class="language-name">English</span></a></li>
                                <li><a href="#" class="language-item"><span class="language-name">Español</span></a></li>
                            </ul>
                        </div>
                    </li>
                @endif
                @if ($isDemo8)
                    <li class="dropdown language-dropdown d-none d-sm-block me-n1">
                        <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-bs-toggle="dropdown">
                            <div class="quick-icon border border-light">
                                <em class="icon ni ni-globe"></em>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-s1">
                            <ul class="language-list">
                                <li><a href="#" class="language-item"><span class="language-name">English</span></a></li>
                                <li><a href="#" class="language-item"><span class="language-name">Español</span></a></li>
                            </ul>
                        </div>
                    </li>
                @endif
            @endif
            <li class="dropdown user-dropdown{{ $isDemo6 ? ' order-sm-first' : '' }}">
                <a href="#" class="dropdown-toggle{{ ($isDemo1 || $isDemo5 || $isDemo6 || $isDemo8 || $isDemo9) ? '' : ' me-n1' }}" data-bs-toggle="dropdown">
                    <div class="user-toggle">
                        <div class="user-avatar sm">
                            <em class="icon ni ni-user-alt"></em>
                        </div>
                        @if ($isDemo1 || $isDemo5)
                            <div class="user-info d-none d-md-block">
                                <div class="user-status">{{ __('Administrator') }}</div>
                                <div class="user-name dropdown-indicator">{{ Auth::user()->name }}</div>
                            </div>
                        @elseif ($isDemo6)
                            <div class="user-info d-none d-xl-block">
                                <div class="user-status">{{ __('Administrator') }}</div>
                                <div class="user-name dropdown-indicator">{{ Auth::user()->name }}</div>
                            </div>
                        @elseif ($isDemo2)
                            <div class="user-info d-none d-xl-block">
                                <div class="user-status">{{ __('Unverified') }}</div>
                                <div class="user-name dropdown-indicator">{{ Auth::user()->name }}</div>
                            </div>
                        @endif
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
            @if ($isDemo6)
                <li class="dropdown language-dropdown d-none d-sm-flex me-n1">
                    <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-bs-toggle="dropdown">
                        <div class="quick-icon">
                            <em class="icon ni ni-globe"></em>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-s1">
                        <ul class="language-list">
                            <li><a href="#" class="language-item"><span class="language-name">English</span></a></li>
                            <li><a href="#" class="language-item"><span class="language-name">Español</span></a></li>
                        </ul>
                    </div>
                </li>
            @endif
            @if ($isDemo5)
                <li class="dropdown notification-dropdown me-n1">
                    <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-bs-toggle="dropdown">
                        <div class="icon-status icon-status-info"><em class="icon ni ni-bell"></em></div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end dropdown-menu-s1">
                        <div class="dropdown-head">
                            <span class="sub-title nk-dropdown-title">Notifications</span>
                        </div>
                        <div class="dropdown-body">
                            <div class="nk-notification">
                                <div class="nk-notification-item dropdown-inner">
                                    <div class="nk-notification-icon">
                                        <em class="icon icon-circle bg-success-dim ni ni-curve-down-left"></em>
                                    </div>
                                    <div class="nk-notification-content">
                                        <div class="nk-notification-text">Your <span>Deposit Order</span> is placed</div>
                                        <div class="nk-notification-time">2 hrs ago</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @endif
            @if ($isAsideLayout)
                <li class="d-lg-none">
                    <a href="#" class="toggle nk-quick-nav-icon me-n1" data-target="{{ $menuTarget }}"><em class="icon ni ni-menu"></em></a>
                </li>
            @endif
        </ul>
    </div>
</div>
