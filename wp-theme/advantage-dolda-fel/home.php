<?php
/**
 * Blog listing — the page assigned as "Posts page" in Settings -> Reading.
 *
 * Elementor first: if a Theme Builder Archive template is published and its
 * conditions match, Elementor renders and this file's markup never runs. Remove
 * or narrow that Elementor template and the design below takes over again. No
 * post content is touched either way.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( ! adf_do_elementor_location( 'archive' ) ) {
	get_template_part( 'template-parts/archive', 'head' );
	get_template_part( 'template-parts/archive', 'loop' );
	get_template_part( 'template-parts/cta', 'contact' );
}

get_footer();
