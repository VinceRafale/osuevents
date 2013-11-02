<?php
/* CREATE A TABLE TO STORE THE BLOCK IP ADDRESS DATA */
global $wpdb;
$ip_db_table_name= strtolower($wpdb->prefix . "ip_settings");
global $ip_db_table_name;
if($wpdb->get_var("SHOW TABLES LIKE \"$ip_db_table_name\"") != $ip_db_table_name){
	$ip_table = 'CREATE TABLE IF NOT EXISTS `'.$ip_db_table_name.'` (
	  `ipid` int(11) NOT NULL AUTO_INCREMENT,
	  `ipaddress` varchar(255) NOT NULL,
	  `ipstatus` varchar(25) NOT NULL,
	  PRIMARY KEY (`ipid`)
	)';
	$wpdb->query($ip_table);
}
/* EOF - CREATE A TABLE */
?>