<?php global $social_user, $social_friend, $social_options; ?>
<?php $display_profile = ( $user->privacy == 'public' or 
                           socialUser::is_logged_in_and_an_admin() or 
                           socialUser::is_logged_in_and_visible() ); 
				?>

<table class="social-profile-table">
  <tr>
    <td valign="top" class="social-profile-table-col-1 social-valign-top">
      <table>
        <tr>
          <td>
            <?php echo $avatar; ?>
            <?php echo $social_friends_controller->display_add_friend_button($social_user->id, $user->id); ?>
            <?php echo do_action('social-profile-display',$user->id); ?>
          </td>
        </tr>
        <tr>
          <td valign="top" class="social-valign-top">
            <?php if($display_profile) { ?>
				<ul style="list-style-type: none;" class="sideul">
          <li><a href="<?php echo get_permalink($social_options->activity_page_id); ?>"><?php _e('Wall', 'social'); ?></a></li>
          <li><a href="<?php echo get_permalink($social_options->profile_page_id); ?>"><?php _e('Info', 'social'); ?></a></li>
		  <?php
		   global $current_user;
      get_currentuserinfo();
		  if( socialUser::is_logged_in_and_visible() and 
        socialFriend::can_friend($current_user->display_name, $_GET['u']))
    {
      global $current_user, $social_friend;
    
      if($current_user->display_name == $_GET['u'] || $_GET['u']=='')
      {
		 
	   ?> 
		  <li><a href="<?php echo get_permalink($social_options->inbox_page_id); ?>"><?php _e('Inbox', 'social'); ?><?php echo $unread_count_str; ?></a></li>
		  <?php } }?>
          <li><a href="<?php echo get_permalink($social_options->friends_page_id); ?>"><?php _e('Friends', 'social'); ?></a></li>
		  <li><a href="<?php echo get_permalink($social_options->photo_page_id); ?>"><?php _e('Photo', 'social'); ?></a></li>
        </ul>
		</td>
		<tr>
		<td>
            <?php } ?>
            <p><strong><?php _e('Friends', 'social'); ?>:</strong><div class="social-profile-friend-grid-wrap"><?php echo $social_friends_controller->display_friends_grid($user->id); ?></div></p>
          </td>
        </tr>
      </table>
    </td>