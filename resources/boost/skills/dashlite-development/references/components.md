# DashLite components and composition

## General rule

Search working Blade files before inventing component markup.

Priority locations:

```text
src/resources/views/components/
src/resources/views/krud/
src/resources/views/krud/components/
src/resources/views/layouts/dashlite/
```

Use the semantic primitive already established by the project.

## Page blocks

DashLite uses `nk-block` structures for primary page sections.

The standard content layout already wraps feature output in an `nk-block` and `row g-gs`, so feature views normally should not add another top-level block unless they genuinely need nested section semantics.

Page headings in the default layout use:

```text
nk-block-head
nk-block-head-sm
nk-block-between
nk-block-head-content
nk-block-title
page-title
```

If the layout's `header` slot already produces the heading, do not duplicate the page title inside the feature body.

## Cards

Common repository pattern:

```html
<div class="card card-bordered">
    <div class="card-inner">
        ...
    </div>
</div>
```

For stretch behavior existing views also use:

```text
card-stretch
```

Preview/form-oriented views use structures such as:

```text
components-preview
card-preview
```

Before creating a custom shell for a standard panel, test whether a DashLite card is enough.

## Grid

DashLite relies on Bootstrap's responsive grid and its own gap convention.

Common composition includes:

```text
row
g-gs
col-12
col-md-6
col-lg-*
```

Feature-level custom grids are acceptable when domain-specific, but do not replace working responsive Bootstrap structure without a concrete need.

## Buttons

Existing views use normal Bootstrap-compatible button classes alongside DashLite styling:

```text
btn
btn-primary
btn-success
btn-danger
btn-secondary
btn-outline-*
btn-space
```

Use existing button conventions in neighboring views.

Icons inside actions should normally use NioIcon:

```html
<button class="btn btn-primary">
    <em class="icon ni ni-plus"></em>
    <span>Create</span>
</button>
```

Do not create a custom clickable `div` when an accessible `button` or `a` element is appropriate.

## Modals

Repository modal markup uses Bootstrap 5 behavior with DashLite-compatible styling, for example:

```html
<div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">...</h5>
                <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">...</div>
        </div>
    </div>
</div>
```

Verify the exact surrounding component/view before copying this sample.

Use `data-bs-*` conventions for current Bootstrap integration unless the repository context proves otherwise.

## Banners and feedback

The standard content partial already renders `<x-banner />` inside the content grid.

Some older/specialized feature views also render banners explicitly. Before adding another banner, determine whether the active layout already provides one to avoid duplicate feedback messages.

## Reusable Laravel components

The package has Blade components for common Jetstream/application behavior under:

```text
src/resources/views/components/
```

Examples include buttons, banners, modals, authentication cards, confirmation dialogs, inputs, labels, and other application primitives.

If a Laravel component already expresses the desired semantics and its rendering matches the project, prefer it over copied raw markup.

## Domain-specific components

Consuming applications often require richer compositions than the stock DashLite demo. A domain-specific component may combine:

- DashLite page/card primitives,
- Bootstrap grid and spacing,
- NioIcon,
- scoped feature CSS,
- application-specific data/status presentation.

This is acceptable.

The rule is not "never write custom UI." The rule is "do not reimplement primitives the stack already provides."

## Avoid

Do not:

- invent unknown `nk-*` classes,
- paste theme markup from another DashLite version without checking source,
- introduce duplicate card/panel abstractions for standard containers,
- hardcode theme colors when semantic classes/variables exist,
- place navigation/layout classes inside feature components,
- mix Bootstrap 4 `data-toggle` examples into Bootstrap 5 views,
- add an icon library for a handful of common actions,
- use global CSS to fix one screen.

## Selection heuristic

When choosing markup:

1. Find a working project example with the same semantic purpose.
2. Reuse its structural classes.
3. Remove irrelevant domain-specific pieces.
4. Add only the required data/content.
5. Add scoped custom styling only if the reused primitive cannot express the requested composition.
