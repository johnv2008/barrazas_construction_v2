# Barraza's Construction — Creative Direction

**Phase 2: The Public Experience**
Status: **Proposal — awaiting approval. No implementation has begun.**

This document supersedes the homepage composition described in `DESIGN_SYSTEM.md` §11.
It does not alter the token layer, the type scale, or the color system defined there — it
extends them. Backend, admin, auth, routing, database, and CMS architecture are untouched.

---

## 0. The Diagnosis

The current homepage is competent and forgettable. That is not a styling problem, and it
will not be solved by adding motion.

Read the current homepage copy with the logo removed:

> "Thoughtful remodeling for better everyday living."
> "Experience you can build on."
> "We learn about your goals and priorities for the project."

There is no sentence on the page that could not appear on any of the other 23 licensed
general building contractors in ZIP 95327. The site has no **argument** — no claim that is
both true of Barraza's and untrue of the competition.

Motion applied to a site with no argument produces an *animated template*. That is a worse
outcome than the current state, because it costs 87KB of JavaScript and buys nothing.

**So the first deliverable is not a layout. It is the argument.**

---

## 1. Creative Direction

### 1.1 The Insight

The customer is not shopping for beauty. They are managing fear.

Consider who actually loads this page. Tuolumne County has roughly 55,000 residents and a
median age near 50. This is a rural Sierra foothill county — Jamestown, Sonora, Columbia,
Twain Harte, Groveland — where a large share of homeowners are retirees or Bay Area
transplants who bought space. The homes are older: Gold Rush-era stock, mid-century ranches,
foothill cabins. They take a beating from fire season, snow at elevation, and hot dry summers.

That homeowner is about to consider spending $40,000 to $250,000 — often a meaningful
fraction of their net worth — on a decision they will make maybe once. They have heard the
stories. Everyone has. Deposit taken, contractor vanishes. Three-month job runs nine months.
Wall opens up and the price doubles.

They are not browsing for inspiration. **They are looking for a reason to stop being afraid.**

A Porsche site sells desire. This site sells **relief**. Every design decision that follows
is judged against that, not against how it would score on a gallery site.

This does not mean the site should be timid — the opposite. Relief at this price point
requires enormous confidence in the presentation, because a homeowner reads competence in
digital presence and competence in craftsmanship as the same signal. But it means awe is in
service of trust, never the reverse.

### 1.2 The Argument — "The Middle Is the Proof"

Every contractor shows before and after. It is the universal, exhausted grammar of the trade,
and homeowners have been trained to discount it entirely, because before-and-after proves
nothing. Anyone can buy a stock photo of a finished kitchen. Nobody can fake the middle.

**The middle is the torn-open wall. The subfloor. The rough-in. The crew on the roof at 7am.
The half-tiled bathroom with the drain still open.**

The middle is also *precisely what the homeowner is afraid of.* It is the part they cannot
picture, the part where the horror stories live, the part where they lose control of their
own house for six weeks.

Showing it plainly — with the same compositional care as the finished shot — is the single
most disarming thing this company can do. It says: *we are not hiding the hard part from you,
because we are good at the hard part.*

This is not a new idea invented for this document. `DESIGN_SYSTEM.md` line 106 already
identifies it:

> "Construction (process imagery): framing and structural work photographed with the same
> care as the finished result — visible craftsmanship mid-process (this is an underused trust
> signal most competitors skip entirely)."

The design system knew. The homepage never executed it. Chapter Three exists to execute it.

**Consequence:** the site's core visual device is **three states, not two.**
`Before → During → After`, with the During state given equal or greater dwell time.
No competitor in the county does this. It is ownable, it is true, and it is built from
photography Barraza's already has.

### 1.3 The Reframe on Photography

The brief says photography is not the hero, and the UI must make average photography look
intentional. Agreed — but I want to go further, because the photo library is being
misdiagnosed as a liability.

What is actually in `/assets/images/projects/`: work trucks in driveways. A ladder against a
freshly painted wall. A crew member on a porch. A roof mid-replacement. A bathroom with
marble tile down and the drain still open. Phone-shot, portrait orientation, uncorrected
verticals.

Judged as architectural photography, that is a weak library. **Judged as evidence, it is a
strong one** — because it is unmistakably real. The awkwardness is the authenticity. A
Tuolumne County homeowner has seen a thousand glossy stock kitchens and believes none of them.

So the strategy is not to disguise the photography behind clever layout. It is to **reframe
its register from "portfolio" to "documentation."** Small crops. Hard bleeds. Precise captions
with dates and locations. Images treated as exhibits, not as decoration.

This produces a second, practical benefit: several assets are only 450×600px. Documentation
register calls for small, cropped, fragmentary images — which is exactly the display size those
files can serve at full sharpness. **The constraint and the concept agree.** Asset resolution
is mapped per chapter in §9.4.

### 1.4 The Visual System — "The Datum"

The brief asks for an identity that survives removing the logo. Here it is.

In architectural drawing, a **datum** is the reference line from which every measurement is
taken. It is the one thing on the sheet that does not move.

The site carries a persistent **plumb line**: a hairline vertical rule, held at a fixed
position in the viewport, running the full height of the document. It carries chapter numbers,
tick marks at section boundaries, and the current chapter title in small letterspaced type.
It is the only element that persists across every chapter.

Why this is the right answer to "no two sections may look the same":

