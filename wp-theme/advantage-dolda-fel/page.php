<?php
/**
 * Default page template.
 *
 * Used by any page that has NOT been given one of the theme's named templates in
 * Page Attributes -> Template. Renders the page title in the site's page head and
 * the editor content in a .prose column, so an Elementor-built or Gutenberg-built
 * page looks at home without needing a bespoke template.
 *
 * Elementor's Single location is offered the request first, so a Theme Builder
 * "Single Page" template takes precedence when one matches.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( ! adf_do_elementor_location( 'single' ) ) :
	while ( have_posts() ) :
		the_post();
		?>

<section class="page-head" aria-labelledby="page-title">
  <div class="page-head__media" aria-hidden="true">
    <?php
    if ( has_post_thumbnail() ) {
      the_post_thumbnail( 'adf-wide', array( 'sizes' => '100vw', 'fetchpriority' => 'high', 'alt' => '' ) );
    } else {
      ?>
      <img src="<?php echo adf_asset( 'assets/images/property/stockholm-skyline-wide-1600.webp' ); ?>"
           srcset="<?php echo esc_attr( adf_srcset( 'assets/images/property/stockholm-skyline-wide-1000.webp 1000w, assets/images/property/stockholm-skyline-wide-1600.webp 1600w, assets/images/property/stockholm-skyline-wide-2400.webp 2400w' ) ); ?>"
           sizes="100vw" width="1600" height="600" fetchpriority="high" decoding="async" alt="">
      <?php
    }
    ?>
  </div>

  <div class="container page-head__inner on-dark">
    <h1 class="h1" id="page-title"><?php the_title(); ?></h1>
  </div>
</section>

<section class="section section--lg">
  <div class="container">
    <div class="article-layout">
      <div class="article-main">
        <div class="prose"><?php the_content(); ?></div>
        <?php
        wp_link_pages(
          array(
            'before' => '<nav class="adf-page-links u-mt-7"><span>' . esc_html__( 'Sidor:', 'advantage-dolda-fel' ) . '</span>',
            'after'  => '</nav>',
          )
        );
        ?>
      </div>
    </div>
  </div>
</section>

		<?php
	endwhile;
endif;

get_footer();
