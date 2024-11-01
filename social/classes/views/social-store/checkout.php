<?php
session_start();
$bloginfo = get_bloginfo( 'wpurl', $filter );
global $wpdb;
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
	  <table id="tablebordermsg1">
        <tr>
          <td id="rightsize">
<?php
if ( is_user_logged_in())
{
if(isset($_POST['update']))
{
$qnt=$_POST['quantity'];
$prod_id=$_POST['prod_id'];
$p = mysql_query("SELECT * FROM {$wpdb->prefix}social_product_list where id='".$prod_id."'");
$p1=mysql_fetch_array($p);
$pricesum=$qnt*$p1[5];
$_SESSION['pricesum']=$pricesum;
$sql1=mysql_query("UPDATE {$wpdb->prefix}social_shopping_cart SET cnt='".$qnt."'WHERE prod_id='".$prod_id."'");
$sql11=mysql_query("UPDATE {$wpdb->prefix}social_shopping_cart SET price='".$pricesum."' WHERE prod_id='".$prod_id."'");
}
global $current_user;
      get_currentuserinfo();
if(isset($_POST['remove']))
{
$prod_id=$_POST['prod_id'];
mysql_query("delete from {$wpdb->prefix}social_shopping_cart where prod_id='".$prod_id."' AND user_id='".$current_user->ID."'");
}
$query = "SELECT * FROM {$wpdb->prefix}social_shopping_cart where user_id='".$current_user->ID."'";
$result = mysql_query($query) or die(mysql_error());
$num=mysql_num_rows($result);
if($num==0)
{
echo "Oops, there is nothing in your cart. ";
?>
<?php
$page_name_id = mysql_query("SELECT MAX(ID) FROM {$wpdb->prefix}posts WHERE post_title='Product Page'");
$shopid=mysql_fetch_array($page_name_id);
?>
<a href="<?php echo $bloginfo; ?>/?page_id=<?php echo $shopid[0]; ?>" >Please visit our shop</a>
<?php
}
else
{
if(isset($_POST['make_purchase']))
{
$first_name=$_POST['first_name'];
$last_name=$_POST['last_name'];
$address=$_POST['address'];
$city=$_POST['city'];
$state=$_POST['state'];
$country=$_POST['country'];
$pcode=$_POST['pcode'];
$phone=$_POST['phone'];
$email=$_POST['email'];
?>
<div class="confirmcheck">
<div id="delete_images">
<div id="text_delete">
Sopping Cart</div>
</div>
<table border="1" style="margin-bottom:0px !important; margin-left:10px;width:420px;margin-top: 10px;font-size: 14px;">
<tr style="background: none repeat scroll 0 0 #E4F6FF;">
<td width="210">
&nbsp;&nbsp;Item Description
</td>
<td>
Qty
</td>
<td>
Sub-total
</td>
</tr>
</table>
<table  style="margin-bottom:0px !important; margin-left:10px;width:420px;font-size: 12px;">
<?php
for($i=0;$i<mysql_num_rows($result);$i++)
{
$row = mysql_fetch_array($result);
?>
<tr>
<td width="210">&nbsp;
<?php echo $row[2];  ?>
</td>
<td  id="py">
<?php echo $row[4];
$qnttotal +=$row[4];
?>
</td>
<td>
Rs.
<?php echo $row[3].".00";
$pricetotal +=$row[3];
?>
</td>
</tr>
<?php 
}
?>
</table>
<table border="1" style="margin-left:10px;width:420px;font-size: 12px;">
<tr>
<td width="210">&nbsp;
Sub-Total
</td>
<td  id="py">
<?php echo $qnttotal; ?>
</td>
<td>
Rs.
<?php echo $pricetotal.".00"; ?>
</td>
</tr>
</table>
</div>
</div>
<b>Please Confirm Your Details</b><br /><br />
Mailing Address<br /><br />
<div style="margin-left:20px;">
Full name: <?php echo $first_name." ".$last_name; ?><br /><br />
Email: <?php echo $email; ?><br /><br />
Phone: <?php echo $phone; ?><br /><br />
Address: <?php echo $address; ?><br /><br />
City or town: <?php echo $city; ?><br /><br />
State: <?php echo $state; ?><br /><br />
County: <?php echo $country; ?><br /><br />
Zip/Post code: <?php echo $pcode; ?><br /><br />
</div>
<form method="post" class="eshop eshop-confirm" action="https://www.sandbox.paypal.com/cgi-bin/webscr"><div>
<input type="hidden" name="business" value="prakas_1294135122_biz@yahoo.co.in" />
<input type="hidden" name="return" value="<?php echo $bloginfo; ?>/?page_id=<?php echo $social_options->purchase_history_page_id; ?>&eshopaction=success" />
<input type="hidden" name="cancel_return" value="<?php echo $bloginfo; ?>/?page_id=<?php echo $social_options->purchase_history_page_id; ?>&eshopaction=cancel" />
<input type="hidden" name="notify_url" value="http://localhost/social_final/?page_id=72&eshopaction=paypalipn" />
<input type="hidden" name="first_name" value="<?php echo $first_name; ?>" />
<input type="hidden" name="last_name" value="<?php echo $last_name; ?>" />
<input type="hidden" name="email" value="<?php echo $email; ?>" />
<input type="hidden" name="phone" value="<?php echo $phone; ?>" />
<input type="hidden" name="address1" value="<?php echo $address; ?>" />
<input type="hidden" name="address2" value="" />
<input type="hidden" name="city" value="<?php echo $city; ?>" />
<input type="hidden" name="state" value="<?php echo $state; ?>" />
<input type="hidden" name="zip" value="<?php echo $pcode; ?>" />
<input type="hidden" name="country" value="<?php echo $country; ?>" />
<input type="hidden" name="reference" value="" />
<input type="hidden" name="comments" value="<?php echo $comm; ?>" />

<input type="hidden" name="amount" value="<?php echo $total; ?>" />
<input type="hidden" name="eshop_payment" value="paypal" />
<?php
$q = "SELECT * FROM {$wpdb->prefix}social_shopping_cart where user_id='".$current_user->ID."'";
$r = mysql_query($q) or die(mysql_error());
for($i=1;$i<=mysql_num_rows($r);$i++)
{
$rw = mysql_fetch_array($r);
$p = mysql_query("SELECT * FROM {$wpdb->prefix}social_product_list where name='".$rw[2]."'");
$p1=mysql_fetch_array($p);
?>
<input type="hidden" name="item_id_<?php echo $i; ?>" value="<?php echo $rw[1]; ?>" />
<input type="hidden" name="item_name_<?php echo $i; ?>" value="<?php echo $rw[2]; ?>" />
<input type="hidden" name="quantity_<?php echo $i; ?>" value="<?php echo $rw[4]; ?>" />
<input type="hidden" name="amount_<?php echo $i; ?>" value="<?php echo $p1[8]; ?>" />
<?php
}
?>
<input type="hidden" name="quantity_<?php echo $i; ?>" value="<?php echo $rw[5]; ?>" />
<input type="hidden" name="totalqny_1" value="<?php echo $qnttotal; ?>" />
<input type="hidden" name="amount" value="<?php echo $pricetotal; ?>" />
<input type="hidden" name="submit" value="Proceed to Confirmation >>" />
<input type="hidden" name="custom" value="20110413095351" />
<input type="hidden" name="rm" value="2" />
<input type="hidden" name="currency_code" value="USD" />
<input type="hidden" name="lc" value="IN" />
<input type="hidden" name="cmd" value="_ext-enter" />
<input type="hidden" name="redirect_cmd" value="_cart" />
<input type="hidden" name="upload" value="1" />
<label for="ppsubmit" class="finalize"><small><strong>Note:</strong> Submit to finalize order at PayPal.</small><br />

<input class="button submit2" type="submit" name="ppsubmit" value="Proceed to Checkout &raquo;" id="procedpur"/></label></div>
<br /><div id="contarrow1">&laquo; <a href="<?php echo $bloginfo ?>/?page_id=<?php echo $social_options->product_page_id; ?>" >Edit Details or Continue Shopping</a></div>
</form>
</td>
</tr>
</table>
<?php
}
else
{ 
?>
<p><?php echo __('Please review your order', 'wpsc'); ?></p>
<table class="productcart">
	<tr class="firstrow">
		<td class='firstcol'></td>
		<td><?php echo __('Product', 'social'); ?>:</td>
		<td><?php echo __('Quantity', 'social'); ?>:</td>
		<td class='firstcol'><?php echo __('Price', 'social'); ?>:</td>
		<td></td>
	</tr>
<?php
for($i=0;$i<mysql_num_rows($result);$i++)
{
$row = mysql_fetch_array($result);
$name = mysql_query("SELECT * FROM {$wpdb->prefix}social_product_list where name='".$row['name']."'");
$namearray=mysql_fetch_array($name);
?>
	<tr>
			<td><img src="<?php echo $bloginfo; ?>/wp-content/plugins/social/images/uploads/<?php echo $namearray['img']; ?>" width="60px" height="60px"></td>
			<td id="pname"><?php echo $row[2]; ?></td> 
			<td id="checkoutbutton">
			<form action="#" method="post" class="adjustform">
					<input type="hidden" name="prod_id" size="2" value="<?php echo $row[1]; ?>" />
					<input type="text" name="quantity" size="2" value="<?php echo $row[4]; ?>" />
					<input type="submit" value="Update" name="update" id="checkoutupdate"/>
				</form>
				</td>
			<td id="pr"><?php echo "Rs. ".$row[3].".00"; ?></td>
			<td id="rem">
			<form action="#" method="post" class="adjustform">
			        <input type="hidden" name="prod_id" size="2" value="<?php echo $row[1]; ?>" />
					<input type="submit" value="Remove" name="remove" id="checkoutremove"/>
				</form>
			</td>
		</tr>

<?php
$total +=$row[3];
}
?>
</table>

<table class="productcart1">

	<tr class='total_price'>
		<td colspan='3'>&nbsp;
		<?php echo __('Total Price', 'wpsc'); ?>
		</td>
		<td style="text-align:right;padding-right: 15px !important;">
<?php echo"Rs.".$total.".00"; ?>
		</td>
	</tr>
	</table>
<div id="contarrow">&laquo; <a href="<?php echo $bloginfo ?>/?page_id=<?php echo $social_options->product_page_id; ?>" >Continue Shopping</a></div>
<table style="border:none !important;">
<tr>
<td style="border:none !important; padding-top:20px !important; font-size:24px;padding-bottom: 17px !important;">
Please enter your contact details:
</td>
</tr>
</table>
<table style="border:none !important;  margin-bottom:0px !important;">
<tr>
<td style="border:none !important; padding:0px !important; font-size:16px;">
Fields marked with an asterisk must be filled in.
</td>
</tr>
</table>
<script type="text/javascript">
var make_payment_validation = function() {
   if (document.getElementById("pcode").value.length < 6) 
  {
    alert("Postal Code must be a 6 character long.");
  }
}
</script>
<form method="post" action="" onsubmit="javascript:return make_payment_validation();">
<table id="formpurchase">
<tr height="50px">
      <td style="padding-left: 20px !important;" colspan="2">
    	Your billing/contact details: 
      </td>
  </tr>
<tr>
      <td style="font-size:12px; color: #999999;padding-left: 20px !important;">
      First Name: *
      </td>
      <td>
      <input type="text" name="first_name" id="first_name" />
      </td>
  </tr>
    <tr>
      <td style="font-size:12px; color: #999999;padding-left: 20px !important;">
      Last Name: *
      </td>
      <td>
     <input type="text" name="last_name" />
      </td>
  </tr>
    <tr>
      <td style="font-size:12px; color: #999999;padding-left: 20px !important;">
      Address: *
      </td>
      <td>
      <input type="text" name="address" />
      </td>
  </tr>
  <tr>
      <td style="font-size:12px; color: #999999;padding-left: 20px !important;">
      City: *
      </td>
      <td>
      <input type="text" name="city" />
      </td>
  </tr>
  <tr>
      <td style="font-size:12px; color: #999999;padding-left: 20px !important;">
      State: *
      </td>
      <td>
      <input type="text" name="state" />
      </td>
  </tr>
  <tr>
      <td style="font-size:12px; color: #999999;padding-left: 20px !important;">
      Country: *
      </td>
      <td>
      <input type="text" name="country" />
      </td>
  </tr>
  <tr>
      <td style="font-size:12px; color: #999999;padding-left: 20px !important;">
      Postal code/Zip code:
      </td>
      <td>
      <input type="text" name="pcode" id="pcode"/>
      </td>
  </tr>
  <tr>
      <td style="font-size:12px; color: #999999;padding-left: 20px !important;">
      Phone: *
      </td>
      <td>
      <input type="text" name="phone" />
      </td>
  </tr>
  <tr>
      <td style="font-size:12px; color: #999999;padding-left: 20px !important;">
      Email: *
      </td>
      <td>
      <input type="text" name="email" />
      </td>
  </tr>
  <tr>
      <td style="font-size:12px; color: #999999;padding-left: 20px !important;">
      </td>
	  <td></td>
  </tr>
  <tr>
      <td style="font-size:12px; color: #999999;padding-left:200px !important;" colspan="2">
   <input type="submit" name="make_purchase" value="Make Purchase" id="makepurchase" />
      </td>
	  <td>
	  </td>
  </tr>
</table>
</form>

<?php
}
}
}
else
{
$page_name_id = mysql_query("SELECT MAX(ID) FROM {$wpdb->prefix}posts WHERE post_title ='Login'");
$shopid=mysql_fetch_array($page_name_id);
?>You're unauthorized to view this page. Why don't you <a href="<?php echo $bloginfo; ?>/?page_id=<?php echo $shopid[0]; ?>" >Login</a> and try again.
<?php }
?>
</td>
</tr>
</table>
</td>
</table>
</div>
</div>