Eight radically different compositions risk reading as eight unrelated pages. The datum is
the thread. It permits *maximum* compositional variance precisely because one element is
absolutely constant. Variance without a constant is noise; variance against a constant is
rhythm.

Why it could only be a builder's site: it is drawing-board logic — plumb lines, tick marks,
sheet numbers, tolerances, precise small type — without a single hard hat, blueprint texture,
or hammer icon. It says "built" structurally rather than decoratively. This is the Olson
Kundig register: the structure is the ornament.

**Supporting devices, all from the same drawing language:**

- Chapter numbers set as thin large numerals — `01`—`08` — like sheet numbers
- Captions in small caps with real data: `SONORA, CA · MARCH 2025 · ROUGH-IN`
- Hairline rules (0.5px at 1x) used structurally to divide, never decoratively
- Measurements stated plainly where they exist: square footage, duration in weeks, elevation

### 1.5 The Palette — From the Place, Not From "Luxury"

Generic luxury is black, white, and gold. It is the reason every premium template looks alike.
This palette is drawn from the actual Sierra foothill landscape visible in Barraza's own
photographs.

| Role | Name | Source | Use |
|---|---|---|---|
| Existing | **Burgundy** | Brand, oxblood leather | Single accent per view. Unchanged. Precision instrument. |
| Existing | **Ink** | Wet slate | Dark chapters (02, 08), maximum contrast moments |
| Existing | **Haze / Stone** | Limewash, morning fog | Default warm ground |
| **New** | **Granite** | Foothill outcrop, cool mid-gray | Documentation register — captions, rules, technical type |
| **New** | **Oak** | Golden grassland, white oak | The single warm chapter (06, Trust). Human moments only. |
| **New** | **Pine** | Ponderosa, deep desaturated green | One accent, Chapter 07 only. Rare enough to feel deliberate. |

Three new tokens. Burgundy stays a precision instrument exactly as `DESIGN_SYSTEM.md` §2
specifies. Oak and Pine each appear in exactly one chapter — a color that appears once is an
event; a color that appears everywhere is a wash.

### 1.6 Typography — Same Kit, New Register

The existing kit is correct and stays: **General Sans** (display), **Inter** (body),
**Fraunces italic** (editorial accent, 1–2 appearances per page maximum).

The problem is not the fonts. It is that they are currently played at a single dynamic level —
every heading is roughly the same size, every body paragraph the same weight, nothing is
whispered and nothing is shouted. That flatness is a large part of why the page reads as
template.

The correction is **range**:

- **One** display moment per page, and it is Chapter Two's statement. Nothing else competes.
- Fraunces italic appears exactly **twice** on the homepage: the philosophy statement (Ch 2)
  and the homeowner quote (Ch 6). Both are human voice. Nowhere else.
- Technical and caption type gets genuinely small — 11–12px, letterspaced, granite. Confident
  smallness reads as expensive; everything-is-18px reads as default.
- Chapter numerals are large and hairline-thin — scale without weight, which creates presence
  without shouting. This is how the site gets scale contrast **without** the oversized
  headlines the brief prohibits.

---

## 2. User Journey

The emotional arc, and what each chapter must accomplish. The site is a single argument
delivered in eight movements, and the movements are sequenced by *emotional state*, not by
information category.

| # | Chapter | They arrive feeling | They leave feeling | The job |
|---|---|---|---|---|
| 01 | **Arrival** | Skeptical, scanning, one of six tabs open | "This is not what I expected from a contractor" | Earn 8 more seconds |
| 02 | **Philosophy** | Curious but guarded | "These people think about this differently" | Establish a mind, not a service |
| 03 | **Transformation** | Interested, still unconvinced | "They showed me the part everyone hides" | **The argument. Disarm the core fear.** |
| 04 | **Craftsmanship** | Warming, now self-interested | "They do my specific project" | Convert interest into relevance |
| 05 | **Featured Home** | Wants proof at depth | "That is a real house, a real timeline" | Substantiate with one deep case |
| 06 | **Trust** | Nearly convinced, needs permission | "People like me hired them and were fine" | Human proof. Social permission. |
| 07 | **Process** | Ready but anxious about logistics | "I know exactly what happens to me" | Remove the last operational fear |
| 08 | **Consultation** | Decided | "Let's begin" | Make the ask feel like a beginning |

**The pivot point is Chapter Three.** Everything before it is earning the right to make the
argument. Everything after it is substantiating the argument. If Chapter Three fails, the
rest of the page is decoration.

**Mobile journey note:** on mobile the arc is identical but compressed — chapters 04 and 05
carry less depth, and Chapter 03 changes interaction model entirely (§5.3). The emotional
sequence is never reordered, because the sequence *is* the design.

---

## 3. New Homepage Architecture

### 3.1 Current → Proposed

| Current (10 sections) | Disposition |
|---|---|
| Hero (full-bleed) | **Rebuilt** → Ch 01 Arrival |
| Trust strip (3 dot-separated facts) | **Deleted** — absorbed into Ch 01 footer line + Ch 06 |
| Intro / About (text + image, 2-col) | **Rebuilt** → Ch 02 Philosophy |
| Services (4 identical cards) | **Deleted as a grid** → Ch 04 Craftsmanship (typographic list) |
| Featured project (image + text, 2-col) | **Rebuilt** → Ch 05, editorial spread |
| Selected projects (4 identical cards) | **Deleted** — redundant with Ch 05, and the definition of repetitive cards |
| Why choose (4-icon benefit grid) | **Deleted** — this is the "corporate icons" the brief rejects |
| Process (5 numbered steps) | **Reframed** → Ch 07, homeowner-experience framing |
| Planning guide (5 text items) | **Moved off homepage** → dedicated `/planning-a-remodel` page |
| Consultation (text + form, 2-col) | **Rebuilt** → Ch 08 |
| — | **New** → Ch 03 Transformation |
| — | **New** → Ch 06 Trust |

