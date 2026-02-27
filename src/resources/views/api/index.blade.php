<x-app-layout>
    <x-slot name="header">
        <h3 class="title">
            {{ __('API Tokens') }}
        </h3>
    </x-slot>

    <div>
        <div class="mt-3">
            @livewire('api.api-token-manager')
        </div>
    </div>
</x-app-layout>
