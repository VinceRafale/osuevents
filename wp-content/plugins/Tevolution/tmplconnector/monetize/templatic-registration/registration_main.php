<?php
/******************************************************************
=======  PLEASE DO NOT CHANGE BELOW CODE  =====
You can add in below code but don't remove original code.
This code to include registration, login and edit profile page.
This file is included in functions.php of theme root at very last php coding line.

You can call registration, login and edit profile page  by the link 
edit profile : http://mydomain.com/?ptype=profile  => echo site_url().'/?ptype=profile';
registration : http://mydomain.com/?ptype=register => echo site_url().'/?ptype=register';
login : http://mydomain.com/?ptype=login => echo site_url().'/?ptype=login';
logout : http://mydomain.com/?ptype=login&action=logout => echo site_url().'/?ptype=login&action=logout';
********************************************************************/

define('TEMPL_REGISTRATION_FOLDER',TEMPL_MONETIZE_FOLDER_PATH . "templatic-registration/");
define('TEMPL_REGISTRATION_URI',TEMPL_MONETIZE_FOLDER_PATH. "templatic-registration/");

include_once(TEMPL_REGISTRATION_FOLDER.'registration_language.php'); // language file
/* name : templ_add_template_reg_page
description : Conditions to retrive the page HTML from the url.*/
add_filter('templ_add_template_page_filter','templ_add_template_reg_page');
function templ_add_template_reg_page($template)
{ 
	if(isset($_REQUEST['ptype']) && $_REQUEST['ptype']!=""){
	if($_REQUEST['ptype']=='profile')
	{
		global $current_user; get_currentuserinfo(); 
		if(!$current_user->ID)
		{ 
			wp_redirect(home_url().'/?ptype=login');
			exit;
		} 
		$template = TEMPL_REGISTRATION_FOLDER.'profile.php';
	}else
	if($_REQUEST['ptype'] == 'register' || $_REQUEST['ptype'] == 'login')
	{
		$template =  TEMPL_REGISTRATION_FOLDER . "registration.php";
	}
	}

	return $template;
}
/* name : get_user_nice_name_plugin
description : function to get the user name.*/
function get_user_nice_name_plugin($fname,$lname='')
{
	global $wpdb;
	if($lname)
	{
		$uname = $fname.'-'.$lname;
	}else
	{
		$uname = $fname;
	}
	$nicename = strtolower(str_replace(array("'",'"',"?",".","!","@","#","$","%","^","&","*","(",")","-","+","+"," "),array('','','','-','','-','-','','','','','','','','','','-','-',''),$uname));
	$nicenamecount = $wpdb->get_var("select count(user_nicename) from $wpdb->users where user_nicename like \"$nicename\"");
	if($nicenamecount=='0')
	{
		return trim($nicename);
	}else
	{
		$lastuid = $wpdb->get_var("select max(ID) from $wpdb->users");
		return $nicename.'-'.$lastuid;
	}
}
?>