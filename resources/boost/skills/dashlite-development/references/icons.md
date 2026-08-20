# DashLite icons

## Preferred icon system

Kitukizuri commonly uses DashLite's NioIcon convention:

```html
<em class="icon ni ni-plus"></em>
<em class="icon ni ni-search"></em>
<em class="icon ni ni-cross"></em>
<em class="icon ni ni-history"></em>
```

For normal application actions, prefer NioIcon before introducing another icon library.

## Search before guessing

Do not invent `ni-*` names from intuition.

Before selecting an unfamiliar icon:

1. Search the repository for `ni ni-` usages with the desired semantic meaning.
2. Search the bundled icon/style sources if needed.
3. Reuse a confirmed icon class.
4. If no suitable NioIcon exists, check existing project dependencies before adding anything new.

## Markup

Existing project markup generally uses:

```html
<em class="icon ni ni-ICON"></em>
```

Preserve the `icon ni` base classes.

## Buttons

For icon + text actions:

```html
<a class="btn btn-primary" href="...">
    <em class="icon ni ni-plus"></em>
    <span>Create</span>
</a>
```

For icon-only controls, include an accessible label through visible text, `aria-label`, title/tooltip, or the established component convention.

## Avoid

Do not:

- add Font Awesome solely for one common action,
- mix several icon systems in one new component without reason,
- guess an icon class that has not been verified,
- use emoji as a replacement for established application icons,
- rely on icon shape alone to communicate destructive or critical state.
