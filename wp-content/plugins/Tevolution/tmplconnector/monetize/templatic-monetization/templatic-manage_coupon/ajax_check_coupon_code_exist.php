<?php /* ajax calling file to check/validate coupon going to generate by admin ( check its exist or not )*/
$file = dirname(__FILE__);
$file = substr($file,0,stripos($file, "wp-content"));
require($file . "/wp-load.php");	

global $wpdb;
$post_table = $wpdb->prefix."posts";
$add_coupon = $_REQUEST['add_coupon'];
$startdate	= $_REQUEST['startdate'];
$enddate 	= $_REQUEST['enddate'];
$post_id	= $_REQUEST['post_id'];
$subsql= '';
if(isset($post_id) && $post_id !='')
	$subsql =  " and ID != $post_id";

$add_coupon = "select ID from $post_table where post_title ='".$add_coupon."' and post_type ='coupon_code' and post_status='publish' $subsql ";
$coupon_id = $wpdb->get_var($add_coupon);
$coupon_startdate = get_post_meta($coupon_id,'startdate',true);
$coupon_enddate = get_post_meta($coupon_id,'enddate',true);
$result = '';
if (($coupon_startdate <= $startdate &&  $coupon_enddate >= $startdate) || ($coupon_startdate <= $enddate &&  $coupon_enddate >= $enddate))
{
	$result = "<p>Coupon already exist with the same name and within the same start date and date date period.</p>";
}
echo $result;exit;
?>