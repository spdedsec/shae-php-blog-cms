# shae-php-blog-cms — Installations- und Nutzungsanleitung (Deutsch)

SHAE Blog CMS — ein kleines, abhängigkeitsfreies Blog-/CMS-System in PHP.
Beiträge werden in einer einzelnen JSON-Datei (`data/posts.json`) gespeichert;
keine Datenbank erforderlich.

Entsprechend dem **SHAE Field Manual Design System**: warmer Papierton
`#F2EEE5`, Tinte `#191712`, ein Oxid-Rot `#B3341B` Signal pro Ansicht,
`0px` Radius, `1px` Ränder, randdefinierte Karten, `44px` Touch-Ziele,
Oxid-Fokusringe und `140–240ms` Bewegung. Siehe `DESIGN.md`.

## Voraussetzungen

- PHP 8.1+ (CLI oder Webserver wie `php -S`, nginx, Apache)
- Schreibzugriff auf `data/` (dort wird `data/posts.json` automatisch erstellt)

> **Hinweis:** PHP ist in dieser Umgebung nicht installiert, sodass das CMS
> hier nicht lokal ausgeführt oder getestet werden kann. Siehe
> `scripts/install.sh`.

## Installation

```bash
git clone https://github.com/spdedsec/shae-php-blog-cms.git
cd shae-php-blog-cms
./scripts/install.sh
```

## Ausführen (eingebauter PHP-Webserver)

```bash
cd src
php -S localhost:8000
# Öffnen: http://localhost:8000/index.php
```

Für nginx/Apache das Dokument-Verzeichnis auf `src/` zeigen und `.php`-Anfragen
an den PHP-Interpreter weiterleiten.

## Nutzung

- `index.php` — alle Beiträge (neueste zuerst) auflisten und neuen Beitrag erstellen.
- `post.php?id=<id>` — Beitrag ansehen, bearbeiten oder löschen.
- Die Beitragsdaten liegen in `data/posts.json` (gitignored, wird beim ersten Schreiben erstellt).

## Lizenz

MIT. Siehe `LICENSE`.
