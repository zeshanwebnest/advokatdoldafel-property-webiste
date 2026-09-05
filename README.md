# Advantage Law Firm — website

Fifteen static pages, built with HTML, CSS and vanilla JavaScript.
No framework, no build step required to run, no third-party runtime dependencies
beyond the Google Fonts stylesheet.

---

## 1. Pages

| File                  | Page                |
| --------------------- | ------------------- |
| `index.html`          | Home                |
| `services.html`       | Main services       |
| `service-details.html`| Service details — contract law |
| `about.html`          | About us            |
| `team.html`           | Team                |
| `team-details.html`   | Team details        |
| `blog.html`           | Blog                |
| `blog-details.html`   | Blog details        |
| `contact.html`        | Contact us          |
| `dolda-fel-i-hus.html`| Dolda fel i hus — practice page |
| `dolda-fel-i-bostadsratt.html` | Dolda fel i bostadsrätt — practice page |
| `dolda-fel-tvister.html` | Dolda fel tvister — practice page |
| `allmanna-villkor.html` | Allmänna villkor — terms |
| `konsumenttvistnamnden.html` | Konsumenttvistnämnden — consumer disputes board |
| `faq.html`            | FAQ — vanliga frågor och svar |
| `kontakta-oss.html`   | Kontakta oss — contact page   |

Seven pages are rebuilds of live pages in this design system: the three practice
pages from `https://advokatdoldafel.se/dolda-fel-i-hus/`,
`/dolda-fel-i-bostadsratt/` and `/dolda-fel-tvister/`, the three informational
pages from `/allmanna-villkor/`, `/konsumenttvistnamnden/` and `/faq/`, and the
contact page from `/kontakta-oss/`. Their copy
is the client's and is **fixed**: every heading, paragraph, clause, card, button
label, FAQ entry and form field is the live page's, word for word, and every `#`
link still points at `#` exactly as it does there. None of them carries eyebrow
labels, because inventing them would have meant adding words — `.eyebrow-rule`
holds that position in the rhythm instead. The home page's "Läs mer om dolda fel i
hus" and "Läs mer om dolda fel i bostadsrätt" now link to the first two rather than
to `service-details.html` and `services.html`.

**The client's copy contains typos that the rebuilds reproduce on purpose.** On
`/faq/` several bolded lead words run into the text after them — "skadeståndför
kostnader", "hävning av köpeti särskilt allvarliga fall", "Fastighetsrätt–
exempelvis", "framställa krav.När ett fel" — because the source markup closes the
`<strong>` with no space. Adding the spaces would be an edit to the copy and the
word-stream check would flag it. Leave them, or get the client to fix the source.

**All seven are reachable from the site chrome.** The Swedish nav carries the three
practice pages and the contact page; the footer's Snabblänkar column carries the
terms page, the disputes board, the FAQ and the contact page (§4).

**`kontakta-oss.html` now owns every "Kontakta oss" link on the site.** Those
hrefs used to be absolute — `https://advokatdoldafel.se/kontakta-oss/` — in the
chrome and in the client's body copy, because there was no local equivalent. They
all point at the local file now. The destination is the same page; only the URL
form changed. `contact.html` is the older English template page and is left alone.

**Before changing any of the seven, re-run the content check.** Fetch the live page,
strip its tags, and diff the word streams both ways — that is how the rebuilds
were verified and it is the only thing standing between a layout change and a
silent edit to the client's copy. Two classes of difference are expected and
safe: `&nbsp;` where the rebuild has an ordinary space, and a `U+2060` word
joiner the live page carries before "Fuktskador". Anything else is a real
change.

**Home page sections, in order:** Hero → About → Services → Why Choose Us →
Call to Action → Team → Reviews → Latest Blogs → FAQ → Contact CTA → Footer.

**`service-details.html` sections, in order:** Page head → Request legal advice (form)
→ How we handle disputes → Agreements we draft, review and negotiate → Contract review
&amp; drafting → Who should hire a contract review lawyer → Common issues → Why choose us
→ Our contract service process → Reviews → FAQs → Related work → CTA.

That order and its content follow the live page at
`https://advantage.se/en/contract-law/`. The page head, the reviews band and the
closing CTA are this build's own components, unchanged. All six service cards on
`services.html` still point here — it is one template, so duplicate it per practice
area when the other five have their own copy.

---

## 2. Preview locally

```bash
python -m http.server 8080     # or: npx serve .   |   php -S localhost:8080
```

Then open <http://localhost:8080>. Opening `index.html` straight from disk works too,
except the OpenStreetMap iframe on the contact page, which needs `http://`.

---

## 3. Structure

