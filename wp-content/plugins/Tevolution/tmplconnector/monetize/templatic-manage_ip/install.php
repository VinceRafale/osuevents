<?php
global $wp_query,$wpdb,$wp_rewrite;
/* ACTIVATING MANAGE IP */
if( @$_REQUEST['activated'] == 'manage_ip' && @$_REQUEST['true'] == 1 ){ 
	update_option('manage_ip','Active'); //ACTIVATING	
} else if( @$_REQUEST['deactivate'] == 'manage_ip' && @$_REQUEST['true'] == 0 ){
	delete_option('manage_ip'); //DEACTIVATING
}
/* EOF - MANAGE IP ACTIVATION */
/* INCLUDING A LANGUAGE FILE */
if(file_exists(TEMPL_MONETIZE_FOLDER_PATH.'templatic-manage_ip/language.php'))
{
	include (TEMPL_MONETIZE_FOLDER_PATH . "templatic-manage_ip/language.php");
}

/* INCLUDING A FUNCTIONS FILE */
if(file_exists(TEMPL_MONETIZE_FOLDER_PATH.'templatic-manage_ip/manage_ip_functions.php'))
{
	include (TEMPL_MONETIZE_FOLDER_PATH . "templatic-manage_ip/manage_ip_functions.php");
}

/* INCLUDING A FILE TO CREATE A DATABASE TABLE */
if(file_exists(TEMPL_MONETIZE_FOLDER_PATH.'templatic-manage_ip/db_table_creation.php'))
{
	include (TEMPL_MONETIZE_FOLDER_PATH . "templatic-manage_ip/db_table_creation.php");
}


add_action("admin_init", "admin_init_func"); /* CALL A FUNCTION TO CREATE A META BOX IN BACK END */



/*
 * Add Filter for create the security settings tab on general setting menu
 *
 */
add_filter('templatic_general_settings_tab', 'security_setting',12); 
function security_setting($tabs ) {
	
	$tabs['security-settings']='Security Settings';					
	return $tabs;
}	
/*
 * Satrt the security main general tab
 */
add_action('templatic_general_data','security_setting_general');
function security_setting_general($tab)
{
	$tmpdata = get_option('templatic_settings');
	switch($tab)
	{
		case 'security-settings':
				?>
				<p class="description"><?php _e('Security related settings are very essential for your domain. From here you can block an IP address by mentioning it the textarea and also you can enable SSL on your domain',DOMAIN); ?>.<strong> <?php _e('Note',DOMAIN); ?> : </strong><?php _e('You must purchase the SSL certificate in order to use it on your site.',DOMAIN); ?></p>
				<tr>
					<th><label for="ilc_intro"><?php _e('Blocked IP addresses',DOMAIN); ?></label></th>
					<td>
						<?php global $ip_db_table_name,$wpdb;
							$parray = $wpdb->get_results("select ipaddress from $ip_db_table_name where ipstatus='1'");
							$mvalue = ""; ?>
						<textarea name="block_ip" id= "block_ip" ><?php foreach($parray as $pay)
							{
								$ip = $pay->ipaddress;
								$val = $pay->ipaddress;
								if($val != "")
								{
									$mvalue .= $val.",";
								}
							}
							echo trim($mvalue); ?></textarea>
						<input type="hidden" name="ipaddress2" id="ipaddress2" value="<?php echo trim($mvalue); ?>"/><br/>
						<p class="description"><?php _e('The IP addresses you have blocked previously appear here. Once you remove them from the list below, they will be unblocked. Suspicious IP addresses should be added to the list below in order to prevent listings done from that IP',DOMAIN); ?>.</p>
					</td>
				</tr>
				<tr>
					<th><label><?php _e('Enable SSL on submit and registration Page',DOMAIN);	$templatic_is_allow_ssl =  @$tmpdata['templatic-is_allow_ssl']; ?></label></th>
					<td>
						<div class="element">
							<div class="input_wrap"> <input type="radio" id="templatic-is_allow_ssl" name="templatic-is_allow_ssl" value="Yes" <?php if($templatic_is_allow_ssl == 'Yes' ){?>checked="checked"<?php }?> /><label for="templatic-is_allow_ssl"> <?php _e('Yes',DOMAIN);?></div>
							<div class="input_wrap"> <input type="radio" id="templatic-is_allow_ssl1" name="templatic-is_allow_ssl" <?php if($templatic_is_allow_ssl == 'No' || $templatic_is_allow_ssl ==''){?> checked="checked"<?php }?> value="No" /><label for="templatic-is_allow_ssl1"> <?php _e('No',DOMAIN);?></div>
						</div>
						<label for="ilc_tag_class"><p class="description"><?php _e('This option will enable SSL on submit and registration page, this option requires an SSL certificate enabled on your server',DOMAIN);?>.</p></label>
					</td>
				</tr>
				<?php
			break;
		
	}
}
/*Finish the main security general tab */
?>