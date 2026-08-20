---
name: dashlite-development
description: Use when building, extending, reviewing, debugging, or refactoring DashLite-based UI in Kitukizuri or consuming Laravel applications. Ground every UI decision in the actual DashLite implementation shipped by this repository before inventing markup, classes, CSS, JavaScript, layout behavior, icons, tables, forms, or components.
---

# DashLite Development

## Purpose

Use this skill whenever a task touches the DashLite UI stack bundled with Kitukizuri.

The objective is not to reproduce generic DashLite examples from memory. The objective is to make changes that are compatible with the exact DashLite integration, Blade layouts, SCSS, JavaScript runtime, Bootstrap version, and conventions present in this repository.

Do not invent DashLite APIs, classes, markup, or behaviors when the source can be inspected.

## Source-of-truth order

When sources disagree, use this precedence:

1. Current repository source under `src/`.
2. Existing working Blade views and components in this repository.
3. `DashliteLayoutState` and current layout/configuration code.
4. Runtime JavaScript and SCSS shipped by the package.
5. The reference files in this skill.
6. External DashLite documentation or historical examples only when the repository does not answer the question.

The package source wins over generic Bootstrap or DashLite knowledge.

## Required workflow

Before implementing a DashLite-related change:

1. Identify the UI concern: page structure, layout, navigation, cards, forms, tables, modal, JS behavior, icons, theme, or custom styling.
2. Search the repository for an existing equivalent pattern.
3. Read the relevant reference file from `references/`.
4. Inspect the underlying Blade/SCSS/JS source named by that reference.
5. Reuse DashLite's existing structure and classes whenever an equivalent exists.
6. Use Bootstrap utilities only as a complement to DashLite, not as a replacement for established `nk-*` structures.
7. Add custom CSS or JavaScript only for behavior not already solved by the shipped stack.
8. Keep the active layout variant and runtime state intact.
9. Preserve compatibility with current project conventions and existing views.
10. Validate the result against the implementation checklist before finishing.

## Mandatory UI implementation rules

### 1. Search before inventing

Never create a custom visual pattern before checking whether DashLite or Kitukizuri already has an equivalent.

For a requested interface element, search in this order:

```text
src/resources/views/
src/resources/sass/
src/resources/dashlite-scss/
src/resources/js/
```

Useful searches include the semantic feature name and common DashLite classes such as:

```text
nk-block
nk-block-head
card-bordered
card-inner
form-control-wrap
form-control-outlined
nk-menu
nk-sidebar
nk-header
modal
datatable-wrap
```

### 2. Preserve DashLite markup

Do not replace established DashLite structures with generic Bootstrap markup merely because both render acceptably.

Prefer:

```html
<div class="card card-bordered">
    <div class="card-inner">
        ...
    </div>
</div>
```

over an unrelated custom wrapper when a card is the intended primitive.

Prefer the repository's `nk-*` structures for page shell, blocks, navigation, sidebar, header, content hierarchy, and theme behavior.

### 3. Preserve the layout state machine

`src/App/Support/DashliteLayoutState.php` is the source of truth for layout behavior.

Do not hardcode:

- sidebar presence,
- apps sidebar presence,
- main wrap classes,
- header classes,
- header/container fluidity,
- theme/light sidebar classes,
- RTL behavior,
- dark mode behavior,
- variant-specific body classes.

Read `references/layouts.md` before changing any layout or navigation code.

### 4. Respect the active variant

Kitukizuri supports these DashLite variants:

```text
demo1
demo2
demo3
demo4
demo5
demo6
demo7
demo8
demo9
covid
```

The default is currently `demo3`.

Do not assume every variant contains a sidebar, apps sidebar, aside, fixed-width container, or identical header arrangement.

### 5. Use NioIcon first

For standard application icons, prefer the NioIcon convention already used by the package:

```html
<em class="icon ni ni-plus"></em>
<em class="icon ni ni-search"></em>
<em class="icon ni ni-cross"></em>
```

Do not introduce another icon library merely because an icon is easier to recall there.

Read `references/icons.md` before adding a new icon dependency or unfamiliar icon class.

### 6. Treat Bootstrap as part of the stack, not the whole design system

The current frontend uses Bootstrap `^5.3.2` together with DashLite's own component layer.

Bootstrap utilities such as grid, spacing, flex, text alignment, responsive helpers, buttons, and modal behavior are valid when compatible with existing markup.

Do not downgrade markup to Bootstrap 4 syntax copied from old DashLite examples.

Examples:

- Prefer `data-bs-toggle` / `data-bs-dismiss` when using Bootstrap 5 behavior.
- Verify component markup in repository source before copying external samples.

### 7. Prefer repository Blade components and working views

Before writing a form, table, modal, action button, or layout from scratch, inspect:

```text
src/resources/views/components/
src/resources/views/krud/
src/resources/views/krud/components/
src/resources/views/layouts/dashlite/
```

The existing implementation is more authoritative than generic theme documentation.

### 8. Do not bypass NioApp behavior unnecessarily

DashLite's runtime is implemented in `src/resources/js/init.js` and exposes `NioApp` behavior for theme state, responsive state, toggles, Bootstrap helpers, Select2 integration, and other UI initialization.

