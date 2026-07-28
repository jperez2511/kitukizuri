---
name: kitukizuri-development
description: Use when developing, extending, debugging, testing, upgrading, or documenting the icebearsoft/kitukizuri Laravel package or an application that uses its Krud controllers, field definitions, queries, permissions, middleware, views, configuration, publishing tags, or Artisan commands.
---

# Kitukizuri development

## Purpose

Work against the actual `icebearsoft/kitukizuri` source code and preserve its public contracts. Do not invent methods from historical documentation, examples, or generic Laravel knowledge.

The package is a Laravel application foundation that combines:

- `Krud`, a base resource controller for CRUD tables, forms, calendars, and charts.
- Role, module, permission, company, branch, menu, log, and tenant functionality.
- Blade views and components under the `krud`, `kitukizuri`, `krud_prev`, and `kitukizuri_prev` namespaces.
- Installation, update, module generation, UI, JavaScript, Docker, logging, and tenant commands.

## Source-of-truth order

When sources disagree, use this order:

1. Installed package source for the current project.
2. Automated tests that exercise the behavior.
3. The consuming application's working controllers and routes.
4. Files in `references/` for this skill.
5. Package README and historical training snippets.

Report discrepancies instead of silently choosing the older behavior.

## Required workflow

Before generating or changing code:

1. Read the root `composer.json` and identify the package snapshot or installed version.
2. Locate the relevant implementation in `src/`.
3. For `Krud`, inspect both `src/Krud.php` and the traits under `src/App/Traits/Krud/`.
4. Search existing controllers for a working example with the same field, relation, view, or query pattern.
5. Verify method visibility. Most configuration methods are `protected` and are intended for subclasses.
6. Check `references/known-issues.md` before copying a pattern.
7. Make the smallest compatible change.
8. Run syntax checks and available tests.
9. Regenerate the API inventory when a `Krud` method changes:

```bash
php resources/boost/skills/kitukizuri-development/scripts/generate-api-index.php
```

10. Validate the skill and PHP syntax:

```bash
bash resources/boost/skills/kitukizuri-development/scripts/validate-skill.sh
```

## Package identity

- Composer package: `icebearsoft/kitukizuri`
- Namespace: `Icebearsoft\Kitukizuri`
- Base CRUD controller: `Icebearsoft\Kitukizuri\Krud`
- Core permission class: `Icebearsoft\Kitukizuri\KituKizuri`
- Container binding: `kitukizuri`
- Facade class: `Icebearsoft\Kitukizuri\Facades\Kitukizuri`
- Configuration: `src/config/kitukizuri.php`

The package currently declares global aliases for `Krud` and `Kitukizuri` in Composer metadata and also registers them with `AliasLoader` in the service provider.

For new controllers, prefer an explicit import:

```php
use Icebearsoft\Kitukizuri\Krud;
```

Do not change `Krud` into a facade. It is an extendable controller class.

## Krud composition

`Krud` extends the consuming application's `App\Http\Controllers\Controller` and composes behavior through traits:

```text
Krud
├── QueryBuilderTrait
├── UiTrait
│   ├── FieldTrait
│   └── ChartTrait
├── HelpTrait
├── ChartTrait
└── TableTrait
```

Read `references/krud-api.md` before creating or changing a controller.
Read `references/field-schema.md` before adding a field.

## Controller generation rules

When implementing a CRUD module:

1. Import `Krud` explicitly.
2. Call `setModel()` before methods that depend on the model table, key, or query builder.
3. Set a title with `setTitulo()` or `setTitle()`.
4. Define each field with `setCampo()` or `setField()` using the verified schema.
5. Add joins and filters after the model has initialized the query builder.
6. Prefer package wrapper methods such as `setJoin()` and `setOrderBy()` when they express the intent clearly.
7. Remember that unknown method calls may be forwarded by `__call()` to Eloquent's builder or model. Verify the receiving API before using that behavior.
8. Add custom action buttons only with a valid URL or route.
9. Keep authorization server-side. Hiding a button does not protect a route.
10. Add or update a named resource route and the module/permission seed data when the module must participate in Kitukizuri authorization.

Minimal verified pattern:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Icebearsoft\Kitukizuri\Krud;

