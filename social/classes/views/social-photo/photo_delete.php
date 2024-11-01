<?php
session_start();
$bloginfo = get_bloginfo( 'wpurl', $filter );
global $current_user;
global $wpdb;
get_currentuserinfo();
$user_id=$current_user->ID;
//echo $user_id;
define('ABSPATH', dirname(__FILE__) . '/');
require(ABSPATH.'wp-config.php');
$get_photo=mysql_query("SELECT * FROM {$wpdb->prefix}social_photo WHERE user_id='".$user_id."' ");
$photoes=mysql_fetch_array($get_photo);
$photo_array=explode(',',$photoes['image']);
$count=count($photo_array);
?>




<div class="fileinputsdelete">
<div id="delete_images">
<div id="text_delete">
Delete Photos</div>
</div>
<div id="photo_deletemain">
<?php for($i=0;$i<$count;$i++)
{?>
<form action="<?php echo $bloginfo; ?>/wp-content/plugins/social/classes/views/social-photo/photo_delete1.php" method="post">
<input type="hidden" name="user_id1" value="<?php echo $user_id; ?>" />
 <input type="hidden" name="blog" value="<?php echo $bloginfo; ?>" />
 <input type="hidden" name="search_image" value="<?php echo $photo_array[$i]; ?>" />
  <div id="mainphoto_delete">
 <div id="photo_delete">
<img src="<?php echo social_IMAGES_URL;?>/userphoto/<?php echo $photo_array[$i]; ?>" height="50px" width="50px" />
</div>         
		 
		 <input type="hidden" name="id" value="<?php echo $i; ?>" />
<div id="photo_delete1">		 
<input type="submit" name="delete" value="Delete" id="uploadimagesubmitdelete"/>
</div>
</div>
	</form>
<?php }

?>
</div>
</div>
</div>

