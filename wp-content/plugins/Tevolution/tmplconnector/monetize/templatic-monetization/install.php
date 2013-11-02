<?php
global $wp_query,$wpdb,$wp_rewrite;
define('TEMPL_MONETIZATION_PATH',TEMPL_MONETIZE_FOLDER_PATH . "templatic-monetization/"); 
/* ACTIVATING PRICE PACKAGES */
if( (isset($_REQUEST['activated']) && $_REQUEST['activated'] == 'monetization') && ($_REQUEST['true'] && $_REQUEST['true'] == 1) )
{
	update_option('monetization','Active');
	update_option('currency_symbol','$');
	update_option('currency_code','USD');
	update_option('currency_pos','1');
	require_once(TEMPL_MONETIZATION_PATH.'add_dummy_packages.php');
}
else if( (isset($_REQUEST['deactivate']) && $_REQUEST['deactivate'] == 'monetization') && (isset($_REQUEST['true']) && $_REQUEST['true'] == 0 ))
{
	delete_option('monetization');
}
/* EOF - PRICE PACKAGES ACTIVATION */

/* INCLUDING A LANGUAGE FILE */
if(file_exists(TEMPL_MONETIZE_FOLDER_PATH.'templatic-monetization/language.php') 
&& is_active_addons('monetization'))
{
	include (TEMPL_MONETIZE_FOLDER_PATH . "templatic-monetization/language.php");
}

/* INCLUDING A FUNCTIONS FILE */
if(file_exists(TEMPL_MONETIZE_FOLDER_PATH.'templatic-monetization/price_package_functions.php') 
&& is_active_addons('monetization'))
{
	include (TEMPL_MONETIZE_FOLDER_PATH . "templatic-monetization/price_package_functions.php");
}

/* CODE TO CREATE AN ADMIN SUBPAGE MENU FOR PRICE PACKAGES */
if(is_active_addons('monetization'))
{
	add_action('templ_add_admin_menu_', 'add_subpage_monetization',1); /* ADD HOOK */
	add_action('init','add_farbtastic_style_script');
	function add_farbtastic_style_script()
	{
		wp_enqueue_script( 'farbtastic' );
		wp_enqueue_style( 'farbtastic' );
	}
	if(file_exists(TEMPL_MONETIZATION_PATH."templatic-payment_options/payment_functions.php"))
		include(TEMPL_MONETIZATION_PATH."templatic-payment_options/payment_functions.php");
	if(file_exists(TEMPL_MONETIZATION_PATH."templatic-manage_coupon/install.php"))
		include(TEMPL_MONETIZATION_PATH."templatic-manage_coupon/install.php");
	function add_subpage_monetization()
	{
		$page_title = __('Monetization',DOMAIN); /* DEFINE PAGE TITLE AND MENU TITLE */
		$transcation_title = __('Transactions',DOMAIN); /* DEFINE PAGE TITLE AND MENU TITLE */
		/* CREATING A SUB PAGE MENU TO TEMPLATIC SYSTEM */
		$hook = add_submenu_page('templatic_system_menu',$page_title,$page_title,'administrator', 'monetization', 'add_monetization');
		
		add_action( "load-$hook", 'add_screen_options' ); /* CALL A FUNCTION TO ADD SCREEN OPTIONS */
		function add_screen_options()
		{
			$option = 'per_page';
			$args = array(
				'label' => 'Show record per page for monetization',
				'default' => 10,
				'option' => 'package_per_page'
				);
			add_screen_option( $option, $args ); /* ADD SCREEN OPTION */
		}
		
		$hook_transaction = add_submenu_page('templatic_system_menu',$transcation_title,$transcation_title,'administrator', 'transcation', 'add_transcation');
		
		add_action( "load-$hook_transaction", 'add_screen_options_transaction' ); /* CALL A FUNCTION TO ADD SCREEN OPTIONS */
		function add_screen_options_transaction()
		{
			$option = 'per_page';
			$args = array(
				'label' => 'Transaction',
				'default' => 10,
				'option' => 'transaction_per_page'
				);
			add_screen_option( $option, $args ); /* ADD SCREEN OPTION */
		}
	}
	/* EOF - CREATE SUB PAGE MENU */
}

