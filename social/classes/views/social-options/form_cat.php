<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Add Category</title>
</head>
<body>
<div class="wrap">
<h2 id="social_title" style="margin: 10px 0px 0px 0px; padding: 0px 0px 0px 56px; height: 48px; background: url(<?php echo social_URL . "/images/social_48.png"; ?>) no-repeat"><?php _e('social: Category', 'social'); ?></h2>
<br/>
<?php 
$bloginfo = get_bloginfo( 'wpurl', $filter );
$base = dirname(__FILE__);
$config_path=explode('wp-content',$base);

?>
<?php

if(isset($_POST['add']))
{
?>
<script type="text/javascript">
var add_cat_validation = function() {
   if (document.getElementById("catname").value == "") 
  {
    alert("Please Enter Category name.");
    return false;
  } 
   if (document.getElementById("desc").value == "") 
  {
    alert("Please Enter Category description.");
    return false;
  } 
  
  if (document.getElementById("image").value == "") 
  {
    alert("Please select path For Image.");
    return false;
  } 
}
</script>
<form name="myForm" method="post" action="../wp-content/plugins/social/classes/views/social-options/form_cat_add.php" enctype="multipart/form-data" onsubmit="javascript:return add_cat_validation();">
<div style="float:right; margin-top:30px; width:45%;">
<table align="center" style="border-left:solid 1px; border-top:solid 1px; border-right:solid 1px; width:100%;">
<tr>
<td align="center">
<font style="font-size:24px; font-weight:bold;">Add Category</font>
</td>
</tr>
</table>
<input type="hidden" name="config_path" value="<?php echo $config_path[0];?>" />
<input type="hidden" name="pathinfo" value="<?php echo $bloginfo; ?>" />
<table align="center" style="border:solid 1px; width:100%;">
<tr>
<td>
<table border="0">
<tr>
<td>Category Name:</td><td><input type="text" name="catname" id="catname"/></td>
</tr>
<tr>
<td>Description:</td><td><textarea name="desc" id="desc"></textarea></td>
</tr>
<tr>
<td>Image:</td><td><input type="file" name="image" id="image" /></td>
</tr>
<tr><td colspan="2" align="center">
<input type="submit" name="submitcat" value="Submit"/>
</td>
</tr>

</table>
</td>
</tr>
</table>
</div>
</form>
<?php
}
		

if(isset($_POST['edit']))
{

$id=$_POST['id'];


$sql="select * from wp_social_category where id='$id'";				
$view=mysql_query($sql);
$count=mysql_num_rows($view);
for($i=0;$i<$count;$i++)
{
    $view1=mysql_fetch_array($view);
}
?>
<script type="text/javascript">
var edit_cat_val = function() {
   if (document.getElementById("cname").value == "") 
  {
    alert("Please Enter Category name.");
    return false;
  } 
   if (document.getElementById("cat_description").value == "") 
  {
    alert("Please Enter Category description.");
    return false;
  } 
  
  if (document.getElementById("cat_image").value == "") 
  {
    alert("Please select path For Image.");
    return false;
  } 
}
</script>
<form method="post" action="../wp-content/plugins/social/classes/views/social-options/form_cat_edit.php" enctype="multipart/form-data" onsubmit="javascript:return edit_cat_val();">
<div style="float:right; width:45%; margin-top:30px;">
<table align="center" style="border-left:solid 1px; border-top:solid 1px; border-right:solid 1px; width:100%">
<tr>
<td align="center">
<font style="font-size:24px; font-weight:bold;">Edit Category</font>
</td>
</tr>
</table>
<table align="center" style="border:solid 1px; width:100%">
<tr>
<td>
<table border="0">
<tr>
<td>
<?php $bloginfo = get_bloginfo( 'wpurl', $filter );
?>
<input type="hidden" name="config_path" value="<?php echo $config_path[0];?>" />
<input type="hidden" name="pathinfo" value="<?php echo $bloginfo; ?>" />
<input type="hidden" name="c_id" value="<?php echo $view1['id']; ?>" />
<input type="hidden" name="cat_old_image" value="<?php echo $view1['img'];?>" />
Category Name: </td><td><input type="text" name="cname" id="cname" value="<?php echo $view1['name']; ?>" />
</td>
</tr>
<tr>
<td>Description:</td><td><textarea name="cat_description" id="cat_description"><?php echo $view1['description']; ?> </textarea> </td>
</tr>
<tr>
<td><img src="<?php echo "../wp-content/plugins/social/images/uploads/".$view1['img'] ?>" width="80px" height="80px"/></td>
<td><input type="file" name="cat_image" id="cat_image" /></td>
</tr>
<tr>
<td colspan="2" align="center">
<input type="submit" name="edit_cat" value="Update" />
</td>
</tr>
</table>
</td>
</tr>
</table>
</div>
</form>
<?php 
}//edit complete
?>
<?php
if(isset($_POST['delete']))
{
$id=$_POST['id'];
$delcat=mysql_query("delete from wp_social_category where id='".$id."'");
$_SESSION['success_msg']="Category is deleted successfully";
}
?>
<form method="post" action="" enctype="multipart/form-data">
<div style="float:left; width:45%;">
<table border="1px"><tr><td></td><input type="submit" name="add" value="Add New Category" /></tr></table>
<table align="center" style="border-left:solid 1px; border-top:solid 1px; border-right:solid 1px; width:331px;">
<tr>
<td align="center">
<font style="font-size:24px; font-weight:bold;">Categories</font>
</td>
</tr>
</table>
<table align="center" style="border:solid 1px; width:331px;" >
<tr style="border:solid 1px;" bordercolor="#000099">

<td><b>No</b></td><td><b>Category Titles</b></td><td><b>Edit</b></td><td><b>Delete</b></td>
</tr>
<?php
			
$view=mysql_query("select * from wp_social_category");
$count=mysql_num_rows($view);
for($j=1;$j<=$count;$j++)
	{
		
		$cat=mysql_fetch_array($view);
		?>
<tr>
<td>
<?php echo $j; ?>
</td>
<td>
<?php echo $cat['name']; ?>
</td>
<td> 
<form method="post" action="" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php echo $cat['id']; ?>" />
<input type="submit" name="edit" value="edit" />
</td>
<td>
<input type="submit" name="delete" value="Delete" onclick="javascript:return confirm('Are you sure you want to delete this category ?')" />
</form>
</td>
</tr>
<?php } ?>
</table>
</div>
</form>
<div style='float:right; width:45%; margin-top:30px;'>
<?php echo $_SESSION['success_msg']; $_SESSION['success_msg']=""; ?>

</div>

</body>
</html>