### 3.2 The 30% Removal

**Deleted outright:** trust strip, why-choose grid, selected-projects grid.
**Relocated:** planning guide.
**Net:** 10 homepage sections → 8 chapters, with 4 sections' worth of content removed or moved,
while adding 2 genuinely new experiences. Word count on the homepage drops roughly 35%.

The single largest source of the current template feeling is **redundancy**. "Since 2006,
licensed, bonded, insured" currently appears **three times** — hero trust line, trust strip,
and why-choose grid. Saying a thing three times does not make it three times as credible; it
makes it sound rehearsed. It appears **once** in the new architecture, in Chapter 06, with
real numbers attached.

The planning guide relocation is not a demotion. As homepage filler it is a wall of
undifferentiated text with no design idea. As a standalone page it becomes a genuine SEO
asset targeting informational intent — permit questions, budget ranges, timeline expectations
— which is exactly the query class that captures homeowners 6–12 months before they are ready
to call. See §11.4.

### 3.3 Compositional Variance Map

Rule #1 verification. No two adjacent chapters share ground tone, primary axis, density, or
type register.

| # | Ground | Axis | Density | Focal device | Type register |
|---|---|---|---|---|---|
| 01 | Photographic | Vertical | Sparse | Image + low-left type | Display, confident |
| 02 | **Ink (dark)** | Vertical | **Very sparse** | Single statement | **Fraunces, editorial** |
| 03 | Haze | Vertical (**pinned**) | Single object | **One image, scrubbed** | Technical caption |
| 04 | Haze | Vertical | **Dense list** | Typographic rows | Large sans list |
| 05 | Haze | **Asymmetric grid** | **Layered/overlapping** | Multi-image spread | Editorial mixed |
| 06 | **Oak (warm)** | Vertical | Sparse | Single quote | **Fraunces, human** |
| 07 | Granite | **Horizontal** | Structured | Timeline | Precise, small |
| 08 | **Ink (dark)** | Vertical | Focused | Form | Quiet, conversational |

Reading down any column, no value repeats consecutively. Chapter 07 is the only horizontal
axis on the site — that is its surprise, and it lands hardest because seven chapters of
vertical rhythm precede it.

---

## 4. Section Storyboards

### Chapter 01 — Arrival

**Intent:** Earn eight more seconds. Signal immediately that this is not a contractor template.

```
┌────────────────────────────────────────────────────────────┐
│  ◇ BARRAZA'S              work  approach  process  contact │  ← nav, transparent over image
│                                                            │
│ │                                                          │  ← datum line (persistent)
│ │        [ full-bleed photograph, masked reveal ]           │
│ │                                                          │
│ │                                                          │
│ │                                                          │
│ │   TUOLUMNE COUNTY                                        │  ← eyebrow, letterspaced, small
│ │   Remodeling, built                                      │  ← H1, low-left, NOT centered
│ │   for the foothills.                                     │
│ │                                                          │
│ │   ┌──────────────┐   view the work →                     │  ← one solid CTA, one text link
│ │   │  Let's begin │                                       │
│ │   └──────────────┘                                       │
│ │                                                          │
│ ├──────────────────────────────────────────────────────────│
│ │ JAMESTOWN, CA · LIC #—— · BUILDING HERE SINCE 2006       │  ← single hairline, 11px granite
└────────────────────────────────────────────────────────────┘
```

**Composition notes.** Type sits low-left against the datum, not centered — centered hero type
is the single most template-coded layout decision available, and abandoning it costs nothing.
The trust line is one hairline-separated row of small caps at the very bottom edge: quiet,
specific, and factual rather than boastful. Two actions only, at clearly different weights.

**The surprise.** The hero does not scroll away. It is pinned, and Chapter 02 rises over it
like a curtain lifting. The first scroll gesture produces something the visitor did not expect
— but quietly, with no jump, no snap, and no loss of scroll control.

---

### Chapter 02 — The Philosophy

**Intent:** Establish a mind, not a service. This is the dark, quiet, almost-empty moment —
maximum contrast against the photographic hero that precedes it.

```
┌────────────────────────────────────────────────────────────┐
│                                                        ░░░ │
│ │  02                                                  ░░░ │  ← image bleeds off right,
│ │                                                      ░░░ │    portrait, only ~60% visible
│ │                                                      ░░░ │
│ │  Most of a remodel                                   ░░░ │  ← Fraunces italic, ragged right
│ │  happens where                                       ░░░ │    max 3 lines. THE display
│ │  you can't see it.                                   ░░░ │    moment of the page.
│ │                                                      ░░░ │
│ │                                                      ░░░ │
│ │                        Framing, rough-in, the        ░░░ │  ← ONE paragraph, offset far
│ │                        things behind the drywall.    ░░░ │    right, small, ~55ch measure
│ │                        We build those the way we     ░░░ │
│ │                        build the parts you'll        ░░░ │
│ │                        show your friends.            ░░░ │
│ │                                                      ░░░ │
└────────────────────────────────────────────────────────────┘
```

