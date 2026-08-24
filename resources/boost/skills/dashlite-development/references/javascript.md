# DashLite JavaScript runtime

## Source of truth

Inspect:

```text
src/resources/js/init.js
```

before writing custom frontend behavior that may already exist in DashLite.

## Runtime identity

The current runtime declares:

```text
NioApp 1.0.8
DashLite 2.3
```

It is integrated with the repository's current dependencies, including Bootstrap 5.3.x and jQuery 3.7.x.

Do not assume an external DashLite 2.3 snippet uses the same Bootstrap syntax as this repository.

## jQuery availability

The runtime imports jQuery and exposes both aliases globally:

```text
window.$
window.jQuery
```

Several plugins depend on global jQuery.

Do not load a second jQuery instance inside a feature view.

## NioApp state

`NioApp` tracks browser/runtime state including:

- viewport dimensions,
- breakpoints,
- touch capability,
- mobile detection,
- RTL state,
- dark state.

Breakpoints currently include semantic values such as:

```text
mb 420
sm 576
md 768
lg 992
xl 1200
xxl 1540
```

When behavior should align with DashLite's JS responsive state, reuse its mechanisms instead of creating unrelated hardcoded breakpoint logic.

## Class initialization

`NioApp.ClassInit` manages body state such as:

```text
as-mobile
has-touch
no-touch
has-rtl
nk-nio-theme
```

Do not manually add these classes in feature views.

## Toggle system

`NioApp.Toggle` implements behavior for:

- trigger activation,
- expanded content,
- body toggle state,
- overlays,
- dropdown/submenu opening,
- closing profile/menu state.

DashLite navigation relies on this convention.

Before writing a custom sidebar/menu toggle, inspect the data attributes and targets expected by this runtime.

## Bootstrap helpers

`NioApp.BS` includes wrappers/helpers for current UI behavior such as:

```text
tooltip
menutip
popover
progress
modalfix
fileinput
```

Before adding another document-ready block for these behaviors, confirm whether the shared runtime already initializes them.

## Select2

`NioApp.select2` provides integration with Select2 and supports configuration derived from element data attributes.

It accounts for RTL state.

The Krud `select2` component may also initialize `.js-select2` directly, so avoid initializing the same element through both paths.

## Background and color helpers

The runtime supports data-driven color/background behavior through attributes such as concepts equivalent to:

```text
data-bg
data-bg-color
data-bg-image
data-color
```

Check implementation before writing custom JS just to map data attributes into inline styles.

## Document-ready lifecycle

The NioApp core maintains lifecycle queues for document ready, window load, and resize callbacks.

Shared behavior that belongs to the theme should integrate with the existing lifecycle rather than creating many unrelated global listeners.

Feature-specific code can still use local `DOMContentLoaded` or module initialization when the scope is genuinely limited to that feature.

## Blade scripts

Existing Blade views often use:

```blade
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            ...
        });
    </script>
@endpush
```

This is valid for feature-local behavior, but before adding it ask:

1. Does NioApp already solve the behavior?
2. Is a shared package JS module more appropriate?
3. Will the code initialize a plugin twice?
4. Does the markup exist on every page where the script runs?

## Bootstrap 5

Use Bootstrap 5 data attributes for new interactive markup:

```text
data-bs-toggle
data-bs-target
data-bs-dismiss
```

Do not copy `data-toggle` / `data-target` markup from older theme samples unless the current repository explicitly uses a compatibility path.

## Avoid

Do not:

- import jQuery again in a feature,
- create a second global `NioApp`,
- manually toggle theme/body classes owned by layout/runtime state,
- duplicate Select2/DataTables initialization,
- use arbitrary breakpoint values when matching DashLite behavior is required,
- copy minified external theme JS over `src/resources/js/init.js`,
- attach global listeners for behavior that belongs to one page.
