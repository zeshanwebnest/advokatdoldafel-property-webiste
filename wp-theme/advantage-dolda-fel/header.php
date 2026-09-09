<?php
/**
 * Document head, and the site header.
 *
 * The header is rendered by Elementor when a Theme Builder Header template is
 * published and matches; otherwise the theme's own header renders.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#FFFFFF">
<link rel="profile" href="https://gmpg.org/xfn/11">
<script>document.documentElement.classList.remove('no-js');</script>
<?php adf_render_hero_preload(); ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e( 'Till innehållet', 'advantage-dolda-fel' ); ?></a>

<?php
// Elementor Theme Builder header, if one is published and matches this request.
if ( ! adf_do_elementor_location( 'header' ) ) {
	get_template_part( 'template-parts/site', 'header' );
}
?>

<main id="main">
