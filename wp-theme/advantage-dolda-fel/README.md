# Advantage Dolda Fel — WordPress theme

The advokatdoldafel.se design as an installable theme. Eight assignable page
templates, a custom blog archive and single-post design, and full Elementor Theme
Builder compatibility.

Written for a site that already has 80+ published articles. **Nothing in this theme
reads, edits, migrates or re-saves a post.** The new article design is applied by
template alone, so it lands on every existing post and every future one with no
content operation of any kind.

---

## 1. Install

1. Zip the folder `advantage-dolda-fel` (or use the zip supplied).
2. **Appearance → Themes → Add New → Upload Theme** → choose the zip → **Install Now**.
3. **Activate**.

Requires WordPress 6.0+ and PHP 7.4+. Elementor is optional; Elementor Pro is only
needed if you want Theme Builder to drive any location.

> **Do this on staging first.** Switching themes changes every template on the
> site at once. Nothing is destructive, but you want to see it before visitors do.

---

## 2. One-click page setup

**Appearance → Advantage Setup.**

Press **Create missing pages and assign templates**. It creates the eight pages
and assigns each its template.

What it does:

- creates a page only if no page with that slug exists;
- sets the template on each page.

What it never does:

- touch a post — the existing articles are not read or written by this screen;
- overwrite the content of a page that already exists.

Running it twice is safe. The same screen shows a live status table, so you can
see at a glance what is assigned and what is not.

If you would rather do it by hand, the mapping is:

| Page | Slug | Template to choose in Page Attributes |
| --- | --- | --- |
| Hem | `hem` | Hem — startsida |
| Dolda fel i hus | `dolda-fel-i-hus` | Dolda fel i hus |
| Dolda fel i bostadsrätt | `dolda-fel-i-bostadsratt` | Dolda fel i bostadsrätt |
| Dolda fel tvister | `dolda-fel-tvister` | Dolda fel tvister |
| Allmänna villkor | `allmanna-villkor` | Allmänna villkor |
| Konsumenttvistnämnden | `konsumenttvistnamnden` | Konsumenttvistnämnden |
| FAQ | `faq` | FAQ — vanliga frågor |
| Kontakta oss | `kontakta-oss` | Kontakta oss |

The slugs matter. Templates link to each other by slug, so a page at the wrong
slug leaves the links pointing at the home page instead of 404ing.

---

## 3. Front page and blog

**Settings → Reading:**

- **Your homepage displays** → *A static page*
- **Homepage** → **Hem**
- **Posts page** → your existing articles page (the one your 80+ posts already
  live under — do not create a new one if you have one)

Leave the Posts page's own template on **Default**. WordPress renders it through
`home.php`; giving it a page template would override that.

---

## 4. The blog — how the two options work

This is the part that matters most for a site with 80+ existing articles, so it is
worth being precise about the mechanism.

### The strategy

Existing posts are **not migrated, duplicated or converted**. The theme changes
only how a post is *rendered*. Every post row in the database is left exactly as
it is. That means:

- all 80+ articles pick up the new design the moment the theme is active;
- every future article does too, automatically;
- reverting is switching the theme back — no content to undo.

### Which design renders

Both options are live at once, and WordPress picks between them per request:

| Situation | What renders |
| --- | --- |
| No Elementor Theme Builder template matches | **The theme's design** (`home.php`, `archive.php`, `single.php`) |
| An Elementor Theme Builder template is published **and** its display conditions match | **Elementor's template** |

There is no setting to flip in the theme. The choice is made entirely by what is
published in **Templates → Theme Builder**.

- **To use the new custom design** — make sure no Elementor *Archive* template
  matches your blog, and no *Single* template matches Posts. Either unpublish
  them, or narrow their display conditions.
- **To keep using Elementor Theme Builder** — publish an Archive template with
  the condition *Posts Archive*, and a Single template with the condition
  *Posts*. They take over immediately.

You can mix: Elementor for the listing, the theme for single posts, or the other
way round. The two locations are independent.

### Why it works

`functions.php` registers the four Elementor theme locations, and every template
asks Elementor first:

```php
if ( ! adf_do_elementor_location( 'single' ) ) {
    // the theme's own single-post design
}
```

