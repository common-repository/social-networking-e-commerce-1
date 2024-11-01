<html>
	<head>
		<!--Make sure page contains valid doctype at the very top!-->
		<link rel="stylesheet" href="<?php echo social_CSS_URL; ?>/lightbox.css" type="text/css" media="screen" />
	
	<script src="<?php echo social_JS_URL ;?>/prototype.js" type="text/javascript"></script>
	<script src="<?php echo social_JS_URL ;?>/scriptaculous.js?load=effects" type="text/javascript"></script>
	<script src="<?php echo social_JS_URL ;?>/lightbox.js" type="text/javascript"></script>
	
	
	<style type="text/css">
		body{ color: #333; font: 13px 'Lucida Grande', Verdana, sans-serif;	}
	</style>
    
	
	 
	</head>
	<?php global $social_options; 
$bloginfo = get_bloginfo( 'wpurl', $filter );
?>
<?php if( $page <= 1 and !$public ) { ?>
<div id="social-profile-tab-control">
<ul>
  <li id="social-board-tab-button" class="social-active-profile-tab"><a href="javascript:social_set_active_tab('board');"><span><?php _e('Board', 'social'); ?></span></a></li>
  <li id="social-info-tab-button"><a href="javascript:social_set_active_tab('info');"><span><?php _e('Info', 'social'); ?></span></a></li>
  <?php if($current_user->display_name != $_GET['u'] && $_GET['u']!='')
      {
	  ?>
	    <li id="social-photo-tab-button"><a href="javascript:social_set_active_tab('photo');"><span><?php _e('Photo', 'social'); ?></span></a></li>
	  <?php
	  } ?>
</ul>
</div>
<?php } ?>

<div id="social-board-tab" class="social-profile-tab">
<?php

if( $page <= 1 and 
    socialUser::is_logged_in_and_visible() and
    ( ($owner_id==$author_id) or
      $social_friend->is_friend($owner_id, $author_id) ) )
{
  ?>
  <div id="social-fake-board-post-form" class="social-post-form">
    <a href="javascript:social_show_board_post_form()"><div id="social-fake-board-post-input" class="social-board-fake-input"><?php _e("What's on your mind?", 'social'); ?></div></a>
  </div>
  <div id="social-board-post-form" class="social-post-form social-growable-hidden">
      <textarea id="social-board-post-input" class="social-board-input social-growable"></textarea>
   <div id="social-post-actions"><?php do_action('social-post-actions', $user->id, $social_user->id); ?></div>
          <input type="submit" class="social-share-button" id="social-board-post-button" onClick="javascript:social_post_to_board( '<?php echo social_SCRIPT_URL; ?>', <?php echo $owner_id; ?>, <?php echo $author_id; ?>, document.getElementById('social-board-post-input').value, '<?php echo (($public)?'activity':'boards'); ?>');" name="Share" value="<?php _e('Share', 'social'); ?>"/></div>
   <?php
}
?>
  <?php
    require_once(social_MODELS_PATH . "/socialUser.php");
    foreach ($board_posts as $board_post)
    {
      $author = socialUser::get_stored_profile_by_id($board_post->author_id);
      $owner  = socialUser::get_stored_profile_by_id($board_post->owner_id);
      
      if($author and $owner)
        $this->display_board_post($board_post,$public);
    }
  ?>
  <?php if( count($board_posts) >= $page_size ) { ?>
    <div id="social-older-posts"><a href="javascript:social_show_older_posts( <?php echo ($page + 1) . ",'" . (($public)?'activity':'boards') . "','" . (($public)?$social_user->screenname:$owner->screenname) . "'"; ?> )"><?php _e('Show Older Posts', 'social'); ?></a></div>
  <?php } ?>
</div>
<div id="social-info-tab" class="social-profile-tab social-hidden">
<table class="profile-edit-table">
<?php if(isset($social_options->field_visibilities['profile_info']['name']) and isset($user->first_name) and !empty($user->first_name)) { ?>
  <tr>
    <td class="social-info-tab-col-1">&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('First Name', 'social'); ?>:</td>
    <td class="social-info-tab-col-2">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $user->first_name; ?></td>
  </tr>
<?php } ?>
<?php if(isset($social_options->field_visibilities['profile_info']['name']) and isset($user->last_name) and !empty($user->last_name)) { ?>
  <tr>
    <td class="social-info-tab-col-1">&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Last Name', 'social'); ?>:</td>
    <td class="social-info-tab-col-2">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $user->last_name; ?></td>
  </tr>
<?php } ?>
<?php if(isset($social_options->field_visibilities['profile_info']['bio']) and (isset($user->bio) and !empty($user->bio))) { ?>
  <tr>
    <td class="social-info-tab-col-1">&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Bio', 'social'); ?>:</td>
    <td class="social-info-tab-col-2">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo make_clickable(stripslashes($user->bio)); ?></td>
  </tr>
  <?php } ?>
  <?php if(isset($social_options->field_visibilities['profile_info']['birthday']) and (isset($user->birthday) and !empty($user->birthday))) { ?>
  <tr>
    <td class="social-info-tab-col-1">&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Birthday', 'social'); ?>:</td>
    <td class="social-info-tab-col-2">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $user->birthday; ?></td>
  </tr>
  <?php } ?>
  <?php if(isset($social_options->field_visibilities['profile_info']['url']) and (isset($user->url) and !empty($user->url))) { ?>
  <tr>
    <td class="social-info-tab-col-1">&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Website', 'social'); ?>:</td>
    <td class="social-info-tab-col-2">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo make_clickable($user->url); ?></td>
  </tr>
  <?php } ?>
  <?php if(isset($social_options->field_visibilities['profile_info']['location']) and (isset($user->location) and !empty($user->location))) { ?>
  <tr>
    <td class="social-info-tab-col-1">&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Location', 'social'); ?>:</td>
    <td class="social-info-tab-col-2">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $user->location; ?></td>
  </tr>
  <?php } ?>
  <?php if(isset($social_options->field_visibilities['profile_info']['sex']) and (isset($user->sex) and !empty($user->sex))) { ?>
  <tr>
    <td class="social-info-tab-col-1">&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Gender', 'social'); ?>:</td>
    <td class="social-info-tab-col-2">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $user->sex_display; ?></td>
  </tr>
  <?php } ?>
  <?php do_action('social-profile-info', $user->id); ?>
</table>
</div>
<div id="social-photo-tab" class="social-photo-tab social-hidden">
<?php
global $wpdb;
$name=$_GET['u'];
$result1 = mysql_query("SELECT * FROM {$wpdb->prefix}users WHERE user_login='".$name."'");
$row1=mysql_fetch_array($result1);
$_user_id=$row1['ID'];
$get_photo=mysql_query("SELECT * FROM {$wpdb->prefix}social_photo WHERE user_id='".$user_id."' && status=1");
$photoes=mysql_fetch_array($get_photo);
$photo_array=explode(',',$photoes['image']);
$count=count($photo_array);
if($photo_array[0]=='')
{
echo $_GET['u']." doesn't want to share photos";
}
else
{
?>
<ul style="margin-top:50px;">
<?php
for($i=0;$i<$count;$i++)
{
?>
<li style="list-style:none; display:inline;">
<a href="<?php echo social_IMAGES_URL;?>/userphoto/<?php echo $photo_array[$i]; ?>" rel="lightbox[pimage]"><img src="<?php echo social_IMAGES_URL;?>/userphoto/<?php echo $photo_array[$i]; ?>" width="100px" height="100px"/></a>
</li>

<?php
 }
}
 ?>

</ul>
</div>
</html>