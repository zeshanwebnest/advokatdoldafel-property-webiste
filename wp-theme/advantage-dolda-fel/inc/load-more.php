<?php
/**
 * "Load More" for the article listing, and the SEO handling that goes with it.
 *
 * The listing shows every article on one URL. Clicking Load More appends the next
 * batch over AJAX; the address bar never changes and the site never links to
 * /artiklar/page/2/, /page/3/ and so on.
 *
 * WHY THE PAGINATED URLS STILL RESOLVE
 * WordPress builds them from the rewrite rules, and a theme cannot switch that
 * off without breaking the query. So rather than pretend they are gone, they are
 * handled deliberately:
 *
 *   - nothing on the site links to them, so nothing feeds them to a crawler;
 *   - any that are already indexed, or reached directly, send
 *     `noindex, follow` — the exact directive for this case. "noindex" takes them
 *     out of the results; "follow" keeps the articles on them discoverable, so
 *     removing the pagination does not orphan older posts;
 *   - a canonical pointing at page 1 consolidates any signals they hold.
 *
 * If you would rather they not resolve at all, one filter turns them into 301s:
 *
 *     add_filter( 'adf_redirect_paged_archives', '__return_true' );
 *
 * That is the harder version. It is not the default because a redirect also
 * strands the no-JavaScript fallback below, and search engines handle
 * `noindex, follow` on paginated archives perfectly well.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Is this request a paginated view of a post listing?
 *
 * @return bool
 */
function adf_is_paged_archive() {
	if ( is_admin() || wp_doing_ajax() || is_feed() || is_singular() ) {
		return false;
	}

	return ( get_query_var( 'paged' ) > 1 ) && ( is_home() || is_archive() || is_search() );
}

/**
 * Keep paginated listings out of the index.
 *
 * Runs on wp_head at priority 1 so it lands before an SEO plugin's own output;
 * if Yoast or Rank Math is active it will emit its own robots tag as well, and
 * the strictest directive wins, which is the one we want.
 */
function adf_noindex_paged_archives() {
	if ( ! adf_is_paged_archive() ) {
		return;
	}

	echo '<meta name="robots" content="noindex, follow">' . "\n";

	// Point any signal these URLs carry at the single listing URL.
	$canonical = adf_unpaged_url();
	if ( $canonical ) {
		printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $canonical ) );
	}
}
add_action( 'wp_head', 'adf_noindex_paged_archives', 1 );

/**
 * Also send the directive as a header, which covers responses an SEO plugin
 * rewrites and anything that reads headers rather than markup.
 */
function adf_noindex_header() {
	if ( adf_is_paged_archive() && ! headers_sent() ) {
		header( 'X-Robots-Tag: noindex, follow', true );
	}
}
add_action( 'template_redirect', 'adf_noindex_header', 5 );

/**
 * The current listing URL with the /page/N/ segment removed.
 *
 * @return string
 */
function adf_unpaged_url() {
	if ( is_home() ) {
		$posts_page = (int) get_option( 'page_for_posts' );
		return $posts_page ? get_permalink( $posts_page ) : home_url( '/' );
	}

	if ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		return ( $term && ! is_wp_error( $term ) ) ? get_term_link( $term ) : '';
	}

	if ( is_author() ) {
		return get_author_posts_url( (int) get_queried_object_id() );
	}

	if ( is_search() ) {
		return get_search_link( get_search_query() );
	}

	return '';
}

/**
 * Optional: send paginated listings to page 1 instead of merely de-indexing them.
 * Off by default — see the file header for why.
 */
function adf_maybe_redirect_paged_archives() {
	if ( ! apply_filters( 'adf_redirect_paged_archives', false ) ) {
		return;
	}

	if ( ! adf_is_paged_archive() ) {
		return;
	}

	$target = adf_unpaged_url();

	if ( $target ) {
		wp_safe_redirect( $target, 301 );
		exit;
	}
}
add_action( 'template_redirect', 'adf_maybe_redirect_paged_archives', 6 );

/**
 * The query context of the listing being viewed, in a form the AJAX handler can
 * safely rebuild. Only whitelisted, typed values travel to the browser and back —
 * the handler never accepts arbitrary WP_Query arguments.
 *
 * @return array
 */
