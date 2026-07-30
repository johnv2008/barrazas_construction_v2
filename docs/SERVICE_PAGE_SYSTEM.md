# Master Service Page System

**Status:** proposal for approval. No production code written.
**Reference standard:** the homepage at tag `homepage-art-direction-approved`
(commit `20c6522`). Nothing in this document changes it.

This is the definitive system every service page inherits. It is written
against what the repository actually contains — the approved homepage
components, the existing `services` / `projects` / `project_images`
schema, the `responsive_image()` pipeline, and the real photo library of
24 source images — not against an idealised greenfield.

---

## 0. The governing problem

The brief asks for a modular system that never looks templated. Those pull
in opposite directions, and the homepage already solved the same tension
once. It did so with a **variance map**: no two adjacent chapters share
ground tone, primary axis, density, or type register. That rule is what
stops eight stacked sections reading as a list.

A service page system has to solve it **twice**:

1. **Down the page** — chapter 03 must not look like chapter 06.
2. **Across services** — the Kitchen page must not look like the Bathroom
   page with the nouns swapped. This is the harder one, and it is where
   most "premium templates" fail.

The system solves (2) with a rule that is deliberately not a matter of
taste:

> **Composition is assigned by photographic inventory, not by preference.**

Each service's chapter compositions are selected by how many images of
which role actually exist for it. Bathroom has seven usable photographs
and Additions has two, so their pages *cannot* resolve to the same layout
— and the difference is honest rather than decorative. This is the same
principle that made Chapter 04's ADU row typographic: the composition
tells the truth about the evidence.

### The 30-second summary

- Nine chapters, four of which are conditional on photography.
- Four **inventory tiers** (A–D) decide which compositions a service gets.
  A service at Tier D does not get a page at all.
- Every page spends the same **fixed budget**: one edge-bleed, three Bold
  headings, two Fraunces italic lines, one signature composition.
- The bleed sits at **Chapter 03** on service pages and at Chapter 01 on
  the homepage — same vocabulary, different placement, so a service page
  is recognisably of the same site without being a clone of it.
- CMS extends the **existing** schema. No parallel content model.

---

## 1. Inventory tiers — the scaling mechanism

Counted from the current library. "Usable" means the photograph is of that
room type and is not already carrying a different job on the homepage in a
way that would make reuse read as recycling.

| Service | Usable images | Tier | Page? |
|---|---|---|---|
| Bathroom Remodeling | ~7 | **A** | Yes |
| Kitchen Remodeling | ~6 | **A** | Yes |
| Whole-Home Renovation | ~6 | **A** | Yes |
| Home Additions | **2** | **C** | Yes, reduced |
| ADUs | **0** | **D** | **No page** |

### What each tier gets

**Tier A — 6+ images, including at least one detail and one context shot.**
The full nine chapters. Chapter 03 runs the signature composition with a
lead image, a supporting image and a detail. Chapter 06 gets real material
photography. Chapter 08 runs a four-frame contact sheet.

**Tier B — 4–5 images.**
Chapter 03 becomes a **diptych** — two images at deliberately unequal size
— rather than a three-image spread. Chapter 06 keeps one material detail
and carries the rest typographically. Chapter 08 drops to three frames.

