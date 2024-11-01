<?php
if ( is_user_logged_in())
{

?>
<html>
	<head>
		<!--Make sure page contains valid doctype at the very top!-->

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>

<script type="text/javascript" src="<?php echo social_JS_URL ;?>/stepcarousel.js">

/***********************************************
* Step Carousel Viewer script- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* Visit http://www.dynamicDrive.com for hundreds of DHTML scripts
* This notice must stay intact for legal use
***********************************************/

</script>

<style type="text/css">

.stepcarousel{
position: relative; /*leave this value alone*/
border: 10px solid black;
overflow: scroll; /*leave this value alone*/
width: 270px; /*Width of Carousel Viewer itself*/
height: 200px; /*Height should enough to fit largest content's height*/
}

.stepcarousel .belt{
position: absolute; /*leave this value alone*/
left: 0;
top: 0;
}

.stepcarousel .panel{
float: left; /*leave this value alone*/
overflow: hidden; /*clip content that go outside dimensions of holding panel DIV*/
margin: 10px; /*margin around each panel*/
width: 250px; /*Width of each panel holding each content. If removed, widths should be individually defined on each content DIV then. */
}

</style>



<script type="text/javascript">

stepcarousel.setup({
	galleryid: 'mygallery', //id of carousel DIV
	beltclass: 'belt', //class of inner "belt" DIV containing all the panel DIVs
	panelclass: 'panel', //class of panel DIVs each holding content
	autostep: {enable:true, moveby:1, pause:3000},
	panelbehavior: {speed:500, wraparound:false, wrapbehavior:'slide', persist:true},
	defaultbuttons: {enable: true, moveby: 1, leftnav: ['http://i34.tinypic.com/317e0s5.gif', -5, 80], rightnav: ['http://i38.tinypic.com/33o7di8.gif', -20, 80]},
	statusvars: ['statusA', 'statusB', 'statusC'], //register 3 variables that contain current panel (start), current panel (last), and total panels
	contenttype: ['inline'] //content setting ['inline'] or ['ajax', 'path_to_external_file']
})

</script>
	</head>
	<body>
<?php
global $current_user;
global $wpdb;
get_currentuserinfo();
$user_id=$current_user->ID;
if(isset($_POST['imagesubmit']))
{


if((!empty($_FILES["photoimg"])) && ($_FILES['photoimg']['error'] == 0))
{
$filename = basename($_FILES['photoimg']['name']);
$path= dirname(__FILE__);
$path1=explode("classes",$path);
$path2=$path1[0].'images/userphoto/';
$newname = $path2.$filename;

move_uploaded_file($_FILES["photoimg"]["tmp_name"],$newname);
$get_photo=mysql_query("SELECT * FROM {$wpdb->prefix}social_photo WHERE user_id='".$user_id."' ");
$count=mysql_num_rows($get_photo);
$photoes=mysql_fetch_array($get_photo);
if($count>0)
{

$ph_img=$photoes['image'];
$ph_img1=$ph_img.",".$filename;
//echo $ph_img."<br>".$ph_img1;

$query="UPDATE {$wpdb->prefix}social_photo SET image='".$ph_img1."' where user_id='".$user_id."'";

}
else
{
echo $filename;
$query="INSERT INTO {$wpdb->prefix}social_photo VALUES('',
											'".$user_id."',
											'".$filename."')";
}
$query1=mysql_query($query);

}
}
?>

<form action="" method="post" enctype="multipart/form-data">
<?php echo $_SESSION['photo_msg']; $_SESSION['photo_msg']=''; ?>
Image:&nbsp;&nbsp;&nbsp;
<input type="file" name="photoimg" />
<input type="submit" name="imagesubmit" value="upload" />
</form>

<?php
$get_photo=mysql_query("SELECT * FROM {$wpdb->prefix}social_photo WHERE user_id='".$user_id."' ");
$photoes=mysql_fetch_array($get_photo);
$photo_array=explode(',',$photoes['image']);
$count=count($photo_array);
//print_r($photo_array);

?>
for($i=0;$i<$count;$i++)
{
?>

<img src="<?php echo social_IMAGES_URL;?>/userphoto/<?php echo $photo_array[$i]; ?>" />
</div>

<?php
 }
 ?>



<?php
}
else
{
$page_name_id = mysql_query("SELECT ID FROM wp_posts WHERE post_name ='login'");
$shopid=mysql_fetch_array($page_name_id);
?>You're unauthorized to view this page. Why don't you <a href="<?php echo $bloginfo; ?>/?page_id=<?php echo $shopid[0]; ?>" >Login</a> and try again.
<?php
}
?>

</body>
</html>