function adf_listing_context() {
	$ctx = array();

	if ( is_category() ) {
		$ctx['cat'] = (int) get_queried_object_id();
	} elseif ( is_tag() ) {
		$ctx['tag_id'] = (int) get_queried_object_id();
	} elseif ( is_author() ) {
		$ctx['author'] = (int) get_queried_object_id();
	}

	if ( is_search() ) {
		$ctx['s'] = get_search_query();
	}

	if ( is_year() || is_month() || is_day() ) {
		$ctx['year'] = (int) get_query_var( 'year' );
		if ( is_month() || is_day() ) {
			$ctx['monthnum'] = (int) get_query_var( 'monthnum' );
		}
		if ( is_day() ) {
			$ctx['day'] = (int) get_query_var( 'day' );
		}
	}

	return $ctx;
}

/**
 * Build WP_Query arguments from a context array.
 *
 * Everything is cast or sanitised, and nothing outside this whitelist is honoured,
 * so a crafted request cannot turn the endpoint into an arbitrary query.
 *
 * @param array $ctx   Context from adf_listing_context().
 * @param int   $paged Page to fetch.
 * @return array
 */
function adf_load_more_query_args( $ctx, $paged ) {
	$per_page = (int) get_option( 'posts_per_page', 10 );
	$per_page = max( 1, min( 50, $per_page ) );

	$args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => $per_page,
		'paged'               => max( 2, (int) $paged ),
		'ignore_sticky_posts' => true,
	);

	if ( ! empty( $ctx['cat'] ) ) {
		$args['cat'] = (int) $ctx['cat'];
	}
	if ( ! empty( $ctx['tag_id'] ) ) {
		$args['tag_id'] = (int) $ctx['tag_id'];
	}
	if ( ! empty( $ctx['author'] ) ) {
		$args['author'] = (int) $ctx['author'];
	}
	if ( ! empty( $ctx['s'] ) ) {
		$args['s'] = sanitize_text_field( $ctx['s'] );
	}
	if ( ! empty( $ctx['year'] ) ) {
		$args['year'] = (int) $ctx['year'];
	}
	if ( ! empty( $ctx['monthnum'] ) ) {
		$args['monthnum'] = (int) $ctx['monthnum'];
	}
	if ( ! empty( $ctx['day'] ) ) {
		$args['day'] = (int) $ctx['day'];
	}

	return $args;
}

/**
 * AJAX: return the next batch of cards.
 *
 * Registered for logged-out and logged-in visitors alike, since staff browse the
 * site too.
 */
function adf_ajax_load_more() {
	// The nonce is checked but NOT enforced, deliberately.
	//
	// This endpoint returns published post cards — the same public data the
	// archive URL already serves — so a nonce buys no real protection. What it
	// would buy is a silent failure: with full-page caching in front of the site,
	// the nonce baked into the cached HTML goes stale within a day and Load More
	// stops working for every visitor, with nothing in the logs to say why.
	//
	// The actual protection is below: the query is rebuilt from a whitelist, so a
	// crafted request cannot reach anything it could not already read.
	check_ajax_referer( 'adf_load_more', 'nonce', false );

	$paged = isset( $_POST['paged'] ) ? (int) $_POST['paged'] : 2;
	$raw   = isset( $_POST['ctx'] ) ? wp_unslash( $_POST['ctx'] ) : '{}'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- decoded and whitelisted below.
	$ctx   = json_decode( $raw, true );

	if ( ! is_array( $ctx ) ) {
		$ctx = array();
	}

	$query = new WP_Query( adf_load_more_query_args( $ctx, $paged ) );

	if ( ! $query->have_posts() ) {
		wp_send_json(
			array(
				'html'   => '',
				'more'   => false,
				'loaded' => 0,
				'total'  => 0,
			)
		);
	}

	ob_start();
	while ( $query->have_posts() ) {
		$query->the_post();
		get_template_part( 'template-parts/content', 'card' );
	}
	$html = ob_get_clean();
	wp_reset_postdata();

	$per_page = (int) $query->get( 'posts_per_page' );
	$loaded   = min( $paged * $per_page, (int) $query->found_posts );

	wp_send_json(
		array(
			'html'   => $html,
			'more'   => $paged < (int) $query->max_num_pages,
			'loaded' => $loaded,
			'total'  => (int) $query->found_posts,
		)
	);
}
add_action( 'wp_ajax_adf_load_more', 'adf_ajax_load_more' );
add_action( 'wp_ajax_nopriv_adf_load_more', 'adf_ajax_load_more' );
