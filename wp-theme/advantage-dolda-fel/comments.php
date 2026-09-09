<?php
/**
 * Comments.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="adf-comments u-mt-8">
  <?php if ( have_comments() ) : ?>
    <h2 class="h3">
      <?php
      printf(
        /* translators: %d: comment count */
        esc_html( _n( '%d kommentar', '%d kommentarer', get_comments_number(), 'advantage-dolda-fel' ) ),
        (int) get_comments_number()
      );
      ?>
    </h2>

    <ol class="comment-list u-mt-6">
      <?php
      wp_list_comments(
        array(
          'style'      => 'ol',
          'short_ping' => true,
          'avatar_size' => 48,
        )
      );
      ?>
    </ol>

    <?php
    the_comments_pagination(
      array(
        'prev_text' => esc_html__( 'Föregående', 'advantage-dolda-fel' ),
        'next_text' => esc_html__( 'Nästa', 'advantage-dolda-fel' ),
        'class'     => 'adf-pagination u-mt-7',
      )
    );
    ?>
  <?php endif; ?>

  <?php
  comment_form(
    array(
      'class_form'   => 'form u-mt-7',
      'title_reply'  => esc_html__( 'Lämna en kommentar', 'advantage-dolda-fel' ),
      'class_submit' => 'btn btn--primary',
    )
  );
  ?>
</div>
