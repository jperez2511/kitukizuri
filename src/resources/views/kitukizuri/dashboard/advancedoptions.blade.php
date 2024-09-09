<div class="col-6">
    <div class="card">
        <div class="card-body mt-3 mb-3 text-center">
            <a href="#" style="color:#7c8a96;" wire:click="showAdvancedOption()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90" style="width: 15%;">
                    <rect x="5" y="22" width="70" height="60" rx="7" ry="7" fill="#e3e7fe" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></rect>
                    <path d="M12,23H68a6,6,0,0,1,6,6v6a0,0,0,0,1,0,0H6a0,0,0,0,1,0,0V29A6,6,0,0,1,12,23Z" fill="#b3c2ff"></path>
                    <line x1="5" y1="35" x2="75" y2="35" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line>
                    <rect x="15" y="8" width="70" height="60" rx="7" ry="7" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></rect>
                    <path d="M22,9H78a6,6,0,0,1,6,6v6a0,0,0,0,1,0,0H16a0,0,0,0,1,0,0V15A6,6,0,0,1,22,9Z" fill="#e3e7fe"></path>
                    <line x1="15" y1="22" x2="85" y2="22" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line>
                    <line x1="61" y1="15" x2="68" y2="15" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line>
                    <line x1="74" y1="15" x2="78" y2="15" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line>
                    <polyline points="60.49 51.07 67.06 44.5 60.49 37.93" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"></polyline>
                    <polyline points="41.51 37.93 34.94 44.5 41.51 51.07" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"></polyline>
                    <line x1="54.55" y1="34.5" x2="47.45" y2="54.5" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"></line>
                </svg><br>
                <strong>{{ __('Advanced Options') }}</strong>
            </a>
        </div>
    </div>

    <x-dialog-modal wire:model.live="displayingAdvancedOptions">
        <x-slot name="title">
            {{ __('Advanced Options') }}
        </x-slot>
    
        <x-slot name="content">
            <div>
                <x-banner style="warning" message="{{ __('Caution: The following options may cause permanent changes within the application that cannot be undone. Please proceed carefully.') }}" />
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90" style="width: 15%;">
                    <ellipse cx="45" cy="25" rx="30" ry="15" fill="#e3e7fe" stroke="#6576ff" stroke-width="2"></ellipse>
                    <rect x="15" y="25" width="60" height="40" rx="15" ry="15" fill="#fff" stroke="#6576ff" stroke-width="2"></rect>
                    <line x1="15" y1="40" x2="75" y2="40" stroke="#6576ff" stroke-width="2"></line>
                    <ellipse cx="45" cy="65" rx="30" ry="15" fill="#e3e7fe" stroke="#6576ff" stroke-width="2"></ellipse>
                </svg>                
            </div>
        </x-slot>
    
        <x-slot name="footer">
            
        </x-slot>
    </x-dialog-modal>

</div>
