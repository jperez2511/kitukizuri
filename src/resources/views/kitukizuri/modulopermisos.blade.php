@php
    $availableModulePermissions = $modulos
        ->flatMap(fn ($module) => $module->modulopermiso)
        ->filter(fn ($modulePermission) => $modulePermission->permisos !== null)
        ->values();

    $permissionColumns = $availableModulePermissions
        ->unique('permisoid')
        ->sortBy(fn ($modulePermission) => mb_strtolower($modulePermission->permisos->nombre))
        ->values();

    $totalAvailablePermissions = $availableModulePermissions->count();
    $initialSelectedPermissions = $availableModulePermissions
        ->whereIn('modulopermisoid', $rmp)
        ->count();
@endphp

<x-app-layout>
    <x-slot name="header">
        <h3 class="title">{{ __($titulo) }}</h3>
    </x-slot>

    @push('styles')
        <style>
            .permission-manager {
                --permission-primary: var(--kz-primary, #6576ff);
                --permission-primary-soft: rgba(var(--bs-primary-rgb, 101, 118, 255), .1);
                --permission-text: #364a63;
                --permission-muted: #8094ae;
                --permission-border: #e5e9f2;
                --permission-surface: #fff;
                --permission-surface-soft: #f5f6fa;
            }
            .dark-mode .permission-manager {
                --permission-text: #d9e1ef;
                --permission-muted: #9ba7b7;
                --permission-border: #344357;
                --permission-surface: #1f2b3a;
                --permission-surface-soft: #182433;
            }
            .permission-manager__hero {
                align-items: center;
                display: flex;
                gap: 1rem;
                justify-content: space-between;
                padding: 1.5rem;
            }
            .permission-manager__eyebrow {
                color: var(--permission-primary);
                font-size: .72rem;
                font-weight: 700;
                letter-spacing: .08em;
                margin-bottom: .35rem;
                text-transform: uppercase;
            }
            .permission-manager__title {
                color: var(--permission-text);
                font-size: 1.15rem;
                margin: 0 0 .35rem;
            }
            .permission-manager__description {
                color: var(--permission-muted);
                margin: 0;
            }
            .permission-manager__total {
                align-items: center;
                background: var(--permission-primary-soft);
                border-radius: 999px;
                color: var(--permission-primary);
                display: inline-flex;
                flex: 0 0 auto;
                font-weight: 700;
                gap: .45rem;
                padding: .55rem .85rem;
            }
            .permission-toolbar {
                border-top: 1px solid var(--permission-border);
                display: grid;
                gap: 1rem;
                grid-template-columns: minmax(240px, 1.3fr) minmax(260px, 1fr);
                padding: 1.25rem 1.5rem;
            }
            .permission-search { position: relative; }
            .permission-search .icon {
                color: var(--permission-muted);
                left: 1rem;
                pointer-events: none;
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
            }
            .permission-search .form-control {
                background: var(--permission-surface-soft);
                border-color: transparent;
                border-radius: .65rem;
                height: 2.75rem;
                padding-left: 2.75rem;
            }
            .permission-search .form-control:focus {
                background: var(--permission-surface);
                border-color: var(--permission-primary);
                box-shadow: 0 0 0 .2rem var(--permission-primary-soft);
            }
            .permission-preset { display: flex; gap: .5rem; }
            .permission-preset .form-select { border-radius: .65rem; min-width: 0; }
            .permission-filter-bar {
                align-items: center;
                border-top: 1px solid var(--permission-border);
                display: flex;
                flex-wrap: wrap;
                gap: .55rem;
                padding: 1rem 1.5rem;
            }
            .permission-filter {
                background: transparent;
                border: 1px solid var(--permission-border);
                border-radius: 999px;
                color: var(--permission-muted);
                font-size: .78rem;
                font-weight: 600;
                padding: .4rem .75rem;
                transition: all .2s ease;
            }
            .permission-filter:hover,
            .permission-filter.is-active {
                background: var(--permission-primary-soft);
                border-color: transparent;
                color: var(--permission-primary);
            }
            .permission-filter__count {
                background: rgba(128, 148, 174, .15);
                border-radius: 999px;
                display: inline-block;
                font-size: .68rem;
                margin-left: .25rem;
                min-width: 1.25rem;
                padding: .08rem .3rem;
                text-align: center;
            }
            .permission-filter-actions {
                display: flex;
                flex-wrap: wrap;
                gap: .5rem;
                margin-left: auto;
            }
            .permission-table-wrap {
                border-top: 1px solid var(--permission-border);
                max-height: min(62vh, 720px);
                overflow: auto;
                position: relative;
            }
            .permission-matrix {
                color: var(--permission-text);
                margin: 0;
                min-width: calc(300px + (var(--permission-count) * 118px));
                width: 100%;
            }
            .permission-matrix > :not(caption) > * > * {
                border-bottom-color: var(--permission-border);
                padding: .85rem 1rem;
                vertical-align: middle;
            }
            .permission-matrix thead th {
                background: var(--permission-surface-soft);
                color: var(--permission-muted);
                font-size: .72rem;
                font-weight: 700;
                letter-spacing: .04em;
                position: sticky;
                text-align: center;
                text-transform: uppercase;
                top: 0;
                z-index: 4;
            }
            .permission-matrix .permission-module-column {
                left: 0;
                min-width: 270px;
                position: sticky;
                text-align: left;
                z-index: 3;
            }
            .permission-matrix thead .permission-module-column { z-index: 5; }
            .permission-module-row .permission-module-column {
                background: var(--permission-surface);
                box-shadow: 1px 0 0 var(--permission-border);
            }
            .permission-module-row {
                background: var(--permission-surface);
                transition: background-color .2s ease;
            }
            .permission-module-row:hover,
            .permission-module-row:hover .permission-module-column { background: var(--permission-surface-soft); }
            .permission-module-row.is-changed .permission-module-column::before {
                background: var(--permission-primary);
                border-radius: 0 4px 4px 0;
                bottom: .55rem;
                content: '';
                left: 0;
                position: absolute;
                top: .55rem;
                width: 3px;
            }
            .permission-module { align-items: center; display: flex; gap: .8rem; }
            .permission-module__details { min-width: 0; }
            .permission-module__name {
                color: var(--permission-text);
                cursor: pointer;
                display: block;
                font-weight: 600;
                margin: 0;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            .permission-module__meta {
                color: var(--permission-muted);
                display: block;
                font-size: .75rem;
                margin-top: .15rem;
            }
            .permission-cell { min-width: 118px; text-align: center; }
            .permission-cell-label,
            .permission-column-control {
                align-items: center;
                cursor: pointer;
                display: flex;
                justify-content: center;
                margin: 0;
                min-height: 2rem;
                width: 100%;
            }
            .permission-column-control { flex-direction: column; gap: .45rem; }
            .permission-column-control span {
                max-width: 100px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            .permission-check {
                -webkit-appearance: none;
                appearance: none;
                background: var(--permission-surface);
                border: 2px solid #c4cbd7;
                border-radius: .35rem;
                cursor: pointer;
                display: inline-grid;
                flex: 0 0 auto;
                height: 1.3rem;
                margin: 0;
                place-content: center;
                transition: border-color .15s ease, background-color .15s ease, box-shadow .15s ease, transform .15s ease;
                width: 1.3rem;
            }
            .permission-check::before {
                border: solid #fff;
                border-width: 0 2px 2px 0;
                content: '';
                height: .58rem;
                transform: rotate(45deg) scale(0);
                transform-origin: center;
                transition: transform .12s ease;
                width: .3rem;
            }
            .permission-check:hover { border-color: var(--permission-primary); transform: translateY(-1px); }
            .permission-check:checked,
            .permission-check:indeterminate {
                background: var(--permission-primary);
                border-color: var(--permission-primary);
            }
            .permission-check:checked::before { transform: rotate(45deg) scale(1); }
            .permission-check:indeterminate::before {
                border-width: 0 0 2px;
                height: 0;
                transform: rotate(0) scale(1);
                width: .65rem;
            }
            .permission-check:focus-visible {
                box-shadow: 0 0 0 .22rem var(--permission-primary-soft);
                outline: none;
            }
            .permission-check:disabled { cursor: not-allowed; opacity: .45; }
            .permission-not-available { color: var(--permission-border); font-size: 1.1rem; }
            .permission-empty-state {
                color: var(--permission-muted);
                display: none;
                padding: 3rem 1rem !important;
                text-align: center;
            }
            .permission-empty-state .icon { display: block; font-size: 2rem; margin-bottom: .5rem; }
            .permission-savebar {
                align-items: center;
                background: var(--permission-surface);
                border-top: 1px solid var(--permission-border);
                bottom: 0;
                display: flex;
                gap: 1rem;
                justify-content: space-between;
                padding: 1rem 1.5rem;
                position: sticky;
                z-index: 6;
            }
            .permission-savebar__summary { align-items: center; display: flex; flex-wrap: wrap; gap: .65rem; }
            .permission-selected-count { color: var(--permission-text); font-weight: 700; }
            .permission-change-badge {
                background: var(--permission-surface-soft);
                border-radius: 999px;
                color: var(--permission-muted);
                font-size: .75rem;
                font-weight: 600;
                padding: .3rem .6rem;
            }
            .permission-change-badge.has-changes {
                background: var(--permission-primary-soft);
                color: var(--permission-primary);
            }
            @media (max-width: 991.98px) {
                .permission-toolbar { grid-template-columns: 1fr; }
                .permission-filter-actions { margin-left: 0; width: 100%; }
            }
            @media (max-width: 575.98px) {
                .permission-manager__hero { align-items: flex-start; flex-direction: column; padding: 1.15rem; }
                .permission-toolbar,
                .permission-filter-bar,
                .permission-savebar { padding-left: 1rem; padding-right: 1rem; }
                .permission-preset,
                .permission-savebar { align-items: stretch; flex-direction: column; }
                .permission-savebar .btn { width: 100%; }
                .permission-filter-actions .btn { flex: 1 1 auto; }
                .permission-matrix .permission-module-column { min-width: 225px; }
            }
        </style>
    @endpush

    <div class="col-12 permission-manager">
        <form id="permission-form" action="{{ route('rolpermisos.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ request('id') }}">

            <div class="card">
                <div class="permission-manager__hero">
                    <div>
                        <div class="permission-manager__eyebrow">{{ __('Control de acceso') }}</div>
                        <h4 class="permission-manager__title">{{ __('Permisos por módulo') }}</h4>
                        <p class="permission-manager__description">
                            {{ __('Busca, compara y modifica los accesos del rol. Los cambios se aplican al guardar.') }}
                        </p>
                    </div>
                    <div class="permission-manager__total">
                        <em class="icon ni ni-shield-check"></em>
                        <span>{{ $modulos->count() }} {{ __('módulos') }}</span>
                    </div>
                </div>

                <div class="permission-toolbar">
                    <div class="permission-search">
                        <em class="icon ni ni-search"></em>
                        <input type="search" class="form-control" id="permission-search"
                            placeholder="{{ __('Buscar módulo...') }}" autocomplete="off">
                    </div>
                    <div class="permission-preset">
                        <select class="form-select" id="permission-preset" aria-label="{{ __('Plantilla rápida de permisos') }}">
                            <option value="">{{ __('Aplicar plantilla a los visibles...') }}</option>
                            <option value="read">{{ __('Solo lectura') }}</option>
                            <option value="operation">{{ __('Operación: crear, ver y editar') }}</option>
                            <option value="all">{{ __('Acceso total') }}</option>
                        </select>
                        <button type="button" class="btn btn-outline-primary" id="apply-permission-preset">
                            {{ __('Aplicar') }}
                        </button>
                    </div>
                </div>

                <div class="permission-filter-bar" aria-label="{{ __('Filtros de permisos') }}">
                    @foreach ([
                        'all' => __('Todos'),
                        'selected' => __('Con permisos'),
                        'incomplete' => __('Incompletos'),
                        'empty' => __('Sin permisos'),
                        'changed' => __('Modificados'),
                    ] as $filterValue => $filterLabel)
                        <button type="button" class="permission-filter {{ $filterValue === 'all' ? 'is-active' : '' }}"
                            data-permission-filter="{{ $filterValue }}">
                            {{ $filterLabel }}
                            <span class="permission-filter__count" data-filter-count="{{ $filterValue }}">0</span>
                        </button>
                    @endforeach

                    <div class="permission-filter-actions">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="select-visible-permissions">
                            <em class="icon ni ni-check-circle"></em>
                            <span>{{ __('Seleccionar visibles') }}</span>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-light" id="clear-visible-permissions">
                            <em class="icon ni ni-cross-circle"></em>
                            <span>{{ __('Limpiar visibles') }}</span>
                        </button>
                    </div>
                </div>

                <div class="permission-table-wrap">
                    <table class="table permission-matrix" style="--permission-count: {{ max($permissionColumns->count(), 1) }}">
                        <thead>
                            <tr>
                                <th scope="col" class="permission-module-column">{{ __('Módulo') }}</th>
                                @foreach ($permissionColumns as $permissionColumn)
                                    <th scope="col" class="permission-cell">
                                        <label class="permission-column-control" for="permission-column-{{ $permissionColumn->permisoid }}">
                                            <span title="{{ $permissionColumn->permisos->nombre }}">{{ $permissionColumn->permisos->nombre }}</span>
                                            <input type="checkbox" class="permission-check permission-column-toggle"
                                                id="permission-column-{{ $permissionColumn->permisoid }}"
                                                data-permission-id="{{ $permissionColumn->permisoid }}"
                                                aria-label="{{ __('Seleccionar :permission en los módulos visibles', ['permission' => $permissionColumn->permisos->nombre]) }}">
                                        </label>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($modulos as $module)
                                @php
                                    $modulePermissions = $module->modulopermiso
                                        ->filter(fn ($modulePermission) => $modulePermission->permisos !== null)
                                        ->keyBy('permisoid');
                                @endphp
                                <tr class="permission-module-row" data-module-name="{{ mb_strtolower($module->nombre) }}"
                                    data-module-id="{{ $module->moduloid }}">
                                    <th scope="row" class="permission-module-column">
                                        <div class="permission-module">
                                            <input type="checkbox" class="permission-check permission-module-toggle"
                                                id="permission-module-{{ $module->moduloid }}"
                                                aria-label="{{ __('Seleccionar todos los permisos de :module', ['module' => $module->nombre]) }}">
                                            <div class="permission-module__details">
                                                <label class="permission-module__name" for="permission-module-{{ $module->moduloid }}">
                                                    {{ $module->nombre }}
                                                </label>
                                                <span class="permission-module__meta">
                                                    <span class="permission-module-selected">0</span>/<span class="permission-module-total">{{ $modulePermissions->count() }}</span>
                                                    {{ __('seleccionados') }}
                                                </span>
                                            </div>
                                        </div>
                                    </th>

                                    @foreach ($permissionColumns as $permissionColumn)
                                        @php
                                            $modulePermission = $modulePermissions->get($permissionColumn->permisoid);
                                            $permission = $modulePermission?->permisos;
                                        @endphp
                                        <td class="permission-cell">
                                            @if ($modulePermission && $permission)
                                                <label class="permission-cell-label"
                                                    for="permission-{{ $modulePermission->modulopermisoid }}"
                                                    title="{{ $module->nombre }} · {{ $permission->nombre }}">
                                                    <input type="checkbox" class="permission-check check permission-item"
                                                        id="permission-{{ $modulePermission->modulopermisoid }}"
                                                        name="permisos[]" value="{{ $modulePermission->modulopermisoid }}"
                                                        data-permission-id="{{ $permission->permisoid }}"
                                                        data-permission-action="{{ mb_strtolower((string) ($permission->nombreLaravel ?? '')) }}"
                                                        data-permission-name="{{ mb_strtolower($permission->nombre) }}"
                                                        {{ in_array($modulePermission->modulopermisoid, $rmp) ? 'checked' : '' }}>
                                                    <span class="visually-hidden">{{ $module->nombre }} · {{ $permission->nombre }}</span>
                                                </label>
                                            @else
                                                <span class="permission-not-available" aria-label="{{ __('No disponible') }}">—</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            <tr id="permission-empty-row">
                                <td class="permission-empty-state" colspan="{{ $permissionColumns->count() + 1 }}">
                                    <em class="icon ni ni-search"></em>
                                    <strong>{{ __('No encontramos módulos con esos criterios.') }}</strong>
                                    <div>{{ __('Prueba otra búsqueda o selecciona un filtro diferente.') }}</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="permission-savebar">
                    <div class="permission-savebar__summary" aria-live="polite">
                        <span class="permission-selected-count">
                            <span id="permission-selected-total">{{ $initialSelectedPermissions }}</span>
                            {{ __('de') }} {{ $totalAvailablePermissions }} {{ __('permisos seleccionados') }}
                        </span>
                        <span class="permission-change-badge" id="permission-change-summary">
                            {{ __('Sin cambios pendientes') }}
                        </span>
                    </div>
                    <button type="submit" class="btn btn-primary" id="save-permissions">
                        <em class="icon ni ni-save"></em>
                        <span>{{ __('Guardar cambios') }}</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        @php
            $permissionMessages = [
                'pendingChanges' => __('Cambios pendientes'),
                'noPendingChanges' => __('Sin cambios pendientes'),
                'incompatiblePreset' => __('No se encontró un permiso compatible con esa plantilla.'),
                'saving' => __('Guardando...'),
            ];
        @endphp
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.getElementById('permission-form');
                const rows = Array.from(document.querySelectorAll('.permission-module-row'));
                const permissionItems = Array.from(document.querySelectorAll('.permission-item'));
                const columnToggles = Array.from(document.querySelectorAll('.permission-column-toggle'));
                const searchInput = document.getElementById('permission-search');
                const emptyState = document.querySelector('#permission-empty-row .permission-empty-state');
                const selectedTotal = document.getElementById('permission-selected-total');
                const changeSummary = document.getElementById('permission-change-summary');
                const saveButton = document.getElementById('save-permissions');
                const initialSelection = new Set(permissionItems.filter(item => item.checked).map(item => item.value));
                const messages = @json($permissionMessages);
                let activeFilter = 'all';
                let isSubmitting = false;

                const normalizeText = value => (value || '').normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '').toLowerCase().trim();
                const getRowItems = row => Array.from(row.querySelectorAll('.permission-item'));
                const getVisibleRows = () => rows.filter(row => !row.hidden);
                const rowHasChanges = row => getRowItems(row)
                    .some(item => item.checked !== initialSelection.has(item.value));

                const updateRowState = row => {
                    const items = getRowItems(row);
                    const selected = items.filter(item => item.checked).length;
                    const toggle = row.querySelector('.permission-module-toggle');

                    row.dataset.selected = selected;
                    row.dataset.total = items.length;
                    row.classList.toggle('has-permissions', selected > 0);
                    row.classList.toggle('is-complete', items.length > 0 && selected === items.length);
                    row.classList.toggle('is-changed', rowHasChanges(row));
                    row.querySelector('.permission-module-selected').textContent = selected;
                    toggle.checked = items.length > 0 && selected === items.length;
                    toggle.indeterminate = selected > 0 && selected < items.length;
                    toggle.disabled = items.length === 0;
                };

                const matchesActiveFilter = row => {
                    const selected = Number(row.dataset.selected || 0);
                    const total = Number(row.dataset.total || 0);
                    if (activeFilter === 'selected') return selected > 0;
                    if (activeFilter === 'incomplete') return selected > 0 && selected < total;
                    if (activeFilter === 'empty') return selected === 0;
                    if (activeFilter === 'changed') return row.classList.contains('is-changed');
                    return true;
                };

                const updateFilterCounts = () => {
                    const counts = {
                        all: rows.length,
                        selected: rows.filter(row => Number(row.dataset.selected) > 0).length,
                        incomplete: rows.filter(row => Number(row.dataset.selected) > 0 && Number(row.dataset.selected) < Number(row.dataset.total)).length,
                        empty: rows.filter(row => Number(row.dataset.selected) === 0).length,
                        changed: rows.filter(row => row.classList.contains('is-changed')).length,
                    };
                    Object.entries(counts).forEach(([filter, count]) => {
                        const target = document.querySelector(`[data-filter-count="${filter}"]`);
                        if (target) target.textContent = count;
                    });
                };

                const updateColumnToggles = () => {
                    const visibleRows = getVisibleRows();
                    columnToggles.forEach(toggle => {
                        const permissionId = toggle.dataset.permissionId;
                        const items = visibleRows.flatMap(row => Array.from(
                            row.querySelectorAll(`.permission-item[data-permission-id="${permissionId}"]`)
                        ));
                        const selected = items.filter(item => item.checked).length;
                        toggle.checked = items.length > 0 && selected === items.length;
                        toggle.indeterminate = selected > 0 && selected < items.length;
                        toggle.disabled = items.length === 0;
                    });
                };

                const applyFilters = () => {
                    const query = normalizeText(searchInput.value);
                    let visibleCount = 0;
                    rows.forEach(row => {
                        const matchesSearch = normalizeText(row.dataset.moduleName).includes(query);
                        const visible = matchesSearch && matchesActiveFilter(row);
                        row.hidden = !visible;
                        if (visible) visibleCount += 1;
                    });
                    emptyState.style.display = visibleCount === 0 ? 'table-cell' : 'none';
                    updateColumnToggles();
                };

                const updateSummary = () => {
                    const selected = permissionItems.filter(item => item.checked).length;
                    const added = permissionItems.filter(item => item.checked && !initialSelection.has(item.value)).length;
                    const removed = permissionItems.filter(item => !item.checked && initialSelection.has(item.value)).length;
                    const hasChanges = added > 0 || removed > 0;
                    selectedTotal.textContent = selected;
                    changeSummary.textContent = hasChanges
                        ? `${messages.pendingChanges}: +${added} / -${removed}`
                        : messages.noPendingChanges;
                    changeSummary.classList.toggle('has-changes', hasChanges);
                    form.dataset.dirty = hasChanges ? 'true' : 'false';
                };

                const refresh = () => {
                    rows.forEach(updateRowState);
                    updateFilterCounts();
                    applyFilters();
                    updateSummary();
                };

                const setRowsPermissions = (targetRows, checked) => {
                    targetRows.forEach(row => getRowItems(row).forEach(item => { item.checked = checked; }));
                    refresh();
                };

                permissionItems.forEach(item => item.addEventListener('change', refresh));
                rows.forEach(row => {
                    row.querySelector('.permission-module-toggle').addEventListener('change', event => {
                        getRowItems(row).forEach(item => { item.checked = event.target.checked; });
                        refresh();
                    });
                });
                columnToggles.forEach(toggle => toggle.addEventListener('change', event => {
                    const permissionId = event.target.dataset.permissionId;
                    getVisibleRows().forEach(row => {
                        const item = row.querySelector(`.permission-item[data-permission-id="${permissionId}"]`);
                        if (item) item.checked = event.target.checked;
                    });
                    refresh();
                }));

                searchInput.addEventListener('input', applyFilters);
                document.querySelectorAll('[data-permission-filter]').forEach(button => {
                    button.addEventListener('click', () => {
                        activeFilter = button.dataset.permissionFilter;
                        document.querySelectorAll('[data-permission-filter]').forEach(filter => {
                            filter.classList.toggle('is-active', filter === button);
                        });
                        applyFilters();
                    });
                });
                document.getElementById('select-visible-permissions').addEventListener('click', () => {
                    setRowsPermissions(getVisibleRows(), true);
                });
                document.getElementById('clear-visible-permissions').addEventListener('click', () => {
                    setRowsPermissions(getVisibleRows(), false);
                });

                document.getElementById('apply-permission-preset').addEventListener('click', () => {
                    const preset = document.getElementById('permission-preset').value;
                    if (!preset) return;
                    const readableActions = ['show', 'read', 'index', 'ver', 'leer'];
                    const operationActions = [...readableActions, 'create', 'store', 'edit', 'update', 'crear', 'editar'];
                    const visibleItems = getVisibleRows().flatMap(getRowItems);
                    const matchesPreset = item => {
                        if (preset === 'all') return true;
                        const action = normalizeText(item.dataset.permissionAction);
                        const name = normalizeText(item.dataset.permissionName);
                        const allowed = preset === 'read' ? readableActions : operationActions;
                        return allowed.some(value => action === value || name === value);
                    };
                    if (preset !== 'all' && !visibleItems.some(matchesPreset)) {
                        window.alert(messages.incompatiblePreset);
                        return;
                    }
                    visibleItems.forEach(item => { item.checked = matchesPreset(item); });
                    refresh();
                });

                form.addEventListener('submit', () => {
                    isSubmitting = true;
                    saveButton.disabled = true;
                    saveButton.querySelector('span').textContent = messages.saving;
                });
                window.addEventListener('beforeunload', event => {
                    if (!isSubmitting && form.dataset.dirty === 'true') {
                        event.preventDefault();
                        event.returnValue = '';
                    }
                });

                refresh();
            });
        </script>
    @endpush
</x-app-layout>
