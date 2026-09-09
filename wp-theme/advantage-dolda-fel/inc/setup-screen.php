<?php
/**
 * Appearance -> Advantage Setup.
 *
 * A one-screen checklist with a button that creates any missing pages and assigns
 * the right template to each. It exists so that going live does not depend on
 * anyone remembering eight slug/template pairs.
 *
 * What it does, and only when the button is pressed:
 *   - creates a page if no page with that slug exists;
 *   - sets _wp_page_template on that page to the matching theme template.
 *
 * What it never does:
 *   - touch a post. The existing articles are not read, edited or re-saved by
 *     anything in this file;
 *   - overwrite the content of a page that already exists. An existing page keeps
 *     its content; only a wrong template assignment is corrected.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The site's pages: slug => array( title, template file, note ).
 *
 * @return array
 */
function adf_page_map() {
	return array(
		'hem'                     => array( 'Hem', 'page-templates/template-hem.php', __( 'Set this as the front page in Settings > Reading.', 'advantage-dolda-fel' ) ),
		'dolda-fel-i-hus'         => array( 'Dolda fel i hus', 'page-templates/template-dolda-fel-i-hus.php', '' ),
		'dolda-fel-i-bostadsratt' => array( 'Dolda fel i bostadsrätt', 'page-templates/template-dolda-fel-i-bostadsratt.php', '' ),
		'dolda-fel-tvister'       => array( 'Dolda fel tvister', 'page-templates/template-dolda-fel-tvister.php', '' ),
		'allmanna-villkor'        => array( 'Allmänna villkor', 'page-templates/template-allmanna-villkor.php', '' ),
		'konsumenttvistnamnden'   => array( 'Konsumenttvistnämnden', 'page-templates/template-konsumenttvistnamnden.php', '' ),
		'faq'                     => array( 'FAQ', 'page-templates/template-faq.php', '' ),
		'kontakta-oss'            => array( 'Kontakta oss', 'page-templates/template-kontakta-oss.php', '' ),
	);
}

/**
 * Add the screen under Appearance.
 */
function adf_setup_menu() {
	add_theme_page(
		__( 'Advantage Setup', 'advantage-dolda-fel' ),
		__( 'Advantage Setup', 'advantage-dolda-fel' ),
		'edit_theme_options',
		'adf-setup',
		'adf_setup_screen'
	);
}
add_action( 'admin_menu', 'adf_setup_menu' );

/**
 * Create missing pages and fix template assignments.
 *
 * @return array
 */
function adf_run_page_setup() {
	$created  = 0;
	$assigned = 0;

	foreach ( adf_page_map() as $slug => $info ) {
		$title    = $info[0];
		$template = $info[1];

		$page = get_page_by_path( $slug );

		if ( ! $page ) {
			$page_id = wp_insert_post(
				array(
					'post_type'    => 'page',
					'post_name'    => $slug,
					'post_title'   => $title,
					'post_status'  => 'publish',
					'post_content' => '',
				)
			);

			if ( is_wp_error( $page_id ) || ! $page_id ) {
				continue;
			}

			$created++;
		} else {
			$page_id = $page->ID;
		}

		if ( get_page_template_slug( $page_id ) !== $template ) {
			update_post_meta( $page_id, '_wp_page_template', $template );
			$assigned++;
		}
	}

	return array(
		'created'  => $created,
		'assigned' => $assigned,
	);
}

/**
 * Render the screen.
 */
