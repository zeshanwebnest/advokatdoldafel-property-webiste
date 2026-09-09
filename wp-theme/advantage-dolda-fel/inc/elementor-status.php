<?php
/**
 * "Which design is actually rendering?" — the Elementor Theme Builder panel on
 * Appearance -> Advantage Setup.
 *
 * The theme hands each location to Elementor whenever a published Theme Builder
 * template matches (see inc/elementor-compat.php). That is the intended dual-mode
 * behaviour, but from the front end the two are indistinguishable: a post that
 * renders through an Elementor Single template simply does not look like the
 * theme, and nothing on screen says why.
 *
 * This panel says why. It lists every published Theme Builder template, what it
 * overrides, and what to do about it — so "the single post looks wrong" becomes a
 * labelled row instead of a DOM inspection.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elementor template types mapped to the theme location they take over, and to
 * the theme file they replace.
 *
 * @return array
 */
function adf_elementor_type_map() {
	return array(
		'header'          => array( 'header', 'header.php + template-parts/site-header.php' ),
		'footer'          => array( 'footer', 'footer.php + template-parts/site-footer.php' ),
		'single'          => array( 'single', 'single.php' ),
		'single-post'     => array( 'single', 'single.php' ),
		'single-page'     => array( 'single', 'single.php / page.php' ),
		'archive'         => array( 'archive', 'home.php + archive.php' ),
		'search-results'  => array( 'archive', 'search.php' ),
		'error-404'       => array( 'single', '404.php' ),
	);
}

/**
 * Published Theme Builder templates, with their type and display conditions.
 *
 * Read from post meta rather than from Elementor's internal classes, so it keeps
 * working across Elementor versions.
 *
 * @return array
 */
function adf_elementor_templates() {
	if ( ! post_type_exists( 'elementor_library' ) ) {
		return array();
	}

	$posts = get_posts(
		array(
			'post_type'      => 'elementor_library',
			'post_status'    => 'publish',
			'posts_per_page' => 50,
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- admin screen, runs once.
				array(
					'key'     => '_elementor_template_type',
					'compare' => 'EXISTS',
				),
			),
		)
	);

	$map  = adf_elementor_type_map();
	$rows = array();

	foreach ( $posts as $p ) {
		$type = get_post_meta( $p->ID, '_elementor_template_type', true );

		if ( ! isset( $map[ $type ] ) ) {
			continue; // Section, page, popup, loop item — not a theme location.
		}

		$conditions = get_post_meta( $p->ID, '_elementor_conditions', true );
		$conditions = is_array( $conditions ) ? $conditions : array();

		$rows[] = array(
			'id'         => $p->ID,
			'title'      => $p->post_title ? $p->post_title : sprintf( '#%d', $p->ID ),
			'type'       => $type,
			'location'   => $map[ $type ][0],
			'replaces'   => $map[ $type ][1],
			'conditions' => $conditions,
		);
	}

	return $rows;
}

/**
 * Turn Elementor's condition strings into something readable.
 *
 * They look like "include/singular/post" or "exclude/singular/page/12".
 *
 * @param array $conditions Raw conditions.
 * @return string
 */
function adf_describe_conditions( $conditions ) {
	if ( empty( $conditions ) ) {
		return __( 'no conditions set — it will not render anywhere', 'advantage-dolda-fel' );
	}

	$out = array();

	foreach ( $conditions as $c ) {
		$parts = explode( '/', $c );
		$mode  = array_shift( $parts );
		$label = implode( ' → ', array_filter( $parts ) );
		$out[] = ( 'exclude' === $mode ? '− ' : '+ ' ) . ( $label ? $label : __( 'entire site', 'advantage-dolda-fel' ) );
	}

	return implode( ', ', $out );
}

/**
 * Render the panel. Called from the setup screen.
 */
