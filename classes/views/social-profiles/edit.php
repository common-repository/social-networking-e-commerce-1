<?php 
global $current_user;
global $social_options;
      get_currentuserinfo();
	  if($current_user->display_name == $_GET['u'] || $_GET['u']=='')
      {
	  $avatar = socialUtils::get_avatar( $current_user->ID, $size );
	  }
	  else
      {
	  $avatar = socialUtils::get_avatar( $_GET['u'], $size );
	  }
	  ?>
	 <div id="mainsidebar"> 
	<div id="avatar1">
            <a href="?page_id=<?php echo $social_options->profile_page_id; ?>"><?php echo $avatar; ?></a>
        </div>
<?php require( social_VIEWS_PATH . '/social-profiles/social_sidebar1.php' ) ?>
 <div id="rightsidebar">
		<div id="menuheader">
	  <ul>
	  <li><a href="<?php echo get_permalink($social_options->profile_page_id); ?>"><?php _e('Home', 'social'); ?></a> | </li>
     <li><a href="<?php echo get_permalink($social_options->profile_edit_page_id); ?>"><?php _e('Setting', 'social'); ?></a> | </li>
	 <li><a href="<?php echo wp_logout_url(get_permalink($social_options->login_page_id)); ?>"><?php _e('Logout', 'social'); ?></a></li>
	 </ul>
	</div>
	 <div id="bodycontent">
	  <table id="tablebordermsg1">
        <tr>
          <td id="rightsize">
		  <?php global $social_options; ?>
