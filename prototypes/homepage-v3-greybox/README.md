# Homepage v3 — Greybox Prototype Findings

**Phase:** structural / pacing / interaction validation only.
**Status:** built, measured, and stopped for review. No production implementation begun.

```
Run:   php -S localhost:8001 -t prototypes/homepage-v3-greybox prototypes/homepage-v3-greybox/serve.php
Open:  http://localhost:8001/
```

Use the black control bar at the bottom to switch Datum A/B, Chapter 03 A/B, Chapter 06
State A/B, grayscale/colour, and annotations. The right-hand readout reports live
viewport size, per-chapter height in `vh`, total scroll length, and estimated scan time.

---

## 1. Prototype location & files created

`/prototypes/homepage-v3-greybox/`

| File | Purpose |
|---|---|
| `index.html` | The eight chapters, all variants, annotations |
| `greybox.css` | Grayscale structural styling. No final type, colour, or polish |
| `greybox.js` | ~150 lines, zero dependencies. Toggles, Datum tracking, Ch03 state, measurement |
| `serve.php` | Dev-only static router mapping `/img/*` to real photos, with a path-traversal guard |
| `README.md` | This document |

## 2. Confirmation: no production files modified

Verified by timestamp — **no file under `app/`, `public/`, `routes/`, `database/`, or
`bootstrap/` was modified during this phase.**

`git status` does show 9 modified production files. **These are pre-existing, from the
earlier approved work in this project** (the consultation-section photo and the licence-number
plumbing). They were not touched here. This phase created only the four files above.

The prototype cannot interfere with the live application:

- It runs on **port 8001**, rooted at the prototype directory — the app runs on 8000 rooted at `public/`
- It shares **no code** with the front controller, and loads no application bootstrap
- `serve.php` is **read-only**, serves images only, and rejects path traversal (verified: `/img/../../../.env` → 404)
- The Chapter 08 form **submits nowhere**. No lead storage, no mail, no uploads, no DB writes
- Deleting the directory removes every trace

---

## 3. Image inventory

**24 files, but only 21 unique images.** Every image was opened and categorised.

### 3.1 Duplicates found

| Hash group | Files |
|---|---|
| A | `contact-sheet-hillside-deck.jpg` = `narrative-detail.jpg` = `narrative-result.jpg` |
| B | `narrative-progress.jpg` = `service-additions-detail.jpg` |

Group B matters: `service-additions-detail.jpg` is filed as an *additions* photo but is
actually the **roof tear-off** image. Any code selecting it by filename is mislabelling it.

### 3.2 ⚠ FINDING I-1 — the current hero is stock photography

`hero-full.jpg` (2200×1463) is a professionally-shot, HDR-processed dusk photograph of a
large two-storey stone-and-stucco **new build**. It is landscape, colour-graded, and has
corrected verticals. Every other image in the library is a phone-shot portrait snapshot.
The vernacular — heavy timber corbels, hipped roofs, that stone — reads Texas Hill Country,
not Sierra foothill.

**This is almost certainly not a Barraza's project, and it is currently the hero of the live
homepage.** A site whose entire argument is "we show you the honest middle" cannot open on a
stock photo of someone else's house. It is excluded from the prototype and should be deleted
from the library.

### 3.3 Project groupings

Groupings are inferred from matching materials — flooring, wall colour, ceiling, tile accent
bands. **All require client confirmation before publication.** No before/after pairing has
been asserted across unrelated projects.

