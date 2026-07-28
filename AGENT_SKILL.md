# Kitukizuri agent skill

The source-grounded skill is located at:

```text
resources/boost/skills/kitukizuri-development/
```

Validate it:

```bash
bash resources/boost/skills/kitukizuri-development/scripts/validate-skill.sh
```

Install repository-local copies for Codex and Claude Code:

```bash
bash resources/boost/skills/kitukizuri-development/scripts/install-local.sh
```

This creates:

```text
.agents/skills/kitukizuri-development/
.claude/skills/kitukizuri-development/
```

The canonical copy remains under `resources/boost/skills` so documentation should be edited there and then reinstalled locally.
