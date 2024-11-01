<?php
session_start();
$config_path = $_POST['config_path'];
require_once( $config_path . 'wp-config.php');
$pathinfo=$_POST['pathinfo'];
$pathinfo1=$pathinfo."/wp-admin/admin.php?page=social-category";

if((!empty($_FILES["image"])) && ($_FILES['image']['error'] == 0))
{
$filename = basename($_FILES['image']['name']);
$path= dirname(__FILE__);
$path1=explode("classes",$path);
$path2=$path1[0].'images/uploads/';
$newname = $path2.$filename;
move_uploaded_file($_FILES["image"]["tmp_name"],$newname);
}
$name=$_POST['catname'];
$desc=$_POST['desc'];
$image=$_FILES['image']['name'];
/*$link = mysql_connect('localhost', 'root', 'root');
mysql_select_db('social_networking');*/
$sql="insert into wp_social_category values(
					'',
					'".$name."',
					'".$desc."',
					'".$image."')";
	
$insert=mysql_query($sql);
$_SESSION['success_msg']="Category is added succesfully.";
header('location:'.$pathinfo1);
?>


