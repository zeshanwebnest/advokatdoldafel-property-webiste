<?php
/**
 * Author card, closing an article.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$adf_bio = get_the_author_meta( 'description' );

if ( ! $adf_bio ) {
	return;
}
?>
<div class="author-card u-mt-8">
  <div class="author-card__media">
    <?php echo get_avatar( get_the_author_meta( 'ID' ), 160, '', get_the_author(), array( 'loading' => 'lazy' ) ); ?>
  </div>
  <div>
    <h2 class="h3"><?php echo esc_html( get_the_author() ); ?></h2>
    <p class="muted u-mt-4"><?php echo esc_html( $adf_bio ); ?></p>
  </div>
</div>