function adf_setup_screen() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'advantage-dolda-fel' ) );
	}

	$notice = '';

	if ( isset( $_POST['adf_setup_nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['adf_setup_nonce'] ) ), 'adf_setup' ) ) {
		$result = adf_run_page_setup();
		$notice = sprintf(
			/* translators: 1: pages created, 2: templates assigned */
			esc_html__( 'Done. %1$d page(s) created, %2$d template assignment(s) set. No posts were touched.', 'advantage-dolda-fel' ),
			$result['created'],
			$result['assigned']
		);
	}

	$posts_page    = (int) get_option( 'page_for_posts' );
	$front_page    = (int) get_option( 'page_on_front' );
	$show_on_front = get_option( 'show_on_front' );
	$counts        = wp_count_posts( 'post' );
	$post_count    = isset( $counts->publish ) ? (int) $counts->publish : 0;
	$elementor     = adf_elementor_theme_builder_active();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Advantage - theme setup', 'advantage-dolda-fel' ); ?></h1>

		<?php if ( $notice ) : ?>
			<div class="notice notice-success"><p><?php echo esc_html( $notice ); ?></p></div>
		<?php endif; ?>

		<p style="max-width:60em">
			<?php esc_html_e( 'This screen creates the eight site pages and assigns each one its template. It never reads or writes posts, so existing articles are unaffected. Running it twice is safe: a page that already exists keeps its content, and only a wrong template assignment is corrected.', 'advantage-dolda-fel' ); ?>
		</p>

		<form method="post">
			<?php wp_nonce_field( 'adf_setup', 'adf_setup_nonce' ); ?>
			<p>
				<button type="submit" class="button button-primary">
					<?php esc_html_e( 'Create missing pages and assign templates', 'advantage-dolda-fel' ); ?>
				</button>
			</p>
		</form>

		<h2><?php esc_html_e( 'Pages', 'advantage-dolda-fel' ); ?></h2>
		<table class="widefat striped" style="max-width:70em">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Page', 'advantage-dolda-fel' ); ?></th>
					<th><?php esc_html_e( 'Slug', 'advantage-dolda-fel' ); ?></th>
					<th><?php esc_html_e( 'Template', 'advantage-dolda-fel' ); ?></th>
					<th><?php esc_html_e( 'Status', 'advantage-dolda-fel' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ( adf_page_map() as $slug => $info ) : ?>
				<?php
				$title    = $info[0];
				$template = $info[1];
				$note     = $info[2];
				$page     = get_page_by_path( $slug );
				$exists   = (bool) $page;
				$ok       = $exists && get_page_template_slug( $page->ID ) === $template;
				?>
				<tr>
					<td>
						<strong><?php echo esc_html( $title ); ?></strong>
						<?php if ( $note ) : ?>
							<br><span class="description"><?php echo esc_html( $note ); ?></span>
						<?php endif; ?>
					</td>
					<td><code><?php echo esc_html( $slug ); ?></code></td>
					<td><code><?php echo esc_html( basename( $template ) ); ?></code></td>
					<td>
						<?php if ( $ok ) : ?>
							<span style="color:#1a7f37">&#10003; <?php esc_html_e( 'assigned', 'advantage-dolda-fel' ); ?></span>
							&mdash; <a href="<?php echo esc_url( get_permalink( $page ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'view', 'advantage-dolda-fel' ); ?></a>
						<?php elseif ( $exists ) : ?>
							<span style="color:#bf5c00">&#9888; <?php esc_html_e( 'page exists, template not assigned', 'advantage-dolda-fel' ); ?></span>
						<?php else : ?>
							<span style="color:#8a2424">&#10007; <?php esc_html_e( 'page missing', 'advantage-dolda-fel' ); ?></span>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		<h2><?php esc_html_e( 'Blog', 'advantage-dolda-fel' ); ?></h2>
		<table class="widefat striped" style="max-width:70em">
			<tbody>
				<tr>
					<td style="width:22em"><strong><?php esc_html_e( 'Published posts', 'advantage-dolda-fel' ); ?></strong></td>
					<td>
						<?php echo esc_html( number_format_i18n( $post_count ) ); ?>
						&mdash; <?php esc_html_e( 'untouched by this theme; the new design applies to all of them automatically.', 'advantage-dolda-fel' ); ?>
					</td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e( 'Front page', 'advantage-dolda-fel' ); ?></strong></td>
					<td>
						<?php if ( 'page' === $show_on_front && $front_page ) : ?>
							<?php echo esc_html( get_the_title( $front_page ) ); ?>
						<?php else : ?>
							<span style="color:#bf5c00"><?php esc_html_e( 'Not set to a static page. Settings > Reading > "A static page".', 'advantage-dolda-fel' ); ?></span>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e( 'Posts page', 'advantage-dolda-fel' ); ?></strong></td>
					<td>
						<?php if ( $posts_page ) : ?>
							<?php echo esc_html( get_the_title( $posts_page ) ); ?>
							&mdash; <a href="<?php echo esc_url( get_permalink( $posts_page ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'view', 'advantage-dolda-fel' ); ?></a>
							<br><span class="description"><?php esc_html_e( 'Rendered by home.php. Leave its template set to "Default".', 'advantage-dolda-fel' ); ?></span>
						<?php else : ?>
							<span style="color:#bf5c00"><?php esc_html_e( 'Not set. Settings > Reading > "Posts page". Until it is set, the blog listing has no URL of its own.', 'advantage-dolda-fel' ); ?></span>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e( 'Header logo', 'advantage-dolda-fel' ); ?></strong></td>
					<td>
						<?php
						if ( has_custom_logo() ) {
							$adf_logo_id  = (int) get_theme_mod( 'custom_logo' );
							$adf_logo_url = wp_get_attachment_image_url( $adf_logo_id, 'full' );
							$adf_logo_file = $adf_logo_url ? basename( wp_parse_url( $adf_logo_url, PHP_URL_PATH ) ) : '';
							?>
							<span style="color:#bf5c00">&#9888;</span>
							<?php esc_html_e( 'A Custom Logo from the media library is overriding the bundled artwork:', 'advantage-dolda-fel' ); ?>
							<code><?php echo esc_html( $adf_logo_file ); ?></code>
							<?php if ( $adf_logo_url ) : ?>
								<div style="margin:8px 0;padding:8px 12px;background:#fff;border:1px solid #ddd;display:inline-block">
									<img src="<?php echo esc_url( $adf_logo_url ); ?>" alt="" style="height:34px;width:auto;display:block">
								</div>
								<div style="margin:0 0 8px;padding:8px 12px;background:#1B2A32;display:inline-block">
									<img src="<?php echo esc_url( $adf_logo_url ); ?>" alt="" style="height:34px;width:auto;display:block">
								</div>
							<?php endif; ?>
							<br>
							<span class="description">
								<?php esc_html_e( 'The two swatches above show it on the header ground (white) and on the footer ground (slate). If the wordmark disappears on white, that attachment is the light variant — remove it below and the theme falls back to the correct bundled logo.', 'advantage-dolda-fel' ); ?>
							</span>
							<br><a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=title_tagline' ) ); ?>"><?php esc_html_e( 'Change or remove it in the Customizer', 'advantage-dolda-fel' ); ?></a>
							<?php
						} else {
							?>
							<span style="color:#1a7f37">&#10003;</span>
							<?php esc_html_e( 'Using the bundled artwork', 'advantage-dolda-fel' ); ?>
							<code>assets/images/brand/advantage-logo.png</code>
							<?php esc_html_e( '(322x62, dark wordmark, transparent background).', 'advantage-dolda-fel' ); ?>
							<div style="margin:8px 0;padding:8px 12px;background:#fff;border:1px solid #ddd;display:inline-block">
								<img src="<?php echo adf_asset( 'assets/images/brand/advantage-logo.png' ); ?>" alt="" style="height:34px;width:auto;display:block">
							</div>
							<div style="margin:0 0 0 6px;padding:8px 12px;background:#1B2A32;display:inline-block">
								<img src="<?php echo adf_asset( 'assets/images/brand/advantage-logo.png' ); ?>" alt="" style="height:34px;width:auto;display:block">
							</div>
							<?php
						}
						?>
					</td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e( 'Elementor Theme Builder', 'advantage-dolda-fel' ); ?></strong></td>
					<td>
						<?php if ( $elementor ) : ?>
							<?php esc_html_e( 'Active. Any published Archive or Single template whose display conditions match will render instead of this theme design. Unpublish it, or narrow its conditions, to hand that location back to the theme.', 'advantage-dolda-fel' ); ?>
							<br><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=elementor_library&tabs_group=theme' ) ); ?>"><?php esc_html_e( 'Open Theme Builder', 'advantage-dolda-fel' ); ?></a>
						<?php else : ?>
							<?php esc_html_e( 'Not detected. The theme renders every location itself. Installing Elementor Pro later needs no change here - the handover points are already in place.', 'advantage-dolda-fel' ); ?>
						<?php endif; ?>
					</td>
				</tr>
			</tbody>
		</table>

		<?php adf_elementor_status_panel(); ?>

		<h2><?php esc_html_e( 'Menus', 'advantage-dolda-fel' ); ?></h2>
		<p style="max-width:60em">
			<?php
			printf(
				/* translators: %s: link to the menus screen */
				esc_html__( 'The header and footer fall back to the six built-in Swedish links until a menu is assigned, so the site is usable immediately. Assign real menus at %s to take control of them.', 'advantage-dolda-fel' ),
				'<a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Appearance > Menus', 'advantage-dolda-fel' ) . '</a>'
			);
			?>
		</p>
	</div>
	<?php
}
