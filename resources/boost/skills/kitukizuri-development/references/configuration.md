# Configuration reference

Distributed configuration file: `src/config/kitukizuri.php`.

## Declared keys

| Key | Default | Purpose |
|---|---:|---|
| `multiTenants` | `false` | Enables host-based runtime tenant connection replacement |
| `routePrefix` | `'krud'` | Prefix for package routes |
| `layout` | `'layouts.app'` | Default Krud layout |
| `preUi` | `false` | Declared legacy/current UI toggle; code mostly reads `prevUi` instead |
| `dashliteBodyClass` | `'npc-default has-apps-sidebar has-sidebar'` | Body classes for DashLite layout |
| `dashliteVariant` | `'demo3'` | Packaged DashLite SCSS variant |
| `dark` | `false` | Dark UI flag |
| `darkSideBar` | `false` | Dark sidebar flag |
| `vBootstrap` | `''` | Bootstrap version hint |
| `edit` | Font Awesome pencil classes | Edit icon |
| `delete` | Font Awesome trash classes | Delete icon |
| `options` | Font Awesome grid classes | Options icon |
| `classBtnEdit` | `'btn-sm btn-outline-primary'` | Edit button CSS |
| `classBtnDelete` | `'btn-sm btn-outline-danger'` | Delete button CSS |
| `classBtnOptions` | `'btn-sm btn-outline-warning'` | Options button CSS |
| `dtBtnAdd` | `'btn btn-outline-success'` | DataTables add button CSS |
| `dtBtnLiner` | `'btn btn-outline-secondary'` | DataTables secondary/linear button CSS |
| `storeMSG` | `'Datos guardados exitosamente.'` | Intended default save message; code reads lowercase `storemsg` |
| `badge` | `'badge bg'` | Badge class prefix |
| `iconFormat` | `'<i {{icono}}></i>'` | Menu icon template |
| `menu` | nested array | Menu element classes and markup templates |

## Optional controller extension keys

Commented examples define these supported extension points:

```php
'userCustomField' => [
    ['nombre' => 'NIT', 'campo' => 'nit'],
],

'sucursalesCustomField' => [
    ['nombre' => 'Código', 'campo' => 'codigo'],
],

'companiesCustomField' => [
    ['name' => 'Sector', 'field' => 'sector'],
],

'companiesCustomButton' => [
    [
        'name' => 'Detalle',
        'url' => '/empresa/{id}/detalle',
        'icon' => 'fa fa-eye',
        'class' => 'outline-primary',
    ],
],
```

Package controllers read these keys and append each definition through `setCampo()` or `setBoton()`.

## Nested menu keys read by the package

- `menu.ul.id`
- `menu.ul.class`
- `menu.li-parent.class`
- `menu.li-parent.layout`
- `menu.li-parent.layout-without-son`
- `menu.li-jr.class`
- `menu.li-jr.layout`
- `menu.ul-jr`
- `menu.ul-jr-divStyle`

Preserve placeholder tokens such as `{{url}}`, `{{iconFormat}}`, and `{{label}}` when customizing layouts.

## Confirmed key mismatches

The analyzed source and distributed config disagree on these exact names:

| Distributed config | Code reads | Effect |
|---|---|---|
| `preUi` | mostly `prevUi` | UI mode defaults may be ignored |
| `storeMSG` | `storemsg` | Default store message may resolve to `null` |
| `kitukizuri.*` | one branch reads `kitikizuri.preUi` | That branch always sees a missing key unless separately defined |

Do not create both spellings as a permanent workaround without a migration plan. Prefer fixing the implementation and supporting the previous key temporarily for backward compatibility.

Suggested compatibility read pattern:

```php
$previousUi = config(
    'kitukizuri.prevUi',
    config('kitukizuri.preUi', false)
);
```

Suggested store message compatibility pattern:

```php
$message = config(
    'kitukizuri.storeMSG',
    config('kitukizuri.storemsg', 'Datos guardados exitosamente.')
);
```

## Environment variables observed

Core/runtime behavior reads variables including:

- `APP_FORCE_HTTPS`
- `LOG_CHANNEL`
- `LOG_LEVEL`
- `DB_CONNECTION`
- `DATABASE_URL_TENANTS`
- `TENANTS_CONNECTION`
- `TENANTS_HOST`
- `TENANTS_PORT`
- `TENANTS_DATABASE`
- `TENANTS_USERNAME`
- `TENANTS_PASSWORD`
- `TENANTS_SOCKET`
- `DB_MONGO_HOST`
- `DB_MONGO_PORT`
- `DB_MONGO_DATABASE`
- `DB_MONGO_USERNAME`
- `DB_MONGO_PASSWORD`
- `LDAP_DIRECTORY_TYPE`

Stubs and auxiliary installers also reference mail and cloud-service variables. Inspect the exact command or stub before adding variables to an application.

## Publishing configuration

The provider publishes:

| Tag | Package source | Application destination |
|---|---|---|
| `krud-migrations` | `src/database/migrations` | `database/migrations` |
| `krud-seeders` | `src/database/seeders` | `database/seeders` |
| `krud-error` | `src/resources/views/errors` | `resources/views/errors` |
| `krud-views` | `src/resources/views/krud` | `resources/views/krud` |
| `krud-app` | `src/resources/views/app` | `resources/views/app` |
| `krud-config` | `src/config` | `config` |
| `krud-public` | `src/public` | `public` |

Before changing a source directory or tag, inspect `KrudUpdate`, which has its own parallel source/destination definitions.
