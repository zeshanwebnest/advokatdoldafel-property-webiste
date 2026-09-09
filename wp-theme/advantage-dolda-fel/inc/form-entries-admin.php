<?php
/**
 * Form Entries — the dashboard side.
 *
 * Columns, filters, the read-only detail view, CSV export and the recipients
 * setting. Entries are records of what a visitor sent, so nothing here lets an
 * admin edit the submitted values; they can be read, exported and deleted.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -------------------------------------------------------------------------
 * List table
 * ---------------------------------------------------------------------- */

/**
 * Columns for the entries list.
 *
 * @param array $columns Default columns.
 * @return array
 */
function adf_entry_columns( $columns ) {
	return array(
		'cb'            => isset( $columns['cb'] ) ? $columns['cb'] : '',
		'adf_from'      => __( 'From', 'advantage-dolda-fel' ),
		'adf_contact'   => __( 'Contact', 'advantage-dolda-fel' ),
		'adf_message'   => __( 'Message', 'advantage-dolda-fel' ),
		'adf_submitted' => __( 'Submitted from', 'advantage-dolda-fel' ),
		'adf_origin'    => __( 'Came from', 'advantage-dolda-fel' ),
		'adf_mail'      => __( 'E-mail', 'advantage-dolda-fel' ),
		'date'          => __( 'Received', 'advantage-dolda-fel' ),
	);
}
add_filter( 'manage_' . ADF_ENTRY_CPT . '_posts_columns', 'adf_entry_columns' );

/**
 * Render one cell.
 *
 * @param string $column  Column key.
 * @param int    $post_id Entry ID.
 */
function adf_entry_column( $column, $post_id ) {
	switch ( $column ) {
		case 'adf_from':
			$name = get_post_meta( $post_id, 'adf_name', true );
			$unread = '0' === get_post_meta( $post_id, '_adf_read', true );
			printf(
				'<strong><a href="%s">%s</a></strong>%s',
				esc_url( get_edit_post_link( $post_id ) ),
				esc_html( $name ? $name : __( '(no name given)', 'advantage-dolda-fel' ) ),
				$unread ? ' <span style="background:#B98B54;color:#fff;font-size:10px;padding:1px 6px;border-radius:9px;vertical-align:middle">' . esc_html__( 'NEW', 'advantage-dolda-fel' ) . '</span>' : ''
			);
			break;

		case 'adf_contact':
			$phone = get_post_meta( $post_id, 'adf_phone', true );
			$email = get_post_meta( $post_id, 'adf_email', true );
			$out   = array();
			if ( $phone ) {
				$out[] = '<a href="tel:' . esc_attr( preg_replace( '/\s+/', '', $phone ) ) . '">' . esc_html( $phone ) . '</a>';
			}
			if ( $email ) {
				$out[] = '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
			}
			echo $out ? wp_kses_post( implode( '<br>', $out ) ) : '&mdash;';
			break;

		case 'adf_message':
			$subject = get_post_meta( $post_id, 'adf_subject', true );
			$message = get_post_meta( $post_id, 'adf_message', true );
			if ( $subject ) {
				echo '<strong>' . esc_html( $subject ) . '</strong><br>';
			}
			echo esc_html( wp_trim_words( $message, 16, '…' ) );
			break;

		case 'adf_submitted':
			$url   = get_post_meta( $post_id, '_adf_submitted_url', true );
			$title = get_post_meta( $post_id, '_adf_submitted_title', true );
			if ( ! $url ) {
				echo '&mdash;';
				break;
			}
			printf(
				'<a href="%s" target="_blank" rel="noopener">%s</a>',
				esc_url( $url ),
				esc_html( $title ? $title : adf_shorten_url( $url ) )
			);
			break;

		case 'adf_origin':
			$landing  = get_post_meta( $post_id, '_adf_landing_url', true );
			$referrer = get_post_meta( $post_id, '_adf_referrer', true );
			$utm      = get_post_meta( $post_id, '_adf_utm', true );
			$bits     = array();
			if ( $referrer ) {
				$bits[] = '<span title="' . esc_attr( $referrer ) . '">' . esc_html( adf_host_of( $referrer ) ) . '</span>';
			} else {
				$bits[] = '<span style="color:#777">' . esc_html__( 'direct', 'advantage-dolda-fel' ) . '</span>';
			}
			if ( $landing ) {
				$bits[] = '<span style="color:#777;font-size:11px">' . esc_html( adf_shorten_url( $landing ) ) . '</span>';
			}
			if ( $utm ) {
				$bits[] = '<span style="color:#8A6538;font-size:11px">' . esc_html( $utm ) . '</span>';
			}
			echo wp_kses_post( implode( '<br>', $bits ) );
			break;

		case 'adf_mail':
			$status = get_post_meta( $post_id, '_adf_mail_status', true );
			if ( 'sent' === $status ) {
				echo '<span style="color:#1a7f37">&#10003; ' . esc_html__( 'sent', 'advantage-dolda-fel' ) . '</span>';
			} elseif ( 'failed' === $status ) {
				$err = get_post_meta( $post_id, '_adf_mail_error', true );
				echo '<span style="color:#8a2424" title="' . esc_attr( $err ) . '">&#10007; ' . esc_html__( 'failed', 'advantage-dolda-fel' ) . '</span>';
			} else {
				echo '&mdash;';
			}
			break;
	}
}
add_action( 'manage_' . ADF_ENTRY_CPT . '_posts_custom_column', 'adf_entry_column', 10, 2 );

