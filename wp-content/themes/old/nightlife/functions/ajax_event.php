<?php
$file = dirname(__FILE__);
$file = substr($file,0,stripos($file, "wp-content"));
require($file . "/wp-load.php");
if(isset($_REQUEST['ptype']) &&$_REQUEST['ptype'] == 'favorite'){
	if(isset($_REQUEST['action']) && $_REQUEST['action']=='add')	{
		if(isset($_REQUEST['st_date']) && $_REQUEST['st_date'] != '' && $_REQUEST['st_date'] != 'undefined' )
		{
			add_to_attend_event($_REQUEST['pid'],$_REQUEST['st_date'],$_REQUEST['end_date']);
		}
		else
			add_to_attend_event($_REQUEST['pid']);
	}else{
		if(isset($_REQUEST['st_date']) && $_REQUEST['st_date'] != '' && $_REQUEST['st_date'] != 'undefined')
			remove_from_attend_event($_REQUEST['pid'],$_REQUEST['st_date'],$_REQUEST['end_date']);
		else
			remove_from_attend_event($_REQUEST['pid']);
	}
}
?>