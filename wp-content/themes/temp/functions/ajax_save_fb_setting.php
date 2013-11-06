<?php
$file = dirname(__FILE__);
$file = substr($file,0,stripos($file, "wp-content"));
require($file . "/wp-load.php");	
global $wpdb,$current_user;
if(isset($_REQUEST['appid']) && $_REQUEST['appid'] != '')
{
	update_user_meta($current_user->ID,'appID',$_REQUEST['appid']);
	update_user_meta($current_user->ID,'secret',$_REQUEST['secret_id']);
	update_user_meta($current_user->ID,'pageID',$_REQUEST['page_id']);
}
echo facebook_events($current_user->ID);exit;
?>