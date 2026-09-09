<?php
/**
 * Template Name: Konsumenttvistnämnden
 *
 * The Bar Association's consumer disputes board.
 *
 * Built from the static page "konsumenttvistnamnden.html". The copy is the client's and is fixed —
 * see README.md in the theme root before editing any of it.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Preload the hero so the largest paint is requested before the CSS resolves.
adf_preload_hero(
	'assets/images/property/stockholm-norrmalm-facade-wide-1800.webp',
	'assets/images/property/stockholm-norrmalm-facade-wide-1200.webp 1200w, assets/images/property/stockholm-norrmalm-facade-wide-1800.webp 1800w, assets/images/property/stockholm-norrmalm-facade-wide-2400.webp 2400w',
	'100vw'
);

get_header();
?>

<!-- ===================== 1. HERO ===================== -->
<section class="hero hero--estate" aria-labelledby="hero-title">
  <div class="hero__frame">
    <div class="hero__media" aria-hidden="true">
      <img src="<?php echo ADF_URI; ?>/assets/images/property/stockholm-norrmalm-facade-wide-1800.webp"
           srcset="<?php echo ADF_URI; ?>/assets/images/property/stockholm-norrmalm-facade-wide-1200.webp 1200w,
                   <?php echo ADF_URI; ?>/assets/images/property/stockholm-norrmalm-facade-wide-1800.webp 1800w,
                   <?php echo ADF_URI; ?>/assets/images/property/stockholm-norrmalm-facade-wide-2400.webp 2400w"
           sizes="100vw" width="1800" height="1013"
           fetchpriority="high" decoding="async" alt="">
    </div>

    <div class="container hero__body">
      <span class="eyebrow-rule eyebrow-rule--dark" aria-hidden="true"></span>

      <h1 class="hero__title hero__title--compound" id="hero-title">Konsumenttvistnämnden</h1>
    </div>

    <figure class="hero__inset" aria-hidden="true">
      <img src="<?php echo ADF_URI; ?>/assets/images/property/stone-arch-window-portrait-600.webp"
           srcset="<?php echo ADF_URI; ?>/assets/images/property/stone-arch-window-portrait-600.webp 600w,
                   <?php echo ADF_URI; ?>/assets/images/property/stone-arch-window-portrait-900.webp 900w"
           sizes="15rem" width="600" height="750"
           loading="lazy" decoding="async" alt="">
    </figure>
  </div>
</section>

<!-- ===================== 2. OM NÄMNDEN ===================== -->
<section class="section section--lg" aria-label="Om Advokatsamfundets konsumenttvistnämnd">
  <div class="container">
    <div class="feature-panel feature-panel--square" data-reveal>
      <div class="feature-panel__media">
        <img src="<?php echo ADF_URI; ?>/assets/images/property/claim-documents-review-1000.webp"
             srcset="<?php echo ADF_URI; ?>/assets/images/property/claim-documents-review-600.webp 600w,
                     <?php echo ADF_URI; ?>/assets/images/property/claim-documents-review-1000.webp 1000w,
                     <?php echo ADF_URI; ?>/assets/images/property/claim-documents-review-1600.webp 1600w"
             sizes="(min-width: 940px) 46vw, 100vw"
             width="1000" height="667" loading="lazy" decoding="async"
             alt="En jurist går igenom handlingarna i ett ärende vid ett skrivbord">
      </div>

      <div class="feature-panel__body">
        <span class="eyebrow-rule" aria-hidden="true"></span>
        <div class="prose">
          <p>Advokatsamfundets konsumenttvistnämnd prövar arvodestvister och andra ekonomiska krav som en klient riktar mot en advokat eller advokatbyrå i anledning av en tjänst. Med konsument avses i detta sammanhang fysisk person, som agerar för ändamål som faller utanför närings- eller yrkesverksamhet.</p>
          <p>För att konsumenttvistnämnden ska pröva tvisten måste du som klient först informera din advokat om detta och Ni måste försöka lösa tvisten innan det skickas till advokatsamfundet. Ditt krav får inte understiga 1 000 kr och får inte överstiga 200 000 kr.</p>
          <p>Mer information finns på Advokatsamfundets hemsida. Konsumenttvistnämnden</p>
        </div>

        <address class="address-note u-mt-6">
          Sveriges advokatsamfund<br>
          Box 27321<br>
          102 54 Stockholm
        </address>
      </div>
    </div>
  </div>
</section>

<!-- ===================== 3. KONTAKTA OSS FÖR RÅDGIVNING ===================== -->
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
        <h2 class="h2" id="kontakt-title">Kontakta oss för rådgivning i ditt ärende.</h2>
        <p class="lead u-mt-5">Vi bistår klienter i hela Sverige med juridisk rådgivning för att hjälpa till att bedöma om ett fel juridiskt sett kvalificerar som ett dolt fel och vilka rättigheter och ersättningsmöjligheter som finns. Vårt mål är alltid att hjälpa våra klienter att nå en effektiv och juridiskt hållbar lösning.</p>
      </div>

      <div class="form-card" data-reveal data-reveal-delay="80">
        <?php
        // THE global form component — one definition in
        // template-parts/form-contact.php, used on every page of the site.
        get_template_part(
          'template-parts/form',
          'contact',
          array( 'source' => 'konsumenttvistnamnden' )
        );
        ?>
      </div>
    </div>
  </div>
</section>

<?php
get_footer();
