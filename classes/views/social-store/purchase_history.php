<?php
$bloginfo = get_bloginfo( 'wpurl', $filter );
global $social_options;
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
	  <table id="historytable">
        <tr> 
          <td id="historytd">
		  <div class="purchasehistorymain">
<div id="titlehistory">
<div id="text_delete">
Purchase History</div>
</div>
<?php
if ( is_user_logged_in())
{
global $current_user;
      get_currentuserinfo();
global $wpdb;
$query1 = "SELECT * FROM {$wpdb->prefix}social_shopping_cart where user_id='".$current_user->ID."'";
$result1 = mysql_query($query1);
for($i=0;$i<mysql_num_rows($result1);$i++)
{
$row1 = mysql_fetch_array($result1);
$name[]=$row1['name'];
$qnt[]=$row1['cnt'];
$price[]=$row1['price'];
$totalprice += $row1['price'];
}
$implname = implode(',' , $name);
$implqty=implode(',',$qnt);
$implprice=implode(',',$price);
$currdate=date("Y-m-d");
$transaction_id=$_POST['transaction_subject'];
if($_POST)
{
$sql="insert into {$wpdb->prefix}social_purchase_logs values(
			' ',
			'".$current_user->ID."',
			'".$implname."',
			'".$implqty."',
			'".$implprice."',
			'".$totalprice."',
			'".$currdate."',
			'".$transaction_id."')";
$insert = mysql_query($sql);
}
$history = mysql_query("SELECT * FROM {$wpdb->prefix}social_purchase_logs where user_id='".$current_user->ID."'");
for($i=0;$i<mysql_num_rows($history);$i++)
{
$history1 = mysql_fetch_array($history);
$count=count(explode(',',$history1[2]));
$explname=explode(',',$history1[2]);
$explqty=explode(',',$history1[3]);
$explprice=explode(',',$history1[4]);
if($_POST)
{
mysql_query("delete from {$wpdb->prefix}social_shopping_cart where user_id='".$current_user->ID."'");
}
?>
<div id="transaction">
&nbsp;&nbsp;Your Transaction Date Is: <?php echo $history1[6]; ?></div>
<table class="tablehist" style="margin-bottom:14px !important;">
<tr id="tabletrhistory" style="border-bottom: 1px solid #E7E7E7;">
<td>
<b>&nbsp;&nbsp;Product Name</b>
</td>
<td>
<b>Quantity</b>
</td>
<td>
<b>Price</b>
</td>
</tr>
<?php
for($j=0;$j<$count;$j++)
{
?>
<tr>
<td>&nbsp;&nbsp;
<?php
echo $explname[$j];
?>
</td>
<td>
<?php
echo $explqty[$j];
$totalqt +=$explqty[$j];
?>
</td>
<td>
Rs.
<?php
echo $explprice[$j].".00";
$totalpr +=$explprice[$j];
?>
</td>
</tr>
<?php
}
?>
<tr style="border-top: 1px solid #E7E7E7;">
<td>
<b>&nbsp;&nbsp;Total</b>
</td>
<td>
<?php echo $totalqt; ?>
</td>
<td>
Rs.
<?php echo $totalpr.".00"; ?>
</td>
</td>
</tr>
</table>
<?php
$totalqt='';
$totalpr='';
}
}
else
{
$page_name_id = mysql_query("SELECT ID FROM wp_posts WHERE post_name ='login'");
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