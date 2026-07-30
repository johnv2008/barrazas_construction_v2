# Phase 3.5 — Global Design Language

**Objective:** the visitor should never know which parts of the website were
built first.

**Status:** audit only. Nothing in this document has been implemented.

Every finding below is measured from the running site, not inferred from
the source. Where a number appears, it was read off a computed style.

---

## The shape of the problem

The site has two design languages running side by side.

**Language A — editorial.** Written during the homepage rebuild and the
service-page system. Burgundy hairline ticks, the 11px annotation
register, `--radius-photo`, the Datum rail, `--ease-out` at 240–900ms,
type-crossing-photograph, unequal image roles. It is coherent and it is
genuinely premium.

**Language B — application chrome.** Predates all of it and was never
revisited. Native form controls, 2px radii, Material Design easing at
180ms, sentence-case bold form labels, hash-anchor navigation, a footer of
placeholder links. It is competent and completely generic.

Language A is the rooms. Language B is the corridors. **A visitor spends
more time in Language B than in Language A** — the header is on every
page, the footer is on every page, the form is the conversion event.

Nothing here requires redesign. Almost all of it is convergence: making
one component out of two, one value out of five, one easing out of two.

---

## 1 · CORRIDOR AUDIT

Every place the experience drops below the homepage standard.

### 1.1 The header — on every page, designed for one

The nav is hardcoded to homepage fragments: `#services`, `#projects`,
`#process`, `#about`, `#contact`.

| Corridor | Measured behaviour |
|---|---|
| Homepage | All five anchors resolve. Works. |
| Service page | `#services` and `#about` **do not exist** — clicking does nothing at all |
| Service page | `#projects` and `#process` **do exist** — so they silently scroll to *that service's* own related-work and process chapters |
| Project page | Same, worse: "Projects" scrolls to a three-card strip |

The second row is the real failure. A dead link is a broken promise; a
link that silently goes somewhere plausible but wrong is a broken *model*.
A homeowner who clicks "Projects" and lands on three cards concludes the
portfolio is three projects.

There is also no active state for the current section on service pages —
"Home" carries `aria-current="page"` on every page including the ones that
are not home.

### 1.2 The footer — the least designed surface on the site

| Item | State |
|---|---|
| Two social icons | `href="#"` — dead |
| `/privacy-policy` | **404** |
| `/terms` | **404** |
| Company links (`#services`, `#projects`, `#process`, `#about`) | Homepage fragments; broken or misleading off-homepage |
| Services list | Four plain-text items linking to `#services` — **not** to the two real service pages that now exist |

On a site whose entire argument is *we are careful about the things you
cannot see*, a footer of placeholder links is the most expensive detail on
the page. It is also the last thing a visitor reads before deciding.

### 1.3 The consultation form — where the illusion breaks completely

This is the conversion event, it appears on all three page types, and it
contains the only elements on the site that were never designed at all.

Measured on `/services/bathroom-remodeling`, dark ground:

- **`<select>` has `appearance: auto`.** The native OS dropdown chevron
  renders on the project-type field.
- **File input is entirely native.** A light-grey `Choose File | No file
  chosen` button sits on a near-black editorial page. It is the single
  most out-of-place element on the website.
- **Textarea shows the native resize grabber** — diagonal lines, bottom
  right.
- A `2px inset rgb(118,118,118)` border is present in the rendered style
  inventory. That is raw browser chrome.
- **Form labels are 14px, weight 600, sentence case.** Every other label
  on the site is 11–12px uppercase and letterspaced. The form is the only
  place the annotation register is abandoned, so it reads as a web form
  bolted into a magazine.
- Field spacing is uniform. There is no grouping rhythm, so nine fields
  read as a stack rather than as three related clusters.
- The copy column ends ~300px above the form's floor, leaving a large
  dead area bottom-left — the same unbalanced-column fault as Chapter 06.

### 1.4 The homepage's own unfinished chapters

- **Chapter 02 and Chapter 06 render broken image crops.** Both carry the
  inert `aspect-ratio` bug and render at 600px natural pixel height.
  Chapter 06's is a narrow vertical strip where a landscape crop was
  designed.