```
/
  index.html … kontakta-oss.html   the sixteen pages (complete, standalone)
  build.ps1                      optional page assembler — see §4

  _partials/                     shared chrome (source for build.ps1)
    base.html                      HTML skeleton with {{TOKENS}}
    header.html                    top bar, header, mobile drawer
    footer.html                    footer

  _pages/                        page sources — front matter + <main> body
    <slug>.html
    head/<slug>.html               per-page LCP preload
    schema/<slug>.json             per-page JSON-LD

  assets/
    css/styles.css               the whole design system, one file
    js/main.js                   all behaviour, one file
    images/
      brand/                       logo, favicons, Open Graph images
      hero/                        hero and full-width banner images
      about/                       office and consultation photography
      property/                    houses, apartments and interiors — the home page set
      services/                    one image per practice area
      blog/                        article images
      team/                        portraits
```

`_partials/` and `_pages/` are source, never served. You can exclude them and
`build.ps1` when deploying.

---

## 4. The site chrome — Swedish header and footer

The header and footer are the client's, taken from `https://advokatdoldafel.se/`
and carried identically by all sixteen pages.

**Header.** Top bar (address, opening hours, phone, e-mail), then the logo, the
six-item Swedish nav and the phone link. There is no "Book a consultation"
button — the live header does not have one, and the six Swedish labels need the
room.

| Nav item                | Target                                        |
| ----------------------- | --------------------------------------------- |
| Hem                     | `index.html`                                  |
| Dolda fel i hus         | `dolda-fel-i-hus.html`                        |
| Dolda fel i bostadsrätt | `dolda-fel-i-bostadsratt.html`                |
| Dolda fel tvister       | `dolda-fel-tvister.html`                      |
| Artiklar                | `https://advokatdoldafel.se/artiklar/`        |
| Kontakta oss            | `kontakta-oss.html`                           |

Artiklar is the one item that still goes out to the live site, because there is no
local equivalent yet; build that page and its href is the only thing to change.
The five local pages carry `aria-current="page"` on their own nav item, in both
the header and the drawer.

Those Swedish labels are roughly 40% longer than the English ones they replaced,
so between 1000px and 1279px the nav tightens its gap, drops to `--fs-xs` and
hides the header phone link rather than wrapping to two rows. Below 1000px it is
the drawer as before.

**Footer.** Four columns — the brand blurb, **Snabblänkar**, **Kontaktinformation**
and **Prenumerera oss** — over a bottom bar carrying the copyright and three links
to `advantage.se` (Entreprenadrätt, Fel i entreprenad, Fastighetsrätt). Between
1040px and 1279px the columns are re-proportioned (`1.2fr 1fr 1.1fr 1.05fr`);
at the default `1.55fr 1fr 1.1fr .75fr` the social column is too narrow there and
the fourth tile drops to a second row.

**The social URLs are still placeholders except YouTube.** On the live site three
of the four icons carry no `href` at all and the Instagram-classed icon points at
a YouTube channel. The real YouTube URL is wired up; Facebook, Instagram and
LinkedIn point at the bare service homepages until the client supplies theirs.

Every page contains a full copy of the header and footer so the site runs without
tooling. To change them, edit one page's block and propagate it — or edit the
partial and rebuild, if the `_partials/` sources are restored:

```powershell
powershell -ExecutionPolicy Bypass -File .\build.ps1
```

It reads `_partials/*` plus `_pages/<slug>.html` and rewrites the root pages it has
sources for. The six rebuilt pages were written directly and have no `_pages/`
sources.

Front matter keys at the top of each `_pages/<slug>.html`:

| Key           | Purpose                                              |
| ------------- | ---------------------------------------------------- |
| `title`       | `<title>` and `og:title`                              |
| `description` | meta description and `og:description`                 |
| `canonical`   | absolute canonical URL                                |
| `ogimage`     | filename inside `assets/images/brand/`                |
| `ogalt`       | alt text for the share image                          |
| `ogtype`      | `website` (default) or `article`                      |
| `nav`         | which nav item gets `aria-current="page"`             |
| `preload`     | filename in `_pages/head/` — the LCP image preload    |
| `jsonld`      | filename in `_pages/schema/` — structured data        |

---

## 5. The logo

`assets/images/brand/advantage-logo.png` is the official file you supplied
(`Group-50.png`, 322 × 62, transparent background). It is used as-is in the header,
the mobile drawer and the footer.

It is the same artwork everywhere — no recoloured second file and no CSS filter. On
the dark grounds (footer, mobile drawer) it sits on a light plate so the near-black
mark and wordmark stay legible:

```css
.brand--inverse { background: var(--surface); padding: .5rem .8rem; border-radius: var(--r); }
.brand--inverse img { filter: none; }
```

Filtering it does not work: the mark is a dark square holding a white "A", so
`brightness(0) invert(1)` flattens both to one tone and the letter disappears.

**The palette is taken from the logo, not chosen separately.** The mark is near-black
and the ADVANTAGE wordmark is `#1F2839` — a deep, desaturated navy-charcoal. That
value drives the whole scheme, with brass as the single supporting accent.