`adf_do_elementor_location()` returns `true` only when Elementor has a published,
matching template — in which case it has already rendered and the theme's markup
is skipped. Without the `register_all_core_location()` call in
`inc/elementor-compat.php`, Theme Builder templates silently do nothing on the
front end; that call is the reason your existing Elementor setup keeps working
after the theme switch.

### Which design is rendering right now?

**Appearance → Advantage Setup → "Which design is rendering?"**

This is the first place to look when a page does not look like the theme. It lists
every published Theme Builder template, what it overrides, the conditions it
matches, and a link to edit it.

The two designs are indistinguishable from the front end — a post rendered by an
Elementor Single template simply does not look like the theme, and nothing on
screen says why. That panel says why.

A worked example, because this exact case came up on the live site: the single
post pages were rendering an Elementor template rather than `single.php`. The
theme's header and footer were correct, but the article layout, typography,
related posts and contact form were all Elementor's, and the form used Elementor's
brown kit accent. Nothing was broken — the site was simply in Elementor mode for
that one location. The panel now names the template doing it.

**The theme's global form and Elementor templates.** If you keep an Elementor
Single template, `single.php` does not run, so the form it contains does not
appear. Add it to the Elementor template with a Shortcode widget:

```
[advantage_form]
```

That renders the same component as everywhere else, posts to the same handler and
saves to Form Entries with the same tracking — so updating
`template-parts/form-contact.php` still updates this copy too.


### After switching

Clear Elementor's CSS cache: **Elementor → Tools → Regenerate CSS & Data**. The
theme does this once automatically on activation, but it is worth knowing the
button exists — stale cached CSS is the usual reason a Theme Builder template
looks wrong straight after a theme change.

---

## 5. What renders where

| Location | File | Elementor location offered first? |
| --- | --- | --- |
| Header | `header.php` → `template-parts/site-header.php` | Yes — `header` |
| Footer | `footer.php` → `template-parts/site-footer.php` | Yes — `footer` |
| Blog listing (Posts page) | `home.php` | Yes — `archive` |
| Category / tag / author / date | `archive.php` | Yes — `archive` |
| Search results | `search.php` | Yes — `archive` |
| Single post | `single.php` | Yes — `single` |
| Page with no template assigned | `page.php` | Yes — `single` |
| **Page with one of the eight templates assigned** | `page-templates/…` | **No — by design** |
| 404 | `404.php` | No |

The last two rows are the deliberate asymmetry. When you assign "Dolda fel i hus"
to a page you are asking for that specific design, so the template renders it and
does not defer. Pages left on **Default** go through `page.php`, which does defer —
so Elementor-built pages behave normally.

**The eight page templates do not call `the_content()`.** Their layout *is* the
page. Text typed into the editor on those pages will not appear, and "Edit with
Elementor" on them will not do anything useful. That is intended: the copy is the
client's, fixed, and lives in the template. To make a page editable instead, set
its template back to **Default**.

---

## 5b. The logo — what was checked, and what to do if it looks wrong

The header logo is `assets/images/brand/advantage-logo.png`: 322×62, 8-bit
truecolour + alpha, sRGB, dark wordmark on a transparent background. It is the
client's supplied master (`Group-50.png`).

### What was verified

A washed-out wordmark beside a crisp "A" mark was reported. Every link in the
chain was checked and all of it is correct:

| Check | Result |
| --- | --- |
| Path in the markup | `assets/images/brand/advantage-logo.png` on every page — the dark file |
| The light variant | Referenced nowhere in the site or theme |
| Live file vs local file | Byte-identical (same MD5), served as `image/png`, HTTP 200 |
| Pixel data | Wordmark strokes fully opaque, luminance down to 39 — genuinely dark |
| PNG structure | `IHDR pHYs sRGB gAMA IDAT` — no odd colour profile |
| CSS | No `filter`, `opacity` or blend mode anywhere on `.brand img` |
| Rendering at 1x / 2x / 3x | Dark and crisp at all three |

**The fault could not be reproduced.** Local and live render identically.

### The two causes that fit the symptom

A crisp mark next to a ghosted wordmark is a very specific signature: it is what
you get when the **light** artwork (`advantage-logo-light.png`, white wordmark)
is drawn on the white header. Two ways that can happen:

