<?php
/**
 * The closing contact band, shared by the blog archive, single posts and 404.
 *
 * Same component as the one that closes every practice page, so an article ends
 * the way the rest of the site does.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- ===================== CONTACT CTA ===================== -->
<section class="section section--lg cta cta--split" aria-labelledby="adf-cta-title">
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
        <h2 class="h2" id="adf-cta-title">Kontakta oss för rådgivning i ditt ärende.</h2>
        <p class="lead u-mt-5">Vi bistår klienter i hela Sverige med juridisk rådgivning för att hjälpa till att bedöma om ett fel juridiskt sett kvalificerar som ett dolt fel och vilka rättigheter och ersättningsmöjligheter som finns. Vårt mål är alltid att hjälpa våra klienter att nå en effektiv och juridiskt hållbar lösning.</p>

      </div>

      <div class="form-card" data-reveal data-reveal-delay="80">
        <?php
        // THE global form component. Same file everywhere on the site — change a
        // field in template-parts/form-contact.php and it changes here too.
        get_template_part(
          'template-parts/form',
          'contact',
          array( 'source' => 'cta-band' )
        );
        ?>
      </div>
    </div>
  </div>
</section>
