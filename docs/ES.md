# shae-php-blog-cms — Guía de instalación y uso (Español)

SHAE Blog CMS — un pequeño blog/CMS sin dependencias en PHP.
Las entradas se almacenan en un único archivo JSON (`data/posts.json`);
no se requiere base de datos.

Aplicado el **Sistema de Diseño SHAE Field Manual**: fondo papel cálido
`#F2EEE5`, texto tinta `#191712`, una señal óxido `#B3341B` por vista,
`0px` de radio, bordes de `1px`, tarjetas definidas por borde, objetivos
táctiles de `44px`, anillos de enfoque óxido y movimiento de `140–240ms`.
Véase `DESIGN.md`.

## Requisitos

- PHP 8.1+ (CLI o servidor web como `php -S`, nginx o Apache)
- Acceso de escritura a `data/` (donde se crea `data/posts.json` automáticamente)

> **Nota:** PHP no está instalado en este entorno, por lo que el CMS no puede
> ejecutarse ni probarse localmente aquí. Véase `scripts/install.sh`.

## Instalación

```bash
git clone https://github.com/spdedsec/shae-php-blog-cms.git
cd shae-php-blog-cms
./scripts/install.sh
```

## Ejecución (servidor web integrado de PHP)

```bash
cd src
php -S localhost:8000
# Abrir: http://localhost:8000/index.php
```

Con nginx/Apache, señalar la raíz del documento a `src/` y enrutar las
solicitudes `.php` al intérprete de PHP.

## Uso

- `index.php` — lista todas las entradas (las más recientes primero) y crea una nueva.
- `post.php?id=<id>` — ver una entrada; editarla o eliminarla desde la vista de detalle.
- Los datos de las entradas están en `data/posts.json` (gitignored, se crea al escribir por primera vez).

## Licencia

MIT. Véase `LICENSE`.
