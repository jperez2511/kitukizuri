<x-app-layout>
    <x-slot name="header">
        <h3 class="nk-block-title page-title">
            {{ __($titulo) }}
        </h3>
    </x-slot>

    <div class="components-preview wide-xl mx-auto">
        <div class="card card-bordered card-preview">
            <div class="card-inner">
                <div class="row">
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
    </div>
    
    <div id="highcharts-container" style="width: 100%; height: 600px;"></div>

    @push('scripts')
        <script>
            // Asegúrate de que Highcharts está disponible (desde bootstrap.js)
            document.addEventListener('DOMContentLoaded', () => {
                const chart = Highcharts.chart('highcharts-container', {
                    chart: {
                        type: 'line',
                    },
                    title: {
                        text: 'Ejemplo de Highcharts en JS Puro',
                    },
                    xAxis: {
                        categories: [],
                    },
                    yAxis: {
                        title: {
                            text: 'Valores',
                        },
                    },
                    series: [],
                });

                function updateChartData() {
                    
                    let data = [];

                    @foreach($campos as $c)
                        @if($c['tipo'] != 'password' && !in_array($c['tipo'], ['h1', 'h2', 'h3', 'h4', 'strong', 'bool']))
                            @if(!empty($c['dependencies']))
                                @foreach($c['dependencies'] as $dependency)
                                    @if(!in_array($dependency['input'], $initialValues))
                                        const {{ $dependency['input'] }}_initial = $('#{{ $dependency['input'] }}-element').is(':checkbox') 
                                            ? $('#{{ $dependency['input'] }}-element').is(':checked') 
                                            : $('#{{ $dependency['input'] }}-element').val();
                                        @php $initialValues[] = $dependency['input'] @endphp
                                    @endif

                                    if ({{ $dependency['input'] }}_initial == '{{ $dependency['value'] }}') {
                                        data['{{$c['inputName']}}'] = $('#{{$c['inputId'] ?? $c['inputName']}}-element').val();
                                    }
                                @endforeach
                            @else
                                data['{{$c['inputName']}}'] = $('#{{$c['inputId'] ?? $c['inputName']}}-element').val();
                            @endif
                        @elseif($c['tipo'] == 'bool' && empty($c['dependencies']))
                            data['{{$c['inputName']}}'] = $('#{{$c['inputId'] ?? $c['inputName']}}-element').is(':checked') ? 1 : 0;
                        @endif
                    @endforeach
                    
                    fetch(`{{$ruta}}?startDate=${startDate}&endDate=${endDate}`)
                        .then(response => response.json())
                        .then(data => {
                            chart.update({
                                xAxis: {
                                    categories: data.categories,
                                },
                                series: data.series,
                            });
                        });
                }
            });

        </script>
    @endpush
</x-app-layout>