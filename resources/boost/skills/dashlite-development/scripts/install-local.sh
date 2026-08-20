#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SKILL_DIR="$(dirname "$SCRIPT_DIR")"
PACKAGE_ROOT="$(cd "$SCRIPT_DIR/../../../../.." && pwd)"
SKILL_NAME="dashlite-development"

install_copy() {
    local destination_root="$1"
    local destination="$destination_root/$SKILL_NAME"

    mkdir -p "$destination_root"
    rm -rf "$destination"
    cp -R "$SKILL_DIR" "$destination"
    echo "Installed: $destination"
}

install_copy "$PACKAGE_ROOT/.agents/skills"
install_copy "$PACKAGE_ROOT/.claude/skills"

echo "Local Codex and Claude DashLite skill copies are ready."
