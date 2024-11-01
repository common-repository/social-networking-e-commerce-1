<h3><?php _e('Request a Password Reset', 'social'); ?></h3>
<form name="social_forgot_password_form" id="social_forgot_password_form" action="" method="post">
	<p>
		<label><?php _e('Enter Your Username or Email Address', 'social'); ?><br/>
		<input type="text" name="social_user_or_email" id="social_user_or_email" class="input" value="<?php echo $social_user_or_email; ?>" tabindex="600" style="width: auto; min-width: 250px; font-size: 12px; padding: 4px;" /></label>
	</p>
	<p class="submit">
		<input type="submit" name="wp-submit" id="wp-submit" class="button-primary social-share-button" value="<?php _e('Request Password Reset', 'social'); ?>" tabindex="610" />
		<input type="hidden" name="social_process_forgot_password_form" value="true" />
	</p>
</form>