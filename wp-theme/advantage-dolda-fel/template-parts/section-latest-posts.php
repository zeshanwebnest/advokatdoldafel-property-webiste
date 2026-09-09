<?php
/**
 * "Senaste artiklarna" — the latest posts as a section, for the home page.
 *
 * The same .post-card component the article listing uses, so a card here and a
 * card on /artiklar/ are the same markup and the same design. What it leaves out,
 * deliberately, is the listing's furniture: no category chips, no search box, no
 * Load More. Those belong on the listing itself. This is a taster with one way
 * onward — the button to /artiklar/.
 *
 * Usage from a template:
 *
 *     get_template_part( 'template-parts/section', 'latest-posts' );
 *     get_template_part( 'template-parts/section', 'latest-posts', array( 'count' => 6 ) );
 *
 * Usage from the editor or an Elementor Shortcode widget:
 *
 *     [advantage_latest_posts]
 *     [advantage_latest_posts count="6" title="Fler artiklar"]
 *
 * Args:
 *   count   How many cards. Default 3, which fills one row of the 3-up grid.
 *   title   The heading. Default "Senaste artiklarna".
 *   lead    Text under the heading. Default is the line the live /artiklar/ page
 *           carries, so nothing new is invented.
 *   ground  'plain' (default) or 'alt' — pick whichever alternates against the
 *           sections either side of where you place it.
 *   button  'yes' (default) shows the link to the listing.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$adf_args   = isset( $args ) && is_array( $args ) ? $args : array();
$adf_count  = isset( $adf_args['count'] ) ? max( 1, min( 12, (int) $adf_args['count'] ) ) : 3;
$adf_title  = isset( $adf_args['title'] ) ? $adf_args['title'] : __( 'Senaste artiklarna', 'advantage-dolda-fel' );
$adf_lead   = isset( $adf_args['lead'] ) ? $adf_args['lead'] : __( 'Här hittar du våra senaste artiklar.', 'advantage-dolda-fel' );
$adf_ground = ( isset( $adf_args['ground'] ) && 'alt' === $adf_args['ground'] ) ? ' section--alt' : '';
$adf_button = ! isset( $adf_args['button'] ) || 'no' !== $adf_args['button'];

$adf_latest = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => $adf_count,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

// Nothing published yet: render nothing rather than an empty band.
if ( ! $adf_latest->have_posts() ) {
	wp_reset_postdata();
	return;
}

$adf_id = 'adf-latest-' . wp_unique_id();
?>
<!-- ===================== SENASTE ARTIKLARNA ===================== -->
<section class="section section--lg<?php echo esc_attr( $adf_ground ); ?>" aria-labelledby="<?php echo esc_attr( $adf_id ); ?>">
  <div class="container">

    <div class="section-head" data-reveal>
      <p class="badge"><?php esc_html_e( 'Artiklar', 'advantage-dolda-fel' ); ?></p>
      <h2 class="h2" id="<?php echo esc_attr( $adf_id ); ?>"><?php echo esc_html( $adf_title ); ?></h2>
      <?php if ( $adf_lead ) : ?>
        <p class="lead u-mt-5"><?php echo esc_html( $adf_lead ); ?></p>
      <?php endif; ?>
    </div>

    <div class="grid grid-3 grid--lg u-mt-7">
      <?php
      while ( $adf_latest->have_posts() ) :
        $adf_latest->the_post();
        get_template_part( 'template-parts/content', 'card' );
      endwhile;
      wp_reset_postdata();
      ?>
    </div>

    <?php if ( $adf_button ) : ?>
      <div class="btn-row u-mt-7" data-reveal>
        <a class="btn btn--primary" href="<?php echo adf_blog_url(); ?>">
          <?php esc_html_e( 'Alla artiklar', 'advantage-dolda-fel' ); ?>
          <span class="icon icon--arrow" aria-hidden="true"></span>
        </a>
      </div>
    <?php endif; ?>

  </div>
</section>
