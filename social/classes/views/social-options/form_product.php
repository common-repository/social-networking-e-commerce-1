<?php session_start();
include('');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Add Category</title>
</head>
<body>
<div class="wrap">
<h2 id="social_title" style="margin: 10px 0px 0px 0px; padding: 0px 0px 0px 56px; height: 48px; background: url(<?php echo social_URL . "/images/social_48.png"; ?>) no-repeat"><?php _e('social: Products', 'social'); ?></h2>
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
var add_product_validation = function() {
   if (document.getElementById("pro_name").value == "") 
  {
    alert("Please Enter Product name.");
    return false;
  } 
   if (document.getElementById("pro_desc").value == "") 
  {
    alert("Please Enter Product description.");
    return false;
  } 
  
  if (document.getElementById("pro_image").value == "") 
  {
    alert("Please select path For Image.");
    return false;
  } 
  if (document.getElementById("pro_height").value == "") 
  {
    alert("Please Enter Product Height.");
    return false;
  } 
  if (document.getElementById("pro_width").value == "") 
  {
    alert("Please Enter Product Width.");
    return false;
  } 
  if (document.getElementById("pro_weight").value == "") 
  {
    alert("Please Enter Product Weight.");
    return false;
  } 
  if (document.getElementById("pro_price").value == "") 
  {
    alert("Please Enter Product Price.");
    return false;
  } 
}
</script>
<form method="post" action="../wp-content/plugins/social/classes/views/social-options/form_pro_add.php" enctype="multipart/form-data" onsubmit="javascript:return add_product_validation();">
<div style="float:right; margin-top:30px; width:45%;">
<table align="center" style="border-left:solid 1px; border-top:solid 1px; border-right:solid 1px; width:100%;">
<tr>
<td align="center">
<font style="font-size:24px; font-weight:bold;">Add Product</font>
</td>
</tr>
</table>
<table align="center" style="border:solid 1px; width:100%;">
<tr>
<td>
<table border="0">
<tr>
<td>Product Category:</td><td><select name="pro_cat_id">
<?php

$pro_category=mysql_query("SELECT * FROM wp_social_category");
$count=mysql_num_rows($pro_category);
for($i=0;$i<$count;$i++)
{
$pro_category1=mysql_fetch_array($pro_category);
?>
<option value="<?php echo $pro_category1['id']; ?>"><?php echo $pro_category1['name']; ?></option>
<?php
}
?>
</select>


</td>
</tr>
<tr>
<td>
<input type="hidden" name="pathinfo" value="<?php echo $bloginfo; ?>" />
<input type="hidden" name="config_path" value="<?php echo $config_path[0];?>" />
Product Name:</td><td><input type="text" name="pro_name" id="pro_name"/></td>
</tr>
<tr>
<td>Description:</td><td><textarea name="pro_desc" id="pro_desc"></textarea></td>
</tr>
<tr>
<td>Image:</td><td><input type="file" name="pro_image" id="pro_image" /></td>
</tr>
<tr>
<td>Product Height:</td><td><input type="text" name="pro_height" id="pro_height"/></td>
</tr>
<tr>
<td>Product Width:</td><td><input type="text" name="pro_width" id="pro_width"/></td>
</tr>
<tr>
<td>Product Wight:</td><td><input type="text" name="pro_weight" id="pro_weight"/></td>
</tr>
<tr>
<td>Product Price:</td><td><input type="text" name="pro_price" id="pro_price"/></td>
</tr>

<tr><td colspan="2" align="center">
<input type="submit" name="submitpro" value="Submit"/>
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
 $bloginfo = get_bloginfo( 'wpurl', $filter );
$product_id=$_POST['product_id'];
$sql="select * from wp_social_product_list where id='$product_id'";				
$view_prod=mysql_query($sql);
$count=mysql_num_rows($view_prod);
for($i=0;$i<$count;$i++)
{
    $view_prod1=mysql_fetch_array($view_prod);
}
?>
<script type="text/javascript">
var edit_product_validation = function() {
   if (document.getElementById("pro_name").value == "") 
  {
    alert("Please Enter Product name.");
    return false;
  } 
   if (document.getElementById("pro_description").value == "") 
  {
    alert("Please Enter Product description.");
    return false;
  } 
  
  if (document.getElementById("pro_height").value == "") 
  {
    alert("Please Enter Product Height.");
    return false;
  } 
  if (document.getElementById("pro_width").value == "") 
  {
    alert("Please Enter Product Width.");
    return false;
  } 
  if (document.getElementById("pro_weight").value == "") 
  {
    alert("Please Enter Product Weight.");
    return false;
  } 
  if (document.getElementById("pro_price").value == "") 
  {
    alert("Please Enter Product Price.");
    return false;
  } 
}
</script>
<form method="post" action="../wp-content/plugins/social/classes/views/social-options/form_pro_edit.php" enctype="multipart/form-data" onsubmit="javascript:return edit_product_validation();">
<div style="float:right; width:45%; margin-top:30px;">
<table align="center" style="border-left:solid 1px; border-top:solid 1px; border-right:solid 1px; width:100%">
<tr>
<td align="center">
<font style="font-size:24px; font-weight:bold;">Edit Product</font>
</td>
</tr>
</table>
<table align="center" style="border:solid 1px; width:100%">
<tr>
<td>
<table border="0">
<tr>
<td>Product Category:</td><td><select name="pro_cat_id">
<?php