**Composition notes.** Roughly 60% of this viewport is empty ink. That emptiness is the entire
design — it is the "remove 30%" rule made visible, and after a dense photographic hero it
reads as a held breath. The statement and the paragraph are on *different vertical bands* and
different horizontal alignments, so the eye travels rather than scans.

**The image is a fragment, not a picture.** Cropped hard, bleeding off the right edge, only
partially visible. This is the core technique for making phone photography look intentional:
a fragment reads as a deliberate compositional choice, while the same photo shown whole reads
as an amateur snapshot. It also means a 450×600 asset displays at full sharpness.

**Copy note.** The statement above is a placeholder written to demonstrate the register — it
states the thesis in the company's voice. Final copy should come from Miguel Barraza's actual
words. See §11.3.

---

### Chapter 03 — Transformation ★ THE SIGNATURE MOMENT

**Intent:** Deliver the argument. This is the chapter the entire site exists to reach.

```
┌────────────────────────────────────────────────────────────┐
│ │  03  TRANSFORMATION                                      │
│ │                                                          │
│ │     ┌────────────────────────────────────────────┐       │
│ │     │                                            │       │
│ │     │                                            │       │
│ │     │      [ ONE image frame — does not move ]    │       │  ← pinned. content scrubs.
│ │     │       state crossfades as you scroll        │       │
│ │     │                                            │       │
│ │     │                                            │       │
│ │     └────────────────────────────────────────────┘       │
│ │                                                          │
│ │     ├─────────────●───────────────────────┤              │  ← ruler. tick per state.
│ │     BEFORE      DURING                 AFTER             │    DURING gets the longest run
│ │                                                          │
│ │     APRIL 2025 · ROUGH-IN COMPLETE                       │  ← caption changes per state
│ │     Subfloor replaced, new drain line set, wall           │
│ │     opened for the window we added.                       │
└────────────────────────────────────────────────────────────┘
```

**Composition notes.** A single object, held still, while its *content* changes underneath the
scroll. Not a slider — sliders are clicked and therefore skipped. A scrub is *inhabited*:
the visitor's own scroll gesture performs the transformation, which is a fundamentally
different psychological experience from watching one.

**The argument, expressed as timing.** The DURING state occupies roughly 50% of the scrub
distance — more dwell than Before and After combined. Every competitor's site skips this
frame entirely. Barraza's lingers on it. The caption is specific and unglamorous: *subfloor
replaced, new drain line set.* That sentence is worth more than any adjective on the current
homepage.

**Why this makes average photography an asset.** A mid-construction phone photo is *supposed*
to look raw. In this frame its rawness reads as documentary evidence, not as a failure of
production value. The chapter converts the library's greatest weakness into its proof.

---

### Chapter 04 — Craftsmanship

**Intent:** Convert general interest into "they do *my* project," without four identical cards.

```
┌────────────────────────────────────────────────────────────┐
│ │  04  WHAT WE BUILD                                       │
│ ├──────────────────────────────────────────────────────────│
│ │ 01   Kitchens                    the room everything     │  ← full-width rows.
│ │                                  else happens around  →  │    hairline between each.
│ ├──────────────────────────────────────────────────────────│
│ │ 02   Bathrooms                   small rooms, the        │
│ │                                  least forgiving woro →  │
│ ├──────────────────────────────────────────────────────────│      ┌──────────┐
│ │ 03   Whole Home                  when the house needs    │      │  image   │ ← follows cursor,
│ │                                  to change together   →  │      │ (masked) │   masked reveal
│ ├──────────────────────────────────────────────────────────│      └──────────┘
│ │ 04   Additions & ADUs            more house, without     │
│ │                                  leaving the county   →  │
│ ├──────────────────────────────────────────────────────────│
└────────────────────────────────────────────────────────────┘
```

**Composition notes.** Maximum density, immediately following Chapter 03's single-object
minimalism. That whiplash between sparse and dense *is* the rhythm the brief asks for.

Four rows, no cards, no boxes, no images at rest — just type and hairlines. The images live
in the interaction: on hover, a small masked image follows the cursor with damped lag. Small
and moving means low-resolution assets are undetectable, and it means the photography earns
attention rather than demanding it.

Clicking a row expands it in place via FLIP — the row *unfolds* into a short paragraph and two
supporting images rather than toggling open. Only one row open at a time.

---

### Chapter 05 — Featured Home

**Intent:** One project, at magazine depth. Substantiate the argument with a real address.

```
┌────────────────────────────────────────────────────────────┐
│ │  05  ONE HOUSE, START TO FINISH                          │
│ │                                                          │
│ │   ┌────────────────────────┐                             │
│ │   │                        │      A hillside house       │  ← narrow text column,
│ │   │      [ large image ]    │      that had stopped       │    ~48ch, NOT centered
│ │   │                        │      keeping the weather    │
│ │   │                        │      out.                   │
│ │   └────────────────────────┘                             │
│ │   SONORA, CA · 2025                     Body copy sits   │
│ │              ┌──────────┐               in a narrow      │
│ │              │  small   │               measure with     │
│ │              │  image   │  ← overlaps    generous lead.  │
│ │              └──────────┘     above                      │
│ │                                                          │
│ │   ┌─────────────────────────────────────────────┐        │
│ │   │ SCOPE      Roof, deck, exterior envelope     │        │  ← data table, small,
│ │   │ DURATION   9 weeks                           │        │    drawing-like
│ │   │ ELEVATION  1,780 ft                          │        │
│ │   └─────────────────────────────────────────────┘        │
└────────────────────────────────────────────────────────────┘
```

