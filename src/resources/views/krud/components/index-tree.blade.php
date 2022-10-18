@props([
    'id'          => ''
    'classColumn' => '',
    'campos'      => [],
    'classTitle' => ''
])

@push('css')
    <link rel="stylesheet" href="{{asset('kitukizuri/libs/jstree/themes/default/style.min.css')}}">
@endpush

@php
    if(empty($classTitle)) {
        $classTitle = "header-title mb-3 text-center";
    }
@endphp


<div class="{{$classColumn}}">
    <div class="card">
        <div class="card-body" style="overflow: auto;">
            <h4 class="{{$classTitle}}"></h4>
            <div class="form-group">
                <input type="text" id="search-{{$id}}" class="form-control form-sm" placeholder="{{__('search')}}...">
            </div>
        </div>
    </div>
    <div id="{{$id}}"></div>
</div>

@push('js')
    <script src="{{asset('kitukizuri/libs/jstree/jstree.min.js')}}"></script>
    <script>
        $('#{{$id}}').jstree({
            "plugins" : ["search", "changed"]
        });
    </script>
@endpush


