<?php 
global $current_user;
global $social_options;
  global $social_friends_controller, $social_user;
  
  $permalink = get_permalink($social_options->directory_page_id);
  $param_char = ((preg_match("#\?#",$permalink))?'&':'?');

  if(!$user_search)
  {
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
   <table id="userfind">
   <tr>
   <td id="rightsize">
   <?php
    $fake_search_classes = (empty($search_query)?'':' social-hidden');
    $search_classes      = (empty($search_query)?' social-hidden':'');
  ?>
      <div id="social-fake-search-form" class="social-search-form<?php echo $fake_search_classes; ?>">
        <a href="javascript:social_show_search_form()"><div id="social-fake-search-input" class="social-board-fake-input"><?php _e("Search Users...", 'social'); ?></div></a>
      </div>
      <div id="social-search-form" class="social-search-form<?php echo $search_classes; ?>">
        <div id="usersize"><input type="text" id="social-search-input" onkeyup="javascript:social_search_directory( this.value )" class="social-board-input social-search-input" value="<?php echo $search_query; ?>" /></div>
      <?php /*?>  <a href="<?php echo $permalink; ?>" class="social-search-reset-button<?php echo $search_classes; ?>"><img src="<?php echo social_IMAGES_URL . "/remove.png"; ?>" alt="Reset" /></a><?php */?>
      </div>
  <?php
  }
?>
<div id="social-profile-results">
<p><strong><?php printf( __ngettext("%s User Was Found", "%s Users Were Found", $record_count, 'social'), number_format( (float)$record_count )); ?></strong></p>
<?php
  if($prev_page > 0)
  {
    ?>
      <div id="social_prev_page"><a href="<?php echo "{$permalink}{$param_char}mdp={$prev_page}{$search_params}"; ?>">&laquo; <?php _e('Previous Page', 'social'); ?></a></div>
    <?php
  }
  ?>
<table style="width:432px;">
<?php

  $avatar_thumb_size = 64;
  
  if(is_array($profiles))
  {
    foreach ($profiles as $key => $profile)
    { 
      $avatar_link = $profile->get_avatar($avatar_thumb_size);
      
      $full_name = $profile->screenname;
    
      if(!empty($search_query))
      {
        $full_name = preg_replace( "#({$search_query})#i", "<span class=\"social-search-match\">$1</span>", $full_name );
      }
?>
  <tr>
    <td valign="top" style="width: <?php echo $avatar_thumb_size; ?>px; vertical-align: top;"><a href="<?php echo $profile->get_profile_url(); ?>" style="font-size:14px;"><?php echo $avatar_link; ?></a></td>
    <td valign="top" style="padding: 0px 0px 0px 10px; vertical-align: top;"><h3 style="margin: 0px;"><a href="<?php echo $profile->get_profile_url(); ?>" style="font-size:14px;"><?php echo "{$full_name}"; ?></a></h3><?php echo $social_friends_controller->display_add_friend_button($social_user->id, $profile->id); ?><?php do_action( 'social-profile-list-name-display', $profile->id ); ?></td>
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
    <div id="social_prev_page"><a href="<?php echo "{$permalink}{$param_char}mdp={$next_page}{$search_params}"; ?>" style="font-size:14px;"><?php _e('Next Page', 'social'); ?> &raquo;</a></div>
  <?php
}
?>
</div>
</td>
</tr>
</table>
</td>
</table>
</div>
</div>