**Composition notes.** This is the only chapter with a genuinely asymmetric, layered grid —
three images at three distinct sizes, one overlapping another, one bleeding off an edge. The
scale relationships are deliberately unequal, which is what separates an editorial spread from
an image grid.

The stats table is the datum language applied to content: precise, small, factual. *Nine weeks*
and *1,780 feet* are worth more to an anxious homeowner than any amount of adjective.

---

### Chapter 06 — Trust

**Intent:** Human proof and social permission. The warmest moment on the site.

```
┌────────────────────────────────────────────────────────────┐
│ │  06                                                      │  ← OAK ground. only warm
│ │                                                          │    chapter on the site.
│ │      "                                                   │  ← hanging quote mark
│ │        They opened the wall, found the                   │  ← Fraunces italic, large
│ │        subfloor was rotted, and called me                │    2nd and final appearance
│ │        that afternoon with the number                    │
│ │        before they did anything.                         │
│ │                                                          │
│ │        — SARAH M., TWAIN HARTE · BATHROOM, 2025          │  ← small caps, granite
│ │                                                          │
│ │                                                          │
│ ├──────────────────────────────────────────────────────────│
│ │  LICENSE          CSLB #———— · verify at cslb.ca.gov  ↗  │  ← specificity as trust.
│ │  BONDED           $25,000 contractor's bond              │    real numbers, plainly set.
│ │  INSURED          General liability · workers' comp      │
└────────────────────────────────────────────────────────────┘
```

**Composition notes.** No icons, no statistics, no star ratings, no logo soup — the brief
rejects all of it, correctly. One quote, given room. Real first name, real town, real project
type, real year.

The chosen quote should be about **a problem being handled**, not about how nice the kitchen
looks. "They found rot and called me before they did anything" is the single most persuasive
sentence a contractor can put on a website, because it answers the exact fear — *what happens
when they open the wall and find something?* — that Chapter 03 raised.

Below it, credentials stated as data rather than badges. A verifiable license number that links
to the CSLB lookup is worth more than any trust seal, because the visitor can check it.

**⚠ This chapter is content-blocked.** See §11.3.

---

### Chapter 07 — Process

**Intent:** Remove the last operational fear. Reframe from workflow to experience.

The current process section describes **the contractor's workflow**: Consultation → Site Visit
→ Scope & Proposal → Construction → Final Walkthrough. That is an internal process document.
It answers zero of the questions a frightened homeowner actually holds.

Reframed to what the homeowner experiences and fears:

| Current (workflow) | Proposed (their fear, answered) |
|---|---|
| Consultation | *"Will I know what this costs before I commit?"* |
| Site Visit | *"Who will be in my house, and when?"* |
| Scope & Proposal | *"What happens when you find something unexpected?"* |
| Construction | *"Will I be able to live here while you work?"* |
| Final Walkthrough | *"How do I know when it's actually finished?"* |

```
┌────────────────────────────────────────────────────────────┐
│ │  07  WHAT ACTUALLY HAPPENS                               │
│ │                                                          │
│ │  ←─────────────────────── scrolls horizontally ────────→ │  ← ONLY horizontal moment
│ │  ┌───────────┐ ┌───────────┐ ┌───────────┐ ┌──────────┐  │    on the site
│ │  │ WEEK 0    │ │ WEEK 1-2  │ │ WEEK 3-7  │ │ WEEK 8   │  │
│ │  │           │ │           │ │           │ │          │  │
│ │  │ Will I    │ │ Who is in │ │ What if   │ │ How do   │  │
│ │  │ know the  │ │ my house? │ │ you find  │ │ I know   │  │
│ │  │ cost?     │ │           │ │ something?│ │ it's done│  │
│ │  │           │ │           │ │           │ │          │  │
│ │  │ [answer]  │ │ [answer]  │ │ [answer]  │ │ [answer] │  │
│ │  └───────────┘ └───────────┘ └───────────┘ └──────────┘  │
│ │  ├───────────────●──────────────────────────────────┤    │  ← schedule rule
└────────────────────────────────────────────────────────────┘
```

**Composition notes.** The axis change is the surprise, and it earns its place: a schedule is
genuinely a horizontal object, so the horizontal treatment is *semantic* rather than a trick.
Cool, precise, structured — deliberate tonal contrast against the warmth of Chapter 06
immediately before it.

---

### Chapter 08 — Consultation

**Intent:** The ask should feel like a beginning, not a lead-capture form.

```
┌────────────────────────────────────────────────────────────┐
│ │  08                                                      │  ← INK ground. return to the
│ │                                                          │    dark of Ch 02 — bookend.
│ │                    Let's begin.                          │
│ │                                                          │
│ │                    Tell us what you're thinking about.   │
│ │                    Miguel reads these himself.           │  ← a person, not a department
│ │                                                          │
│ │           Your name                                      │  ← underline-only fields.
│ │           ─────────────────────────────────              │    no boxes. no card.
│ │           Where's the project?                           │
│ │           ─────────────────────────────────              │
│ │           What are you thinking about?                   │
│ │           ─────────────────────────────────              │
│ │                                                          │
│ │           ┌─────────────────┐                            │  ← magnetic button
│ │           │  Send  →        │                            │
│ │           └─────────────────┘                            │
│ │                                                          │
│ │           Usually a reply within one business day.       │  ← specific promise
└────────────────────────────────────────────────────────────┘
```

