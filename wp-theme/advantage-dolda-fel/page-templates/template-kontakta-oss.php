<?php
/**
 * Template Name: Kontakta oss
 *
 * Contact page: heading, contact lines, enquiry form and the map.
 *
 * Built from the static page "kontakta-oss.html". The copy is the client's and is fixed —
 * see README.md in the theme root before editing any of it.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Preload the hero so the largest paint is requested before the CSS resolves.
adf_preload_hero(
	'assets/images/property/stockholm-boulevard-wide-1800.webp',
	'assets/images/property/stockholm-boulevard-wide-1200.webp 1200w, assets/images/property/stockholm-boulevard-wide-1800.webp 1800w, assets/images/property/stockholm-boulevard-wide-2400.webp 2400w',
	'100vw'
);

get_header();
?>


<!-- ===================== 1. HERO ===================== -->
<section class="hero hero--estate" aria-labelledby="hero-title">
  <div class="hero__frame">
    <div class="hero__media" aria-hidden="true">
      <img src="<?php echo ADF_URI; ?>/assets/images/property/stockholm-boulevard-wide-1800.webp"
           srcset="<?php echo ADF_URI; ?>/assets/images/property/stockholm-boulevard-wide-1200.webp 1200w,
                   <?php echo ADF_URI; ?>/assets/images/property/stockholm-boulevard-wide-1800.webp 1800w,
                   <?php echo ADF_URI; ?>/assets/images/property/stockholm-boulevard-wide-2400.webp 2400w"
           sizes="100vw" width="1800" height="1013"
           fetchpriority="high" decoding="async" alt="">
    </div>

    <div class="container hero__body">
      <span class="eyebrow-rule" aria-hidden="true"></span>

      <h1 class="hero__title" id="hero-title">Kontakta oss för dolda fel</h1>
    </div>

    <figure class="hero__inset" aria-hidden="true">
      <img src="<?php echo ADF_URI; ?>/assets/images/property/city-entrance-doors-portrait-600.webp"
           srcset="<?php echo ADF_URI; ?>/assets/images/property/city-entrance-doors-portrait-600.webp 600w,
                   <?php echo ADF_URI; ?>/assets/images/property/city-entrance-doors-portrait-900.webp 900w"
           sizes="15rem" width="600" height="750"
           loading="lazy" decoding="async" alt="">
    </figure>
  </div>
</section>

<!-- ===================== 2. KARTA ===================== -->
<section class="section section--lg section--alt" aria-label="ADVANTAGE Advokatbyrå AB SVEAVÄGEN 33, 111 34 STOCKHOLM">
  <div class="container">
    <figure class="map-frame" data-reveal>
      <iframe
        src="https://maps.google.com/maps?q=ADVANTAGE%20Advokatbyr%C3%A5%20AB%20SVEAV%C3%84GEN%2033%2C%20111%2034%20STOCKHOLM&amp;t=m&amp;z=10&amp;output=embed&amp;iwloc=near"
        title="ADVANTAGE Advokatbyrå AB SVEAVÄGEN 33, 111 34 STOCKHOLM"
        aria-label="ADVANTAGE Advokatbyrå AB SVEAVÄGEN 33, 111 34 STOCKHOLM"
        width="1200" height="600" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"></iframe>
    </figure>
  </div>
</section>
<!-- ===================== 3. HAR DU NÅGRA FRÅGOR OM DOLDA FEL? ===================== -->
<section class="section section--lg cta cta--split" aria-labelledby="kontakt-title">
  <div class="cta__media" aria-hidden="true">
    <img src="<?php echo ADF_URI; ?>/assets/images/property/stockholm-skyline-wide-1600.webp"
         srcset="<?php echo ADF_URI; ?>/assets/images/property/stockholm-skyline-wide-1000.webp 1000w,
                 <?php echo ADF_URI; ?>/assets/images/property/stockholm-skyline-wide-1600.webp 1600w,
                 <?php echo ADF_URI; ?>/assets/images/property/stockholm-skyline-wide-2400.webp 2400w"
         sizes="100vw" width="1600" height="600" loading="lazy" decoding="async" alt="">
  </div>
  <div class="container cta__inner on-dark">
    <div class="cta__grid">
      <div data-reveal>
        <span class="eyebrow-rule eyebrow-rule--dark" aria-hidden="true"></span>
        <h2 class="h2" id="kontakt-title">Har du några frågor om dolda fel?<br>Kontakta oss gärna!</h2>

        <ul class="contact-lines">
          <li>
            <span class="contact-lines__icon" aria-hidden="true"><span class="icon icon--phone"></span></span>
            <a href="tel:+468202140">+468202140</a>
          </li>
          <li>
            <span class="contact-lines__icon" aria-hidden="true"><span class="icon icon--mail"></span></span>
            <a href="mailto:info@advantage.se">info@advantage.se</a>
          </li>
          <li>
            <span class="contact-lines__icon" aria-hidden="true"><span class="icon icon--pin"></span></span>
            <span>ADVANTAGE ADVOKATBYRÅ AB SVEAVÄGEN 33 111 34 STOCKHOLM</span>
          </li>
        </ul>
      </div>

      <div class="form-card" data-reveal data-reveal-delay="80">
        <?php
        // THE global form component. This page is where its field set comes
        // from, and every other place on the site now renders the same one.
        get_template_part(
          'template-parts/form',
          'contact',
          array( 'source' => 'kontakta-oss' )
        );
        ?>
      </div>
    </div>
  </div>
</section>


<?php
// Structured data for this page, emitted verbatim from the static build.
// If an SEO plugin also outputs FAQ schema, delete this block to avoid duplicates.
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ContactPage",
  "url": "https://advokatdoldafel.se/kontakta-oss/",
  "name": "Kontakta oss för dolda fel",
  "mainEntity": {
    "@type": "LegalService",
    "name": "Advantage Advokatbyrå AB",
    "telephone": "+468202140",
    "email": "info@advantage.se",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Sveavägen 33",
      "postalCode": "111 34",
      "addressLocality": "Stockholm",
      "addressCountry": "SE"
    }
  }
}
</script>

<?php
get_footer();
