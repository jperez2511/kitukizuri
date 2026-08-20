# Kitukizuri agent skills

Canonical skills are stored under:

```text
resources/boost/skills/
├── kitukizuri-development/
└── dashlite-development/
```

Use `kitukizuri-development` for package APIs, Krud controllers, fields, permissions, commands, backend behavior, and package contracts.

Use `dashlite-development` for Blade UI, DashLite layouts, components, SCSS, NioIcon, NioApp JavaScript, Bootstrap integration, DataTables, Select2, and visual conventions.

Validate them:

```bash
bash resources/boost/skills/kitukizuri-development/scripts/validate-skill.sh
bash resources/boost/skills/dashlite-development/scripts/validate-skill.sh
```

Install repository-local copies for Codex and Claude Code:

```bash
bash resources/boost/skills/kitukizuri-development/scripts/install-local.sh
bash resources/boost/skills/dashlite-development/scripts/install-local.sh
```

This creates:

```text
.agents/skills/kitukizuri-development/
.agents/skills/dashlite-development/
.claude/skills/kitukizuri-development/
.claude/skills/dashlite-development/
```

The canonical copies remain under `resources/boost/skills`, so edit them there and then reinstall local copies.
