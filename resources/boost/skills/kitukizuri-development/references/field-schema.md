# Field schema

`setCampo()` is implemented in `src/App/Traits/Krud/FieldTrait.php` and is also exposed through `setField()` and `setControl()`.

## Accepted input keys

The static allowed list in the analyzed snapshot contains:

| Key | Alias | Meaning |
|---|---|---|
| `campo` | `field` | Database/query field |
| `campoReal` | `realField` | Real attribute used for input/storage when `campo` is computed or qualified |
| `nombre` | `name` | User-facing label |
| `tipo` | `type` | Field type |
| `column` | — | Two collection columns used as option ID and label |
| `columnClass` | — | Form-grid CSS class; default `col-md-6` |
| `collect` | — | Collection used by `combobox` or `select2` |
| `columnParent` | — | Parent-key column for values stored in a related table |
| `dependencies` | — | Field dependencies normalized by helper functions |
| `edit` | — | Whether the field appears in edit; default `true` |
| `enumArray` | — | Intended enum values; implementation casing is inconsistent |
| `filepath` | — | Destination for `file` type |
| `format` | — | Type-dependent display/storage format |
| `htmlType` | — | Overrides generated HTML input type |
| `htmlAttr` | — | HTML attributes; array/string converted to Collection |
| `inputClass` | — | Additional input CSS classes |
| `inputId` | — | Explicit HTML/input identifier |
| `multiple` | — | Listed as accepted, but multiple behavior is primarily driven by `htmlAttr.multiple` |
| `show` | — | Table visibility; default `true`; `soft` has special external-filter behavior |
| `target` | — | Intended URL target metadata |
| `unique` | — | Performs a create-only existence check in `store()` |
| `value` | — | Default/fixed value |
| `validation` | — | Laravel validation rule string or array |

In chart mode, `setCampo()` additionally accepts:

- `isFilter`
- `isCategory`

Call `setView('chart')` before defining those fields.

## Generated/internal keys

`setCampo()` adds or normalizes values such as:

- `inputName`
- `inputId`
- `nombre`
- `tipo`
- `edit`
- `show`
- `decimales`
- `format`
- `unique`
- `input`
- `component`
- `columnClass`
- `inputClass`
- `editClass`
- `collect`
- `value`
- `validation`
- `htmlType`
- `htmlAttr`
- `options`

Do not pass generated keys unless they are also present in the accepted input list.

## Supported field types

The analyzed `fieldTypes` list contains:

| Type | Default component/input | Observed behavior |
|---|---|---|
| `string` | text input | Default type |
| `text` | text input | Same fallback component behavior as string |
| `textarea` | `krud-textarea` | Multiline value |
| `numeric` | number input | Removes commas before persistence |
| `bool` | checkbox | Stores boolean; renders badge in table |
| `date` | date input | Adds `datetimepicker`; normalizes during store |
| `datetime` | text input by fallback | Adds `datetimepicker`; formatted in table |
| `dateRange` | `krud-daterange` | Used as chart filter |
| `combobox` | `krud-select` | Requires collection/options |
| `select2` | `krud-select2` | Requires collection/options; supports `htmlAttr.multiple` |
| `enum` | `krud-select` | Intended to use enum values; current key casing is inconsistent |
| `password` | `krud-password` | Hashes non-empty submitted password |
| `hidden` | hidden input | Requires a value; special value `userid` maps to authenticated ID |
| `image` | file input | Stores an image data URI in the model |
| `file64` | file input | Uses the same base64 branch as `image` |
| `file` | file input | Intended to move a file to `public_path() + filepath`; current implementation has defects |
| `icono` | text input fallback | Adds `iconpicker` template |
| `url` | text input fallback | Converts value into an anchor in table output |
| `table` | `krud-table` | Reads/writes auxiliary table values through helper models |
| `h1` | `krud-title` | Display-only title; field key not required |
| `h2` | `krud-title` | Display-only title |
| `h3` | `krud-title` | Display-only title |
| `h4` | `krud-title` | Display-only title |
| `strong` | `krud-title` | Display-only emphasized title |

## Basic examples

### Text field

```php
$this->setField([
    'name' => 'Nombre',
    'field' => 'nombre',
    'validation' => ['required', 'string', 'max:150'],
]);
```

### Boolean field

```php
$this->setCampo([
    'nombre' => 'Activo',
    'campo' => 'activo',
    'tipo' => 'bool',
]);
```

### Computed field

A query expression requires a real field alias:

```php
$this->setField([
    'name' => 'URL',
    'field' => DB::raw('SUBSTRING(url, 1, 50)'),
    'realField' => 'url',
    'htmlAttr' => ['disabled' => true],
]);
```

### Combobox

```php
$this->setField([
    'name' => 'Empresa',
    'field' => 'empresaid',
    'type' => 'combobox',
    'collect' => Empresa::query()
        ->select('empresaid as id', 'nombre as value')
        ->orderBy('nombre')
        ->get(),
    'show' => false,
]);
```

### Select2 multiple relation

This pattern exists in `UsuariosController`:

```php
$this->setField([
    'name' => 'Roles',
    'field' => 'usuarioRol.rolid',
    'type' => 'select2',
    'collect' => Rol::select('rolid as id', 'nombre as value')->get(),
    'columnParent' => 'usuarioid',
    'show' => false,
    'htmlAttr' => [
        'multiple' => true,
    ],
]);
```

Because the dotted table name differs from the main model table, `columnParent` is required and persistence is routed through `SelectValues`.

### Parent field

```php
$this->setParents('empresaid', 'parent', true);
```

In this example, the request's `parent` value is associated with model attribute `empresaid`.

## Collection requirements

For `combobox` and `select2`:

- `collect` must be non-empty to build options without recording an error.
- Default option columns are `id` and `value`.
- A custom `column` must be an array of exactly two entries.
- Option rows are converted with `toArray()` and then `array_values()`.
- The field's `show` is forced to `false` after successful option construction.

## Qualified fields

For a string field containing a dot, such as `r.nombre`, `setCampo()` derives `campoReal = nombre`. This allows the query to select a qualified field while using the final segment in form/storage metadata.

For `Expression` instances, the method cannot infer the real field and requires `campoReal` or `realField`.

## Multiple-value formats

The implementation uses `format` values:

- `json` for values stored in the main model attribute.
- `table` for values stored in an auxiliary table.

The current source contains overlapping logic that can force `table` even after selecting `json`. Test multiple selection persistence before relying on automatic format inference.

## Validation behavior

A field's `validation` value is copied into the controller's validation map under `inputName`. `store()` calls:

```php
$request->validate($this->validations);
```

The `unique` flag is separate from Laravel validation. It performs only a create-time model existence query and does not handle update exclusions or race conditions. Prefer Laravel's `Rule::unique()` for robust uniqueness when possible.

## Safety notes

- `htmlAttr.disabled` prevents browser submission. Ensure the storage code does not depend on that value being present.
- `show => false` controls table visibility, not authorization.
- Raw SQL expressions must not interpolate user input.
- File types require dedicated tests because the analyzed implementation has confirmed variable and persistence inconsistencies.
- Enum behavior requires a fix or a regression test before use.
