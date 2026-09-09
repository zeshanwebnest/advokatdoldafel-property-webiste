<?php
/**
 * Template helpers.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * URL for a file inside the theme's assets folder.
 *
 * @param string $path Path relative to the theme root, e.g. 'assets/images/brand/advantage-logo.png'.
 * @return string
 */
function adf_asset( $path ) {
	return esc_url( ADF_URI . '/' . ltrim( $path, '/' ) );
}

/**
 * Permalink for one of the site's pages, looked up by slug.
 *
 * The static build linked pages by filename ("dolda-fel-i-hus.html"). In WordPress
 * those become real pages, so the templates ask for them by slug instead. If the
 * page has not been created yet the link falls back to the home page rather than
 * 404ing, which keeps a half-built site navigable.
 *
 * @param string $slug Page slug, e.g. 'dolda-fel-i-hus'.
 * @return string
 */
function adf_page_url( $slug ) {
	$cache = wp_cache_get( 'adf_page_urls' );
	if ( ! is_array( $cache ) ) {
		$cache = array();
	}

	if ( ! isset( $cache[ $slug ] ) ) {
		$page              = get_page_by_path( $slug );
		$cache[ $slug ]    = $page ? get_permalink( $page ) : home_url( '/' );
		wp_cache_set( 'adf_page_urls', $cache );
	}

	return esc_url( $cache[ $slug ] );
}

/**
 * URL of the blog listing — the page assigned as "Posts page" in Settings -> Reading,
 * or the site root when the front page is the blog.
 *
 * @return string
 */
function adf_blog_url() {
	$posts_page = (int) get_option( 'page_for_posts' );
	return esc_url( $posts_page ? get_permalink( $posts_page ) : home_url( '/' ) );
}

/**
 * Queue a hero image to be preloaded in <head>.
 *
 * Page templates call this BEFORE get_header() so the largest paint is requested
 * as early as possible. Skipping it costs roughly a second on the LCP of any page
 * whose hero is a full-bleed photograph.
 *
 * @param string $src    Theme-relative path to the default-size image.
 * @param string $srcset Full srcset attribute value, already theme-relative.
 * @param string $sizes  Sizes attribute value.
 */
function adf_preload_hero( $src, $srcset = '', $sizes = '100vw' ) {
	$GLOBALS['adf_hero_preload'] = array(
		'src'    => $src,
		'srcset' => $srcset,
		'sizes'  => $sizes,
	);
}

/**
 * Emit the preload link queued by adf_preload_hero(). Called from header.php.
 */
function adf_render_hero_preload() {
	if ( empty( $GLOBALS['adf_hero_preload'] ) ) {
		return;
	}

	$hero = $GLOBALS['adf_hero_preload'];

	printf(
		'<link rel="preload" as="image" fetchpriority="high" href="%s"%s imagesizes="%s">' . "\n",
		adf_asset( $hero['src'] ),
		$hero['srcset'] ? ' imagesrcset="' . esc_attr( adf_srcset( $hero['srcset'] ) ) . '"' : '',
		esc_attr( $hero['sizes'] )
	);
}

/**
 * Turn a theme-relative srcset into an absolute one.
 *
 * @param string $srcset e.g. 'assets/images/a-600.webp 600w, assets/images/a-900.webp 900w'.
 * @return string
 */
function adf_srcset( $srcset ) {
	$parts = array_map( 'trim', explode( ',', $srcset ) );
	$out   = array();

	foreach ( $parts as $part ) {
		if ( '' === $part ) {
			continue;
		}
		$bits   = preg_split( '/\s+/', $part );
		$bits[0] = ADF_URI . '/' . ltrim( $bits[0], '/' );
		$out[]   = implode( ' ', $bits );
	}

	return implode( ', ', $out );
}

/**
 * Fallback header navigation.
 *
 * Used when no menu has been assigned to the "primary" location, so the theme is
 * usable the moment it is activated — before anyone has built a menu. Assigning a
 * real WordPress menu replaces this entirely.
 */
function adf_fallback_primary_menu() {
	$items = adf_default_nav_items();

	echo '<ul>';
	foreach ( $items as $item ) {
		printf(
			'<li><a href="%s"%s>%s</a></li>',
			$item['url'],
			$item['current'] ? ' aria-current="page"' : '',
			esc_html( $item['label'] )
		);
	}
	echo '</ul>';
}

/**
 * Fallback drawer navigation — same items, the drawer's markup.
 */
function adf_fallback_mobile_menu() {
	$items = adf_default_nav_items();

	echo '<ul class="mobile-nav__list">';
	foreach ( $items as $item ) {
		printf(
			'<li><a href="%s"%s>%s</a></li>',
			$item['url'],
			$item['current'] ? ' aria-current="page"' : '',
			esc_html( $item['label'] )
		);
	}
	echo '</ul>';
}

