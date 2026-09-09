# Go-live runbook — advokatdoldafel.se

Follow in order. Total time is about 25 minutes, most of it the SMTP step. Do it on staging first.

The single most important fact: **your 80+ existing articles are never touched.**
No import, no export, no migration, no bulk edit. The theme changes how posts are
*displayed*, nothing else. If you switch back to the old theme, everything returns
exactly as it was.

---

## Step 0 — Back up

Take a database + files backup, or a staging snapshot. This is standard practice
for a theme switch, not a warning about this theme specifically.

---

## Step 1 — Upload and activate

1. **Appearance → Themes → Add New → Upload Theme**
2. Choose `advantage-dolda-fel.zip`
3. **Install Now** → **Activate**

The site will already look right on the front end, using built-in fallback menus,
before you configure anything.

---

## Step 2 — Create the pages and assign templates

**Appearance → Advantage Setup** → press **Create missing pages and assign
templates**.

That creates these eight pages and assigns each its template:

| Page | Slug |
| --- | --- |
| Hem | `hem` |
| Dolda fel i hus | `dolda-fel-i-hus` |
| Dolda fel i bostadsrätt | `dolda-fel-i-bostadsratt` |
| Dolda fel tvister | `dolda-fel-tvister` |
| Allmänna villkor | `allmanna-villkor` |
| Konsumenttvistnämnden | `konsumenttvistnamnden` |
| FAQ | `faq` |
| Kontakta oss | `kontakta-oss` |

**If a page already exists at one of those slugs**, the tool leaves its content
alone and only sets the template. It does not overwrite anything.

**If your existing pages use different slugs**, either rename them to match, or
skip the button and assign templates by hand: edit each page → right sidebar →
**Page Attributes → Template** → pick the matching name. If you keep different
slugs, cross-page links will fall back to the home page — see §2 of the theme
README.

Re-load the Advantage Setup screen. Every row should read **✓ assigned**.

---

## Step 3 — Front page and posts page

**Settings → Reading**

- Your homepage displays → **A static page**
- Homepage → **Hem**
- Posts page → **your existing articles page** (the one the 80+ posts already
  live under — do not create a new one)

Save. Leave the Posts page's own Template on **Default** — WordPress renders it
through `home.php`, and assigning a page template would override that.

---

## Step 4 — Choose how the blog renders

You have both options available at the same time. WordPress picks per request:

### Option A — use the new custom design

First, check what is currently overriding what: **Appearance → Advantage Setup →
"Which design is rendering?"** lists every published Theme Builder template and
the theme file each one replaces.

Then go to **Templates → Theme Builder**.

- Any **Archive** template whose conditions match your blog → set it to Draft, or
  narrow its conditions so it no longer matches.
- Any **Single** template whose conditions include Posts → same.

That is it. The theme's `home.php`, `archive.php` and `single.php` take over, and
all 80+ posts render in the new design immediately.

### Option B — keep using Elementor Theme Builder

Leave your Theme Builder templates published. Confirm the conditions:

- Archive template → **Include: Posts Archive**
- Single template → **Include: Posts**

They will render instead of the theme's design. Nothing else needs changing —
the theme registers the Elementor locations, which is what makes Theme Builder
work at all on a custom theme.

### Mixing

The Archive and Single locations are independent. Elementor for the listing plus
the theme for single posts (or the reverse) is a supported combination.

### Switching later

Publish or unpublish the Elementor template. The change takes effect on the next
page load. No content is affected either way.

---

## Step 5 — Clear Elementor's cache

**Elementor → Tools → Regenerate CSS & Data**

The theme does this once on activation, but run it manually if any Theme Builder
template looks wrong. Stale cached CSS is the usual cause immediately after a
theme switch.

---

## Step 6 — Menus

**Appearance → Menus** → create a menu with your six items → assign to:

- **Primary menu** (header + mobile drawer)
- **Footer — Snabblänkar column**

Until you do, the header and footer show the built-in Swedish links, so nothing
is broken while you get to this.

---

## Step 7 — Settings

**Appearance → Customize → Advantage — kontakt & sociala medier**

- Phone, e-mail, address, opening hours
- Footer blurb, footer address, legal name
- Social URLs — only YouTube is filled in. Facebook, Instagram and LinkedIn are
  empty and stay hidden until you add them.

**Appearance → Customize → Site Identity → Logo** if you want to manage the logo
from the media library rather than the bundled file.

---

## Step 8 — Regenerate thumbnails (recommended)

The theme adds three image sizes. Images uploaded before activation do not have
them, so WordPress serves full-size originals — correct, but heavier than needed
across 80 article cards.

