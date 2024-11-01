<?php global $social_user, $social_friend, $social_options; ?>
<?php $display_profile = ( $user->privacy == 'public' or 
                           socialUser::is_logged_in_and_an_admin() or 
                           socialUser::is_logged_in_and_visible() ); 
			global $social_friends_controller;	?>
     		  <?php
		   global $current_user;
      get_currentuserinfo();
		  if( socialUser::is_logged_in_and_visible() and 
        socialFriend::can_friend($current_user->display_name, $_GET['u']))
    {
      global $current_user, $social_friend;
	 ?>
	
	 <?php
      if($current_user->display_name == $_GET['u'] || $_GET['u']=='')
      {
		 
	   ?>
	    <div id="mainsidebarmenu">
	   <div id="sidemenu">
           <ul>
           <li><img src="<?php echo social_IMAGES_URL; ?>/wall.png" /><a href="<?php echo get_permalink($social_options->profile_page_id); ?>"><?php _e('Wall', 'social'); ?></a></li>
           <li><img src="<?php echo social_IMAGES_URL; ?>/info.png" /><a href="<?php echo get_permalink($social_options->profile_page_id); ?>"><?php _e('Info', 'social'); ?></a></li>
		   <li><img src="<?php echo social_IMAGES_URL; ?>/inbox.png" /><a href="<?php echo get_permalink($social_options->inbox_page_id); ?>"><?php _e('Inbox', 'social'); ?><?php echo $unread_count_str; ?></a></li>
           <li><img src="<?php echo social_IMAGES_URL; ?>/friends.png" /><a href="<?php echo get_permalink($social_options->friends_page_id); ?>"><?php _e('Friends', 'social'); ?></a></li>
		   <li><img src="<?php echo social_IMAGES_URL; ?>/frequest.png" /><a href="<?php echo get_permalink($social_options->friend_requests_page_id); ?>"><?php _e('Friend Requests', 'social'); ?></a></li>
		   <li><img src="<?php echo social_IMAGES_URL; ?>/ffriends.png" /><a href="<?php echo get_permalink($social_options->directory_page_id); ?>"><?php _e('Find Friends', 'social'); ?></a></li>
		   <li><img src="<?php echo social_IMAGES_URL; ?>/photo.png" /><a href="<?php echo get_permalink($social_options->photo_page_id); ?>"><?php _e('Photo', 'social'); ?></a></li>
        </ul>
		</div>
		<div id="linediff"></div>
		<div id="sidemenu1">
			<ul>
		<li><img src="<?php echo social_IMAGES_URL; ?>/arrow.png" /><a href="<?php echo get_permalink($social_options->product_page_id); ?>"><?php _e('Product Page', 'social'); ?></a></li>
		<li  id="chgcolor"><img src="<?php echo social_IMAGES_URL; ?>/arrow.png" /><a href="<?php echo get_permalink($social_options->checkout_page_id); ?>"><?php _e('Checkout', 'social'); ?></a></li>
		  <li><img src="<?php echo social_IMAGES_URL; ?>/arrow.png" /><a href="<?php echo get_permalink($social_options->purchase_history_page_id); ?>"><?php _e('Purchse History', 'social'); ?></a></li>
		  </ul>
		 </div>
		 </div>
            <?php }
			?>
			
			<div id="main-friend-grid">
            <div id="side-friend-grid"><div id="side-friend-grid1">Friends</div></div><?php echo $social_friends_controller->display_friends_grid($current_user->ID); ?>
</div>
</div>
			 <?php }
			 else
			 {
			 ?>
			  <div id="mainsidebarmenu">
			  <div id="main-friend-grid">
            <div id="side-friend-grid"><div id="side-friend-grid1">Friends</div></div><?php echo $social_friends_controller->display_friends_grid($_GET['u']); ?>
</div>
</div>
			 <?php
			 }
			 ?>