If you later have an SVG version of the logo, drop it in the same folder and swap the
three `<img src>` references in `_partials/header.html` and `_partials/footer.html`.

---

## 6. Images

All photography is under `assets/images/<group>/`, named in English and
SEO-friendly. Naming convention — the `srcset` blocks depend on it:

```
<descriptive-name>[-variant]-<width>.webp

real-estate-property-law-1000.webp          3:2  card image
real-estate-property-law-wide-2400.webp     16:6 page banner
lawyer-client-consultation-portrait-900.webp 4:5 editorial column
law-firm-hero-columns-1800.webp             16:9 home hero
```

| Suffix       | Ratio  | Used for                          |
| ------------ | ------ | --------------------------------- |
| *(none)*     | 3:2    | cards and section images          |
| `-portrait`  | 4:5    | tall editorial columns            |
| `-wide`      | 16:6   | page headers and banners          |
| `hero-…-wide`| 16:9   | the home hero plate               |

**`assets/images/property/`** is the home-page set: one library of houses,
apartment buildings, Nordic interiors, a construction detail, a surveyor's
drawings and the Stockholm waterfront, shot in the same daylight and the same
restrained palette so the page reads as one commissioned set rather than
assorted stock. The home page and the practice pages draw every one of their
photographs from this folder; no other page does, so the set can be re-shot or
re-licensed without touching the rest of the site.

Every photograph is chosen for the section it sits in, and each one is sized to
finish level with the copy beside it rather than tower over it — that pairing is
the point, so check it before swapping any single image:

| Section                       | Photograph                          |
| ----------------------------- | ----------------------------------- |
| Hero (full-bleed)             | `hero-modern-villa-wide` + `oak-stair-hall-portrait` inset |
| Om vår hjälp                  | *(none — copy and the service card only)* |
| Jurist och advokat            | `survey-drawings-desk`              |
| Vad räknas som dolda fel      | `nordic-apartment-parquet`          |
| Hus · Bostadsrätt · Fastighet | `family-house-porch`, `apartment-block-facade`, `timber-entrance-facade` |
| Vanliga dolda fel             | `hidden-construction-detail`, in the note panel under the six cards |
| Har du upptäckt …             | `house-facade-night` behind the slate scrim |
| Juridisk rådgivning           | `services/contract-review-detail`   |
| Tvister                       | `swedish-house-red-roof`            |
| Vilken ersättning             | *(none — three cards in a row)*     |
| Fastighetstvister             | `modern-house-drive`                |
| Erfarenhet                    | `stockholm-riddarholmen-wide` behind the slate scrim |
| Varför anlita oss             | `nordic-kitchen-corridor` behind the slate scrim |
| Kontakt                       | `stockholm-skyline-wide`            |

**`kontakta-oss.html`** is the shortest page on the site — the live contact page
is a heading, three contact lines, a form and a map, and there is nothing else to
say without writing new copy. It carries its weight with photography instead:

| Section          | Photograph                                                    |
| ---------------- | ------------------------------------------------------------- |
| Hero (full-bleed)| `stockholm-boulevard-wide` + `city-entrance-doors-portrait` inset |
| Karta            | the Google embed in `.map-frame`                              |
| Kontakta oss     | `stockholm-skyline-wide` — the same ground as every closing band |

**The map sits between the hero and the contact band, not after it.** The live
page puts it last, but that leaves two dark grounds touching — the hero and the
contact band read as one region, the same failure the expert band hit on
`dolda-fel-i-hus.html`. The limestone map band between them gives the page a
dark / light / dark rhythm and lets the form close it above the footer, as on
every other page. No copy moved; only the embed did.

The hero photograph runs bright and sunlit where every other hero on the site is
overcast or dusk, which is what stops a three-band page from reading as thin. The
inset is a stately city doorway rather than another interior, so the two images
tell one story — the street, then the door.

The hero plate — `hero-modern-villa-wide-{1200,1800,2400}.webp` — is preloaded in
the `<head>` of `index.html`. Change the photograph and change the
`rel="preload"` with it, or the largest paint regresses.

**To replace an image:** export the new photo at every width already present for that
name (e.g. `-600`, `-1000`, `-1600`), keep the same aspect ratio, save as WebP at
quality ~70, and drop it in the same folder with the same filenames. Then update the
`alt` text wherever it is used — `alt` has to describe what is genuinely in the new
photograph:

```powershell
Select-String -Path .\_pages\*.html -Pattern "your-image-name"
```

If the aspect ratio changes, also update `width` and `height` on the `<img>` so the
space stays reserved and layout shift stays at zero.

Photography comes from Unsplash (Unsplash License) and Pexels (Pexels License).
Both permit commercial use without attribution. The nine portraits in
`assets/images/team/` are the real published photographs from advantage.se —
confirm the firm holds the rights and that everyone pictured is still with the
firm before launch.

