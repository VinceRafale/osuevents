<?php
global $wp_query,$wpdb,$wp_rewrite,$current_user;
if(function_exists('add_js_script'))
	add_action('wp_head','add_js_script');
/**-- conditions for activation Custom Fields --**/
if((@$_REQUEST['activated'] == 'custom_fields_templates' && @$_REQUEST['true']==1) || (isset($_REQUEST['activated']) && $_REQUEST['activated']=='true')){
		update_option('custom_fields_templates','Active');
		$templatic_settings=get_option('templatic_settings');
		$tmpdata['templatic-category_custom_fields'] = 'No';
		update_option('templatic_settings',array_merge($templatic_settings,$tmpdata));
}else if(@$_REQUEST['deactivate'] == 'custom_fields_templates' && @$_REQUEST['true']==0){
		delete_option('custom_fields_templates');
}
/**-- coading to add submenu under main menu--**/
if(file_exists(TEMPL_MONETIZE_FOLDER_PATH.'templatic-custom_fields/install.php') && is_active_addons('custom_fields_templates')){
	add_action('templ_add_admin_menu_', 'templ_add_submenu',1);
	function templ_add_submenu()
	{
		$menu_title1 = __('Custom Fields',DOMAIN);
		global $custom_fields_screen_option;
		$custom_fields_screen_option = add_submenu_page('templatic_system_menu', $menu_title1,$menu_title1, 'administrator', 'custom_fields', 'add_custom_fields');
		add_action("load-$custom_fields_screen_option", "custom_fields_screen_options");
	}
}
/* Set the file extension for allown only image/picture file extension in upload file*/
$extension_file=array('.jpg','.JPG','jpeg','JPEG','.png','.PNG','.gif','.GIF','.jpe','.JPE');  
global $extension_file;


/* Function for screen option */
function custom_fields_screen_options() {
 	global $custom_fields_screen_option;
 	$screen = get_current_screen();
 	// get out of here if we are not on our settings page
	if(!is_object($screen) || $screen->id != $custom_fields_screen_option)
		return;
 
	$args = array(
		'label' => __('Custom Fields per page', DOMAIN),
		'default' => 10,
		'option' => 'custom_fields_per_page'
	);
	add_screen_option( 'per_page', $args );
}

function custom_fields_set_screen_option($status, $option, $value) {
	if ( 'custom_fields_per_page' == $option ) return $value;
}
add_filter('set-screen-option', 'custom_fields_set_screen_option', 10, 3);


function add_custom_fields(){
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'addnew'){
		include (TEMPL_MONETIZE_FOLDER_PATH . "templatic-custom_fields/admin_manage_custom_fields_edit.php");
	}else{
		include (TEMPL_MONETIZE_FOLDER_PATH . "templatic-custom_fields/admin_manage_custom_fields_list.php");
	}
}
if(file_exists(TEMPL_MONETIZE_FOLDER_PATH.'templatic-custom_fields/custom_fields_function.php') && is_active_addons('custom_fields_templates'))
{
	include (TEMPL_MONETIZE_FOLDER_PATH . "templatic-custom_fields/custom_fields_function.php");	
}
if(file_exists(TEMPL_MONETIZE_FOLDER_PATH.'templatic-custom_fields/language.php') && is_active_addons('custom_fields_templates'))
{
	include (TEMPL_MONETIZE_FOLDER_PATH . "templatic-custom_fields/language.php");
}
if(!file_exists(get_template_directory().'/page-template_form.php') && is_active_addons('custom_fields_templates'))
{
	$custom_fields = dirname( __FILE__ )."/page-template_form.php";
	copy($custom_fields,get_template_directory()."/page-template_form.php");
}
if(!file_exists(get_template_directory().'/page-template_advanced_search.php') && is_active_addons('custom_fields_templates'))
{
	$adv_search = dirname( __FILE__ )."/page-template_advanced_search.php";
	copy($adv_search,get_template_directory()."/page-template_advanced_search.php");
}
/*
 * chack map template
 */
