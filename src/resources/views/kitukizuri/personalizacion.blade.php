<x-app-layout>
    <x-slot name="header">
        <h3 class="title">
            {{ __($titulo) }}
        </h3>
    </x-slot>

    @php
        $selectedDirection = old('direction', $personalizacion->direction ?? 'ltr');
        $selectedUiStyle = old('ui_style', $personalizacion->ui_style ?? 'default');
        $selectedSidebarStyle = old('sidebar_style', $personalizacion->sidebar_style ?? 'auto');
        $selectedSkinMode = old('skin_mode', $personalizacion->skin_mode ?? 'light');
        $selectedPrimarySkin = old('primary_skin', $personalizacion->primary_skin ?? 'custom');
    @endphp

    <div class="components-preview mx-auto">
        <div class="card card-bordered card-preview">
            <div class="card-inner">
                <div class="row">
                    <div class="col-12">
                        <p class="mb-3 text-muted">
                            Configura la apariencia del layout como en el panel de DashLite, pero fija desde base de datos.
                        </p>
                    </div>

                    @if (session('status'))
                        <div class="col-12">
                            <div class="alert alert-success">{{ session('status') }}</div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="col-12 mb-3">
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge" style="background-color: {{ $personalizacion->primary_color }};">Primario</span>
                            <span class="badge" style="background-color: {{ $personalizacion->secondary_color }};">Secundario</span>
                            <span class="badge text-dark" style="background-color: {{ $personalizacion->accent_color }};">Acento</span>
                            <span class="badge text-dark border" style="background-color: {{ $personalizacion->surface_color }};">Superficie</span>
                        </div>
                    </div>

                    <div class="col-12">
                        <form method="POST" action="{{ route('personalizacion.store') }}">
                            @csrf

                            <div class="row g-3">
                                <div class="col-12">
                                    <h6 class="title mb-2 text-uppercase text-muted">Direction Change</h6>
                                    <div class="row g-2">
                                        @foreach ($layoutOptions['direction'] as $value => $label)
                                            <div class="col-6">
                                                <input
                                                    type="radio"
                                                    class="btn-check"
                                                    name="direction"
                                                    id="direction_{{ $value }}"
                                                    value="{{ $value }}"
                                                    {{ $selectedDirection === $value ? 'checked' : '' }}
                                                >
                                                <label class="btn btn-outline-primary w-100" for="direction_{{ $value }}">{{ $label }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-12">
                                    <h6 class="title mb-2 text-uppercase text-muted">Main UI Style</h6>
                                    <div class="row g-2">
                                        @foreach ($layoutOptions['ui_style'] as $value => $label)
                                            <div class="col-6">
                                                <input
                                                    type="radio"
                                                    class="btn-check"
                                                    name="ui_style"
                                                    id="ui_style_{{ $value }}"
                                                    value="{{ $value }}"
                                                    {{ $selectedUiStyle === $value ? 'checked' : '' }}
                                                >
                                                <label class="btn btn-outline-primary w-100" for="ui_style_{{ $value }}">{{ $label }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-12">
                                    <h6 class="title mb-2 text-uppercase text-muted">Sidebar Style</h6>
                                    <div class="row g-2">
                                        @foreach ($layoutOptions['sidebar_style'] as $value => $label)
                                            <div class="col-6 col-md-3">
                                                <input
                                                    type="radio"
                                                    class="btn-check"
                                                    name="sidebar_style"
                                                    id="sidebar_style_{{ $value }}"
                                                    value="{{ $value }}"
                                                    {{ $selectedSidebarStyle === $value ? 'checked' : '' }}
                                                >
                                                <label class="btn btn-outline-primary w-100" for="sidebar_style_{{ $value }}">{{ $label }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-12">
                                    <h6 class="title mb-2 text-uppercase text-muted">Primary Skin</h6>
                                    <div class="row g-2">
                                        @foreach ($skinPresetLabels as $value => $label)
                                            @php
                                                $presetColors = $skinPresetColors[$value] ?? null;
                                            @endphp
                                            <div class="col-6 col-md-4">
                                                <input
                                                    type="radio"
                                                    class="btn-check"
                                                    name="primary_skin"
                                                    id="primary_skin_{{ $value }}"
                                                    value="{{ $value }}"
                                                    {{ $selectedPrimarySkin === $value ? 'checked' : '' }}
                                                >
                                                <label class="btn btn-outline-primary w-100 text-start" for="primary_skin_{{ $value }}">
                                                    <span class="d-block fw-semibold mb-1">{{ $label }}</span>
                                                    @if ($presetColors)
                                                        <span class="d-flex gap-1">
                                                            <span class="kz-swatch" style="background: {{ $presetColors['primary_color'] }};"></span>
                                                            <span class="kz-swatch" style="background: {{ $presetColors['secondary_color'] }};"></span>
                                                            <span class="kz-swatch" style="background: {{ $presetColors['accent_color'] }};"></span>
                                                        </span>
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-12">
                                    <h6 class="title mb-2 text-uppercase text-muted">Skin Mode</h6>
                                    <div class="row g-2">
                                        @foreach ($layoutOptions['skin_mode'] as $value => $label)
                                            <div class="col-6">
                                                <input
                                                    type="radio"
                                                    class="btn-check"
                                                    name="skin_mode"
                                                    id="skin_mode_{{ $value }}"
                                                    value="{{ $value }}"
                                                    {{ $selectedSkinMode === $value ? 'checked' : '' }}
                                                >
                                                <label class="btn btn-outline-primary w-100" for="skin_mode_{{ $value }}">{{ $label }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-12 pt-2">
                                    <h6 class="title mb-2 text-uppercase text-muted">Colores Base</h6>
                                </div>

                                <div class="col-12 col-md-6 col-lg-3">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="primary_color">Color primario</label>
                                        <input type="color" class="form-control form-control-color" id="primary_color" name="primary_color" value="{{ old('primary_color', $personalizacion->primary_color) }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-3">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="secondary_color">Color secundario</label>
                                        <input type="color" class="form-control form-control-color" id="secondary_color" name="secondary_color" value="{{ old('secondary_color', $personalizacion->secondary_color) }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-3">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="accent_color">Color de acento</label>
                                        <input type="color" class="form-control form-control-color" id="accent_color" name="accent_color" value="{{ old('accent_color', $personalizacion->accent_color) }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-3">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="surface_color">Color de superficie</label>
                                        <input type="color" class="form-control form-control-color" id="surface_color" name="surface_color" value="{{ old('surface_color', $personalizacion->surface_color) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    Guardar cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .kz-swatch {
            display: inline-block;
            width: 28px;
            height: 14px;
            border-radius: 4px;
            border: 1px solid rgba(0, 0, 0, 0.08);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const presets = @json($skinPresetColors);
            const skinInputs = document.querySelectorAll('input[name="primary_skin"]');
            const colorInputs = {
                primary_color: document.getElementById('primary_color'),
                secondary_color: document.getElementById('secondary_color'),
                accent_color: document.getElementById('accent_color'),
                surface_color: document.getElementById('surface_color')
            };

            function applyPresetColors(key) {
                if (!presets[key]) {
                    return;
                }

                Object.keys(colorInputs).forEach(function (field) {
                    if (colorInputs[field] && presets[key][field]) {
                        colorInputs[field].value = presets[key][field];
                    }
                });
            }

            skinInputs.forEach(function (input) {
                input.addEventListener('change', function () {
                    if (this.value !== 'custom') {
                        applyPresetColors(this.value);
                    }
                });
            });
        });
    </script>
</x-app-layout>