1. **A WordPress Custom Logo is overriding the bundled file.** A site migrating
   from another theme usually already has one set, and if that attachment is the
   light variant, this is exactly what you see. **Appearance → Advantage Setup**
   now has a *Header logo* row that says which of the two is in use and previews
   it on both the white and the slate ground, so this takes seconds to confirm.
   Removing the Custom Logo in the Customizer falls back to the correct file.

2. **A browser extension or Windows High Contrast mode** recolouring images.
   Section 27 of `styles.css` now sets `forced-color-adjust: none` on the logo,
   which opts it out of forced-colors repainting while leaving the rest of the
   page free to adapt. Note this was not reproducible in headless Chrome, so it
   is a targeted precaution rather than a proven fix.

### The real underlying weakness

The logo is a **raster** wordmark with thin serifs, and 62px tall is not much to
work with. It survives 1x, 2x and 3x today, but any future use at a larger size
will soften. The correct asset for a wordmark is vector.

**Ask the client for the vector original** (AI, EPS, PDF or SVG). Dropping an
`advantage-logo.svg` into `assets/images/brand/` and changing the one `printf` in
`adf_brand_link()` would make the logo resolution-independent for good. The
bundled `favicon.svg` is only the "A" mark, so it cannot stand in for the full
lockup, and recreating the wordmark from the bitmap would mean altering the
client's identity — not something to do without their source file.


---

## 5c. Section bands — why a join can go invisible

The page alternates three grounds: white, warm stone (`.section--alt`) and slate
(`.section--dark`). A band change is the only thing separating one section from
the next — there are no rules or hairlines between them — so the tone has to do
all the work.

**The trap, and it bit once already.** `.section--alt` is a vertical gradient
built from the stone tokens. It used to start at `--stone-100` (`#FBF9F5`), which
is 98% white. That made the *top* edge of every stone band invisible against the
white section above it: a white → alt join simply vanished and the two sections
read as one continuous block. Only the bottom edge showed, because it lands on
`--stone-300`.

It now runs between `--stone-200` (`#F4F0E9`) and `--stone-300` (`#E9E2D6`), so
the band has a visible edge at **both** ends:

```css
.section--alt {
  background: linear-gradient(180deg, var(--stone-200) 0%, var(--stone-300) 100%);
}
```

**If you change these values, check a white → alt join specifically.** The bottom
edge always looks fine because it ends on the darkest stop; the top edge is the
one that fails quietly, and it fails on every alternating section at once.

A quick way to check the whole page: open the console on the listing or the home
page and read the first and last gradient stop of each section —

```js
document.querySelectorAll('main#main > section').forEach((s, i) => {
  const bi = getComputedStyle(s).backgroundImage;
  const m  = bi.match(/rgba?\([^)]*\)/g);
  console.log(i + 1, s.className, m ? [m[0], m[m.length - 1]] : getComputedStyle(s).backgroundColor);
});
```

No two adjacent sections should report the same colour where they meet.


---

## 6. Menus

Header and footer fall back to the six built-in Swedish links, so the site is
correct the moment it is activated. To take control:

**Appearance → Menus** → create a menu → assign it to:

- **Primary menu** — header and mobile drawer
- **Footer — Snabblänkar column** — the footer's first link column

Assigning a menu replaces the fallback completely.

---

## 7. Customizer

**Appearance → Customize → Advantage — kontakt & sociala medier**

Phone (display + `tel:` link), e-mail, address, opening hours, footer blurb,
footer address, legal name, and the four social URLs. Leave a social field empty
and that icon disappears.

**Site logo**: Appearance → Customize → Site Identity → Logo. Falls back to the
bundled `advantage-logo.png`.

---

## 8. Things to sort before launch

- **Set up SMTP so notification e-mails arrive.** The form saves every entry and
  sends to info@ and shafqat.se, but WordPress mail without SMTP
  usually lands in spam. See §9 — it is a ten-minute job and the one thing that
  can silently cost the firm a client.
- **Social URLs.** Only YouTube is real. Facebook, Instagram and LinkedIn are
  empty and therefore hidden until you set them in the Customizer.