if(!file_exists(get_template_directory().'/page-template_map.php') && is_active_addons('custom_fields_templates'))
{
	$custom_fields = dirname( __FILE__ )."/page-template_map.php";
	copy($custom_fields,get_template_directory()."/page-template_map.php");
}
if(!file_exists(get_template_directory().'/submition_validation.php') && is_active_addons('custom_fields_templates'))
{
	$validation = dirname( __FILE__ )."/submition_validation.php";
	copy($validation,get_template_directory()."/submition_validation.php");
}
if(file_exists(get_template_directory().'/page-template_form.php') && !is_active_addons('custom_fields_templates'))
{
	unlink(get_template_directory()."/page-template_form.php");
}
if(file_exists(get_template_directory().'/page-template_map.php') && !is_active_addons('custom_fields_templates'))
{
	unlink(get_template_directory()."/page-template_map.php");
}
if(file_exists(get_template_directory().'/page-template_advanced_search.php') && !is_active_addons('custom_fields_templates'))
{
	unlink(get_template_directory()."/page-template_advanced_search.php");
}
/* Specially for image resizer */
if(file_exists(TEMPL_MONETIZE_FOLDER_PATH.'templatic-custom_fields/image_resizer.php') && is_active_addons('custom_fields_templates'))
{
	require_once (TEMPL_MONETIZE_FOLDER_PATH . 'templatic-custom_fields/image_resizer.php');
}

/* Custom Fields Preview page Start  */

add_action( 'init', 'custom_fields_preview' ,11);
function custom_fields_preview()
{
	if(@$_REQUEST['page'] == "preview")
	{
		include(TEMPL_MONETIZE_FOLDER_PATH . "templatic-custom_fields/custom_fields_preview.php");
		exit;
	}
	if(@$_REQUEST['page'] == "paynow")
	{
		global $_wp_additional_image_sizes;		
		include(TEMPL_MONETIZE_FOLDER_PATH . "templatic-custom_fields/custom_fields_paynow.php");
		exit;
	}
	if(@$_REQUEST['page'] == "success")
	{
		include(TEMPL_MONETIZE_FOLDER_PATH . "templatic-custom_fields/success.php");
		exit;
	}
	if(@$_REQUEST['page'] == "paypal_pro_success")
	{
		$dir = ABSPATH . 'wp-content/plugins/Tevolution-paypal_pro/includes/paypal_pro_success.php';
		include($dir);
		exit;
	}
	if(@$_REQUEST['page'] == "authorizedotnet_success")
	{
		$dir = ABSPATH . 'wp-content/plugins/Tevolution-authorizedotnet/includes/authorizedotnet_success.php';
		include($dir);
		exit;
	}
	if(@$_REQUEST['page'] == "googlecheckout_success")
	{
		$dir = ABSPATH . 'wp-content/plugins/Tevolution-googlecheckout/includes/googlecheckout_success.php';
		include($dir);
		exit;
	}
	if(@$_REQUEST['page'] == "worldpay_success")
	{
		$dir = ABSPATH . 'wp-content/plugins/Tevolution-worldpay/includes/worldpay_success.php';
		include($dir);
		exit;
	}
	if(@$_REQUEST['page'] == "eway_success")
	{
		$dir = ABSPATH . 'wp-content/plugins/Tevolution-eway/includes/eway_success.php';
		include($dir);
		exit;
	}
	if(@$_REQUEST['page'] == "ebay_success")
	{
		$dir = ABSPATH . 'wp-content/plugins/Tevolution-ebay/includes/ebay_success.php';
		include($dir);
		exit;
	}
	if(@$_REQUEST['page'] == "ebs_success")
	{
		$dir = ABSPATH . 'wp-content/plugins/Tevolution-ebs/includes/ebs_success.php';
		include($dir);
		exit;
	}
	if(@$_REQUEST['page'] == "psigate_success")
	{
		$dir = ABSPATH . 'wp-content/plugins/Tevolution-psigate/includes/psigate_success.php';
		include($dir);
		exit;
	}
	if(@$_REQUEST['page'] == "2co_success")
	{
		$dir = ABSPATH . 'wp-content/plugins/Tevolution-2co/includes/2co_success.php';
		include($dir);
		exit;
	}
	if(@$_REQUEST['page'] == "stripe_success")
	{
		$dir = ABSPATH . 'wp-content/plugins/Tevolution-stripe/includes/stripe_success.php';
		include($dir);
		exit;
	}
	if(isset($_GET['stripe-listener']) && $_GET['stripe-listener'] == 'recurring') {
		$dir = ABSPATH . 'wp-content/plugins/Tevolution-stripe/includes/stripe_listener.php';
		include($dir);
		exit;
	}
	if(@$_REQUEST['page'] == 'login')
	{
		include(TEMPL_MONETIZE_FOLDER_PATH . "templatic-custom_fields/registration.php");
		exit;
	}
	if(isset($_REQUEST['page']) && $_REQUEST['page'] == 'delete')
	{		
		$current_user = wp_get_current_user();		
		if($_REQUEST['pid'])
		{
			wp_delete_post($_REQUEST['pid']);
			$link = get_author_posts_url($current_user->ID);			
			wp_redirect($link);
			exit;
		}
		
	}

}