Before adding custom JavaScript for a toggle, menu, overlay, tooltip, popover, progress indicator, file input, responsive behavior, or Select2 initialization, inspect the current runtime.

Read `references/javascript.md`.

### 9. Minimize custom CSS

Custom CSS is acceptable when the domain-specific screen needs a visual treatment DashLite does not provide, but it must be scoped and additive.

Preferred order:

1. Existing DashLite component class.
2. Existing Bootstrap utility.
3. Existing project/component class.
4. Small scoped custom rule.
5. New reusable SCSS abstraction only when repetition justifies it.

Do not redefine core `nk-*`, `.card`, `.btn`, `.form-control`, `.modal`, or global theme classes in a feature stylesheet unless the change is intentionally system-wide.

Read `references/styling.md`.

### 10. Keep domain screens consistent without forcing all UI into stock DashLite

Consuming applications may contain feature-specific classes such as BEM-like screen namespaces. These are valid when they add domain presentation while still using the DashLite layout, grid, typography, forms, buttons, icons, and interaction primitives underneath.

Do not remove intentional feature-level CSS solely to make a page look more like a stock theme demo.

Instead ask:

- Is DashLite already solving the primitive?
- Is custom CSS only solving the domain-specific composition?
- Can the custom rule be reduced by reusing existing utilities/components?

## Decision model

Use this reasoning sequence for new UI:

```text
Requested interface
        |
        v
Does Kitukizuri already contain a working equivalent?
        |
      yes ---------------------- no
        |                         |
        v                         v
Reuse/adapt the pattern      Does DashLite SCSS/JS provide it?
                                  |
                                yes -------- no
                                  |           |
                                  v           v
                            Use DashLite   Use Bootstrap utilities/
                            structure      existing project components
                                              |
                                              v
                                    Is custom behavior still needed?
                                          /           \
                                        no             yes
                                        |               |
                                     finish       add minimal scoped
                                                  CSS/JS/component code
```

## Task routing

Read only the references relevant to the current task.

| Task | Reference |
| --- | --- |
| Understand package UI architecture | `references/architecture.md` |
| Modify layout, sidebar, header, body classes, variants | `references/layouts.md` |
| Create cards, blocks, page sections, modals, common UI | `references/components.md` |
| Build or change inputs, Select2, field presentation | `references/forms.md` |
| Build or change DataTables / responsive tables | `references/tables.md` |
| Add frontend behavior or troubleshoot NioApp | `references/javascript.md` |
| Add SCSS/CSS, themes, dark mode, visual overrides | `references/styling.md` |
| Choose or add icons | `references/icons.md` |
| Review a completed implementation | `references/implementation-checklist.md` |

## Important implementation locations

### Layout

```text
src/App/Support/DashliteLayoutState.php
src/resources/views/layouts/dashlite/partials/
src/resources/views/layouts/dashlite/variants/
```

### Blade components and examples

```text
src/resources/views/components/
src/resources/views/krud/
src/resources/views/krud/components/
src/resources/views/navigation-menu.blade.php
```

### JavaScript

```text
src/resources/js/init.js
```

### SCSS

```text
src/resources/sass/
src/resources/dashlite-scss/
```

## Current runtime facts

The repository currently declares:

- DashLite runtime metadata: version `2.3` in `src/resources/js/init.js`.
- Bootstrap: `^5.3.2` in `package.json`.
- jQuery: `^3.7.1`.
- Select2, DataTables, SimpleBar, Chart.js, SweetAlert2, noUiSlider, Quill, Magnific Popup, Bootstrap Datepicker, and related plugins.

This combination means external DashLite 2.x snippets may be stale regarding Bootstrap integration. Always verify them against the current repository.

## Examples of correct behavior

### User asks: "Create a CRUD listing"

Do not immediately generate a plain Bootstrap table.

First inspect:

```text
src/resources/views/krud/index.blade.php
src/resources/views/krud/components/table.blade.php
```

Reuse the existing `card card-bordered`, responsive table/DataTables structure, button conventions, NioIcon usage, and repository-specific initialization where appropriate.

### User asks: "Add a form field"

First inspect:

```text
src/resources/views/krud/components/input.blade.php
src/resources/views/krud/components/select.blade.php
src/resources/views/krud/components/select2.blade.php
```

Use the established `form-group`, `form-control-wrap`, outlined-control, and Select2 conventions instead of inventing a different form system.

### User asks: "Change the sidebar"

Read `DashliteLayoutState` and the appropriate layout partial first. Do not manually paste a sidebar into the requested page.

### User asks: "Make this page modern"

Do not interpret this as permission to replace the design system.

Preserve the existing DashLite shell, typography, responsive structure, icons, interaction behavior, and existing components. Modernize composition, hierarchy, spacing, and domain-specific presentation with the smallest necessary additional styling.

## Validation

After editing this skill, run:

```bash
bash resources/boost/skills/dashlite-development/scripts/validate-skill.sh
```

Install repository-local copies for Codex and Claude Code with:

```bash
bash resources/boost/skills/dashlite-development/scripts/install-local.sh
```

The canonical copy is `resources/boost/skills/dashlite-development/`. Do not edit generated local copies as the primary source.
