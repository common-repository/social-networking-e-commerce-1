 <form name="loginform" id="loginform" action="<?php echo $login_page; ?>" method="post">
	<p style="margin:0 auto; width:360px;">
		<label style="float:left;"><strong><?php _e('Username', 'social'); ?></strong>
		<input type="text" name="log" id="user_login" class="input" value="<?php echo (isset($_POST['log'])?$_POST['log']:''); ?>" tabindex="500" style="width: auto; min-width: 250px; font-size: 12px; padding: 4px; float:right; margin-left:38px; background-color:#fff;" /></label><br />
		<label><strong><?php _e('Password', 'social'); ?></strong>
		<input type="password" name="pwd" id="user_pass" class="input" value="<?php echo (isset($_POST['pwd'])?$_POST['pwd']:''); ?>" tabindex="510" style="width: auto; min-width: 250px; line-height: 12px; padding: 4px; float:right;" /></label><br/>
		
	  <label style="margin-left:20px; width:100px; float:left;"><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="520" style="width: 15px; float:left;"<?php echo (isset($_POST['rememberme'])?' checked="checked"':''); ?> /> <?php _e('Remember Me', 'social'); ?></label>
	</p><br /><br />
	<p class="submit">
		<input type="submit" name="wp-submit" id="wp-submit" class="button-primary social-share-button" value="<?php _e('Log In', 'social'); ?>" tabindex="530" style="float:left;" />
		<input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
		<input type="hidden" name="testcookie" value="1" />
		<input type="hidden" name="social_process_login_form" value="true" />
	</p>
</form>
<p style="font-size: 10px" class="social-login-actions">
<?php if(get_option('users_can_register')) {
?>
    <a href="<?php echo $signup_url; ?>" style="float:right;"><?php _e('Register', 'social'); ?></a>&nbsp;|
<?php
      }
?>
<a href="<?php echo $forgot_password_url; ?>" style="float: left; margin-left: 5px; margin-top: -23px;"><?php _e('Lost Password?', 'social'); ?></a>
</p>