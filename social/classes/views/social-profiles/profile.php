<?php require( social_VIEWS_PATH . '/social-profiles/social_sidebar.php' ) ?>
    <div id="rightsidebar1">
		<div id="menuheader">
	  <ul>
	  <li><a href="<?php echo get_permalink($social_options->profile_page_id); ?>"><?php _e('Home', 'social'); ?></a> | </li>
     <li><a href="<?php echo get_permalink($social_options->profile_edit_page_id); ?>"><?php _e('Setting', 'social'); ?></a> | </li>
	 <li><a href="<?php echo wp_logout_url(get_permalink($social_options->login_page_id)); ?>"><?php _e('Logout', 'social'); ?></a></li>
	 </ul>
	</div>
	 <div id="bodycontent">
       
            <div class="social-profile-name"><?php echo $user->screenname; ?></div>
            <?php 
              if(!$display_profile)
                require( social_VIEWS_PATH . '/social-boards/private.php' );
            ?>
        
          <?php if($display_profile) { ?>
		  <div class="social-board"><?php echo $social_boards_controller->display($user->id); ?></div>
          <?php } ?>
       </div>
	   </div>