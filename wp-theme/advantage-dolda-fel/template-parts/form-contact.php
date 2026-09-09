<?php
/**
 * THE contact form. One definition, one field set, used everywhere.
 *
 * This is the form from the Kontakta oss page — Namn, Telefon, E-post, Din
 * motpart, Ämne, Meddelande — and it is now the only enquiry form the site has.
 * It renders in the closing band on every practice page, on the contact page, in
 * the sidebar of every article, on the 404, and anywhere the [advantage_form]
 * shortcode is dropped. Change a field here and it changes in all of them.
 *
 * There used to be two variants, a short one for the closing band and the full
 * one for the contact page. That is gone deliberately: two variants meant a
 * future edit could reach one and miss the other, which is the exact failure a
 * global component is supposed to prevent.
 *
 * Narrow columns are handled in CSS, not by dropping fields. Inside .side-form
 * and .form-card the paired rows stack to a single column — same fields, same
 * order, same names, just one per line. See styles.css sections 24 and 28.
 *
 * Usage from a template:
 *
 *     get_template_part( 'template-parts/form', 'contact' );
 *     get_template_part( 'template-parts/form', 'contact', array( 'source' => 'sidebar' ) );
 *
 * Usage from the editor, Elementor, or a widget:
 *
 *     [advantage_form]
 *     [advantage_form title="Skicka ett meddelande" card="no"]
 *
 * Args:
 *   id      Unique prefix for field ids. Auto-generated when omitted, so two
 *           forms on one page never collide — which happens on every article,
 *           where the sidebar form and the closing band form share a page.
 *   source  Free label stored with the entry, e.g. "artikel-sidebar". Tells apart
 *           submissions from different places without changing the form itself.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$adf_args   = isset( $args ) && is_array( $args ) ? $args : array();
$adf_source = isset( $adf_args['source'] ) ? $adf_args['source'] : '';
$adf_id     = isset( $adf_args['id'] ) ? $adf_args['id'] : 'adf-' . wp_unique_id();

// Where the visitor is submitting from. Read server-side so it cannot be spoofed
// by editing the DOM, and so it is right even with JavaScript disabled.
$adf_submit_url = adf_current_url();
?>
<form class="form" data-contact-form novalidate
      action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post"
      data-endpoint="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">

  <?php wp_nonce_field( 'adf_form_submit', 'adf_nonce' ); ?>
  <input type="hidden" name="action" value="adf_form_submit">
  <input type="hidden" name="adf_source" value="<?php echo esc_attr( $adf_source ); ?>">
  <input type="hidden" name="adf_submitted_url" value="<?php echo esc_url( $adf_submit_url ); ?>">
  <input type="hidden" name="adf_submitted_title" value="<?php echo esc_attr( adf_current_page_title() ); ?>">

  <?php
  // Spam traps. The honeypot must stay empty; the timestamp catches bots that
  // post the instant the page loads. Both are cheap and need no third party,
  // which matters for a form that carries legal enquiries.
  ?>
  <div class="adf-hp" aria-hidden="true">
    <label for="<?php echo esc_attr( $adf_id ); ?>-website">Website</label>
    <input type="text" id="<?php echo esc_attr( $adf_id ); ?>-website" name="adf_website" tabindex="-1" autocomplete="off">
  </div>
  <input type="hidden" name="adf_rendered" value="<?php echo esc_attr( time() ); ?>">

  <div class="form__row form__row--2">
    <div class="field">
      <label for="<?php echo esc_attr( $adf_id ); ?>-name"><?php esc_html_e( 'Namn', 'advantage-dolda-fel' ); ?></label>
      <input class="input" type="text" id="<?php echo esc_attr( $adf_id ); ?>-name" name="adf_name"
             placeholder="<?php esc_attr_e( 'Ditt namn', 'advantage-dolda-fel' ); ?>"
             autocomplete="name" aria-describedby="<?php echo esc_attr( $adf_id ); ?>-name-err">
      <span class="field__error" id="<?php echo esc_attr( $adf_id ); ?>-name-err"><?php esc_html_e( 'Ange ditt namn.', 'advantage-dolda-fel' ); ?></span>
    </div>

    <div class="field">
      <label for="<?php echo esc_attr( $adf_id ); ?>-phone">
        <?php esc_html_e( 'Telefon', 'advantage-dolda-fel' ); ?> <span class="req" aria-hidden="true">*</span>
      </label>
      <input class="input" type="tel" id="<?php echo esc_attr( $adf_id ); ?>-phone" name="adf_phone"
             placeholder="<?php esc_attr_e( 'Din telefon', 'advantage-dolda-fel' ); ?>"
             autocomplete="tel" required aria-describedby="<?php echo esc_attr( $adf_id ); ?>-phone-err">
      <span class="field__error" id="<?php echo esc_attr( $adf_id ); ?>-phone-err"><?php esc_html_e( 'Ange ett giltigt telefonnummer.', 'advantage-dolda-fel' ); ?></span>
    </div>
  </div>

  <div class="form__row form__row--2">
    <div class="field">
      <label for="<?php echo esc_attr( $adf_id ); ?>-email"><?php esc_html_e( 'E-post', 'advantage-dolda-fel' ); ?></label>
      <input class="input" type="email" id="<?php echo esc_attr( $adf_id ); ?>-email" name="adf_email"
             placeholder="<?php esc_attr_e( 'Din e-post', 'advantage-dolda-fel' ); ?>"
             autocomplete="email" aria-describedby="<?php echo esc_attr( $adf_id ); ?>-email-err">
      <span class="field__error" id="<?php echo esc_attr( $adf_id ); ?>-email-err"><?php esc_html_e( 'Ange en giltig e-postadress.', 'advantage-dolda-fel' ); ?></span>
    </div>

    <div class="field">
      <label for="<?php echo esc_attr( $adf_id ); ?>-motpart"><?php esc_html_e( 'Din motpart', 'advantage-dolda-fel' ); ?></label>
      <input class="input" type="text" id="<?php echo esc_attr( $adf_id ); ?>-motpart" name="adf_motpart"
             placeholder="<?php esc_attr_e( 'Din motpart', 'advantage-dolda-fel' ); ?>">
    </div>
  </div>

  <div class="field">
    <label for="<?php echo esc_attr( $adf_id ); ?>-subject"><?php esc_html_e( 'Ämne', 'advantage-dolda-fel' ); ?></label>
    <input class="input" type="text" id="<?php echo esc_attr( $adf_id ); ?>-subject" name="adf_subject"
           placeholder="<?php esc_attr_e( 'Ämne', 'advantage-dolda-fel' ); ?>">
  </div>

  <div class="field">
    <label for="<?php echo esc_attr( $adf_id ); ?>-message"><?php esc_html_e( 'Meddelande', 'advantage-dolda-fel' ); ?></label>
    <textarea class="textarea" id="<?php echo esc_attr( $adf_id ); ?>-message" name="adf_message" rows="6"
              placeholder="<?php esc_attr_e( 'Meddelande', 'advantage-dolda-fel' ); ?>"></textarea>
  </div>

  <?php
  // The no-JavaScript path posts to admin-post.php and comes back with a flag.
  // Rendering the result here means a visitor without JS still gets an answer.
  $adf_sent  = isset( $_GET['adf_sent'] );   // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display only.
  $adf_error = isset( $_GET['adf_error'] );  // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display only.
  ?>
  <p class="form-status<?php echo ( $adf_sent || $adf_error ) ? ' is-visible' : ''; ?>"
     data-form-status role="status" aria-live="polite"
     <?php echo $adf_sent ? 'data-state="success"' : ( $adf_error ? 'data-state="error"' : '' ); ?>>
    <?php
    if ( $adf_sent ) {
      esc_html_e( 'Tack. Din förfrågan har tagits emot och vi återkommer så snart som möjligt.', 'advantage-dolda-fel' );
    } elseif ( $adf_error ) {
      esc_html_e( 'Ditt meddelande kunde inte skickas. Ring + 46 8 20 21 40 eller mejla info@advantage.se.', 'advantage-dolda-fel' );
    }
    ?>
  </p>

  <button class="btn btn--gold btn--block u-mt-5" type="submit">
    <?php esc_html_e( 'Begär konsultation', 'advantage-dolda-fel' ); ?>
    <span class="icon icon--arrow" aria-hidden="true"></span>
  </button>
</form>
