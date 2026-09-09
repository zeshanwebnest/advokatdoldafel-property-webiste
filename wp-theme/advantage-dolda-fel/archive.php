<?php
/**
 * Category, tag, author and date archives.
 *
 * Same design as the main blog listing. Elementor's Archive location is offered
 * the request first.
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