/**
 * The site's six Swedish nav items, mirroring advokatdoldafel.se.
 *
 * @return array<int,array{label:string,url:string,current:bool}>
 */
function adf_default_nav_items() {
	$map = array(
		'hem'          => array( __( 'Hem', 'advantage-dolda-fel' ), home_url( '/' ) ),
		'hus'          => array( __( 'Dolda fel i hus', 'advantage-dolda-fel' ), adf_page_url( 'dolda-fel-i-hus' ) ),
		'bostadsratt'  => array( __( 'Dolda fel i bostadsrätt', 'advantage-dolda-fel' ), adf_page_url( 'dolda-fel-i-bostadsratt' ) ),
		'tvister'      => array( __( 'Dolda fel tvister', 'advantage-dolda-fel' ), adf_page_url( 'dolda-fel-tvister' ) ),
		'artiklar'     => array( __( 'Artiklar', 'advantage-dolda-fel' ), adf_blog_url() ),
		'kontakt'      => array( __( 'Kontakta oss', 'advantage-dolda-fel' ), adf_page_url( 'kontakta-oss' ) ),
	);

	$items = array();

	foreach ( $map as $key => $item ) {
		$items[] = array(
			"label"   => $item[0],
			"url"     => $item[1],
			"current" => adf_nav_is_current( $key ),
		);
	}

	return $items;
}

/**
 * Estimated reading time for the current post, in whole minutes.
 *
 * @return int
 */
function adf_reading_time() {
	$words = str_word_count( wp_strip_all_tags( get_the_content() ) );
	return max( 1, (int) ceil( $words / 200 ) );
}

/**
 * The post's first category, for the eyebrow on cards and the single-post hero.
 *
 * @return string
 */
function adf_primary_category() {
	$terms = get_the_category();
	return ( ! empty( $terms ) && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
}

/**
 * The brand link — a Custom Logo when one is set, otherwise the bundled logo file.
 *
 * @param string $class Class list for the <a>.
 */
function adf_brand_link( $class = 'brand' ) {
	$home  = esc_url( home_url( '/' ) );
	$label = esc_attr__( 'Advantage Advokatbyrå — startsidan', 'advantage-dolda-fel' );

	printf( '<a class="%s" href="%s" aria-label="%s">', esc_attr( $class ), $home, $label );

	// A Custom Logo set in the Customizer wins, because that is the WordPress-native
	// way to change it. Worth knowing: a site migrating from another theme often
	// already has one set, and if that stored attachment is the LIGHT variant of
	// the artwork, the header shows a crisp "A" beside a ghosted wordmark on the
	// white ground. Appearance -> Advantage Setup reports which one is in use.
	if ( has_custom_logo() ) {
		$logo_id = (int) get_theme_mod( 'custom_logo' );
		echo wp_get_attachment_image(
			$logo_id,
			'full',
			false,
			array(
				'alt'           => get_bloginfo( 'name', 'display' ),
				'class'         => 'brand__img',
				'fetchpriority' => 'high',
				'decoding'      => 'sync',
			)
		);
	} else {
		// The bundled master: 322x62 PNG, dark wordmark, transparent background.
		printf(
			'<img class="brand__img" src="%s" alt="%s" width="322" height="62" fetchpriority="high" decoding="sync">',
			adf_asset( 'assets/images/brand/advantage-logo.png' ),
			esc_attr( get_bloginfo( 'name', 'display' ) )
		);
	}

	echo '</a>';
}

/**
 * Fallback footer "Snabblänkar" column, used until a menu is assigned to the
 * "footer" location.
 */
function adf_fallback_footer_menu() {
	$items = array(
		array( __( 'Hem', 'advantage-dolda-fel' ), home_url( '/' ) ),
		array( __( 'Dolda fel i hus', 'advantage-dolda-fel' ), adf_page_url( 'dolda-fel-i-hus' ) ),
		array( __( 'Dolda fel i bostadsrätt', 'advantage-dolda-fel' ), adf_page_url( 'dolda-fel-i-bostadsratt' ) ),
		array( __( 'Allmänna villkor', 'advantage-dolda-fel' ), adf_page_url( 'allmanna-villkor' ) ),
		array( __( 'Konsumenttvistnämnden', 'advantage-dolda-fel' ), adf_page_url( 'konsumenttvistnamnden' ) ),
		array( __( 'FAQ', 'advantage-dolda-fel' ), adf_page_url( 'faq' ) ),
		array( __( 'Kontakta oss', 'advantage-dolda-fel' ), adf_page_url( 'kontakta-oss' ) ),
	);

	echo '<ul class="footer-links">';
	foreach ( $items as $item ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( $item[1] ), esc_html( $item[0] ) );
	}
	echo '</ul>';
}

