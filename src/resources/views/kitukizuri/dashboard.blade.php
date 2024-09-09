<x-app-layout>
    <div class="nk-block-head nk-block-head-lg mx-auto">
        <div class="nk-block-head-content text-center">
            <h2 class="nk-block-title fw-normal">{{__('Control Panel')}}</h2>
            <div class="nk-block-des">
                <p>{{__('Welcome to the Control Panel! You have the following options available within your application.')}}</p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body mt-3 mb-3 text-center">
                <a href="{{ route('usuarios.index') }}" style="color:#7c8a96;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90" style="width: 15%;">
                        <rect x="5" y="7" width="60" height="56" rx="7" ry="7" fill="#e3e7fe" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <rect x="25" y="27" width="60" height="56" rx="7" ry="7" fill="#e3e7fe" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <rect x="15" y="17" width="60" height="56" rx="7" ry="7" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <line x1="15" y1="29" x2="75" y2="29" fill="none" stroke="#6576ff" stroke-miterlimit="10" stroke-width="2" />
                        <circle cx="53" cy="23" r="2" fill="#c4cefe" />
                        <circle cx="60" cy="23" r="2" fill="#c4cefe" />
                        <circle cx="67" cy="23" r="2" fill="#c4cefe" />
                        <rect x="22" y="39" width="20" height="20" rx="2" ry="2" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <circle cx="32" cy="45.81" r="2" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <path d="M29,54.31a3,3,0,0,1,6,0" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <line x1="49" y1="40" x2="69" y2="40" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <line x1="49" y1="51" x2="69" y2="51" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <line x1="49" y1="57" x2="59" y2="57" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <line x1="64" y1="57" x2="66" y2="57" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <line x1="49" y1="46" x2="59" y2="46" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <line x1="64" y1="46" x2="66" y2="46" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                    </svg><br>
                    <strong>{{__('Users')}}</strong>
                </a>
            </div>
        </div>
    </div>
    
    
<div class="col-6">
    <div class="card">
        <div class="card-body mt-3 mb-3 text-center">
            <a href="{{ route('empresas.index') }}" style="color:#7c8a96;" >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90" style="width: 15%;">
                    <rect x="5" y="5" width="53.97" height="69.95" rx="7" ry="7" fill="#e3e7fe" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <path d="M51.66,15H22.4A7.22,7.22,0,0,0,15,22V78a7.21,7.21,0,0,0,7.41,7H61.56A7.2,7.2,0,0,0,69,78V30.5Z" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <polyline points="68.96 30.98 51.97 30.91 51.97 15.99" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <line x1="23" y1="34" x2="44" y2="34" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <line x1="23" y1="42" x2="57" y2="42" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <line x1="23" y1="50" x2="57" y2="50" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <line x1="23" y1="58" x2="32" y2="58" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <ellipse cx="61.1" cy="61.11" rx="23.9" ry="23.89" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <polygon points="69.56 74.43 47.7 52.84 52.46 48.15 74.32 69.74 69.56 74.43" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <line x1="54.98" y1="51.16" x2="54.22" y2="51.91" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <line x1="57.62" y1="53.77" x2="55.59" y2="55.78" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <line x1="71.22" y1="67.2" x2="70.46" y2="67.95" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <line x1="68.87" y1="64.88" x2="66.84" y2="66.89" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <path d="M69.55,48.21l5,4.89L55.42,72H51V67.6Z" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <line x1="65.67" y1="52.24" x2="70.35" y2="56.86" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                </svg><br>
                <strong>{{__('Companies')}}</strong>
            </a>
        </div>
    </div>
</div>

