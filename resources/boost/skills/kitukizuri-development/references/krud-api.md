# Krud API reference

This is a curated reference for the analyzed source snapshot. The generated inventory in `api-index.generated.md` is the drift detector; the source is authoritative.

## API conventions

- Most setup methods are `protected`: call them from a `Krud` subclass, normally in its constructor.
- Most setters mutate internal state and return no value.
- English aliases are wrapper methods, not separate implementations.
- `__call()` can forward unknown calls to the Eloquent query builder or model; those forwarded calls are not native Kitukizuri methods.

## Model and query initialization

### `setModel($model)`

```php
protected function setModel($model)
```

Source: `src/App/Traits/Krud/QueryBuilderTrait.php:36`

Sets:

- `$model`
- `$queryBuilder = $model->newQuery()`
- `$tableName = $model->getTable()`
- `$keyName = $model->getKeyName()`

Call it before field definitions that inspect the model table or before query methods.

### `getKeyName()`

```php
protected function getKeyName()
```

Source: `src/App/Traits/Krud/QueryBuilderTrait.php:50`

Returns the primary key name captured by `setModel()`.

### `__call($method, $args)`

```php
public function __call($method, $args)
```

Source: `src/App/Traits/Krud/QueryBuilderTrait.php:62`

Forwarding order:

1. Current controller method, when `method_exists()` succeeds.
2. Current Eloquent query builder.
3. Model dynamic method.
4. Throws `BadMethodCallException` when unresolved.

When a builder method is found, the builder is replaced with the method result and the controller is returned. This supports configuration chains, but it does **not** safely preserve terminal scalar/model results. Avoid using forwarded terminal calls when their return value matters.

Example found in the package:

```php
$this->leftJoin('users as u', 'u.id', '=', 'logs.id_user');
$this->orderBy('id_log', 'desc');
```

Prefer `setLeftJoin()` and `setOrderBy()` in new Kitukizuri-specific code when possible.

## Titles, layout, and view mode

### `setTitulo($titulo)` / `setTitle($title)`

```php
protected function setTitulo($titulo)
protected function setTitle($title)
```

Sources:

- `src/App/Traits/Krud/UiTrait.php:104`
- `src/App/Traits/Krud/UiTrait.php:110`

Sets the title used by index and edit views. `setTitle()` delegates to `setTitulo()`.

### `setLayout($layout)` / `getLayout()`

```php
protected function setLayout($layout)
protected function getLayout()
```

Sources:

- `src/App/Traits/Krud/UiTrait.php:45`
- `src/App/Traits/Krud/UiTrait.php:56`

`getLayout()` returns the controller override or `config('kitukizuri.layout')`.

### `setView($view, $options = [])`

```php
protected function setView($view, $options = [])
```

Source: `src/App/Traits/Krud/UiTrait.php:124`

Allowed views:

- `table`
- `calendar`
- `chart`

Calendar options are validated against the `public` key. See `known-issues.md` before relying on the public-calendar option.

Call `setView('chart')` before adding fields with `isFilter` or `isCategory`, because those keys are added to the allowed field schema only in chart mode.

### `setCalendarDefaultView($view)`

```php
protected function setCalendarDefaultView($view)
```

Source: `src/App/Traits/Krud/UiTrait.php:149`

Allowed values: `day`, `week`, `month`.

### `setTemplate($templates)`

```php
protected function setTemplate($templates)
```

Source: `src/App/Traits/Krud/UiTrait.php:89`

Appends template/library identifiers. The default list contains `datatable`. Date and icon fields add template identifiers automatically.

### `help()`

```php
protected function help()
```

Source: `src/App/Traits/Krud/UiTrait.php:32`

Switches `index()` to the package training/help view.

## Store messages

### `setStoreMSG($msg)` / `getStoreMSG()`

```php
protected function setStoreMSG($msg)
protected function getStoreMSG()
```

Sources:

- `src/App/Traits/Krud/UiTrait.php:76`
- `src/App/Traits/Krud/UiTrait.php:66`

The controller override is reliable. The config fallback currently reads a differently cased key than the distributed config file; use `setStoreMSG()` until that mismatch is corrected.

## Fields

### `setCampo($params)`

```php
protected function setCampo($params)
```

Source: `src/App/Traits/Krud/FieldTrait.php:100`

Validates and normalizes a field definition, then appends it to `$campos`.

Key normalization includes:

```text
type -> tipo
field -> campo
realField -> campoReal
name -> nombre
```

It derives `inputName`, `inputId`, component name, HTML type, column classes, visibility, editability, validation, and select options.

Read `field-schema.md` for the full contract and known edge cases.

### `setField($params)` / `setControl($params)`

```php
protected function setField($params)
protected function setControl($params)
```

Sources:

- `src/App/Traits/Krud/FieldTrait.php:271`
- `src/App/Traits/Krud/FieldTrait.php:277`

Both delegate to `setCampo()`.

