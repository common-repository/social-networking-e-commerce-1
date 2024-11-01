
<?php
session_start();
$bloginfo = get_bloginfo( 'wpurl', $filter );

global $current_user;
global $wpdb;
get_currentuserinfo();
$user_id=$current_user->ID;

?>
<div class="fileinputs">
<div id="upload_images">
<div id="text_upload">
Upload Photos</div>
</div>
<div id="photo_browse">
<form action="<?php echo $bloginfo; ?>/wp-content/plugins/social/classes/views/social-photo/upload_photo1.php" method="post" enctype="multipart/form-data">
<div id="imageup"><?php echo $_SESSION['photo_msg']; $_SESSION['photo_msg']=''; ?>
Image:&nbsp;&nbsp;</div>
<div id="imagefile"><input type="file" name="photoimg" /></div>
<input type="hidden" name="user_id" value="<?php echo $user_id;  ?>" />
<input type="hidden" name="blog" value="<?php echo $bloginfo; ?>"  />
<input type="submit" name="imagesubmit1" value="Upload" id="uploadimagesubmit" />
</form>
</div>
</div>
</div>
