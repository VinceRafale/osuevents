<?php
/*
Plugin Name: Tevolution
Plugin URI: http://templatic.com/
Description: Tevolution is a collection of Templatic features to enhance your website.
Version: 1.0.5
Author: Templatic
Author URI: http://templatic.com/
*/
	error_reporting(0);
	ob_start();
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	require_once(plugin_dir_path( __FILE__ ).'classes/templconnector.class.php' );
	/* for auto updates */
	
	if(strstr($_SERVER['REQUEST_URI'],'plugins.php')){
		require_once('wp-updates-plugin.php');
		new WPUpdatesPluginUpdater( 'http://wp-updates.com/api/1/plugin', 96, plugin_basename(__FILE__) );
	}
	/* end */
	define('TEMPL_MONETIZE_FOLDER_PATH', plugin_dir_path( __FILE__ ).'tmplconnector/monetize/');
	define('TEMPL_PLUGIN_URL', plugin_dir_url( __FILE__ ));
	define('TT_CUSTOM_USERMETA_FOLDER_PATH',TEMPL_MONETIZE_FOLDER_PATH.'templatic-registration/custom_usermeta/');
	define('TEMPL_PAYMENT_FOLDER_PATH',TEMPL_MONETIZE_FOLDER_PATH.'templatic-monetization/templatic-payment_options/payment/');
	define('MY_PLUGIN_SETTINGS_URL',site_url().'/wp-admin/admin.php?page=templatic_system_menu&activated=true');
	define('DOMAIN', 'templatic');
	define('TEVOLUTION_VERSION','1.0.4');
	define('PLUGIN_NAME','Tevolution Plugin');
	
	
	load_textdomain( DOMAIN, plugin_dir_path( __FILE__ ).'languages/en_US.mo' );
	//load_plugin_textdomain( DOMAIN, plugin_dir_path( __FILE__ ));
	load_plugin_textdomain( DOMAIN,false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	global $templatic;
	if(class_exists('templatic')){	
		$templatic = new Templatic( __FILE__ );
		global $templatic;
	}
	if ( ! class_exists( 'Templatic_connector' ) ) {
		require_once( plugin_dir_path( __FILE__ ).'classes/templconnector.class.php' );			
		//require_once( plugin_dir_path( __FILE__ ).'classes/main.connector.class.php' );			
		$templconnector = new Templatic_connector( __FILE__ );		
		global $templconnector;
	}	
    if ( apply_filters( 'tmplconnector_enable', true ) == true ) {
		if(!function_exists('wp_get_current_user')) {
			include(ABSPATH . "wp-includes/pluggable.php"); 
		}
		$file = dirname(__FILE__);
		$file = substr($file,0,stripos($file, "wp-content"));
	
        require_once( plugin_dir_path( __FILE__ ).'tmplconnector/templatic-connector.php' );
		require_once(plugin_dir_path( __FILE__ ).'classes/main.connector.class.php' );				
        global $tmplconnector;
		/* remove custom user meta box*/
		function remove_custom_metaboxes() {
			$custom_post_types_args = array();  
			$custom_post_types = get_post_types($custom_post_types_args,'objects');   
			foreach ($custom_post_types as $content_type) 
			{
			 remove_meta_box( 'postcustom' , $content_type->name , 'normal' ); //removes custom fields for page
			}
		}
		add_action( 'admin_menu' , 'remove_custom_metaboxes' );
    }
	/*	Files for updating page templates START	 */
	if(file_exists(plugin_dir_path( __FILE__ ).'/tmplconnector/update_pagetemplates.php')){
		require_once(plugin_dir_path( __FILE__ ).'/tmplconnector/update_pagetemplates.php');
	}
	/*	Files for updating page templates END	 */
	function my_plugin_activate() { 
		
		/*set templatic settings option */
			$templatic_settings=get_option('templatic_settings');
			$settings=array(
						 'templatic_view_counter' =>'Yes',
						 'related_post' =>'categories',
						 'facebook_share_detail_page' =>'no',
						 'google_share_detail_page' =>'no',
						 'twitter_share_detail_page' =>'no',
						 'templatin_rating' =>'no',
						 'post_default_status'=>'publish',
						 'post_default_status_paid' =>'draft',
						 'send_to_frnd'   =>'send_to_frnd',
						 'allow_autologin_after_reg' =>'0',
						 'templatic_widgets' => array( 'templatic_listing_post','templatic_browse_by_categories','templatic_browse_by_tag','templatic_advertisements','templatic_aboust_us','templatic_slider',
												'templatic_facebook','templatic_twiter','templatic_popular_post','templatic_recent_review')
						);
			
			if(empty($templatic_settings))
			{
				update_option('templatic_settings',$settings);	
			}else{
				update_option('templatic_settings',array_merge($templatic_settings,$settings));
			}
			/* finish the templatic settings option */
		
		/*	Updated default payment gateway option on plugin activation START	*/
		if(!get_option('payment_method_paypal')){
			$paypal_update = array(
				'name' => 'Paypal',
				'key' => 'paypal',
				'isactive' => 1,
				'display_order' => 1,
				'payOpts' => array
					(
						array
							(
								'title' => 'Merchant Id',
								'fieldname' => 'merchantid',
								'value' => 'myaccount@paypal.com',
								'description' => 'Example : myaccount@paypal.com',
							),

					),			
			);
			update_option('payment_method_paypal',$paypal_update);
		}
		if(!get_option('payment_method_prebanktransfer')){
			$prebanktransfer_update = array(
				'name' => 'Pre Bank Transfer',
				'key' => 'prebanktransfer',
				'isactive' => 1,
				'display_order' => 6,
				'payOpts' => array
					(
						array
							(
								'title' => 'Bank Information',
								'fieldname' => 'bankinfo',
								'value' => 'ICICI Bank',
								'description' => 'Enter the bank name to which you want to transfer payment',
							),

						array
							(
								'title' => 'Account ID',
								'fieldname' => 'bank_accountid',
								'value' => 'AB1234567890',
								'description' => 'Enter your bank Account ID',
							),

					),
			);
			update_option('payment_method_prebanktransfer',$prebanktransfer_update);
			
			
		}
		/*	Updated default payment gateway option on plugin activation END	*/
		
		
		update_option('myplugin_redirect_on_first_activation', 'true');
		$default_pointers = "wp330_toolbar,wp330_media_uploader,wp330_saving_widgets,wp340_choose_image_from_library,wp340_customize_current_theme_link";
		update_user_meta(get_current_user_id(),'dismissed_wp_pointers',$default_pointers);
	}
	function my_plugin_deactivate() { 
		delete_option('myplugin_redirect_on_first_activation');
	}
	register_activation_hook(__FILE__, 'my_plugin_activate');
	register_deactivation_hook(__FILE__, 'my_plugin_deactivate');
	


function wp_admin_widgets_admin_init1()
{
        /* Register our script. */
        wp_enqueue_script('admin-widgets');
        wp_enqueue_script('link');
        // wp_enqueue_script('xfn');
         

}

/* Plugin automatic update cosding end */


add_action('admin_init', 'wp_admin_widgets_admin_init1');
add_action('wp_head', 'recatcha_settings');

function recatcha_settings(){
	$a = get_option('recaptcha_options'); 
?>
	<script type="text/javascript">
				 var RecaptchaOptions = {
					 theme : '<?php echo $a['comments_theme']; ?>',
					lang : '<?php echo $a['recaptcha_language']; ?>',
					tabindex :'<?php echo $a['comments_tab_index']?>'
				 };
	</script>
<?php }

/*set page title for sign in page*/
if(isset($_REQUEST['ptype']) && ($_REQUEST['ptype'] == 'register' || $_REQUEST['ptype'] == 'login') )
	add_action('wp_title','nightlife_rigeter_the_title',10,2);
	
	
/*name : nightlife_rigeter_the_title
description :set page title for sign in page
return : page title*/
	function nightlife_rigeter_the_title($title){
			$title = SIGN_IN_PAGE_TITLE;
			return $title;
		}
		
/*set page title for prifile page*/
if($_REQUEST['ptype'] == 'profile' )
	add_action('wp_title','nightlife_profile_the_title',10,2);
	
	
/*name : nightlife_profile_the_title
description :set page title for sign in page
return : page title*/
	function nightlife_profile_the_title($title){
			$title = EDIT_PROFILE_TITLE;
			return $title;
		}
		
/*
 * add action for set the auto update for tevolution plugin
 * Functio Name: tevolution_plugin_row
 * Return : Display the plugin new version update message
 */
function tevolution_plugin_row()
{
	$wptuts_plugin_remote_path = 'http://templatic.com/update.php'; // Version check file path
	$request = wp_remote_post($wptuts_plugin_remote_path, array('body' => array('action' => 'info')));	
	
	if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
		$response=unserialize($request['body']);
	}	
	$remote_version=$response->new_version;		
	 if (version_compare(TEVOLUTION_VERSION, $remote_version, '<'))
	 {	
             $new_version = version_compare(TEVOLUTION_VERSION, $remote_version, '<') ? __('There is a new version of Tevolution available.', DOMAIN) .' <a class="thickbox" title="Tevolution Forms" href="plugin-install.php?tab=plugin-information&plugin=templatic&TB_iframe=true&width=640&height=808">'. sprintf(__('View version %s Details', DOMAIN), $remote_version) . '</a>. ' : '';
		  
		  $ajax_url = esc_url( add_query_arg( array( 'slug' => 'tevolution', 'action' => 'tevolution' , '_ajax_nonce' => wp_create_nonce( 'tevolution' ), 'TB_iframe' => true ,'width'=>500,'height'=>400), admin_url( 'admin-ajax.php' ) ) );
		  $file='Tevolution/templatic.php';
		  $download= wp_nonce_url( self_admin_url('update.php?action=upgrade-plugin&plugin=').$file, 'upgrade-plugin_' . $file);
		echo '</tr><tr class="plugin-update-tr"><td colspan="3" class="plugin-update"><div class="update-message">' . $new_version . __( 'or <a href="'.$ajax_url.'" class="thickbox" title="Tevolution Update">update now</a>.', DOMAIN) .'</div></td>';
	
	 }
}

/*
 * Function Name: tevolution_update_login
 * Return: update tevolution plugin version after templatic member login
 */
function tevolution_update_login()
{
	check_ajax_referer( 'tevolution', '_ajax_nonce' );
	$plugin_dir = rtrim( plugin_dir_path(__FILE__), '/' );	
	require_once( $plugin_dir .  '/templatic_login.php' );	
	exit;
}
/* remove wp autoupdates */
add_action('admin_init','templatic_wpup_changes',20);

function templatic_wpup_changes(){
	 remove_action( 'after_plugin_row_Tevolution/templatic.php', 'wp_plugin_update_row' ,10, 2 );
}
?>