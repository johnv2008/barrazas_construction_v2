# Font licences

All three faces are self-hosted rather than loaded from a CDN, so the
Content-Security-Policy in `public/index.php` can keep `font-src 'self'`
and no visitor request reaches a third party.

Only the weights the stylesheets actually request are stored here — see
the audit note at the top of `public/assets/css/variables.css` before
adding more files.

| File | Face | Weight / style | Size | Licence |
|---|---|---|---|---|
| `general-sans-600.woff2` | General Sans | 600 normal | 23 KB | ITF Free Font License |
| `general-sans-700.woff2` | General Sans | 700 normal | 21 KB | ITF Free Font License |
| `inter-latin-var.woff2` | Inter (variable, latin subset) | 400–700 normal | 47 KB | SIL Open Font License 1.1 |
| `fraunces-italic-400.woff2` | Fraunces | 400 italic | 22 KB | SIL Open Font License 1.1 |
| `fraunces-400.woff2` | Fraunces | 400 upright | 18 KB | SIL Open Font License 1.1 |

The public site loads the first four (~113 KB). The upright Fraunces is
used only by `admin.css` brand marks, and a browser fetches a
`@font-face` file only when a rendered glyph needs it — so public pages
never request it.

Semibold 600 is the default heading weight; Bold 700 is reserved for three
moments (hero H1, the Chapter 03 statement, the Chapter 05 featured
title). Both are real files — no synthetic bolding.

## Sources

- **General Sans** — Indian Type Foundry, via Fontshare (`cdn.fontshare.com`).
  The ITF Free Font License permits commercial use and self-hosting.
  <https://www.fontshare.com/fonts/general-sans>
- **Inter** — Rasmus Andersson, via Google Fonts (`fonts.gstatic.com`).
  Latin subset of the variable font.
  <https://github.com/rsms/inter>
- **Fraunces** — Undercase Type / Flavia Zimbardi / Phaedra Charles, via
  Google Fonts. Single italic 400 instance rather than the full variable
  italic, which is 80 KB for the same two rendered phrases.
  <https://github.com/undercasetype/Fraunces>

The SIL Open Font License 1.1 requires that the licence accompany the
font files. Full text: <https://openfontlicense.org/>

## Replacing or adding a weight

1. Download the WOFF2 into this directory.
2. Add an `@font-face` block in `public/assets/css/variables.css`
   (top of file) with `font-display: swap`.
3. Only add a `<link rel="preload">` in `app/Views/layouts/frontend.php`
   if the weight renders above the fold — every preload competes with
   the two already on the critical path.