| Group | Images | State | Confidence |
|---|---|---|---|
| **A — Hillside roof & deck** | `narrative-progress` (during) → `narrative-result` (after) | During → After | **High.** Same blue siding, roofline, deck, pines |
| **B — Whole-home, grey kitchen** | `transformation-progress` (during) → `transformation-complete`, `service-kitchen`, `contact-sheet-kitchen`, `service-wholehome-primary` | During → After | **High.** Identical LVP, sage walls, textured ceiling |
| **C — White kitchen** | `project-supporting-1`, `service-wholehome-detail` | After | Medium |
| **D — Standard bath** | `contact-sheet-bath-tub`, `contact-sheet-bath-vanity` | After | **High.** Same room, two angles |
| **E — Primary suite, lake view** | `service-bathroom`, `project-supporting-2` | After | **High.** Matching agate accent band + cedar ceiling |
| **F — Marble bath** | `project-featured` (after), `process-detail` (during?) | Unconfirmed | **Low — do not pair.** Tile patterns differ |
| **G — Marble slab surround** | `intro-detail` | After | n/a |
| **H — Carrara walk-in shower** | `hero-inset` | After, in use | n/a |
| **I — Blue two-storey exterior** | `hero-exterior`, `service-whole-home` | After | High as a pair, **but see I-2** |
| **J — Entry door** | `service-additions` | Final detailing | n/a |
| **REJECT** | `hero-full` | Stock | See I-1 |

### 3.4 ⚠ FINDING I-2 — Group I may not be a Tuolumne County project

The blue two-storey has **eucalyptus** in frame and flat coastal light. Eucalyptus is not
foothill oak woodland. This looks like a coastal or Bay Area job. It can still be used, but
not while claiming foothill positioning. Confirm the location.

### 3.5 ⚠ FINDING I-3 — there is not one single "before" photograph

**All 21 unique images are During, After, or detail. Zero are Before.**

Chapter 03's entire structure is Before → During → After. It cannot ship as specified.
The prototype renders the Before slot as an explicit empty placeholder rather than
substituting an unrelated image.

### 3.6 ⚠ FINDING I-4 — no addition or ADU photography exists

Two of the four services in Chapter 04 — Additions and ADUs — have no project evidence at
all. `service-additions.jpg` is an entry door, not an addition. On a page arguing "we show
you the evidence," this is the weakest row.

### 3.7 Resolution — do not enlarge

| Do not exceed ~600px display | 450×600 / 600×450 | `narrative-progress`, `narrative-result`, `hero-exterior`, `service-whole-home`, `transformation-progress`, `project-supporting-2`, `service-additions`, `contact-sheet-hillside-deck`, `narrative-detail`, `service-additions-detail` |
|---|---|---|
| Safe to ~1200px | 1200×1600 | `service-bathroom`, `service-kitchen`, `contact-sheet-kitchen`, `service-wholehome-primary`, `contact-sheet-bath-tub`, `contact-sheet-bath-vanity`, `process-detail`, `project-supporting-1`, `service-wholehome-detail`, `transformation-complete`, `hero-inset` |
| Mid | 768×1024 | `project-featured`, `intro-detail` |

**Consequence:** the two strongest hero-scale candidates — the roof During shot and the
lake-view primary bath — are both low-resolution. The prototype handles this by displaying
them at moderate size within composed frames, but re-shoots or originals should be requested.

---

## 4. Chapter architecture

| # | Chapter | Ground | Axis | Density | Purpose |
|---|---|---|---|---|---|
| 01 | Arrival | Light | Vertical | Sparse | Place, service, confidence. Finished work only |
| 02 | Listening | **Dark** | Vertical | **~60% empty** | Philosophy. Humanise |
| 03 | **The Middle Is the Proof** | Light tint | Vertical, **pinned** | Single object | **The argument** |
| 04 | Craft | Light | Vertical | **Dense list** | Services as evidence |
| 05 | Featured Home | Light tint | **Asymmetric** | **Layered** | Complete-project capability |
| 06 | Verifiable Trust | Mid | Vertical | Sparse | Human / checkable proof |
| 07 | The Schedule | Light | **Horizontal** | Structured | Reduce uncertainty |
| 08 | Begin | **Dark** | Vertical | Focused | Convert |

No two adjacent chapters share ground, axis, and density. Chapter 07 is the only horizontal
moment; Chapter 03 is the only pinned one.

---

## 5. Desktop pacing analysis

Measured, annotations off, Chapter 03 Variant A.

