# DashLite styling and SCSS

## Source locations

Primary styling sources:

```text
src/resources/sass/
src/resources/dashlite-scss/
```

The repository contains both shared/current SCSS and variant-specific DashLite source trees.

Search both before creating CSS for an existing primitive.

## Styling priority

Use this order:

1. Existing DashLite class.
2. Existing Bootstrap utility.
3. Existing project utility/component class.
4. Small feature-scoped custom CSS.
5. New reusable SCSS abstraction when multiple features need it.
6. Core/theme override only when the requested behavior is intentionally global.

## Do not overwrite core primitives casually

Avoid feature-local global changes to:

```text
.nk-*
.card
.btn
.form-control
.form-select
.modal
.table
body
```

A feature stylesheet should not silently redefine the design system.

Prefer scoping:

```scss
.inventory-stock {
    .card { ... }
}
```

only when the modification is necessary and cannot be represented by existing classes.

## Variant SCSS

`src/resources/dashlite-scss/` contains trees for multiple variants such as `demo1` through `demo9` and `covid`.

These include variant-level differences in layout, skins, tables, core behavior, and visual themes.

Do not modify one variant's SCSS and assume all variants inherit the change.

Before a global change, determine whether the same source exists in shared `src/resources/sass/` and whether that is the correct layer.

## Theme and dark mode

Dark-mode styling exists in DashLite SCSS, including `_dark-skin.scss` sources.

The layout state can add `dark-mode` to the body.

Avoid hardcoded values such as:

```css
background: #fff;
color: #111;
```

for generic surfaces unless the design intentionally requires fixed colors and dark-mode behavior has been considered.

Prefer existing semantic surface/text classes or SCSS variables where available.

## Skin classes

DashLite supports theme/skin styling and sidebar tone classes such as:

```text
is-light
is-dark
is-theme
```

These are resolved through layout state for global shell elements.

Do not use them as arbitrary feature modifiers without confirming their intended SCSS scope.

## Bootstrap utilities

Bootstrap 5 utilities are valid and preferred over new CSS for standard layout concerns:

- display,
- flex,
- grid columns,
- gaps,
- margins/padding,
- text alignment,
- responsive visibility,
- sizing when appropriate.

Example: use `d-flex align-items-center gap-2` before writing a new utility class that does exactly the same thing.

## Domain-specific styling

A screen may legitimately use a feature namespace, for example:

```text
.quote-flow
.inventory-stock
.reservation-flow
```

This is useful when the screen has domain-specific visual composition not represented by DashLite primitives.

The feature namespace should compose existing primitives rather than reimplementing them.

Good custom styling typically handles:

- a specialized hero/header composition,
- domain-specific metric layout,
- timeline/flow visualization,
- application-specific state presentation,
- spacing or responsive composition unique to the feature.

It should not need to recreate standard buttons, inputs, cards, modals, or navigation.

## Inline styles

Existing legacy/feature views may contain `@push('styles')` with local `<style>` blocks.

When modifying existing code, preserve compatibility unless the task includes refactoring.

For new reusable screens, prefer SCSS in the appropriate application/package stylesheet when the style is substantial.

Small page-only corrections can remain scoped locally if that matches neighboring code and build constraints.

## Before adding CSS

Search by intended behavior, not just exact class name.

Examples:

```text
card bordered
form outlined
datatable wrapper
sidebar light
dark mode
block head
responsive table
```

Then inspect the matching SCSS/Blade implementation.

## Styling review checklist

- No existing DashLite class could replace the new rule.
- No Bootstrap utility could replace the new rule.
- The selector is scoped appropriately.
- Core theme classes are not unintentionally overridden.
- Dark mode has been considered.
- Responsive behavior has been considered.
- The rule does not depend on one variant unless intentionally variant-specific.
- Repeated custom patterns have been extracted only when repetition justifies abstraction.