class ClienteController extends Krud
{
    public function __construct()
    {
        $this->setModel(new Cliente());
        $this->setTitle('Clientes');

        $this->setField([
            'name' => 'Nombre',
            'field' => 'nombre',
            'validation' => ['required', 'string', 'max:150'],
        ]);
    }
}
```

Do not assume fluent returns from `setModel`, `setField`, `setWhere`, or UI setters; current implementations mutate controller state and generally return `void`.

## Field rules

- Use either Spanish keys (`nombre`, `campo`, `tipo`, `campoReal`) or their verified English aliases (`name`, `field`, `type`, `realField`). Avoid mixing styles without a reason.
- A raw `Expression` field requires `campoReal` or `realField`.
- A dotted field such as `roles.nombre` derives `nombre` as its real field unless overridden.
- `combobox` and `select2` require a collection containing `id` and `value`, or a two-element `column` mapping.
- A multiple relation stored in another table requires `columnParent`.
- `htmlAttr` is converted to an `Illuminate\Support\Collection` internally.
- Chart-only keys `isFilter` and `isCategory` are accepted only after `setView('chart')` has been called.

Do not use undocumented field keys merely because they appear in a Blade template. Verify that `FieldTrait::$fieldOptions` accepts them.

## Query rules

- `setModel()` initializes `$queryBuilder` with `$model->newQuery()`.
- `setJoin`, `setLeftJoin`, `setWhere`, `setWhereIn`, `setOrWhere`, `setOrderBy`, and `setGroupBy` mutate that builder.
- `__call()` forwards recognized calls to the Eloquent builder and then to the model, returning the controller for chaining.
- Do not assume the returned value of a forwarded terminal method such as `count()`, `first()`, or `exists()` is preserved. The current forwarding implementation returns the controller in several paths.
- `getSql()` terminates execution with `dd()` and is a debugging helper, not a production API.

## Resource lifecycle

The base controller implements:

```text
index  -> chooses table/calendar/chart view
show   -> returns DataTables or chart JSON
create -> delegates to edit(encrypted zero)
edit   -> decrypts ID and renders the form
store  -> validates, normalizes fields, saves model and relation values
destroy-> decrypts ID and deletes the model
```

IDs passed through the default UI are encrypted with Laravel `Crypt`. Preserve this contract unless the application intentionally replaces the UI and routes together.

## Permissions and middleware

Internal package routes use middleware:

```text
web -> auth -> kitukizuri -> kmenu
```

- `kitukizuri` validates module availability and route permission.
- `kmenu` builds the session menu.
- `klang` is registered but not applied to the internal route group.
- `Tenant` is pushed globally by the package provider and exits early unless `multiTenants` is `true`.

Never rely only on the list of permissions sent to a Blade view. Protect custom routes with middleware, policies, gates, or explicit server-side checks.

## Views and components

Registered view namespaces:

- `krud`
- `kitukizuri`
- `krud_prev`
- `kitukizuri_prev`

Registered Blade component aliases include:

- `krud-input`, `krud-table`, `krud-title`, `krud-select`, `krud-select2`
- `krud-password`, `krud-textarea`, `krud-daterange`, `krud-index-tree`
- equivalent `krud-prev-*` aliases

A Livewire component is conditionally registered as `krud.advancedOptions` when Livewire exists.

## Commands and publishing

Read `references/commands.md` before invoking an installation or update command. Some commands modify controllers, models, routes, seeders, configuration, frontend files, or Docker files.

Publishing tags currently registered by the provider:

- `krud-migrations`
- `krud-seeders`
- `krud-error`
- `krud-views`
- `krud-app`
- `krud-config`
- `krud-public`

## Compatibility discipline

The analyzed snapshot declares PHP `^8.0` but does not declare Laravel, Illuminate, Livewire, MongoDB, or other framework package constraints. Do not claim a Laravel version is supported solely because the package installs.

For Laravel upgrades:

1. Prefer explicit class imports over global aliases.
2. Verify provider boot signatures and route registration.
3. Run `composer dump-autoload` and `php artisan package:discover --ansi`.
4. Exercise a real resource route: index, DataTables AJAX, create, edit, store, and destroy.
5. Test both MySQL and PostgreSQL code paths if both are supported by the application.

Read `references/compatibility.md` for the observed constraints.

## Completion criteria

A change is complete only when:

- It uses methods that exist in the analyzed or installed source.
- It respects method visibility and initialization order.
- It preserves aliases, routes, view namespaces, config keys, and publishing tags unless a migration is provided.
- Validation and authorization are server-side.
- Relevant syntax checks and tests pass.
- `api-index.generated.md` is current after API changes.
- Public behavior changes are reflected in the references.