### `setValidationItem($name, $rule)`

```php
protected function setValidationItem($name, $rule)
```

Source: `src/App/Traits/Krud/UiTrait.php:168`

Adds a Laravel validation rule to the internal `$validations` array. `setCampo()` calls this automatically when the field contains `validation`.

### `setParents($nombre, $value, $editable = null)`

```php
protected function setParents($nombre, $value, $editable = null)
```

Source: `src/App/Traits/Krud/FieldTrait.php:291`

Adds parent metadata:

```php
[
    'nombre' => $nombre,
    'value' => $value,
    'editable' => $editable === true,
]
```

Used to propagate parent values through edit/store and rebuild return query strings.

### `setParentId($text)`

```php
public function setParentId($text)
```

Source: `src/Krud.php:127`

Sets the model column that receives the encrypted `parent` value when storing a child record.

## Query configuration

### `searchBy($column)`

```php
protected function searchBy($column)
```

Source: `src/App/Traits/Krud/QueryBuilderTrait.php:94`

Configures columns for DataTables global search. A string is normalized to a one-element array.

The current simple-array/associative-array validation is inconsistent with its comment. Prefer a string until the implementation is fixed, or verify the exact behavior with a test.

### `setJoin($tabla, $v1, $operador = null, $v2 = null)`

```php
protected function setJoin($tabla, $v1, $operador = null, $v2 = null)
```

Source: `src/App/Traits/Krud/QueryBuilderTrait.php:163`

Supports:

```php
$this->setJoin('roles as r', 'r.rolid', 'usuarioRol.rolid');
$this->setJoin('roles as r', 'r.rolid', '=', 'usuarioRol.rolid');
```

The three-argument form defaults the operator to `=`.

### `setLeftJoin($tabla, $v1, $operador = null, $v2 = null)`

Source: `src/App/Traits/Krud/QueryBuilderTrait.php:184`

Same argument behavior as `setJoin()`, using `leftJoin()`.

### `setWhere($column, $op = null, $column2 = null)`

Source: `src/App/Traits/Krud/QueryBuilderTrait.php:205`

Accepted forms:

```php
$this->setWhere('activo', true);
$this->setWhere('estado', '!=', 'eliminado');
$this->setWhere(function ($query) {
    $query->where('activo', true)->orWhereNull('deleted_at');
});
```

### `setWhereIn($column, $data, $boolean = 'and', $not = false)`

Source: `src/App/Traits/Krud/QueryBuilderTrait.php:231`

Delegates directly to builder `whereIn()` with all four arguments.

### `setOrWhere($column, $op = null, $column2 = null)`

Source: `src/App/Traits/Krud/QueryBuilderTrait.php:245`

Two arguments default the operator to `=`.

### `setOrderBy($column, $orientation = 'asc')`

Source: `src/App/Traits/Krud/QueryBuilderTrait.php:264`

Delegates to builder `orderBy()`.

### `setGroupBy($column)`

Source: `src/App/Traits/Krud/QueryBuilderTrait.php:277`

Delegates to builder `groupBy()` with one argument.

### `setExternalData($relation, $colName, $data)`

Source: `src/App/Traits/Krud/QueryBuilderTrait.php:119`

Registers an external collection to merge into query results by a relation key.

### `searchInExternalData($colName, $value)`

Source: `src/App/Traits/Krud/QueryBuilderTrait.php:128`

Registers an in-memory equality filter applied after external data processing.

### `getSql()`

Source: `src/App/Traits/Krud/QueryBuilderTrait.php:139`

Interpolates bindings for debugging and calls `dd($sql)`. Never leave it in production execution paths.

### `getData($limit = null, $offset = null)`

Source: `src/App/Traits/Krud/QueryBuilderTrait.php:288`

Protected extension point used by table and chart rendering. It:

1. Selects visible fields.
2. Adds the key column.
3. Counts rows using separate MySQL/SQLite and PostgreSQL paths.
4. Optionally merges external data.
5. Applies in-memory external filtering.
6. Applies offset/limit.
7. Returns `[$data, $count]`.

### `getSelectShow()`

Source: `src/App/Traits/Krud/QueryBuilderTrait.php:353`

Returns fields whose `show` value is exactly `true`. When external filtering is enabled, it also keeps `show === 'soft'` fields and mutates `$campos` to that filtered list.

## Buttons

### `setBoton($params)` / `setButton($params)`

```php
protected function setBoton($params)
protected function setButton($params)
```

Sources:

- `src/App/Traits/Krud/UiTrait.php:233`
- `src/App/Traits/Krud/UiTrait.php:248`

Allowed keys:

- `nombre` / `name`
- `url`
- `class`
- `icon`
- `routeName`
- `id`

When `routeName` exists, the method builds the URL with `route()`. When `id` is `true`, it appends `?parent={id}`.

The table renderer replaces `{id}` with an encrypted key. Under the current renderer, `class` is concatenated after `btn btn-`; values such as `outline-primary` are safer than a complete `btn ...` class string.

