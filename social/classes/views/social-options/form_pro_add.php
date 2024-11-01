
<?php
session_start();
$config_path = $_POST['config_path'];
require_once( $config_path . 'wp-config.php');
$pathinfo=$_POST['pathinfo'];
$pathinfo1=$pathinfo."/wp-admin/admin.php?page=social-products";
$pro_cat_id=$_POST['pro_cat_id'];
$pro_name=$_POST['pro_name'];
$pro_desc=$_POST['pro_desc'];
$pro_height=$_POST['pro_height'];
$pro_width=$_POST['pro_width'];
$pro_weight=$_POST['pro_weight'];
$pro_price=$_POST['pro_price'];
$pro_image=$_FILES['pro_image']['name'];
if((!empty($_FILES["pro_image"])) && ($_FILES['pro_image']['error'] == 0))
{
$filename = basename($_FILES['pro_image']['name']);
$path= dirname(__FILE__);
$path1=explode("classes",$path);
$path2=$path1[0].'images/uploads/';
$newname = $path2.$filename;
move_uploaded_file($_FILES["pro_image"]["tmp_name"],$newname);
}
/*$link = mysql_connect('localhost', 'root', 'root');
mysql_select_db('social_networking');*/
$sql="insert into wp_social_product_list values(
					'',
					'".$pro_cat_id."',
					'".$pro_name."',
					'".$pro_desc."',
					'".$pro_image."',
					'".$pro_height."',
					'".$pro_width."',
					'".$pro_weight."',
					'".$pro_price."')";
	
$insert=mysql_query($sql);
$_SESSION['success_msg']="Product is added succesfully.";
header('location:'.$pathinfo1);

?>


