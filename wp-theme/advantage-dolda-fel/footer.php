<?php
/**
 * Site footer and document close.
 *
 * Rendered by Elementor when a Theme Builder Footer template is published and
 * matches; otherwise the theme's own footer renders.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
</main>

<?php
if ( ! adf_do_elementor_location( 'footer' ) ) {
	get_template_part( 'template-parts/site', 'footer' );
}
?>

<?php wp_footer(); ?>
</body>
</html>
