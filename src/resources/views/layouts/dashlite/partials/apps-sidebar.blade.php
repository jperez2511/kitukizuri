<div class="{{ $layout['appsSidebarClass'] }}">
    <div class="nk-apps-brand text-center">
        <a href="{{ route('home.index') }}">
            <x-application-mark style="width:50%; margin-top:10px;" />
        </a>
    </div>
    <div class="nk-sidebar-element">
        <div class="nk-sidebar-body">
            <div class="nk-sidebar-content" data-simplebar>
                <div class="nk-sidebar-menu">
                    <ul class="nk-menu apps-menu">
                        <li class="nk-menu-item">
                            <a href="html/cms/index.html" class="nk-menu-link" title="CMS Panel">
                                <span class="nk-menu-icon"><em class="icon ni ni-template"></em></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