| Chapter | 1024×768 | 1280×800 | 1440×900 | 1920×1080 |
|---|---|---|---|---|
| 01 Arrival | 0.95 | **0.86** | **0.86** | **0.86** |
| 02 Listening | 0.93 | 1.06 | 1.04 | 1.00 |
| 03 The Middle | 3.65 | 3.65 | 3.58 | 3.49 |
| 04 Craft | 1.82 | 1.88 | 1.81 | 1.76 |
| 05 Featured | 1.39 | 1.62 | 1.60 | 1.63 |
| 06 Trust | 1.35 | 1.32 | 1.18 | 0.98 |
| 07 Schedule | 1.16 | 1.04 | 0.91 | 0.73 |
| 08 Begin | 1.18 | 1.12 | 0.99 | 0.83 |
| **TOTAL** | **12.50** | **12.61** | **12.02** | **11.32** |
| Total px | 9,602 | 10,086 | 10,820 | 12,229 |

- **Chapter 01 hits the 80–95vh target** at 1280 and above (0.86vh). ✓
- **Total 12–12.6 viewports.** For comparison the current live homepage is ~14.2vh. The
  redesign is *shorter* than what it replaces despite adding a signature chapter.
- **Pinned viewport equivalents: 1.** Chapter 03 only. ✓
- **Scan time** (fast scroll, no reading): **~21–24 seconds**
- **Engaged time** (reading at ~230wpm plus image dwell): **~4–6 minutes**

### 5.1 ⚠ FINDING P-1 — the content balance misses the approved ratio

Measured by vertical space at 1440×900, excluding Chapter 08 (conversion, not content):

| Category | Measured | Target | Delta |
|---|---|---|---|
| Completed results | **34%** | 45% | **−11** |
| Process & progress | **46%** | 35% | **+11** |
| People, planning, trust | **20%** | 20% | ✓ |

**Cause:** Chapter 03 alone is 3.58vh — 32% of the entire page. The signature chapter is
so structurally dominant that it pushes the page past the approved balance on its own.

This is the single most important finding of the prototype, because it is exactly the
failure mode the brief warned about: *"Do not turn the site into a gallery of demolition or
unfinished work. The process supports the outcome. It does not replace it."*

**Three remedies, in order of preference:**

1. **Trim the Chapter 03 pin track** from 3 viewports to 2.25 while keeping the 22/48/30
   state allocation intact. Saves ~0.8vh. Process → ~40%.
2. **Add one more finished image to Chapter 05** and a fourth to the Craft evidence strips.
   Results → ~40%.
3. Both together land at approximately **42 / 38 / 20**, within tolerance.

I recommend 1 + 2. Neither weakens the argument — the *proportion* of Chapter 03 given to
DURING stays at 48% either way.

---

## 6. Mobile pacing analysis

| Chapter | 375×812 | 430×932 | 768×1024 |
|---|---|---|---|
| 01 Arrival | 1.19 | 1.06 | 1.07 |
| 02 Listening | 1.02 | 0.92 | 1.09 |
| 03 The Middle | 2.45 | 2.16 | 2.54 |
| 04 Craft | 2.40 | 2.14 | 1.85 |
| 05 Featured | 1.96 | 1.73 | 1.97 |
| 06 Trust | 1.68 | 1.22 | 0.84 |
| 07 Schedule | **2.45** | 1.68 | 1.03 |
| 08 Begin | 1.70 | 1.46 | 1.14 |
| **TOTAL** | **15.01** | **12.50** | **11.62** |

- **No horizontal overflow at any width.** Verified at all seven target widths.
- Chapter 03 **unpins entirely** below 1024px → 2.45vh instead of 3.58vh.
- **⚠ FINDING P-2:** at 375px, Chapter 07 becomes the joint-longest chapter (2.45vh) because
  six horizontal columns become six full-width vertical blocks. At 15.01vh total, the small-phone
  experience is 25% longer than desktop. Recommend condensing stages 01–03 into tighter rows
  on mobile, since those three are the least feared and least in need of reassurance.

---

## 7. Datum — Option A vs Option B

