<div class="wrap">
<h2 id="social_title" style="margin: 10px 0px 0px 0px; padding: 0px 0px 0px 56px; height: 48px; background: url(<?php echo social_URL . "/images/social_48.png"; ?>) no-repeat"><?php _e('social: Options', 'social'); ?></h2>
<br/>

<form name="social_options_form" method="post" action="">
<input type="hidden" name="action" value="process-form">
<?php wp_nonce_field('update-options'); ?>

<h3><?php _e('social Pages', 'social'); ?>:</h3>
<span class="description"><?php printf(__('Before you can get going with social, you must configure where social pages on your website will appear. You\'ll want to %1$screate a new page%2$s for each of these pages that social needs to work. You should give your page a title and optionally put some content into the page ... just know that once you set the page up here, the page\'s content will not display.', 'social'), '<a href="page-new.php">', '</a>'); ?></span>
<table class="form-table">
  <tr class="form-field">
    <td valign="top" style="text-align: right; width: 150px;"><?php _e('Profile Page', 'social'); ?>*: </td>
    <td style="width: 150px;">
      <?php socialOptionsHelper::wp_pages_dropdown( $social_options->profile_page_id_str, $social_options->profile_page_id, __("Profile") )?>
    </td>
    <td valign="top" style="text-align: right; width: 150px;"><?php _e('Activity Page', 'social'); ?>*: </td>
    <td style="width: 150px;">
      <?php socialOptionsHelper::wp_pages_dropdown( $social_options->activity_page_id_str, $social_options->activity_page_id, __("Activity") )?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="form-field">
    <td valign="top" style="text-align: right;"><?php _e('Profile Edit Page', 'social'); ?>*: </td>
    <td>
      <?php socialOptionsHelper::wp_pages_dropdown( $social_options->profile_edit_page_id_str, $social_options->profile_edit_page_id, __("Account") )?>
    </td>
    <td valign="top" style="text-align: right;"><?php _e('Directory Page', 'social'); ?>: </td>
    <td>
      <?php socialOptionsHelper::wp_pages_dropdown( $social_options->directory_page_id_str, $social_options->directory_page_id, __("Find Friends"), true )?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="form-field">
    <td valign="top" style="text-align: right;"><?php _e('Friends Page', 'social'); ?>*: </td>
    <td>
      <?php socialOptionsHelper::wp_pages_dropdown( $social_options->friends_page_id_str, $social_options->friends_page_id, __("Friends") )?>
    </td>
    <td valign="top" style="text-align: right;"><?php _e('Login Page', 'social'); ?>: </td>
    <td>
      <?php socialOptionsHelper::wp_pages_dropdown( $social_options->login_page_id_str, $social_options->login_page_id, __("Login"), true )?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="form-field">
    <td valign="top" style="text-align: right;"><?php _e('Friend Requests Page', 'social'); ?>*: </td>
    <td><?php socialOptionsHelper::wp_pages_dropdown( $social_options->friend_requests_page_id_str, $social_options->friend_requests_page_id, __("Friends Request") )?></td>
    <td valign="top" style="text-align: right;"><?php _e('Signup Page', 'social'); ?>: </td>
    <td><?php socialOptionsHelper::wp_pages_dropdown( $social_options->signup_page_id_str, $social_options->signup_page_id, __("Signup"), true )?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top" style="text-align: right;"><?php _e('Inbox Page', 'social'); ?>*: </td>
    <td><?php socialOptionsHelper::wp_pages_dropdown( $social_options->inbox_page_id_str, $social_options->inbox_page_id, __("Inbox") )?></td>
    <td valign="top" style="text-align: right;"><?php _e('Product Page', 'social'); ?>*: </td>
    <td><?php socialOptionsHelper::wp_pages_dropdown( $social_options->product_page_id_str, $social_options->product_page_id, __("Product Page") )?></td>
     <td>&nbsp;</td>
	 </tr>
	 <tr>
    <td valign="top" style="text-align: right;"><?php _e('Checkout', 'social'); ?>*: </td>
    <td><?php socialOptionsHelper::wp_pages_dropdown( $social_options->checkout_page_id_str, $social_options->checkout_page_id, __("Checkout") )?></td>
    <td valign="top" style="text-align: right;"><?php _e('Purchase History', 'social'); ?>*: </td>
    <td><?php socialOptionsHelper::wp_pages_dropdown( $social_options->purchase_history_page_id_str, $social_options->purchase_history_page_id, __("Purchase History") )?></td>
     <td>&nbsp;</td>
	 </tr>
	  <tr>
    <td valign="top" style="text-align: right;"><?php _e('Photo', 'social'); ?>*: </td>
    <td><?php socialOptionsHelper::wp_pages_dropdown( $social_options->photo_page_id_str, $social_options->photo_page_id, __("Photo") )?></td>
	</tr>
</table>

<?php do_action('social_option_pages'); ?>