<div class="profile-edit-form">
<form action="" enctype="multipart/form-data" method="post">
  <input type="hidden" name="action" id="action" value="process_form" />
  <input type="hidden" name="<?php echo $social_user->id_str; ?>" id="<?php echo $social_user->id_str; ?>" value="<?php echo $social_user->id; ?>" />
  <input type="hidden" name="<?php echo $social_user->screenname_str; ?>" id="<?php echo $social_user->screenname_str; ?>" value="<?php echo $social_user->screenname; ?>" />
  <h3><?php _e('Information', 'social'); ?>:</h3>
  <table width="100%" class="profile-edit-table">
    <?php if(isset($social_options->field_visibilities['profile_edit']['name'])) { ?>
    <tr>
      <td valign="top"><?php _e('First Name', 'social'); ?>:</td>
      <td valign="top"><input type="text" name="<?php echo $social_user->first_name_str; ?>" id="<?php echo $social_user->first_name_str; ?>" value="<?php echo $social_user->first_name; ?>" class="social-profile-edit-field" /></td>
    </tr>
    <tr>
      <td valign="top"><?php _e('Last Name', 'social'); ?>:</td>
      <td valign="top"><input type="text" name="<?php echo $social_user->last_name_str; ?>" id="<?php echo $social_user->last_name_str; ?>" value="<?php echo $social_user->last_name; ?>" class="social-profile-edit-field" /></td>
    </tr>
    <?php } ?>
    <tr>
      <td valign="top"><?php _e('Email', 'social'); ?>:</td>
      <td valign="top"><input type="text" name="<?php echo $social_user->email_str; ?>" id="<?php echo $social_user->email_str; ?>" value="<?php echo $social_user->email; ?>" class="social-profile-edit-field" /></td>
    </tr>
    <?php if(isset($social_options->field_visibilities['profile_edit']['bio'])) { ?>
    <tr>
      <td valign="top"><?php _e('Bio', 'social'); ?>:</td>
      <td valign="top"><textarea name="<?php echo $social_user->bio_str; ?>" id="<?php echo $social_user->bio_str; ?>" class="social-profile-edit-field social-growable"><?php echo stripslashes($social_user->bio); ?></textarea></td>
    </tr>
    <?php } ?>
    <?php if(isset($social_options->field_visibilities['profile_edit']['birthday'])) { ?>
    <tr>
      <td valign="top"><?php _e('Birthday', 'social'); ?>:</td>
      <td valign="top"><input type="text" name="<?php echo $social_user->birthday_str; ?>" id="<?php echo $social_user->birthday_str; ?>" value="<?php echo $social_user->birthday; ?>" class="social-datepicker social-profile-edit-field" /></td>
    </tr>
    <?php } ?>
    <?php if(isset($social_options->field_visibilities['profile_edit']['url'])) { ?>
    <tr>
      <td valign="top"><?php _e('URL', 'social'); ?>:</td>
      <td valign="top"><input type="text" name="<?php echo $social_user->url_str; ?>" id="<?php echo $social_user->url_str; ?>" value="<?php echo $social_user->url; ?>" class="social-profile-edit-field" /></td>
    </tr>
    <?php } ?>
    <?php if(isset($social_options->field_visibilities['profile_edit']['location'])) { ?>
    <tr>
      <td valign="top"><?php _e('Location', 'social'); ?>:</td>
      <td valign="top"><input type="text" name="<?php echo $social_user->location_str; ?>" id="<?php echo $social_user->location_str; ?>" value="<?php echo $social_user->location; ?>" class="social-profile-edit-field" /></td>
    </tr>
    <?php } ?>
    <?php if(isset($social_options->field_visibilities['profile_edit']['sex'])) { ?>
    <tr>
      <td valign="top"><?php _e('Gender', 'social'); ?>:</td>
      <td valign="top"><?php echo socialProfileHelper::sex_dropdown($social_user->sex_str, $social_user->sex); ?></td>
    </tr>
    <?php } ?>
    <?php if(isset($social_options->field_visibilities['profile_edit']['password'])) { ?>
    <tr>
      <td valign="top"><?php _e('Password', 'social'); ?>:</td>
      <td valign="top"><input type="password" name="<?php echo $social_user->password_str; ?>" id="<?php echo $social_user->password_str; ?>" class="social-profile-edit-field" /></td>
    </tr>
    <tr>
      <td valign="top"><?php _e('Password Confirmation', 'social'); ?>:</td>
      <td valign="top"><input type="password" name="<?php echo $social_user->password_confirm_str; ?>" id="<?php echo $social_user->password_confirm_str; ?>" class="social-profile-edit-field" /></td>
    </tr>
    <?php } ?>
    <tr>
      <td valign="top"><?php _e('Avatar', 'social'); ?>:</td>
      <td valign="top">
        <input type="file" name="<?php echo $social_user->avatar_str; ?>" id="<?php echo $social_user->avatar_str; ?>" class="social-profile-edit-field" /><br/>
          <?php require(social_VIEWS_PATH . "/social-profiles/edit_avatar.php"); ?>
      </td>
    </tr>
    <?php do_action('social-edit-user-fields'); ?>
  </table>
  <h3><?php _e('Privacy', 'social'); ?>:</h3>
  <div><?php echo socialProfileHelper::privacy_dropdown($social_user->privacy_str, $social_user->privacy); ?></div>
  <h3><?php _e('Notification Settings', 'social'); ?>:</h3>
  <table width="100%" class="profile-edit-table">
    <?php
    foreach ($social_options->notification_types as $ntype => $settings)
    {
      ?>
      <tr>
        <td width="5%" valign="top"><input type="checkbox" name="<?php echo "{$social_user->hide_notifications_str}[$ntype]"; ?>" id="<?php echo "{$social_user->hide_notifications_str}[$ntype]"; ?>"<?php socialAppHelper::value_is_checked_with_array($social_user->hide_notifications_str, $ntype, $social_user->hide_notifications[$ntype]); ?> /></td>
        <td width="95%" valign="top"><?php printf(__('Don\'t Send Me "%s" Notifications','social'), $settings['name']); ?></td>
      </tr> 
      <?php
    }
    ?>
  </table>
  <br/>
  <input type="submit" class="social-share-button" name="Update" value="<?php _e('Update', 'social'); ?>" />
</form>
</div>
</td>
</tr>
</table>
</td>
</table>
</div>
</div>