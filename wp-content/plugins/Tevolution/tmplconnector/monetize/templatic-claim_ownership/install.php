<?php
global $wp_query,$wpdb,$wp_rewrite;
/* ACTIVATING CLAIM OWNERSHIP */
if( @$_REQUEST['activated'] == 'claim_ownership' && @$_REQUEST['true'] == 1 ){ 
	update_option('claim_ownership','Active'); //ACTIVATING
	update_option('claim_enabled','Yes');
	$types['claim_post_type_value'] = get_post_types();
	update_option('templatic_settings',$types);	
} else if( @$_REQUEST['deactivate'] == 'claim_ownership' && @$_REQUEST['true'] == 0 ){
	delete_option('claim_enabled');
	delete_option('claim_ownership'); //DEACTIVATING
}
/* EOF - CLAIM OWNERSHIP ACTIVATION */

add_action( 'init', 'add_claim_stylesheet' );
/* INCLUDING A LANGUAGE FILE */
if(file_exists(TEMPL_MONETIZE_FOLDER_PATH.'templatic-claim_ownership/language.php') 
&& is_active_addons('claim_ownership'))
{
	include (TEMPL_MONETIZE_FOLDER_PATH . "templatic-claim_ownership/language.php");
}

/* INCLUDING A FUNCTIONS FILE */
if(file_exists(TEMPL_MONETIZE_FOLDER_PATH.'templatic-claim_ownership/claim_functions.php') 
&& is_active_addons('claim_ownership'))
{
	include (TEMPL_MONETIZE_FOLDER_PATH . "templatic-claim_ownership/claim_functions.php");
}

/* INCLUDING A WIDGET FILE */
if(file_exists(TEMPL_MONETIZE_FOLDER_PATH.'templatic-claim_ownership/claim_widget.php') 
&& is_active_addons('claim_ownership'))
{
	include (TEMPL_MONETIZE_FOLDER_PATH . "templatic-claim_ownership/claim_widget.php");
}

/* INCLUDING STYLESHEET */

function add_claim_stylesheet()
{
	wp_register_style( 'claim-style', TEMPL_PLUGIN_URL.'tmplconnector/monetize/templatic-claim_ownership/css/style.css');
	wp_enqueue_style( 'claim-style' );
}


/* CALL A FUNCTION TO ADD DASHBOARD METABOX */
if (is_active_addons('claim_ownership'))
{
	add_action('wp_dashboard_setup','add_claim_dashboard_metabox');
}

/* CALL A FUNCTION TO ADD METABOX IN POST TYPES */
if (is_active_addons('claim_ownership'))
{
	add_action('admin_init','add_claim_metabox_posts');
}

/* CALL A FUNCTION TO ADD A WIDGET */
if (is_active_addons('claim_ownership'))
{
	add_action('widgets_init','add_claim_widget');
}

if (is_active_addons('claim_ownership'))
{
	/*
	 * Add Filter for create the general setting sub tab for basic setting
	 */
	add_filter('templatic_general_settings_subtabs', 'claim_setting',11); 
	function claim_setting($sub_tabs ) {
		
		$sub_tabs['basic']='Claim Settings';					
		return $sub_tabs;
	}
	
	/*
	 * Add Action for display basic setting data
	 */
	add_action('templatic_general_setting_data','claim_setting_data');
	function claim_setting_data($column)
	{
		$tmpdata = get_option('templatic_settings');		
		switch($column)
		{
			case 'basic' :
					?>
						<tr>
						 <label for="ilc_tag_class"><p class="description"><?php _e('These settings enable you to select the post type for which you want to enable the claim feature. By default it will be enabled for all the post types. By default it will be display as a link. <br/> After selecting the post types, move on to <a href="widgets.php">Widgets</a> page to set up the widget. It will display a link to fill up the form and user will be able to claim for the particular post.',DOMAIN); ?></p></label>
							 <th><label><?php _e('Select Post Types',DOMAIN);?></label></th>
							 <td>
							 <?php $value = $tmpdata['claim_post_type_value']; ?>
								<?php $types = get_post_types();
									foreach ($types as $type) :
									if($type == 'attachment' || $type == 'revision' || $type =='nav_menu_item') { } else { ?>
							   <div class="element">
									<input type="checkbox" name="claim_post_type_value[]" id="<?php echo $type; ?>" value="<?php echo $type; ?>" <?php if(@$value && in_array($type,$value)) { echo "checked=checked";  } ?>><label for="<?php echo $type; ?>">&nbsp;<?php echo $type; } ?></label>
								</div>
								<?php endforeach; ?>
							</td>
						</tr>
					<?php
				break;
			default:
				break;
		}
		//echo $column."<br>";
	}
	/* Finish claim setting data */
}
?>