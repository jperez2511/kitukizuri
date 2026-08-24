# DashLite layouts

## Source of truth

Always inspect:

```text
src/App/Support/DashliteLayoutState.php
src/resources/views/layouts/dashlite/variants/
src/resources/views/layouts/dashlite/partials/
```

Do not hardcode layout behavior in feature Blade views.

## Supported variants

Current variants:

| Variant | Layout flags |
| --- | --- |
| `demo1` | `has-sidebar` |
| `demo2` | `has-sidebar` |
| `demo3` | `has-apps-sidebar has-sidebar` |
| `demo4` | `has-aside` |
| `demo5` | `has-sidebar` |
| `demo6` | none |
| `demo7` | `has-sidebar` |
| `demo8` | none |
| `demo9` | `has-sidebar` |
| `covid` | `has-sidebar has-sidebar-short` |

Invalid configured variants fall back to `demo3`.

## Layout state behavior

`DashliteLayoutState::build()` computes the layout used by Blade.

Relevant configuration keys include:

```text
kitukizuri.dashliteVariant
kitukizuri.dashliteBodyClass
```

Visual preferences are read through the package layout configuration and include:

```text
direction: ltr | rtl
ui_style: default | bordered
sidebar_style: auto | white | dark | theme
skin_mode: light | dark
```

The builder applies visual classes rather than requiring individual pages to manage them.

## Important generated concepts

The layout state determines or normalizes:

- body class,
- whether apps sidebar exists,
- whether sidebar exists,
- whether an aside exists,
- split-sidebar behavior,
- sidebar target identifiers,
- main wrap class,
- sidebar class,
- apps sidebar class,
- header class,
- header container class,
- content container class.

A feature view should consume the resulting shell, not reproduce it.

## Sidebar styles

Current semantic style mapping:

| Style | CSS class |
| --- | --- |
| `white` | `is-light` |
| `dark` | `is-dark` |
| `theme` | `is-theme` |

Default styles by variant currently resolve as:

| Variant | Default sidebar style |
| --- | --- |
| `demo1` | dark |
| `demo2` | white |
| `demo3` | auto |
| `demo4` | white |
| `demo5` | auto |
| `demo6` | auto |
| `demo7` | white |
| `demo8` | auto |
| `demo9` | white |
| `covid` | white |

Do not add `is-light`, `is-dark`, or `is-theme` manually to a feature page in order to override global sidebar configuration.

## Body visual state

The layout builder manages visual state including:

```text
ui-bordered
has-rtl
dark-mode
```

It also detects body/layout classes such as:

```text
has-apps-sidebar
has-sidebar
has-aside
bg-white
bg-lighter
ui-clean
ui-rounder
```

Do not duplicate this logic in Blade.

## Standard layout structure

`layout-standard.blade.php` follows a reusable shell similar to:

```text
nk-app-root
  optional apps-sidebar
  nk-main
    optional sidebar
    main wrap from layout state
      header from layout state
        header container
          navigation menu
      optional sidebar in wrap
      content-default
```

The exact sidebar placement can differ based on parameters/variant.

## Content structure

`content-default.blade.php` provides:

```html
<div class="nk-content">
    <div class="...">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <!-- optional page heading -->
                <div class="nk-block">
                    <div class="row g-gs">
                        <!-- banner + slot -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
```

Therefore a normal feature view using `<x-app-layout>` generally begins at the grid-item/content level.

A common valid page pattern is:

```blade
<x-app-layout>
    <x-slot name="header">Page title</x-slot>

    <div class="col-12">
        <div class="card card-bordered">
            <div class="card-inner">
                ...
            </div>
        </div>
    </div>
</x-app-layout>
```

Do not wrap that again in `nk-content`, `nk-content-inner`, and `nk-content-body` unless the page intentionally bypasses the standard content partial.

## Navigation changes

Before modifying navigation, inspect:

```text
src/resources/views/navigation-menu.blade.php
src/resources/views/layouts/dashlite/partials/sidebar-standard.blade.php
src/resources/views/layouts/dashlite/partials/sidebar-split.blade.php
src/resources/views/layouts/dashlite/partials/apps-sidebar.blade.php
src/resources/js/init.js
```

DashLite menu behavior depends on specific class/data-attribute conventions and `NioApp.Toggle` behavior.

Avoid building a second independent sidebar/menu implementation.

## Layout change checklist

Before finishing a layout change verify:

- The change works with the active variant.
- It does not assume `demo3` unless intentionally scoped to it.
- `DashliteLayoutState` still owns body/header/sidebar state.
- Dark mode is not broken by hardcoded colors/classes.
- RTL is not broken by directional assumptions.
- Mobile menu/toggle behavior still uses expected targets and classes.
- Existing layout partials were reused instead of duplicated.
- Feature views did not reconstruct the application shell unnecessarily.
