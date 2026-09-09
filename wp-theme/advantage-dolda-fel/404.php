<?php
/**
 * 404.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="page-head" aria-labelledby="page-title">
  <div class="page-head__media" aria-hidden="true">
    <img src="<?php echo adf_asset( 'assets/images/property/stockholm-skyline-wide-1600.webp' ); ?>"
         sizes="100vw" width="1600" height="600" fetchpriority="high" decoding="async" alt="">
  </div>
  <div class="container page-head__inner on-dark">
    <p class="badge badge--dark">404</p>
    <h1 class="h1" id="page-title"><?php esc_html_e( 'Sidan kunde inte hittas', 'advantage-dolda-fel' ); ?></h1>
    <p class="lead"><?php esc_html_e( 'Länken kan vara gammal eller felstavad. Prova sökningen nedan, eller gå till startsidan.', 'advantage-dolda-fel' ); ?></p>
  </div>
</section>

<section class="section section--lg">
  <div class="container">
    <div class="article-layout">
      <div class="article-main">
        <?php get_search_form(); ?>
        <div class="btn-row u-mt-7">
          <a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Till startsidan', 'advantage-dolda-fel' ); ?></a>
          <a class="btn btn--ghost" href="<?php echo adf_blog_url(); ?>"><?php esc_html_e( 'Alla artiklar', 'advantage-dolda-fel' ); ?></a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
get_template_part( 'template-parts/cta', 'contact' );
get_footer();