### `setBotonDT($params)` / `setButtonDT($params)`

Sources:

- `src/App/Traits/Krud/UiTrait.php:260`
- `src/App/Traits/Krud/UiTrait.php:268`

Allowed keys: `text`, `class`, `action`. Appends a custom DataTables-level button.

### `setDefaultBotonDT($params)` / `setDefaultButtonDT($params)`

Sources:

- `src/App/Traits/Krud/UiTrait.php:280`
- `src/App/Traits/Krud/UiTrait.php:288`

Accepts `['name' => ...]` and stores only the name in the default DataTables button list.

## Embeds and hooks

### `embedIndexView($view, $position, $script = null, $values = [])`

```php
public function embedIndexView($view, $position, $script = null, $values = [])
```

Source: `src/App/Traits/Krud/UiTrait.php:418`

Adds an embedded index-view descriptor.

### `embedEditView($controller, $relation, $request)`

```php
public function embedEditView($controller, $relation, $request)
```

Source: `src/Krud.php:102`

Appends a three-item embedded edit descriptor. The source marks this area as still in development.

### `setStoreFunction(callable $function)`

```php
public function setStoreFunction(callable $function)
```

Source: `src/Krud.php:113`

Registers a callback executed after the primary model and supported relation values are saved:

```php
$this->setStoreFunction(function ($savedModel): void {
    // Post-save action.
});
```

Callbacks execute inside the `store()` try block, but the method does not create an explicit database transaction.

## Permission/UI controls

### `getPermisos($id)`

```php
protected function getPermisos($id)
```

Source: `src/Krud.php:72`

Creates a `KituKizuri` instance and delegates to `getPermisos($id)`. The current core implementation ignores the supplied user ID and reads the authenticated user.

### `removePermisos($permisos)`

```php
protected function removePermisos($permisos)
```

Source: `src/Krud.php:86`

Removes permission names from the lists rendered by the table view. It does **not** remove or secure routes.

## Resource methods

### `index()`

```php
public function index()
```

Source: `src/Krud.php:310`

Returns training/error views when configuration is incomplete; otherwise selects table, calendar, or chart rendering.

### `show($id, Request $request)`

Source: `src/Krud.php:356`

Dispatches to `showTable()` or `showChart()`. Calendar is not present in the dispatch map, so verify route behavior before overriding `show()` for calendar data.

### `create(Request $request)`

Source: `src/Krud.php:377`

Calls `edit(Crypt::encrypt(0), $request)`.

### `edit($id, Request $request)`

Source: `src/Krud.php:389`

Decrypts the ID, loads model values for edits, initializes field values, computes parent query strings, chooses the view/layout, and renders the form.

### `store(Request $request)`

Source: `src/Krud.php:473`

Validates and persists only request fields represented in `$campos`. Handles several field types, parents, external relation values, and store callbacks. Review `known-issues.md` before extending file, enum, or multiple-select behavior.

### `destroy($id, Request $request)`

Source: `src/Krud.php:711`

Decrypts the ID, confirms the record exists, calls model `destroy()`, flashes a message, and returns `1`. A caught query exception is returned directly.

## Utility methods

### `getDefaultPrefix()`

```php
public function getDefaultPrefix()
```

Source: `src/Krud.php:137`

Returns `config('kitukizuri.routePrefix') ?? 'krud'`.

### `getModuloRuta()`

```php
public function getModuloRuta()
```

Source: `src/Krud.php:288`

Builds the module index URL by combining route-name and URI segments, then calls `route(...'.index')`.

### `toDateMysql($date)`

Source: `src/Krud.php:746`

Converts slashes to hyphens and formats the parsed date. The current format string is suspicious; see `known-issues.md`.

### `mesesEntreFechas($aFechaInicio, $aFechaFin)`

Source: `src/Krud.php:762`

Creates two Carbon instances and returns `$ff->diffInMonths($fi)`.

## Internal rendering methods

These protected methods are implementation details and should usually be overridden only with tests:

| Method | Source | Purpose |
|---|---|---|
| `setCalendarView($prefix, $layout)` | `UiTrait.php:293` | Render calendar shell |
| `setTableView($prefix, $layout)` | `UiTrait.php:328` | Render table shell |
| `setChartView($prefix, $layout)` | `UiTrait.php:380` | Render chart shell |
| `showTable($id, $request)` | `TableTrait.php:12` | Build DataTables JSON |
| `showChart($id, $request)` | `ChartTrait.php:17` | Build chart series JSON |
| `transformData($data, $prefix = null)` | `UiTrait.php:181` | Escape and format table values |
| `buildMsg($type, $msg)` | `UiTrait.php:397` | Build JSON or legacy flash response |
| `allowed($params, $allowed, $badType)` | `HelpTrait.php:40` | Record schema errors |
| `showErrors($error)` | `HelpTrait.php:65` | Render training error view |