**Composition notes.** The form is not a white card floating beside text — that is the
template arrangement, and it makes the form read as an advertisement widget. Here the form
*is* the chapter, on ink ground, with underline-only fields that feel like writing on paper
rather than filling in a database.

Labels are written as a person speaking: *"Where's the project?"* rather than *"Project City*."
The named human — *Miguel reads these himself* — is worth more than any amount of design.

**Backend note:** field names, CSRF handling, validation, and the existing
`project-form` submission contract are **unchanged**. This is a restyle and a re-label of a
working form, not a rebuild.

---

## 5. Interaction Storyboards

### 5.1 Principles

Every interaction must have **mass**. Nothing bounces, spins, or overshoots. Motion decelerates
into rest, never oscillates around it. If an animation draws attention to itself as an
animation, it has failed.

**Global easing:** `cubic-bezier(0.16, 1, 0.3, 1)` — a strong, unidirectional decel curve.
Long tail, no overshoot, feels weighted. This is the site's single signature ease; deviations
require justification.

### 5.2 Interaction Inventory

| Element | Trigger | Behavior | Duration |
|---|---|---|---|
| **Nav** | Scroll past hero | Transparent → haze, hairline appears | 400ms |
| **Datum** | Chapter enter | Number + label crossfade, tick extends | 500ms |
| **Primary button** | Hover | Magnetic pull toward cursor, max 6px offset | 300ms damped |
| **Primary button** | Hover | Burgundy fill sweeps from left edge | 450ms |
| **Text link** | Hover | Underline draws left→right | 350ms |
| **Craft row (04)** | Hover | Cursor-following image, masked reveal | 600ms, 0.12 lerp |
| **Craft row (04)** | Click | FLIP expand in place | 700ms |
| **Transformation (03)** | Scroll | State scrub, pinned | scrub: 1 |
| **Process (07)** | Scroll | Horizontal translate | scrub: 1 |
| **Form field** | Focus | Underline thickens, label rises | 250ms |
| **Form** | Submit success | Panel crossfades to human message | 600ms |

### 5.3 Mobile — Redesigned, Not Scaled

The brief is explicit that mobile must be intentionally designed. Several desktop interactions
have no mobile equivalent and must be *replaced*, not degraded:

| Chapter | Desktop | Mobile |
|---|---|---|
| **Datum** | Vertical plumb line, left | **Horizontal hairline progress bar** under sticky header |
| **01** | Pinned hero, curtain reveal | Standard reveal. **No pin** — pinning fights mobile scroll |
| **03** | Scroll-scrubbed pin | **Horizontal drag/swipe** between 3 states, same ruler |
| **04** | Cursor-following image | Thin image strip per row; tap to expand |
| **05** | Layered asymmetric grid | Re-stacked with **deliberate** size variance retained |
| **07** | Horizontal scroll-scrub | **Native horizontal swipe carousel**, snap points |

**Rationale for removing pins on mobile.** Scroll-pinning on touch devices fights the
browser's native scroll physics and URL-bar behavior, producing jitter on exactly the
mid-range Android hardware a rural county's users are most likely to carry. The experience
must be *equivalent in intent*, not identical in mechanism — Chapter 03's swipe gives the
visitor even more direct control over the transformation than the desktop scrub does.

---

## 6. Animation Storyboards

### 6.1 The Stack

| Library | Size (gz) | Purpose | License |
|---|---|---|---|
| GSAP core | ~50KB | Tween engine | Free (standard "no charge") |
| ScrollTrigger | ~25KB | Pin, scrub, enter/leave | Free |
| Lenis | ~9KB | Smooth scroll | MIT |
| SplitType | ~3KB | Line/word splitting | MIT |
| **Total** | **~87KB** | | |

**Hosting fit.** All four are static JS files, self-hosted in `/assets/js/vendor/`. No Node,
no build step, no Composer — fully compatible with the one.com shared hosting constraint in
`README.md`. SplitType is specified over GSAP's SplitText specifically because its MIT license
is unambiguous.

**87KB is a real cost and I want to be honest about it.** It is affordable *only* if the image
work in §11.1 is done first. Today the image payload dwarfs the JS payload — shipping the
motion layer onto unoptimized 1200×1600 JPEGs would make the site slower and feel *cheaper*,
which is the precise opposite of the goal. **Image optimization is a hard prerequisite, not a
follow-up.**

### 6.2 Choreography

**Load (Chapter 01).**
```
0ms     Nav fades in                                    400ms
120ms   Hero image mask wipes upward, scale 1.06 → 1.00  900ms
400ms   H1 lines rise from mask, 80ms stagger            700ms
700ms   CTAs fade + rise 12px                            500ms
900ms   Trust hairline draws left → right                600ms
1100ms  Datum line draws top → bottom                    800ms
```
Total ~1.9s to full settle. Sequenced so the visitor's eye is led — image, headline, action,
proof — rather than everything appearing at once.

