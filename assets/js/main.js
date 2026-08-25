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

  /* 5. Scroll reveal --------------------------------------------------------- */
  (function reveal() {
    var targets = $$('[data-reveal]');
    if (!targets.length) return;
    if (reduceMotion || !('IntersectionObserver' in window)) {
      targets.forEach(function (el) { el.classList.add('is-visible'); });
      return;
    }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        var delay = parseInt(entry.target.getAttribute('data-reveal-delay') || '0', 10);
        window.setTimeout(function () { entry.target.classList.add('is-visible'); }, delay);
        io.unobserve(entry.target);
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.06 });
    targets.forEach(function (el) { io.observe(el); });
  })();

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

  /* 10. Contact form — validation + endpoint guard ---------------------------- *
     ENDPOINT CONFIGURATION
     Add data-endpoint="https://..." to the <form> to enable real submission.
     While it is absent the form validates input but NEVER claims a message was
     sent — it points the visitor at the phone number and e-mail instead.
     Nothing typed here is written to localStorage/sessionStorage: enquiries to
     a law firm are confidential.
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

        if (firstInvalid) {
          setStatus('error', 'Please check the highlighted fields and try again.');
          firstInvalid.focus();
          return;
        }

        var endpoint = form.getAttribute('data-endpoint');
        if (!endpoint) {
          setStatus('info',
            'This form is not connected to a recipient yet. Please call +46 8 20 21 40 or ' +
            'e-mail info@advantage.se and we will get straight back to you. ' +
            '(Developer note: set data-endpoint on the <form> to enable sending.)');
          return;
        }

        if (submit) { submit.setAttribute('aria-disabled', 'true'); submit.textContent = 'Sending…'; }
        fetch(endpoint, { method: 'POST', headers: { Accept: 'application/json' }, body: new FormData(form) })
          .then(function (res) {
            if (!res.ok) throw new Error('Bad response');
            form.reset();
            setStatus('success', 'Thank you. Your enquiry has been received and we will respond shortly.');
          })
          .catch(function () {
            setStatus('error', 'Your message could not be sent. Please call +46 8 20 21 40 or e-mail info@advantage.se.');
          })
          .then(function () {
            if (submit) { submit.removeAttribute('aria-disabled'); submit.textContent = submitLabel; }
          });
      });
    });
  })();

  /* 11. Current year --------------------------------------------------------- */
  $$('[data-year]').forEach(function (el) { el.textContent = String(new Date().getFullYear()); });

})();