### The hidden-defect set — `dolda-fel-i-hus.html`

The client's brief: *"dolda fel" means hidden defects in a house or property —
issues not visible or easily detectable when buying, discovered later.* A photo of
a nice house does not say that. Every image on the practice page was replaced with
one that shows the defect itself, or someone looking for it:

| Section                      | Photograph                    | What it shows                    |
| ---------------------------- | ----------------------------- | -------------------------------- |
| Hero                         | `house-ceiling-damage-wide`   | a failed ceiling in a panelled hall |
| Hero inset                   | `settlement-crack-portrait`   | a crack running down plaster     |
| Dolda fel i hus              | `mould-damp-wall`             | damp and mould on an inner wall  |
| Exempel på dolda fel         | `opened-wall-insulation-wide` | a wall stripped to studs         |
| Vad är dolda fel vid husköp  | `ceiling-water-damage-portrait` | ceiling plaster off after a leak |
| Fråga våra experter          | `basement-beams-pipes`        | an unfinished basement           |
| Köparens undersökningsplikt  | `inspector-outlet-check`      | an inspector at an outlet + vent |
| När ett fel inte är dolt     | `waterproofing-behind-tiles`  | the membrane behind the tiling   |
| Vanliga exempel på tvister   | `crawl-space-inspection`      | a crawl-space hatch being opened |
| Så bör du agera              | `moisture-meter-bathroom`     | a moisture meter on a wet-room floor |
| Ersättning vid dolda fel     | `bathroom-rebuild-drywall`    | a wet room being rebuilt         |
| Varför juridisk hjälp        | `studs-insulation-room`       | a room open to reglar and insulation |

The hero went through one revision worth recording: the first pick was a torch
playing over a mould-covered wall, and it was rejected because it read as an
abandoned building rather than someone's home. The replacement is a white
tongue-and-groove hall — unmistakably a Nordic house — with the ceiling collapsed
and damp staining down the panelling. **A defect photograph still has to look like
a home.** Derelict-building stock fails that test however dramatic it is.

Each one is matched to the section's argument, not just to the theme —
`waterproofing-behind-tiles` sits under the paragraph about `tätskiktsfel`,
`crawl-space-inspection` under the one about `fuktskada i källare` and
`grundläggning`. **Update the `alt` text with the picture.** It has to describe
what is genuinely in the photograph; the alt on this page names the actual defect
(«Mörka fukt- och mögelstråk …»), which is also what makes it useful to a screen
reader and to search.

Two things to know before extending the set to the other pages. Unsplash returns
mostly abstract *peeling-paint texture* for these queries; Pexels has the usable
photographs of inspections and real damage. And Pexels serves WebP directly —
append `&fm=webp&q=<n>` to the `images.pexels.com/photos/<id>/…` URL, the same
trick the Unsplash URLs use, or you get a JPEG with a `.webp` name.

### The hidden-defect set — `dolda-fel-i-bostadsratt.html`

Same brief, different building. A flat's hidden defects live in the wet room, the
pipework and the boundary with the association's stammar, so none of the house
page's photographs is reused here:

| Section                        | Photograph                    | What it shows                       |
| ------------------------------ | ----------------------------- | ----------------------------------- |
| Hero                           | `tiles-off-wall-wide`         | tiles lifting off a damp-stained wall |
| Hero inset                     | `wetroom-pipes-portrait`      | pipework against wet-room tiling    |
| Dolda fel i bostadsrätt        | `wc-stripped-pipes`           | a WC stripped back, pipes exposed   |
| Exempel på dolda fel           | `apartment-wall-opened-wide`  | a flat's wall opened up             |
| Vad menas med dolda fel        | `wetroom-renovation-portrait` | a wet room mid-rebuild              |
| Fråga våra experter            | `corroded-pipes-wall`         | corroded pipe stubs out of a wall   |
| Säljarens ansvar               | `damp-stained-wall`           | failed plaster and damp runs        |
| Vanliga exempel på fel         | `floor-drain-wetroom`         | a floor drain in a tiled floor      |
| Skillnaden bostadsrätt/fastighet | `pipe-manifold-work`        | pipes joined at a floor manifold    |
| Så bör du agera                | `documenting-apartment`       | photographing a flat as evidence    |
| Vad kan du kräva               | `apartment-strip-out`         | a flat stripped for repair          |
| Bevisfrågan                    | `pipe-joint-inspection`       | a pipe joint examined behind insulation |
| När juridisk hjälp gör skillnad| `apartment-renovation-open`   | a flat open, floor taken up         |

The matching is again argument-level, and on this page the copy names the subjects
outright: `floor-drain-wetroom` sits under "golvbrunnar som installerats fel",
`damp-stained-wall` under "missfärgningar … tecken på tidigare läckage",
`pipe-manifold-work` under the section separating the flat from the building.