/* FUNCTION CALLED ON SUB PAGE MENU HOOK */
function add_monetization()
{
	include(TEMPL_MONETIZATION_PATH."templatic_monetization.php");
}

/* FUNCTION CALLED ON SUB PAGE MENU HOOK */
function add_transcation()
{
	if(isset($_REQUEST['page']) && $_REQUEST['page'] == 'transcation' && isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		include(TEMPL_MONETIZATION_PATH."templatic_transaction_detail_report.php");
	elseif(isset($_REQUEST['page']) && $_REQUEST['page'] == 'transcation')
		include(TEMPL_MONETIZATION_PATH."templatic_transaction_report.php");
}


/*
Name :payment_option_plugin_function 
desc : Function to insert file for add/edit/delete options for payment options/gateway settings BOF 
*/
function payment_option_plugin_function(){
	if($_REQUEST['tab'] == 'payment_options' && (!isset($_GET['payact']) && $_GET['payact']=='')){
		templ_payment_methods();
	}else if((isset($_GET['payact']) && $_GET['payact']=='setting') && (isset($_GET['id']) && $_GET['id'] != '')){
		include (TEMPL_MONETIZATION_PATH."templatic-payment_options/admin_paymethods_add.php");
	}
}
/* Function to insert file for add/edit/delete options for custom fields EOF --**/

/*
Name :payment_option_plugin_function 
desc : Function to insert file for add/edit/delete options for payment options/gateway settings BOF 
*/
function manage_coupon_plugin_function(){
	if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'manage_coupon'){
		manage_coupon_function();
	}
}
/* Function to insert file for add/edit/delete options for custom fields EOF --**/


/*
Name: templ_add_pkg_js
desc : return the script for fetching price packages
*/

function templ_add_pkg_js(){
	global $wp_query;
	// If a static page is set as the front page, $pagename will not be set. Retrieve it from the queried object
	$post = $wp_query->get_queried_object();
	$template = get_post_meta( $post->ID, '_wp_page_template', TRUE );

	if(is_page() && $template =='page-template_form.php'){
	include(TEMPL_MONETIZE_FOLDER_PATH.'templatic-monetization/price_package_js.php');
	}
}

add_action('wp_head','templ_add_pkg_js');

/*transaction table BOF*/

global $wpdb;
$transection_db_table_name = $wpdb->prefix . "transactions";
if($wpdb->get_var("SHOW TABLES LIKE \"$transection_db_table_name\"") != $transection_db_table_name)
{
	$transaction_table = 'CREATE TABLE IF NOT EXISTS `'.$transection_db_table_name.'` (
	`trans_id` bigint(20) NOT NULL AUTO_INCREMENT,
	`user_id` bigint(20) NOT NULL,
	`post_id` bigint(20) NOT NULL,
	`post_title` varchar(255) NOT NULL,
	`status` int(2) NOT NULL,
	`payment_method` varchar(255) NOT NULL,
	`payable_amt` float(25,5) NOT NULL,
	`payment_date` datetime NOT NULL,
	`paypal_transection_id` varchar(255) NOT NULL,
	`user_name` varchar(255) NOT NULL,
	`pay_email` varchar(255) NOT NULL,
	`billing_name` varchar(255) NOT NULL,
	`billing_add` text NOT NULL,
	PRIMARY KEY (`trans_id`)
	)';
	$wpdb->query($transaction_table);	
}
/*transaction table EOF*/

/* NAME : FILTER FOR SCREEN OPTIONS
DESCRIPTION : THIS FUNCTION WILL FILTER DATA ACCORDING TO SCREEN OPTIONS */
add_filter('set-screen-option', 'package_table_set_option', 10, 3);
function package_table_set_option($status, $option, $value)
{
    return $value;
}

