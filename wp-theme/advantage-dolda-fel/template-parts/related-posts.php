<?php
/**
 * Related articles — three more from the same category.
 *
 * A plain WP_Query, so it works against the existing 80+ posts with no taxonomy
 * setup beyond the categories they already have. Renders nothing when there is
 * nothing to show.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$adf_cats = wp_get_post_categories( get_the_ID() );

$adf_related = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'post__not_in'        => array( get_the_ID() ),
		'category__in'        => ! empty( $adf_cats ) ? $adf_cats : array(),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

// Fall back to the most recent posts if the category has nothing else in it.
if ( ! $adf_related->have_posts() ) {
	$adf_related = new WP_Query(
		array(
			'post_type'           => 'post',
			'posts_per_page'      => 3,
			'post__not_in'        => array( get_the_ID() ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
}

if ( ! $adf_related->have_posts() ) {
	return;
}
?>
<section class="section section--lg section--alt" aria-labelledby="adf-related-title">
  <div class="container">
    <span class="eyebrow-rule" aria-hidden="true"></span>
    <h2 class="h2" id="adf-related-title"><?php esc_html_e( 'Fler artiklar', 'advantage-dolda-fel' ); ?></h2>

    <div class="grid grid-3 grid--lg u-mt-7">
      <?php
      while ( $adf_related->have_posts() ) :
        $adf_related->the_post();
        get_template_part( 'template-parts/content', 'card' );
      endwhile;
      wp_reset_postdata();
      ?>
    </div>

    <div class="btn-row u-mt-7">
      <a class="btn btn--primary" href="<?php echo adf_blog_url(); ?>">
        <?php esc_html_e( 'Alla artiklar', 'advantage-dolda-fel' ); ?>
        <span class="icon icon--arrow" aria-hidden="true"></span>
      </a>
    </div>
  </div>
</section>
