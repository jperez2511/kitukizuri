@extends($layout)

@section('content')
    @php
        $selectedDirection = old('direction', $personalizacion->direction ?? 'ltr');
        $selectedUiStyle = old('ui_style', $personalizacion->ui_style ?? 'default');
        $selectedSidebarStyle = old('sidebar_style', $personalizacion->sidebar_style ?? 'auto');
        $selectedSkinMode = old('skin_mode', $personalizacion->skin_mode ?? 'light');
        $selectedPrimarySkin = old('primary_skin', $personalizacion->primary_skin ?? 'custom');
    @endphp

    <div class="col-12">
        <div class="alert alert-info">
            Configura layout y colores del tema, guardados directamente en base de datos.
        </div>
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
        <span class="badge" style="background-color: {{ $personalizacion->primary_color }};">Primario</span>
        <span class="badge" style="background-color: {{ $personalizacion->secondary_color }};">Secundario</span>
        <span class="badge text-dark" style="background-color: {{ $personalizacion->accent_color }};">Acento</span>
        <span class="badge text-dark border" style="background-color: {{ $personalizacion->surface_color }};">Superficie</span>
    </div>

    <div class="col-12">
        <form method="POST" action="{{ route('personalizacion.store') }}">
            @csrf
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group mb-3">
                        <label for="direction">Direction</label>
                        <select class="form-control" id="direction" name="direction">
                            @foreach ($layoutOptions['direction'] as $value => $label)
                                <option value="{{ $value }}" {{ $selectedDirection === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group mb-3">
                        <label for="ui_style">Main UI Style</label>
                        <select class="form-control" id="ui_style" name="ui_style">
                            @foreach ($layoutOptions['ui_style'] as $value => $label)
                                <option value="{{ $value }}" {{ $selectedUiStyle === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group mb-3">
                        <label for="sidebar_style">Sidebar Style</label>
                        <select class="form-control" id="sidebar_style" name="sidebar_style">
                            @foreach ($layoutOptions['sidebar_style'] as $value => $label)
                                <option value="{{ $value }}" {{ $selectedSidebarStyle === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group mb-3">
                        <label for="skin_mode">Skin Mode</label>
                        <select class="form-control" id="skin_mode" name="skin_mode">
                            @foreach ($layoutOptions['skin_mode'] as $value => $label)
                                <option value="{{ $value }}" {{ $selectedSkinMode === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group mb-3">
                        <label for="primary_skin">Primary Skin</label>
                        <select class="form-control" id="primary_skin" name="primary_skin">
                            @foreach ($skinPresetLabels as $value => $label)
                                <option value="{{ $value }}" {{ $selectedPrimarySkin === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group mb-3">
                        <label for="primary_color">Color primario</label>
                        <input type="color" class="form-control" id="primary_color" name="primary_color" value="{{ old('primary_color', $personalizacion->primary_color) }}">
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group mb-3">
                        <label for="secondary_color">Color secundario</label>
                        <input type="color" class="form-control" id="secondary_color" name="secondary_color" value="{{ old('secondary_color', $personalizacion->secondary_color) }}">
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group mb-3">
                        <label for="accent_color">Color de acento</label>
                        <input type="color" class="form-control" id="accent_color" name="accent_color" value="{{ old('accent_color', $personalizacion->accent_color) }}">
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group mb-3">
                        <label for="surface_color">Color de superficie</label>
                        <input type="color" class="form-control" id="surface_color" name="surface_color" value="{{ old('surface_color', $personalizacion->surface_color) }}">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>
    </div>
@endsection