if (is_active_addons('monetization')) 
{
	/*
	 * Add Filter for create the general setting sub tab for Transaction setting 
	 */
	add_filter('templatic_general_settings_subtabs', 'transaction_setting',16); 
	function transaction_setting($sub_tabs ) {
		
		$sub_tabs['transaction']='Transaction Settings';					
		return $sub_tabs;
	}
	
	/* 
	 * Create do action for transaction setting data
	 */
	add_action('templatic_general_setting_data','transaction_setting_data');
	function transaction_setting_data($column)
	{	
		$tmpdata = get_option('templatic_settings');
		$map_setting =  $tmpdata;				
		switch($column)
		{
			case 'transaction':
					
				/* name : templatic_load_color_picker_script
				   description : load farbtastic script. */
				function templatic_load_color_picker_script() {
					wp_enqueue_script('farbtastic');
				}
				/* name : templatic_load_color_picker_style
				   description : load farbtastic style. */
				function templatic_load_color_picker_style() {
					wp_enqueue_style('farbtastic');	
				}
				add_action('admin_print_scripts-widgets.php', 'templatic_load_color_picker_script');
				add_action('admin_print_styles-widgets.php', 'templatic_load_color_picker_style');
			?>
				
				<p class="description"><?php _e('The settings listed below will be applied to the dashboard widgets. You can set the color for the post type in order to differentiate the post types on the Transaction dashboard widget.',DOMAIN); ?></p>
				<tr>
					<th><label><?php _e('Transaction Settings(Select Post Types)',DOMAIN);?></label></th>
					<td>
					   <div class="element">
						 <?php if(isset($tmpdata['trans_post_type_value'])) { $value = $tmpdata['trans_post_type_value']; } ?>
						
						
						<?php $types = get_post_types();
							foreach ($types as $type) :
							
							if($type == 'attachment' || $type == 'revision' || $type =='nav_menu_item') { } else {
							$color_taxonomy = 'trans_post_type_colour_'.$type;
							$color_value = $tmpdata[$color_taxonomy];
							?>
							<script type="text/javascript">
								jQuery(document).ready(function($){
									jQuery('#trans_post_type_colour_<?php echo $type; ?>').farbtastic('#color_<?php echo $type; ?>');
								});
								function showColorPicker(id)
								{
									document.getElementsByName(id)[0].style.display = '';				
								}
							</script>
						<?php
							if(isset($color_value) && $color_value!= '') { $color_taxonomy_value = $color_value; } else { $color_taxonomy_value = '#'; }?>
								 <label style="min-width: 150px; display:inline-block;"> <input <?php if(isset($value) && in_array($type,$value)) { echo "checked=checked";  } ?> type="checkbox" value="<?php echo $type; ?>" id="trans_post_type_value" name="trans_post_type_value[]"><?php echo " ".$type; ?> </label>
								 <label><input type="text" name="trans_post_type_colour_<?php echo $type; ?>" onclick="showColorPicker(this.id);" id="color_<?php echo $type; ?>" value="<?php if(isset($color_taxonomy_value) && $color_taxonomy_value != '') { echo $color_taxonomy_value; }?>" ><?php echo " "; ?><img style="position:relative;vertical-align:middle;" src="<?php echo  plugin_dir_url( __FILE__ ); ?>images/Color_block.png" /></label>
								 <div id="trans_post_type_colour_<?php echo $type; ?>"  name="color_<?php echo $type; ?>" style="display:none" ></div>
								 <div class="clearfix"></div>
							<?php } endforeach; ?>

					  </div>
					   <label for="ilc_tag_class"><p class="description"><?php _e('These settings enable you to select the post type for which you want to see the transaction of particular post type on transaction dashboard widget.',DOMAIN); ?></p></label>
					</td>
				 </tr>                                                  
				<?php					
				break;
		}
	}
	/*Finish the transaction setting data  */	
}
?>