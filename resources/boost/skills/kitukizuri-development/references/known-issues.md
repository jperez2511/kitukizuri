# Known issues and risk register

These findings come from static inspection of the uploaded snapshot. They are not fixes, and some require runtime tests to determine full impact.

## High-priority confirmed inconsistencies

### Configuration key casing/name mismatches

- Config defines `preUi`; code mostly reads `prevUi`.
- Config defines `storeMSG`; `getStoreMSG()` reads `storemsg`.
- `buildMsg()` contains `config('kitikizuri.preUi')`, with a misspelled namespace.

Impact: UI mode and default messages may not behave as configured.

### Enum key casing mismatch

`FieldTrait::$fieldOptions` accepts `enumArray`, while validation and calendar views read `enumarray`.

Impact: enum fields can trigger undefined-key errors or fail to render.

### File persistence uses an undefined variable

In `Krud::store()`, the `file` branch reads `$c['filepath']` instead of `$campo['filepath']`.

It also assigns the moved `UploadedFile` object to `$valor` instead of clearly storing the generated filename/path.

Impact: file uploads are not safe to use without correction and tests.

### Date format swaps minutes and seconds

`toDateMysql()` formats with:

```php
Y-m-d H:s:i
```

The conventional order is `Y-m-d H:i:s`.

Impact: stored time portions can be wrong.

### `searchBy()` array validation is reversed

The comment says associative arrays are invalid, but the condition accepts an associative array and records an error for a normal indexed list.

Impact: `searchBy(['name', 'email'])` may not configure search as intended.

### Calendar public option check is internally inconsistent

`setCalendarView()` checks `in_array('public', $this->viewOptions)` and then reads `$this->viewOptions['public']`. For the documented associative form `['public' => true]`, `in_array()` searches values and does not find the string key.

Impact: public calendar permissions may not be enabled.

## Permission-related findings

### `KituKizuri::getPermisos()` passes an array to the route lookup

The method performs:

```php
$nombreRuta = explode('.', $ruta);
Modulo::where('ruta', $nombreRuta)
```

Other permission methods use the first route segment. This likely needs `$nombreRuta[0]`.

### Supplied user ID is ignored

`KituKizuri::getPermisos($uid = null, ...)` and `Krud::getPermisos($id)` accept a user ID, but the core query uses `Auth::id()`.

Impact: callers cannot actually query permissions for another supplied user.

### UI permission removal is not authorization

`removePermisos()` removes actions from rendered UI lists, but routes remain active.

Impact: custom route protection is still required.

## Field/persistence findings

### Select type naming is inconsistent

The declared field type is `combobox`, while `store()` checks `comobox` in some branches and also checks `select`, which is not in the declared field type list.

### `htmlAttr` may be null during missing-field processing

The second field loop calls `$campo['htmlAttr']->has('multiple')` for select-like types without always confirming `htmlAttr` is a Collection.

Impact: requests that omit select fields can raise errors in some definitions.

### Multiple format inference overwrites JSON with table

In `setCampo()`, one branch can set `format = 'json'` and later unconditionally set `format = 'table'`.

Impact: multiple values intended for the main table may be redirected to auxiliary-table handling.

### `decimales` is generated but not accepted or used

`setCampo()` defaults a `decimales` key after validating input, but `decimales` is absent from the allowed input list and no other source usage was found.

Impact: numeric decimal configuration appears incomplete.

### `unique` is create-only and race-prone

The custom existence check runs only when `id == 0`, does not ignore the current record on update, and is not backed by a database constraint in this logic.

## Query findings

### Dynamic forwarding loses terminal return values

`__call()` commonly returns `$this` after a model/builder call. Configuration methods chain, but terminal methods such as `first`, `count`, or `exists` should not be assumed to return their normal result.

### External data merge condition looks inverted

`getData()` checks whether the target external column on the current row is non-empty before replacing it with external data. This may prevent populating an initially absent/empty column.

Requires a focused runtime test before correction.

### Debug SQL helper terminates execution

`getSql()` calls `dd()`. It must not be used in production request paths.

## Runtime/dependency risks

### Composer dependencies are incomplete

Only PHP is declared, while source code uses Laravel, Carbon, MongoDB Laravel, Livewire, Laravel Prompts, and host application classes.

Impact: dependency resolution cannot guarantee a working installation.

### Package depends on host application classes

`Krud` extends `App\Http\Controllers\Controller`, and `Krud.php` imports `App\Models\Municipio`.

Impact: package isolation and standalone testing are weakened.

### Global alias registration is duplicated

Aliases exist in Composer package metadata and in provider boot code.

Impact: upgrade/debugging complexity; explicit imports are safer for new code.

### No automated tests were included

Impact: behavior and framework compatibility cannot be proven from this snapshot.

## Additional suspicious literals

- `SetLang` reads user property `defalut_lang`; verify whether the database column intentionally uses that spelling.
- `KituKizuri::permiso()` calls `dd()` when a route name lacks exactly two segments.
- `destroy()` returns caught database exceptions directly.
- `store()` catches `Mockery\Exception`, not the general exception hierarchy normally expected for application errors.
- `buildMsg()` and `store()` mix JSON responses, redirects, and flash state depending on UI mode.

## Recommended fix order

1. Add regression tests around config, fields, permissions, and persistence.
2. Normalize config keys with temporary backward-compatible fallbacks.
3. Correct enum, file, date, and select handling.
4. Correct permission route parsing and user-ID semantics.
5. Replace unsafe terminal `__call()` usage or redesign forwarding.
6. Add explicit Composer constraints and a Testbench CI matrix.
7. Migrate consuming controllers to explicit `Krud` imports before removing aliases.
