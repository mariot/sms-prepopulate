<?php

/**
 * @package SMS_Prepopulate
 * @version 1.0
 */
/*
Plugin Name: SMS Prepopulate
Description: Create an SMS link that pre-populates the SMS message with a standard message, plus the page title.
Author: Mariot Tsitoara
Version: 1.0
*/

function html_form_code() {
  $message = get_option('smsprepopulate_options_prefix').' '.lcfirst(get_the_title()).get_option('smsprepopulate_options_suffix');
  $linkText = get_option('smsprepopulate_options_linktext') ?: 'Send SMS';
	?>
	<a href="sms:?&amp;body=<?php echo $message; ?>"
      data-rel="external"
      onclick="ga('send', 'event', 'link', 'click', '<?php echo $message; ?>');">
      <?php echo $linkText; ?>
  </a>
  <?php
}

function cf_shortcode() {
    ob_start();
    html_form_code();

    return ob_get_clean();
}

add_shortcode( 'sms-prepopulate', 'cf_shortcode' );

function my_enqueued_assets() {
  wp_register_script( 'my_plugin_script', plugins_url('/sms-prepopulate.js', __FILE__), array('jquery'));
  wp_enqueue_script( 'my_plugin_script' );
}
add_action( 'wp_enqueue_scripts', 'my_enqueued_assets' );

function smsprepopulate_settings_page() {
  if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
      <h1><?= esc_html(get_admin_page_title()); ?></h1>
      <form action="options.php" method="post">
        <?php
        settings_fields('smsprepopulate_options');
        do_settings_sections('smsprepopulate');
        ?>
        <table class="form-table">
          <tr valign="top">
          <th scope="row">Prefix</th>
          <td><input type="text" name="smsprepopulate_options_prefix" value="<?php echo esc_attr( get_option('smsprepopulate_options_prefix') ); ?>" /></td>
          </tr>

          <tr valign="top">
          <th scope="row">Suffix</th>
          <td><input type="text" name="smsprepopulate_options_suffix" value="<?php echo esc_attr( get_option('smsprepopulate_options_suffix') ); ?>" /></td>
          </tr>

          <tr valign="top">
          <th scope="row">Link Text</th>
          <td><input type="text" name="smsprepopulate_options_linktext" value="<?php echo esc_attr( get_option('smsprepopulate_options_linktext') ); ?>" /></td>
          </tr>
      </table>
      <?php
      submit_button('Save');
      ?>
      </form>
    </div>
    <?php
}

function smsprepopulate_menu() {
	add_menu_page('SMS Prepopulate', 'SMS Prepopulate', 'manage_options', 'smsprepopulate-settings', 'smsprepopulate_settings_page', 'dashicons-admin-generic');
}

function smsprepopulate_settings() {
  register_setting('smsprepopulate_options', 'smsprepopulate_options_prefix');
  register_setting('smsprepopulate_options', 'smsprepopulate_options_suffix');
  register_setting('smsprepopulate_options', 'smsprepopulate_options_linktext');
}

add_action('admin_menu', 'smsprepopulate_menu');
add_action('admin_init', 'smsprepopulate_settings' );

?>