**Tier C — 2–3 images.**
Chapter 03 is **removed entirely**, not thinned. A "featured
transformation" built from two photographs is a claim the evidence does
not support. The strongest image is promoted to Chapter 01, the second
becomes Chapter 06's single material detail, and the page leans on
Chapters 02, 04, 05 and 07 — which are typographic by nature and are the
chapters that actually answer a homeowner's questions. Chapter 08 pulls
from *other* services and is labelled truthfully ("Recent work across
Tuolumne County", not "Additions we have completed").

**Tier D — 0–1 images.**
**No service page is published.** The service remains a row in the
homepage's Chapter 04, exactly as ADU does today. This is the one rule in
the document with no exceptions: a page whose premise is "photography
carries the narrative" cannot ship without photography. Shipping one would
break the standard the homepage just set, and it would be doorway content
in substance even if not in intent.

ADU therefore stays unpublished until real photographs exist. When they
do, it promotes through the tiers automatically — no redesign, just a
higher tier.

### Why tiers rather than "hide empty sections"

Conditional sections that simply disappear leave a page that feels
abridged. Tiers **re-compose** what remains so the result reads as
composed for its own content. That difference is the whole reason the
system won't look templated.

---

## 2. The nine chapters

Journey mapping, so the emotional order the brief specifies is explicit:

| # | Chapter | Journey stage | Ground | Axis | Density | Conditional |
|---|---|---|---|---|---|---|
| 01 | Arrival | Inspiration | Warm white | Vertical | Sparse | No |
| 02 | Why This Room Matters | Inspiration → Understanding | **Ink (dark)** | Vertical | **Very sparse** | No |
| 03 | Featured Transformation | Evidence | Warm white | **Asymmetric** | **Layered** | Tier A/B only |
| 04 | What Changes | Understanding | Haze | Vertical | **Dense list** | No |
| 05 | Process | Trust | Granite | **Horizontal** | Structured | No |
| 06 | Materials & Craftsmanship | Trust → Confidence | Warm white | Vertical | Medium | Photo-dependent |
| 07 | Questions Homeowners Ask | Confidence | **Oak (warm)** | Vertical | Dense text | No |
| 08 | Related Projects | Evidence | Haze | Grid-of-unequals | Medium | Needs ≥3 projects |
| 09 | Consultation | Consultation | **Ink (dark)** | Vertical | Focused | No |

Reading down the Ground and Axis columns, no value repeats consecutively —
the same verification the homepage's variance map performs. Chapter 05 is
the only horizontal axis on a service page, mirroring the role Chapter 07
plays on the homepage: one deliberate axis break, late, where it lands
hardest.

### 01 · Arrival — *Inspiration*

**Purpose.** Make the homeowner feel the outcome before a single word about
construction. This is the page's emotional entry point and its LCP.

**Content.** Eyebrow (service name, small caps). H1 — a large emotional
statement, not a keyword string. A short supporting paragraph, ~2
sentences. Primary CTA plus a quiet secondary link. Breadcrumb.

**The deliberate difference from the homepage hero.** The homepage's hero
bleeds off the right edge; that device is spent and stays unique to it.
The service arrival is **contained** and **mirrors the axis** — image
left, copy right — so it is recognisably the same language read the other
way round, not the same composition reused. The image is a tall portrait
(the library's native orientation), capped by height so the H1 always
clears the fold.

**Breadcrumb into the Datum rail.** The existing `.datum` rail already
carries a chapter number and label vertically down the left edge. On
service pages it also carries the breadcrumb — `Home / Services /
Kitchen` — set in the 11px annotation register. This reuses an approved
component for a new job instead of adding a horizontal breadcrumb strip
above the fold, which every contractor site has and which would be the
first thing to make this look ordinary.

**H1 discipline.** "Kitchen Remodeling in Tuolumne County" is a title tag,
not a headline. The H1 is the emotional statement; the keyword lives in
`<title>`, the breadcrumb, and the body copy where it occurs naturally.
This is an SEO *strength*, not a sacrifice — see §6.

### 02 · Why This Room Matters — *Inspiration → Understanding*

**Purpose.** Establish emotional relevance before any remodeling talk. The
brief's examples are exactly right in register.

**Content.** One short statement, set in **Fraunces italic** — this is one
of the page's two sanctioned Fraunces appearances. Below it, one paragraph
in Inter at a narrow measure. Nothing else. No image, or at most a single
narrow context fragment.

**Composition.** Dark ink ground, very sparse, generous void. Inherits the
homepage's Chapter 02 treatment directly. The restraint is the point: this
chapter is the page's held breath between the photograph above and the
argument below.

**Why dark here.** It separates the emotional claim from the evidence that
follows, and it guarantees Chapters 01 and 03 — both warm white and both
image-led — never touch.

### 03 · Featured Transformation — *Evidence* · **the signature moment**

**Purpose.** One complete project at magazine depth. This is where the page
earns belief, and it is the page's signature composition.

**Content.** Project title. Narrative paragraph. A drawn spec table —
Scope, Materials, Duration, Location, Completion — populated only with
fields that are actually known. Construction observations as a short
annotated note. Lead photograph, supporting photograph, detail crop.

**Composition — this is where the page spends its one edge-bleed.** The
lead photograph runs off the right viewport edge at ≥1024px, using the
same padding-removal mechanism proven on the homepage hero (never a
negative margin, which produced a 1464px document in a 1440px viewport).
Because the homepage bleeds at Chapter 01 and service pages bleed at
Chapter 03, the two page types share a vocabulary without sharing a
silhouette.

**Three variants, assigned by inventory — never by taste:**

- **Held frame** (Tier A, 3+ images incl. a detail): lead image bleeds
  right, spec table sits in the void left of it, detail plate hangs off
  the lead's lower-left corner with the 7px warm-white print mount.
- **Diptych** (Tier B, 2 images): two images at deliberately unequal size
  on a broken baseline — roughly 60/40 — with the spec table beneath the
  smaller. No bleed at this tier; the bleed needs a large image to be
  worth spending.
- **Absent** (Tier C/D): chapter removed. See §1.

**Spec table honesty.** `projects.duration_weeks` and `completion_year`
already exist in the schema, which resolves the gap flagged during the
homepage build — the Duration row the storyboard wanted but had no data
for. Rows with no value are **not rendered**; they are never filled with
"Varies" or "Contact us".

### 04 · What Changes — *Understanding*

**Purpose.** What the homeowner actually gains. Outcomes, not features.

**Content.** 4–6 entries. Each is a short outcome title plus one or two
sentences: storage, flow, light, function, comfort, accessibility, resale.
Written as consequences — "You stop losing the counter to the toaster" not
"Ample counter space".

**Composition.** This is the page's **dense list**, and it reuses the
approved `craft-row` role system from homepage Chapter 04 — the five
descending roles, no two adjacent alike. At Tier A two entries carry a
small image; at Tier B/C all entries are typographic, using the `plain`
role with its burgundy left rule, which the homepage already proves reads
as deliberate rather than unfinished.

**Why reuse craft-row.** It is the component that most strongly signals
"this is the same site", and it was built precisely to stop a list of five
things resolving into five matching cards.

### 05 · Process — *Trust*

**Purpose.** Transparency as a competitive weapon. No jargon.

**Content.** Five numbered steps — Conversation, Walkthrough, Planning,
Construction, Completion — each a title and one plain sentence. Optionally
one honest note about what the homeowner will experience.

**Composition.** **Horizontal** — the page's single axis break, reusing the
homepage's Chapter 07 `.track` timeline. Large hairline-thin numerals,
scale without weight. On mobile it becomes a vertical sequence with the
numerals retained.

**Copy discipline.** The homepage's process copy is already in the right
voice ("We ask what is not working and roughly what you are hoping to
spend"). Service pages must not repeat it verbatim — see §6 on duplicate
content — so each service's steps are service-specific in detail while
identical in structure.

### 06 · Materials & Craftsmanship — *Trust → Confidence*

**Purpose.** Explain quality without naming brands. This is the chapter
that separates a builder from a contractor.

**Content.** 3–5 short passages on tile, stone, wood, cabinet
construction, paint preparation, trim, hardware. Each is a claim about
method, not product.

**Composition.** Tight **detail crops** — square, held high in frame. This
is the one chapter where the same image role repeats deliberately, because
a set of material details *is* a legitimate set. It is kept from becoming
a gallery by scale: the crops are small, captioned in the annotation
register, and the passages carry more visual weight than the images.

At Tier B/C this chapter is mostly typographic with a single detail crop,
and it remains one of the strongest chapters on the page — craftsmanship
claims are verbal as much as visual.

### 07 · Questions Homeowners Ask — *Confidence*

**Purpose.** Answer real anxieties: permits, timeline, living in the home
during work, dust, communication, budget, change orders.

**Content.** 5–8 question/answer pairs, CMS-managed per service. Questions
phrased the way a homeowner would say them.

**Composition.** Warm oak ground — the page's only warm chapter, placed
late where reassurance belongs. Questions as `h3`, answers in Inter at a
generous measure. Hairline between pairs, burgundy tick on the first.

**Implementation note (no JavaScript).** Native `<details>`/`<summary>`.
It is keyboard accessible for free, needs no script, and — critically —
its content is in the DOM and indexable whether open or closed. An
accordion built in JS would fail all three. Default state: first item
open, rest closed.

**Why this chapter matters most for SEO.** These are the actual long-tail
queries homeowners type. Answered genuinely, this chapter is the page's
strongest ranking asset and requires no keyword manipulation whatsoever.

### 08 · Related Projects — *Evidence*

**Purpose.** Portfolio as editorial, not gallery. Route the visitor deeper.

**Content.** One larger project plus 2–3 supporting ones. Each: title,
location, project type, and a link to its detail page.

**Composition.** The approved **contact sheet** from homepage Chapter 05 —
uniform frames at the library's native 3:4, captions written *beneath* the
frame (never hover-only gradient overlays, which hid the caption from
anyone who never hovered and cropped portraits into landscape), opened by
a hairline with the 28px burgundy tick. One project is given a larger
frame so the set is not equal-weight.

**Honest labelling.** At Tier C, where related projects come from other
services, the filing label says so.

### 09 · Consultation — *Consultation*

**Purpose.** A premium ending. One clear action.

**Content.** Short warm heading. The existing `project-form` component,
with the service pre-selected in the project-type field. One reassurance
line. Warm photography as ground.

**Composition.** Dark ink, focused, reusing the homepage's Chapter 08
treatment. **No visual changes to any form control** — labels, tap
targets, focus rings and the CSRF/validation path stay exactly as shipped.
This chapter is the conversion point; it is the last place to be clever.

**Lead attribution.** `leads.source_page` already exists in the schema, so
which service page produced a lead is captured with no new columns.

---

## 3. Image composition specification

Every image on a service page is assigned exactly one **role**. Roles have
fixed crops and fixed scale relationships. This is the mechanism that
prevents "gallery-first" layouts.

| Role | Crop | Scale | Appears in | Count/page |
|---|---|---|---|---|
| **Hero** | Portrait 3:4, height-capped | Largest on page | 01 | 1 |
| **Lead** | Height-driven, bleeds right | Largest in chapter | 03 | 1 |
| **Supporting** | 4:3 landscape | ~50% of lead | 03, 08 | 1–2 |
| **Detail** | Square 1:1, held high | ~25% of lead | 03, 04, 06 | 2–5 |
| **Material** | Square 1:1, tight | Small, uniform set | 06 | 0–4 |
| **Context** | 3:2 landscape | Small, quiet | 04, 08 | 0–2 |
| **Sheet frame** | Portrait 3:4, uniform | Small, equal | 08 | 3–4 |

**Hard rules, inherited from the homepage:**

1. **Never equal weight** outside a contact sheet. Within a chapter, no two
   images share a size.
2. **One dominant image per chapter.** If a chapter has two candidates for
   dominance, it has a composition error.
3. **Portrait is the native orientation.** Twenty of twenty-one project
   photographs are phone-shot portraits. Any landscape frame is a crop that
   throws away pixels, so landscape roles are used sparingly and never for
   a hero. The homepage's old 2:1 tile — cropping a 1200×1600 portrait to
   landscape — is the exact mistake this rule prevents.
4. **`--radius-photo` (7px) on photography.** Square only where an image
   meets a viewport edge, since there is no edge there to round.
5. **Captions live outside the photograph**, on a hairline, opened by the
   28px burgundy tick.
6. **Focal points, not re-crops.** Every role that crops must respect the
   stored focal point (§5), because a centre crop of a portrait routinely
   lands on floor or ceiling.

**Mandatory implementation rule for any new image component:**

> `responsive_image()` emits intrinsic `width`/`height` for CLS. Browsers
> map those to presentational hints, and **`aspect-ratio` is ignored while
> both used dimensions are definite.** Every new rule that sets
> `aspect-ratio` must also set `height: auto`, or the crop is silently
> inert and the image renders at natural pixel height.

This cost real debugging time on the homepage — the Chapter 05 detail
plate rendered 600px tall instead of square, and Chapters 02 and 06 still
carry the latent version of the same bug. It is written here so no new
component repeats it.

**Second implementation rule:**

> Grid items default to `align-self: stretch`. A figure in a grid row will
> take its height from the *copy* column, and `height: 100%` on the image
> inherits it, overriding the role's crop. Media containers in role-based
> grids need `align-self: start`.

---

## 4. Per-page budgets — how "premium" stays premium

A page reads as art-directed when its devices are scarce. These budgets are
fixed and a service page may not exceed them:

| Device | Budget | Where, on a service page |
|---|---|---|
| Edge-bleed | **1** | Chapter 03 lead image (Tier A only) |
| Bold 700 headings | **3** | H1; Ch 02 statement; Ch 03 project title |
| Fraunces italic | **2** | Ch 02 statement; one homeowner quote |
| Signature composition | **1** | Chapter 03 |
| Type crossing a photograph | **0** | Reserved to the homepage |
| Accent colour per composition | **1** | Burgundy tick, rule, or numeral |

**Type-over-photograph is deliberately withheld.** It is the homepage's
single most distinctive gesture and Chapter 05 owns it. If every service
page repeated it, the homepage would stop being the reference standard and
become one page among many. Service pages get their scale from the bleed
and from unequal roles instead.

Everything else — h2 headings at 600, small labels in Inter at 11–12px
uppercase letterspaced, `--radius-photo`, hairlines, the Datum rail — is
inherited without variation. Cohesion comes from the constants; distinction
comes from the budgeted devices.

---

## 5. CMS architecture

**Principle: extend the existing schema. Do not build a parallel model.**

The database already has `services`, `projects`, `project_images`,
`project_categories`, `testimonials`, `pages`, `page_sections`,
`site_settings` and `leads`. What is missing is (a) a few columns, (b)
three join/child tables, and (c) **the admin CRUD modules, which do not
exist yet** — every content route in `Admin\PlaceholderController` is
currently an honest "coming in a later phase" screen, and the homepage's
own copy is a hardcoded array in `HomeController`.

That last point needs stating plainly: **the brief's "fully editable
without code" is not a small addition to this work — it is the larger half
of it.** The page templates can be built in a phase; the CMS to drive them
is its own phase.

### 5.1 Extend `services`

Chapter copy is modular, so it belongs in a child table rather than in ever
more columns:

```
services  (existing: title, slug, summary, description, icon,
           image_path, status, sort_order)
  + hero_image_path      VARCHAR(255)   -- Ch 01 hero
  + hero_image_alt       VARCHAR(255)
  + h1_statement         VARCHAR(255)   -- emotional H1, not the title
  + featured_project_id  INT UNSIGNED   -- drives Ch 03  (FK projects)
  + meta_title           VARCHAR(180)
  + meta_description     VARCHAR(320)
  + inventory_tier       ENUM('A','B','C','D')  -- derived, cached
```

`inventory_tier` is **computed** from linked project images, not typed by
hand, and shown in the admin as a read-only badge with an explanation
("Tier C — 2 usable images. Chapter 03 is not rendered."). This makes the
system's honesty visible to the editor instead of mysterious.

### 5.2 New: `service_sections`

Mirrors the existing `page_sections` pattern exactly, so it needs no new
concepts:

```
service_sections
  id, service_id (FK), section_key, heading, subheading,
  body MEDIUMTEXT, image_path, image_alt, sort_order, status
```

`section_key` ∈ `why_it_matters`, `what_changes_item`, `process_step`,
`materials_item`. Repeatable keys are ordered by `sort_order`, which gives
the editor "add another outcome" without a schema change.

### 5.3 New: `service_faqs`

```
service_faqs
  id, service_id (FK), question VARCHAR(255), answer MEDIUMTEXT,
  sort_order, status
```

Separate from `service_sections` because it drives `FAQPage` schema and
needs its own validation (a question must end in `?`, answers have a
minimum length to discourage thin content).

### 5.4 New: `service_projects` join

```
service_projects
  service_id (FK), project_id (FK),
  relationship ENUM('featured','related'),
  sort_order
  PRIMARY KEY (service_id, project_id)
```

Many-to-many, because a whole-home renovation legitimately belongs to both
Whole Home and Kitchen. `featured` drives Chapter 03; `related` drives
Chapter 08.

### 5.5 Extend `project_images` — the important one

```
project_images.image_role
  current: ENUM('gallery','before','after')
  becomes: ENUM('hero','lead','supporting','detail','material',
                'context','sheet','before','after')
  + focal_x DECIMAL(4,3) NOT NULL DEFAULT 0.500
  + focal_y DECIMAL(4,3) NOT NULL DEFAULT 0.500
```

The role enum is what makes the contact-sheet philosophy CMS-manageable:
the editor assigns a *role*, and the template decides the crop and scale.
An editor can reorder photography and change which image is dominant
without touching layout.

`focal_x`/`focal_y` are explicitly anticipated by
`app/Config/image-derivatives.php`, whose header states the values move to
"`focal_x` / `focal_y` columns on the image record … the shape is
deliberately the same, so the migration is a straight copy." The four
existing overrides port directly.

### 5.6 Admin modules required

Replacing placeholders, in dependency order:

1. **Projects** — CRUD, image upload with role + alt + focal-point picker,
   soft delete (the schema already has `deleted_at`). Alt text **required**
   on upload; this is how 20/20 alt coverage is preserved by construction
   rather than by review.
2. **Services** — CRUD, section repeaters, FAQ repeater, featured/related
   project pickers, hero image, SEO fields, live tier badge.
3. **SEO** — per-page title/description with character counters and
   generated fallbacks.
4. **Media** — a focal-point picker (click the subject on the image) is the
   single highest-value admin affordance for this photo library.

**Editor guardrails, not just fields.** The admin should refuse to publish
a service whose tier is D, warn when a spec-table row is empty rather than
inviting filler, and show the computed tier with its consequence. The
system's honesty has to be enforced where content is entered, or it erodes
one edit at a time.

### 5.7 Fallback behaviour

Every template value resolves through: **CMS value → sensible generated
default → omit the element.** Never a placeholder string. A missing
Location row is not rendered; it never says "Location: TBD".

---

## 6. SEO architecture

The instruction is that the page ranks because it is useful. The
architecture below follows from that rather than working against it.

### 6.1 Per-page metadata

- `<title>` — CMS `meta_title`, fallback
  `{Service} in Tuolumne County, CA | Barraza's Construction`. This is
  where the keyword belongs.
- `<meta name="description">` — CMS `meta_description`, fallback derived
  from `services.summary`. Unique per service, enforced by a uniqueness
  check in the admin.
- `<link rel="canonical">` — already emitted by the layout.
- Open Graph title/description/url already emitted; add `og:image` from
  the hero, which the layout currently lacks.

### 6.2 Heading structure

```
h1   The emotional statement                (exactly one)
h2   Why this room matters                   Ch 02
h2   Featured transformation                 Ch 03
h3     Project title
h2   What changes                            Ch 04
h3     Each outcome
h2   How the work goes                       Ch 05
h2   Materials and craftsmanship             Ch 06
h3     Each material passage
h2   Questions homeowners ask                Ch 07
h3     Each question
h2   Related projects                        Ch 08
h3     Each project title
h2   Start a conversation                    Ch 09
```

No skipped levels, one h1, and heading text that reads as language rather
than as keyword slots. The homepage already validates clean under this
discipline and the service pages inherit it.

### 6.3 Structured data

Emitted as `application/ld+json` with the existing CSP nonce pattern:

- **`Service`** — `name`, `serviceType`, `description`, `areaServed`
  (Tuolumne County), `provider` → the existing `GeneralContractor` node.
- **`FAQPage`** — built from `service_faqs`. Only emitted with ≥2 real
  pairs, and only for questions actually rendered on the page.
- **`BreadcrumbList`** — Home → Services → this service.
- **`ImageObject`** — the hero, with real dimensions from the manifest.

**Not emitted:** `aggregateRating`, `review`, `priceRange`, or `offers`
unless real data exists. The repository's existing structured data already
holds this line — it only populates fields it knows and suppresses the
licence number until a real one is configured. Fabricated ratings are both
a Google violation and a trust failure.

### 6.4 Duplicate content — the real risk

Nine chapters across five services is 45 sections, and a shared template
invites shared prose. Three defences:

1. **Chapter 02 must be service-specific by definition** — the brief's own
   examples differ completely per room. This chapter cannot be templated
   even if someone tried.
2. **Chapter 05 (Process) is the highest-risk chapter**, being structurally
   identical everywhere. Rule: the five step *titles* may be shared; the
   step *bodies* must be service-specific. A bathroom walkthrough and a
   whole-home walkthrough genuinely differ, and saying how is more useful
   than repeating generic copy.
3. **No homepage copy is reused verbatim.** The homepage's Chapter 04
   already contains a one-line description of each service; service pages
   expand rather than restate.

### 6.5 Internal linking

```
Homepage Ch 04 row  ──►  Service page
Service Ch 03/08    ──►  Project detail page
Service Ch 08       ──►  sibling Service pages ("related services")
Every chapter CTA   ──►  Consultation (same page, anchor)
Breadcrumb          ──►  Home, Services index
```

Anchor text is descriptive, never "click here" or "learn more" alone.

### 6.6 `sitemap.xml`

Currently lists only the homepage, with a code comment that it should be
extended. It must enumerate every **published** service and project, with
`lastmod` from `updated_at`. Draft and Tier-D services are excluded — a
sitemap advertising an unpublished ADU page would be a crawl error.

### 6.7 Why this ranks without manipulation

The FAQ chapter answers real long-tail queries. The spec tables provide
genuine entity data. The project pages create a legitimate internal link
graph. The copy is specific to a county, a company and actual jobs. There
is no doorway content because each page describes work that was really
performed — and where it wasn't, §1's Tier D rule means no page exists.

---

## 7. Project integration

### 7.1 The relationship

```
Service ──┬── featured project  ──►  Ch 03  ──►  /projects/{slug}
          └── related projects  ──►  Ch 08  ──►  /projects/{slug}
                                                       │
Project ──── project_type / category ──► back to Service
        └─── images (roles + focal) ──► drives every composition
```

`projects.slug` already exists and is unique, so `/projects/{slug}` is
available with no schema change.

### 7.2 The dependency that must be decided now

Chapter 08's whole premise is that every project links somewhere. But
**project detail pages do not exist**, and linking to a 404 is worse than
not linking — for the homeowner and for crawling.

Three options, in my order of preference:

1. **Build a minimal project detail page in the same phase.** Arrival,
   image sequence, spec table, related services, consultation — roughly
   four chapters reusing components this system already defines. It is
   less work than it sounds precisely because the components are shared,
   and it makes Chapter 08 honest immediately. **Recommended.**
2. **Render project cards unlinked** until detail pages ship. Safe, but
   Chapter 08 becomes a gallery — the exact thing the brief prohibits.
3. **Link to a filtered projects index** (`/projects?type=kitchen`).
   Requires an index page that also doesn't exist yet.

I recommend (1) and would scope it into the Kitchen reference
implementation, because Chapter 08 cannot be built truthfully without it.

### 7.3 Route additions

```
GET /services                      services index
GET /services/{slug}               service page          ← this system
GET /projects                      projects index        (later)
GET /projects/{slug}               project detail        (see 7.2)
```

Slug-based, resolved against `status = 'published'`, 404 otherwise. The
existing `Router` supports parameterised routes (`$params` is already
threaded through every controller signature).

---

## 8. ASCII wireframe — Tier A service page (desktop ≥1024px)

```
        ┌─ Datum rail: chapter no. + label + breadcrumb, vertical, 11px ─┐
        │
════════╪═════════════════════════════════════════════════════════════════
 01 · ARRIVAL                                          ground: warm white
        │
   │ ┌──────────────────────┐
   │ │                      │      KITCHEN REMODELING      ← eyebrow, 11px
   │ │                      │                                 caps, burgundy
   │ │   [ HERO PORTRAIT ]  │      The room where             ← h1, General
   │ │      3:4, height-    │      birthdays, holidays        Sans 700
   │ │      capped, 7px     │      and ordinary
   │ │      radius          │      Tuesdays happen.
   │ │                      │
   │ │                      │      Short supporting para,     ← Inter, 48ch
   │ └──────────────────────┘      two sentences, muted.
   │ ▬▬ FEATURED                                              ← burgundy tick
   │ Kitchen Remodel                 [ Start your project ]   ← caption line
   │ Tuolumne County                  See the work  →            on hairline
   │                          NOTE: contained, NOT bled.
   │                          Axis mirrored vs homepage hero.
────┼─────────────────────────────────────────────────────────────────────
 02 · WHY THIS ROOM MATTERS                            ground: INK (dark)
   │
   │        A kitchen is the only room                 ← Fraunces italic
   │        the whole house walks through.                400, 1 of 2
   │
   │        One paragraph, narrow measure,             ← Inter, muted
   │        generous void below. No image.
   │
   │        ( very sparse — the page's held breath )
────┼─────────────────────────────────────────────────────────────────────
 03 · FEATURED TRANSFORMATION            ★ SIGNATURE · ground: warm white
   │
   │  FEATURED PROJECT                                      ← eyebrow
   │
   │   Project title, two lines           ┌────────────────────────────────▶
   │   General Sans 700  (Bold 3 of 3)    │                                │
   │                                      │                                │
   │   Narrative paragraph about the      │      [ LEAD PHOTOGRAPH ]       │
   │   project, ~48ch, muted.             │       height-driven            │
   │                                      │       BLEEDS OFF RIGHT ─────────▶
   │   ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬            │       EDGE (the page's         │
   │   SCOPE      Full remodel            │       one bleed)               │
   │   ───────────────────────            │                                │
   │   MATERIALS  Quartz, tile            │       rounded left only —      │
   │   ───────────────────────            │       no edge to round right   │
   │   DURATION   9 weeks   ← real data   │                                │
   │   ───────────────────────      ┌─────┴──────┐                        │
   │   LOCATION   Jamestown         │ [ DETAIL ] │  ← square, 7px mount,  │
   │   ───────────────────────      │  1:1 crop  │    hangs off lower-left │
   │                                └────────────┘    corner, z above lead │
   │   ▬▬ Construction note: one honest observation.
   │      Empty spec rows are NOT rendered. Never "TBD".
────┼─────────────────────────────────────────────────────────────────────
 04 · WHAT CHANGES                                          ground: haze
   │
   │  h2 · What actually changes
   │  ──────────────────────────────────────────────────────────────
   │  01  Storage that              ┌───────────────────────────┐
   │      disappears                │                           │
   │      You stop losing the       │   [ DOMINANT · 4:3 ]      │  ← role:
   │      counter to the toaster.   │                           │    dominant
   │      See this work →           └───────────────────────────┘
   │  ──────────────────────────────────────────────────────────────
   │  02  Light                                        ┌─────────┐
   │      Two sentences on what changes.               │[DETAIL] │  ← small
   │                                                   │  1:1    │    square,
   │                                                   └─────────┘    far right
   │  ──────────────────────────────────────────────────────────────
   │ ▌03  Flow            ← plain role: burgundy left rule, NO image
   │ ▌    Typographic by design, not by omission.
   │  ──────────────────────────────────────────────────────────────
   │  04  Comfort                              ┌──────────────┐
   │      Two sentences.                       │ [ CONTEXT ]  │  ← 3:2, quiet
   │                                           └──────────────┘
   │      ( reuses approved craft-row roles · no two adjacent alike )
────┼─────────────────────────────────────────────────────────────────────
 05 · PROCESS                        ground: granite · axis: HORIZONTAL ◄─┐
   │                                          the page's one axis break   │
   │  h2 · How the work actually goes
   │
   │   01           02            03            04            05
   │   ─────        ─────         ─────         ─────         ─────
   │   A            We walk       Scope and     We build      You get
   │   conversation the property  estimate                    your house
   │                                                          back
   │   Sentence.    Sentence.     Sentence.     Sentence.     Sentence.
   │
   │   ( large hairline-thin numerals — scale without weight )
   │   ( mobile: becomes vertical, numerals retained )
────┼─────────────────────────────────────────────────────────────────────
 06 · MATERIALS & CRAFTSMANSHIP                        ground: warm white
   │
   │  h2 · What we use, and why
   │
   │   ┌────┐  TILE                    ┌────┐  CABINETRY
   │   │ ▨▨ │  Method, not brand.      │ ▨▨ │  How boxes are built.
   │   └────┘  Two sentences.          └────┘  Two sentences.
   │
   │   ┌────┐  PAINT PREP              ┌────┐  TRIM & HARDWARE
   │   │ ▨▨ │  Two sentences.          │ ▨▨ │  Two sentences.
   │   └────┘                          └────┘
   │
   │   ( material role: small uniform squares — the ONE place a repeated
   │     size is legitimate, because a set of samples IS a set.
   │     Kept from being a gallery by scale: copy outweighs image. )
────┼─────────────────────────────────────────────────────────────────────
 07 · QUESTIONS HOMEOWNERS ASK                       ground: OAK (warm)
   │
   │  h2 · Questions homeowners ask
   │  ▬▬
   │  ▸ Do I need a permit?                              ← h3 in <summary>
   │    Genuine answer, generous measure.                  native <details>
   │  ──────────────────────────────────────────────       NO JavaScript
   │  ▸ Can I live in the house while you work?
   │  ──────────────────────────────────────────────
   │  ▸ How do you handle dust?
   │  ──────────────────────────────────────────────
   │  ▸ What happens if I change my mind?
   │
   │   ( content in DOM whether open or closed → indexable + accessible )
────┼─────────────────────────────────────────────────────────────────────
 08 · RELATED PROJECTS                                      ground: haze
   │
   │  ▬▬ ALSO COMPLETED — TUOLUMNE COUNTY          ← filing label, not h2
   │  ────────────────────────────────────────────────────────────────
   │   ┌──────────────────┐   ┌────────┐  ┌────────┐  ┌────────┐
   │   │                  │   │        │  │        │  │        │
   │   │  [ LARGER ONE ]  │   │ 3:4    │  │ 3:4    │  │ 3:4    │
   │   │      3:4         │   │        │  │        │  │        │
   │   │                  │   └────────┘  └────────┘  └────────┘
   │   └──────────────────┘   02          03          04
   │   01                     Title       Title       Title
   │   Title                  TYPE        TYPE        TYPE
   │   TYPE · LOCATION
   │        ↓ every frame links to /projects/{slug}
   │   ( contact sheet, captions BENEATH the frame, never hover-only )
────┼─────────────────────────────────────────────────────────────────────
 09 · CONSULTATION                                     ground: INK (dark)
   │
   │        h2 · Start a conversation
   │
   │        One warm reassurance line.
   │
   │        ┌──────────────────────────────────┐
   │        │ Name                             │   ← existing project-form
   │        │ Email            Phone           │     component, UNCHANGED
   │        │ Project type  [Kitchen ▾]        │   ← pre-selected
   │        │ Message                          │
   │        │ Photo (optional)                 │
   │        │ ☐ consent                        │
   │        │        [ Send Request ]          │
   │        └──────────────────────────────────┘
   │        ( warm photography as ground · leads.source_page captures
   │          which service page produced the lead )
════════╧═════════════════════════════════════════════════════════════════
```

### Tier C variant (Home Additions — 2 images)

```
 01 ARRIVAL      strongest of the two images, contained
 02 WHY IT MATTERS   Fraunces statement, dark, no image
 ── 03 FEATURED TRANSFORMATION — REMOVED ────────────────────────────
    Two photographs cannot substantiate a "complete transformation".
    The chapter is removed, not thinned. Its evidence job passes to 08.
 04 WHAT CHANGES     all rows typographic (plain role, burgundy rule)
 05 PROCESS          unchanged — horizontal, no photography needed
 06 MATERIALS        one detail crop + typographic passages
 07 QUESTIONS        unchanged — the page's strongest chapter at this tier
 08 RELATED PROJECTS labelled "Recent work across Tuolumne County"
                     because the projects are from other services
 09 CONSULTATION     unchanged
```

Seven chapters instead of nine, recomposed rather than abridged — and the
page is still honest, still cohesive, still not a template.

### Mobile (375px) — all tiers

Single column throughout. The bleed reverts to contained below 1024px, as
the homepage hero does. Hero portrait squares off so the H1 clears the
fold. The Chapter 03 detail plate overlaps the lead's lower-left at 46%
width. Chapter 05 becomes vertical. Contact sheet drops to two columns at
the native 3:4. `text-wrap: balance` on every display heading, which is
what fixed the homepage's stranded-word problem across 320–430px.

---

## 9. What I need decided before implementation

1. **Project detail pages** — §7.2. I recommend building a minimal version
   alongside Kitchen, because Chapter 08 cannot be honest without it.
2. **ADU** — confirmed as no-page until photography exists? This follows
   from Tier D but it is a business call, not a technical one.
3. **Home Additions at Tier C** — accept the seven-chapter variant, or hold
   the page until more photography exists?
4. **CMS phasing** — build Kitchen against the extended schema with content
   seeded by migration first and the admin UI second, or build the admin
   modules first? The former shows you a real page sooner; the latter means
   no content is ever hardcoded. I lean to the former, with the explicit
   commitment that no service ships to production hardcoded.
5. **Photography commission** — the single highest-leverage investment
   available. Two Additions photographs and zero ADU photographs are the
   only reason two of five services cannot have full pages. Everything else
   in this system is already achievable with what exists.

---

## 10. Definition of done, per service page

Inherited from the homepage's validation standard, all measured rather
than assumed:

- No horizontal overflow at 320 / 375 / 390 / 430 / 768 / 1024 / 1440
- CLS 0; hero is the only preloaded LCP candidate
- No font fallback; no duplicate font fetches; no new font families
- Every image has alt text; every image has a role and a focal point
- Single h1, no skipped heading levels
- `<details>` FAQ works with JavaScript disabled
- Reduced-motion path renders everything visible
- Unique title and meta description; valid `Service` + `FAQPage` +
  `BreadcrumbList` schema with no fabricated fields
- Every project link resolves to a real page
- No copy repeated verbatim from the homepage or another service page
