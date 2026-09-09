<?php
/**
 * Page head for the blog archive, category archives and search results.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$adf_posts_page = (int) get_option( 'page_for_posts' );

if ( is_search() ) {
	$adf_title = sprintf(
		/* translators: %s: search query */
		__( 'Sökresultat för ”%s”', 'advantage-dolda-fel' ),
		get_search_query()
	);
	$adf_eyebrow = __( 'Sök', 'advantage-dolda-fel' );
	$adf_lead    = '';
} elseif ( is_category() || is_tag() || is_tax() ) {
	$adf_title   = single_term_title( '', false );
	$adf_eyebrow = __( 'Artiklar', 'advantage-dolda-fel' );
	$adf_lead    = wp_strip_all_tags( term_description() );
} elseif ( is_author() ) {
	$adf_title   = get_the_author();
	$adf_eyebrow = __( 'Skribent', 'advantage-dolda-fel' );
	$adf_lead    = get_the_author_meta( 'description' );
} elseif ( is_year() || is_month() || is_day() ) {
	$adf_title   = get_the_archive_title();
	$adf_eyebrow = __( 'Arkiv', 'advantage-dolda-fel' );
	$adf_lead    = '';
} else {
	$adf_title   = $adf_posts_page ? get_the_title( $adf_posts_page ) : __( 'Artiklar', 'advantage-dolda-fel' );
	$adf_eyebrow = __( 'Artiklar', 'advantage-dolda-fel' );
	$adf_lead    = $adf_posts_page ? wp_strip_all_tags( get_post_field( 'post_content', $adf_posts_page ) ) : '';

	// The live listing carries this line under the title. Used when the Posts
	// page has no content of its own to draw from.
	if ( ! $adf_lead ) {
		$adf_lead = __( 'Här hittar du våra senaste artiklar.', 'advantage-dolda-fel' );
	}
}

// Banner: the Posts page's own featured image if it has one, otherwise the house banner.
$adf_banner_id = $adf_posts_page ? get_post_thumbnail_id( $adf_posts_page ) : 0;
?>
<section class="page-head" aria-labelledby="page-title">
  <div class="page-head__media" aria-hidden="true">
    <?php
    if ( $adf_banner_id ) {
      echo wp_get_attachment_image( $adf_banner_id, 'adf-wide', false, array( 'sizes' => '100vw', 'alt' => '' ) );
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
    <nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Brödsmulor', 'advantage-dolda-fel' ); ?>">
      <ol>
        <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Hem', 'advantage-dolda-fel' ); ?></a></li>
        <li><span aria-current="page"><?php echo esc_html( $adf_title ); ?></span></li>
      </ol>
    </nav>

    <p class="badge badge--dark"><?php echo esc_html( $adf_eyebrow ); ?></p>
    <h1 class="h1" id="page-title"><?php echo esc_html( $adf_title ); ?></h1>

    <?php if ( $adf_lead ) : ?>
      <p class="lead"><?php echo esc_html( wp_trim_words( $adf_lead, 42, '…' ) ); ?></p>
    <?php endif; ?>
  </div>
</section>
