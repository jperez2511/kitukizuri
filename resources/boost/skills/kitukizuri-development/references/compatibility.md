# Compatibility and upgrade notes

## Declared compatibility

The analyzed root `composer.json` declares only:

```json
{
  "require": {
    "php": "^8.0"
  }
}
```

It does **not** declare a Laravel/Illuminate version range or the other integrations referenced by the source. Therefore:

- PHP 8.0+ is declared.
- Laravel compatibility is not formally declared.
- Installation success is not proof of behavioral compatibility.

## Observed runtime assumptions

The source assumes the consuming application provides or installs:

- Laravel framework classes and helpers.
- `App\Http\Controllers\Controller`.
- Authentication and a user model/table.
- Livewire when the optional component is used.
- `mongodb/laravel-mongodb` when MongoDB paths are used.
- Laravel Prompts for `krud:make`.
- Carbon.
- Mockery's `Exception` class is imported in `Krud.php` and used in catches.
- Frontend libraries referenced by views and installers.

These should become explicit Composer dependencies or suggestions.

## Framework-sensitive areas

### Global aliases

Aliases are declared in Composer metadata and again in the provider. For modern Laravel applications and especially upgrade work, prefer:

```php
use Icebearsoft\Kitukizuri\Krud;
```

rather than relying on:

```php
use Krud;
```

Keep the legacy alias for backward compatibility until consuming applications migrate.

### Provider boot and middleware

The provider injects `Tenant` into the global kernel and receives `Router` and `Kernel` through `boot()`. Verify this integration against each target Laravel version.

### Route namespaces

`RouteRegistrar` uses a namespace group plus string controller names. This is a legacy-sensitive area. A future-safe migration would use controller class references, but changing it alters route registration and should be covered by route tests.

### Laravel Prompts

`MakeModule` imports `Laravel\Prompts\text`, `confirm`, and `multiselect`. Ensure the target Laravel/Prompts version supplies them.

### PostgreSQL grammar

`PostgresGrammarProvider` replaces query grammar for newly created PostgreSQL connections. Test raw SQL, pagination, counting, and `toRawSql()` behavior after framework upgrades.

### MongoDB

`Krud.php` directly references `MongoDB\Laravel\Connection`. Test class availability and selection behavior even when a project does not use MongoDB.

## Database behavior

`getData()` has separate paths:

- MySQL/SQLite: creates a subquery from `toRawSql()` for count.
- Other drivers, including PostgreSQL: ensures key selection and uses builder `count()`.
- MongoDB: `getSelect()` returns plain field names instead of SQL expressions.

A support claim should include tests for the exact driver.

## Recommended compatibility matrix

Create CI using Orchestra Testbench and test the versions the package intends to support. Do not publish this matrix as supported until green:

```text
PHP:       8.1, 8.2, 8.3, 8.4
Laravel:   explicitly selected maintained versions
Database:  MySQL and PostgreSQL; MongoDB only when dependency is installed
```

The package currently declares PHP 8.0, so dropping it requires a major/minor policy decision and Composer update.

## Minimum upgrade test suite

For each target framework version:

1. Package discovery succeeds.
2. Aliases or explicit imports resolve.
3. Internal routes register.
4. Middleware aliases register.
5. Blade namespaces and components resolve.
6. `krud:install`, `krud:update --skip-migrate --skip-seed`, and `krud:make --module` are discoverable.
7. A basic `Krud` controller renders index.
8. DataTables `show()` returns valid JSON.
9. Create/edit/store work with text, bool, date, and select fields.
10. Destroy handles existing and missing records.
11. Permission middleware accepts and rejects expected routes.
12. MySQL and PostgreSQL counting/paging behave consistently.

## Composer recommendations

A future package release should define:

- `illuminate/*` or `laravel/framework` constraints.
- `nesbot/carbon` if not guaranteed transitively.
- `mongodb/laravel-mongodb` under `suggest` or conditional integration.
- Livewire under `suggest` if optional.
- Development dependencies for Testbench, PHPUnit/Pest, static analysis, and formatting.

## Snapshot limitations

The uploaded archive contains no automated test suite and no Git metadata/version tag. This skill documents the snapshot's observed source, not a semantically versioned release guarantee.