/**
 * Is this fallback nav item the page currently being viewed?
 *
 * Only used by the fallback menus. A real WordPress menu marks the current item
 * itself, so this never runs once menus are assigned.
 *
 * @param string $key One of: hem, hus, bostadsratt, tvister, artiklar, kontakt.
 * @return bool
 */
function adf_nav_is_current( $key ) {
	$slugs = array(
		'hus'         => 'dolda-fel-i-hus',
		'bostadsratt' => 'dolda-fel-i-bostadsratt',
		'tvister'     => 'dolda-fel-tvister',
		'kontakt'     => 'kontakta-oss',
	);

	if ( 'hem' === $key ) {
		return is_front_page();
	}

	if ( 'artiklar' === $key ) {
		return is_home() || is_singular( 'post' ) || is_category() || is_tag();
	}

	return isset( $slugs[ $key ] ) ? is_page( $slugs[ $key ] ) : false;
}

/**
 * The full URL of the request being served.
 *
 * Built from the server variables rather than from a permalink, so it is right on
 * paginated archives, search results and query-string URLs — which is exactly
 * where "where did they submit from?" gets interesting.
 *
 * @return string
 */
function adf_current_url() {
	$host = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
	$uri  = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '/';

	if ( ! $host ) {
		return home_url( '/' );
	}

	return esc_url_raw( ( is_ssl() ? 'https://' : 'http://' ) . $host . $uri );
}

/**
 * A human label for the page being viewed, stored alongside the entry so the
 * dashboard can show "Dolda fel i hus" instead of a bare URL.
 *
 * @return string
 */
function adf_current_page_title() {
	if ( is_front_page() ) {
		return get_bloginfo( 'name' );
	}

	if ( is_singular() ) {
		return wp_strip_all_tags( get_the_title() );
	}

	if ( is_home() ) {
		$posts_page = (int) get_option( 'page_for_posts' );
		return $posts_page ? wp_strip_all_tags( get_the_title( $posts_page ) ) : __( 'Artiklar', 'advantage-dolda-fel' );
	}

	if ( is_archive() ) {
		return wp_strip_all_tags( get_the_archive_title() );
	}

	if ( is_search() ) {
		return __( 'Sökresultat', 'advantage-dolda-fel' );
	}

	if ( is_404() ) {
		return '404';
	}

	return get_bloginfo( 'name' );
}

/**
 * [advantage_form] — the global form, usable anywhere.
 *
 * Drop it into a page, a post, an Elementor Shortcode widget or a text widget and
 * it renders the same component the templates use, wired to the same handler.
 *
 * Attributes:
 *   (no variant attribute: there is one form with one field set, everywhere)
 *   source   free label stored with the entry, e.g. "sidebar"
 *   title    optional heading rendered above the form
 *   card     'yes' (default) wraps it in the dark .form-card; 'no' renders bare
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function adf_form_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'source' => 'shortcode',
			'title'  => '',
			'card'   => 'yes',
		),
		$atts,
		'advantage_form'
	);

	ob_start();

	$wrap = ( 'no' !== $atts['card'] );

	if ( $wrap ) {
		echo '<div class="form-card">';
	}

	if ( $atts['title'] ) {
		echo '<h2 class="h3">' . esc_html( $atts['title'] ) . '</h2>';
	}

	get_template_part(
		'template-parts/form',
		'contact',
		array( 'source' => sanitize_text_field( $atts['source'] ) )
	);

	if ( $wrap ) {
		echo '</div>';
	}

	return ob_get_clean();
}
add_shortcode( 'advantage_form', 'adf_form_shortcode' );

/**
 * [advantage_latest_posts] — the latest-articles section, usable anywhere.
 *
 * Same component the home page uses, so a copy dropped into an Elementor
 * Shortcode widget or a page stays in step with it.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function adf_latest_posts_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'count'  => 3,
			'title'  => '',
			// null, not "", so that lead="" is distinguishable from lead being
			// left out entirely. Omitted means "use the default line"; explicitly
			// empty means "no lead paragraph at all".
			'lead'   => null,
			'ground' => 'plain',
			'button' => 'yes',
		),
		$atts,
		'advantage_latest_posts'
	);

	$args = array(
		'count'  => (int) $atts['count'],
		'ground' => $atts['ground'],
		'button' => $atts['button'],
	);

	// Only override the defaults the template part already carries when the
	// shortcode actually supplied something.
	if ( '' !== $atts['title'] ) {
		$args['title'] = sanitize_text_field( $atts['title'] );
	}
	if ( null !== $atts['lead'] ) {
		$args['lead'] = sanitize_text_field( $atts['lead'] );
	}

	ob_start();
	get_template_part( 'template-parts/section', 'latest-posts', $args );
	return ob_get_clean();
}
add_shortcode( 'advantage_latest_posts', 'adf_latest_posts_shortcode' );