<h4><?php _e('Profile Options', 'social'); ?>:</h4>
<div class="social-options-pane">
<p><label for="<?php echo $social_options->pretty_profile_urls_str; ?>"><input type="checkbox" name="<?php echo $social_options->pretty_profile_urls_str; ?>" id="<?php echo $social_options->pretty_profile_urls_str; ?>"<?php echo (($social_options->pretty_profile_urls)?' checked="checked"':''); ?>/>&nbsp;<?php _e('Pretty Profile Urls','social'); ?></label><br/>
<span class="description"><?php _e('When checked, Pretty Profile Urls will allow users to type their screenname following your site\'s domain name for their url. Note, if you do not have Apache rewrite functioning and have not selected something other than "Default" under your General Permalink settings, this will not work.', 'social'); ?></span></p>
<p><label for="<?php echo $social_options->signup_spam_email_protection_str; ?>"><input type="checkbox" name="<?php echo $social_options->signup_spam_email_protection_str; ?>" id="<?php echo $social_options->signup_spam_email_protection_str; ?>"<?php echo (($social_options->signup_spam_email_protection)?' checked="checked"':''); ?>/>&nbsp;<?php _e('User Registration Email Spam Protection','social'); ?></label><br/>
<span class="description"><?php _e('When checked, social will use advanced email lookup techniques to validate the user signup to prevent against user registration spam.', 'social'); ?></span></p>
<p><label for="<?php echo $social_options->signup_robot_protection_str; ?>"><input type="checkbox" name="<?php echo $social_options->signup_robot_protection_str; ?>" id="<?php echo $social_options->signup_robot_protection_str; ?>"<?php echo (($social_options->signup_robot_protection)?' checked="checked"':''); ?>/>&nbsp;<?php _e('Block Robots from Registering as Users','social'); ?></label><br/>
<span class="description"><?php _e('When checked, social will attempt to identify the user as legitimate or as a robot (computer program) if it\'s a robot then registration will be prevented.', 'social'); ?></span></p>
<p><label for="<?php echo $social_options->signup_captcha_str; ?>"><input type="checkbox" name="<?php echo $social_options->signup_captcha_str; ?>" id="<?php echo $social_options->signup_captcha_str; ?>"<?php echo (($social_options->signup_captcha)?' checked="checked"':''); ?>/>&nbsp;<?php _e('Require Captcha On User Signup Page','social'); ?></label><br/>
<span class="description"><?php _e('When checked, social will display an image captcha that the user will be required to complete in order to successfully register.', 'social'); ?></span></p>
<p><label for="<?php echo $social_options->prevent_admin_access_str; ?>"><input type="checkbox" name="<?php echo $social_options->prevent_admin_access_str; ?>" id="<?php echo $social_options->prevent_admin_access_str; ?>"<?php echo (($social_options->prevent_admin_access)?' checked="checked"':''); ?>/>&nbsp;<?php _e('Prevent Subscribers Access to the WordPress Admin','social'); ?></label><br/>
<span class="description"><?php _e('When checked, social will redirect non-admin users to their activity page if attempting to go anywhere within /wp-admin.', 'social'); ?></span></p>
<p><label for="<?php echo $social_options->show_powered_by_str; ?>"><input name="<?php echo $social_options->show_powered_by_str; ?>" type="checkbox" id="<?php echo $social_options->show_powered_by_str; ?>"<?php echo (($social_options->show_powered_by)?' checked="checked"':''); ?>/>&nbsp;<?php _e('Show Powered by social link in sidebar','social'); ?></label><br/>
<span class="description"><?php _e('When unchecked, it will remove the social Powered by in sidebar.', 'social'); ?></span></p>
</div>

<h4><?php _e('Default Friends', 'social'); ?>:</h4>
<div class="social-options-pane">
<span class="description"><?php _e('These Users will be added as a friends to all new signups.', 'social'); ?></span>
  <table class="form-table social-default-friends-table" style="width: auto;">
<?php

  if(count($social_options->default_friends) > 0)
  {
    foreach($social_options->default_friends as $default_friend)
    {
      $default_friend = (int)$default_friend;
      if($default_friend and !empty($default_friend))
        $this->display_default_friend_drop_down($default_friend);
    }
  }
  
?>
  </table>
  <p><a href="javascript:social_add_default_user();" class="button">+ <?php _e('Add a Default Friend', 'social'); ?></a></p>
</div>

<h4><?php _e('Invisible Users', 'social'); ?>:</h4>
<div class="social-options-pane">
<span class="description"><?php _e('Any users checked below will not be visible to social. They won\'t have a profile page, friends, be listed in the directory or show up anywhere in social.', 'social'); ?></span>
<p><?php socialOptionsHelper::users_multiselect($social_options->invisible_users_str . "[]", $social_options->invisible_users); ?><br/><span class="description"><?php _e('Hold down Control Key (the Command Key if you\'re on a Mac) or the Shift Key to select multiple users.', 'social'); ?></span></p>
</div>

