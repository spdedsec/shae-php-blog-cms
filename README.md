# shae-php-blog-cms

SHAE Blog CMS — a tiny, dependency-free blog/content management system in PHP.
Posts are stored in a single JSON file (`data/posts.json`); no database is required.

Built to the **SHAE Field Manual Design System**: warm paper `#F2EEE5`, ink
`#191712`, one oxide-red `#B3341B` signal per view, `0px` radius, `1px` borders,
border-defined cards, `44px` touch targets, oxide focus rings, and `140–240ms`
motion. See `DESIGN.md`.

## Features

- Create, read, update, delete blog posts (full CRUD)
- Posts stored in JSON (no database)
- List view with titles and dates
- Detail view with full content
- Simple HTML forms styled Apple-like: paper background, ink text, oxide buttons
- `0px` radius, `1px` borders, `44px` touch targets

## Requirements

- PHP 8.1+ (CLI or a web server such as `php -S`, nginx, or Apache)
- Write access to `data/` (where `data/posts.json` is created automatically)

> **Note:** PHP is not installed in this environment, so the CMS cannot be
> run or tested locally here. See `scripts/install.sh` for install notes.

## Install

```bash
# Clone
git clone https://github.com/spdedsec/shae-php-blog-cms.git
cd shae-php-blog-cms

# Review install notes
./scripts/install.sh
```

## Run (built-in PHP web server)

```bash
cd src
php -S localhost:8000
# Open http://localhost:8000/index.php
```

With nginx/Apache, point the document root at `src/` and route `.php` requests
to the PHP interpreter.

## Usage

- `index.php` — list all posts (newest first) and create a new post.
- `post.php?id=<id>` — view a post; edit or delete it from the detail view.
- Post data lives in `data/posts.json` (gitignored, created on first write).

## Project structure

```
src/
  index.php     # list view + create form
  post.php      # detail view + edit + delete + update
  storage.php   # JSON storage layer
  styles.css    # SHAE design-system stylesheet
data/           # posts.json (created at runtime, gitignored)
scripts/install.sh
Makefile
README.md
DESIGN.md
.github/workflows/release.yml
docs/DE.md
docs/ES.md
LICENSE
```

## Release

Releases are created from git tags (`v*`) via
`.github/workflows/release.yml` and packaged as a zip of `src/`.

## License

MIT. See `LICENSE`.