One image was replaced after review for the same reason the house hero was: a
water-stained bedroom with bedding and pictures on the wall read as a neglected
flat rather than a defect, and it looked like a personal snapshot beside the rest
of the set. `damp-stained-wall` does the same job and matches the register.

---

## 7. Adding a service, a team member or an article

**Service** — duplicate `_pages/service-details.html`, update the front matter and
body, add an image set to `assets/images/services/`, then add a `.service-card` to the
grid in `_pages/services.html` and `_pages/index.html`. Rebuild.

**Team member** — save a square portrait (1080 × 1080 or larger) to
`assets/images/team/<firstname-lastname>.webp`; CSS crops it to a 4:5 portrait with
`object-position: 50% 20%`. Add an `.person` block to `_pages/team.html`, and add the
person to the `employee` array in `_pages/schema/team.json`. For a full profile,
duplicate `_pages/team-details.html` and point the link at it.

The card is identical on all three pages that use it — home, `about.html` and
`team.html` — four across in a `.team-grid.team-grid--4`:

```html
<article class="person" data-reveal>
  <div class="person__media">
    <img src="assets/images/team/firstname-lastname.webp" width="1080" height="1080"
         loading="lazy" decoding="async" alt="Portrait of Firstname Lastname">
    <div class="person__actions">   <!-- mail + phone, revealed on hover/focus -->
      <a href="mailto:info@advantage.se" aria-label="E-mail Firstname Lastname">…</a>
      <a href="tel:+468202140" aria-label="Call Firstname Lastname">…</a>
    </div>
  </div>
  <h3 class="person__name"><a class="stretched-link" href="team-details.html">Firstname Lastname</a></h3>
  <p class="person__role">Associate</p>
  <p class="person__meta">Their areas, in a few words</p>
</article>
```

Copy all four parts. A card missing `.person__actions` still lays out, but it loses
the hover overlay and stops matching the other two pages. `data-reveal-delay` cycles
`0 / 70 / 140 / 210` so each row animates left to right.

**Article** — duplicate `_pages/blog-details.html`, update the front matter and body.
The layout follows the live single-post pages: a `.page-head` with the headline,
standfirst and meta set over the featured image, then an `.article-layout` — prose in
`.article-main` on the left, a sticky `.article-aside` on the right holding one
`.side-form` contact card. The article opens with a landscape image before the first
paragraph, and the author card closes it. There is no table of contents and no
reading-progress bar. Give each `<h2>` an `id` anyway — they are useful anchors — then
add a card to the grid in `_pages/blog.html` with the three data attributes the filter
needs:

```html
<article class="post-card" data-post
         data-category="Contracts"
         data-search="title words excerpt words category">
```

`data-category` must match one of the `data-filter` values on the chips.

---

## 8. Where to configure form submission

Nine forms, all handled by the same code in `assets/js/main.js` (section 10):
`contact.html`, `kontakta-oss.html`, and the closing band on each of the seven
rebuilt and practice pages.

**None of them is wired to a recipient.** By design the code never pretends a
message was sent. Submitting a valid form currently shows:

> This form is not connected to a recipient yet. Please call +46 8 20 21 40 or e-mail
> info@advantage.se and we will get straight back to you.

To enable real submission, add `data-endpoint` to each `<form>`:

```html
<form class="form" data-contact-form data-endpoint="https://your-handler.example/submit" novalidate>
```

The script then `POST`s a `FormData` payload of whatever fields that form carries
and expects a `2xx` response. Anything else shows an error pointing at the phone
number.

**The fields differ by page, and that is deliberate — each form mirrors its live
original.** `contact.html` (the older English template) has Name, Your
Counterparty, Your Phone, Email, Describe your case and a required consent
checkbox. The Swedish pages carry the live site's Elementor form: Namn, Telefon
(the only required field), E-post, Ämne, Meddelande — and on `kontakta-oss.html`
one extra field, **Din motpart**, which the live contact page has and the others
do not. Do not "harmonise" them; the difference is the client's.

Every input has a real `<label>`. If you add a field, add its label the same way —
a placeholder on its own vanishes the moment someone types and leaves the field
unnamed to a screen reader.

**Privacy notes.** Nothing typed into any form is written to `localStorage` or
`sessionStorage`. There is no analytics, tag manager, advertising pixel or tracking
cookie anywhere in the build. `contact.html` uses an OpenStreetMap embed, so no API
key is exposed client-side; `kontakta-oss.html` uses the client's own Google Maps
embed URL, copied verbatim from the live page, which does set Google cookies for the
visitor. Swap it for the OSM embed if the client wants the page cookie-free.

---

## 9. Design system

Everything lives in `assets/css/styles.css`, in numbered sections.

**Colour** — derived from the logo.

