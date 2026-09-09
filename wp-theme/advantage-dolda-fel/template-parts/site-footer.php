<?php
/**
 * The theme's own site footer: four columns over a copyright bar.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$adf_phone_display = get_theme_mod( 'adf_phone_display', '+ 46 8 20 21 40' );
$adf_phone_link    = get_theme_mod( 'adf_phone_link', '+468202140' );
$adf_email         = get_theme_mod( 'adf_email', 'info@advantage.se' );
$adf_blurb         = get_theme_mod(
	'adf_footer_blurb',
	'Advantage Advokatbyrå tillhandahåller kvalificerad juridisk rådgivning till privatpersoner och företag i hela Sverige. Vårt mål är att ge tydlig och resultatinriktad juridisk hjälp som tillvaratar våra klienters intressen.'
);
$adf_social = array(
	'youtube'   => get_theme_mod( 'adf_social_youtube', 'https://www.youtube.com/@advantagejuristbyraab4359' ),
	'facebook'  => get_theme_mod( 'adf_social_facebook', '' ),
	'instagram' => get_theme_mod( 'adf_social_instagram', '' ),
	'linkedin'  => get_theme_mod( 'adf_social_linkedin', '' ),
);
$adf_social_svg = array(
	'youtube' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.6 7.2a2.5 2.5 0 0 0-1.75-1.77C18.3 5 12 5 12 5s-6.3 0-7.85.43A2.5 2.5 0 0 0 2.4 7.2 26 26 0 0 0 2 12a26 26 0 0 0 .4 4.8 2.5 2.5 0 0 0 1.75 1.77C5.7 19 12 19 12 19s6.3 0 7.85-.43a2.5 2.5 0 0 0 1.75-1.77A26 26 0 0 0 22 12a26 26 0 0 0-.4-4.8ZM10 15V9l5.2 3Z"/></svg>',
	'facebook' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 21v-8h2.7l.4-3.1h-3.1V7.9c0-.9.25-1.5 1.55-1.5H16.7V3.6a22 22 0 0 0-2.4-.12c-2.4 0-4 1.45-4 4.13V9.9H7.6V13h2.7v8Z"/></svg>',
	'instagram' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 4.6c2.4 0 2.7 0 3.6.05.9.04 1.35.18 1.66.3.42.16.72.36 1.03.67.31.31.51.61.67 1.03.12.31.26.77.3 1.66.04.9.05 1.17.05 3.6s0 2.7-.05 3.6c-.04.9-.18 1.35-.3 1.66-.16.42-.36.72-.67 1.03-.31.31-.61.51-1.03.67-.31.12-.77.26-1.66.3-.9.04-1.17.05-3.6.05s-2.7 0-3.6-.05c-.9-.04-1.35-.18-1.66-.3a2.8 2.8 0 0 1-1.03-.67 2.8 2.8 0 0 1-.67-1.03c-.12-.31-.26-.77-.3-1.66C4.6 14.7 4.6 14.4 4.6 12s0-2.7.05-3.6c.04-.9.18-1.35.3-1.66.16-.42.36-.72.67-1.03.31-.31.61-.51 1.03-.67.31-.12.77-.26 1.66-.3.9-.04 1.2-.05 3.6-.05m0-1.6c-2.44 0-2.75.01-3.71.05-.95.05-1.6.2-2.17.42-.59.23-1.09.54-1.59 1.03-.49.5-.8 1-1.03 1.59-.22.57-.37 1.22-.42 2.17C3.04 9.25 3 9.56 3 12s.01 2.75.05 3.71c.05.95.2 1.6.42 2.17.23.59.54 1.09 1.03 1.59.5.49 1 .8 1.59 1.03.57.22 1.22.37 2.17.42.96.04 1.27.05 3.71.05s2.75-.01 3.71-.05c.95-.05 1.6-.2 2.17-.42.59-.23 1.09-.54 1.59-1.03.49-.5.8-1 1.03-1.59.22-.57.37-1.22.42-2.17.04-.96.05-1.27.05-3.71s-.01-2.75-.05-3.71c-.05-.95-.2-1.6-.42-2.17a4.4 4.4 0 0 0-1.03-1.59 4.4 4.4 0 0 0-1.59-1.03c-.57-.22-1.22-.37-2.17-.42C14.75 3.01 14.44 3 12 3Zm0 4.38a4.62 4.62 0 1 0 0 9.24 4.62 4.62 0 0 0 0-9.24ZM12 15a3 3 0 1 1 0-6 3 3 0 0 1 0 6Zm5.88-7.8a1.08 1.08 0 1 1-2.16 0 1.08 1.08 0 0 1 2.16 0Z"/></svg>',
	'linkedin' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.94 8.5H4.06V20h2.88ZM5.5 3.9a1.67 1.67 0 1 0 0 3.34 1.67 1.67 0 0 0 0-3.34ZM20 13.6c0-3.1-1.66-4.54-3.87-4.54-1.79 0-2.59.98-3.03 1.67V8.5H10.2c.04.81 0 11.5 0 11.5h2.9v-6.42c0-.26.02-.52.1-.7.2-.52.68-1.05 1.48-1.05 1.05 0 1.47.79 1.47 1.95V20H20Z"/></svg>',
);
$adf_social_label = array(
	'youtube'   => 'YouTube',
	'facebook'  => 'Facebook',
	'instagram' => 'Instagram',
	'linkedin'  => 'LinkedIn',
);
?>
<!-- ===================== FOOTER ===================== -->
<footer class="site-footer">
  <div class="container">

    <div class="footer-cols footer-cols--brand">
      <div class="footer-intro">
        <?php adf_brand_link( 'brand brand--inverse' ); ?>
        <p><?php echo esc_html( $adf_blurb ); ?></p>
      </div>

      <nav aria-labelledby="f-snabb">
        <h2 id="f-snabb"><?php esc_html_e( 'Snabblänkar', 'advantage-dolda-fel' ); ?></h2>
        <?php
        wp_nav_menu(
          array(
            'theme_location' => 'footer',
            'container'      => false,
            'menu_class'     => 'footer-links',
            'depth'          => 1,
            'fallback_cb'    => 'adf_fallback_footer_menu',
          )
        );
        ?>
      </nav>

      <div>
        <h2 id="f-kontakt"><?php esc_html_e( 'Kontaktinformation', 'advantage-dolda-fel' ); ?></h2>
        <address>
          <ul class="footer-contact" aria-labelledby="f-kontakt">
            <li><span class="icon icon--pin" aria-hidden="true"></span>
              <span><?php echo wp_kses_post( get_theme_mod( 'adf_footer_address', 'Advantage Advokatbyrå AB<br>Sveavägen 33 111 34 Stockholm' ) ); ?></span></li>
            <li><span class="icon icon--mail" aria-hidden="true"></span>
              <a href="mailto:<?php echo esc_attr( $adf_email ); ?>"><?php echo esc_html( $adf_email ); ?></a></li>
            <li><span class="icon icon--phone" aria-hidden="true"></span>
              <a href="tel:<?php echo esc_attr( $adf_phone_link ); ?>"><?php echo esc_html( $adf_phone_display ); ?></a></li>
          </ul>
        </address>
      </div>

      <div>
        <h2 id="f-social"><?php esc_html_e( 'Prenumerera oss', 'advantage-dolda-fel' ); ?></h2>
        <ul class="social-row" aria-labelledby="f-social">
          <?php
          foreach ( $adf_social as $adf_net => $adf_url ) :
            if ( ! $adf_url ) {
              continue;
            }
            ?>
            <li><a href="<?php echo esc_url( $adf_url ); ?>" rel="noopener noreferrer" target="_blank"
                   aria-label="<?php echo esc_attr( sprintf( __( 'Advantage Advokatbyrå på %s', 'advantage-dolda-fel' ), $adf_social_label[ $adf_net ] ) ); ?>">
              <?php echo $adf_social_svg[ $adf_net ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static inline SVG. ?>
            </a></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <div class="site-footer__bottom">
      <p>
        <?php
        printf(
          /* translators: 1: site name, 2: current year */
          esc_html__( '© Copyright %1$s %2$s. Alla rättigheter förbehållna.', 'advantage-dolda-fel' ),
          '<b>' . esc_html( get_theme_mod( 'adf_legal_name', 'Advantage Law Firm' ) ) . '</b>',
          esc_html( wp_date( 'Y' ) )
        );
        ?>
      </p>
      <ul class="footer-legal">
        <li><a href="https://advantage.se/entreprenadratt/" rel="noopener noreferrer" target="_blank"><?php esc_html_e( 'Entreprenadrätt', 'advantage-dolda-fel' ); ?></a></li>
        <li><a href="https://advantage.se/fel-i-entreprenad/" rel="noopener noreferrer" target="_blank"><?php esc_html_e( 'Fel i entreprenad', 'advantage-dolda-fel' ); ?></a></li>
        <li><a href="https://advantage.se/hyres-och-fastighetsratt/" rel="noopener noreferrer" target="_blank"><?php esc_html_e( 'Fastighetsrätt', 'advantage-dolda-fel' ); ?></a></li>
      </ul>
    </div>
  </div>
</footer>
