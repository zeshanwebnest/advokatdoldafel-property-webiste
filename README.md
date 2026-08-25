# Advantage Law Firm — website

Nine static pages, English content, built with HTML, CSS and vanilla JavaScript.
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

Nothing else. The navigation, footer and every internal link stay inside these nine.

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
  index.html … contact.html      the nine pages (complete, standalone)
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
      services/                    one image per practice area
      blog/                        article images
      team/                        portraits
```

`_partials/` and `_pages/` are source, never served. You can exclude them and
`build.ps1` when deploying.

---

## 4. Editing the shared header and footer

Every page contains a full copy of the header and footer so the site runs without
tooling. To keep those copies identical, edit the partial and rebuild:

```powershell
powershell -ExecutionPolicy Bypass -File .\build.ps1
```

It reads `_partials/*` plus `_pages/<slug>.html` and rewrites all nine root pages.

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

| Suffix       | Ratio | Used for                          |
| ------------ | ----- | --------------------------------- |
| *(none)*     | 3:2   | cards and section images          |
| `-portrait`  | 4:5   | tall editorial columns            |
| `-wide`      | 16:6  | page headers and banners          |

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

Every photograph is sourced from Unsplash under the Unsplash License, which permits
commercial use. The nine portraits in `assets/images/team/` are the real published
photographs from advantage.se — confirm the firm holds the rights and that everyone
pictured is still with the firm before launch.

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

One form, on `contact.html`, handled by `assets/js/main.js` (section 10).

**It is not wired to a recipient.** By design the code never pretends a message was
sent. Submitting a valid form currently shows:

> This form is not connected to a recipient yet. Please call +46 8 20 21 40 or e-mail
> info@advantage.se and we will get straight back to you.

To enable real submission, add `data-endpoint` to the `<form>` in
`_pages/contact.html` and rebuild:

```html
<form class="form" data-contact-form data-endpoint="https://your-handler.example/submit" novalidate>
```

The script then `POST`s a `FormData` payload (`name`, `counterparty`, `phone`,
`email`, `message`, `consent`) and expects a `2xx` response. Anything else shows an
error pointing at the phone number.

**The fields.** Name, Your Counterparty, Your Phone, Email, Describe your case, then
Send. `counterparty` is the only optional one; it is there so the firm can run a
conflict check before replying. Every input has a real `<label>` — they are
`visually-hidden` so the placeholder carries the visible text, because a placeholder
on its own vanishes the moment someone types and leaves the field unnamed to a screen
reader. If you add a field, add its label the same way. The consent checkbox is
required: the form collects personal detail about a legal matter, so do not remove it.

**Privacy notes.** Nothing typed into the form is written to `localStorage` or
`sessionStorage`. There is no analytics, tag manager, advertising pixel or tracking
cookie anywhere in the build. The map is an OpenStreetMap embed, so no API key is
exposed client-side. The message field tells visitors not to send sensitive documents
through the form — keep that wording if you rewrite the copy.

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

**Space** — sections run `--section-y: clamp(2.75rem, 4vw, 4rem)` and the large
variant `--section-y-lg: clamp(3.25rem, 5vw, 5rem)`. Section heads have
`clamp(2rem, 3.4vw, 3rem)` below them. The container caps at 1360px (`--container`),
the wide variant at 1500px, and long-form copy at 42rem.

**Shape** — radii top out at 6px. Separation comes from white space and 1px
hairlines; the only shadows are the soft lift on card hover and under `.feature-panel`.

**Recurring components** — three carry the look across all nine pages:

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
- The footer — logo and description row, four link columns, then copyright and the
  social tiles. Edit it once in `_partials/footer.html`.

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
* Images: 134 files, ~12 MB total, largest single file 283 KB

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
- [ ] Configure `data-endpoint` on the contact form (§8).
- [ ] Replace the placeholder social URLs in `_partials/footer.html`.
- [ ] Set the real domain — the canonical URLs and JSON-LD currently use
      `https://advantagelaw.example`. Find and replace across `_pages/` and rebuild.
- [ ] Generate `sitemap.xml` and `robots.txt`.
- [ ] Add a privacy policy page and link it from the consent checkbox on the contact
      form, which currently has no target.
- [ ] Re-run Lighthouse against the deployed origin, not `file://`.
