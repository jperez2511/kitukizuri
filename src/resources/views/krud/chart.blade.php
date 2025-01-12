<x-app-layout>
    <x-slot name="header">
        <h3 class="nk-block-title page-title">
            {{ __($titulo) }}
        </h3>
    </x-slot>

    <div class="components-preview wide-xl mx-auto">
        <div class="card card-bordered card-preview">
            <div class="card-inner">    
                @foreach($campos as $c)
                    @if ($c['edit'] === true)
                        @if($c['tipo'] != 'password' )
                            @php
                                if (!empty($c['dependencies'])) {
                                    $mergeDependencies[] = $c['dependencies'];        
                                }
                            @endphp

                            <x-dynamic-component 
                                :component="$c['component']" 
                                columnClass="{{$c['columnClass']}} {{$c['editClass']}}" 
                                inputClass="{{$c['inputClass']}}"
                                label="{{$c['nombre']}}"
                                name="{!!$c['inputName']!!}"
                                id="{{ $c['inputId'] ?? $c['inputName']}}"
                                collection="{!! $c['collect'] !!}"
                                type="{{$c['htmlType']}}"
                                attr="{!! $c['htmlAttr'] !!}"
                                value="{{$c['value']}}"
                                dependencies="{!! json_encode($c['dependencies']) !!}"
                            />

                        @else
                            <x-dynamic-component 
                                :component="$c['component']" 
                                nombre="{{$c['inputName']}}"
                                label="{{$c['nombre']}}"
                            />
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
    </div>

</x-app-layout>