<?php /* ajax calling file to check/validate coupon going to add by user from front end*/
$file = dirname(__FILE__);
$file = substr($file,0,stripos($file, "wp-content"));
require($file . "/wp-load.php");	

global $wpdb;
$post_table = $wpdb->prefix."posts";
$add_coupon = $_REQUEST['add_coupon'];

$add_coupon	= "select ID from $post_table where post_title ='".$add_coupon."' and post_type ='coupon_code' and post_status='publish'";
$coupon_id	= $wpdb->get_var($add_coupon);
$coupondisc = get_post_meta($coupon_id,'coupondisc',true);
$couponamt 	= get_post_meta($coupon_id,'couponamt',true);
$start_date = strtotime(get_post_meta($coupon_id,'startdate',true));
$end_date 	= strtotime(get_post_meta($coupon_id,'enddate',true));
$todays_date = strtotime(date("Y-m-d"));
if ($start_date <= $todays_date && $end_date >= $todays_date)
{
	if($coupondisc == 'per')
	{
		$result = _e("Congratulations!!!You save ",DOMAIN).$couponamt;
	}
	if($coupondisc == 'amt')
	{
		$price = fetch_currency_with_position($couponamt);
		$result = _e("Congratulations!!!You save ",DOMAIN).$price;
	}
}
echo $result;exit;
?>