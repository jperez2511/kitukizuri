#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SKILL_DIR="$(dirname "$SCRIPT_DIR")"
PACKAGE_ROOT="$(cd "$SCRIPT_DIR/../../../../.." && pwd)"

if [[ ! -f "$SKILL_DIR/SKILL.md" ]]; then
    echo "Missing SKILL.md" >&2
    exit 1
fi

if ! head -n 12 "$SKILL_DIR/SKILL.md" | grep -q '^name: dashlite-development$'; then
    echo "SKILL.md is missing the expected name frontmatter" >&2
    exit 1
fi

if ! head -n 12 "$SKILL_DIR/SKILL.md" | grep -q '^description:'; then
    echo "SKILL.md is missing description frontmatter" >&2
    exit 1
fi

required_references=(
    architecture.md
    layouts.md
    components.md
    forms.md
    tables.md
    javascript.md
    styling.md
    icons.md
    implementation-checklist.md
)

for reference in "${required_references[@]}"; do
    if [[ ! -s "$SKILL_DIR/references/$reference" ]]; then
        echo "Missing or empty reference: $reference" >&2
        exit 1
    fi
done

required_sources=(
    src/App/Support/DashliteLayoutState.php
    src/resources/js/init.js
    src/resources/views/layouts/dashlite/partials/content-default.blade.php
    src/resources/views/layouts/dashlite/partials/layout-standard.blade.php
    src/resources/views/layouts/dashlite/variants/demo3.blade.php
    src/resources/views/krud/index.blade.php
    src/resources/views/krud/components/input.blade.php
    src/resources/views/krud/components/select2.blade.php
    src/resources/sass
    src/resources/dashlite-scss
)

for source in "${required_sources[@]}"; do
    if [[ ! -e "$PACKAGE_ROOT/$source" ]]; then
        echo "Missing DashLite source referenced by the skill: $source" >&2
        exit 1
    fi
done

if ! grep -q 'Bootstrap.*5\.3\.2' "$SKILL_DIR/SKILL.md"; then
    echo "SKILL.md should document the current Bootstrap 5.3.2 integration" >&2
    exit 1
fi

if ! grep -q 'DashLite.*2\.3' "$SKILL_DIR/SKILL.md"; then
    echo "SKILL.md should document the current DashLite 2.3 runtime metadata" >&2
    exit 1
fi

echo "DashLite skill validation passed."