**Scroll reveals (all chapters).** A single shared pattern, applied consistently:
- Trigger at 80% viewport
- Mask reveal (`clip-path` inset), never opacity-from-zero
- Translate 24px → 0
- 800ms, signature ease, 60–90ms stagger within a group
- Fires **once** — re-animating on scroll-back is the hallmark of animation for its own sake

**Chapter 03 scrub.** Pin 300vh. Three states across the scrub; DURING allocated ~50% of the
distance. Crossfade with a slight clip-wipe, never a hard cut. The ruler indicator moves
linearly with progress. `scrub: 1` gives a 1-second catch-up lag, which is what produces the
sense of weight.

### 6.3 Reduced Motion — A First-Class Path

For a median-age-50 rural audience, this is not a compliance checkbox. It is a meaningful
share of real users.

Under `prefers-reduced-motion: reduce`:
- Lenis **disabled entirely** — native scroll restored
- All ScrollTrigger pins and scrubs **disabled**; Chapter 03 becomes three stacked captioned
  images, which reads perfectly well as a documentary sequence
- Reveals become instant, or a ≤200ms opacity fade
- Magnetic and cursor-following effects disabled
- **All content remains present and readable.** No content is ever gated behind motion.

---

## 7. Layout Sketches

Included inline per chapter in §4 above. Desktop wireframes at ~1440px reasoning width; the
`│` at left in each sketch marks the persistent datum.

**If it would be useful, I can produce a clickable static HTML prototype of these
compositions — greybox, no real motion — so you can judge the pacing in a browser before any
production code is written.** That is the standard next step at this stage and I'd recommend
it. Say the word.

---

## 8. Component Changes

### 8.1 Delete

| File | Reason |
|---|---|
| `app/Views/components/service-card.php` | Replaced by typographic rows (Ch 04) |
| `app/Views/components/project-card.php` | Selected-projects grid removed entirely |
| `.trust-strip` rules in `frontend.css` | Section deleted |
| `.benefits-grid` rules in `frontend.css` | Why-choose grid deleted |
| `.card-grid`, `.card-grid--2/--4` | No repeating card grids remain |
| `.planning-grid` rules | Moves with the content to its own page |

### 8.2 New

| File | Purpose |
|---|---|
| `app/Views/components/datum.php` | Persistent plumb line + chapter indicator |
| `app/Views/components/chapter-transform.php` | Ch 03 three-state scrub |
| `app/Views/components/craft-row.php` | Ch 04 expanding typographic row |
| `app/Views/components/feature-spread.php` | Ch 05 editorial layout |
| `app/Views/components/process-track.php` | Ch 07 horizontal timeline |
| `public/assets/js/vendor/*` | GSAP, ScrollTrigger, Lenis, SplitType |
| `public/assets/js/motion.js` | Central choreography, reduced-motion gate |
| `public/assets/css/chapters/*.css` | One file per chapter |
| `bin/generate-image-derivatives.php` | One-time CLI, GD/Imagick → WebP + srcset |

### 8.3 Modify

| File | Change |
|---|---|
| `app/Views/frontend/home.php` | Fully rewritten as 8 chapters |
| `app/Controllers/HomeController::homeData()` | Restructured to a chapters array. **Still returns a static array** — the Phase-2 CMS swap point described in its docblock is preserved exactly |
| `app/Views/components/project-form.php` | Restyle + re-label only. **Field names, CSRF, and validation unchanged** |
| `app/Views/components/header.php` / `footer.php` | Restyle to new register |
| `public/assets/css/variables.css` | +3 tokens (granite, oak, pine) |
| `app/Views/layouts/frontend.php` | Datum include, motion script, preload hints |

### 8.4 Untouched — Explicitly

`app/Core/*` · `app/Services/*` · `app/Middleware/*` · `app/Models/*` · `app/Validation/*` ·
`routes/*` · `database/*` · `bootstrap/*` · `public/install.php` · `.htaccess` · all admin views

No change in this proposal requires a database migration, a routing change, or a security
review.

---

## 9. What Stays

Not everything here is broken, and rebuilding what already works would be waste.

**Keep entirely:**
- The **design token layer** — `variables.css` is well-built and correctly structured
- The **type kit** — General Sans / Inter / Fraunces is the right pairing, self-hosted
- **Burgundy as a precision instrument** — the discipline in `DESIGN_SYSTEM.md` §2 is correct
- The **consultation form's** entire backend contract
- The **`homeData()` seam** — static array now, DB query later, one method to change
- **Semantic HTML and server-side rendering** — every word ships in the initial HTML
- The **Chapter 05 photograph** (`narrative-result.jpg`) and the hillside project it documents
- The **Jamestown address and Google Business Profile** wiring added last commit

**Keep and elevate:**
- The **planning guide content** — genuinely useful, wrong location
- The **process content** — right information, wrong framing
- **`DESIGN_SYSTEM.md`** — this document extends it; §11 (homepage composition) is superseded,
  everything else stands

---

## 10. Why Every Decision Improves the Experience

| Decision | Improvement |
|---|---|
| Lead with an argument, not a layout | The site becomes un-swappable. Remove the logo and the *thesis* still identifies the company |
| Three states, not two | Directly disarms the specific fear driving the purchase decision |
| The Datum | Delivers a logo-independent identity **and** licenses extreme compositional variance |
| Palette from the foothills | Escapes generic-luxury black/white/gold; ties brand to place, which supports local SEO positioning |
| Photography as documentation | Converts the asset library's weakness into the argument's evidence |
| Delete 4 sections | Removes triple-redundant trust claims that read as rehearsed |
| Process reframed to fear | Answers questions homeowners actually hold instead of describing internal workflow |
| A named human on the form | Single highest-leverage trust element on the page; costs nothing |
| Verifiable license number | Specificity beats badges — and it is legally required regardless |
| Reduced motion as a real path | For a median-age-50 audience this is a meaningful share of actual users |
| Mobile interactions replaced | Avoids the pinned-scroll jitter that would make the site feel broken on mid-range Android |

