# DashLite architecture in Kitukizuri

## Scope

DashLite in Kitukizuri is not a standalone static theme directory. It is integrated with Laravel Blade, configuration-driven layout state, package components, SCSS sources, JavaScript runtime initialization, and third-party frontend plugins.

Use this document to locate the authoritative implementation before changing UI.

## Main layers

### 1. Layout state

Source:

```text
src/App/Support/DashliteLayoutState.php
```

This class translates package configuration and visual preferences into the state consumed by Blade layouts.

It determines values such as:

- active DashLite variant,
- body classes,
- sidebar presence,
- apps sidebar presence,
- aside layout behavior,
- main wrap class,
- header class,
- sidebar class/tone,
- fixed vs fluid containers,
- dark mode,
- RTL mode,
- bordered UI mode.

Treat this as layout source of truth.

### 2. Blade layout templates

Sources:

```text
src/resources/views/layouts/dashlite/variants/
src/resources/views/layouts/dashlite/partials/
```

Variants represent supported DashLite shells. Partials hold reusable structural pieces.

Confirmed variant views:

```text
demo1.blade.php
demo2.blade.php
demo3.blade.php
demo4.blade.php
demo5.blade.php
demo6.blade.php
demo7.blade.php
demo8.blade.php
demo9.blade.php
covid.blade.php
```

Confirmed reusable partials include:

```text
apps-sidebar.blade.php
content-aside.blade.php
content-default.blade.php
layout-aside.blade.php
layout-plain.blade.php
layout-split.blade.php
layout-standard.blade.php
sidebar-split.blade.php
sidebar-standard.blade.php
```

Do not duplicate these structures in feature views.

### 3. Feature and component Blade views

Sources:

```text
src/resources/views/components/
src/resources/views/krud/
src/resources/views/krud/components/
src/resources/views/navigation-menu.blade.php
```

These files demonstrate how the project actually applies DashLite classes to Laravel UI.

They are the preferred reference for implementation patterns.

### 4. JavaScript runtime

Source:

```text
src/resources/js/init.js
```

The runtime establishes global jQuery compatibility and the `NioApp` object.

It contains responsive state, class initialization, background/color helpers, toggle behavior, Bootstrap wrappers, progress handling, file input behavior, Select2 support, and other DashLite initialization.

Before adding frontend behavior, confirm that `NioApp` does not already implement it.

### 5. SCSS system

Sources:

```text
src/resources/sass/
src/resources/dashlite-scss/
```

`src/resources/sass/` contains the package's main shared DashLite-derived styling and current application-level SCSS.

`src/resources/dashlite-scss/` contains variant-specific DashLite source trees for demos and the covid variant.

The variant trees include theme skins, global components, layout definitions, and dark-mode-related styling.

Do not copy CSS from rendered browser output when the SCSS source is available.

## Frontend dependencies

`package.json` currently uses Bootstrap `^5.3.2` and jQuery `^3.7.1` together with the DashLite runtime.

Other relevant dependencies include:

- DataTables,
- Select2,
- SimpleBar,
- SweetAlert2,
- Chart.js,
- Bootstrap Datepicker,
- MetisMenu,
- noUiSlider,
- Quill,
- Magnific Popup,
- node-waves.

The presence of these packages does not mean every page should initialize them manually. Search existing project initialization first.

## Page composition

The default content partial currently follows this hierarchy:

```text
nk-content
  content container from layout state
    nk-content-inner
      nk-content-body
        optional nk-block-head
        nk-block
          row g-gs
            banner
            page slot
```

This means feature Blade pages rendered through the application layout generally provide the content placed inside the existing `row g-gs` rather than reconstructing the full DashLite page shell.

## Default variant

The configured fallback variant is currently `demo3`.

`demo3` delegates to `layout-standard` with:

- sidebar placed inside the main wrap,
- apps sidebar enabled.

Never assume this default is permanently active in consuming applications. Read the layout state/configuration when behavior is variant-sensitive.

## Working principle

Think of Kitukizuri's DashLite integration as this stack:

```text
Laravel configuration
        |
        v
DashliteLayoutState
        |
        v
variant Blade
        |
        v
layout partials
        |
        v
feature Blade/components
        |
        +------> DashLite/Bootstrap classes
        |
        +------> NioApp / plugins
        |
        +------> SCSS theme/overrides
```

A UI change should enter this stack at the lowest layer necessary. Do not edit layout state to solve a feature-only styling problem, and do not add feature CSS to solve a global layout requirement.
