<div class="nk-block-head-content">
    <h5 class="card-title">{{ $title }}</h5>
    <div class="nk-block-des"><p>{{ $description }}</p></div>
</div> 

@if (!empty($aside))
    <div class="nk-block-des mt-2">
        {{ $aside }}
    </div>
@endif