```
--ink          #0C1119   deepest; logo square, primary button
--brand        #1F2839   the wordmark colour, 14.8:1 on white
--brand-deep   #141A26   dark section ground
--brand-darker #1B2230   footer — lifted so it reads apart from the CTA above it
--accent       #A98B5B   brass — rules, icons, UI (3.2:1 → non-text only)
--accent-deep  #806A44   brass as text (5.2:1 on white)
--accent-soft  #C9A870   brass on dark grounds (7.7:1 / 8.3:1 on the footer)
--surface      #FFFFFF   /  --surface-alt #F3F1EC  (warm stone, the alternate band)
--text         #131A25   /  --text-muted  #55606F
--line #E5E2DA  --line-strong #D3CFC4  --line-control #7C8798 (3.6:1)
```

**Section rhythm** — three grounds only: white, stone (`--surface-alt`), and dark.
They alternate, and two dark bands never sit next to each other. Every alternate and
dark band opens with a 1px brass hairline so the seam is deliberate rather than a
colour change you have to squint at. On the stone band, cards flip to white
(`.section--alt .value-card`) so they do not disappear into their own ground.

`--accent` never carries small text — it only reaches 3.2:1 on white. `--accent-deep`
is the legible text variant; `--accent-soft` is its counterpart on dark grounds.

**Type** — two families. *Playfair Display* for display headings and the numerals,
chosen because it matches the high-contrast serif of the ADVANTAGE wordmark. *Inter*
for all body and UI text. Both variable, `font-display: swap`, weight axis only.

**Space** — sections run `--section-y: clamp(3.75rem, 5.4vw, 6.25rem)` and the large
variant `--section-y-lg: clamp(4.75rem, 7vw, 8rem)`. Section heads have
`clamp(2.5rem, 4.2vw, 4rem)` below them. The container caps at 1360px (`--container`),
the wide variant at 1500px, and long-form copy at 42rem.

**Shape and elevation** — the estate layer (§18 of the stylesheet) sets one radius
scale, `--r-xs: 8px` through `--r-2xl: 40px`, and one shadow family, `--sh-xs`
through `--sh-xl`. Both are wide, soft and warm-neutral. Use the tokens; do not
write a one-off `border-radius` or `box-shadow`, or the page stops reading as one
material.

**Palette weights** — the brand owns two colours, wet slate and warm oak, and the
estate layer simply uses them at more weights: `--slate-900` … `--slate-500`,
`--oak-700` … `--oak-300`, and `--stone-100` … `--stone-300` for the limestone
ground. Nothing outside those three ramps appears on the page. Slate bands close
with a single oak hairline seam, which is the page's one recurring rule.

**Recurring components** — these carry the look across all nine pages:

- `.hero--estate` on the home page — one cinematic plate of architecture running the
  full width of the viewport (`.hero__frame`), the headline set into its lower-left
  corner where the light has already fallen away, and `.hero__inset` mounting an
  interior over the opposite corner. Only the photograph and the scrim break out of
  the page box: `.hero__body` is itself a `.container`, and the inset and the
  surveyor's corner mark are pinned to the container's content edge with
  `max(var(--gutter), calc((100% - var(--container)) / 2 + var(--gutter)))` so they
  never drift into the margin on a wide screen.
- `.media-figure` — a photograph mounted like a drawing on board: the plate, and a
  fine oak rule stepped off one corner behind it. `--flip` steps it the other way,
  `--portrait` / `--wide` / `--band` set the ratio. This is the page's one
  decorative move, and it is drawn rather than photographed.
- `.section--photo` on a `.section--dark` — a slate band over a photograph. The
  scrim lives on `.section__media::after`, **not** on the section's `::after`,
  which the oak seam already claims. Three sections use it, and they carry the
  same card treatment: the four steps, and the six reasons via `.checklist--cards`.
- `.section--photo-soft` on a `.section--alt` — the daylight counterpart. The
  photograph sits far back under a limestone wash, giving a light band warmth and
  depth without competing with the white cards standing on it. The wash is opaque
  enough that text contrast on the band is unchanged; if you swap the photograph,
  keep it that way rather than letting the picture come forward.
- `.checklist--cards` — the plain checklist re-set as glass plates on a slate
  band, the tick in an oak medallion. Same markup as `.checklist`; only the
  modifier changes.
- `.split--flush` — stretches the photograph so the two columns of an editorial
  split begin and end on the same lines. Without it a 3:2 plate beside a
  two-paragraph column centres, starting lower and finishing higher than the copy.
- `.split--advisory` — the row under the head in the two service sections: a 3:2
  photograph beside the eight tiles two-up. The copy belongs in a
  `.section-head--two` above it, not in the left column; putting a heading, two
  paragraphs and a plate on one side left the tile side 270px short and the
  section looked half-empty on the right.
