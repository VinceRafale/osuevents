<?php
$file = dirname(__FILE__);
$file = substr($file,0,stripos($file, "wp-content"));
require($file . "/wp-load.php");
if(isset($_REQUEST['pkid'])){
$packid = $_REQUEST['pkid']; }else{
	$pxid = 1;
}
$pckid = $_REQUEST['pckid'];
$post_type = $_REQUEST['post_type'];
$taxonomy = $_REQUEST['taxonomy'];
$all_cat_id = str_replace('|',',',$_REQUEST['pckid']);
global  $price_db_table_name,$wpdb ;
	if($packid != "")
	{
	$pricesql = $wpdb->get_row("select * from $wpdb->posts where ID='".$packid."'"); 
	$homelist = get_post_meta($packid,'feature_amount',true);
	if(!$homelist){ $homelist =0; }
	$catlist =  get_post_meta($packid,'feature_cat_amount',true);
	if(!$catlist){ $catlist =0; }
	$bothlist = $catlist + $homelist;
	$packprice = get_post_meta($packid,'package_amount',true);
	$is_featured = get_post_meta($packid,'is_featured',true);
	$alive_days = get_post_meta($packid,'validity',true);
	$none = 0;
	
	$priceof = array($homelist,$catlist,$bothlist,$none,$packprice,$is_featured,$alive_days);
	$rawrsize = sizeof($priceof);
	
	$returnstring = "";
	
	//go through the array, using a unique identifier to mark the start of each new record
	for($i=0;$i<$rawrsize;$i++)
	{
		
		$returnstring .= $priceof[$i];
		$returnstring .= '###RAWR###';
	}
	
	echo $returnstring;
	}
	if(isset($_REQUEST['pckid'])) {
		$pckid = $_REQUEST['pckid'];
		$edit_id ='';
		global $monetization;
		if($pckid != ""){
			$monetization->fetch_monetization_packages_front_end('','ajax_packages_checkbox',$post_type,$taxonomy,$all_cat_id);
		}  
	}
?>