<?php
global $wp_query,$wpdb;
/**-- condition for activate bulk upload --**/
if(@$_REQUEST['activated'] == 'bulk_upload' && @$_REQUEST['true']==1){
		update_option('bulk_upload','Active');
}else if(@$_REQUEST['deactivate'] == 'bulk_upload' && @$_REQUEST['true']==0){
		delete_option('bulk_upload');
}

/**-- Add submenu under Templatic main menu--**/
if(file_exists(TEMPL_MONETIZE_FOLDER_PATH.'templatic-bulk_upload/install.php') && is_active_addons('bulk_upload')){
	add_action('templ_add_admin_menu_', 'templ_add_submenu_bulk_upload',1);
	function templ_add_submenu_bulk_upload()
	{
		$menu_title = __('Bulk Import/Export',DOMAIN);	
		add_submenu_page('templatic_system_menu', $menu_title,$menu_title, 'administrator', 'bulk_upload', 'templ_bulk_upload');
	}
}

/*	included file containing bulk upload functionality	*/
function templ_bulk_upload()
{
	if(file_exists(TEMPL_MONETIZE_FOLDER_PATH.'templatic-bulk_upload/templatic_bulk_upload.php')){
		include_once(TEMPL_MONETIZE_FOLDER_PATH.'templatic-bulk_upload/templatic_bulk_upload.php');
	}
}
?>