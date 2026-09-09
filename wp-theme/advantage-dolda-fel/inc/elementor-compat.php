<?php
/**
 * Elementor Theme Builder compatibility.
 *
 * This is what gives the site both of the options asked for, with no switch to flip:
 *
 *   1. No Elementor Theme Builder template published for a location
 *      -> the theme renders its own design (header, footer, archive, single).
 *
 *   2. An Elementor Theme Builder template IS published and its display
 *      conditions match the current request
 *      -> Elementor renders it, and the theme steps aside completely.
 *
 * The choice is therefore made in Templates -> Theme Builder, not in code.
 * Unpublishing an Elementor template (or narrowing its conditions) hands that
 * location straight back to the theme on the next page load.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the four core Elementor theme locations: header, footer, single, archive.
 *
 * Without this, Elementor Pro cannot take over any part of the page and Theme
 * Builder templates silently do nothing on the front end. This single call is the
 * whole reason Theme Builder keeps working after switching to this theme.
 *
 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $manager Location manager.
 */
function adf_register_elementor_locations( $manager ) {
	$manager->register_all_core_location();
}
add_action( 'elementor/theme/register_locations', 'adf_register_elementor_locations' );

/**
 * Render an Elementor theme location if one applies.
 *
 * Returns true when Elementor is active, has a published template for $location,
 * and that template's display conditions match the current request — in which case
 * it has already echoed the markup. Returns false otherwise, which is the theme's
 * cue to render its own design.
 *
 * Every template in this theme guards its markup with this, e.g.
 *
 *     if ( ! adf_do_elementor_location( 'single' ) ) {
 *         // the theme's own single-post design
 *     }
 *
 * @param string $location One of: header, footer, single, archive.
 * @return bool True if Elementor rendered the location.
 */
function adf_do_elementor_location( $location ) {
	if ( ! function_exists( 'elementor_theme_do_location' ) ) {
		return false;
	}

	return elementor_theme_do_location( $location );
}

/**
 * True when Elementor Pro's Theme Builder is available at all.
 *
 * Used by the admin notice below and by the documentation shortcode, so the site
 * owner can see at a glance which of the two modes they are in.
 *
 * @return bool
 */
function adf_elementor_theme_builder_active() {
	return function_exists( 'elementor_theme_do_location' );
}

/**
 * Elementor caches its CSS per template. Switching themes changes the wrapper
 * markup, so stale cached CSS can leave Theme Builder templates looking broken
 * until the cache is cleared. Doing it once on activation saves a support round.
 */
function adf_flush_elementor_css_on_activation() {
	if ( ! is_admin() ) {
		return;
	}

	if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
}
add_action( 'after_switch_theme', 'adf_flush_elementor_css_on_activation' );
