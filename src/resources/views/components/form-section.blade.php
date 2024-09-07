@props(['submit'])

<div {{ $attributes->merge(['class' => 'card card-bordered']) }}>
    <div class="card-inner">
        <div class="card-head">
            <x-section-title>
                <x-slot name="title">{{ $title }}</x-slot>
                <x-slot name="description">{{ $description }}</x-slot>
            </x-section-title>
        </div>
        <form wire:submit="{{ $submit }}" class="">
            
            {{ $form }}
            
            @if (isset($actions))
                <div class="form-group text-right">
                    {{ $actions }}
                </div>
            @endif
        </form>
    </div>
</div>