<h4><?php _e('Field Display Options', 'social'); ?>:</h4>
<div class="social-options-pane">
<span class="description"><?php _e("Configure the fields you'd like your users to see, and if they will be able to display them on their profiles. <code>Public</code> indicates that the field will be available to the users and that the value they enter into it will show up on their public profiles. <code>Private</code> indicates that the field will be available to the users but the value they enter into it will not show up on their public profiles. <code>Hidden</code> indicates that this field won't be visible to the users or on their public profiles.", 'social'); ?></span>
<table class="form-table">
  <tr class="form-field">
    <td valign="top"><?php _e('Show Name Fields', 'social'); ?>: </td>
    <td valign="top"><?php socialOptionsHelper::display_field_visibility_buttons('name'); ?></td>
  </tr>
  <tr class="form-field">
    <td valign="top"><?php _e('Show Website Field', 'social'); ?>: </td>
    <td valign="top"><?php socialOptionsHelper::display_field_visibility_buttons('url'); ?></td>
  </tr>
  <tr class="form-field">
    <td valign="top"><?php _e('Show Location Field', 'social'); ?>: </td>
    <td valign="top"><?php socialOptionsHelper::display_field_visibility_buttons('location'); ?></td>
  </tr>
  <tr class="form-field">
    <td valign="top"><?php _e('Show Bio Field', 'social'); ?>: </td>
    <td valign="top"><?php socialOptionsHelper::display_field_visibility_buttons('bio'); ?></td>
  </tr>
  <tr class="form-field">
    <td valign="top"><?php _e('Show Birthday Field', 'social'); ?>: </td>
    <td valign="top"><?php socialOptionsHelper::display_field_visibility_buttons('birthday'); ?></td>
  </tr>
  <tr class="form-field">
    <td valign="top"><?php _e('Show Gender Field', 'social'); ?>: </td>
    <td valign="top"><?php socialOptionsHelper::display_field_visibility_buttons('sex'); ?></td>
  </tr>
  <tr class="form-field">
    <td valign="top"><?php _e('Show Password Field', 'social'); ?>: </td>
    <td valign="top"><?php socialOptionsHelper::display_field_visibility_buttons('password', false, true); ?></td>
  </tr>
</table>

<?php do_action('social_custom_fields'); ?>
</div>

<h4><?php _e('Mail Options', 'social'); ?>:</h4>
<div class="social-options-pane">
<span class="description"><?php _e("Configure the way mail is sent from social. This setting will affect how all mail throughout WordPress is sent. If your website is hosted on Windows then you'll probably need to set this to SMTP and enter your credentials. <strong>Note:</strong> If your mail already works and you don't know what this setting means then you should probably leave it alone.", 'social'); ?></span>
  <p><?php socialOptionsHelper::mailer_dropdown( 'type', $social_options->mailer['type'] ); ?></p>
  <p id="social-sendmail-form" class="social-options-pane social-hidden">
    <?php _e('Sendmail Path', 'social'); ?>:&nbsp;
    <?php echo socialOptionsHelper::mailer_input( 'sendmail-path', $social_options->mailer['sendmail-path'], 'form-field'); ?>
  </p>
  <table id="social-smtp-form" class="social-options-pane social-hidden">
    <tr>
      <td><?php _e('SMTP Host', 'social'); ?>:&nbsp;</td>
      <td><?php echo socialOptionsHelper::mailer_input( 'smtp-host', $social_options->mailer['smtp-host'], 'form-field'); ?></td>
    </tr>
    <tr>
      <td><?php _e('SMTP Port', 'social'); ?>:&nbsp;</td>
      <td><?php echo socialOptionsHelper::mailer_input( 'smtp-port', $social_options->mailer['smtp-port'], 'form-field'); ?></td>
    </tr>
    <tr>
      <td><?php _e('SMTP Encryption', 'social'); ?>:&nbsp;</td>
      <td><?php echo socialOptionsHelper::smtp_encryption_dropdown( 'smtp-secure', $social_options->mailer['smtp-secure'], 'form-field'); ?></td>
    </tr>
    <tr>
      <td><?php _e('SMTP Username', 'social'); ?>:&nbsp;</td>
      <td><?php echo socialOptionsHelper::mailer_input( 'smtp-username', $social_options->mailer['smtp-username'], 'form-field'); ?></td>
    </tr>
    <tr>
      <td><?php _e('SMTP Password', 'social'); ?>:&nbsp;</td>
      <td><?php echo socialOptionsHelper::mailer_input( 'smtp-password', $social_options->mailer['smtp-password'], 'form-field', 'password'); ?></td>
    </tr>
  </table>
</div>
<script type="text/javascript">
  social_mailer_options();
</script>
<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'social') ?>" />
</p>

</form>

<p><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>&action=add_default_friends_to_all_users"><?php _e('Add Default Friends to Existing Users', 'social'); ?></a></p>
</div>