<div class="col-6">
    <div class="card">
        <div class="card-body mt-3 mb-3 text-center">
            <a href="{{ route('roles.index') }}" style="color:#7c8a96;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90" style="width: 15%;">
                    <rect x="3" y="12.5" width="64" height="63.37" rx="7" ry="7" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></rect>
                    <path d="M10,13.49H60a6,6,0,0,1,6,6v3.9a0,0,0,0,1,0,0H4a0,0,0,0,1,0,0v-3.9A6,6,0,0,1,10,13.49Z" fill="#e3e7fe"></path>
                    <rect x="3" y="23.39" width="64" height="1.98" fill="#6576ff"></rect>
                    <path d="M65.37,31.31H76.81A12.24,12.24,0,0,0,87,42S88.12,66.31,65.37,77.5C42.62,66.31,43.75,42,43.75,42A12.23,12.23,0,0,0,53.93,31.31Z" fill="#fff" stroke="#6576ff" stroke-miterlimit="10" stroke-width="2"></path>
                    <path d="M66,72.62c19-9.05,18.1-28.71,18.1-28.71s-7.47-.94-8.52-8.64H66Z" fill="#e3e7fe"></path>
                    <polygon points="56 46.16 55 46.16 55 42.2 59 42.2 59 43.2 56 43.2 56 46.16" fill="#010863"></polygon>
                    <polygon points="59 65.97 55 65.97 55 62.01 56 62.01 56 64.98 59 64.98 59 65.97" fill="#010863"></polygon>
                    <polygon points="78 65.97 74 65.97 74 64.98 77 64.98 77 62.01 78 62.01 78 65.97" fill="#010863"></polygon>
                    <polygon points="78 46.16 77 46.16 77 43.2 74 43.2 74 42.2 78 42.2 78 46.16" fill="#010863"></polygon>
                    <path d="M70,51.12H62V48.86a3.74,3.74,0,0,1,3.17-3.57c2.56-.46,4.83,1.28,4.83,3.49Zm-7-1h6V48.56a2.78,2.78,0,0,0-2-2.63,3,3,0,0,0-4,2.64Z" fill="#6576ff"></path>
                    <path d="M58,57.28V50.13H74V57.5c0,4.62-4.65,8.26-9.86,7.17A7.63,7.63,0,0,1,58,57.28Z" fill="#e5effe"></path>
                    <path d="M59,51.12v6.7A7,7,0,0,0,73,58V51.12Z" fill="#6576ff"></path>
                    <ellipse cx="66" cy="55.08" rx="2" ry="1.98" fill="#fff"></ellipse>
                    <polygon points="68.91 62.01 63.84 62.01 65.18 56.07 67.57 56.07 68.91 62.01" fill="#fff"></polygon>
                    <path d="M72,51.12H60V48.66a5.41,5.41,0,0,1,4.06-5.14c4.13-1.14,7.94,1.54,7.94,5Zm-11-1H71V48.49A4.69,4.69,0,0,0,67.08,44c-3.23-.6-6.08,1.58-6.08,4.33Z" fill="#6576ff"></path>
                    <rect x="13" y="32.3" width="22" height="5.94" rx="1" ry="1" fill="none" stroke="#6576ff" stroke-miterlimit="10" stroke-width="2"></rect>
                    <rect x="12" y="45.17" width="22" height="5.94" rx="1" ry="1" fill="none" stroke="#6576ff" stroke-miterlimit="10" stroke-width="2"></rect>
                    <rect x="12" y="57.06" width="12" height="5.94" rx="1" ry="1" fill="none" stroke="#6576ff" stroke-miterlimit="10" stroke-width="2"></rect>
                </svg><br>
                <strong>{{ __('User Roles') }}</strong>
            </a>
        </div>
    </div>
</div>

<div class="col-6">
    <div class="card">
        <div class="card-body mt-3 mb-3 text-center">
            <a href="#" style="color:#7c8a96;">
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
</div>

<x-dialog-modal wire:model.live="displayingAdvancedOptions">
    <x-slot name="title">
        {{ __('AdvancedOptions') }}
    </x-slot>

    <x-slot name="content">
        <div>
            {{ __('Please copy your new API token. For your security, it won\'t be shown again.') }}
        </div>

        <x-input x-ref="plaintextToken" type="text" readonly :value="$plainTextToken"
            class="mt-4 bg-gray-100 px-4 py-2 rounded font-mono text-sm text-gray-500 w-full break-all"
            autofocus autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
            @showing-token-modal.window="setTimeout(() => $refs.plaintextToken.select(), 250)"
        />
    </x-slot>

    <x-slot name="footer">
        <x-secondary-button class="btn-danger" wire:click="$set('displayingAdvancedOptions', false)" wire:loading.attr="disabled">
            {{ __('Close') }}
        </x-secondary-button>
    </x-slot>
</x-dialog-modal>


<div class="modal fade" id="avanzadosModal" tabindex="-1" aria-labelledby="avanzadosModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="avanzadosModalLabel">{{ __('Opciones avanzadas') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group text-center">
                            <a href="{{ route('database.index') }}" class="btn btn-primary btn-icon-split btn-sm btn-block">
                                <span class="icon">
                                    <i class="fa-light fa-database"></i>
                                </span>
                                <span class="text">{{ __('Base de datos') }}</span>
                            </a>
                        </div>
                    </div> 
                    <div class="col-md-6">
                        <a href="{{ route('logs.index') }}" class="btn btn-tertiary btn-icon-split btn-sm btn-block">
                            <span class="icon">
                                <i class="fa-light fa-receipt"></i>
                            </span>
                            <span class="text">Logs</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>