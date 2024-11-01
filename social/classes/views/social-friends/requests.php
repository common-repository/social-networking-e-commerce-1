<?php 
global $current_user;
global $social_options;
      get_currentuserinfo();
	  $avatar = socialUtils::get_avatar( $current_user->ID, $size );
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
		  <div class="social-friend-requests">
<table>
<?php
  if(count($requests) > 0)
  {
    foreach ($requests as $key => $request)
    {
      $requestor = socialUser::get_stored_profile_by_id($request->user_id);

      if($requestor)
      {
        $avatar = $requestor->get_avatar(64);
?>
  <tr class="social-friend-request" id="request-<?php echo $request->id; ?>">
    <td valign="top"><?php echo $avatar; ?></td>
    <td valign="top" style="padding: 0px 0px 0px 10px; vertical-align: top;"><?php printf(__('%s wants to be your friend.', 'social'), '<a href="' . $requestor->get_profile_url() . '">' . $requestor->screenname . '</a>'); ?><br/><a href="javascript:social_accept_friend_request( '<?php echo social_SCRIPT_URL; ?>', <?php echo $request->id; ?>, '<?php echo $requestor->screenname; ?>' )"><?php _e('Accept', 'social'); ?></a>&nbsp;|&nbsp;<a href="javascript:social_ignore_friend_request( '<?php echo social_SCRIPT_URL; ?>', <?php echo $request->id; ?> )"><?php _e('Ignore', 'social'); ?></a></td>
  </tr>
<?php
      }
    }
  }
  else
  {
    ?>
      <p><?php _e("You don't currently have any friend requests.", 'social'); ?></p>
    <?php
  }
?>  
</table>
</div>
</td>
</tr>
</table>
</td>
</table>
</div>
</div>