/**
 * Host part of a URL, for the compact "came from" column.
 *
 * @param string $url URL.
 * @return string
 */
function adf_host_of( $url ) {
	$host = wp_parse_url( $url, PHP_URL_HOST );
	return $host ? preg_replace( '/^www\./', '', $host ) : $url;
}

/**
 * Path part of a URL, trimmed for display.
 *
 * @param string $url URL.
 * @return string
 */
function adf_shorten_url( $url ) {
	$path = wp_parse_url( $url, PHP_URL_PATH );
	$path = $path ? $path : '/';
	$q    = wp_parse_url( $url, PHP_URL_QUERY );
	return $q ? $path . '?' . $q : $path;
}

/**
 * Make the Received column sortable and default the list to newest first.
 *
 * @param array $columns Sortable columns.
 * @return array
 */
function adf_entry_sortable( $columns ) {
	$columns['date'] = 'date';
	return $columns;
}
add_filter( 'manage_edit-' . ADF_ENTRY_CPT . '_sortable_columns', 'adf_entry_sortable' );

/**
 * Remove Quick Edit — an entry is a record, not editable content.
 *
 * @param array   $actions Row actions.
 * @param WP_Post $post    Post.
 * @return array
 */
function adf_entry_row_actions( $actions, $post ) {
	if ( ADF_ENTRY_CPT !== $post->post_type ) {
		return $actions;
	}
	unset( $actions['inline hide-if-no-js'] );
	$actions['view_entry'] = sprintf(
		'<a href="%s">%s</a>',
		esc_url( get_edit_post_link( $post->ID ) ),
		esc_html__( 'View', 'advantage-dolda-fel' )
	);
	return $actions;
}
add_filter( 'post_row_actions', 'adf_entry_row_actions', 10, 2 );

/* -------------------------------------------------------------------------
 * Detail view
 * ---------------------------------------------------------------------- */

/**
 * Replace the editor with a read-only entry view.
 */
