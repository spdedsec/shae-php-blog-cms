#!/usr/bin/env bash
# scripts/install.sh — shae-php-blog-cms
#
# SHAE Blog CMS — a tiny PHP blog/CMS whose posts live in a JSON file.
# No database is required. A PHP web server is required to run it.
#
# This project cannot be tested locally in this environment because PHP is
# not installed. See README.md for requirements.

set -euo pipefail

echo "=== shae-php-blog-cms install ==="
echo ""

# Requirements
echo "Requirements:"
echo "  - PHP 8.1+ (CLI and/or a web server such as php -S, nginx, or Apache)"
echo "  - Write access to data/ (where data/posts.json lives)"
echo ""

# Check for PHP
if command -v php >/dev/null 2>&1; then
  echo "PHP found: $(php -v | head -n 1)"
else
  echo "PHP not found on PATH. Install PHP before running the CMS."
  echo "  Debian/Ubuntu: sudo apt-get install php php-cli"
  echo "  macOS:         brew install php"
  echo "  Arch:          sudo pacman -S php"
fi

echo ""
echo "=== Quick start (built-in PHP web server) ==="
echo "# From the project root:"
echo "  cd shae-php-blog-cms/src"
echo "  php -S localhost:8000"
echo ""
echo "# Then open http://localhost:8000/index.php"
echo ""
echo "Posts are stored in data/posts.json (created automatically on first write)."
echo "The data/ directory is gitignored."
