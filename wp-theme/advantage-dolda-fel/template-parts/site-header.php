<?php
/**
 * The theme's own site header: top bar, header row, mobile drawer.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$adf_phone_display = get_theme_mod( 'adf_phone_display', '+ 46 8 20 21 40' );
$adf_phone_link    = get_theme_mod( 'adf_phone_link', '+468202140' );
$adf_email         = get_theme_mod( 'adf_email', 'info@advantage.se' );
$adf_address       = get_theme_mod( 'adf_address', 'Sveavägen 33, 111 34 Stockholm' );
$adf_hours         = get_theme_mod( 'adf_hours', 'Mån–Fre, 09:00–17:00' );
?>
<!-- ===================== TOP BAR ===================== -->
<div class="topbar">
  <div class="container topbar__inner">
    <ul>
      <li><span class="icon icon--pin" aria-hidden="true"></span> <?php echo esc_html( $adf_address ); ?></li>
      <li class="u-hide-sm"><span class="icon icon--clock" aria-hidden="true"></span> <?php echo esc_html( $adf_hours ); ?></li>
    </ul>
    <ul>
      <li><span class="icon icon--phone" aria-hidden="true"></span> <a href="tel:<?php echo esc_attr( $adf_phone_link ); ?>"><?php echo esc_html( $adf_phone_display ); ?></a></li>
      <li><span class="icon icon--mail" aria-hidden="true"></span> <a href="mailto:<?php echo esc_attr( $adf_email ); ?>"><?php echo esc_html( $adf_email ); ?></a></li>
    </ul>
  </div>
</div>

<!-- ===================== HEADER ===================== -->
<header class="site-header">
  <div class="container site-header__inner">
    <?php adf_brand_link( 'brand' ); ?>

    <nav class="nav" aria-label="<?php esc_attr_e( 'Huvudmeny', 'advantage-dolda-fel' ); ?>">
      <?php
      wp_nav_menu(
        array(
          'theme_location' => 'primary',
          'container'      => false,
          'depth'          => 2,
          'fallback_cb'    => 'adf_fallback_primary_menu',
        )
      );
      ?>
    </nav>

    <div class="header-actions">
      <a class="header-phone" href="tel:<?php echo esc_attr( $adf_phone_link ); ?>">
        <span class="icon icon--phone" aria-hidden="true"></span> <?php echo esc_html( $adf_phone_display ); ?>
      </a>
      <button class="nav-toggle" type="button" data-nav-toggle aria-expanded="false" aria-controls="mobile-nav">
        <span class="icon" aria-hidden="true"></span>
        <span class="visually-hidden"><?php esc_html_e( 'Öppna menyn', 'advantage-dolda-fel' ); ?></span>
      </button>
    </div>
  </div>
</header>

<!-- ===================== MOBILE NAV ===================== -->
<div class="mobile-nav" id="mobile-nav">
  <div class="mobile-nav__head">
    <?php adf_brand_link( 'brand brand--inverse' ); ?>
    <button class="nav-toggle nav-toggle--close" type="button" data-nav-close>
      <span class="icon" aria-hidden="true"></span>
      <span class="visually-hidden"><?php esc_html_e( 'Stäng menyn', 'advantage-dolda-fel' ); ?></span>
    </button>
  </div>
  <div class="mobile-nav__body">
    <nav aria-label="<?php esc_attr_e( 'Mobilmeny', 'advantage-dolda-fel' ); ?>">
      <?php
      wp_nav_menu(
        array(
          'theme_location' => 'primary',
          'container'      => false,
          'menu_class'     => 'mobile-nav__list',
          'depth'          => 2,
          'fallback_cb'    => 'adf_fallback_mobile_menu',
        )
      );
      ?>
    </nav>
    <div class="mobile-nav__foot">
      <a class="btn btn--light btn--block" href="<?php echo adf_page_url( 'kontakta-oss' ); ?>"><?php esc_html_e( 'Kontakta oss', 'advantage-dolda-fel' ); ?></a>
    </div>
    <div class="mobile-nav__contact">
      <a href="tel:<?php echo esc_attr( $adf_phone_link ); ?>"><span class="icon icon--phone" aria-hidden="true"></span> <?php echo esc_html( $adf_phone_display ); ?></a>
      <a href="mailto:<?php echo esc_attr( $adf_email ); ?>"><span class="icon icon--mail" aria-hidden="true"></span> <?php echo esc_html( $adf_email ); ?></a>
      <p><?php echo esc_html( $adf_address ); ?></p>
    </div>
  </div>
</div>
