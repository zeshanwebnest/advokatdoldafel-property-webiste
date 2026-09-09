<?php
/**
 * Customizer settings.
 *
 * Everything the header and footer read is a theme mod with a sensible default,
 * so the theme is correct on activation and editable without touching PHP.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the panel, its sections and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function adf_customize_register( $wp_customize ) {
	$wp_customize->add_panel(
		'adf_panel',
		array(
			'title'       => __( 'Advantage — kontakt & sociala medier', 'advantage-dolda-fel' ),
			'description' => __( 'Everything the header top bar and the footer display. Leave a social field empty to hide that icon.', 'advantage-dolda-fel' ),
			'priority'    => 30,
		)
	);

	$fields = array(
		'adf_contact' => array(
			'title'  => __( 'Kontaktuppgifter', 'advantage-dolda-fel' ),
			'fields' => array(
				'adf_phone_display' => array( __( 'Telefon (visas)', 'advantage-dolda-fel' ), '+ 46 8 20 21 40', 'text' ),
				'adf_phone_link'    => array( __( 'Telefon (tel:-länk)', 'advantage-dolda-fel' ), '+468202140', 'text' ),
				'adf_email'         => array( __( 'E-post', 'advantage-dolda-fel' ), 'info@advantage.se', 'email' ),
				'adf_address'       => array( __( 'Adress (topbar)', 'advantage-dolda-fel' ), 'Sveavägen 33, 111 34 Stockholm', 'text' ),
				'adf_hours'         => array( __( 'Öppettider (topbar)', 'advantage-dolda-fel' ), 'Mån–Fre, 09:00–17:00', 'text' ),
			),
		),
		'adf_footer'  => array(
			'title'  => __( 'Sidfot', 'advantage-dolda-fel' ),
			'fields' => array(
				'adf_footer_blurb'   => array( __( 'Text under logotypen', 'advantage-dolda-fel' ), 'Advantage Advokatbyrå tillhandahåller kvalificerad juridisk rådgivning till privatpersoner och företag i hela Sverige. Vårt mål är att ge tydlig och resultatinriktad juridisk hjälp som tillvaratar våra klienters intressen.', 'textarea' ),
				'adf_footer_address' => array( __( 'Adress (sidfot, HTML tillåten)', 'advantage-dolda-fel' ), 'Advantage Advokatbyrå AB<br>Sveavägen 33 111 34 Stockholm', 'textarea' ),
				'adf_legal_name'     => array( __( 'Juridiskt namn i copyright', 'advantage-dolda-fel' ), 'Advantage Law Firm', 'text' ),
			),
		),
		'adf_social'  => array(
			'title'  => __( 'Sociala medier', 'advantage-dolda-fel' ),
			'fields' => array(
				'adf_social_youtube'   => array( 'YouTube', 'https://www.youtube.com/@advantagejuristbyraab4359', 'url' ),
				'adf_social_facebook'  => array( 'Facebook', '', 'url' ),
				'adf_social_instagram' => array( 'Instagram', '', 'url' ),
				'adf_social_linkedin'  => array( 'LinkedIn', '', 'url' ),
			),
		),
	);

	foreach ( $fields as $section_id => $section ) {
		$wp_customize->add_section(
			$section_id,
			array(
				'title' => $section['title'],
				'panel' => 'adf_panel',
			)
		);

		foreach ( $section['fields'] as $key => $field ) {
			list( $label, $default, $type ) = $field;

			$sanitize = 'sanitize_text_field';
			if ( 'url' === $type ) {
				$sanitize = 'esc_url_raw';
			} elseif ( 'email' === $type ) {
				$sanitize = 'sanitize_email';
			} elseif ( 'textarea' === $type ) {
				$sanitize = 'wp_kses_post';
			}

			$wp_customize->add_setting(
				$key,
				array(
					'default'           => $default,
					'sanitize_callback' => $sanitize,
					'transport'         => 'refresh',
				)
			);

			$wp_customize->add_control(
				$key,
				array(
					'label'   => $label,
					'section' => $section_id,
					'type'    => ( 'textarea' === $type ) ? 'textarea' : ( 'url' === $type ? 'url' : ( 'email' === $type ? 'email' : 'text' ) ),
				)
			);
		}
	}
}
add_action( 'customize_register', 'adf_customize_register' );