function adf_entry_meta_boxes() {
	remove_post_type_support( ADF_ENTRY_CPT, 'editor' );

	add_meta_box(
		'adf_entry_detail',
		__( 'Submission', 'advantage-dolda-fel' ),
		'adf_entry_detail_box',
		ADF_ENTRY_CPT,
		'normal',
		'high'
	);

	add_meta_box(
		'adf_entry_tracking',
		__( 'Tracking', 'advantage-dolda-fel' ),
		'adf_entry_tracking_box',
		ADF_ENTRY_CPT,
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes_' . ADF_ENTRY_CPT, 'adf_entry_meta_boxes' );

/**
 * The submitted values.
 *
 * @param WP_Post $post Entry.
 */
function adf_entry_detail_box( $post ) {
	echo '<table class="widefat striped" style="border:0">';
	foreach ( adf_entry_fields() as $key => $label ) {
		$value = get_post_meta( $post->ID, $key, true );
		printf(
			'<tr><th style="width:12em;text-align:left">%s</th><td>%s</td></tr>',
			esc_html( $label ),
			'' === $value ? '<span style="color:#777">&mdash;</span>' : nl2br( esc_html( $value ) )
		);
	}
	echo '</table>';

	$email = get_post_meta( $post->ID, 'adf_email', true );
	$phone = get_post_meta( $post->ID, 'adf_phone', true );

	echo '<p style="margin-top:14px">';
	if ( $email ) {
		printf(
			'<a class="button button-primary" href="mailto:%s?subject=%s">%s</a> ',
			esc_attr( $email ),
			esc_attr( rawurlencode( __( 'Re: din förfrågan till Advantage Advokatbyrå', 'advantage-dolda-fel' ) ) ),
			esc_html__( 'Reply by e-mail', 'advantage-dolda-fel' )
		);
	}
	if ( $phone ) {
		printf(
			'<a class="button" href="tel:%s">%s</a>',
			esc_attr( preg_replace( '/\s+/', '', $phone ) ),
			esc_html__( 'Call', 'advantage-dolda-fel' )
		);
	}
	echo '</p>';
}

/**
 * The tracking values.
 *
 * @param WP_Post $post Entry.
 */
function adf_entry_tracking_box( $post ) {
	echo '<table class="widefat" style="border:0">';
	foreach ( adf_entry_tracking_fields() as $key => $label ) {
		$value = get_post_meta( $post->ID, $key, true );

		if ( '_adf_mail_status' === $key ) {
			if ( 'sent' === $value ) {
				$value = __( 'Sent to: ', 'advantage-dolda-fel' ) . implode( ', ', adf_notification_recipients() );
			} elseif ( 'failed' === $value ) {
				$value = __( 'FAILED — ', 'advantage-dolda-fel' ) . get_post_meta( $post->ID, '_adf_mail_error', true );
			}
		}

		if ( '' === $value ) {
			continue;
		}

		$display = esc_html( $value );
		if ( in_array( $key, array( '_adf_submitted_url', '_adf_landing_url', '_adf_referrer' ), true ) ) {
			$display = '<a href="' . esc_url( $value ) . '" target="_blank" rel="noopener">' . esc_html( $value ) . '</a>';
		}

		printf(
			'<tr><th style="text-align:left;padding:6px 0 0">%s</th></tr><tr><td style="padding:0 0 8px;word-break:break-all">%s</td></tr>',
			esc_html( $label ),
			wp_kses_post( $display )
		);
	}
	echo '</table>';
}

/**
 * Mark an entry read when it is opened, so the NEW badge clears itself.
 *
 * @param string $post_type Current post type.
 */
function adf_entry_mark_read( $post_type ) {
	global $post;
	if ( ADF_ENTRY_CPT === $post_type && $post instanceof WP_Post ) {
		update_post_meta( $post->ID, '_adf_read', '1' );
	}
}
add_action( 'add_meta_boxes', 'adf_entry_mark_read' );

/**
 * Unread count bubble next to the menu item.
 */
function adf_entry_menu_bubble() {
	global $menu;

	$unread = new WP_Query(
		array(
			'post_type'      => ADF_ENTRY_CPT,
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'meta_key'       => '_adf_read',
			'meta_value'     => '0',
			'fields'         => 'ids',
			'no_found_rows'  => false,
		)
	);

	$count = (int) $unread->found_posts;

	if ( ! $count ) {
		return;
	}

	foreach ( $menu as $i => $item ) {
		if ( isset( $item[2] ) && 'edit.php?post_type=' . ADF_ENTRY_CPT === $item[2] ) {
			$menu[ $i ][0] .= sprintf( ' <span class="awaiting-mod"><span class="pending-count">%d</span></span>', $count ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			break;
		}
	}
}
add_action( 'admin_menu', 'adf_entry_menu_bubble', 999 );

/* -------------------------------------------------------------------------
 * CSV export
 * ---------------------------------------------------------------------- */

/**
 * Export button above the list.
 *
 * @param string $post_type Post type.
 */
function adf_entry_export_button( $post_type ) {
	if ( ADF_ENTRY_CPT !== $post_type ) {
		return;
	}

	$url = wp_nonce_url(
		admin_url( 'edit.php?post_type=' . ADF_ENTRY_CPT . '&adf_export=1' ),
		'adf_export',
		'adf_export_nonce'
	);

	printf(
		'<a href="%s" class="button" style="margin:0 0 0 8px">%s</a>',
		esc_url( $url ),
		esc_html__( 'Export CSV', 'advantage-dolda-fel' )
	);
}
add_action( 'restrict_manage_posts', 'adf_entry_export_button' );

/**
 * Stream every entry as CSV.
 */
function adf_entry_export() {
	if ( ! isset( $_GET['adf_export'], $_GET['adf_export_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['adf_export_nonce'] ) ), 'adf_export' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_die( esc_html__( 'You do not have permission to export entries.', 'advantage-dolda-fel' ) );
	}

	$entries = get_posts(
		array(
			'post_type'      => ADF_ENTRY_CPT,
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);

	$fields   = adf_entry_fields();
	$tracking = adf_entry_tracking_fields();

	nocache_headers();
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=advantage-entries-' . gmdate( 'Y-m-d' ) . '.csv' );

	$out = fopen( 'php://output', 'w' );

	// BOM so Excel opens the Swedish characters correctly.
	fwrite( $out, "\xEF\xBB\xBF" );

	fputcsv( $out, array_merge( array( __( 'Received', 'advantage-dolda-fel' ) ), array_values( $fields ), array_values( $tracking ) ) );

	foreach ( $entries as $entry ) {
		$row = array( get_the_date( 'Y-m-d H:i', $entry ) );
		foreach ( array_keys( $fields ) as $key ) {
			$row[] = get_post_meta( $entry->ID, $key, true );
		}
		foreach ( array_keys( $tracking ) as $key ) {
			$row[] = get_post_meta( $entry->ID, $key, true );
		}
		fputcsv( $out, $row );
	}

	fclose( $out );
	exit;
}
add_action( 'admin_init', 'adf_entry_export' );

/* -------------------------------------------------------------------------
 * Settings: who gets notified, and a test send
 * ---------------------------------------------------------------------- */

/**
 * Settings page under Form Entries.
 */
function adf_entry_settings_menu() {
	add_submenu_page(
		'edit.php?post_type=' . ADF_ENTRY_CPT,
		__( 'Notifications', 'advantage-dolda-fel' ),
		__( 'Notifications', 'advantage-dolda-fel' ),
		'manage_options',
		'adf-notifications',
		'adf_entry_settings_screen'
	);
}
add_action( 'admin_menu', 'adf_entry_settings_menu' );

/**
 * Render the notifications screen.
 */
function adf_entry_settings_screen() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'advantage-dolda-fel' ) );
	}

	$notice = '';

	if ( isset( $_POST['adf_notify_nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['adf_notify_nonce'] ) ), 'adf_notify' ) ) {

		if ( isset( $_POST['adf_recipients'] ) ) {
			$raw   = sanitize_text_field( wp_unslash( $_POST['adf_recipients'] ) );
			$valid = array_filter( array_map( 'trim', explode( ',', $raw ) ), 'is_email' );
			update_option( 'adf_notification_recipients', implode( ', ', $valid ) );
			$notice = __( 'Recipients saved.', 'advantage-dolda-fel' );
		}

		if ( isset( $_POST['adf_send_test'] ) ) {
			$to   = adf_notification_recipients();
			$sent = wp_mail(
				$to,
				__( '[Test] Advantage form notification', 'advantage-dolda-fel' ),
				'<p>' . esc_html__( 'This is a test of the form notification e-mail. If you can read this, delivery works.', 'advantage-dolda-fel' ) . '</p>',
				array( 'Content-Type: text/html; charset=UTF-8' )
			);
			$notice = $sent
				? sprintf( /* translators: %s: recipients */ __( 'Test e-mail accepted for delivery to %s. Check the inboxes, including spam.', 'advantage-dolda-fel' ), implode( ', ', $to ) )
				: __( 'Test e-mail FAILED. wp_mail() rejected it — install an SMTP plugin (see below).', 'advantage-dolda-fel' );
		}
	}

	$recipients = get_option( 'adf_notification_recipients', '' );
	if ( ! $recipients ) {
		$recipients = implode( ', ', adf_notification_recipients() );
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Form notifications', 'advantage-dolda-fel' ); ?></h1>

		<?php if ( $notice ) : ?>
			<div class="notice notice-info"><p><?php echo esc_html( $notice ); ?></p></div>
		<?php endif; ?>

		<form method="post">
			<?php wp_nonce_field( 'adf_notify', 'adf_notify_nonce' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="adf_recipients"><?php esc_html_e( 'Send notifications to', 'advantage-dolda-fel' ); ?></label></th>
					<td>
						<input name="adf_recipients" id="adf_recipients" type="text" class="regular-text" style="width:32em"
						       value="<?php echo esc_attr( $recipients ); ?>">
						<p class="description"><?php esc_html_e( 'Comma-separated. Anything that is not a valid address is dropped on save.', 'advantage-dolda-fel' ); ?></p>
					</td>
				</tr>
			</table>
			<p>
				<button type="submit" class="button button-primary"><?php esc_html_e( 'Save recipients', 'advantage-dolda-fel' ); ?></button>
				<button type="submit" name="adf_send_test" value="1" class="button"><?php esc_html_e( 'Send test e-mail', 'advantage-dolda-fel' ); ?></button>
			</p>
		</form>

		<hr>

		<h2><?php esc_html_e( 'Making delivery reliable', 'advantage-dolda-fel' ); ?></h2>
		<p style="max-width:62em">
			<?php esc_html_e( 'WordPress sends mail with PHP\'s mail() by default. Most hosts send it from an address that fails SPF and DKIM checks, so it lands in spam or is dropped silently. For a law firm that is a lost client, not an inconvenience.', 'advantage-dolda-fel' ); ?>
		</p>
		<p style="max-width:62em">
			<strong><?php esc_html_e( 'Install an SMTP plugin', 'advantage-dolda-fel' ); ?></strong>
			<?php esc_html_e( '(WP Mail SMTP, FluentSMTP or Post SMTP) and connect it to the advantage.se mail account, or to a transactional service such as Postmark, Brevo or Amazon SES. Then press "Send test e-mail" above and confirm both inboxes received it.', 'advantage-dolda-fel' ); ?>
		</p>
		<p style="max-width:62em">
			<?php esc_html_e( 'Whatever happens to the e-mail, every submission is stored under Form Entries first. Mail is a notification, not the record — nothing is lost if delivery fails, and the E-mail column on the entries list shows you when it does.', 'advantage-dolda-fel' ); ?>
		</p>
	</div>
	<?php
}
