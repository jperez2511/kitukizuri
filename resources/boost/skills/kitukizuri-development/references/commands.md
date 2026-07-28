# Artisan commands

Commands are discovered dynamically from `src/App/Console/Command/*.php` by `CommandRegistrar`.

## Command catalog

| Command | Options | Observed purpose |
|---|---|---|
| `krud:install` | — | Initialize a KRUD-based Laravel project |
| `krud:update` | `--force`, `--skip-publish`, `--skip-migrate`, `--skip-seed` | Synchronize package resources, migrations, and base seeders |
| `krud:default-data` | — | Create/seed the initial database structure/data |
| `krud:make` | `--module` | Interactive module/model/controller/route/seeder generator |
| `krud:libs-install` | — | Install/configure JavaScript dependencies |
| `krud:vue-install` | `--vite-config` | Install/configure Vue |
| `krud:react-install` | `--vite-config` | Install/configure React |
| `krud:ts-install` | — | Install/configure TypeScript |
| `krud:ui-config` | — | Configure the newer visual environment |
| `krud:log-install` | — | Configure database logging |
| `krud:set-docker` | — | Generate Docker environment files |
| `migrate:tts` | `--all`, `--rollback`, `--id=*` | Run or roll back tenant migrations |
| `db:tts` | `--class=*`, `--tenantid=*` / `-t` | Run seeders for selected tenants |

## `krud:update`

Signature:

```text
krud:update
  --force         Replace all synchronized files after creating *_old backups
  --skip-publish  Skip resource synchronization
  --skip-migrate  Skip migrations
  --skip-seed     Skip seeder synchronization
```

Observed behavior:

1. Synchronizes missing package module entries into the application's `ModulosSeeder`.
2. Synchronizes published directories.
3. Replaces view/public resources by default according to per-tag settings.
4. Creates `_old`, `_old_1`, etc. backups before replacements.
5. Checks database connectivity before migration/seeding work.
6. Runs migrations unless skipped.
7. Runs selected base seeders unless skipped.

Review application customizations before running with `--force`.

## `krud:make --module`

This is an interactive generator using Laravel Prompts. It can modify:

- `database/seeders/ModulosSeeder.php`
- A generated model under `app/Models`
- A generated controller under `app/Http/Controllers`
- `routes/web.php`
- Database seed data

It expects marker comments in `routes/web.php`:

```php
// Automatic injection routes don't remove this line
// Automatic injection controllers don't remove this line
```

The generated controller is converted from a normal Laravel controller into a `Krud` subclass and receives a constructor with `setModel()` and `setTitulo()`.

Because it edits files by string replacement, run it only with version control or a backup and inspect the diff immediately.

## Tenant commands

### `migrate:tts`

- `--all`: target every tenant database.
- `--id=*`: target selected tenant IDs.
- `--rollback`: run rollback behavior instead of forward migration.

### `db:tts`

- `--class=*`: run specific seeder classes.
- `--tenantid=*` or `-t`: target selected tenant IDs.

Inspect connection environment variables and tenant model data before execution.

## Installation/update verification

After provider, alias, command, or publishing changes:

```bash
composer dump-autoload
php artisan package:discover --ansi
php artisan list | grep -E 'krud:|migrate:tts|db:tts'
php artisan route:list --path=krud
```

For a package repository without a complete Laravel host application, use Orchestra Testbench before assuming these commands can be executed directly.
