#!/usr/bin/env bash
# PreToolUse hook: gate `git commit` behind `composer check`
# (vendor/bin/pint --test + php artisan test). Exit 2 blocks the commit.
set -uo pipefail

command=$(jq -r '.tool_input.command // empty')

case "$command" in
  *"git commit"*) ;;
  *) exit 0 ;;
esac

cd "${CLAUDE_PROJECT_DIR:-$(dirname "$0")/../..}" || exit 0

if output=$(composer check 2>&1); then
  exit 0
fi

{
  echo "Commit blocked: 'composer check' failed. Fix the failures, then commit again."
  echo "--- composer check output ---"
  echo "$output" | tail -60
} >&2
exit 2