- **Featured images on posts.** The archive cards and the single-post banner use
  the featured image. Posts without one fall back to a stock banner, which is
  fine but repetitive across 80 cards. Worth a pass.
- **Regenerate thumbnails once.** The theme adds three image sizes (`adf-card`,
  `adf-card-sm`, `adf-wide`). Images uploaded before the theme was active do not
  have them, so WordPress serves the full-size original instead — correct, but
  heavier than it needs to be. Run any "Regenerate Thumbnails" plugin once after
  activation, or `wp media regenerate` on WP-CLI, and the cards get properly
  sized files. Nothing breaks if you skip it.
- **Two repointed links.** The home page's team section linked to `team.html`
  and `team-details.html` in the static build — English template pages that are
  not part of the live Swedish site. Both now point at the contact page. See the
  file header of `page-templates/template-hem.php` to change them.
- **The Google Maps embed** on the contact page is the client's own embed URL and
  sets Google cookies. Swap it if the site needs to be cookie-free.

---

## 9. The contact form

### One component, used everywhere

Every enquiry form on the site renders from a single file:
`template-parts/form-contact.php`. The closing band on the practice pages, the
contact page, the 404 and the shortcode all include it. Change a field there and
it changes in every place at once.

Two variants:

| Variant | Fields |
| --- | --- |
| `default` | Namn, Telefon*, E-post, Ämne, Meddelande |
| `full` | the same plus **Din motpart** — the contact page's extra field |

From a template:

```php
get_template_part( 'template-parts/form', 'contact' );
get_template_part( 'template-parts/form', 'contact', array( 'variant' => 'full', 'source' => 'kontakta-oss' ) );
```

From the editor, an Elementor Shortcode widget, or a text widget:

```
[advantage_form]
[advantage_form variant="full" title="Skicka ett meddelande" source="sidebar"]
[advantage_form card="no"]
```

`source` is a free label stored with the entry, so you can tell a submission from
the closing band apart from one on the contact page.

### Entries

**Dashboard → Form Entries.**

Every submission is saved before any e-mail is attempted. If the mail host is
down, the enquiry is still in the dashboard — e-mail is a notification, never the
record.

The list shows: who it is from, their phone and e-mail (both clickable), the
subject and message, the page they submitted from, where they originally came
from, whether the notification e-mail went out, and when it arrived. Unread
entries carry a **NEW** badge and the menu shows a count, so nothing sits
unnoticed.

Opening an entry shows every submitted value plus a Tracking panel, with
**Reply by e-mail** and **Call** buttons. Entries are records, so the values
cannot be edited — they can be read, exported and deleted.

**Export CSV** sits above the list and exports everything, including tracking, with
a UTF-8 BOM so Excel opens the Swedish characters correctly.

### What is tracked

| Field | Meaning |
| --- | --- |
| Submitted from | The exact URL of the page the form was sent from |
| Page title | That page's title, so the list reads "Dolda fel i hus" not a URL |
| First page visited | The first page of their **first** visit, from a first-party cookie |
| Came from (referrer) | The site that sent them — Google, an ad, a link |
| First seen | When that first visit happened |
| Campaign (UTM) | `utm_source`, `utm_medium`, `utm_campaign`, `utm_term`, `utm_content`, `gclid`, `fbclid` from the first visit |
| Form location | The `source` label of the form they used |
| IP address, Browser | Standard request metadata |
| Notification e-mail | Whether `wp_mail()` accepted it, and the error if not |

First-touch is deliberate. It answers "which page won this client?", which the
last page before the form cannot. The cookie is first-party, `HttpOnly`,
`SameSite=Lax`, one month, and holds a URL, a referrer, a timestamp and UTM
values — no personal data. Turn it off entirely with:

```php
add_filter( 'adf_enable_tracking', '__return_false' );
```

Behind Cloudflare or a load balancer, the real visitor IP is in a proxy header.
That header can be forged, so it is ignored unless you opt in:

```php
add_filter( 'adf_trust_proxy_headers', '__return_true' );
```

**A note on GDPR.** IP address and tracking data attached to an enquiry are
personal data. Storing them to handle a legal enquiry is normally defensible, but
it belongs in the site's privacy notice, and entries should not be kept forever.
That is a decision for the firm, not a technical default.

