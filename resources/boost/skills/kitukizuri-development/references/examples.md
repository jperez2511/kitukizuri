# Verified usage examples

These examples are based on patterns found in the package source and adjusted to avoid known defects.

## Basic CRUD controller

```php
<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Icebearsoft\Kitukizuri\Krud;

class EmpresaController extends Krud
{
    public function __construct()
    {
        $this->setModel(new Empresa());
        $this->setTitle('Empresas');

        $this->setField([
            'name' => 'Nombre',
            'field' => 'nombre',
            'validation' => ['required', 'string', 'max:150'],
        ]);

        $this->setField([
            'name' => 'NIT',
            'field' => 'nit',
            'validation' => ['nullable', 'string', 'max:30'],
        ]);

        $this->setField([
            'name' => 'Activa',
            'field' => 'activo',
            'type' => 'bool',
        ]);
    }
}
```

Route:

```php
use App\Http\Controllers\EmpresaController;
use Illuminate\Support\Facades\Route;

Route::resource('empresas', EmpresaController::class)
    ->middleware(['auth', 'kitukizuri', 'kmenu']);
```

For Kitukizuri module authorization, the route resource name must match the module route stored in the module tables/seeders.

## Joined display field and editable relation key

Pattern based on `UsuarioRolController`:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\UsuarioRol;
use Icebearsoft\Kitukizuri\Krud;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class UsuarioRolController extends Krud
{
    public function __construct(Request $request)
    {
        try {
            $usuarioId = Crypt::decrypt($request->query('parent'));
        } catch (DecryptException) {
            $usuarioId = $request->query('parent');
        }

        $this->setModel(new UsuarioRol());
        $this->setTitle('Roles');

        $this->setField([
            'name' => 'Rol',
            'field' => 'r.nombre',
            'edit' => false,
        ]);

        $this->setField([
            'name' => 'Rol',
            'field' => 'usuarioRol.rolid',
            'type' => 'combobox',
            'collect' => Rol::select('rolid as id', 'nombre as value')->get(),
            'show' => false,
        ]);

        $this->setJoin('roles as r', 'r.rolid', '=', 'usuarioRol.rolid');
        $this->setWhere('usuarioid', '=', $usuarioId);
        $this->setParents('usuarioid', 'parent', true);
    }
}
```

## Select2 multiple relation

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

Add a feature test covering create, edit, removal of all selections, and relation-table synchronization before using this pattern in a critical module.

## Computed read-only field

```php
use Illuminate\Support\Facades\DB;

$this->setField([
    'name' => 'URL',
    'field' => DB::raw('SUBSTRING(url, 1, 50)'),
    'realField' => 'url',
    'htmlAttr' => [
        'disabled' => true,
    ],
]);
```

`realField` is mandatory because a database expression cannot be mapped automatically to a model attribute.

## Custom row button

```php
$this->setButton([
    'name' => 'Sucursales',
    'url' => route('sucursales.index').'?parent={id}',
    'class' => 'outline-primary',
    'icon' => 'fa-duotone fa-solid fa-store',
]);
```

The current table renderer replaces `{id}` with an encrypted primary key and prefixes the class with `btn btn-`.

Equivalent route-name form:

```php
$this->setButton([
    'name' => 'Sucursales',
    'routeName' => 'sucursales.index',
    'id' => true,
    'class' => 'outline-primary',
    'icon' => 'fa-duotone fa-solid fa-store',
]);
```

## Query wrappers

```php
$this->setJoin('roles as r', 'r.id', '=', 'users.role_id');
$this->setWhere('users.activo', true);
$this->setWhereIn('users.estado', ['activo', 'pendiente']);
$this->setOrderBy('users.nombre', 'asc');
```

Closure filter:

```php
$this->setWhere(function ($query): void {
    $query
        ->whereNull('users.deleted_at')
        ->where(function ($nested): void {
            $nested
                ->where('users.activo', true)
                ->orWhere('users.es_admin', true);
        });
});
```

## Dynamic builder forwarding

The source permits this:

```php
$this->leftJoin('roles as r', 'r.id', '=', 'users.role_id');
$this->orderBy('users.nombre');
```

Use it for builder configuration only. Do not write this and expect a scalar:

```php
$count = $this->count(); // Current __call semantics may return the controller.
```

Use the model/query directly when a terminal result is required.

## Post-save hook

```php
$this->setStoreFunction(function ($cliente): void {
    activity()
        ->performedOn($cliente)
        ->log('Cliente guardado desde Krud');
});
```

The hook runs after the model save. The base `store()` does not create an explicit transaction, so add transaction handling in an override when atomicity is required.

## Custom fields from configuration

```php
// config/kitukizuri.php
'companiesCustomField' => [
    [
        'name' => 'Sector',
        'field' => 'sector',
        'validation' => ['nullable', 'string', 'max:100'],
    ],
],
```

The package `EmpresasController` loops through this array and calls `setCampo()` for each item.

## Safe override pattern

When overriding a resource method, preserve parent behavior explicitly:

```php
public function show($id, Request $request)
{
    if (! $request->ajax()) {
        return $this->edit($id, $request);
    }

    return parent::show($id, $request);
}
```

This pattern is used by the package log controller.