/* Custom Fields Preview page End  */
/* Insert wordpress default fields in posts table when plugin activated */

$custom_post_types_args = array();
$custom_post_types = get_post_types($custom_post_types_args,'objects');
$post_type_arr='';
foreach ($custom_post_types as $content_type) 
{
	if($content_type->name!='nav_menu_item' && $content_type->name!='attachment' && $content_type->name!='revision' && $content_type->name!='page')
	{
		$post_type_arr .= $content_type->name.",";
	}
}
$cus_pos_type = get_option("templatic_custom_post");
if($cus_pos_type && count($cus_pos_type) > 0)
 {
	foreach($cus_pos_type as $key=> $_cus_pos_type)
	{
		$post_type_arr .= $key.",";
	}
 }
$post_type_arr = substr($post_type_arr,0,-1);

if(is_active_addons('custom_fields_templates'))
{
	global $wpdb;
	
	/* Insert Post Category into posts */
	$post_category = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'category' and $wpdb->posts.post_type = 'custom_fields'");
	if(count($post_category) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Post Category',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'category',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'post_type'=> $post_type_arr,
			'ctype'=>'post_categories',
			'htmlvar_name'=>'category',
			'sort_order' => '1',
			'is_active' => '1',
			'is_require' => '1',
			'show_on_page' => 'user_side',
			'is_edit' => 'true',
			'show_on_detail' => '0',
			'show_on_listing' => '0',
			'show_in_column' => '0',
			'is_search'=>'0',
			'field_require_desc' => 'Please Select the Category',
			'validation_type' => 'require',
			'heading_type' => '[#taxonomy_name#]',
			);
		$post_id = wp_insert_post( $my_post );
		/* Finish the place geo_latitude and geo_longitude in postcodes table*/
		if(is_plugin_active('wpml-translation-management/plugin.php')){
			if(function_exists('wpml_insert_templ_post'))
				wpml_insert_templ_post($post_id,'custom_fields'); /* insert post in language */
		}
		wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
		$ex_post_type = '';
		$ex_post_type = explode(",",$post_type_arr);
		foreach($ex_post_type as $_ex_post_type)
		 {
			add_post_meta($post_id, 'post_type_'.$_ex_post_type.'' , 'all');
		 }
	 }

	
	/* Insert Post title into posts */
	$post_title = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'post_title' and $wpdb->posts.post_type = 'custom_fields'");
	if(count($post_title) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Post Title',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'post_title',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'post_type'=> $post_type_arr,
			'ctype'=>'text',
			'htmlvar_name'=>'post_title',
			'sort_order' => '2',
			'is_active' => '1',
			'is_require' => '1',
			'show_on_page' => 'user_side',
			'is_edit' => 'true',
			'show_on_detail' => '0',
			'show_on_success' => '1',
			'show_on_listing' => '1',
			'show_in_column' => '0',
			'is_search'=>'0',
			'field_require_desc' => 'Please Enter the title',
			'validation_type' => 'require',
			'heading_type' => '[#taxonomy_name#]',
			);
		$post_id = wp_insert_post( $my_post );
		/* Finish the place geo_latitude and geo_longitude in postcodes table*/
		if(is_plugin_active('wpml-translation-management/plugin.php')){
			if(function_exists('wpml_insert_templ_post'))
				wpml_insert_templ_post($post_id,'custom_fields'); /* insert post in language */
		}
		wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
		$ex_post_type = '';
		$ex_post_type = explode(",",$post_type_arr);
		foreach($ex_post_type as $_ex_post_type)
		 {
			add_post_meta($post_id, 'post_type_'.$_ex_post_type.'' , 'all');
		 }
 
	 }
	 
	 /* Insert Post content into posts */
	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'post_content' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Post Content',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'post_content',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'post_type'=> $post_type_arr,
			'ctype'=>'texteditor',
			'show_in_column' => '0',
			'htmlvar_name'=>'post_content',
			'sort_order' => '3',
			'is_active' => '1',
			'is_require' => '1',
			'show_on_page' => 'user_side',
			'is_edit' => 'true',
			'show_on_detail' => '1',
			'show_on_listing' => '1',
			'show_in_column' => '0',
			'is_search'=>'0',
			'field_require_desc' => 'Please Enter the content',
			'validation_type' => 'require',
			'heading_type' => '[#taxonomy_name#]',
			);
		$post_id = wp_insert_post( $my_post );
		/* Finish the place geo_latitude and geo_longitude in postcodes table*/
		if(is_plugin_active('wpml-translation-management/plugin.php')){
			if(function_exists('wpml_insert_templ_post'))
				wpml_insert_templ_post($post_id,'custom_fields'); /* insert post in language */
		}
		wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
		
		$ex_post_type = '';
		$ex_post_type = explode(",",$post_type_arr);
		foreach($ex_post_type as $_ex_post_type)
		 {
			add_post_meta($post_id, 'post_type_'.$_ex_post_type.'' , 'all');
		 }

	 }

	 /* Insert Post excerpt into posts */
	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'post_excerpt' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Post Excerpt',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'post_excerpt',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'post_type'=> $post_type_arr,
			'ctype'=>'texteditor',
			'htmlvar_name'=>'post_excerpt',
			'sort_order' => '3',
			'is_active' => '1',
			'is_require' => '0',
			'show_on_page' => 'user_side',
			'show_in_column' => '0',
			'show_on_listing' => '1',
			'is_edit' => 'true',
			'show_on_detail' => '1',
			'show_in_column' => '0',
			'is_search'=>'0',
			'heading_type' => '[#taxonomy_name#]',
			);
		$post_id = wp_insert_post( $my_post );
		/* Finish the place geo_latitude and geo_longitude in postcodes table*/
		if(is_plugin_active('wpml-translation-management/plugin.php')){
			if(function_exists('wpml_insert_templ_post'))
				wpml_insert_templ_post($post_id,'custom_fields'); /* insert post in language */
		}
		wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
 		
		$ex_post_type = '';
		$ex_post_type = explode(",",$post_type_arr);
		foreach($ex_post_type as $_ex_post_type)
		 {
			add_post_meta($post_id, 'post_type_'.$_ex_post_type.'' , 'all');
		 }

	 }

	 /* Insert Post image_uploader into posts */
	 $post_images = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'post_images' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_images) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Post Images',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'post_images',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'post_type'=> $post_type_arr,
			'ctype'=>'image_uploader',
			'site_title'=>'Post Images',
			'htmlvar_name'=>'post_images',
			'sort_order' => '4',
			'is_active' => '1',
			'is_require' => '1',
			'show_on_page' => 'user_side',
			'show_in_column' => '0',
			'show_on_detail' => '1',
			'show_on_listing' => '1',
			'show_in_email' => '0',
			'is_edit' => 'true',
			'is_search'=>'0',
			'heading_type' => '[#taxonomy_name#]',
			);
		$post_id = wp_insert_post( $my_post );
		/* Finish the place geo_latitude and geo_longitude in postcodes table*/
		if(is_plugin_active('wpml-translation-management/plugin.php')){
			if(function_exists('wpml_insert_templ_post'))
				wpml_insert_templ_post($post_id,'custom_fields'); /* insert post in language */
		}
		wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
 		
		$ex_post_type = '';
		$ex_post_type = explode(",",$post_type_arr);
		foreach($ex_post_type as $_ex_post_type)
		 {
			add_post_meta($post_id, 'post_type_'.$_ex_post_type.'' , 'all');
		 }

	 }

	 /* Insert Post heading type into posts */
	 $post_images = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_title = '[#taxonomy_name#]' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_images) == 0)
	 {
		$my_post = array(
			 'post_title' => '[#taxonomy_name#]',
			 'post_content' => 'Its default heading type - If you are not selected any heading type , then your field inserted in default heading (In front end the name of heading will shown LIKE place information, event information etc.)',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'basic_inf',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'post_type'=> $post_type_arr,
			'ctype'=>'heading_type',
			'site_title'=>'[#taxonomy_name#]',
			'htmlvar_name'=>'basic_inf',
			'sort_order' => '5',
			'is_active' => '1',
			'show_on_page' => 'user_side',
			'show_on_detail' => '0',
			'show_in_column' => '0',
			'is_search'=>'0',
			'is_edit' => 'true',
			'heading_type' => '[#taxonomy_name#]',
			);
		$post_id = wp_insert_post( $my_post );
		/* Finish the place geo_latitude and geo_longitude in postcodes table*/
		if(is_plugin_active('wpml-translation-management/plugin.php')){
			if(function_exists('wpml_insert_templ_post'))
				wpml_insert_templ_post($post_id,'custom_fields'); /* insert post in language */
		}
		wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
 		
		$ex_post_type = '';
		$ex_post_type = explode(",",$post_type_arr);
		foreach($ex_post_type as $_ex_post_type)
		 {
			add_post_meta($post_id, 'post_type_'.$_ex_post_type.'' , 'all');
		 }

	 }


}