- **Chapter 06 has ~300px of dead space** below the left column and
  illustrates *credentials and trust* with a photograph of a
  half-demolished kitchen.
- **Chapters 03, 04 and 05 share a ground tone** (all warm white,
  measured). `DESIGN_SYSTEM §10` requires alternation and calls it "the
  single rule that makes a long page feel composed." The variance map in
  `CREATIVE_DIRECTION §3.3` contradicts it by listing 03/04/05 as
  identical. The documentation disagrees with itself and the build
  followed the wrong half.

### 1.5 The corridor between homepage and service pages

**The two best pages on the site cannot be reached from the homepage.**
No link exists. Chapter 04 lists all five services and every row links to
`#projects` on the same page.

Every internal-linking claim in the SEO architecture is currently unmet,
and the entire service-page system is invisible to a real visitor.

### 1.6 The mobile corridor

All four signature gestures live in `@media (min-width: 1024px)` —
verified. Below that width the hero bleed, the Chapter 05 slab, the
material band and the vertical band all resolve to the same stacked
column.

For a local remodeling company, mobile is the majority. The corridor most
visitors walk is the one with none of the rooms in it.

### 1.7 Project-page corridor

- Breadcrumb's last crumb duplicates the eyebrow directly beneath it —
  `KITCHEN REMODEL` above `KITCHEN REMODEL`, 40px apart, on all ten
  project pages.
- Seven of ten project pages have one photograph and an identical
  silhouette. Two visited in sequence is where the site feels most
  templated.

---

## 2 · SHARED COMPONENT AUDIT

### 2.1 The annotation register — one idea, seven implementations

The 11px uppercase letterspaced label is the most-repeated element on the
site and the strongest carrier of its identity. Measured across pages:

| Component | Size | Tracking | Weight | Colour |
|---|---|---|---|---|
| `.eyebrow` | 12px | 1.68px | 700 | burgundy |
| `.arrival__trust` | 12px | **1.2px** | 400 | muted |
| `.plate__label` | **10px** | 1.4px | 400 | burgundy |
| `.tile__meta` | 11px | 1.32px | 400 | muted |
| `.crumbs` | 11px | 1.32px | 400 | muted |
| `.credit` | 11px | 1.32px | 400 | muted |
| `.pcard__meta` | 11px | 1.32px | 400 | muted |
| `.filing` | 11px | 1.32px | **500** | muted |
| `.spec dt` | 11px | 1.32px | 400 | burgundy |
| `.vband__label` | 11px | 1.32px | 400 | burgundy |
| `.track__num` | 11px | **1.54px** | 400 | burgundy |

**Four sizes. Five tracking values. Three weights.** Two components at the
same 12px carry different tracking (1.68 vs 1.2). Two at 11px likewise
(1.32 vs 1.54).

`font-size: 0.6875rem` is declared **20 separate times** in the CSS. There
is no token. The register is not a system; it is eleven near-misses that
happen to look similar.

*Refinement, not redesign: three tokens — label, label-strong,
label-micro — and every call site reads one of them.*

### 2.2 The spec table — built twice

`.feature__meta` (frontend.css:781) and `.spec` (service.css:109) are the
same component with the same visual design, implemented independently.
They currently match because they were copied carefully. They will drift
the first time either is edited alone.

*Refinement: delete one. `.spec` is the generalised version; the homepage
should consume it.*

### 2.3 Captions — six components doing one job

`.plate`, `.credit`, `.tile figcaption`, `.band__note`, `.vband__note`,
`.pcard__caption`. All are "small text describing an image." All have
separate rules, separate spacing, and three different relationships to the
hairline above them.

*Refinement: one caption primitive with two or three modifiers.*

### 2.4 Motion — two easing languages

| Layer | Easing | Duration |
|---|---|---|
| Buttons, form controls (components.css) | `cubic-bezier(0.4, 0, 0.2, 1)` — Material Design | 180ms |
| Editorial content (frontend.css / service.css) | `cubic-bezier(0.16, 1, 0.3, 1)` — `--ease-out` | 240–900ms |

Six distinct durations in use site-wide: 900 / 460 / 380 / 300 / 240 /
120ms. One raw `cubic-bezier` bypasses the token system entirely.