### Option A — persistent vertical line, outer page edge
Hairline at a fixed x-position, full viewport height, carrying: a burgundy progress segment,
chapter-boundary ticks, the current chapter number, and a rotated chapter title.

- ✅ Continuously present — it becomes the identity element the brief asked for
- ✅ Provides global progress without a generic top progress bar
- ✅ Supplies the constant that *licenses* radical composition changes between chapters
- ⚠ **FINDING D-1 (fixed):** at first build the rotated label overlapped the copy. Required
  raising content clearance to `5.5rem`. It now measures **31px clearance** at 1920. The cost
  is a permanent ~124–145px left margin on every desktop chapter.

### Option B — section-contained
A per-chapter sticky label pinned to the chapter's own left edge.

- ✅ Lighter, no global fixed element, no permanent margin obligation
- ❌ Disappears and re-appears per chapter, so it never becomes a *thread*
- ❌ Reads as a wayfinding label, not an identity element
- ❌ No global progress

### ▶ Recommendation: **Option A.**

B is the safer, more conventional choice and is exactly why it fails the brief. The whole
purpose of the Datum is to be the one thing that does not move, so that everything else can.
A label that scrolls away cannot do that job. The 145px cost is real but affordable at
≥1280px, and both options collapse to the same top hairline indicator below 1024px.

---

## 8. Chapter 03 interaction — Option A vs Option B

### Option A — sticky frame, three states
Pinned with `position: sticky`. **The browser's scroll is never intercepted** — no wheel
listeners, no `preventDefault`, accurate scrollbar, working keyboard and anchor navigation.
State advances at 22% / 70% of the track. Verified: 5% → Before, 35% → During, 60% → During,
85% → After.

### Option B — progressive vertical sequence
Three stacked states, no pin. DURING gets emphasis compositionally (wider frame, tinted
full-bleed band, more copy) rather than temporally.

### Measured comparison

| | Variant A | Variant B |
|---|---|---|
| Scroll cost @1920×855 | 3.61vh | 3.13vh |
| Scroll cost @1440×900 | 3.58vh | ~3.10vh |
| Pinned viewports | 1 | 0 |
| DURING emphasis | **Temporal — enforced** | Compositional — skippable |

**⚠ FINDING C-1 — this corrected an assumption I had made.** Before measuring, I stated in
the creative direction that the unpinned variant would cost roughly half the scroll of the
pinned one. Measured, **it is only 13% cheaper.** Three stacked states occupy nearly as much
room as one pinned state plus its track. **Scroll length is therefore not a valid reason to
choose B.**

### ▶ Recommendation: **Option A.**

The strategic argument is that the middle deserves more attention than the ends. Variant A is
the only version that can *enforce* that, because it controls time. In Variant B a fast
scroller passes DURING in the same second as the other two states, and the argument
evaporates. Since the cost difference is only 13%, A wins on the merits.

**One production requirement:** in Variant A the two inactive states remain in the DOM at
`opacity: 0`, so a screen reader would announce all three consecutively. Production must
toggle `aria-hidden` and `inert` on inactive states. Content stays server-rendered — it is
hidden visually, never withheld from the HTML.

---

## 9. Chapter 07 — desktop and mobile treatment

**Desktop:** a static six-column horizontal band that **fits within a single viewport** at
≥1280px (0.73–1.04vh). No pin, no scroll-linking, no interception whatsoever. Emphasis on
stage 05 (Construction — the longest and most feared) is carried by a tint, not motion.

This was chosen over the scroll-linked horizontal option deliberately. The brief permits only
one major pinned interaction, Chapter 03 has claimed it, and a second scroll-linked section
would dilute the signature moment. A schedule is genuinely a horizontal object, so the
horizontal axis is semantic rather than decorative — which is what earns it.

**Mobile (<1024px):** converts to a numbered vertical sequence — number, stage, description
in a single row per stage. Verified rendering at 375 and 768. See finding P-2 on length.

---

## 10. Scrolling recommendation: **native scroll only. Do not adopt Lenis.**