define('SELECT_PAY_MEHTOD_TEXT',__('Select Payment Method'));
/* End of Insert wordpress default fields in posts table when plugin activated */


if(is_active_addons('custom_fields_templates'))
{
	/*
	 * Add Filter for create the general setting sub tab for Post page setting
	 */
	add_filter('templatic_general_settings_subtabs', 'post_page_setting',10); 
	function post_page_setting($sub_tabs ) {
		
		$sub_tabs['listing']='Basic settings';		
		return $sub_tabs;
	}
	/*
	 * Crate action for post par listing setting
	 */
	add_action('templatic_general_setting_data','post_page_setting_data');
	function post_page_setting_data($column)
	{
		$tmpdata = get_option('templatic_settings');
		switch($column)
		{
			case 'listing' :
					?>
					<p class="description"><?php _e('These settings will be applied to category listing page, detail/single post and submit post page.',DOMAIN);?></p>
                    <tr>
                    	<td colspan="2"><h3><?php _e('Listing page Settings',DOMAIN);?></h3></td>
                    </tr>
					 <tr>
                     <th><label><?php _e('Select Post type for home page listing',DOMAIN); ?></label></th>
                     <td>
                     <?php $types = get_post_types();
								foreach ($types as $type) :
									if($type == 'attachment' || $type == 'revision' || $type == 'page' || $type =='nav_menu_item') { } else { ?>
							   <div class="element">
									<input type="checkbox" name="home_listing_type_value[]" id="<?php echo $type; ?>" value="<?php echo $type; ?>" <?php if(@$tmpdata['home_listing_type_value'] && in_array($type,$tmpdata['home_listing_type_value'])) { echo "checked=checked";  } ?>><label for="<?php echo $type; ?>">&nbsp;<?php echo $type; } ?></label>
								</div>
								<?php endforeach; ?>
                            <?php do_action('templ_post_type_description');?>            
                    </td>
                    </tr>
                    <tr>
                    	<td colspan="2"><h3><?php _e('Submit page Settings',DOMAIN);?></h3></td>
                    </tr> 
					  <tr>
						<th><label><?php _e('Show custom fields categorywise',DOMAIN);	$templatic_category_custom_fields =  @$tmpdata['templatic-category_custom_fields']; if(!isset($templatic_category_custom_fields) && $templatic_category_custom_fields == ''){update_option('templatic-category_custom_fields','No');}?></label></th>
						<td>
							<div class="element" style="width: 100px;" id="custom_fields_wp_footer">
								 <div class="input_wrap"> <input type="radio" id="templatic-category_custom_fields" name="templatic-category_custom_fields" value="Yes" <?php if($templatic_category_custom_fields == 'Yes' || $templatic_category_custom_fields ==''){?>checked="checked"<?php }?> /><label for="templatic-category_custom_fields">&nbsp;<?php _e('Yes',DOMAIN);?></div>
								 
								 <div class="input_wrap"> <input type="radio" id="templatic-category_custom_fields1" name="templatic-category_custom_fields" <?php if($templatic_category_custom_fields == 'No'){?> checked="checked"<?php }?> value="No" /><label for="templatic-category_custom_fields1">&nbsp;<?php _e('No',DOMAIN);?> </div>
							</div>
						   <label for="ilc_tag_class"><p class="description"><?php _e('Select if you want to show category custom fields wise.',DOMAIN);?></p></label>
						</td>
					 </tr>
					 <tr>
						<th><label><?php _e('Category display setting',DOMAIN); ?></label></th>
						<td>
							<div class="element">
								 <div class="input_wrap">
									<?php $templatic_category_type =  @$tmpdata['templatic-category_type']; ?>
								  <select id="templatic-category_type" name="templatic-category_type" style="vertical-align:top;width:200px;" >
									<option value=""><?php  _e('Please select category type',DOMAIN);  ?></option>
									<option value="checkbox" <?php if($templatic_category_type == 'checkbox' ) { echo "selected=selected";  } ?>><?php _e('Check Box',DOMAIN); ?></option>
									<option value="select" <?php if($templatic_category_type == 'select' ) { echo "selected=selected";  } ?>><?php _e('Select Box',DOMAIN); ?></option>
									<option value="multiselectbox" <?php if($templatic_category_type == 'multiselectbox' ) { echo "selected=selected";  } ?>><?php _e('Multi-select Box',DOMAIN); ?></option>
								</select> 
							</div>
							</div>
						   <label for="ilc_tag_class"><p class="description"><?php _e('Specify the format in which you want to display the categories on Submit page.',DOMAIN);?></p></label>
						</td>
					 </tr>
					 <tr>
						<th><label><?php _e('Maximum Image Upload Size',DOMAIN);	$templatic_image_size =  @$tmpdata['templatic_image_size']; ?></label></th>
						<td>
							<div class="element">
								 <div class="input_wrap">
								 <input type="text" id="templatic_image_size" name="templatic_image_size" value="<?php echo $templatic_image_size; ?>"/><?php _e(' In Bytes',DOMAIN); ?> </div>
								</div>
							</div>
						   <label for="ilc_tag_class"><p class="description"><?php _e('You can specify the maximum size of image upload here',DOMAIN);?>.</p></label>
						</td>
					 </tr> 
                      <tr>
						<th><label><?php _e('Set default status for free submissions',DOMAIN);	$post_default_status =  @$tmpdata['post_default_status']; ?></label></th>
						<td>
							<select name="post_default_status">
									<option value="publish" <?php if($post_default_status == 'publish')echo "selected";?>><?php _e('Published',DOMAIN); ?></option>
									<option value="draft" <?php if($post_default_status == 'draft')echo "selected";?>><?php _e('Draft',DOMAIN); ?></option>
								</select>
								 <p class="description"><?php _e('Set the default status for posts from here. By default the post will be in published status.',DOMAIN);?></p>
						</td>
					 </tr> 
                    <tr>
						<th><label><?php _e('Set default status for paid submissions',DOMAIN);	$post_default_status_paid =  @$tmpdata['post_default_status_paid']; ?></label></th>
						<td>
							<select name="post_default_status_paid">
									<option value="publish" <?php if($post_default_status_paid == 'publish')echo "selected";?>><?php _e('Published',DOMAIN); ?></option>
									<option value="draft" <?php if($post_default_status_paid == 'draft')echo "selected";?>><?php _e('Draft',DOMAIN); ?></option>
							</select>
								 <p class="description"><?php _e('Set the default status for paid submission posts from here. By default the post will be in published status.',DOMAIN);?></p>
						</td>
					 </tr> 
                    
                     <tr>
						<th><label><?php _e('Default status for expired listings',DOMAIN);	$post_listing_ex_status =  @$tmpdata['post_listing_ex_status']; ?></label></th>
						<td>
							<select name="post_listing_ex_status">
									<option value="draft" <?php if($post_default_status_paid == 'draft')echo "selected";?>><?php _e('Draft',DOMAIN); ?></option>
                                    <option value="trash" <?php if($post_default_status_paid == 'trash')echo "selected";?>><?php _e('Trash',DOMAIN); ?></option>
							</select>
								 <p class="description"><?php _e('Set the default status for expired listings posts from here. By default the post will be in Draft status.',DOMAIN);?></p>
						</td>
					 </tr> 
					 
                     <tr>
						<th><label><?php _e('Listing Email Notification',DOMAIN);	$listing_email_notification =  @$tmpdata['listing_email_notification']; ?></label></th>
						<td>
							<select name="listing_email_notification">
									<option value="">-- Choose One --</option>
                                    <option value="1" <?php if($listing_email_notification == '1')echo "selected";?>>1</option>
                                    <option value="2" <?php if($listing_email_notification == '2')echo "selected";?>>2</option>
                                    <option value="3" <?php if($listing_email_notification == '3')echo "selected";?>>3</option>
                                    <option value="4" <?php if($listing_email_notification == '4')echo "selected";?>>4</option>
                                    <option value="5" <?php if($listing_email_notification == '5')echo "selected";?>>5</option>
                                    <option value="6" <?php if($listing_email_notification == '6')echo "selected";?>>6</option>
                                    <option value="7" <?php if($listing_email_notification == '7')echo "selected";?>>7</option>
                                    <option value="8" <?php if($listing_email_notification == '8')echo "selected";?>>8</option>
                                    <option value="9" <?php if($listing_email_notification == '9')echo "selected";?>>9</option>
                                    <option value="10" <?php if($listing_email_notification == '10')echo "selected";?>>10</option>
							</select>
								 <p class="description"><?php _e('Enter number of days before pre expiry notification Email will be sent.',DOMAIN);?></p>
						</td>
					 </tr> 

                    <tr>
						<th><label><?php _e('Enable terms and conditions for submit form',DOMAIN); 
						$tev_accept_term_condition =  @$tmpdata['tev_accept_term_condition'];
						if($tev_accept_term_condition ==1){ $checked ="checked=checked"; }else{
							$checked='';
						}
						?> <label> </th>
						<td>
							<input id="tev_accept_term_condition" type="checkbox" value="1" name="tev_accept_term_condition" <?php echo $checked; ?>/>&nbsp; <?php _e('Enable',DOMAIN); ?>
						</td>
					</tr> 
					
					<tr>
						<th><label><?php _e('Terms and condition text',DOMAIN); 
						$term_condition_content =  @$tmpdata['term_condition_content'];
						?> <label> </th>
						<td>
							<textarea id="term_condition_content" name="term_condition_content"><?php echo $term_condition_content; ?></textarea>
							 <p class="description"><?php _e('Enter the lable for terms and conditions checkbox going to be display in submit form,"<b>Enable terms and conditions for submit form</b>" must be selected.',DOMAIN);?></p>
						</td>
					</tr>
                    <tr>
                    	<td colspan="2"><h3><?php _e('Detail/Single post page Settings',DOMAIN);?></h3></td>
                    </tr> 
					 <tr>
						<th><label><?php _e('View Counter Enable',DOMAIN);	$templatic_view_counter =  @$tmpdata['templatic_view_counter']; ?></label></th>
						<td>
							<div class="element">
								 <div class="input_wrap">                     
								 <input type="radio" name="templatic_view_counter" value="Yes" <?php if($templatic_view_counter == 'Yes' || $templatic_view_counter ==''){?>checked="checked"<?php }?> id="yes" /><label for="yes">&nbsp;<?php _e('Yes',DOMAIN);?></div>
								 
								 <div class="input_wrap"> <input type="radio" name="templatic_view_counter" <?php if($templatic_view_counter == 'No'){?> checked="checked"<?php }?> value="No" id="no" /><label for="no"/>&nbsp;<?php _e('No',DOMAIN);?>
								 
								</div>
								</div>
							</div>
						   <label for="ilc_tag_class"><p class="description"><?php _e('Display the single post view counter enable or disable.',DOMAIN);?>.</p></label>
						</td>
					 </tr>
					 
                     <tr>
						<th><label><?php _e('Select Related Post By',DOMAIN);	$related_post =  @$tmpdata['related_post']; ?></label></th>
						<td>
							<input type="radio" name="related_post" value="categories"  <?php if(isset($related_post) && $related_post=='categories') echo 'checked'; ?>/>&nbsp;<label for="related_post_categories"> Categorie Wise</label> <br/>
                            <input type="radio" name="related_post" value="tags" <?php if(isset($related_post) && $related_post=='tags') echo 'checked'; ?>/>&nbsp;<label for="related_post_tags">Tag Wise</label>
								 <p class="description"><?php _e('Set the default status for posts from here. By default the post will be in published status.',DOMAIN);?></p>
						</td>
					 </tr>                    
                      <tr>
						<th><label><?php _e('Show facebook share Detail Page?',DOMAIN);	$facebook_share_detail_page =  @$tmpdata['facebook_share_detail_page']; ?></label></th>
						<td>
							<input type="radio" name="facebook_share_detail_page" value="yes"  <?php if(isset($facebook_share_detail_page) && $facebook_share_detail_page=='yes') echo 'checked'; ?>/>&nbsp;<label for="facebook_share_detail_page_yes"> Yes</label> <br/>
                            <input type="radio" name="facebook_share_detail_page" value="no" <?php if(isset($facebook_share_detail_page) && $facebook_share_detail_page=='no') echo 'checked'; ?>/>&nbsp;<label for="facebook_share_detail_page_yes_no">No</label>
								 <p class="description"><?php _e('Select if you want to show facebook share on detail page.',DOMAIN);?></p>
						</td>
					 </tr>  
                      <tr>
						<th><label><?php _e('Show Google Plus one in Detail Page?',DOMAIN);	$google_share_detail_page =  @$tmpdata['google_share_detail_page']; ?></label></th>
						<td>
							<input type="radio" name="google_share_detail_page" value="yes"  <?php if(isset($google_share_detail_page) && $google_share_detail_page=='yes') echo 'checked'; ?>/>&nbsp;<label for="google_share_detail_page_yes"> Yes</label> <br/>
                            <input type="radio" name="google_share_detail_page" value="no" <?php if(isset($google_share_detail_page) && $google_share_detail_page=='no') echo 'checked'; ?>/>&nbsp;<label for="google_share_detail_page_no">No</label>
								 <p class="description"><?php _e('Select if you want to show google plus one on detail page.',DOMAIN);?></p>
						</td>
					 </tr>  
                      <tr>
						<th><label><?php _e('Show twitter share in Detail Page?',DOMAIN);	$twitter_share_detail_page =  @$tmpdata['twitter_share_detail_page']; ?></label></th>
						<td>
							<input type="radio" name="twitter_share_detail_page" value="yes"  <?php if(isset($twitter_share_detail_page) && $twitter_share_detail_page=='yes') echo 'checked'; ?>/>&nbsp;<label for="twitter_share_detail_page_yes"> Yes</label> <br/>
                            <input type="radio" name="twitter_share_detail_page" value="no" <?php if(isset($twitter_share_detail_page) && $twitter_share_detail_page=='no') echo 'checked'; ?>/>&nbsp;<label for="twitter_share_detail_page_no">No</label>
								 <p class="description"><?php _e('Select if you want to show twitter share on detail page.',DOMAIN);?></p>
						</td>
					 </tr> 
					          
					<?php
				break;				
			default:
				break;
		}
	}

}// finish the if condition for check custom_fields_templates active or not
?>