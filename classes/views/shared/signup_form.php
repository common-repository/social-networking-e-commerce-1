<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>

<body <?php body_class(); ?>>
<div class="regform">
<div id="formhead">
<div id="text_delete">
Registration Form</div>
</div>
<form name="registerform" id="registerform" action="" method="post">
<input type="hidden" id="social-process-form" name="social-process-form" value="Y" />
<div id="register"><p>
	<label><?php _e('Username', 'social'); ?>*:</label>
	<input type="text" name="user_login" id="user_login" class="input social_signup_input" value="<?php echo $user_login; ?>" size="20" tabindex="200" />
	</p>
<p>
	<label><?php _e('E-mail', 'social'); ?>*:</label>
	<input type="text" name="user_email" id="user_email" class="input social_signup_input" value="<?php echo $user_email; ?>" size="25" tabindex="300" />
</p>
<?php if(isset($social_options->field_visibilities['signup_page']['name'])) { ?>
  <p>
	  <label><?php _e('First Name', 'social'); ?>:</label>
	  <input type="text" name="user_first_name" id="user_first_name" class="input social_signup_input" value="<?php echo $user_first_name; ?>" size="20" tabindex="400" />
  </p>
  <p>
    <label><?php _e('Last Name', 'social'); ?>:</label>
    <input type="text" name="user_last_name" id="user_last_name" class="input social_signup_input" value="<?php echo $user_last_name; ?>" size="20" tabindex="500" />
  </p>
<?php } ?>
<?php if(isset($social_options->field_visibilities['signup_page']['url'])) { ?>
  <p>
    <label><?php _e('Website', 'social'); ?>:</label>
    <input type="text" name="social_user_url" id="social_user_url" value="<?php echo $social_user_url; ?>" class="input social_signup_input" size="20" tabindex="600"/>
  </p>
<?php } ?>
<?php if(isset($social_options->field_visibilities['signup_page']['location'])) { ?>
  <p>
    <label><?php _e('Location', 'social'); ?>:</label>
    <input type="text" name="social_user_location" id="social_user_location" value="<?php echo $social_user_location; ?>" class="input social_signup_input" size="20" tabindex="700" />
  </p>
<?php } ?>
<?php if(isset($social_options->field_visibilities['signup_page']['bio'])) { ?>
  <p>
    <label><?php _e('Bio', 'social'); ?>:</label>
    <textarea name="social_user_bio" id="social_user_bio" class="input social-growable social_signup_input" tabindex="800"><?php echo wptexturize($social_user_bio); ?></textarea>
  </p>
<?php } ?>  
<?php if(isset($social_options->field_visibilities['signup_page']['sex'])) { ?>
  <p>
    <label><?php _e('Gender', 'social'); ?>*:</label>&nbsp;<?php echo socialProfileHelper::sex_dropdown('social_user_sex', $social_user_sex, '', 900); ?>
  </p>
<?php } ?>

<?php if(isset($social_options->field_visibilities['signup_page']['password'])) { ?>
  <p>
    <label><?php _e('Password', 'social'); ?>:</label>
    <input type="password" name="social_user_password" id="social_user_password" class="input social_signup_input" tabindex="1000"/>
  </p>
  <p>
    <label><?php _e('Password Confirmation', 'social'); ?>:</label>
    <input type="password" name="social_user_password_confirm" id="social_user_password_confirm" class="input social_signup_input" tabindex="1100"/>
  </p></div>
<?php } else { ?>
	<p id="reg_passmail"><?php _e('A password will be e-mailed to you.', 'social'); ?></p>
<?php } ?>
<?php if($social_options->signup_captcha) { ?>
<?php
   $captcha_code = socialUtils::str_encrypt(socialUtils::generate_random_code(6));
?>
<p>
<label><?php _e('Enter Captcha Text', 'social'); ?>*:
<img src="<?php echo social_SCRIPT_URL; ?>&controller=captcha&action=display&width=120&height=40&code=<?php echo $captcha_code; ?>" /><br/>
<input id="security_code" name="security_code" style="width:120px" type="text" tabindex="1200" />
<input type="hidden" name="security_check" value="<?php echo $captcha_code; ?>">
</p>
<?php } ?>
  <?php do_action('social-user-signup-fields'); ?>

	<br class="clear" />
	<p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="social-share-button" value="<?php _e('Sign Up', 'social'); ?>" tabindex="60" /></p>
</form>
 <?php get_footer(); ?>
	</div></body></html>