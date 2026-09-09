<?php
/**
 * Single blog post.
 *
 * This template applies to every post already in the database and every post
 * written from now on. It reads nothing but core WordPress fields — title,
 * date, author, categories, featured image and the_content() — so the 80+
 * existing articles render in the new design without being edited, migrated or
 * re-saved. Nothing here writes to a post.
 *
 * Elementor first: if a Theme Builder Single template is published and its
 * conditions match this post, Elementor renders it and none of the markup below
 * runs. That is the switch between the two modes, and it lives in the WordPress
 * admin rather than in this file.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( ! adf_do_elementor_location( 'single' ) ) :
	while ( have_posts() ) :
		the_post();

		$adf_cat      = adf_primary_category();
		$adf_has_side = is_active_sidebar( 'adf-blog' );
		?>

<!-- ===================== ARTICLE HEAD ===================== -->
<article <?php post_class( 'adf-article' ); ?>>

  <section class="page-head" aria-labelledby="page-title">
    <div class="page-head__media" aria-hidden="true">
      <?php
      if ( has_post_thumbnail() ) {
        the_post_thumbnail( 'adf-wide', array( 'sizes' => '100vw', 'fetchpriority' => 'high', 'alt' => '' ) );
      } else {
        ?>
        <img src="<?php echo adf_asset( 'assets/images/property/stockholm-skyline-wide-1600.webp' ); ?>"
             srcset="<?php echo esc_attr( adf_srcset( 'assets/images/property/stockholm-skyline-wide-1000.webp 1000w, assets/images/property/stockholm-skyline-wide-1600.webp 1600w, assets/images/property/stockholm-skyline-wide-2400.webp 2400w' ) ); ?>"
             sizes="100vw" width="1600" height="600" fetchpriority="high" decoding="async" alt="">
        <?php
      }
      ?>
    </div>

    <div class="container page-head__inner on-dark">
      <nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Brödsmulor', 'advantage-dolda-fel' ); ?>">
        <ol>
          <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Hem', 'advantage-dolda-fel' ); ?></a></li>
          <li><a href="<?php echo adf_blog_url(); ?>"><?php esc_html_e( 'Artiklar', 'advantage-dolda-fel' ); ?></a></li>
          <li><span aria-current="page"><?php the_title(); ?></span></li>
        </ol>
      </nav>

      <?php if ( $adf_cat ) : ?>
        <p class="badge badge--dark"><?php echo esc_html( $adf_cat ); ?></p>
      <?php endif; ?>

      <h1 class="h1" id="page-title"><?php the_title(); ?></h1>

      <?php
      // The standfirst under the headline, as on the local design.
      //
      // has_excerpt(), not get_the_excerpt(): the latter auto-generates from the
      // body when no excerpt is set, which would print the article's own opening
      // paragraph twice. Posts with a hand-written excerpt get a standfirst;
      // posts without one simply go straight from headline to meta, which is what
      // the 80+ existing articles will do until someone writes excerpts for them.
      if ( has_excerpt() ) :
        ?>
        <p class="lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
      <?php endif; ?>

      <div class="article-meta article-meta--inverse u-mt-6">
        <time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
        <span aria-hidden="true">·</span>
        <span>
          <?php
          printf(
            /* translators: %d: minutes */
            esc_html( _n( '%d min läsning', '%d min läsning', adf_reading_time(), 'advantage-dolda-fel' ) ),
            (int) adf_reading_time()
          );
          ?>
        </span>
        <span aria-hidden="true">·</span>
        <span><?php echo esc_html( get_the_author() ); ?></span>
      </div>
    </div>
  </section>

  <!-- ===================== ARTICLE BODY ===================== -->
  <section class="section section--lg">
    <div class="container">
      <div class="article-layout">
        <div class="article-main">
          <div class="prose" data-article-body>
            <?php the_content(); ?>
          </div>

          <?php
          wp_link_pages(
            array(
              'before' => '<nav class="adf-page-links u-mt-7"><span>' . esc_html__( 'Sidor:', 'advantage-dolda-fel' ) . '</span>',
              'after'  => '</nav>',
            )
          );
          ?>

          <?php if ( has_tag() ) : ?>
            <div class="chips u-mt-7">
              <?php
              foreach ( get_the_tags() as $adf_tag ) :
                printf(
                  '<a class="chip" href="%s">%s</a>',
                  esc_url( get_tag_link( $adf_tag ) ),
                  esc_html( $adf_tag->name )
                );
              endforeach;
              ?>
            </div>
          <?php endif; ?>

          <?php get_template_part( 'template-parts/author', 'card' ); ?>
        </div>

        <?php
        // The sticky sidebar. It renders ALWAYS, which is the fix for the single
        // post looking nothing like the local design.
        //
        // It used to be wrapped in is_active_sidebar( 'adf-blog' ), so on a fresh
        // install — where no widgets have been added — the whole column was
        // omitted. .article-layout is a two-column grid above 1040px, so with the
        // aside gone it collapsed to one column and the prose ran the full width
        // of the container. That is a very visible difference from local, and it
        // also meant articles were the only pages on the site with no enquiry
        // form above the fold.
        //
        // Widgets, if any are added later, stack above the form rather than
        // replacing it.
        ?>
        <aside class="article-aside" aria-labelledby="adf-side-form-title">
          <?php if ( $adf_has_side ) : ?>
            <?php dynamic_sidebar( 'adf-blog' ); ?>
          <?php endif; ?>

          <div class="side-form">
            <p class="side-form__title" id="adf-side-form-title">
              <?php esc_html_e( 'Kontakta oss för rådgivning', 'advantage-dolda-fel' ); ?>
            </p>
            <?php
            // THE global form component — the same file, and now the same field
            // set, as the Kontakta oss page. The narrow column is handled in CSS
            // (styles.css section 28), not by dropping fields here.
            get_template_part(
              'template-parts/form',
              'contact',
              array( 'source' => 'artikel-sidebar' )
            );
            ?>
          </div>
        </aside>
      </div>
    </div>
  </section>

  <?php get_template_part( 'template-parts/related', 'posts' ); ?>

  <?php
  if ( comments_open() || get_comments_number() ) :
    ?>
    <section class="section section--lg section--alt">
      <div class="container">
        <div class="article-layout">
          <div class="article-main">
            <?php comments_template(); ?>
          </div>
        </div>
      </div>
    </section>
    <?php
  endif;
  ?>

</article>

		<?php
	endwhile;

	get_template_part( 'template-parts/cta', 'contact' );

endif;

get_footer();
