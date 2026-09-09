<?php
/**
 * One post card in the archive grid.
 *
 * Uses the design system's .post-card component, so cards look identical whether
 * they come from the static build or from a WordPress query.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$adf_cat = adf_primary_category();
?>
<article <?php post_class( "post-card" ); ?> data-post-card data-reveal>
  <div class="post-card__media">
    <?php
    if ( has_post_thumbnail() ) {
      the_post_thumbnail(
        'adf-card',
        array(
          'sizes'   => '(min-width: 900px) 30vw, (min-width: 640px) 45vw, 100vw',
          'loading' => 'lazy',
          'alt'     => the_title_attribute( array( 'echo' => false ) ),
        )
      );
    } else {
      printf(
        '<img src="%s" width="1000" height="667" loading="lazy" decoding="async" alt="">',
        adf_asset( 'assets/images/property/stockholm-skyline-wide-1600.webp' )
      );
    }

    if ( $adf_cat ) :
      ?>
      <span class="post-card__chip"><?php echo esc_html( $adf_cat ); ?></span>
      <?php
    endif;
    ?>
  </div>

  <div class="post-card__body">
    <div class="post-card__meta">
      <?php if ( $adf_cat ) : ?>
        <span class="post-card__cat"><?php echo esc_html( $adf_cat ); ?></span>
      <?php endif; ?>
      <time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
    </div>

    <h3 class="post-card__title">
      <a class="stretched-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h3>

    <p class="post-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28, '…' ) ); ?></p>

    <span class="link-arrow" aria-hidden="true"><span><?php esc_html_e( 'Läs artikeln', 'advantage-dolda-fel' ); ?></span></span>
  </div>
</article>
