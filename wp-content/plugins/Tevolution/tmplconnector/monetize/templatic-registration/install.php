<?php
global $wp_query,$wpdb,$wp_rewrite,$post;
define('TEMPL_REGISTRATION_FOLDER_PATH',TEMPL_MONETIZE_FOLDER_PATH.'templatic-registration/');
/* conditions for activation of login wizard */
if(@$_REQUEST['activated'] == 'templatic-login' && @$_REQUEST['true']==1){
	update_option('templatic-login','Active');
	$tmpdata['allow_autologin_after_reg'] = 'No';
	update_option('templatic_settings',$tmpdata);
	/* insert two fields of user name and email while activation this meta box */
	global $current_user;
	$postname = 'user_fname';
	$postid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_name = '" . $postname . "'" );
	if(!$postid)
	{
		$my_post = array();
		//$admin_title = $_POST['admin_title'];
		$site_title = 'E-mail';
		$ctype = 'text';
		$htmlvar_name = 'user_email';
		$admin_desc = '';
		//cancel $clabels = $_POST['clabels'];
		//cancel $default_value = $_POST['default_value'];
		$sort_order = 1;
		$is_active = 'publish';
		$on_registration = 1;
		$on_profile = 1;
		$option_values = '';
		$is_require = 1;
		$my_post['post_title'] = $site_title;
		$my_post['post_name'] = $htmlvar_name;
		$my_post['post_content'] = $admin_desc;
		$my_post['post_status'] = $is_active;
		$my_post['post_author'] = 1;
		$on_author_page = 1;
		$my_post['post_type'] = 'custom_user_field';
		$custom = array("ctype"		=> $ctype,
							"sort_order" 		=> $sort_order,
							"on_registration"	=> $on_registration,
							"on_profile"		=> $on_profile,
							"option_values"		=> $option_values,
							"is_require"		=> $is_require,
							"on_author_page"	=> $on_author_page
						);
		$last_postid = wp_insert_post( $my_post );
		/* Finish the place geo_latitude and geo_longitude in postcodes table*/
		if(is_plugin_active('wpml-translation-management/plugin.php')){
			if(function_exists('wpml_insert_templ_post'))
				wpml_insert_templ_post($last_postid,'custom_user_field'); /* insert post in language */
		}
		foreach($custom as $key=>$val)
			{				
				update_post_meta($last_postid, $key, $val);
			}
		
		$site_title = 'User name';
		$ctype = 'text';
		$htmlvar_name = 'user_fname';
		$admin_desc = '';
		//cancel $clabels = $_POST['clabels'];
		//cancel $default_value = $_POST['default_value'];
		$sort_order = 2;
		$is_active = 'publish';
		$on_registration = 1;
		$on_profile = 1;
		$option_values = '';
		$is_require = 1;
		$my_post['post_title'] = $site_title;
		$my_post['post_name'] = $htmlvar_name;
		$my_post['post_content'] = $admin_desc;
		$my_post['post_status'] = $is_active;
		$my_post['post_author'] = 1;
		$on_author_page = 1;
		$my_post['post_type'] = 'custom_user_field';
		$custom = array("ctype"		=> $ctype,
							"sort_order" 		=> $sort_order,
							"on_registration"	=> $on_registration,
							"on_profile"		=> $on_profile,
							"option_values"		=> $option_values,
							"is_require"		=> $is_require,
							"on_author_page"	=> $on_author_page
						);
		$last_postid = wp_insert_post( $my_post );
		/* Finish the place geo_latitude and geo_longitude in postcodes table*/
		if(is_plugin_active('wpml-translation-management/plugin.php')){
			if(function_exists('wpml_insert_templ_post'))
				wpml_insert_templ_post($last_postid,'custom_user_field'); /* insert post in language */
		}
		foreach($custom as $key=>$val)
			{				
				update_post_meta($last_postid, $key, $val);
			}
		}
		
}else if(@$_REQUEST['deactivate'] == 'templatic-login' && @$_REQUEST['true']==0){
		delete_option('templatic-login');
		/* delete two fields of user name and email while deavtivation this meta box */
		$postname = 'user_fname';
		$postid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_name = '" . $postname . "'" );
		wp_delete_post($postid);
		$postname = 'user_email';
		$postid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_name = '" . $postname . "'" );
		wp_delete_post($postid);
}
/*
name:addicted_search_rewrite
description: wp rewrite rule --**/
function addicted_search_rewrite($wp_rewrite) {
	$rules = array('wp-content/plugins/registration/registration.php' => '/');
	$wp_rewrite->rules = $rules + $wp_rewrite->rules;
}
add_filter('generate_rewrite_rules', 'addicted_search_rewrite');

