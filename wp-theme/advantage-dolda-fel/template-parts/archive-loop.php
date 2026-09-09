<?php
/**
 * The archive body: category chips, the card grid, and Load More.
 *
 * Shared by home.php, archive.php, search.php and index.php so every listing on
 * the site is the same design and the same markup.
 *
 * There is deliberately NO numbered pagination. The listing lives on one URL and
 * grows in place, so the site never links to /artiklar/page/2/ and a crawler is
 * never handed one. See inc/load-more.php for how the paginated URLs that
 * WordPress still resolves are kept out of the index.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_query;

$adf_max    = (int) $wp_query->max_num_pages;
$adf_total  = (int) $wp_query->found_posts;
$adf_shown  = (int) $wp_query->post_count;
$adf_ctx    = adf_listing_context();
?>
<section class="section section--lg" aria-labelledby="adf-list-title">
  <div class="container">
    <h2 class="visually-hidden" id="adf-list-title"><?php esc_html_e( 'Artiklar', 'advantage-dolda-fel' ); ?></h2>

    <?php
    // Real category links, not JS filtering. With 80+ posts these are crawlable
    // and shareable; a client-side filter is neither.
    $adf_cats = get_categories(
      array(
        'hide_empty' => true,
        'number'     => 12,
        'orderby'    => 'count',
        'order'      => 'DESC',
      )
    );

    if ( ! empty( $adf_cats ) && ! is_search() ) :
      $adf_current = is_category() ? (int) get_queried_object_id() : 0;
      ?>
      <div class="filter-bar" data-reveal>
        <div class="chips">
          <a class="chip<?php echo $adf_current ? '' : ' is-active'; ?>" href="<?php echo adf_blog_url(); ?>">
            <?php esc_html_e( 'Alla', 'advantage-dolda-fel' ); ?>
          </a>
          <?php foreach ( $adf_cats as $adf_cat ) : ?>
            <a class="chip<?php echo ( $adf_current === (int) $adf_cat->term_id ) ? ' is-active' : ''; ?>"
               href="<?php echo esc_url( get_category_link( $adf_cat ) ); ?>">
              <?php echo esc_html( $adf_cat->name ); ?>
            </a>
          <?php endforeach; ?>
        </div>

        <?php get_search_form(); ?>
      </div>
    <?php endif; ?>

    <?php if ( have_posts() ) : ?>

      <div class="grid grid-3 grid--lg u-mt-7" data-post-grid>
        <?php
        while ( have_posts() ) :
          the_post();
          get_template_part( 'template-parts/content', 'card' );
        endwhile;
        ?>
      </div>

      <?php if ( $adf_max > 1 ) : ?>
        <div class="load-more" data-ajax-load-more
             data-page="1"
             data-max="<?php echo esc_attr( $adf_max ); ?>"
             data-ctx="<?php echo esc_attr( wp_json_encode( $adf_ctx ) ); ?>"
             data-nonce="<?php echo esc_attr( wp_create_nonce( 'adf_load_more' ) ); ?>">

          <button class="btn btn--primary" type="button" data-load-more-btn>
            <?php esc_html_e( 'Visa fler artiklar', 'advantage-dolda-fel' ); ?>
            <span class="icon icon--arrow" aria-hidden="true"></span>
          </button>

          <p class="load-more__status" data-load-more-status role="status" aria-live="polite">
            <?php
            printf(
              /* translators: 1: articles shown, 2: total articles */
              esc_html__( '%1$d av %2$d artiklar visas.', 'advantage-dolda-fel' ),
              (int) $adf_shown,
              (int) $adf_total
            );
            ?>
          </p>

          <?php
          // Without JavaScript the button does nothing, so give those visitors —
          // and any crawler that ignores JS — a plain link onward. The target
          // carries noindex,follow, so it stays out of the results while keeping
          // the older articles reachable rather than orphaned.
          ?>
          <noscript>
            <p class="u-mt-5">
              <a class="btn btn--ghost" href="<?php echo esc_url( get_next_posts_page_link( $adf_max ) ); ?>" rel="next">
                <?php esc_html_e( 'Äldre artiklar', 'advantage-dolda-fel' ); ?>
              </a>
            </p>
          </noscript>
        </div>
      <?php endif; ?>

    <?php else : ?>
      <div class="empty-state u-mt-7">
        <p><?php esc_html_e( 'Inga artiklar hittades.', 'advantage-dolda-fel' ); ?></p>
        <?php if ( is_search() ) : ?>
          <p class="muted"><?php esc_html_e( 'Prova ett annat sökord, eller bläddra bland kategorierna.', 'advantage-dolda-fel' ); ?></p>
          <div class="btn-row u-mt-6">
            <a class="btn btn--primary" href="<?php echo adf_blog_url(); ?>"><?php esc_html_e( 'Alla artiklar', 'advantage-dolda-fel' ); ?></a>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
