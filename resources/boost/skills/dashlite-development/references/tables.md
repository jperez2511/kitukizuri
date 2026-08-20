# DashLite tables and DataTables

## Canonical sources

Inspect:

```text
src/resources/views/krud/index.blade.php
src/resources/views/krud/components/table.blade.php
src/resources/sass/global/tables/
src/resources/dashlite-scss/<variant>/global/tables/
```

The current Krud listing is the strongest example for server-side DataTables integration.

## Basic table shell

The Krud index uses a DashLite card plus a responsive table wrapper:

```html
<div class="card card-bordered card-stretch">
    <div class="card-inner">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle nowrap w-100">
                ...
            </table>
        </div>
    </div>
</div>
```

Reuse the relevant parts instead of generating a visually unrelated table shell.

## DataTables integration

The package currently uses DataTables with server-side processing in Krud listings.

The implementation includes:

- custom language strings,
- responsive behavior,
- server-side Ajax,
- action-column configuration,
- export/create buttons,
- customized DataTables DOM placement,
- DashLite-compatible classes around filters, export buttons, table wrapper, pagination, and info.

Do not initialize a second DataTable on a table already managed by a package component.

## Existing layout classes

The Krud listing uses or generates classes including:

```text
datatable-filter
datatable-wrap
dt-export-buttons
dt-export-title
with-export
```

Search the repository before inventing parallel wrapper names for standard DataTables controls.

## Actions column

The existing Krud table explicitly marks the action column as:

- non-orderable,
- non-searchable,
- centered,
- vertically aligned.

When adding actions to a DataTable, preserve these semantics unless the requested behavior differs.

Use accessible buttons/links and NioIcon for common action icons.

## Responsive behavior

The current view includes scoped adjustments for:

- filter wrapping on small screens,
- full-width search input on mobile,
- button/action sizing,
- no-wrap action cells.

If a new table has a mobile problem, first determine whether the existing responsive/DataTables structure can be reused before writing feature-specific breakpoint CSS.

## Table styling

DashLite has table SCSS under shared and variant-specific trees.

Before redefining borders, header colors, row hover, spacing, or dark mode styles, inspect the relevant table SCSS.

Avoid global overrides such as:

```css
table { ... }
.table { ... }
```

for feature-specific requirements.

## Non-DataTables tables

Not every table needs DataTables.

Use a standard DashLite/Bootstrap table when the feature requires only static or server-rendered rows.

Do not add DataTables merely for visual styling.

## Checklist

Before finishing a table task verify:

- The existing Krud table pattern was inspected.
- DataTables is used only if interaction requirements justify it.
- Existing DataTables initialization is not duplicated.
- `table-responsive` or equivalent responsive behavior is present when needed.
- Actions are non-orderable/searchable when appropriate.
- Mobile controls remain usable.
- NioIcon is used for standard actions.
- Styling is scoped and does not override all tables globally.
- The result works with dark mode/theme styling rather than assuming a white-only surface.
