<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>

</head>
<body>
<?php
$bloginfo = get_bloginfo( 'wpurl', $filter );
global $wpdb;
global $social_options;
if(isset($_POST['cart']))
{
?>
<?php global $current_user;
      get_currentuserinfo();

?>
<?php /*?><img src="wp-content/plugins/social/images/indicator.gif" /> 
<?php echo __('Updating cart...', 'social'); ?><?php */?>
<?php
$id=$_POST['id'];
$name=$_POST['prod_name'];
$_SESSION['prod_name']=$name;
$price=$_POST['price'];
$query = "SELECT * FROM {$wpdb->prefix}social_shopping_cart where name='".$name."' AND user_id='".$current_user->ID."'";
$result = mysql_query($query);
$num=mysql_num_rows($result);
$c=1;
if($num==0)
{
$sql="insert into {$wpdb->prefix}social_shopping_cart values(
			' ',
			'".$id."',
			'".$name."',
			'".$price."',
			'".$c."',
			'".$current_user->ID."')";
mysql_query($sql);
}
for($i=0;$i<mysql_num_rows($result);$i++)
{
$row = mysql_fetch_array($result);
if($row[2]!='')
{
$p = mysql_query("SELECT * FROM {$wpdb->prefix}social_product_list where id='".$id."'");
$p1=mysql_fetch_array($p);
$cnt=$row[4];
$price=$row[3];
$pricesum=$price+$p1['price'];
$cnt1=$cnt+1;
$sql1=mysql_query("UPDATE {$wpdb->prefix}social_shopping_cart SET cnt='".$cnt1."' WHERE name='".$row[2]."' AND user_id='".$current_user->ID."'");
$sql11=mysql_query("UPDATE {$wpdb->prefix}social_shopping_cart SET price='".$pricesum."' WHERE name='".$row[2]."' AND user_id='".$current_user->ID."'");
}
}
header('Location:'.$bloginfo.'/?page_id='.$social_options->checkout_page_id);
}
if ( is_user_logged_in())
{
?>
<?php 
global $current_user;
      get_currentuserinfo();
	  if($current_user->display_name == $_GET['u'] || $_GET['u']=='')
      {
	  $avatar = socialUtils::get_avatar( $current_user->ID, $size );
	  }
	  else
      {
	  $avatar = socialUtils::get_avatar( $_GET['u'], $size );
	  }
	  ?>
   <div id="mainsidebar"> 
	<div id="avatar1">
             <a href="?page_id=<?php echo $social_options->profile_page_id; ?>"><?php echo $avatar; ?></a>
        </div>
<?php require( social_VIEWS_PATH . '/social-profiles/social_sidebar1.php' ) ?>
 <div id="rightsidebar">
		<div id="menuheader">
	  <ul>
	  <li><a href="<?php echo get_permalink($social_options->profile_page_id); ?>"><?php _e('Home', 'social'); ?></a> | </li>
     <li><a href="<?php echo get_permalink($social_options->profile_edit_page_id); ?>"><?php _e('Setting', 'social'); ?></a> | </li>
	 <li><a href="<?php echo wp_logout_url(get_permalink($social_options->login_page_id)); ?>"><?php _e('Logout', 'social'); ?></a></li>
	 </ul>
	</div>
	 <div id="bodycontent">
        <table id="tablebordermsg1">
       
        <tr>
          <td id="rightsize">
		  

<div class="productstyle">
<div id="delete_images">
<div id="text_delete">
Products</div>
</div>
<?php
if($_GET['category_id']!='')
{
$get_cat_id=$_GET['category_id'];
$query = "SELECT * FROM {$wpdb->prefix}social_product_list where cat_id='".$get_cat_id."'";
$result = mysql_query($query);
for($i=0;$i<mysql_num_rows($result);$i++)
{
$row = mysql_fetch_array($result);
?>
<div id="mainimg"> 
<div id="fiximg">
<img src="<?php echo $bloginfo; ?>/wp-content/plugins/social/images/uploads/<?php echo $row['img']; ?>">
</div>

<form action="#" method="post">
<div style="float:left; padding-top:10px; padding-left:20px;">
<div style="padding-bottom:20px;">
<?php echo $row['name']; ?>
</div>
<input type="hidden" value="<?php echo $row['id']; ?>" name="id"/>
<input type="hidden" value="<?php echo $row['name']; ?>" name="prod_name"/>
<input type="hidden" value="<?php echo $row['price']; ?>" name="price"/>
Price: $
<?php echo $row['price'].".00";  ?>
<div style="padding-top:10px;">
<input type="submit" name="cart" value="Add To Cart" id="uploadimagesubmitdelete"/>
</div>
</div>
</form>
</div>
<?php
}
}
else
{
$query = mysql_query("SELECT * FROM {$wpdb->prefix}social_product_list");
for($i=0;$i<mysql_num_rows($query);$i++)
{
$data=mysql_fetch_array($query);
?>
<div id="mainimg">
<div id="fiximg">
<img src="<?php echo $bloginfo; ?>/wp-content/plugins/social/images/uploads/<?php echo $data['img']; ?>">
</div>

<form action="#" method="post">
<div style="float:left; padding-top:0px; padding-left:20px;">
<div style="padding-bottom:2px; padding-top:10px;">
<?php echo $data['name']; ?>
</div>
<input type="hidden" value="<?php echo $data['id']; ?>" name="id"/>
<input type="hidden" value="<?php echo $data['name']; ?>" name="prod_name"/>
<input type="hidden" value="<?php echo $data['price']; ?>" name="price"/>
<div style="padding-top:10px;">
Price: $
<?php echo $data['price'].".00";  ?>
</div>
<div id="addtocart">
<input type="submit" name="cart" value="Add To Cart" id="uploadimagesubmitdelete"/>
</div>
</div>
</form>
</div>
<?php
}
}
}
else
{
?>
<td valign="top" class="social-profile-table-col-2"> 
        <table id="tablebordermsg1">
       
        <tr>
          <td id="rightsize">
<?php
$page_name_id = mysql_query("SELECT ID FROM {$wpdb->prefix}posts WHERE post_name ='login'");
$shopid=mysql_fetch_array($page_name_id);
?>You're unauthorized to view this page. Why don't you <a href="<?php echo $bloginfo; ?>/?page_id=<?php echo $shopid[0]; ?>" >Login</a> and try again.
<?php
}
?>
</td>
</tr>
</table>
</td>
</table>
</div>
</div>
</body>
</html>