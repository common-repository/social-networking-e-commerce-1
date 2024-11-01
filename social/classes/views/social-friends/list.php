<?php
session_start();
global $social_options;
  if(!$user_search)
  {
global $current_user;
      get_currentuserinfo();
	  if($current_user->display_name == $_GET['u'] || $_GET['u']=='')
      {
	  $avatar = socialUtils::get_avatar( $current_user->ID, $size );
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
		  
<div class="social-friend-list-header">
  <div class="social-profile-image-wrap social-friend-list-profile-image-wrap">
    <span class="social-alignleft"><?php echo $user_avatar; ?></span>
    <p class="social-friend-list-profile-text"><?php printf(__("%s's Friends", 'social'), $user->screenname); ?>:</p>
  </div>
</div>
<div id="social-fake-search-form" class="social-search-form">
  <a href="javascript:social_show_search_form()"><div id="social-fake-search-input" class="social-board-fake-input"><?php _e("Search Friends...", 'social'); ?></div></a>
</div>
<div id="social-search-form" class="social-search-form social-hidden">
  <input type="text" id="social-search-input" onkeyup="javascript:social_search_friends( this.value, '<?php echo $page_params; ?>' )" class="social-search-input social-board-input" />
</div>
  <?php
  }
?>
<div id="social-friends-directory" class="friends-list">
<p><strong><?php printf( __ngettext("%s Friend Was Found", "%s Friends Were Found", $record_count, 'social'), number_format( (float)$record_count )); ?></strong></p>
  <?php
  if($prev_page > 0)
  {
    ?>
      <div id="social_prev_page"><a href="<?php echo "{$friends_page_url}{$param_char}mdp={$prev_page}{$page_params}"; ?>">&laquo; <?php _e('Previous Page', 'social'); ?></a></div>
    <?php
  }
  ?>
<table style="width: 100%;">
<?php
if(is_array($friends))
{
  $thumb_size = 64;
  foreach ($friends as $key => $friend)
  {
    $avatar_link = $friend->get_avatar($thumb_size);
    
    $full_name = $friend->screenname;

    if(!empty($search_query))
    {
      // highlight search term if present
      $full_name = preg_replace( "#({$search_query})#i", "<span class=\"social-search-match\">$1</span>", $full_name );
    }
?>
  <tr id="social-friend-<?php echo $friend->id; ?>">
    <td valign="top" style="width: <?php echo $thumb_size; ?>px; vertical-align: top;"><a href="<?php echo $friend->get_profile_url(); ?>" style="font-size:14px;"><?php echo $avatar_link; ?></a></td>
    <td valign="top" style="padding: 0px 0px 0px 10px; vertical-align: top;"><h3 style="margin: 0px;"><a href="<?php echo $friend->get_profile_url(); ?>" style="font-size:14px;"><?php echo "{$full_name}"; ?></a></h3><?php do_action( 'social-profile-list-name-display', $friend->id ); ?>
    <?php
    if($social_user->id == $user->id and socialFriend::can_delete_friend($user->id, $friend->id))
    {
    ?>
      <a href="javascript:social_delete_friend('<?php echo social_SCRIPT_URL; ?>',<?php echo $user->id; ?>,<?php echo $friend->id; ?> )" style="font-size:14px;"><?php _e('Delete', 'social'); ?></a>
      
    <?php
    do_action('social-friend-row', $friend, $user);
    }
    ?></td>
  </tr>
<?php
  }
}
?>  
</table>
<?php
if($next_page > 0)
{
  ?>
    <div id="social_prev_page"><a href="<?php echo "{$friends_page_url}{$param_char}mdp={$next_page}{$page_params}"; ?>"><?php _e('Next Page', 'social'); ?> &raquo;</a></div>
  <?php
}
do_action('social-friend-list-page');
?>
</div>
</td>
</tr>
</table>
</td>
</table>
</div>
</div>