### Notifications

**Form Entries → Notifications.**

Notifications go to **info@advantage.se** and **shafqat@advantage.se** out of the
box. Change them on that screen; anything that is not a valid address is dropped
on save. Or in code:

```php
add_filter( 'adf_notification_recipients', function ( $to ) {
    return array( 'someone@advantage.se' );
} );
```

The e-mail carries every submitted field, the full tracking table and a button
straight to the entry in WordPress. `From:` is on the site's own domain so it
passes SPF, and `Reply-To:` is the enquirer, so hitting Reply answers them
directly.

**Make delivery reliable — this is the one step that needs you.** WordPress sends
through PHP's `mail()` by default, which most hosts send from an address that
fails SPF and DKIM. The message then lands in spam or is dropped silently. For a
law firm that is a lost client.

1. Install **WP Mail SMTP**, **FluentSMTP** or **Post SMTP**.
2. Connect it to the advantage.se mailbox, or a transactional service (Postmark,
   Brevo, Amazon SES).
3. **Form Entries → Notifications → Send test e-mail**, and confirm both inboxes
   received it.

The **E-mail** column on the entries list shows `sent` or `failed` per entry, with
the mailer's own error on hover, so a delivery problem is visible rather than
silent.

### Spam

A honeypot field and a two-second time trap, both invisible to real visitors and
requiring no third-party service — which matters for a form carrying legal
enquiries. Add a CAPTCHA only if spam actually gets through.

### Without JavaScript

The form posts to `admin-post.php` and returns with a result message. A visitor
with JavaScript disabled still gets through, and their enquiry is still recorded.

---

## 10. Elementor style guard

Elementor publishes a global "kit" stylesheet that styles bare elements site-wide,
not just Elementor widgets:

```css
.elementor-kit-8 button,
.elementor-kit-8 input[type="submit"],
.elementor-kit-8 .elementor-button { background-color: var(--e-global-color-accent); … }
.elementor-kit-8 button:hover      { background-color: #4F3B24; }
.elementor-kit-8                   { font-family: …; color: …; }
```

Because those selectors sit on `<body>`, they reach every element the theme
renders — the FAQ accordion triggers, the nav toggle, the form submit button and
the site's typography.

`assets/css/elementor-guard.css` wins them back. It is enqueued last, so it lands
after the kit, and each selector is specific enough to take the cascade without a
single `!important`.

**The rule the guard follows:** every selector contains at least one class that
only this theme emits — `.accordion__trigger`, `.btn`, `.nav-toggle`,
`.site-header`, `.page-head`, `.prose`, `.form`, `.chip`, `.adf-pagination`.
Elementor never outputs those, so the guard cannot reach an Elementor widget. A
Theme Builder header, footer, archive or single template keeps its own styling
exactly as designed — which is the point of the dual-mode setup.

If you add a component and Elementor's kit bleeds into it, add a rule to that file
using the same discipline. Do not reach for `!important`, and do not dequeue the
kit stylesheet: Theme Builder templates need it.


### Two things the first version of the guard got wrong

Both were found from a live page, and both are worth understanding before adding
rules to this file.

**1. Order, not just specificity.** The guard was enqueued alongside the other
theme styles, which put it at stylesheet 2 of 15 with Elementor's kit at 9. It
only ever won on specificity; any equal-specificity rule silently lost. It is now
enqueued in its own callback at priority 999, so it lands after Elementor and wins
on both counts:

```php
add_action( 'wp_enqueue_scripts', 'adf_elementor_guard_style', 999 );
```

**2. The kit sets a padding shorthand, and the guard only reset colour.** The kit
ships:

```css
.elementor-kit-8 button { padding: 16px 032px 16px 032px; }
```

A shorthand, so it wiped the theme's `padding-block` *and* injected 32px of
horizontal padding. The visible result was FAQ accordion titles pushed inward and
squashed while the answer text below stayed at the real left edge — the two no
longer lined up. It also padded `<button class="btn">` differently from
`<a class="btn">`, because the kit selector reaches the element, not the class,
and stretched the 48×48 nav toggle.

Section 6 of the guard now restates the theme's own box model for
`.accordion__trigger`, `.btn`, `.nav-toggle`, `.chip` and `.adf-pagination`.