- `.section-head--two` splits its paragraphs across both columns where there are
  two of them: the first sits under the heading on the left, the second stands
  alone on the right. Reading order is unchanged — heading, first paragraph,
  second paragraph — and neither column is left with a hole beside a heading.
  A head that carries a `.prose` block top-aligns (`:has(.prose)`); one carrying
  a single short `.lead` keeps the bottom alignment it was designed for.
**A long column beside a photograph.** Five or six paragraphs will always outrun a
3:2 plate, and every way of papering over that gap looked worse than the gap:
stretching the photograph crops its subject away, centring it leaves it floating,
a sticky plate still reads as two columns, and running it off the page edge just
made the section top-heavy. Two things actually work, and the practice page uses
both so they do not become their own kind of repetition:

- **Give the plate a portrait source.** `.split--editorial` narrows the media
  track to `.85fr` and takes a 4:5 crop, so the photograph is tall because it was
  shot tall. Use this wherever a portrait crop exists.
- **Let the copy widen instead.** Keep the heading and the first paragraphs beside
  the plate at its natural ratio, then run the rest full width in a
  `.prose--cols` block. The section opens up as the argument does, and the
  photograph never has to be tall at all.
- `.eyebrow-rule` — a short oak rule standing where a `.badge` would, for pages
  whose copy is fixed and cannot take an invented label.
- `.expert-band` — headline, one action and a circular portrait, over a slate
  photographic ground. `.expert-band--light` is the same band on white, with an
  oak ring around the portrait; the terms page uses it because the band sits
  between the clause sheet and the closing contact band, and left dark it merged
  into the CTA below it. Watch the section rhythm when placing this band: two
  slate grounds touching read as one region, whichever two they are.
- `.form-card` — a glass panel holding a form on a dark band.
- `.terms-sheet` / `.terms-list` / `.terms-item` — a numbered legal document on one
  white sheet, set on the limestone band so the panel reads as a document rather
  than a run of loose text. Each clause is three columns from 1000px: oak
  medallion, title, body. **The body must run to the right edge of the sheet.** The
  first attempt stacked the body under the title and capped it at a measure, which
  left the right third of the page empty and looked unfinished. The numerals are a
  CSS counter on the `<ol>`, so the markup carries no numbers the source page does
  not already imply — renumbering happens by itself if a clause is added or moved.
- `.contact-note` — the closing note as a bar: copy left, action right, so it does
  not sit as a tall block under a two-column argument.
- `.address-note` — a postal address set apart from the copy with an oak rule and
  a limestone ground. On the source page the disputes board's address is the tail
  of a paragraph; inline it reads as three orphan lines.
- **Accordion answers can hold lists.** `.accordion__body li::before` draws an oak
  dot on every item, which turned an `<ol>` on `faq.html` into four dots; ordered
  lists now keep their markers, and a paragraph closing a list gets the same air as
  one following a paragraph.
- `.hero__title--compound` — for a hero whose title is one long word. The base
  title breaks words so a headline can never overflow, which split
  "Konsumenttvistnämnden" as "Konsumenttvistnäm / nden". `hyphens: auto` would
  break it correctly but only where the browser ships a Swedish dictionary, and
  Chrome does not — so the modifier sizes the headline to fit instead.
- `.note-panel` — a photograph and a short slate note locked into one frame.
- `.party-card` / `.party-card--slate` — a matched pair of panels, one limestone
  and one slate, each with a glyph medallion and a drawn elevation watermark. Used
  where a text-only block would otherwise read as two grey boxes.
- `.remedy-row` / `.remedy-card` — three outcomes in a row, each opening with its
  own oak rule. It was tried as a column beside a 4:5 photograph and neither
  worked: the plate doubled the height of the section, and no crop of a house
  reads well at that shape.
- `.process-grid` — draws a dashed oak rail behind a four-up step grid.

- `.feature-panel` — image and copy inside one bordered frame, image left by default,
  `.feature-panel--flip` puts it right. `.feature-panel__note` is the optional caption
  strip over the bottom of the image. Use it for a single hero-weight block on a page.
- `.svc-row` inside `.svc-rows` — copy and a landscape image side by side, alternating
  with `.svc-row--flip`, separated by hairlines. This is the long-form pattern on
  `service-details.html`. Unlike the panel it has no frame and the image keeps a normal
  3:2 shape rather than being stretched to the height of the column beside it, so use
  it when several image + text blocks run one after another. Feed it landscape sources:
  a portrait crop in either component gets centre-cropped and reads badly.
- `.cta.cta--split` — the closing band on all nine pages: headline, lead and buttons
  in the left column, a `.contact-cards` list (phone, e-mail, response time, office)
  in the right. Stacks to one column below 940px.
