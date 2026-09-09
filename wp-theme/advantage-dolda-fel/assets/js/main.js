/* =============================================================================
   ADVANTAGE LAW FIRM — main.js
   Vanilla JS, no dependencies, no build step.
   Everything degrades gracefully: with JavaScript off the site stays fully
   readable and navigable, accordions render open, and forms submit natively
   to whatever endpoint is configured.
   ============================================================================= */
(function () {
  'use strict';

  var d = document;
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function $(s, c) { return (c || d).querySelector(s); }
  function $$(s, c) { return Array.prototype.slice.call((c || d).querySelectorAll(s)); }

  /* 1. Progressive-enhancement flags ---------------------------------------- */
  d.documentElement.classList.remove('no-js');
  d.documentElement.classList.add('js', 'js-reveal');

  /* 2. Sticky header shadow -------------------------------------------------- */
  (function stickyHeader() {
    var header = $('.site-header');
    if (!header) return;
    var ticking = false;
    function update() { header.classList.toggle('is-stuck', window.scrollY > 8); ticking = false; }
    window.addEventListener('scroll', function () {
      if (!ticking) { window.requestAnimationFrame(update); ticking = true; }
    }, { passive: true });
    update();
  })();

  /* 3. Mobile drawer — focus trap + scroll lock ------------------------------ */
  (function mobileNav() {
    var toggle = $('[data-nav-toggle]');
    var drawer = $('#mobile-nav');
    if (!toggle || !drawer) return;
    var closeBtn = $('[data-nav-close]', drawer);
    var lastFocused = null;

    function focusables() {
      return $$('a[href], button:not([disabled])', drawer)
        .filter(function (el) { return el.offsetParent !== null; });
    }
    function open() {
      lastFocused = d.activeElement;
      drawer.classList.add('is-open');
      toggle.setAttribute('aria-expanded', 'true');
      d.body.classList.add('is-locked');
      var f = focusables(); if (f.length) f[0].focus();
    }
    function close() {
      drawer.classList.remove('is-open');
      toggle.setAttribute('aria-expanded', 'false');
      d.body.classList.remove('is-locked');
      if (lastFocused) lastFocused.focus();
    }

    toggle.addEventListener('click', function () {
      if (drawer.classList.contains('is-open')) close(); else open();
    });
    if (closeBtn) closeBtn.addEventListener('click', close);

    drawer.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') { close(); return; }
      if (e.key !== 'Tab') return;
      var f = focusables(); if (!f.length) return;
      var first = f[0], last = f[f.length - 1];
      if (e.shiftKey && d.activeElement === first) { e.preventDefault(); last.focus(); }
      else if (!e.shiftKey && d.activeElement === last) { e.preventDefault(); first.focus(); }
    });

    $$('a[href]', drawer).forEach(function (a) {
      a.addEventListener('click', function () {
        d.body.classList.remove('is-locked');
        drawer.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
      });
    });

    window.addEventListener('resize', function () {
      if (window.innerWidth >= 1000 && drawer.classList.contains('is-open')) close();
    });
  })();

  /* 4. Accordions ------------------------------------------------------------ */
  (function accordions() {
    $$('.accordion').forEach(function (group) {
      var single = group.hasAttribute('data-single');
      var triggers = $$('.accordion__trigger', group);

      triggers.forEach(function (trigger, i) {
        var panel = trigger.nextElementSibling;
        if (!panel) return;
        var openByDefault = trigger.getAttribute('data-open') === 'true';

        if (!trigger.id) trigger.id = (group.id || 'acc') + '-t' + i;
        if (!panel.id) panel.id = (group.id || 'acc') + '-p' + i;
        trigger.setAttribute('aria-controls', panel.id);
        panel.setAttribute('role', 'region');
        panel.setAttribute('aria-labelledby', trigger.id);
        trigger.setAttribute('aria-expanded', String(openByDefault));

        trigger.addEventListener('click', function () {
          var isOpen = trigger.getAttribute('aria-expanded') === 'true';
          if (single && !isOpen) {
            triggers.forEach(function (t) { t.setAttribute('aria-expanded', 'false'); });
          }
          trigger.setAttribute('aria-expanded', String(!isOpen));
        });
      });
    });
  })();

  /* 5. Scroll reveal --------------------------------------------------------- *
     Exposed as window.adfReveal(scope) so content added after page load — the
     cards the Load More button fetches — can be registered too. Without that,
     appended cards keep the opacity:0 the reveal CSS starts them at and never
     appear.
     -------------------------------------------------------------------------- */
  var revealObserver = null;

  function revealAll(targets) {
    targets.forEach(function (el) { el.classList.add('is-visible'); });
  }

  window.adfReveal = function (scope) {
    var root = scope || d;
    var targets = $$('[data-reveal]', root).filter(function (el) {
      return !el.classList.contains('is-visible');
    });
    if (!targets.length) return;

    if (reduceMotion || !('IntersectionObserver' in window)) {
      revealAll(targets);
      return;
    }

    if (!revealObserver) {
      revealObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          var delay = parseInt(entry.target.getAttribute('data-reveal-delay') || '0', 10);
          window.setTimeout(function () { entry.target.classList.add('is-visible'); }, delay);
          revealObserver.unobserve(entry.target);
        });
      }, { rootMargin: '0px 0px -8% 0px', threshold: 0.06 });
    }

    targets.forEach(function (el) { revealObserver.observe(el); });
  };

  window.adfReveal();

  /* 6. Reading progress (blog detail) ---------------------------------------- */
  (function readingProgress() {
    var bar = $('[data-reading-progress]');
    var article = $('[data-article-body]');
    if (!bar || !article) return;
    if (reduceMotion) { bar.style.display = 'none'; return; }
    var ticking = false;
    function update() {
      var rect = article.getBoundingClientRect();
      var total = rect.height - window.innerHeight;
      var p = total > 0 ? Math.min(1, Math.max(0, -rect.top / total)) : 0;
      bar.style.transform = 'scaleX(' + p + ')';
      ticking = false;
    }
    window.addEventListener('scroll', function () {
      if (!ticking) { window.requestAnimationFrame(update); ticking = true; }
    }, { passive: true });
    window.addEventListener('resize', update);
    update();
  })();

  /* 7. Table-of-contents scrollspy ------------------------------------------- */
  (function scrollspy() {
    var navs = $$('[data-scrollspy]');
    if (!navs.length || !('IntersectionObserver' in window)) return;

    navs.forEach(function (nav) {
      var links = $$('a[href^="#"]', nav);
      var map = {}, sections = [];
      links.forEach(function (link) {
        var id = link.getAttribute('href').slice(1);
        var section = d.getElementById(id);
        if (section) { map[id] = link; sections.push(section); }
      });
      if (!sections.length) return;

      var visible = new Set();
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
          if (e.isIntersecting) visible.add(e.target.id); else visible.delete(e.target.id);
        });
        var current = sections.filter(function (s) { return visible.has(s.id); })[0];
        links.forEach(function (l) { l.classList.remove('is-active'); l.removeAttribute('aria-current'); });
        if (current && map[current.id]) {
          map[current.id].classList.add('is-active');
          map[current.id].setAttribute('aria-current', 'true');
        }
      }, { rootMargin: '-20% 0px -65% 0px', threshold: 0 });
      sections.forEach(function (s) { io.observe(s); });
    });
  })();

  /* 8. Blog filter + search --------------------------------------------------- */
  (function postFilter() {
    var root = $('[data-post-filter]');
    if (!root) return;
    var chips = $$('[data-filter]', root);
    var search = $('[data-post-search]');
    var list = $('[data-post-list]');
    var empty = $('[data-post-empty]');
    var counter = $('[data-post-count]');
    if (!list) return;

    var cards = $$('[data-post]', list);
    var activeCat = 'all';

    function apply() {
      var q = (search ? search.value.trim() : '').toLowerCase();
      var shown = 0;
      cards.forEach(function (card) {
        var cat = card.getAttribute('data-category') || '';
        var hay = (card.getAttribute('data-search') || card.textContent).toLowerCase();
        var show = (activeCat === 'all' || cat === activeCat) && (!q || hay.indexOf(q) !== -1);
        card.hidden = !show;
        if (show) shown++;
      });
      if (empty) empty.hidden = shown !== 0;
      if (counter) counter.textContent = shown === 1 ? '1 article' : shown + ' articles';
    }

    chips.forEach(function (chip) {
      chip.addEventListener('click', function () {
        chips.forEach(function (c) { c.setAttribute('aria-pressed', 'false'); });
        chip.setAttribute('aria-pressed', 'true');
        activeCat = chip.getAttribute('data-filter');
        apply();
      });
    });
    if (search) {
      var t;
      search.addEventListener('input', function () {
        window.clearTimeout(t); t = window.setTimeout(apply, 140);
      });
    }
    apply();
  })();

  /* 9. Load more -------------------------------------------------------------- */
  (function loadMore() {
    $$('[data-load-more]').forEach(function (btn) {
      var container = $(btn.getAttribute('data-load-more'));
      if (!container) return;
      var step = parseInt(btn.getAttribute('data-step') || '6', 10);

      $$('[data-more-item]', container).forEach(function (el, i) {
        if (i >= step) el.classList.add('is-hidden-extra');
      });
      function hiddenItems() {
        return $$('[data-more-item]', container).filter(function (el) {
          return el.classList.contains('is-hidden-extra');
        });
      }
      function sync() {
        $$('[data-more-item]', container).forEach(function (el) {
          el.hidden = el.classList.contains('is-hidden-extra');
        });
        btn.hidden = hiddenItems().length === 0;
      }
      btn.addEventListener('click', function () {
        hiddenItems().slice(0, step).forEach(function (el) { el.classList.remove('is-hidden-extra'); });
        sync();
      });
      sync();
    });
  })();

  /* 10. Contact form — validation + submission ------------------------------- *
     Posts to the theme's WordPress handler (admin-ajax.php, action
     adf_form_submit), which stores the entry, records the tracking data and
     sends the notification. The endpoint is injected by wp_localize_script as
     window.adfForm; the data-endpoint attribute on the form is the fallback.

     Every reply is JSON: { ok: bool, message: string }. The message shown to the
     visitor is always the server's, so the wording stays in one place and stays
     Swedish. Nothing typed here is written to localStorage or sessionStorage —
     enquiries to a law firm are confidential.
     -------------------------------------------------------------------------- */
  (function contactForm() {
    $$('form[data-contact-form]').forEach(function (form) {
      var status = $('[data-form-status]', form);
      var submit = $('button[type="submit"]', form);
      var submitLabel = submit ? submit.textContent : 'Send enquiry';

      function setStatus(state, message) {
        if (!status) return;
        status.setAttribute('data-state', state);
        status.textContent = message;
        status.classList.add('is-visible');
      }
      function wrapOf(input) { return input.closest('.field') || input.closest('.consent'); }

      function validate(input) {
        var wrap = wrapOf(input);
        var valid = input.checkValidity();
        if (input.type === 'email' && input.value) {
          valid = valid && /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(input.value);
        }
        if (input.type === 'tel' && input.value) {
          valid = valid && /^[\d\s+()-]{6,}$/.test(input.value);
        }
        if (wrap) wrap.setAttribute('data-invalid', String(!valid));
        input.setAttribute('aria-invalid', String(!valid));
        return valid;
      }

      $$('input, select, textarea', form).forEach(function (input) {
        input.addEventListener('blur', function () { validate(input); });
        input.addEventListener('input', function () {
          var wrap = wrapOf(input);
          if (wrap && wrap.getAttribute('data-invalid') === 'true') validate(input);
        });
      });

      form.addEventListener('submit', function (e) {
        e.preventDefault();
        var inputs = $$('input, select, textarea', form).filter(function (i) { return i.type !== 'hidden'; });
        var firstInvalid = null;
        inputs.forEach(function (input) { if (!validate(input) && !firstInvalid) firstInvalid = input; });

        var cfg = window.adfForm || {};

        if (firstInvalid) {
          setStatus('error', cfg.invalid || 'Kontrollera de markerade fälten och försök igen.');
          firstInvalid.focus();
          return;
        }

        var endpoint = cfg.endpoint || form.getAttribute('data-endpoint');
        if (!endpoint) {
          // No WordPress behind the form. Never claim the message was sent.
          setStatus('info',
            'Formuläret är inte kopplat till en mottagare än. Ring + 46 8 20 21 40 ' +
            'eller mejla info@advantage.se så återkommer vi direkt.');
          return;
        }

        if (submit) { submit.setAttribute('aria-disabled', 'true'); submit.textContent = cfg.sending || 'Skickar…'; }

        fetch(endpoint, {
          method: 'POST',
          headers: { Accept: 'application/json' },
          credentials: 'same-origin',
          body: new FormData(form)
        })
          .then(function (res) { return res.json().catch(function () { return null; }); })
          .then(function (data) {
            if (data && data.ok) {
              form.reset();
              setStatus('success', data.message);
            } else {
              // The server explains why — a stale nonce, a missing phone number,
              // a spam trap. Showing its message beats a generic failure.
              setStatus('error', (data && data.message) || cfg.failed ||
                'Ditt meddelande kunde inte skickas. Ring + 46 8 20 21 40 eller mejla info@advantage.se.');
            }
          })
          .catch(function () {
            setStatus('error', cfg.failed ||
              'Ditt meddelande kunde inte skickas. Ring + 46 8 20 21 40 eller mejla info@advantage.se.');
          })
          .then(function () {
            if (submit) { submit.removeAttribute('aria-disabled'); submit.textContent = submitLabel; }
          });
      });
    });
  })();

  /* 12. AJAX Load More (blog listing) ---------------------------------------- *
     Appends the next batch of posts to the grid without changing the URL, so the
     archive never produces /page/2/, /page/3/ … for a crawler to index.

     Server contract (action adf_load_more): returns
       { html: '<article>…', more: bool, loaded: int, total: int }
     -------------------------------------------------------------------------- */
  (function ajaxLoadMore() {
    var root = $('[data-ajax-load-more]');
    if (!root) return;

    var btn    = $('[data-load-more-btn]', root);
    var status = $('[data-load-more-status]', root);
    var grid   = $('[data-post-grid]');
    var cfg    = window.adfLoadMore || {};

    if (!btn || !grid || !cfg.endpoint) return;

    var page    = parseInt(root.getAttribute('data-page') || '1', 10);
    var max     = parseInt(root.getAttribute('data-max') || '1', 10);
    var ctx     = root.getAttribute('data-ctx') || '{}';
    var nonce   = root.getAttribute('data-nonce') || '';
    var label   = btn.textContent;
    var busy    = false;

    function say(msg) { if (status) status.textContent = msg; }

    btn.addEventListener('click', function () {
      if (busy || page >= max) return;
      busy = true;
      btn.setAttribute('aria-disabled', 'true');
      btn.textContent = cfg.loading || 'Laddar…';

      var body = new FormData();
      body.append('action', 'adf_load_more');
      body.append('nonce', nonce);
      body.append('paged', String(page + 1));
      body.append('ctx', ctx);

      fetch(cfg.endpoint, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { Accept: 'application/json' },
        body: body
      })
        .then(function (res) { return res.json().catch(function () { return null; }); })
        .then(function (data) {
          if (!data || !data.html) throw new Error('empty');

          // Remember where the new cards start so focus can move there.
          var before = $$('[data-post-card]', grid).length;

          grid.insertAdjacentHTML('beforeend', data.html);
          page += 1;
          root.setAttribute('data-page', String(page));

          var cards = $$('[data-post-card]', grid);
          var first = cards[before];

          // Register the new cards with the reveal observer, or they stay invisible.
          if (typeof window.adfReveal === 'function') window.adfReveal(grid);

          // Move focus to the first new card. Without this a keyboard user is
          // returned to the top of the document and loses their place entirely.
          if (first) {
            var link = first.querySelector('a');
            if (link) { link.setAttribute('tabindex', '-1'); link.focus({ preventScroll: true }); }
          }

          say((cfg.announced || '%d av %d artiklar visas.')
            .replace('%d', String(data.loaded || cards.length))
            .replace('%d', String(data.total || cards.length)));

          if (!data.more || page >= max) {
            btn.hidden = true;
            root.setAttribute('data-exhausted', 'true');
          }
        })
        .catch(function () {
          say(cfg.failed || 'Fler artiklar kunde inte laddas. Ladda om sidan och försök igen.');
        })
        .then(function () {
          busy = false;
          btn.removeAttribute('aria-disabled');
          btn.textContent = label;
        });
    });
  })();

  /* 11. Current year --------------------------------------------------------- */
  $$('[data-year]').forEach(function (el) { el.textContent = String(new Date().getFullYear()); });

})();