Evidence from the prototype:

1. **The one interaction that needed pinning did not need a library.** Chapter 03 is achieved
   with `position: sticky` — a native CSS feature, zero JS, correct scrollbar, working anchors
   and keyboard navigation. Lenis adds nothing to it.
2. **Nothing here is continuously scrubbed.** Chapter 03 changes between three discrete
   states. Smoothing the input between discrete states is imperceptible — you are paying to
   smooth something the eye cannot see.
3. **Audience.** Tuolumne County's median age is near 50, on rural connections and
   mid-range hardware. Momentum scrolling that the user did not opt into reads as *lag*, not
   luxury, to exactly this group.
4. **Touch.** Mobile is where the pin is already removed; Lenis is disabled on touch anyway.
   It would only ever affect desktop.
5. **Reduced motion.** Native scroll plus sticky degrades perfectly with zero extra code.
6. **Budget.** Dropping Lenis removes ~9KB gzipped and, more importantly, removes ownership
   of the scroll loop and a documented class of anchor, scrollbar, and assistive-tech bugs.

**No A/B test is warranted.** The evidence is one-sided. If the goal is an expensive *feel*,
the budget is far better spent on image quality (§11) and on easing the reveal animations
than on smoothing the scroll wheel.

This also reduces the proposed production JS from ~87KB to **~78KB** gzipped.

---

## 11. Image optimisation — technical specification (for a later phase)

Not implemented in this greybox. Specified for build on one.com shared hosting.

### 11.1 Why this works without Node

One.com provides PHP with **GD** (and often Imagick). Derivative generation is a **one-time,
offline, CLI-invoked** operation, not a runtime service — so no persistent Node process, no
build step, no `vendor/` upload is required. Derivatives are committed as static files and
served directly by Apache.

Two invocation paths:
- `php bin/generate-image-derivatives.php` run locally or over SSH, output committed
- Triggered from the existing admin on upload, for CMS-added images later

### 11.2 Pipeline