---

## 11. Risks, Blockers, and Honest Reservations

I would rather flag these now than discover them in build.

### 11.1 Image optimization is a hard prerequisite — **BLOCKER**

Current assets are unoptimized JPEGs, several at 1200×1600 with no WebP and no `srcset`.
Adding 87KB of motion JavaScript on top of that payload will make the site *slower and feel
cheaper*. This must be done first.

**Approach that fits the constraints:** a one-time PHP CLI script using GD or Imagick
(`bin/generate-image-derivatives.php`) generating WebP at 3–4 widths, committed as static
files. No Node, no build step, no shared-hosting conflict.

### 11.2 Lenis smooth scroll — **my one real reservation about the brief**

Momentum-based smooth scroll is standard on award-gallery sites, and on a median-age-50 rural
audience it frequently reads as *lag*, not luxury. It also overrides scroll behavior users have
configured deliberately, and it interacts poorly with some assistive technology.

**I recommend using it, but conservatively:** low lerp (~0.08), hard-disabled on touch, hard-
disabled under `prefers-reduced-motion`, and with native scrollbar behavior preserved. I would
also like to A/B it against native scroll before committing — this is the single element of the
requested stack where I think the gallery-site consensus may be wrong for *this* audience.

### 11.3 Chapter 06 is content-blocked — **BLOCKER**

There are no real testimonials in the repository. Chapter 06 requires:
- 1–3 genuine homeowner quotes, ideally about **a problem being handled well**
- First name, town, project type, year
- Written permission to publish

**I will not write fictional testimonials.** Fabricated social proof is both unlawful under
FTC endorsement rules and the precise opposite of a strategy built on documentary honesty. If
real quotes are unavailable, Chapter 06 should instead be built around the crew, the years, and
the verifiable credentials — still human, still warm, and still true.

Chapter 02's philosophy statement should likewise come from Miguel's actual words rather than
my placeholder.

### 11.4 The CSLB license number — still outstanding

Chapters 01 and 06 both display it, and the plumbing is already in place from earlier work
(`BUSINESS_LICENSE_NUMBER`). My three CSLB searches did not locate a Barraza license in
Tuolumne County. This needs resolving before launch — it is legally required in all advertising
under B&P Code §7030.5, and Chapter 06's entire credibility argument rests on a number the
visitor can independently verify.

### 11.5 SEO risks introduced by this redesign

As SEO lead, the things I will not let this design break:

- **All copy server-rendered by PHP.** GSAP animates existing DOM. **No text is ever injected
  by JavaScript.** Non-negotiable.
- **SplitType and screen readers.** Splitting text into per-line spans can cause
  letter-by-letter announcement. Mitigation: `aria-label` on the container, `aria-hidden` on
  split children, and `.revert()` after the animation completes.
- **LCP versus the cinematic reveal.** A hero fading from `opacity: 0` over 900ms sets a
  ~900ms LCP floor. Mitigation: reveal by `clip-path` mask with the image at full opacity
  underneath, `fetchpriority="high"`, and a `preload` hint. The pixels are painted early; only
  the mask animates.
- **Heading hierarchy survives the redesign.** One `<h1>` (Ch 01), `<h2>` per chapter, in
  document order regardless of visual position.
- **The planning guide page** becomes a genuine informational-intent asset — permits, budget
  ranges, timelines — with `FAQPage` schema. This is the query class that reaches homeowners
  6–12 months before they are ready to call.

**Separately, and more important than anything on this page:** for a local service business the
largest organic gains are not on the homepage at all. They are service × city landing pages
(Kitchen Remodeling in Sonora, Bathroom Remodeling in Twain Harte, ADUs in Groveland), a
complete Google Business Profile, and a steady review cadence. That is a Phase 3 conversation,
but I want it on record now so the redesign is not mistaken for an SEO strategy.

### 11.6 Scope

This is a substantial build — realistically 5 phases, not one pass:

| Phase | Contents |
|---|---|
| **A** | Image pipeline, motion infrastructure, datum, palette tokens |
| **B** | Chapters 01, 02, 08 — the frame |
| **C** | Chapter 03 — the signature. Highest risk, highest value |
| **D** | Chapters 04, 05 |
| **E** | Chapters 06, 07 — content-dependent |

Phase A is a hard gate. Phase C should be built and evaluated on its own before D and E,
because if the signature moment does not land, the architecture needs revisiting before more
is built on top of it.

---

## 12. Decisions I Need From You

1. **Approve or challenge the argument** (§1.2). Everything else is downstream of it. If "the
   middle is the proof" is wrong for this company, I need to know before anything is built.
2. **Lenis** — accept my conservative recommendation, or overrule me (§11.2).
3. **Testimonials** — are real quotes obtainable? This determines Chapter 06's design (§11.3).
4. **The license number** (§11.4).
5. **Greybox prototype first, or straight to Phase A?** I recommend the prototype.
