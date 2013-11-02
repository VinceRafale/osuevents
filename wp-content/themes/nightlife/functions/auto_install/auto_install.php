<?php 
if( get_option('ptthemes_auto_install') == 'No' || get_option('ptthemes_auto_install') == ''){
	global $pagenow;
	if(!is_plugin_active('Tevolution/templatic.php') && is_admin() && 'themes.php' == $pagenow){
		add_action("admin_head", "activate_eco_plugin"); // please comment this line if you wish to DEACTIVE SAMPLE DATA INSERT.
	}else{
		if(function_exists('is_active_addons')){
			if(!is_active_addons('custom_taxonomy') || !is_active_addons('custom_fields_templates')){
				add_action("admin_head", "activate_eco_addons"); // please comment this line if you wish to DEACTIVE SAMPLE DATA INSERT.
			}else{
				// Action to admin_head for auto install
				add_action("admin_head", "nightlife_autoinstall"); // please comment this line if you wish to DEACTIVE SAMPLE DATA INSERT.
			}
		}else{
			// Action to admin_head for auto install
			add_action("admin_head", "nightlife_autoinstall"); // please comment this line if you wish to DEACTIVE SAMPLE DATA INSERT.
		}
	}
}

function activate_eco_plugin(){
	$url = home_url().'/wp-admin/plugins.php';
	add_css_to_admin();
?>	
	<div class="error" style="padding:10px 0 10px 10px;font-weight:bold;">
		<span>
			<?php _e('Thanks for choosing templatic themes, the base system of templatic is not installed at your side, Please download and activate <a id="templatic_plugin" href="'.$url.'" style="color:#21759B">Tevolution</a> plugin to start your journey of <b>'.get_current_theme().'</b>.',"templatic");?>
		</span>
	</div>
<?php 	
}
add_action('admin_head','add_css_to_admin');
function add_css_to_admin(){
	echo '<style type="text/css">
		#message1{
			display:none;
		}
	</style>';
}
function activate_eco_addons(){
	$url_custom_field = site_url()."/wp-admin/admin.php?page=templatic_system_menu&activated=custom_fields_templates&true=1";
	$url_custom_post_type = site_url()."/wp-admin/admin.php?page=templatic_system_menu&activated=custom_taxonomy&true=1";
	add_css_to_admin();
?>
		
	<div class="error" style="padding:10px 0 10px 10px;font-weight:bold;">
		<span>
			<?php _e('Thanks for choosing templatic themes,  the base system of templatic is not installed at your side Now, Please activate both <a id="templatic_plugin" href="'.$url_custom_post_type.'" style="color:#21759B">Templatic - Custom Post Types Manager</a> and <a  href="'.$url_custom_field.'" style="color:#21759B">Templatic - Custom Fields</a> addons to start your journey of <b>'.get_current_theme().'</b>.',"templatic");?>
		</span>
	</div>
	
<?php 
	} 
function nightlife_autoinstall(){
	global $wpdb;
	$wp_user_roles_arr = get_option($wpdb->prefix.'user_roles');
	global $wpdb;
	if(strstr($_SERVER['REQUEST_URI'],'themes.php') && @$_REQUEST['template']=='' && @$_GET['page']==''){
	
		$post_counts = $wpdb->get_var("select count(post_id) from $wpdb->postmeta where (meta_key='pt_dummy_content' || meta_key='tl_dummy_content') and meta_value=1");
		if($post_counts>0){
			$dummy_data_msg = 'Sample data has been <b>populated</b> on your site. Wish to delete sample data?  <a class="button_delete" href="'.get_option('home').'/wp-admin/themes.php?dummy=del">Yes Delete Please!</a>';
		}else{
			$dummy_data_msg = 'Would you like to <b>auto populate</b> sample data on your site?  <a class="button_insert" href="'.get_option('home').'/wp-admin/themes.php?dummy_insert=1">Yes, insert please</a>';
		}
		
		if(isset($_REQUEST['dummy_insert']) && $_REQUEST['dummy_insert']){
			require_once (TEMPLATE_FUNCTION_FOLDER_PATH.'auto_install/auto_install_data.php');
			$dummy_data_msg = 'Dummy data successfully <b>populated</b> on your site. Click <a href="'.get_option('home').'/wp-admin/themes.php">here</a> to continue.';
		}
		if(isset($_REQUEST['dummy']) && $_REQUEST['dummy']=='del'){
			nightlife_delete_dummy_data();
			$dummy_data_msg = 'All Dummy data has been <b>removed</b> from your database successfully! Click <a href="'.get_option('home').'/wp-admin/themes.php">here</a> to continue.';
		}
		
		
		define('THEME_ACTIVE_MESSAGE','<div class="updated templatic_autoinstall"> '.$dummy_data_msg.'</div>');
		echo THEME_ACTIVE_MESSAGE;
	}
}
//<div class="updated highlight fade"> '.@$theme_actived_success.@$dummy_deleted.$dummy_data_msg.'</div>');
function nightlife_delete_dummy_data()
{
	global $wpdb;
	delete_option('sidebars_widgets'); //delete widgets
	$productArray = array();
	$pids_sql = "select p.ID from $wpdb->posts p join $wpdb->postmeta pm on pm.post_id=p.ID where (meta_key='pt_dummy_content' || meta_key='tl_dummy_content' || meta_key='auto_install') and (meta_value=1 || meta_value='auto_install')";
	$pids_info = $wpdb->get_results($pids_sql);
	foreach($pids_info as $pids_info_obj)
	{
		wp_delete_post($pids_info_obj->ID,true);
	}
}
?>