| Stage | Specification |
|---|---|
| **Validation** | Verify with `getimagesize()` + `finfo` MIME, never the extension. Accept JPEG/PNG/HEIC-converted only. Reject >8MB or >8000px on any edge. |
| **EXIF orientation** | Read `exif_read_data()`, apply `imagerotate()`, then **strip all EXIF** (removes GPS — several of these are phone photos of clients' homes). Critical: 20 of 21 images are phone-shot portraits. |
| **Max source** | Downscale the working master to 2400px on the long edge before deriving. |
| **Derivatives** | `thumb` 320w · `card` 640w · `content` 1024w · `hero` 1600w and 2400w. **Never upscale** — an asset with a 450px source emits only `thumb` and `card`. |
| **Formats** | AVIF if `imageavif()` exists (PHP 8.1+ with support), WebP via `imagewebp()`, JPEG fallback via `imagejpeg()`. Emit `<picture>` with AVIF → WebP → JPEG. |
| **Compression** | AVIF q45 · WebP q78 · JPEG q82 progressive. Target ≤120KB at 1024w, ≤220KB at 1600w. |
| **Filenames** | `{slug}-{width}w.{ext}`, e.g. `hillside-roof-during-1024w.webp`. Content-hash suffix optional for cache-busting. |
| **Focal point** | Store `focal_x`/`focal_y` as 0–1 floats on the image record; emit as `object-position`. Solves the portrait-into-landscape crop problem that already required a manual fix on the live consultation photo. |
| **srcset** | `srcset` with real widths + `sizes` matching the CSS layout per slot. |
| **Originals** | **Preserve.** Store outside the web root at `storage/originals/`. They are the only re-derivable source, and several are already the sole record of a completed job. Never delete on reprocess. |

### 11.3 Expected gain

Current library is ~9MB of unoptimised JPEG. At the compression targets above, a full page
load should carry roughly **250–400KB of imagery** instead of several megabytes.

**This must land before any motion layer.** Adding JS to today's payload makes the site slower
and feel cheaper, which is the opposite of the goal.

---

## 12. Content still required — developer checklist

Nothing below has been fabricated anywhere in the prototype. Blanks render as em-dashes with
a visible annotation.

- [ ] **CSLB licence number** — three CSLB searches found no Barraza licence in Tuolumne County. Legally required in all advertising (B&P §7030.5)
- [ ] **Bond amount** and exact insurance wording
- [ ] **Confirmed service-area cities** — currently assuming Jamestown, Sonora, Tuolumne County
- [ ] **Real customer testimonials** with written permission — target quotes about *a problem handled well*
- [ ] **Confirmed phone number**
- [ ] **Confirmed email address**
- [ ] **Project names and locations** for Chapters 03 and 05
- [ ] **Before/During/After grouping confirmation** for Groups A, B, D, E — and rejection or confirmation of Group F
- [ ] **Owner / team photograph** — the single cheapest missing trust asset; would carry Chapter 06 State B on its own
- [ ] **Owner's own words** for the Chapter 02 philosophy statement
- [ ] **Permit coordination policy** — what is actually handled vs. the homeowner's responsibility
- [ ] **Warranty terms**, if any
- [ ] **ADU and addition capability** — plus photography, or reconsider leading with the service
- [ ] **Typical stage durations** for Chapter 07, or drop the week axis entirely
- [ ] **Location of the blue two-storey exterior** (finding I-2)
- [ ] **Confirmation that `hero-full.jpg` is stock** and may be deleted (finding I-1)
- [ ] **Verification of the two process commitments** written into Chapter 03 — material staged before tear-off, and findings photographed and priced before being covered

---

## 13. Recommendation — what should proceed to production

**Proceed:**

1. **The eight-chapter architecture.** It measures 12.0–12.6vh, shorter than the current
   14.2vh homepage, and no two adjacent chapters share ground, axis, and density.
2. **Datum Option A**, with the 5.5rem clearance and the sub-1024px collapse.
3. **Chapter 03 Variant A** (sticky), with `aria-hidden`/`inert` on inactive states, and the
   pin track trimmed to ~2.25 viewports per finding P-1.
4. **Chapter 07 static horizontal**, with the mobile vertical conversion condensed per P-2.
5. **Native scroll. No Lenis.**
6. **Chapter 04 unequal-row treatment** — it reads as a list, never as four cards.
7. **Chapter 06 State B as the default**, upgrading to State A only when a real quote exists.
8. **The image pipeline in §11, before any motion work.**

**Reject or hold:**

| Concept | Why |
|---|---|
| `hero-full.jpg` and any stock photography | Contradicts the entire strategic argument. Delete |
| Lenis smooth scroll | No measurable benefit here; wrong for this audience. §10 |
| A second pinned or scroll-linked section | Chapter 03 must remain the only one |
| Chapter 03 Variant B | Cannot enforce the DURING emphasis. §8 |
| Datum Option B | Cannot function as an identity thread. §7 |
| Before/After pairing for Group F | Unverified. Would be a fabricated pairing |
| Leading Chapter 04 with Additions & ADUs | No supporting evidence exists. Finding I-4 |
| Any "PENDING" or placeholder label in production | Prototype annotation only |
| Fabricated testimonials, durations, budgets, or stage timings | Non-negotiable |

**Blocked until content arrives:** Chapter 03's Before state (I-3), Chapter 06 State A,
Chapter 04's ADU row (I-4), and every item in §12.

---

## 14. Open questions for review

1. **Content balance (P-1).** Accept remedies 1+2 to reach ~42/38/20, or accept 34/46/20 as-is?
2. **Before photography (I-3).** Can before photos be sourced or re-shot? Without them
   Chapter 03 is a two-state chapter, which materially weakens the signature moment.
3. **`hero-full.jpg` (I-1).** Confirm it is stock and may be deleted.
4. **Blue two-storey location (I-2).** Tuolumne County or not?
5. **Additions & ADUs (I-4).** Keep the service claim with no evidence, or restructure
   Chapter 04 around the three services that do have proof?
