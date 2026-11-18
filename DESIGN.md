# DESIGN.md — shae-php-blog-cms

SHAE Field Manual Design System applied to the PHP Blog CMS.

## Color palette

| Token | Value | Use |
|-------|-------|-----|
| Warm paper | `#F2EEE5` | Page/card background |
| Ink | `#191712` | Primary text, borders |
| Oxide signal | `#B3341B` | Primary buttons, focus rings, links |

One oxide-red signal per view: the primary action button ("Create post" /
"Update post" / "Delete post"). No gradients, no glows, no glass cards, no
shadows, no pills.

## Layout & geometry

- `0px` border radius everywhere; flat, physical cards.
- `1px` solid ink borders define cards, inputs, buttons, notices.
- Border-defined cards: `.card { border: 1px solid var(--border); }`.
- `4px` spacing base; `1280px` max container width; centered.
- `44px` minimum touch targets on every button and interactive control
  (`min-height`/`min-width: 44px`).
- No horizontal overflow; `overflow-x: hidden` on `<body>`.

## Typography

- Display/headings: **Archivo** (fallback Georgia/serif), uppercase short labels
  only, letter-spaced.
- Body: **Instrument Sans** (fallback system-ui).
- Labels, code, metadata, mono data: **JetBrains Mono** (fallback monospace),
  tabular numerals.
- Web fonts are referenced by name with system fallbacks; they load from
  Google Fonts when available and degrade gracefully otherwise.

## Motion

- State changes: `140–240ms` (default `180ms`), `transform`/`opacity` only.
- Page transitions: `320ms` where applied.
- `prefers-reduced-motion: reduce` disables transitions/animations.
- No animation libraries for simple UI.

## Accessibility

- WCAG AA contrast: ink on paper; oxide buttons use white text on `#B3341B`.
- Visible oxide focus rings (`2px solid #B3341B`, `2px` offset) on
  `:focus-visible` for buttons, links, and fields.
- Complete keyboard paths: all actions reachable via Tab/Enter/Space.
- `required` fields and `novalidate` forms with server-side validation in PHP.
- Reduced-transparency fallback restores opacity for metadata text.

## Files

- `src/styles.css` — design tokens and components above.
- `src/index.php`, `src/post.php` — markup applying the components.
- `DESIGN.md` — this document.
