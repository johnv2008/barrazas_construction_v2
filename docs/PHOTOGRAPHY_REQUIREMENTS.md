# Photography Requirements

**Status:** the highest-value investment available to this website.

Everything else on this site has been solved with design. This cannot be.
Two of five services currently cannot have a full page, and the reason is
not the build, the budget or the copy — it is that the photographs do not
exist. Design can compose honestly around thin evidence (see
`DESIGN_SYSTEM.md` §8.1) but it cannot manufacture evidence.

**The recommendation, stated plainly:** photograph every job from now on,
using the shot list below. It costs a few minutes per site visit and a
phone. It is worth more to this website than any further design work.

---

## 1. Why this is the constraint

| Service | Usable photographs | Tier | Consequence |
|---|---|---|---|
| Bathroom Remodeling | ~7 | A | Full page possible |
| Kitchen Remodeling | ~6 | A | Full page possible |
| Whole-Home Renovation | ~6 | A | Full page possible |
| Home Additions | **2** | C | Educational page only — no project showcase |
| ADUs | **0** | **D** | **No page exists** |

The library is 24 source images, of which one (`hero-full.jpg`) is stock
and rejected, and 19 are already committed to the homepage. There is very
little slack. Every service page, project page and portfolio entry drawn
from now on competes for the same small pool.

Note also what is *missing* rather than merely scarce: across 24 images
there is **exactly one verified before/after pair** (the roof replacement),
and **no** hardware macro, **no** materials-in-hand shot, and **no**
before shot for any kitchen or bathroom. The chapters that would be
strongest — "the middle is the proof", materials, craftsmanship — are the
ones the library can barely support.

---

## 2. The shot list

Ten roles per job. Each maps directly onto an `image_role` value in
`project_images`, so a completed shoot is filed without interpretation or
judgement calls at upload time.

| # | Shot | `image_role` | Min. per job | What it is for |
|---|---|---|---|---|
| 1 | **Before** | `before` | 2–3 | The honest starting point. Wide enough to read the whole room. |
| 2 | **During** | `during` | 3–5 | Open walls, subfloor, rough plumbing, protection in place. The single most persuasive category and currently the emptiest. |
| 3 | **After — wide room** | `hero` | 2–3 | The finished room from the doorway. This becomes the page hero. |
| 4 | **After — second angle** | `context` | 1–2 | The same room read the other way, showing how it connects to the house. |
| 5 | **Detail** | `detail` | 3–5 | One built element isolated: a niche, a bench, a mitred edge, a transition. |
| 6 | **Materials** | `material` | 3–4 | Tile, stone, counter surface, cabinet face, flooring — shot square and tight. |
| 7 | **Hardware** | `material` | 2–3 | Pulls, faucet, hinges, trim profile, fixtures. Close, filling the frame. |
| 8 | **Craftsmanship** | `detail` | 2–3 | The joint, seam, caulk line, or grout line that proves care. Shot to be looked at closely. |
| 9 | **Exterior** | `context` | 1–2 | The house as a house, so a local homeowner can place it. |
| 10 | **Context** | `context` | 1–2 | The room in use, or the view it looks onto — the human frame. |

**Minimum viable job: 12 photographs.** Achieving that puts any service at
Tier A within two or three documented jobs.

### The two that matter most

**During shots.** They are the whole argument of the homepage's Chapter
03 ("Anyone can show you the after"), and there are currently two of them
in the entire library. Every competitor shows finished rooms; almost none
show the middle. This is the cheapest available competitive advantage —
it costs one photograph before the drywall closes.

**Materials and hardware.** Kitchen's signature composition is built from
tight material crops. Today those crops are cropped *out of* wide room
photographs, which limits how tight they can go before they soften. Four
purpose-shot squares per job would transform that chapter — and the same
four serve the Materials & Craftsmanship chapter on every page type.

---

## 3. How to shoot it

No equipment purchase required. A recent phone is sufficient; consistency
matters far more than resolution.

**Do**

- **Shoot portrait for rooms, square for materials.** Portrait is already
  the library's native orientation and every template is built around it.
  Square material shots crop to any frame without loss.
- **Same time of day, all natural light.** Turn ceiling lights off if
  daylight is available — mixed colour temperature is the fastest way for
  a set to look amateur, and it cannot be fixed later.
- **Stand in the doorway for the wide shot.** Back as far as possible,
  phone at chest height, held level. A tilted phone puts a lean on every
  vertical line in the room, which reads as carelessness even to people
  who cannot say why.
- **Shoot the same angle before, during and after.** A matched sequence is
  worth several times an unmatched one. Photograph the "before" from the
  doorway, note where you stood, and stand there again at the end.
- **Clear the frame.** Tools, cups, cords, bins. Thirty seconds of tidying
  outperforms any amount of editing.
- **Photograph more than feels necessary.** Deleting later is free.

**Do not**

- Use flash. It flattens the room and colours the tile wrong.
- Use a wide-angle or "0.5×" lens for rooms. It bends the walls and makes
  a well-built room look like a fisheye photograph.
- Edit, filter, or apply "auto enhance" before upload. Grading is applied
  once, consistently, at intake.
- Photograph an unfinished room and file it as `after`. Roles are the
  system's honesty mechanism; mislabelling one is worse than a missing
  photograph.

---

## 4. Per-image metadata

Three fields are captured at upload. All three exist to serve the page
templates, and the first is non-negotiable.

- **`alt_text` — required.** The site holds 20/20 alt coverage today and
  that is preserved by making the field mandatory rather than by auditing
  later. Describe what is visible, not what it is for: *"Quartz counter
  meeting a gloss white stacked-tile backsplash with under-cabinet
  lighting"*, not *"Beautiful kitchen"*.
- **`image_role` — required.** From the table above. This drives crop and
  scale; the template asks the role, never the filename.
- **`focal_x` / `focal_y`.** Click the subject. Twenty of twenty-one
  existing photographs are portraits, and a centre crop of a portrait
  routinely lands on floor or ceiling — the focal point is what lets one
  file crop correctly in a square, a 3:2 and a 4:5 frame. Four such
  overrides already exist in `app/Config/image-derivatives.php`, whose
  header states they migrate to these columns unchanged.

Optional: `caption` (editorial, appears beneath the frame on a hairline)
and a project association.

---

## 5. Consent and attribution

Before publishing any photograph of a client's home, confirm in writing
that the homeowner permits its use on the website. Record the city, never
the street address — the homepage and every template already use
`Tuolumne County` and `Jamestown` at that granularity deliberately.

Where a homeowner declines, the job can still contribute material,
hardware and craftsmanship shots, which contain no identifying
information about a house.

---

## 6. What changes when this exists

| Today | With 12 photographs per job |
|---|---|
| ADU has no page | ADU reaches Tier A after two documented jobs |
| Additions is educational only | Additions gains a real featured transformation |
| One verified before/after pair on the whole site | A "the middle is the proof" chapter on every service page |
| Material crops taken from room photographs | A purpose-shot material band — Kitchen's signature composition at full strength |
| Portfolio would repeat homepage images | A genuine contact-sheet portfolio |
| Project pages are text-led by necessity | Project pages become the strongest content on the site |

Two or three properly documented jobs move every remaining service from
"cannot be built honestly" to Tier A. That is the entire gap.
