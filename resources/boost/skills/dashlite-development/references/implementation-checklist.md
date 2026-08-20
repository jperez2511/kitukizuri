# DashLite implementation checklist

Use this before completing a DashLite UI task.

## Source grounding

- [ ] I searched the repository for an equivalent UI pattern.
- [ ] I inspected the relevant Blade source rather than relying only on memory.
- [ ] I inspected SCSS/JavaScript when the task changes styling or behavior.
- [ ] Any external DashLite example was verified against the current repository before use.

## Layout

- [ ] The feature uses the existing application/DashLite shell.
- [ ] I did not recreate `nk-content`, header, sidebar, or navigation unnecessarily.
- [ ] `DashliteLayoutState` still owns variant/body/sidebar/header behavior.
- [ ] I did not assume every installation uses `demo3` unless the requirement is explicitly variant-specific.
- [ ] Dark mode and RTL behavior are not broken by hardcoded layout state.

## Components

- [ ] Standard cards use existing DashLite card primitives where appropriate.
- [ ] Grid/spacing uses existing Bootstrap/DashLite utilities where possible.
- [ ] Existing Blade components were reused when they already solve the requirement.
- [ ] Modals/interactions use Bootstrap 5-compatible markup.

## Forms

- [ ] Existing form components/patterns were inspected.
- [ ] Label/control associations remain correct.
- [ ] Outlined controls use the expected DashLite DOM structure where applicable.
- [ ] Laravel validation feedback remains visible.
- [ ] Select2 or other plugins are not initialized twice.

## Tables

- [ ] I inspected the existing Krud/DataTables implementation when relevant.
- [ ] DataTables is only used when its behavior is required.
- [ ] Actions are non-orderable/searchable where appropriate.
- [ ] Mobile table/filter behavior remains usable.

## JavaScript

- [ ] I checked `NioApp` before implementing a duplicate toggle/helper.
- [ ] I did not import another jQuery instance.
- [ ] I used Bootstrap 5 `data-bs-*` conventions for new markup.
- [ ] Feature JS is scoped to the page/component where possible.
- [ ] Existing shared/plugin initialization is not duplicated.

## Styling

- [ ] Existing DashLite classes were considered before custom CSS.
- [ ] Existing Bootstrap utilities were considered before custom CSS.
- [ ] New CSS is scoped to the feature/component.
- [ ] Core `.nk-*`, `.card`, `.btn`, form, modal, or table rules are not globally overridden accidentally.
- [ ] Dark mode and theme surfaces were considered.
- [ ] Responsive behavior was verified.

## Icons

- [ ] NioIcon is used for normal application actions where possible.
- [ ] New `ni-*` classes were verified rather than guessed.
- [ ] No new icon dependency was added without a real gap in the existing stack.

## Final quality

- [ ] The implementation looks native to this Kitukizuri DashLite integration, not like generic Bootstrap pasted inside it.
- [ ] The smallest compatible change was made.
- [ ] New abstractions were added only when repetition or reuse justifies them.
- [ ] Legacy behavior was preserved unless the task explicitly required refactoring.
