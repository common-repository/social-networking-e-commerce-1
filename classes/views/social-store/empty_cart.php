<?php
session_start();
global $wpdb;
$user_id=$_SESSION['user_id'];
$blog=$_SESSION['bloginfo'];
echo $blog;
require_once('../../../../../../wp-config.php');
mysql_query("delete from {$wpdb->prefix}social_shopping_cart where user_id='".$user_id."'");
$page_name_id = mysql_query("SELECT MAX(ID) FROM {$wpdb->prefix}posts WHERE post_title='Product Page'");
$shopid=mysql_fetch_array($page_name_id);
header('Location:'.$blog.'/?page_id='.$shopid[0]);
?>