The chrome animates like Bootstrap and the content animates like the
brand. On a page containing both — every page — the difference is
perceptible even to someone who cannot name it.

*Refinement: three durations bound to interaction classes (micro / state
/ reveal), one easing token, zero raw cubic-beziers.*

### 2.5 Hairlines — the most repeated element has four values

Measured distinct border declarations in use:

- `rgba(17,18,20,0.10)` — on light
- `rgba(17,18,20,0.12)` — on light
- `rgba(255,255,255,0.08)` — on dark
- `rgba(255,255,255,0.18)` — on dark

Two "on light" values and two "on dark" values, used interchangeably.

*Refinement: one on-light, one on-dark, one accent. Three total.*

### 2.6 Radius — five values, no rule

2px (buttons, checkbox) · 3px (`--radius-md`) · 6px (`--radius-lg`) · 7px
(`--radius-photo`) · 999px (pill).

A 2px button beside a 7px photograph is the clearest visible seam between
Language A and Language B.

*Refinement: two values — one for photography, one for controls — and a
documented reason for the difference.*

### 2.7 Buttons and links

- `.btn-primary`: 54px tall, 2px radius, 15px/600, Material easing.
- `.link-arrow`: 15px/600, gradient underline, brand easing.

Two primary interactive elements, two motion languages, two visual
vocabularies. The button is the more generic of the two and it is the one
carrying the conversion.

### 2.8 Section rhythm

`.ch` padding is 96px, except Chapters 02 and 06 at 120px. That exception
is deliberate and documented — but it is the *only* deliberate variation,
so the rest of the page has no rhythm, just a constant.

Container widths in play: `--container-max` 1280, `--container-wide` 1440,
`--container-narrow` 900, plus `.arrival` which is not a `.container` at
all and manages its own padding. Four measuring systems.

### 2.9 Loading and empty states

There are none. `.tile__frame`, `.band__frame` and `.vband__frame` set
`background: var(--stone)`, which acts as an accidental placeholder;
`.svc-arrival__media img`, `.feature__main` and `.prj-doc__shot` do not.
On a slow connection the hero areas flash empty white.

There is no visible loading state on form submission.

### 2.10 Focus and hover

Focus is good and consistent — `--focus-ring-width` / `--focus-ring-offset`
tokens exist and are used. This is the one shared system that is already
right.

Hover is less consistent: images scale 1.03 or 1.04 depending on
component, links change colour, buttons translate. No documented rule.

---

## 3 · CONSISTENCY MAP

Legend — **A** matches the premium standard · **B** functional but
generic · **✕** actively breaks the illusion.

| Component | Homepage | Kitchen | Bathroom | Project | Verdict |
|---|---|---|---|---|---|
| Datum rail | A | A | A | A | **Best shared element on the site** |
| Typography scale | A | A | A | A | Consistent |
| Focus rings | A | A | A | A | Consistent |
| Ground alternation | ✕ (03/04/05 repeat) | A | A | A | Homepage is the outlier |
| Header nav | B | ✕ | ✕ | ✕ | Breaks off-homepage |
| Footer | B | ✕ | ✕ | ✕ | Dead links; no service links |
| Form | ✕ | ✕ | ✕ | ✕ | Native controls everywhere |
| Buttons | B | B | B | B | Generic, wrong easing |
| Annotation register | B | B | B | B | Drifts everywhere |
| Spec table | B (`.feature__meta`) | A (`.spec`) | A | A | Two implementations |
| Captions | B | B | B | B | Six components |
| Hairlines | B | B | B | B | Four values |
| Image radius | A | A | A | A | Consistent |
| Breadcrumb | n/a | A | A | ✕ (duplicate crumb) | |
| Signature gesture | A ≥1024 / ✕ <1024 | A / ✕ | A / ✕ | A / ✕ | Desktop only |
| Image crops | ✕ (Ch 02, 06) | A | A | A | Two broken |
| Homepage → service link | ✕ | — | — | — | Does not exist |
| Loading states | ✕ | ✕ | ✕ | ✕ | None |

**Reading the map:** every column is strong on the rows that were
art-directed and weak on the rows that are shared. The site's quality is
inversely correlated with how often a component appears.