**The lesson for anything added later:** the kit overrides whole property groups,
not single declarations. When a component looks wrong, check the *computed* box
model in DevTools, not just the colours — and remember those values are repeated
in the guard rather than inherited, so they must stay in sync with the base rules
in `styles.css`.


---

## 11. The article listing — Load More, not pagination

The listing lives on **one URL**. There is no numbered pagination anywhere on the
site: the grid grows in place when the visitor presses **Visa fler artiklar**, and
the address bar never changes.

### How it behaves

- The first batch is the site's normal per-page count (Settings → Reading).
- The button appends the next batch over AJAX and updates the count beneath it
  ("18 av 84 artiklar visas"), so a visitor can see how much is left.
- When everything is loaded the button hides itself.
- Focus moves to the first newly loaded card. Without that a keyboard user is
  dumped back at the top of the document and loses their place.
- The count sits in an `aria-live` region, so screen readers hear the change.
- The same behaviour applies to category, tag, author, date and search listings.

### The paginated URLs

This is the part worth being precise about, because it was the reason for the
change.

Nothing on the site links to `/artiklar/page/2/` any more, so nothing hands one to
a crawler. But WordPress builds those URLs from its rewrite rules, and a theme
cannot switch that off without breaking the query — so rather than pretend they
are gone, they are handled:

| | |
| --- | --- |
| Linked from the site? | No. Never. |
| Do they still resolve? | Yes — WordPress core behaviour |
| Indexed? | **No.** They send `noindex, follow` as both a meta tag and an `X-Robots-Tag` header |
| Canonical | Points at page 1 of the listing, consolidating any signal they hold |

`noindex, follow` is the right directive here rather than `noindex, nofollow`.
"noindex" takes the URL out of the results — which is what you asked for.
"follow" keeps the articles *on* that page discoverable, so dropping the
pagination links does not orphan the older posts from the crawl. They also stay in
the XML sitemap, which is the primary discovery path either way.

**If you want them gone entirely**, one line in a child theme or a snippet plugin
turns them into 301 redirects to page 1:

```php
add_filter( 'adf_redirect_paged_archives', '__return_true' );
```

That is deliberately not the default: a redirect also breaks the no-JavaScript
fallback below, and `noindex, follow` already achieves the stated goal.

### Without JavaScript

The button does nothing without JS, so a `<noscript>` block renders a plain
"Äldre artiklar" link to the next page. Those targets carry `noindex, follow`, so
they stay out of the results while keeping the archive walkable for a visitor
whose JS failed to load. If you enable the 301 filter above, delete that
`<noscript>` block from `template-parts/archive-loop.php` — the link would
otherwise bounce them back to page 1.

### Security

The endpoint (`action=adf_load_more`) returns nothing that the archive URL does not
already serve publicly. It is protected by construction rather than by a token:
the query is rebuilt server-side from a **whitelist** — `cat`, `tag_id`, `author`,
`s`, `year`, `monthnum`, `day` — each cast or sanitised. `post_type` is forced to
`post`, `post_status` to `publish`, `paged` floored at 2, and `posts_per_page`
read from the site option and clamped to 1–50. A crafted request cannot widen the
query, change the post type, add a `meta_query` or ask for 9,999 rows.

A nonce is sent and checked, but **not enforced**. Behind full-page caching the
nonce baked into cached HTML goes stale within a day, and enforcing it would break
Load More for every visitor with nothing in the logs to explain why. For a
read-only public endpoint that is a bad trade.

### Files

| File | Role |
| --- | --- |
| `inc/load-more.php` | AJAX handler, query whitelist, noindex/canonical, optional redirect |
| `template-parts/archive-loop.php` | The grid and the Load More control |
| `assets/js/main.js` §12 | The button behaviour, focus handling, live count |
| `assets/css/styles.css` §26 | The Load More block |


---

## 11b. Latest articles on the home page

The home page ends with a live "Senaste artiklarna" band: the three newest posts,
then a button to the full listing.

It is the only dynamic block on an otherwise fixed page. Publish an article and it
appears there on the next page load — nobody has to edit the home page.

### It is the same card, not a copy of it

