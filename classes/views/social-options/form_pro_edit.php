<?php
// Silence is golden.
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Edit Category</title>
</head>

<body>
<?php
     $config_path = $_POST['config_path'];
require_once( $config_path . 'wp-config.php');
     $pathinfo=$_POST['pathinfo'];
	 $pathinfo1=$pathinfo."/wp-admin/admin.php?page=social-products";
	$pro_id=$_POST['pro_id'];
	$pro_cat_id=$_POST['pro_cat_id'];
	$pro_name=$_POST['pro_name'];
	$pro_desc=$_POST['pro_description'];
	$pro_height=$_POST['pro_height'];
	$pro_width=$_POST['pro_width'];
	$pro_weight=$_POST['pro_weight'];
	$pro_price=$_POST['pro_price'];
	
	if($_FILES['pro_image']['name'] != '')
	{
	 $pro_image=$_FILES['pro_image']['name'];
	 $filename = basename($_FILES['pro_image']['name']);
	$path= dirname(__FILE__);
	$path1=explode("classes",$path);
	$path2=$path1[0].'images/uploads/';
	$newname = $path2.$filename;
	move_uploaded_file($_FILES["pro_image"]["tmp_name"],$newname);
	   
	 }
	 else
	 {
	       $pro_image=$_POST['pro_old_image'];	 
	 }
	
	
      $sql="update wp_social_product_list set
	    			cat_id='".$pro_cat_id."',
					name='".$pro_name."',
					description='".$pro_desc."',
					img='".$pro_image."',
					height='".$pro_height."',
					width='".$pro_width."',
					weight='".$pro_weight."',
					price='".$pro_price."' where id='".$pro_id."'";
	
    $update=mysql_query($sql);
	$_SESSION['success_msg']="Product is edited successfully.";
	header('location:'.$pathinfo1);
	
?>
</body>
</html>