---

## 4 · IMPLEMENTATION PRIORITY

Ordered by impact first, and within equal impact, by lowest risk first.
Every item is refine / remove / align — none is a redesign.

### Tier 1 — highest impact, lowest risk (do first)

| # | Item | Effort | Risk | Why first |
|---|---|---|---|---|
| 1 | Link Chapter 04 rows to the real service pages | S | None | Makes the two best pages reachable. Data change only. |
| 2 | Header nav → absolute paths; drop `#services`/`#about` until those pages exist; correct `aria-current` | S | Low | Removes silent wrong-destination on every non-homepage page |
| 3 | Footer: remove dead `href="#"`, link Services list to real pages, unlink or build `/privacy-policy` + `/terms` | S | Low | Cheapest trust win available |
| 4 | Style `select`, file input and textarea; kill `appearance: auto` | S | Low | Removes the single most out-of-place element on the site |
| 5 | Form labels into the annotation register | S | Low | Makes the conversion surface belong to the publication |
| 6 | `height: auto` on Chapters 02 and 06 | S | None | Two lines; fixes two visibly broken crops |
| 7 | Change one of Chapter 03/04/05's ground tone; reconcile the two docs | S | Low | Restores the page's own composition rule |
| 8 | Remove duplicate breadcrumb crumb | S | None | Ten pages |

Tier 1 is roughly a week and removes **every finding that currently reads
as unfinished rather than restrained**.

### Tier 2 — high impact, contained risk

| # | Item | Effort | Risk |
|---|---|---|---|
| 9 | Tokenise the annotation register (3 tokens); migrate all 20 call sites | M | Low |
| 10 | Collapse `.feature__meta` into `.spec` | M | Medium — touches the homepage |
| 11 | One motion language: 3 durations, 1 easing, no raw cubic-beziers | M | Low |
| 12 | Hairlines → 3 values; radius → 2 values | S | Low |
| 13 | Placeholder grounds on all image frames | S | None |
| 14 | Form field grouping rhythm + fix the dead column | M | Low |

### Tier 3 — high impact, higher risk (schedule deliberately)

| # | Item | Effort | Risk |
|---|---|---|---|
| 15 | Bring **one** signature gesture below 1024px | L | Medium — touches the frozen homepage |
| 16 | Collapse six caption components into one primitive | M | Medium |
| 17 | Rebuild Chapter 06 around the credentials, not a photograph | M | Medium |

### Explicitly out of scope for Phase 3.5

Whole Home, Portfolio, city pages, SEO landing pages, CMS modules, new
photography, `/services` and `/projects` index pages. Item 3 unlinks the
legal pages rather than building them; item 2 removes nav entries rather
than creating destinations.

---

## 5 · The homeowner walk-through

Where a visitor becomes aware they are using a website rather than reading
a publication:

1. **Clicking "Services" in the header from a service page.** Nothing
   happens. *(Tier 1 #2)*
2. **Clicking "Projects" from a service page.** Lands on three cards.
   *(Tier 1 #2)*
3. **Reaching the consultation form.** The native "Choose File" button.
   *(Tier 1 #4)*
4. **Opening the project-type dropdown.** The OS chevron. *(Tier 1 #4)*
5. **Dragging the textarea corner.** The native grabber. *(Tier 1 #4)*
6. **Reading Chapter 06 on the homepage.** A broken crop and a large empty
   area. *(Tier 1 #6, Tier 3 #17)*
7. **Reaching the footer.** Dead social icons and two 404s. *(Tier 1 #3)*
8. **Any of it on a phone.** Nothing distinctive survives. *(Tier 3 #15)*

Seven of those eight are Tier 1. The eighth is the hardest problem on the
project.

---

## 6 · The measure of done

Phase 3.5 is complete when:

- Every link in the header and footer resolves, from every page.
- No native form control is visible anywhere.
- Every label on the site reads from one of three tokens.
- One easing function and three durations exist in the CSS.
- Hairlines have three values; radius has two.
- No image renders at natural pixel height.
- The homepage links to every published service page.
- A visitor cannot tell, from any component, which page was built first.

That last line is the only one that matters. The rest are how it is
verified.
