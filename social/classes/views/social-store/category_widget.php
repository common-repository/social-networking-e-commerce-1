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
if ( is_user_logged_in())
{
global $current_user;
get_currentuserinfo();
$query = "SELECT * FROM {$wpdb->prefix}social_category";
$result = mysql_query($query);
?>
<div style="margin-top:10px; margin-left:10px;"></div>
<div id="cat_lst" style="padding-left:20px;">
<?php
for($i=0;$i<mysql_num_rows($result);$i++)
{
$row = mysql_fetch_array($result);
?>
<?php
$page_name_id = mysql_query("SELECT MAX(ID) FROM {$wpdb->prefix}posts WHERE post_title='Product Page'");
$shopid=mysql_fetch_array($page_name_id);
?>

<img src="<?php echo social_IMAGES_URL; ?>/arrow.png" />
<a href="<?php echo $bloginfo; ?>/?page_id=<?php echo $shopid[0]; ?>&category_id=<?php echo $row[0]; ?>" ><?php echo $row[1]."<br>"; ?></a>
<?php
}
}
?>
</div>
</body>
</html>