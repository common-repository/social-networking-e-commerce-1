<?php
session_start();
global $wpdb;
global $social_options;
require_once('../../../../../../wp-config.php');
$bloginfo=$_POST['blog'];
$user_id=$_POST['user_id'];
if((!empty($_FILES["photoimg"])) && ($_FILES['photoimg']['error'] == 0))
{
$filename = basename($_FILES['photoimg']['name']);
$path= dirname(__FILE__);
$path1=explode("classes",$path);
$path2=$path1[0].'images/userphoto/';
$newname = $path2.$filename;
//echo $newname;

move_uploaded_file($_FILES["photoimg"]["tmp_name"],$newname);
$get_photo=mysql_query("SELECT * FROM {$wpdb->prefix}social_photo WHERE user_id='".$user_id."' ");
$count=mysql_num_rows($get_photo);
$photoes=mysql_fetch_array($get_photo);
if($count>0)
{
$filename = basename($_FILES['photoimg']['name']);
$ph_img=$photoes['image'];
$ph_img1=$ph_img.",".$filename;
echo $ph_img."<br>".$ph_img1;

$query="UPDATE {$wpdb->prefix}social_photo SET image='".$ph_img1."' where user_id='".$user_id."'";

}
else
{
$status=1;
$query="INSERT INTO {$wpdb->prefix}social_photo VALUES('',
											'".$user_id."',
											'".$filename."',
											'".$status."')";
}
$query1=mysql_query($query);

}
header('Location:'.$bloginfo.'/?page_id='.$social_options->photo_page_id);


?>