The band renders `template-parts/content-card.php`, which is the exact component
the listing at `/artiklar/` uses. Restyle a card once and both places change. The
chip, the category, the date, the excerpt trim and the stretched link are all the
listing's behaviour, unchanged.

What it deliberately leaves out is the listing's furniture:

- no category chips,
- no search box,
- no Load More.

Those are navigation aids for a page of 80+ articles. Here there are three cards
and one way onward, so they would be noise. The button is the way onward.

### Where the button goes

`adf_blog_url()` — your **Settings → Reading → Posts page**. Set that (go-live
step 3) and the button follows it. If no posts page is set it falls back to the
home page, which is WordPress behaviour, not a bug in the theme: nothing is
broken, the button just has nowhere better to point yet.

### Using it somewhere else

From a template:

```php
get_template_part( 'template-parts/section', 'latest-posts' );
get_template_part( 'template-parts/section', 'latest-posts', array( 'count' => 6 ) );
```

From the editor, or an Elementor Shortcode widget:

```
[advantage_latest_posts]
[advantage_latest_posts count="6" title="Fler artiklar" ground="alt"]
```

| Attribute | Default | Notes |
| --- | --- | --- |
| `count` | `3` | Clamped to 1-12. Three fills one row of the 3-up grid. |
| `title` | `Senaste artiklarna` | |
| `lead` | `Här hittar du våra senaste artiklar.` | The line the live listing carries. Pass `lead=""` to drop it. |
| `ground` | `plain` | `alt` adds the warm stone band. |
| `button` | `yes` | `no` hides the link to the listing. |

### If you move it, check the band either side

`ground` exists because the bands have to alternate — see section 5c. On the home
page the section above it is `section--alt` and the CTA below is slate, so it sits
on plain ground and both edges stay visible. Drop it somewhere else and pick the
`ground` that alternates against its new neighbours, or two bands will merge into
one.

### With no posts published

The section renders nothing at all, rather than an empty band with a heading over
it. Relevant on a fresh install, before the 80+ articles are in place.

### Files

| File | Role |
| --- | --- |
| `template-parts/section-latest-posts.php` | The section |
| `inc/template-tags.php` | `[advantage_latest_posts]` |
| `page-templates/template-hem.php` | Where it is placed, section 18 |

---

## 12. Editing the copy

The Swedish copy on the eight page templates is the client's and is **fixed** —
every heading, paragraph, clause, card and form label is the live page's, word for
word. It was verified against the live site by extracting each page's text,
tokenising it and diffing both directions.

Before changing any of it, re-run that check. The full method, the known-safe
differences and the deliberately preserved client typos on the FAQ page are
documented in the project README that ships alongside this theme.

---

## 13. File map

```
advantage-dolda-fel/
  style.css                    theme header only — real CSS is in assets/css/
  functions.php                supports, menus, image sizes, enqueues
  header.php  footer.php       document chrome, both defer to Elementor
  home.php                     blog listing
  archive.php  search.php      same design, other queries
  single.php                   the article design — applies to all existing posts
  page.php                     default page (defers to Elementor)
  404.php  comments.php  searchform.php  index.php
  inc/
    template-tags.php          helpers: adf_asset, adf_page_url, preload, menus
    elementor-compat.php       location registration + the handover switch
    customizer.php             contact and social settings
    setup-screen.php           Appearance → Advantage Setup
    load-more.php              AJAX Load More + paginated-URL noindex
    elementor-status.php       "which design is rendering?" admin panel
    form-entries.php           entry storage, tracking, submit handler, e-mail
    form-entries-admin.php     the Form Entries dashboard, CSV export, settings
  page-templates/              the eight assignable page templates
  template-parts/
    site-header.php  site-footer.php
    archive-head.php  archive-loop.php  content-card.php
    author-card.php  related-posts.php  cta-contact.php
    form-contact.php           THE form — one definition, used everywhere
    section-latest-posts.php   "Senaste artiklarna" — home page, also a shortcode
  assets/css/styles.css        the design system (sections 1–25)
  assets/css/elementor-guard.css  wins the cascade back from Elementor kit CSS
  assets/js/main.js            nav drawer, accordions, reveals, form validation
  assets/images/               165 images, ~21 MB
```
