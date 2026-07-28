#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SKILL_DIR="$(dirname "$SCRIPT_DIR")"
PACKAGE_ROOT="$(cd "$SCRIPT_DIR/../../../../.." && pwd)"

if [[ ! -f "$SKILL_DIR/SKILL.md" ]]; then
    echo "Missing SKILL.md" >&2
    exit 1
fi

if ! head -n 12 "$SKILL_DIR/SKILL.md" | grep -q '^name: kitukizuri-development$'; then
    echo "SKILL.md is missing the expected name frontmatter" >&2
    exit 1
fi

if ! head -n 12 "$SKILL_DIR/SKILL.md" | grep -q '^description:'; then
    echo "SKILL.md is missing description frontmatter" >&2
    exit 1
fi

php "$SCRIPT_DIR/generate-api-index.php" --check

syntax_errors=0
while IFS= read -r -d '' file; do
    if ! php -l "$file" >/dev/null; then
        syntax_errors=$((syntax_errors + 1))
    fi
done < <(find "$PACKAGE_ROOT/src" -type f -name '*.php' -print0)

if [[ "$syntax_errors" -ne 0 ]]; then
    echo "PHP syntax validation failed for $syntax_errors file(s)." >&2
    exit 1
fi

required_references=(
    architecture.md
    krud-api.md
    field-schema.md
    configuration.md
    commands.md
    examples.md
    compatibility.md
    known-issues.md
    api-index.generated.md
)

for reference in "${required_references[@]}"; do
    if [[ ! -s "$SKILL_DIR/references/$reference" ]]; then
        echo "Missing or empty reference: $reference" >&2
        exit 1
    fi
done

echo "Kitukizuri skill validation passed."