/*
name:templ_add_subadmin_menu
description: coading to add submenu under main menu--**/
if(file_exists(TEMPL_REGISTRATION_FOLDER_PATH.'install.php') && is_active_addons('templatic-login')){
	add_action('templ_add_admin_menu_', 'templ_add_subadmin_menu',1);
	function templ_add_subadmin_menu()
	{ 
		$menu_title1 = __('User Custom Fields',DOMAIN);
		$hook = add_submenu_page('templatic_system_menu', $menu_title1,$menu_title1, 'administrator', 'user_custom_fields', 'my_user_plugin_function');
		add_action( "load-$hook", 'add_screen_options_user_custom_fields' ); /* CALL A FUNCTION TO ADD SCREEN OPTIONS */
		function add_screen_options_user_custom_fields()
		{
			$option = 'per_page';
			$args = array(
				'label' => 'User custom fields',
				'default' => 10,
				'option' => 'user_custom_fields_per_page'
				);
			add_screen_option( $option, $args ); /* ADD SCREEN OPTION */
		}
	}
}
/**-- coading to add submenu under main menu--**/


add_action('init','templ_registration_style_script');
function templ_registration_style_script()
{	
	wp_enqueue_script('jquery');	
}

if(file_exists(TEMPL_REGISTRATION_FOLDER_PATH.'registration.php') && is_active_addons('templatic-login')){
	
	if(file_exists(TEMPL_REGISTRATION_FOLDER_PATH . 'registration_functions.php'))
	{
		define(TT_CUSTOM_USERMETA_FOLDER_PATH, TEMPL_REGISTRATION_FOLDER_PATH.'custom_usermeta/');
		/**--below are the main file which will work with registration -**/
		include_once(TEMPL_REGISTRATION_FOLDER_PATH . 'registration_functions.php');
		include_once(TEMPL_REGISTRATION_FOLDER_PATH . 'lang_reg.php');
		include_once(TEMPL_REGISTRATION_FOLDER_PATH . 'login_box_widget.php');
	}
	if(@$_REQUEST['ptype'] == 'register' || @$_REQUEST['ptype'] == 'login') {
		//include (TEMPL_REGISTRATION_FOLDER_PATH."registration.php");
		$template =  apply_filters('templ_add_template_page_filter',$template);		
		include($template);	
	}else if(@$_REQUEST['ptype'] == 'profile'){ //exit;	
		$template =  apply_filters('templ_add_template_page_filter',$template);
		include($template);		
	}
	function filter_my_theme_nav_bars($items, $args) {
	global $current_user;
	$login_url=strstr(home_url(),'?')? home_url()."&ptype=login" : home_url()."?ptype=login";	
	$register_url=strstr(home_url(),'?')? home_url()."&ptype=register" : home_url()."?ptype=register";	
    if($args->theme_location == 'primary') {
		if($current_user->ID){
			$loginlink = '<li class="home' . ((is_home())? ' ' : '') . '"><a href="' .wp_logout_url(site_url()). '">' . __('Log out') . '</a></li>'; 
		}else{
					
			$loginlink = '<li class="home' . (($_REQUEST['ptype']=='login')? ' current_page_item' : '') . '"><a href="' .$login_url . '">' . __('Login',DOMAIN) . '</a></li>'; 
		}
		if($current_user->ID){
			$reglink = '<li class="home' . ((is_home())? ' ' : '') . '"><a href="' . get_author_posts_url($current_user->ID) . '">' . $current_user->display_name . '</a></li>'; 
		}else{
			$users_can_register = get_option('users_can_register');
			if($users_can_register){
				
				$reglink = '<li class="home' . (($_REQUEST['ptype']=='register')? ' current_page_item' : '') . '"><a href="' .$register_url . '">' . __('Register',DOMAIN) . '</a></li>';
			}
		}
        $items = $items. $loginlink.$reglink ;
    } 
	if($args->theme_location == 'secondory') {
        if($current_user->ID){
			$loginlink = '<li class="home' . (($_REQUEST['ptype']=='login')? ' current_page_item' : '') . '"><a href="' . $login_url. '">' . __('Login',DOMAIN) . '</a></li>'; 
		}else{
			$loginlink = '<li class="home' . ((is_home())? ' current_page_item' : '') . '"><a href="' .wp_logout_url(). '">' . __('LogOut') . '</a></li>'; 
		}
		if($current_user->ID){
			$reglink = '<li class="home' . ((is_home())? ' current_page_item' : '') . '"><a href="' . get_author_posts_url($current_user->ID) . '">' . $current_user->display_name . '</a></li>'; 
		}else{
			$users_can_register = get_option('users_can_register');
			if($users_can_register){
				$reglink = '<li class="home' . (($_REQUEST['ptype']=='register')? ' current_page_item' : '') . '"><a href="' . $register_url . '">' . __('Register',DOMAIN) . '</a></li>';
			}
		}
        $items = $items. $loginlink.$reglink ;
    }
 
    if($args->theme_location == 'footer') {
        $dlink = '<li class="login">' . wp_loginout('', false) . '</li>' . wp_register('<li class="admin">', '</li>', false);
        $items .= $dlink;
    }
 
    return $items;
	}
	
	add_filter('wp_nav_menu_items', 'filter_my_theme_nav_bars', 10, 2);
	
	/*
	name : my_user_plugin_function
	description :Function to insert file for add/edit/delete options for custom fields BOF */
	function my_user_plugin_function(){
		if(@$_REQUEST['action'] == 'addnew'){
			include (TEMPL_REGISTRATION_FOLDER_PATH . "admin_custom_usermeta_edit.php");
		}else{
			include (TEMPL_REGISTRATION_FOLDER_PATH . "admin_custom_usermeta_list.php");
		}
	}
	/**-- Function to insert file for add/edit/delete options for custom fields EOF --**/
	
	add_action('login_form','sfc_register_add_login_button');
	function sfc_register_add_login_button() {
		if(@$_REQUEST['ptype']){
		$action = $_REQUEST['ptype'];
		}
		if (@$action){ echo '<p><fb:login-button v="2" registration-url="'.site_url('wp-login.php?action=register', 'login').'" scope="email,user_website" onlogin="window.location.reload();" /></p>';
		}
	}
	/* registration validation for special fields */
	add_action('wp_head','tmpl_reg_js');
	function tmpl_reg_js(){
		global $wp_query;
		// If a static page is set as the front page, $pagename will not be set. Retrieve it from the queried object
		$post = $wp_query->get_queried_object();
		$template = get_post_meta( $post->ID, '_wp_page_template', TRUE );
		if((is_page() && $template =='page-template_form.php') || (isset($_REQUEST['ptype']) && $_REQUEST['ptype'] !='')){ 
			/* include only for pages and registration page */
			include_once(TEMPL_REGISTRATION_FOLDER_PATH . 'registration_js.php');
		}
	}
	
	/*
	 * Add filte for create login api settings tab in general setting menu
	 */
	/*add_filter('templatic_general_settings_tab', 'login_setting',11); 
	function login_setting($tabs ) {
		
		$tabs['login-reg']='Login API settings';					
		return $tabs;
	}*/
	
	/*
	 * Satrt the login API setting main general menu
	 */
	
	/*Finish the main general tab for login api setting */
	
	/*
	 * Add Filter for create the general setting sub tab for email setting
	 */
	add_filter('templatic_general_settings_subtabs', 'registration_email_setting',13); 
	function registration_email_setting($sub_tabs ) {			
		$sub_tabs['email']='Email Settings';					
		return $sub_tabs;
	}	
	/*
	 * Create email setting data action
	 */
	add_action('templatic_general_setting_data','registration_email_setting_data',11);
	function registration_email_setting_data($column)
	{
		$tmpdata = get_option('templatic_settings');		
		switch($column)
		{
			case 'email':	
				?>
                <tr>
                	<td>
                    	<h3><?php _e('Registration Email Content Settings',DOMAIN);?></h3>
                    	<table style="width:60%"  class="widefat post">
                        	<thead>
                                <tr>
                                    <th>
                                    	<label for="email_type" class="form-textfield-label"><?php _e('Email Type',DOMAIN); ?></label>
                                    </th>
                                    <th>
                                    	<label for="email_sub" class="form-textfield-label"><?php _e('Email Subject',DOMAIN); ?></label>
                                    </th>
                                    <th>
                                    	<label for="email_desc" class="form-textfield-label"><?php _e('Email Description',DOMAIN); ?></label>
                                    </th>
                                </tr>
							</thead>
                            <tbody>
                            	<tr>
                                    <td>
                                    	<label class="form-textfield-label"><?php _e('Registration success email',DOMAIN); ?></label>
                                    </td>
                                    <td>
                                    	<textarea name="registration_success_email_subject" style="width:350px; height:130px;">Log In Details</textarea>
                                    </td>
                                    <td>
                                   		<textarea name="registration_success_email_content" style="width:350px; height:130px;"><p>Dear [#user_name#],</p><p>You can log in  with the following information:</p><p>Username: [#user_login#]</p><p>Password: [#user_password#]</p><p>You can login from [#site_login_url#] or the URL is : [#site_login_url_link#] .</p><br><p>We hope you enjoy, Thanks!</p><p>[#site_name#]</p></textarea>
                                    </td>
								</tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <?php
				break;
				case 'listing' :
				?>
					<tr>
						<td colspan="2"><?php _e('<h3>Registration Option</h3>',DOMAIN);?></td>
					</tr>
					 <tr>
						<th><label><?php _e('Allow user to autologin after registration',DOMAIN);  ?></label></th>
						<td>
						   <div class="input_wrap"><input type="radio" id="allow_autologin_after_reg" name="allow_autologin_after_reg" value="1" <?php if(isset($tmpdata['allow_autologin_after_reg']) && $tmpdata['allow_autologin_after_reg']==1){?>checked="checked"<?php }?> /><label for="allow_autologin_after_reg">&nbsp;<?php _e('Yes',DOMAIN);?></label></div>
					 
						<div class="input_wrap"><input type="radio" id="allow_autologin_after_reg1" name="allow_autologin_after_reg" <?php if(isset($tmpdata['allow_autologin_after_reg']) && $tmpdata['allow_autologin_after_reg']==0 ){?> checked="checked"<?php }?> value="0" /><label for="allow_autologin_after_reg1">&nbsp;<?php _e('No',DOMAIN);?></label> </div>
							<div class="clearfix"></div>
							<p class="description"><?php _e('These settings enable you to sign in or not automatically after registration.',DOMAIN); ?></p>
						</td>
					 </tr>  
				<?php
				break;
		}
	}
	/*
	 * Create email setting data action
	 */
	add_action('templatic_general_setting_data','legends_email_setting_data',15);
	function legends_email_setting_data($column)
	{
		$tmpdata = get_option('templatic_settings');		
		switch($column)
		{
			case 'email':	
				?>
                <tr>
                	<td>
	                    <?php echo templatic_legend_notification(); ?>
                    </td>
                </tr>
                <?php
				break;	
		}
	}
}


?>