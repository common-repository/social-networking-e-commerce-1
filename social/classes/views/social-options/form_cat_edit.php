<?php
// Silence is golden.
session_start();
$config_path = $_POST['config_path'];
require_once( $config_path . 'wp-config.php');
?>
<?php

       $pathinfo=$_POST['pathinfo'];
	   $pathinfo1=$pathinfo."/wp-admin/admin.php?page=social-category";
	   $c_id=$_POST['c_id'];
	   $c_name=$_POST['cname'];
	   $c_desc=$_POST['cat_description'];
	   $path= dirname(__FILE__);
	   if($_FILES['cat_image']['name'] != '')
	   {
			 $cat_image=$_FILES['cat_image']['name'];
		     $filename = basename($_FILES['cat_image']['name']);
			$path1=explode("classes",$path);
			$path2=$path1[0].'images/uploads/';
			$newname = $path2.$filename;
			move_uploaded_file($_FILES["cat_image"]["tmp_name"],$newname);
	   
	  }
	 else
	 {
	       $cat_image=$_POST['cat_old_image'];	 
	 }
				/*$link = mysql_connect('localhost', 'root', 'root');
		 		mysql_select_db('social_networking');*/
		  		$sql="update wp_social_category set
						name='".$c_name."',
						description='".$c_desc."',
						img='".$cat_image."' where id='".$c_id."'";
	
				$update=mysql_query($sql);
				$_SESSION['success_msg']="Category is edited successfully.";
				header('location:'.$pathinfo1);
					
	?>



</body>
</html>
