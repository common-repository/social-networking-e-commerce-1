<?php
session_start();
$bloginfo = get_bloginfo( 'wpurl', $filter );
global $wpdb;
$_SESSION['bloginfo']=$bloginfo;
global $current_user;
      get_currentuserinfo();
	  $_SESSION['user_id']=$current_user->ID;
function wt_get_ID_by_page_name($page_name)
{
	global $wpdb;
	$page_name_id = $wpdb->get_var("SELECT MAX(ID) FROM {$wpdb->prefix}posts WHERE post_title = '".$page_name."'");
	return $page_name_id;
}
$query = "SELECT * FROM {$wpdb->prefix}social_shopping_cart where user_id='".$current_user->ID."'";
$result = mysql_query($query) or die(mysql_error());
$num=mysql_num_rows($result);
if($num==0)
{
?>
<div id="empty_cart">
<?php
echo "Your shopping cart is empty<br>";
?>
</div>
<?php $shopid=wt_get_ID_by_page_name('Product Page'); ?>
<div id="visit_shop"><a href="<?php echo $bloginfo; ?>/?page_id=<?php echo $shopid; ?>" >Visit the shop</a></div>
<?php
}
else
{
for($i=0;$i<mysql_num_rows($result);$i++)
{
	$row = mysql_fetch_array($result);
	$countitem += $row['cnt'];
}
?>
<div style="margin-top:10px; padding-left:14px;">
<?php
$prodname=$_SESSION['prod_name'];
echo "You have just add <font color='#000'><b>".$prodname." </b></font>to your cart";
?>
</div>
<div id="cart_total">
<?php
echo "Number of items:&nbsp;".$countitem;
?>
</div>
<table class='shoppingcart' style="margin-top:20px; margin-left:20px;">
		<tr>
			<th id='product'><?php echo __('Product&nbsp;', 'social'); ?>&nbsp;&nbsp;</th>
			<th id='quantity'><?php echo __('&nbsp;Qty&nbsp;', 'social'); ?>&nbsp;&nbsp;</th>
			<th id='price'><?php echo __('Price', 'social'); ?>&nbsp;&nbsp;</th>
		</tr>
		<?php $query1 = "SELECT * FROM {$wpdb->prefix}social_shopping_cart where user_id='".$current_user->ID."'";
			  $result1 = mysql_query($query1) or die(mysql_error());
			  for($j=0;$j<mysql_num_rows($result1);$j++)
			  {
				$row1 = mysql_fetch_array($result1);
				$total +=$row1['price']; ?>
			<tr>
					<td><?php echo $row1['name']; ?></td>
					<td>&nbsp;<?php echo $row1['cnt']; ?></td>
					<td><?php echo "Rs. ".$row1['price'].".00"; ?></td>
			</tr>	
		<?php } ?>
	</table>
	
			<div id="cart_total">
			<div style="font-weight:bold; float:left;"><?php echo __('Total', 'social'); ?>:</div>
			<div style="float:left; padding-left:86px; "><?php echo "Rs. ".$total.".00"; ?></div>
			</div>
			<div id="empty_cart">
			<a href='<?php echo $bloginfo; ?>/wp-content/plugins/social/classes/views/social-store/empty_cart.php'><?php echo __('Empty your cart', 'social'); ?></a>
			</div>
			<div id="go_to">
			<?php $checkid=wt_get_ID_by_page_name('Checkout'); ?>
			<a href='<?php echo $bloginfo; ?>/?page_id=<?php echo $checkid; ?>'><?php echo __('Go to Checkout', 'social'); ?></a>
			</div>
<?php
}
?>