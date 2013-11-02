<?php
/*
WPUpdates Theme Updater Class
http://wp-updates.com
v1.1

Example Usage:
require_once('wp-updates-theme.php');
new WPUpdatesThemeUpdater( 'http://wp-updates.com/api/1/theme', 1, basename(get_template_directory()) );
*/
/*
 * Function Name: supreme_update_theme
 * Return: update supreme version after templatic member login
 */
function supreme_update_theme()
{
	check_ajax_referer( 'supreme', '_ajax_nonce' );
	$theme_dir = rtrim(  get_template_directory(), '/' );	
	require_once( get_template_directory() .  '/templatic_login.php' );	
	exit;
}


if( !class_exists('WPUpdatesThemeUpdater') ) {
    class WPUpdatesThemeUpdater {
    
        var $api_url;
    	var $theme_id;
    	var $theme_slug;
		function supreme_clear_update_transient() {

			delete_transient( 'supreme-update' );
		}
        function __construct( $api_url, $theme_id, $theme_slug ) {
    		$this->api_url = $api_url;
    		$this->theme_id = $theme_id;
    		$this->theme_slug = $theme_slug;
    
    		add_filter( 'pre_set_site_transient_update_themes', array(&$this, 'check_for_update') );
    		add_filter( 'after_theme_row_supreme', array(&$this, 'supreme_theme_row') );
    		add_action('wp_ajax_supreme','supreme_update_theme');
    		// This is for testing only!
    		//set_site_transient('update_themes', null);
			if(!strstr($_SERVER['REQUEST_URI'],'plugin-install.php') && !strstr($_SERVER['REQUEST_URI'],'update.php'))
			{
		
			add_action( 'load-update-core.php', array(&$this,'supreme_clear_update_transient') );
			add_action( 'load-themes.php', array(&$this, 'supreme_clear_update_transient') );
			if(!strstr($_SERVER['REQUEST_URI'],'/network/')){
			add_action( 'admin_notices', array(&$this, 'supreme_update_nag') );
			}
			delete_transient( 'supreme-update' );
			add_filter( 'pre_site_transient_update_themes', create_function( '$a', "return null;" ) );
			}
    	}
    	
		function supreme_update_nag($transient) {
			$request_args = array(
    		    'id' => $this->theme_id,
    		    'slug' => $this->theme_slug,
    			'version' => @$transient->checked[$this->theme_slug]
    		); 
	
    		$request_string = $this->prepare_request( 'theme_update', $request_args );
			echo'';
    		$raw_response = wp_remote_post( $this->api_url, $request_string );
        	
        	$response = null;
    		if( !is_wp_error($raw_response) && ($raw_response['response']['code'] == 200) )
    			$response = unserialize($raw_response['body']);
				

			if(file_exists(get_template_directory().'/style.css')){
				$theme_data = get_theme_data(get_template_directory().'/style.css');
			}else{
				$theme_data = array('Version'=> $response['new_version']);
			}

			$supreme_version = $theme_data['Version'];
			$remote_version = $response['new_version'];

			if (version_compare($supreme_version, $remote_version, '<') && $supreme_version!='')
			{	
				echo '<div id="update-nag">';
				 $new_version = version_compare($supreme_version, $remote_version, '<') ? __('There is a new version of Supreme available.', 'supreme') .' <a class="thickbox" title="Supreme Forms" href="plugin-install.php?tab=plugin-information&plugin=templatic&TB_iframe=true&width=640&height=808">'. sprintf(__('View version %s Details', 'supreme'), $remote_version) . '</a>. ' : '';
		  
					$ajax_url = esc_url( add_query_arg( array( 'slug' => 'supreme', 'action' => 'supreme' , '_ajax_nonce' => wp_create_nonce( 'supreme' ), 'TB_iframe' => true ,'width'=>500,'height'=>400), admin_url( 'admin-ajax.php' ) ) );
					$file= get_template_directory().'/style.css';
					$download= wp_nonce_url( self_admin_url('update.php?action=upgrade-theme&theme=').$file, 'upgrade-theme_' . $file);
					echo '</tr><tr class="plugin-update-tr"><td colspan="3" class="plugin-update"><div class="update-message">' . $new_version . sprintf(__( 'or <a href="%s" class="thickbox" title="Supreme Update">update now</a>.', 'supreme'),$ajax_url) .'</div></td>';
				echo '</div>';
			}

		}

	
    	function check_for_update( $transient ) {
        	if (empty($transient->checked)) return $transient;
        			print_r($request_args);
        	$request_args = array(
    		    'id' => $this->theme_id,
    		    'slug' => $this->theme_slug,
    			'version' => $transient->checked[$this->theme_slug]
    		);
    		$request_string = $this->prepare_request( 'theme_update', $request_args );
    		$raw_response = wp_remote_post( $this->api_url, $request_string );
        	
        	$response = null;
    		if( !is_wp_error($raw_response) && ($raw_response['response']['code'] == 200) )
    			$response = unserialize($raw_response['body']);
    		
    		if( !empty($response) ) // Feed the update data into WP updater
    			$transient->response[$this->theme_slug] = $response;
        	
        	return $transient;
        }
        
		/*
		 * add action for set the auto update for tevolution plugin
		 * Functio Name: tevolution_plugin_row
		 * Return : Display the plugin new version update message
		 */
		function supreme_theme_row()
		{
			$request_args = array(
    		    'id' => $this->theme_id,
    		    'slug' => $this->theme_slug,
    			'version' => $transient->checked[$this->theme_slug]
    		);
    		$request_string = $this->prepare_request( 'theme_update', $request_args );
    		$raw_response = wp_remote_post( $this->api_url, $request_string );
        	
        	$response = null;
    		if( !is_wp_error($raw_response) && ($raw_response['response']['code'] == 200) )
    			$response = unserialize($raw_response['body']);
			
    		$theme_data = get_theme_data(get_template_directory().'/style.css');

			$supreme_version = $theme_data['Version'];
			$remote_version = $response['new_version'];
			if (version_compare($supreme_version, $remote_version, '<') && $supreme_version!='')
			{	
             $new_version = version_compare($supreme_version, $remote_version, '<') ? __('There is a new version of Supreme available.', DOMAIN) .' <a class="thickbox" title="Supreme Forms" href="plugin-install.php?tab=plugin-information&plugin=templatic&TB_iframe=true&width=640&height=808">'. sprintf(__('View version %s Details', DOMAIN), $remote_version) . '</a>. ' : '';
		  
			$ajax_url = esc_url( add_query_arg( array( 'slug' => 'supreme', 'action' => 'supreme' , '_ajax_nonce' => wp_create_nonce( 'supreme' ), 'TB_iframe' => true ,'width'=>500,'height'=>400), admin_url( 'admin-ajax.php' ) ) );
			$file= get_template_directory().'/style.css';
			$download= wp_nonce_url( self_admin_url('update.php?action=upgrade-theme&theme=').$file, 'upgrade-theme_' . $file);
			echo '</tr><tr class="plugin-update-tr"><td colspan="3" class="plugin-update"><div class="update-message">' . $new_version . __( 'or <a href="'.$ajax_url.'" class="thickbox" title="Supreme Update">update now</a>.', DOMAIN) .'</div></td>';
	
			}
		}

        function prepare_request( $action, $args ) {
    		global $wp_version;
    		
    		return array(
    			'body' => array(
    				'action' => $action, 
    				'request' => serialize($args),
    				'api-key' => md5(home_url())
    			),
    			'user-agent' => 'WordPress/'. $wp_version .'; '. home_url()
    		);	
    	}

    }
}

?>