- `.contact-lines` — phone, e-mail and address as icon + text pills on a dark
  ground, used in the contact band on `kontakta-oss.html`. It exists because
  `.contact-cards` needs a `<dt>` label above each value ("Ring oss", "Mejla
  oss") and the live contact page has none; adding them would have meant writing
  new words. Its top margin lives in the component, not on a `.u-mt-*` utility —
  the component's own `margin: 0` list reset sits later in the file and would
  win.
- `.map-frame` — the Google embed, framed like every other media block, on a
  limestone gradient rather than white so it still reads as a panel while the
  iframe loads. **The map does not paint in headless Chrome** — the embed loads
  (its "Open in Maps" control renders) but the tiles need a real GPU context, so
  screenshot it in a browser, not a capture script.
- The footer — the client's four Swedish columns over a copyright bar (§4).

Icon tiles (`.value-card__icon`, `.contact-cards__icon`) wrap the glyph rather than
sharing an element with it: `<span class="value-card__icon"><span class="icon icon--users">
</span></span>`. Putting both classes on one element makes the tile's `background`
override the `.icon` mask fill and the glyph vanishes.

**Icons** — one family on a 24px grid, 1.6px stroke, round caps, implemented as CSS
`mask-image` data URIs so they inherit `currentColor` and add zero requests. Add one
by defining `--i-<name>` and a matching `.icon--<name>`.

**Motion** — a soft fade-and-rise on scroll, a 1.03–1.04 image hover scale, animated
accordions, and a reading-progress bar on the article page. All of it disabled under
`prefers-reduced-motion: reduce`.

---

## 10. Accessibility and performance

Verified in this build:

* One `<h1>` per page across all nine
* Every `<img>` has descriptive `alt` (or `alt=""` where decorative) plus `width` and
  `height` — layout shift is designed out
* **All 29 colour pairs meet WCAG 2.2 AA**, including the 3:1 non-text threshold for
  form-control borders, the focus ring and the review-card star rating
* Skip link, visible focus rings, keyboard-operable accordions with
  `aria-expanded`/`aria-controls`, focus-trapped mobile drawer that closes on Escape
* Touch targets ≥44px; buttons 52px
* Zero horizontal overflow at 360 / 480 / 768 / 992 / 1200 / 1440
* One CSS file and one JS file, both deferred and unminified for legibility
* LCP image preloaded per page with `fetchpriority="high"`; everything else lazy
* Images: 222 files, ~28 MB total

---

## 11. Google reviews

The reviews band on the home page is **static markup**, not a live feed. A live feed
means the Google Places API, and that means an API key sitting in the page source —
which the brief rules out. If you want it live, put a small server-side endpoint in
front of it that holds the key and returns just the review JSON.

The four reviews currently in `_pages/index.html` were transcribed from the Google
reviews shown on advantage.se. Two are quoted in full. Two were already truncated at
source and end with an ellipsis — **the missing text was not invented, and must not
be.** They are marked in an HTML comment above the block.

To refresh them by hand, copy an existing `<article class="g-review">` and change:

| Part | What to put there |
| --- | --- |
| `.g-review__avatar` | The reviewer's first initial |
| `.g-review__name` | The reviewer's name as Google shows it |
| `.g-review__when` | The relative date as Google shows it ("11 months ago") |
| `<blockquote>` | The review verbatim. Set `lang` if it is not English |
| star count | Remove `.icon--star` spans to show fewer, and update `aria-label` |

Two things to keep honest: the relative dates go stale, so re-check them whenever you
touch the section; and do not add an aggregate rating or a review count anywhere
unless you are reading it off the live Google listing that day.

---

## 12. Before launch

- [ ] **Re-check the Google reviews** (§11). Confirm the four are still the current
      top reviews, refresh the relative dates, and never complete the two truncated
      quotes from memory.
- [ ] **Confirm the team.** Names, roles and specialisms on `team.html` are
      placeholders around the real portraits. Every profile currently links to the same
      `team-details.html`; duplicate it per person once the real biographies exist.
- [ ] Configure `data-endpoint` on all nine forms (§8) — they validate but never
      send until then.
- [ ] **Get the real social URLs.** Only YouTube is wired up; Facebook, Instagram
      and LinkedIn point at the bare service homepages. The live site is no help —
      three of its four icons have no `href` and the Instagram one goes to YouTube.
- [ ] Set the real domain — the canonical URLs and JSON-LD currently use
      `https://advantagelaw.example`. Find and replace across `_pages/` and rebuild.
- [ ] Generate `sitemap.xml` and `robots.txt`.
- [ ] Add a privacy policy page and link it from the consent checkbox on
      `contact.html`, which currently has no target.
- [ ] Decide on the Google Maps embed on `kontakta-oss.html` — it is the client's
      own embed URL and sets Google cookies. Keep it, or swap in the OSM embed
      `contact.html` already uses (§8).
- [ ] Re-run Lighthouse against the deployed origin, not `file://`.
"# advokatdoldafel-premium" 
"# advokatdoldafel-premium" 
"# advokatdoldafel-premium-website" 
"# advokatdoldafel-property-webiste" 
