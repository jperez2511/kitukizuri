# Analysis report

## Scope analyzed

The uploaded archive was unpacked and inspected across:

- Root Composer and package metadata.
- `Krud.php` and all Krud traits.
- Core `KituKizuri` permission class and facade.
- Main and auxiliary service providers.
- Middleware.
- Package controllers and real `Krud` subclasses.
- Configuration.
- Artisan commands.
- Models, migrations, seeders, views, components, public assets, and stubs at inventory level.

## Validation performed

All PHP files under `src/` passed `php -l` syntax validation in the analyzed environment.

No automated test directory or semantic package version was present in the archive, so runtime behavior and Laravel-version support remain unverified.

## Deliverables

- `SKILL.md`: operational instructions for Codex/Claude-compatible skill consumption.
- `krud-api.md`: curated API documentation.
- `api-index.generated.md`: automatically generated method inventory.
- `field-schema.md`: verified `setCampo` contract.
- `architecture.md`: package architecture and request flow.
- `configuration.md`: declared keys, usage, and mismatches.
- `commands.md`: command catalog and side effects.
- `examples.md`: verified patterns.
- `compatibility.md`: observed compatibility constraints.
- `known-issues.md`: static risk register.
- `generate-api-index.php`: API inventory generator/checker.
- `validate-skill.sh`: source and skill validation.
- `install-local.sh`: copies the canonical skill into `.agents/skills` and `.claude/skills` for local repository use.

## Maintenance rule

When the public/protected Krud API changes:

```bash
php resources/boost/skills/kitukizuri-development/scripts/generate-api-index.php
bash resources/boost/skills/kitukizuri-development/scripts/validate-skill.sh
```

Review the curated reference whenever generated inventory changes. The generator detects method drift; it cannot automatically explain semantic behavior.
