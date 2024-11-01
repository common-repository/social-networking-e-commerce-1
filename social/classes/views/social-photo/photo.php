<?php
$bloginfo = get_bloginfo( 'wpurl', $filter );
global $social_options;
if ( is_user_logged_in())
{

?>
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
	<body>
	<?php 
global $current_user;
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
<?php
global $current_user;
global $wpdb;
get_currentuserinfo();
$user_id=$current_user->ID;

?>
<form action="" method="post">
<div id="photo_button" style="margin-top:20px;">
<input type="submit" name="upload_photo" value="Upload Photo">
<input type="submit" name="delete_photo" value="Delete Photo">
<input type="submit" name="view_photo" value="View Photo">
<input type="submit" name="setting" value="Setting">
</div>
</form>

<?php
$get_photo=mysql_query("SELECT * FROM {$wpdb->prefix}social_photo WHERE user_id='".$user_id."' ");
$photoes=mysql_fetch_array($get_photo);
$photo_array=explode(',',$photoes['image']);
$count=count($photo_array);
//print_r($photo_array);

?>
<?php if(isset($_POST['upload_photo']))
{ 

 require( social_VIEWS_PATH . '/social-photo/photo_upload.php' );
 

  } ?>
<?php if(isset($_POST['delete_photo']))
{ 
 require( social_VIEWS_PATH . '/social-photo/photo_delete.php' );
 

  } ?>
  <?php if(isset($_POST['view_photo']))
{ 


 header('Location:'.$bloginfo.'/?page_id='.$social_options->photo_page_id);

  } ?>
<?php if(isset($_POST['setting']))

{?>

<div class="fileinputsdelete1">
<div id="delete_images">
<div id="text_delete">
Apply Setting</div>
</div>
<form action="<?php echo $bloginfo; ?>/wp-content/plugins/social/classes/views/social-photo/photo_setting.php" method="post">
<div id="photosetting">
<input type="radio" name="photo_set" value="1"> &nbsp;I Want My Photo to Be Visible To Me and My Friends <br>
<input type="radio" name="photo_set" value="0"> &nbsp;I Want My Photo to Only Be Visible To Me <br>
<input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
<input type="hidden" name="bloginfo" value="<?php echo $bloginfo; ?>" />
<div id="photo_setting"><input type="submit" name="update" value="Update" id="updatesetting"></div>
</div>
</form>
</div>
</div></div>

<?php } ?>
<?php if((!isset($_POST['upload_photo'])) && (!isset($_POST['delete_photo'])))
{?>
<div class="fileinputsdelete">
<div id="delete_images">
<div id="text_delete">
Photos</div>
</div>
<ul>
<?php
for($i=0;$i<$count;$i++)
{
?>
<li style="list-style:none; display:inline;">
<a href="<?php echo social_IMAGES_URL;?>/userphoto/<?php echo $photo_array[$i]; ?>" rel="lightbox[pimage]"><img src="<?php echo social_IMAGES_URL;?>/userphoto/<?php echo $photo_array[$i]; ?>" width="100px" height="100px"/></a>
</li>

<?php
 }
 ?>

</ul>
</div>

<?php
}
}
else
{
?>
<div class="fileinputsdelete">
<div id="delete_images">
<div id="text_delete">
Photos</div>
</div>
<?php
$page_name_id = mysql_query("SELECT ID FROM wp_posts WHERE post_name ='login'");
$shopid=mysql_fetch_array($page_name_id);
?>You're unauthorized to view this page. Why don't you <a href="<?php echo $bloginfo; ?>/?page_id=<?php echo $shopid[0]; ?>" >Login</a> and try again.
<?php
?>
</div>
</div> 
</div>
<?php
}
?>
</td>
</tr>
</table>
</td>
</table>
</div>
</div>
</body>
</html>