
<?php 
session_start();

$user_id=$_POST['user_id'];
$photo_set=$_POST['photo_set'];
global $wpdb;
global $social_options;
$bloginfo = $_POST['bloginfo'];
echo $bloginfo;
require_once('../../../../../../wp-config.php');

$query="UPDATE {$wpdb->prefix}social_photo SET status='".$photo_set."' where user_id='".$user_id."'";
$query1=mysql_query($query);
header('Location:'.$bloginfo.'/?page_id='.$social_options->photo_page_id);



?>
