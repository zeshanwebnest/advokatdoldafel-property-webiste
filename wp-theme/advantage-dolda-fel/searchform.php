<?php
/**
 * Search form, styled to sit in the archive filter bar.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$adf_id = 'adf-search-' . wp_unique_id();
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
  <label class="visually-hidden" for="<?php echo esc_attr( $adf_id ); ?>"><?php esc_html_e( 'Sök bland artiklar', 'advantage-dolda-fel' ); ?></label>
  <input class="input" type="search" id="<?php echo esc_attr( $adf_id ); ?>" name="s"
         value="<?php echo esc_attr( get_search_query() ); ?>"
         placeholder="<?php esc_attr_e( 'Sök bland artiklar', 'advantage-dolda-fel' ); ?>">
  <input type="hidden" name="post_type" value="post">
  <button class="btn btn--primary" type="submit"><?php esc_html_e( 'Sök', 'advantage-dolda-fel' ); ?></button>
</form>
