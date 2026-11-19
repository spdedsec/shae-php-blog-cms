# Blog CMS (PHP & Apache)

A lightweight blogging platform and content management system written in PHP 8.3 and packaged with Apache, styled strictly following the Apple Human Interface Guidelines (HIG).

## Features

- **Apple HIG Design System** — San Francisco typography, Cupertino cards, system accent buttons, light/dark mode support
- **Container Architecture** — Production Docker container with PHP 8.3, Apache `mod_rewrite`, non-root execution permissions, and dynamic `$PORT` binding
- **CRUD Publishing** — Create, read, edit, and delete articles with JSON document storage
- **Clean URLs** — Apache `.htaccess` rewrite rules for human-friendly routing
- **Responsive & Accessible** — Full keyboard accessibility, touch targets ≥ 44pt, and visible focus rings
- **Health Checks** — Native container healthcheck probe

## Local Development (Docker)

```bash
docker build -t php-blog-cms .
docker run -p 8080:80 php-blog-cms
```

Visit `http://localhost:8080`.

## Design System

Engineered strictly following the [Apple Human Interface Guidelines](https://developer.apple.com/design/human-interface-guidelines/):
- **Typography:** San Francisco (`-apple-system`, `BlinkMacSystemFont`, `SF Pro Text`, `SF Pro Display`)
- **System Colors:** Apple System Blue (`#007AFF` / `#0A84FF`), Apple System Green (`#34C759` / `#30D158`), Apple System Red (`#FF3B30` / `#FF453A`)
- **Light/Dark Mode:** Full support via `color-scheme: light dark` and `@media (prefers-color-scheme: dark)`
- **8pt Grid:** Precision layout rhythm
- **Corner Radii:** 8pt, 12pt, 16pt, pill buttons
- **Elevation:** Apple 5-tier elevation shadows

## License

MIT © 2026 spdedsec
