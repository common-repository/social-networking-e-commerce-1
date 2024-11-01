<?php 
$id=$_POST['id'];
$bloginfo=$_POST['blog'];
//echo $bloginfo;
global $wpdb;
//echo $id;
$user_id1=$_POST['user_id1'];
$search_image=$_POST['search_image'];
//echo $search_image;
//echo $user_id1;
require_once('../../../../../../wp-config.php');
$get_photo=mysql_query("SELECT * FROM {$wpdb->prefix}social_photo WHERE user_id='".$user_id1."' ");
$photoes=mysql_fetch_array($get_photo);
$photo_array=explode(',',$photoes['image']);
$count=count($photo_array);
//echo $count;

$key = array_search($search_image, $photo_array);
unset($photo_array[$key]);
$update_image=implode(',',$photo_array);
echo $update_image;

$update_photo=mysql_query("UPDATE {$wpdb->prefix}social_photo SET image='".$update_image."' WHERE user_id='".$user_id1."'  ");
header('Location:'.$bloginfo.'/?page_id='.$social_options->photo_page_id);
 ?>