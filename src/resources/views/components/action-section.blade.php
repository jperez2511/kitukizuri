<div {{ $attributes->merge(['class' => 'card card-bordered']) }}>
    <div class="card-inner">
        <div class="card-head">
            <x-section-title>
                <x-slot name="title">{{ $title }}</x-slot>
                <x-slot name="description">{{ $description }}</x-slot>
            </x-section-title>
        </div>
        
        <div class="row mt-3">
            {{ $content }}
        </div>
    </div>
</div>