$pro_category=mysql_query("SELECT * FROM wp_social_category");
$count=mysql_num_rows($pro_category);
for($i=0;$i<$count;$i++)
{
$pro_category1=mysql_fetch_array($pro_category);
?>
<option value="<?php echo $pro_category1['id']; ?>"><?php echo $pro_category1['name']; ?></option>
<?php
}
?>
</select>


</td>
</tr>
<tr>
<td>
<input type="hidden" name="config_path" value="<?php echo $config_path[0];?>" />
<input type="hidden" name="pathinfo" value="<?php echo $bloginfo; ?>" />
<input type="hidden" name="pro_id" value="<?php echo $view_prod1['id']; ?>" />
<input type="hidden" name="pro_old_image" value="<?php echo $view_prod1['img'];?>" />
Product Name: </td><td><input type="text" name="pro_name" id="pro_name" value="<?php echo $view_prod1['name']; ?>" />
</td>
</tr>
<tr>
<td>Description:</td><td><textarea name="pro_description" id="pro_description"><?php echo $view_prod1['description']; ?> </textarea> </td>
</tr>
<tr>
<td><img src="<?php echo "../wp-content/plugins/social/images/uploads/".$view_prod1['img'] ?>" width="80px" height="80px"/></td>
<td><input type="file" name="pro_image" id="pro_image" /></td>
</tr>
<tr>
<td>Product Height:</td><td><input type="text" name="pro_height" id="pro_height" value="<?php echo $view_prod1['height']; ?>"/></td>
</tr>
<tr>
<td>Product Width:</td><td><input type="text" name="pro_width" id="pro_width" value="<?php echo $view_prod1['width']; ?>"/></td>
</tr>
<tr>
<td>Product Wight:</td><td><input type="text" name="pro_weight" id="pro_weight" value="<?php echo $view_prod1['weight']; ?>"/></td>
</tr>
<tr>
<td>Product Price:</td><td><input type="text" name="pro_price" id="pro_price" value="<?php echo $view_prod1['price']; ?>"/></td>
</tr>

<tr>
<td colspan="2" align="center">
<input type="submit" name="edit_pro" value="Update" />
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
$id=$_POST['product_id'];
$delcat=mysql_query("delete from wp_social_product_list where id='".$id."'");
$_SESSION['success_msg']="Product is deleted successfully";
}
?>
<form method="post" action="" enctype="multipart/form-data">
<div style="float:left; width:45%;">
<table border="1px"><tr><td></td><input type="submit" name="add" value="Add New Product" /></tr></table>
<table align="center" style="border-left:solid 1px; border-top:solid 1px; border-right:solid 1px; width:100%">
<tr>
<td align="center">
<font style="font-size:24px; font-weight:bold;">Products</font>
</td>
</tr>
</table>
<table align="center" style="border:solid 1px; width:100%;" >
<tr style="border:solid 1px;" bordercolor="#000099">

<td><b>No</b></td><td><b>Product Titles</b></td><td><b>Edit</b></td><td><b>Delete</b></td>
</tr>
<?php
			
$view_pro=mysql_query("select * from wp_social_product_list");
$count=mysql_num_rows($view_pro);
for($j=1;$j<=$count;$j++)
	{
		
		$product=mysql_fetch_array($view_pro);
		?>
<tr>
<td>
<?php echo $j; ?>
</td>
<td>
<?php echo $product['name']; ?>
</td>
<td> 
<form method="post" action="" enctype="multipart/form-data">
<input type="hidden" name="product_id" value="<?php echo $product['id']; ?>" />
<input type="submit" name="edit" value="edit" />
</td>
<td>
<input type="submit" name="delete" value="Delete" onclick="javascript:return confirm('Are you sure you want to delete this product ?')" />
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