Run any "Regenerate Thumbnails" plugin once, or `wp media regenerate` on WP-CLI.
Skipping this breaks nothing.

---
## Step 9 — Set up SMTP so form notifications arrive

**This is the only step with a real chance of silently failing, so do not skip it.**

The form saves every submission under **Form Entries** first, then e-mails
info@advantage.se and shafqat@advantage.se. WordPress sends mail through PHP's
`mail()` by default, which most hosts send from an address that fails SPF and
DKIM checks. The message then goes to spam or is dropped without a bounce.

1. Install **WP Mail SMTP**, **FluentSMTP** or **Post SMTP**.
2. Connect it to the advantage.se mailbox, or to a transactional service
   (Postmark, Brevo, Amazon SES).
3. Go to **Form Entries → Notifications** and press **Send test e-mail**.
4. Confirm both inboxes received it — check spam too.

The **E-mail** column on the entries list shows `sent` or `failed` for every
submission, so a delivery problem is visible rather than silent. And because the
entry is written before the e-mail is attempted, nothing is ever lost if mail
fails.

---

## Step 10 — Check the form end to end

1. Open the contact page in a private window.
2. Submit a real-looking test enquiry.
3. **Form Entries** should show it immediately, with a NEW badge.
4. Open it — the Tracking panel should show the page you submitted from, the page
   you first landed on, and the referrer.
5. Both inboxes should have the notification.

To test the tracking properly, arrive from outside first: open
`https://yoursite.se/?utm_source=test` in a fresh private window, click through to
another page, and submit from there. The entry should show the landing page with
the UTM, and the *other* page as where you submitted.

---

## Step 11 — Final check

- [ ] Home page renders with the full-bleed hero
- [ ] All seven other pages render
- [ ] Blog listing shows your posts with a **Visa fler artiklar** button — no
      numbered pagination anywhere
- [ ] Pressing it appends more posts and the URL stays on /artiklar/
- [ ] The home page ends with a **Senaste artiklarna** band showing your three
      newest posts, and **Alla artiklar** goes to the posts page. If it is missing,
      no posts are published yet; if the button lands on the home page, the Posts
      page in Settings → Reading has not been set (step 3).
- [ ] View source on /artiklar/page/2/ — it should contain
      `<meta name="robots" content="noindex, follow">`
- [ ] Open three or four **old** posts — the new design applies, content intact
- [ ] Category links from the listing work
- [ ] Search works
- [ ] Mobile: the drawer opens, the header collapses at 1000px
- [ ] A 404 URL shows the styled 404
- [ ] **Section bands are clearly separated.** Scroll the home page: every warm
      stone band should have a visible edge where it starts AND where it ends,
      against the white sections either side.
- [ ] **No brown Elementor buttons.** Open the FAQ page and hover an accordion
      header — it should stay white with dark Playfair text, not turn brown with
      white uppercase text. Same for every button on the site.
- [ ] **FAQ titles line up with their answers.** Open any FAQ and expand an item:
      the question and the answer text below it must share the same left edge. If
      the question is indented further, Elementor's kit padding is winning — see
      README §10.
- [ ] A test enquiry appears in **Form Entries** and both inboxes
- [ ] **The header logo reads correctly** — dark "ADVANTAGE" wordmark beside the
      black "A" mark on the white header. If the wordmark looks pale, open
      Appearance → Advantage Setup and check the *Header logo* row: a Custom Logo
      from the media library may be overriding the bundled artwork.

---

## Still to do before the site is truly finished

These are open items, not bugs:

1. **SMTP** (step 9). The one thing that can silently cost a client.
2. **Social URLs** for Facebook, Instagram and LinkedIn.
3. **Featured images** on posts that lack them.
4. **The Google Maps embed** on the contact page sets Google cookies. Swap it if
   the site must be cookie-free.
5. **Two links repointed.** The home page's team section pointed at English
   template pages that are not part of the live Swedish site; both now go to the
   contact page. See the file header of `page-templates/template-hem.php`.
6. **Ask Google to re-crawl.** The old /artiklar/page/2/ … URLs now carry
   `noindex, follow`, so they drop out of the index on the next crawl. To hurry
   it along, submit the sitemap again in Search Console. Nothing needs deleting
   by hand.
7. **Privacy notice.** Entries store the visitor's IP and where they came from.
   That is defensible for handling a legal enquiry, but it belongs in the site's
   privacy notice, and entries should not be kept forever. A retention decision
   for the firm — the theme deliberately does not delete anything on its own.

---

## If something goes wrong

**Switch back to the previous theme.** Every page and post is untouched, so the
site returns to exactly its current state. Form entries already collected stay in
the database and reappear if you switch back again. Then tell me what you saw —
nothing about this change is one-way.
