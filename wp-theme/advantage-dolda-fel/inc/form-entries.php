<?php
/**
 * Form entries: storage, tracking, admin screens, e-mail notifications.
 *
 * Every submission of the site's contact form is saved as an "adf_entry" post so
 * it survives a mail failure. E-mail is a notification, never the record — if the
 * SMTP host is down, the enquiry is still in the dashboard.
 *
 * Tracking captured per entry:
 *   - first landing page and referrer (first-touch, from a first-party cookie)
 *   - the page the form was actually submitted from
 *   - UTM parameters from the first visit
 *   - date/time, browser, IP
 *   - whether the notification e-mail was accepted for delivery
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const ADF_ENTRY_CPT    = 'adf_entry';
const ADF_TOUCH_COOKIE = 'adf_first_touch';

/* -------------------------------------------------------------------------
 * 1. Storage
 * ---------------------------------------------------------------------- */

/**
 * Register the entry post type.
 *
 * Not public, not queryable, not in search or REST. It exists purely to give
 * entries a durable row and a dashboard list.
 */
function adf_register_entry_cpt() {
	register_post_type(
		ADF_ENTRY_CPT,
		array(
			'labels'              => array(
				'name'               => __( 'Form Entries', 'advantage-dolda-fel' ),
				'singular_name'      => __( 'Form Entry', 'advantage-dolda-fel' ),
				'menu_name'          => __( 'Form Entries', 'advantage-dolda-fel' ),
				'all_items'          => __( 'All Entries', 'advantage-dolda-fel' ),
				'search_items'       => __( 'Search entries', 'advantage-dolda-fel' ),
				'not_found'          => __( 'No entries yet.', 'advantage-dolda-fel' ),
				'not_found_in_trash' => __( 'No entries in the trash.', 'advantage-dolda-fel' ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => false,
			'menu_icon'           => 'dashicons-email-alt',
			'menu_position'       => 26,
			'capability_type'     => 'post',
			'capabilities'        => array(
				'create_posts' => 'do_not_allow', // Entries arrive from the front end only.
			),
			'map_meta_cap'        => true,
			'supports'            => array( 'title' ),
			'has_archive'         => false,
			'rewrite'             => false,
			'query_var'           => false,
		)
	);
}
add_action( 'init', 'adf_register_entry_cpt' );

/**
 * The fields the form collects, in display order.
 *
 * Add a field to the form component and to this map, and it appears in the
 * dashboard, in the e-mail and in the CSV export without touching anything else.
 *
 * @return array<string,string> meta key => label.
 */
function adf_entry_fields() {
	return apply_filters(
		'adf_entry_fields',
		array(
			'adf_name'    => __( 'Namn', 'advantage-dolda-fel' ),
			'adf_phone'   => __( 'Telefon', 'advantage-dolda-fel' ),
			'adf_email'   => __( 'E-post', 'advantage-dolda-fel' ),
			'adf_motpart' => __( 'Din motpart', 'advantage-dolda-fel' ),
			'adf_subject' => __( 'Ämne', 'advantage-dolda-fel' ),
			'adf_message' => __( 'Meddelande', 'advantage-dolda-fel' ),
		)
	);
}

/**
 * The tracking fields, in display order.
 *
 * @return array<string,string> meta key => label.
 */
function adf_entry_tracking_fields() {
	return array(
		'_adf_submitted_url'   => __( 'Submitted from', 'advantage-dolda-fel' ),
		'_adf_submitted_title' => __( 'Page title', 'advantage-dolda-fel' ),
		'_adf_landing_url'     => __( 'First page visited', 'advantage-dolda-fel' ),
		'_adf_referrer'        => __( 'Came from (referrer)', 'advantage-dolda-fel' ),
		'_adf_first_seen'      => __( 'First seen', 'advantage-dolda-fel' ),
		'_adf_utm'             => __( 'Campaign (UTM)', 'advantage-dolda-fel' ),
		'_adf_source'          => __( 'Form location', 'advantage-dolda-fel' ),
		'_adf_ip'              => __( 'IP address', 'advantage-dolda-fel' ),
		'_adf_user_agent'      => __( 'Browser', 'advantage-dolda-fel' ),
		'_adf_mail_status'     => __( 'Notification e-mail', 'advantage-dolda-fel' ),
	);
}

/* -------------------------------------------------------------------------
 * 2. First-touch tracking
 * ---------------------------------------------------------------------- */

/**
 * Record the first page of the visit in a first-party cookie.
 *
 * Written once per visitor and never updated, so the entry shows where they
 * originally arrived rather than the last page before the form. Contains no
 * personal data — a URL, a referrer, a timestamp and any UTM parameters.
 *
 * Runs on 'template_redirect' so conditional tags are ready, and bails on admin,
 * AJAX, feeds and logged-in staff.
 */
function adf_set_first_touch_cookie() {
	if ( is_admin() || wp_doing_ajax() || is_feed() || is_robots() || headers_sent() ) {
		return;
	}

	if ( ! empty( $_COOKIE[ ADF_TOUCH_COOKIE ] ) ) {
		return;
	}

	if ( ! apply_filters( 'adf_enable_tracking', true ) ) {
		return;
	}

	$utm = array();
	foreach ( array( 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'gclid', 'fbclid' ) as $key ) {
		if ( isset( $_GET[ $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only analytics capture.
			$utm[ $key ] = sanitize_text_field( wp_unslash( $_GET[ $key ] ) );
		}
	}

	$data = array(
		'landing'  => adf_current_url(),
		'referrer' => isset( $_SERVER['HTTP_REFERER'] ) ? esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : '',
		'time'     => time(),
		'utm'      => $utm,
	);

	$value = wp_json_encode( $data );

	if ( ! $value ) {
		return;
	}

	setcookie(
		ADF_TOUCH_COOKIE,
		$value,
		array(
			'expires'  => time() + MONTH_IN_SECONDS,
			'path'     => COOKIEPATH ? COOKIEPATH : '/',
			'domain'   => COOKIE_DOMAIN,
			'secure'   => is_ssl(),
			'httponly' => true,
			'samesite' => 'Lax',
		)
	);

	// Make it readable within this same request too.
	$_COOKIE[ ADF_TOUCH_COOKIE ] = $value;
}
add_action( 'template_redirect', 'adf_set_first_touch_cookie', 1 );

/**
 * Read the first-touch cookie.
 *
 * @return array{landing:string,referrer:string,time:int,utm:array}
 */
function adf_get_first_touch() {
	$empty = array(
		'landing'  => '',
		'referrer' => '',
		'time'     => 0,
		'utm'      => array(),
	);

	if ( empty( $_COOKIE[ ADF_TOUCH_COOKIE ] ) ) {
		return $empty;
	}

	$raw = json_decode( wp_unslash( $_COOKIE[ ADF_TOUCH_COOKIE ] ), true ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- decoded and sanitised below.

	if ( ! is_array( $raw ) ) {
		return $empty;
	}

	return array(
		'landing'  => isset( $raw['landing'] ) ? esc_url_raw( $raw['landing'] ) : '',
		'referrer' => isset( $raw['referrer'] ) ? esc_url_raw( $raw['referrer'] ) : '',
		'time'     => isset( $raw['time'] ) ? (int) $raw['time'] : 0,
		'utm'      => isset( $raw['utm'] ) && is_array( $raw['utm'] ) ? array_map( 'sanitize_text_field', $raw['utm'] ) : array(),
	);
}

/* -------------------------------------------------------------------------
 * 3. Submission handling
 * ---------------------------------------------------------------------- */

/**
 * Handle a submission. Shared by the AJAX and the no-JavaScript path.
 *
 * @return array{ok:bool,message:string,entry_id:int}
 */
function adf_process_submission() {
	$fail = function ( $message ) {
		return array(
			'ok'       => false,
			'message'  => $message,
			'entry_id' => 0,
		);
	};

	// Nonce. A stale nonce on a long-cached page is the one legitimate failure
	// here, so the message tells the visitor to reload rather than blaming them.
	if ( ! isset( $_POST['adf_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['adf_nonce'] ) ), 'adf_form_submit' ) ) {
		return $fail( __( 'Sidan var för gammal för att skicka. Ladda om sidan och försök igen.', 'advantage-dolda-fel' ) );
	}

	// Honeypot: a real visitor never fills this in.
	if ( ! empty( $_POST['adf_website'] ) ) {
		return $fail( __( 'Ditt meddelande kunde inte skickas.', 'advantage-dolda-fel' ) );
	}

	// Time trap: submitted under two seconds after render is a script.
	$rendered = isset( $_POST['adf_rendered'] ) ? (int) $_POST['adf_rendered'] : 0;
	if ( $rendered && ( time() - $rendered ) < 2 ) {
		return $fail( __( 'Ditt meddelande kunde inte skickas.', 'advantage-dolda-fel' ) );
	}

	// Collect and sanitise.
	$values = array();
	foreach ( array_keys( adf_entry_fields() ) as $key ) {
		$raw = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitised on the next lines.

		if ( 'adf_message' === $key ) {
			$values[ $key ] = sanitize_textarea_field( $raw );
		} elseif ( 'adf_email' === $key ) {
			$values[ $key ] = sanitize_email( $raw );
		} else {
			$values[ $key ] = sanitize_text_field( $raw );
		}
	}

	// Telephone is the only required field, matching the live form.
	if ( '' === $values['adf_phone'] ) {
		return $fail( __( 'Ange ett telefonnummer så att vi kan återkomma.', 'advantage-dolda-fel' ) );
	}

	if ( '' !== $values['adf_email'] && ! is_email( $values['adf_email'] ) ) {
		return $fail( __( 'E-postadressen ser inte riktig ut. Kontrollera den och försök igen.', 'advantage-dolda-fel' ) );
	}

	$touch = adf_get_first_touch();

	$submitted_url   = isset( $_POST['adf_submitted_url'] ) ? esc_url_raw( wp_unslash( $_POST['adf_submitted_url'] ) ) : '';
	$submitted_title = isset( $_POST['adf_submitted_title'] ) ? sanitize_text_field( wp_unslash( $_POST['adf_submitted_title'] ) ) : '';
	$source          = isset( $_POST['adf_source'] ) ? sanitize_text_field( wp_unslash( $_POST['adf_source'] ) ) : '';

	if ( ! $submitted_url && isset( $_SERVER['HTTP_REFERER'] ) ) {
		$submitted_url = esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) );
	}

	$utm_string = '';
	if ( ! empty( $touch['utm'] ) ) {
		$pairs = array();
		foreach ( $touch['utm'] as $k => $v ) {
			$pairs[] = $k . '=' . $v;
		}
		$utm_string = implode( ' · ', $pairs );
	}

	// Title: who it is from and when, so the list is scannable at a glance.
	$who   = $values['adf_name'] ? $values['adf_name'] : $values['adf_phone'];
	$title = sprintf(
		/* translators: 1: name or phone, 2: date */
		__( '%1$s — %2$s', 'advantage-dolda-fel' ),
		$who,
		wp_date( 'Y-m-d H:i' )
	);

	$entry_id = wp_insert_post(
		array(
			'post_type'   => ADF_ENTRY_CPT,
			'post_title'  => $title,
			'post_status' => 'publish',
		),
		true
	);

	if ( is_wp_error( $entry_id ) || ! $entry_id ) {
		return $fail( __( 'Ditt meddelande kunde tyvärr inte tas emot. Ring gärna + 46 8 20 21 40.', 'advantage-dolda-fel' ) );
	}

	foreach ( $values as $key => $value ) {
		update_post_meta( $entry_id, $key, $value );
	}

	update_post_meta( $entry_id, '_adf_submitted_url', $submitted_url );
	update_post_meta( $entry_id, '_adf_submitted_title', $submitted_title );
	update_post_meta( $entry_id, '_adf_landing_url', $touch['landing'] );
	update_post_meta( $entry_id, '_adf_referrer', $touch['referrer'] );
	update_post_meta( $entry_id, '_adf_first_seen', $touch['time'] ? wp_date( 'Y-m-d H:i', $touch['time'] ) : '' );
	update_post_meta( $entry_id, '_adf_utm', $utm_string );
	update_post_meta( $entry_id, '_adf_source', $source );
	update_post_meta( $entry_id, '_adf_user_agent', isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '' );
	update_post_meta( $entry_id, '_adf_ip', adf_client_ip() );
	update_post_meta( $entry_id, '_adf_read', '0' );

	// The record is safe now. Mail is a notification on top of it.
	$sent = adf_send_notification( $entry_id );
	update_post_meta( $entry_id, '_adf_mail_status', $sent ? 'sent' : 'failed' );

	/**
	 * Fires after an entry is stored and the notification attempted.
	 *
	 * @param int   $entry_id Entry post ID.
	 * @param array $values   Sanitised field values.
	 * @param bool  $sent     Whether wp_mail() accepted the message.
	 */
	do_action( 'adf_entry_saved', $entry_id, $values, $sent );

	return array(
		'ok'       => true,
		'message'  => __( 'Tack. Din förfrågan har tagits emot och vi återkommer så snart som möjligt.', 'advantage-dolda-fel' ),
		'entry_id' => $entry_id,
	);
}

/**
 * Visitor IP, respecting a proxy header only when the site opts in.
 *
 * REMOTE_ADDR is the only value that cannot be forged by the client, so it is the
 * default. Sites behind Cloudflare or a load balancer can switch this on with
 * add_filter( 'adf_trust_proxy_headers', '__return_true' ).
 *
 * @return string
 */
function adf_client_ip() {
	if ( apply_filters( 'adf_trust_proxy_headers', false ) ) {
		foreach ( array( 'HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP' ) as $header ) {
			if ( ! empty( $_SERVER[ $header ] ) ) {
				$list = explode( ',', sanitize_text_field( wp_unslash( $_SERVER[ $header ] ) ) );
				$ip   = trim( $list[0] );
				if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
					return $ip;
				}
			}
		}
	}

	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';

	return filter_var( $ip, FILTER_VALIDATE_IP ) ? $ip : '';
}

/**
 * AJAX endpoint. Both hooks are registered because enquiries can come from
 * logged-in staff testing the form as well as from visitors.
 */
function adf_ajax_submit() {
	$result = adf_process_submission();

	wp_send_json(
		array(
			'ok'      => $result['ok'],
			'message' => $result['message'],
		),
		$result['ok'] ? 200 : 400
	);
}
add_action( 'wp_ajax_adf_form_submit', 'adf_ajax_submit' );
add_action( 'wp_ajax_nopriv_adf_form_submit', 'adf_ajax_submit' );

/**
 * No-JavaScript fallback: the form posts to admin-post.php and we redirect back
 * with a flag. Without this, a visitor with JS disabled loses their enquiry.
 */
function adf_post_submit() {
	$result = adf_process_submission();

	$back = isset( $_POST['adf_submitted_url'] ) ? esc_url_raw( wp_unslash( $_POST['adf_submitted_url'] ) ) : home_url( '/' );
	$back = add_query_arg( $result['ok'] ? 'adf_sent' : 'adf_error', '1', $back );

	wp_safe_redirect( $back . '#main' );
	exit;
}
add_action( 'admin_post_adf_form_submit', 'adf_post_submit' );
add_action( 'admin_post_nopriv_adf_form_submit', 'adf_post_submit' );

/* -------------------------------------------------------------------------
 * 4. E-mail notification
 * ---------------------------------------------------------------------- */

/**
 * Who gets notified.
 *
 * @return string[]
 */
function adf_notification_recipients() {
	$default = array( 'info@advantage.se', 'shafqat@advantage.se' );
	$stored  = get_option( 'adf_notification_recipients', '' );

	if ( $stored ) {
		$list = array_filter( array_map( 'trim', explode( ',', $stored ) ), 'is_email' );
		if ( $list ) {
			$default = $list;
		}
	}

	return apply_filters( 'adf_notification_recipients', $default );
}

/**
 * Send the notification for one entry.
 *
 * @param int $entry_id Entry post ID.
 * @return bool True when wp_mail() accepted the message for delivery.
 */
function adf_send_notification( $entry_id ) {
	$to = adf_notification_recipients();

	if ( empty( $to ) ) {
		return false;
	}

	$name  = get_post_meta( $entry_id, 'adf_name', true );
	$phone = get_post_meta( $entry_id, 'adf_phone', true );
	$email = get_post_meta( $entry_id, 'adf_email', true );

	$subject = sprintf(
		/* translators: 1: site name, 2: submitter */
		__( '[%1$s] Ny förfrågan från %2$s', 'advantage-dolda-fel' ),
		wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ),
		$name ? $name : $phone
	);

	$body = adf_notification_body( $entry_id );

	// From must be on the site's own domain or the message is treated as spoofed
	// and quietly dropped by most receiving servers. Reply-To carries the actual
	// enquirer, so hitting Reply in the inbox answers them directly.
	$site_domain = wp_parse_url( home_url(), PHP_URL_HOST );
	$site_domain = preg_replace( '/^www\./', '', (string) $site_domain );

	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		sprintf( 'From: %s <wordpress@%s>', wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ), $site_domain ),
	);

	if ( $email && is_email( $email ) ) {
		$headers[] = sprintf( 'Reply-To: %s <%s>', $name ? $name : $email, $email );
	}

	$headers = apply_filters( 'adf_notification_headers', $headers, $entry_id );

	// Capture the reason if the mailer rejects it, so the dashboard can show it.
	$error = '';
	$catch = function ( $wp_error ) use ( &$error ) {
		$error = $wp_error->get_error_message();
	};
	add_action( 'wp_mail_failed', $catch );

	$sent = wp_mail( $to, $subject, $body, $headers );

	remove_action( 'wp_mail_failed', $catch );

	if ( ! $sent ) {
		update_post_meta( $entry_id, '_adf_mail_error', $error ? $error : __( 'wp_mail() returned false.', 'advantage-dolda-fel' ) );
	}

	return (bool) $sent;
}

/**
 * The HTML body of the notification.
 *
 * @param int $entry_id Entry post ID.
 * @return string
 */
function adf_notification_body( $entry_id ) {
	$rows = '';

	foreach ( adf_entry_fields() as $key => $label ) {
		$value = get_post_meta( $entry_id, $key, true );
		if ( '' === $value ) {
			continue;
		}
		$rows .= sprintf(
			'<tr><th align="left" valign="top" style="padding:6px 14px 6px 0;color:#5a6b74;font-weight:600;white-space:nowrap">%s</th><td style="padding:6px 0;color:#1B2A32">%s</td></tr>',
			esc_html( $label ),
			nl2br( esc_html( $value ) )
		);
	}

	$track = '';
	foreach ( adf_entry_tracking_fields() as $key => $label ) {
		if ( '_adf_mail_status' === $key ) {
			continue;
		}
		$value = get_post_meta( $entry_id, $key, true );
		if ( '' === $value ) {
			continue;
		}
		$track .= sprintf(
			'<tr><th align="left" valign="top" style="padding:5px 14px 5px 0;color:#5a6b74;font-weight:600;white-space:nowrap">%s</th><td style="padding:5px 0;color:#1B2A32;word-break:break-all">%s</td></tr>',
			esc_html( $label ),
			esc_html( $value )
		);
	}

	$admin_link = admin_url( 'post.php?post=' . (int) $entry_id . '&action=edit' );

	ob_start();
	?>
<!doctype html>
<html><body style="margin:0;padding:24px;background:#F2EEE7;font-family:-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;font-size:15px;line-height:1.6">
  <div style="max-width:640px;margin:0 auto;background:#fff;border-radius:12px;padding:28px 30px">
    <p style="margin:0 0 4px;font-size:12px;letter-spacing:.1em;text-transform:uppercase;color:#8A6538"><?php esc_html_e( 'Ny förfrågan', 'advantage-dolda-fel' ); ?></p>
    <h1 style="margin:0 0 20px;font-size:20px;color:#1B2A32"><?php echo esc_html( get_the_title( $entry_id ) ); ?></h1>

    <table cellpadding="0" cellspacing="0" style="width:100%;border-collapse:collapse"><?php echo $rows; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?></table>

    <h2 style="margin:26px 0 10px;font-size:13px;letter-spacing:.08em;text-transform:uppercase;color:#5a6b74"><?php esc_html_e( 'Spårning', 'advantage-dolda-fel' ); ?></h2>
    <table cellpadding="0" cellspacing="0" style="width:100%;border-collapse:collapse;font-size:13px"><?php echo $track; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?></table>

    <p style="margin:26px 0 0">
      <a href="<?php echo esc_url( $admin_link ); ?>" style="display:inline-block;background:#1B2A32;color:#fff;text-decoration:none;padding:11px 20px;border-radius:8px;font-weight:600"><?php esc_html_e( 'Öppna i WordPress', 'advantage-dolda-fel' ); ?></a>
    </p>
    <p style="margin:18px 0 0;font-size:12px;color:#8a959b"><?php esc_html_e( 'Förfrågan är sparad i WordPress även om detta mejl skulle fastna på vägen.', 'advantage-dolda-fel' ); ?></p>
  </div>
</body></html>
	<?php
	return ob_get_clean();
}
