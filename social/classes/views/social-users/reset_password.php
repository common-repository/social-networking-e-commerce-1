<h3><?php _e('Enter your new password', 'social'); ?></h3>
<form name="social_reset_password_form" id="social_reset_password_form" action="" method="post">
  <p>
    <label><?php _e('Password', 'social'); ?>:<br/>
    <input type="password" name="social_user_password" id="social_user_password" class="input social_signup_input" tabindex="700"/></label>
  </p>
  <p>
    <label><?php _e('Password Confirmation', 'social'); ?>:<br />
    <input type="password" name="social_user_password_confirm" id="social_user_password_confirm" class="input social_signup_input" tabindex="710"/></label>
  </p>
	<p class="submit">
		<input type="submit" name="wp-submit" id="wp-submit" class="button-primary social-share-button" value="<?php _e('Reset Password', 'social'); ?>" tabindex="720" />
		<input type="hidden" name="action" value="social_process_reset_password_form" />
		<input type="hidden" name="social_screenname" value="<?php echo $social_screenname; ?>" />
  	<input type="hidden" name="social_key" value="<?php echo $social_key; ?>" />
	</p>
</form>