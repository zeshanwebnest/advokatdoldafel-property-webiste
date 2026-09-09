<?php
/**
 * Advantage Dolda Fel — theme bootstrap.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ADF_VERSION', '1.4.0' );
define( 'ADF_DIR', get_template_directory() );
define( 'ADF_URI', get_template_directory_uri() );

require_once ADF_DIR . '/inc/template-tags.php';
require_once ADF_DIR . '/inc/elementor-compat.php';
require_once ADF_DIR . '/inc/customizer.php';
require_once ADF_DIR . '/inc/setup-screen.php';
require_once ADF_DIR . '/inc/form-entries.php';
require_once ADF_DIR . '/inc/load-more.php';

if ( is_admin() ) {
	require_once ADF_DIR . '/inc/form-entries-admin.php';
	require_once ADF_DIR . '/inc/elementor-status.php';
}

/**
 * Theme supports, menus, image sizes.
 */
function adf_setup() {
	load_theme_textdomain( 'advantage-dolda-fel', ADF_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 62,
			'width'       => 322,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Card thumbnails on the blog archive. 3:2, matching the design system.
	add_image_size( 'adf-card', 1000, 667, true );
	add_image_size( 'adf-card-sm', 600, 400, true );
	// Wide banner for the single-post hero.
	add_image_size( 'adf-wide', 1800, 1013, true );

	register_nav_menus(
		array(
			'primary' => __( 'Primary menu (header + mobile drawer)', 'advantage-dolda-fel' ),
			'footer'  => __( 'Footer — Snabblänkar column', 'advantage-dolda-fel' ),
		)
	);
}
add_action( 'after_setup_theme', 'adf_setup' );

/**
 * Content width, used by WordPress when sizing embeds.
 */
function adf_content_width() {
	$GLOBALS['content_width'] = 1200;
}
add_action( 'after_setup_theme', 'adf_content_width', 0 );

/**
 * Styles and scripts.
 */
function adf_assets() {
	// Google Fonts — Playfair Display for display type, Inter for body.
	wp_enqueue_style(
		'adf-fonts',
		'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..700;1,400..600&family=Inter:wght@400..700&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'adf-styles',
		ADF_URI . '/assets/css/styles.css',
		array( 'adf-fonts' ),
		ADF_VERSION
	);

	wp_enqueue_script(
		'adf-main',
		ADF_URI . '/assets/js/main.js',
		array(),
		ADF_VERSION,
		true
	);

	wp_localize_script(
		'adf-main',
		'adfForm',
		array(
			'endpoint' => admin_url( 'admin-ajax.php' ),
			'sending'  => __( 'Skickar…', 'advantage-dolda-fel' ),
			'invalid'  => __( 'Kontrollera de markerade fälten och försök igen.', 'advantage-dolda-fel' ),
			'failed'   => __( 'Ditt meddelande kunde inte skickas. Ring + 46 8 20 21 40 eller mejla info@advantage.se.', 'advantage-dolda-fel' ),
		)
	);

	wp_localize_script(
		'adf-main',
		'adfLoadMore',
		array(
			'endpoint'  => admin_url( 'admin-ajax.php' ),
			'loading'   => __( 'Laddar…', 'advantage-dolda-fel' ),
			'failed'    => __( 'Fler artiklar kunde inte laddas. Ladda om sidan och försök igen.', 'advantage-dolda-fel' ),
			'announced' => __( '%d av %d artiklar visas.', 'advantage-dolda-fel' ),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'adf_assets' );

/**
 * The Elementor style guard, enqueued separately at a very late priority.
 *
 * Order matters as much as specificity here. Elementor registers its kit
 * stylesheet (post-N.css) during its own enqueue pass, which runs after the
 * theme's. Enqueued alongside the other theme styles the guard came out as
 * stylesheet 2 of 15 with the kit at 9 — so it only ever won on specificity, and
 * any equal-specificity rule silently lost.
 *
 * Priority 999 puts it last in the queue, after Elementor, so the guard wins on
 * both order AND specificity. Keep it in its own callback for that reason.
 */
function adf_elementor_guard_style() {
	wp_enqueue_style(
		'adf-elementor-guard',
		ADF_URI . '/assets/css/elementor-guard.css',
		array( 'adf-styles' ),
		ADF_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'adf_elementor_guard_style', 999 );

/**
 * Preconnect to the font hosts so the display face is not the last thing to arrive.
 */
function adf_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array( 'href' => 'https://fonts.googleapis.com' );
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'adf_resource_hints', 10, 2 );

/**
 * Body classes the design system uses.
 */
function adf_body_class( $classes ) {
	// Scope for the Elementor style guard. Every guard selector is additionally
	// scoped to a class only this theme emits, so adding this always is safe.
	$classes[] = "adf-theme";

	if ( ! is_singular() || is_page_template() ) {
		$classes[] = 'adf-template';
	}
	if ( is_singular( 'post' ) ) {
		$classes[] = 'adf-single-post';
	}
	if ( is_home() || is_archive() || is_search() ) {
		$classes[] = 'adf-archive';
	}
	return $classes;
}
add_filter( 'body_class', 'adf_body_class' );

/**
 * Excerpt length and the "…" that follows it, tuned to the card design.
 */
function adf_excerpt_length() {
	return 28;
}
add_filter( 'excerpt_length', 'adf_excerpt_length' );

function adf_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'adf_excerpt_more' );

/**
 * Sidebar for the blog column, optional. Registered so the widget screen offers it;
 * the archive and single templates only render it when it holds something.
 */
function adf_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Blog sidebar', 'advantage-dolda-fel' ),
			'id'            => 'adf-blog',
			'description'   => __( 'Shown beside the article on single posts. Leave empty to run the article full width.', 'advantage-dolda-fel' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget__title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'adf_widgets_init' );