function adf_elementor_status_panel() {
	if ( ! adf_elementor_theme_builder_active() ) {
		?>
		<h2><?php esc_html_e( 'Elementor Theme Builder', 'advantage-dolda-fel' ); ?></h2>
		<p style="max-width:62em">
			<?php esc_html_e( 'Not detected. Every location renders with the theme design. If Elementor Pro is installed later, the handover points are already in place — no code change needed.', 'advantage-dolda-fel' ); ?>
		</p>
		<?php
		return;
	}

	$rows = adf_elementor_templates();
	?>
	<h2><?php esc_html_e( 'Which design is rendering?', 'advantage-dolda-fel' ); ?></h2>

	<p style="max-width:62em">
		<?php esc_html_e( 'A published Theme Builder template whose conditions match a page renders INSTEAD of this theme. That is by design and it is how you switch between the two — but it is invisible from the front end, so anything listed below is worth knowing about.', 'advantage-dolda-fel' ); ?>
	</p>

	<?php if ( empty( $rows ) ) : ?>
		<p style="max-width:62em">
			<span style="color:#1a7f37">&#10003;</span>
			<?php esc_html_e( 'No Theme Builder templates are published. Every location renders with the theme design.', 'advantage-dolda-fel' ); ?>
		</p>
		<?php
		return;
	endif;
	?>

	<table class="widefat striped" style="max-width:78em">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Elementor template', 'advantage-dolda-fel' ); ?></th>
				<th><?php esc_html_e( 'Overrides', 'advantage-dolda-fel' ); ?></th>
				<th><?php esc_html_e( 'Shown on', 'advantage-dolda-fel' ); ?></th>
				<th><?php esc_html_e( 'Action', 'advantage-dolda-fel' ); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ( $rows as $row ) : ?>
			<tr>
				<td>
					<strong><?php echo esc_html( $row['title'] ); ?></strong>
					<br><span class="description"><?php echo esc_html( $row['type'] ); ?> &middot; #<?php echo (int) $row['id']; ?></span>
				</td>
				<td>
					<span style="color:#bf5c00">&#9888;</span>
					<code><?php echo esc_html( $row['replaces'] ); ?></code>
					<br><span class="description">
						<?php
						printf(
							/* translators: %s: theme location name */
							esc_html__( 'the theme %s location does not run while this is published', 'advantage-dolda-fel' ),
							'<strong>' . esc_html( $row['location'] ) . '</strong>'
						);
						?>
					</span>
				</td>
				<td><?php echo esc_html( adf_describe_conditions( $row['conditions'] ) ); ?></td>
				<td>
					<a href="<?php echo esc_url( get_edit_post_link( $row['id'] ) ); ?>"><?php esc_html_e( 'Edit', 'advantage-dolda-fel' ); ?></a><br>
					<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=elementor_library&tabs_group=theme' ) ); ?>"><?php esc_html_e( 'Theme Builder', 'advantage-dolda-fel' ); ?></a>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<h3><?php esc_html_e( 'To use the theme design instead', 'advantage-dolda-fel' ); ?></h3>
	<p style="max-width:62em">
		<?php esc_html_e( 'Set the template above to Draft, or narrow its display conditions so it no longer matches. The theme takes that location back on the next page load. No content is touched either way — posts and pages are not read or written by this.', 'advantage-dolda-fel' ); ?>
	</p>

	<h3><?php esc_html_e( 'To keep the Elementor template and still get the site form', 'advantage-dolda-fel' ); ?></h3>
	<p style="max-width:62em">
		<?php esc_html_e( 'Add a Shortcode widget where you want the form and put this in it:', 'advantage-dolda-fel' ); ?>
		<br><code style="display:inline-block;margin-top:6px;padding:6px 10px;background:#f6f7f7">[advantage_form]</code>
		<br><span class="description">
			<?php esc_html_e( 'It renders the same component as every other form on the site, posts to the same handler, and saves to Form Entries with the same tracking. Update the component once and every copy changes, including this one.', 'advantage-dolda-fel' ); ?>
		</span>
	</p>
	<?php
}
