<?php
/*
	@Theme Customizer settings for Wordpress customizer.
*/	
global $pagenow;
if(is_admin() && 'customize.php' == $pagenow){
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this section.','supreme' ) );
	}
}

//error_reporting(E_ALL);
/*	Add Action for Customizer   START	*/
	add_action( 'customize_register',  'templatic_register_customizer_settings');
/*	Add Action for Customizer   END	*/

//echo "<pre>";print_r(get_option('supreme_theme_settings'));echo "</pre>";

/*	Function to create sections, settings, controls for wordpress customizer START.  */
$support_jigoshop = get_theme_support('supreme_jigoshop_layout');
$support_woocommerce = get_theme_support('supreme_woocommerce_layout');

function templatic_register_customizer_settings( $wp_customize ){
	
	/*	Add Section START */
		$wp_customize->add_section('templatic_theme_settings', array(
			'title' => 'Templatic Theme Settings',
			'priority'=>'35'
		));
		$wp_customize->add_section('templatic_excerpts_settings', array(
			'title' => 'Excerpts Settings',
			'priority'=>'36'
		));
		$wp_customize->add_section('templatic_layout_settings', array(
			'title' => 'Layouts',
			'priority'=>'37'
		));
		$wp_customize->add_section('templatic_captcha_settings', array(
			'title' => 'Captcha',
			'priority'=>'38'
		));
		$wp_customize->add_section('templatic_logo_settings', array(
			'title' => 'Site Logo',
			'priority'=>'5'
		));
		
	/*	Add Section END */
		
	/*	Add Settings START */

		$wp_customize->add_setting('supreme_theme_settings[supreme_logo_url]',array(
			'default' => '',
			'type' => 'option',
			'capabilities' => 'edit_theme_options',
			'sanitize_callback' => 	"templatic_customize_supreme_logo_url",
			'sanitize_js_callback' => 	"templatic_customize_supreme_logo_url",
			//'transport' => 'postMessage',
		));
		
		$wp_customize->add_setting('supreme_theme_settings[supreme_favicon_icon]',array(
			'default' => '',
			'type' => 'option',
			'capabilities' => 'edit_theme_options',
			'sanitize_callback' => 	"templatic_customize_supreme_favicon_icon",
			'sanitize_js_callback' => 	"templatic_customize_supreme_favicon_icon",
			//'transport' => 'postMessage',
		));
		
		$wp_customize->add_setting('supreme_theme_settings[supreme_site_description]',array(
			'default' => '',
			'type' => 'option',
			'capabilities' => 'edit_theme_options',
			'sanitize_callback' => 	'templatic_customize_supreme_site_description',
			'sanitize_js_callback' => 	'templatic_customize_supreme_site_description',
			
			//'transport' => 'postMessage',
		));
		
			
		$wp_customize->add_setting('hide_ajax_notification',array(
			'default' => '',
			'type' => 'option',
			'capabilities' => 'edit_theme_options',
			'sanitize_callback' => 	"templatic_customize_supreme_show_auto_install_message",
			'sanitize_js_callback' => 	"templatic_customize_supreme_show_auto_install_message",
			//'transport' => 'postMessage',
		));
		
		$wp_customize->add_setting('supreme_theme_settings[customcss]',array(
			'default' => '',
			'type' => 'option',
			'capabilities' => 'edit_theme_options',
			'sanitize_callback' => 	'templatic_customize_supreme_customcss',
			'sanitize_js_callback' => 	'templatic_customize_supreme_customcss',
			//'transport' => 'postMessage',
		));
		
		
		$wp_customize->add_setting('supreme_theme_settings[enable_comments]',array(
			'default' => 0,
			'type' => 'option',
			'capabilities' => 'edit_theme_options',
			'sanitize_callback' => 	'templatic_customize_supreme_enable_comments',
			'sanitize_js_callback' => 	'templatic_customize_supreme_enable_comments',
			//'transport' => 'postMessage',
		));
		
		
		$wp_customize->add_setting('supreme_theme_settings[supreme_search_display_excerpt]',array(
			'default' => 1,
			'type' => 'option',
			'capabilities' => 'edit_theme_options',
			'sanitize_callback' => 	'templatic_customize_supreme_search_display_excerpt',
			'sanitize_js_callback' => 	'templatic_customize_supreme_search_display_excerpt',
			//'transport' => 'postMessage',
		));
		
		
		$wp_customize->add_setting('supreme_theme_settings[supreme_archive_display_excerpt]',array(
			'default' => 1,
			'type' => 'option',
			'capabilities' => 'edit_theme_options',
			'sanitize_callback' => 	'templatic_customize_supreme_archive_display_excerpt',
			'sanitize_js_callback' => 	'templatic_customize_supreme_archive_display_excerpt',
			//'transport' => 'postMessage',
		));
		
		$wp_customize->add_setting('supreme_theme_settings[templatic_excerpt_length]',array(
			'default' => 20,
			'type' => 'option',
			'capabilities' => 'edit_theme_options',
			'sanitize_callback' => 	'templatic_customize_templatic_excerpt_length',
			'sanitize_js_callback' => 	'templatic_customize_templatic_excerpt_length',
			//'transport' => 'postMessage',
		));
		
		$wp_customize->add_setting('supreme_theme_settings[templatic_excerpt_link]',array(
			'default' => 'Read More',
			'type' => 'option',
			'capabilities' => 'edit_theme_options',
			'sanitize_callback' => 	'templatic_customize_templatic_excerpt_link',
			'sanitize_js_callback' => 	'templatic_customize_templatic_excerpt_link',
			//'transport' => 'postMessage',
		));
		
		
		$wp_customize->add_setting('supreme_theme_settings[supreme_author_bio_posts]',array(
			'default' => 0,
			'type' => 'option',
			'capabilities' => 'edit_theme_options',
			'sanitize_callback' => 	'templatic_customize_supreme_author_bio_posts',
			'sanitize_js_callback' => 	'templatic_customize_supreme_author_bio_posts',
			//'transport' => 'postMessage',
		));
		$wp_customize->add_setting('supreme_theme_settings[supreme_author_bio_pages]',array(
			'default' => 0,
			'type' => 'option',
			'capabilities' => 'edit_theme_options',
			'sanitize_callback' => 	'templatic_customize_supreme_author_bio_pages',
			'sanitize_js_callback' => 	'templatic_customize_supreme_author_bio_pages',
			//'transport' => 'postMessage',
		));
		
		$wp_customize->add_setting('supreme_theme_settings[supreme_global_layout]',array(
			'default' => '',
			'type' => 'option',
			'capabilities' => 'edit_theme_options',
			'sanitize_callback' => 	'templatic_customize_supreme_global_layout',
			'sanitize_js_callback' => 	'templatic_customize_supreme_global_layout',
			//'transport' => 'postMessage',
		));
		
		$wp_customize->add_setting('supreme_theme_settings[supreme_show_breadcrumb]',array(
				'default' => 1,
				'type' => 'option',
				'capabilities' => 'edit_theme_options',
				'sanitize_callback' => 	"templatic_customize_supreme_show_breadcrumb",
				'sanitize_js_callback' => 	"templatic_customize_supreme_show_breadcrumb"
				//'transport' => 'postMessage',
			));
			
		$wp_customize->add_setting('supreme_theme_settings[supreme_global_contactus_captcha]',array(
			'default' => 0,
			'type' => 'option',
			'capabilities' => 'edit_theme_options',
			'sanitize_callback' => 	'templatic_customize_supreme_global_contactus_captcha',
			'sanitize_js_callback' => 	'templatic_customize_supreme_global_contactus_captcha',
			//'transport' => 'postMessage',
		));
		
		if($support_jigoshop){
			$wp_customize->add_setting('supreme_theme_settings[supreme_jigoshop_layout]',array(
				'default' => '',
				'type' => 'option',
				'capabilities' => 'edit_theme_options',
				'sanitize_callback' => 	'templatic_customize_supreme_jigoshop_layout',
				'sanitize_js_callback' => 	'templatic_customize_supreme_jigoshop_layout',
				//'transport' => 'postMessage',
			));
		}
		
		if($support_woocommerce){
			$wp_customize->add_setting('supreme_theme_settings[supreme_woocommerce_layout]',array(
				'default' => '',
				'type' => 'option',
				'capabilities' => 'edit_theme_options',
				'sanitize_callback' => 	'templatic_customize_supreme_woocommerce_layout',
				'sanitize_js_callback' => 	'templatic_customize_supreme_woocommerce_layout',
				//'transport' => 'postMessage',
			));
		}
	
	/*	Add Settings END */
		
	/*	Add Control START */
		$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'supreme_theme_settings[supreme_logo_url]', array(
			'label' => __(' Upload image for logo','supreme'),
			'section' => 'templatic_logo_settings',
			'settings' => 'supreme_theme_settings[supreme_logo_url]',
		)));
		
		$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'supreme_theme_settings[supreme_favicon_icon]', array(
			'label' => __(' Upload favicon icon','supreme'),
			'section' => 'templatic_logo_settings',
			'settings' => 'supreme_theme_settings[supreme_favicon_icon]',
		)));
		
		$wp_customize->add_control( 'supreme_site_description', array(
			'label' => __('Hide Site Description','supreme'),
			'section' => 'title_tagline',
			'settings' => 'supreme_theme_settings[supreme_site_description]',
			'type' => 'checkbox',
		));
		
		$wp_customize->add_control('hide_ajax_notification', array(
			'label'   => __( 'Hide autoinstall', T_DOMAIN ),
			'section' => 'templatic_theme_settings',
			'settings'   => 'hide_ajax_notification',
			'type' => 'checkbox',
		) );
		
		
		$wp_customize->add_control( 'customcss', array(
			'label' => __('Use custom css','supreme'),
			'section' => 'templatic_theme_settings',
			'settings' => 'supreme_theme_settings[customcss]',
			'type' => 'checkbox',
		));
		
		$wp_customize->add_control( 'enable_comments', array(
			'label' => __('Enable comments on pages and posts','supreme'),
			'section' => 'templatic_theme_settings',
			'settings' => 'supreme_theme_settings[enable_comments]',
			'type' => 'checkbox',
		));
		
		
		
		
		
		$wp_customize->add_control( 'supreme_search_display_excerpt', array(
			'label' => __('Display excerpts on Search Result Pages','supreme'),
			'section' => 'templatic_excerpts_settings',
			'settings' => 'supreme_theme_settings[supreme_search_display_excerpt]',
			'type' => 'checkbox',
		));
		
		$wp_customize->add_control( 'supreme_archive_display_excerpt', array(
			'label' => __('Display excerpts on archive pages','supreme'),
			'section' => 'templatic_excerpts_settings',
			'settings' => 'supreme_theme_settings[supreme_archive_display_excerpt]',
			'type' => 'checkbox',
		));
		
		$wp_customize->add_control( 'templatic_excerpt_length', array(
			'label' => __('Excerpt length','supreme'),
			'section' => 'templatic_excerpts_settings',
			'settings' => 'supreme_theme_settings[templatic_excerpt_length]',
			'type' => 'text',
		));
		
		$wp_customize->add_control( 'templatic_excerpt_link', array(
			'label' => __('Text for Continue reading','supreme'),
			'section' => 'templatic_excerpts_settings',
			'settings' => 'supreme_theme_settings[templatic_excerpt_link]',
			'type' => 'text',
		));
		
		
		
		
		
		
		
		$wp_customize->add_control( 'supreme_author_bio_posts', array(
			'label' => __('Show author biography on posts','supreme'),
			'section' => 'templatic_theme_settings',
			'settings' => 'supreme_theme_settings[supreme_author_bio_posts]',
			'type' => 'checkbox',
		));
		$wp_customize->add_control( 'supreme_author_bio_pages', array(
			'label' => __('Show author biography on pages','supreme'),
			'section' => 'templatic_theme_settings',
			'settings' => 'supreme_theme_settings[supreme_author_bio_pages]',
			'type' => 'checkbox',
		));
		
		$wp_customize->add_control( 'supreme_show_breadcrumb', array(
				'label'   => __( 'Show Breadcrumb', 'supreme'),
				'section' => 'templatic_theme_settings',
				'settings'   => 'supreme_theme_settings[supreme_show_breadcrumb]',
				'type' => 'checkbox'
			) ) ;
		
		$wp_customize->add_control( 'supreme_global_layout', array(
			'label' => __('Global Layout','supreme'),
			'section' => 'templatic_layout_settings',
			'settings' => 'supreme_theme_settings[supreme_global_layout]',
			'type' => 'select',
			'choices' => array(
								'layout_default' => 'Default Layout',	
								'layout_1c' => 'One Column',	
								'layout_2c_l' => 'Two Columns, Left',	
								'layout_2c_r' => 'Two Columns, Right',	
							  ),
		));
		
		
		
		$wp_customize->add_control( 'supreme_global_contactus_captcha', array(
			'label' => __('Contact Us Captacha Setting','supreme'),
			'section' => 'templatic_captcha_settings',
			'settings' => 'supreme_theme_settings[supreme_global_contactus_captcha]',
			'type' => 'checkbox',
			'choices' => array(
								'WP-reCaptcha' => 'WP-reCaptcha',	
							  ),
		));
		
		if($support_jigoshop){
			$wp_customize->add_control( 'supreme_jigoshop_layout', array(
				'label' => __('Jigoshop Layout','supreme'),
				'section' => 'templatic_layout_settings',
				'settings' => 'supreme_theme_settings[supreme_jigoshop_layout]',
				'type' => 'select',
				'choices' => array(
									'layout_default' => 'Default Layout',	
									'layout_1c' => 'One Column',	
									'layout_2c_l' => 'Two Columns, Left',	
									'layout_2c_r' => 'Two Columns, Right',	
								  ),
			));
		}
		if($support_woocommerce){
			$wp_customize->add_control( 'supreme_woocommerce_layout', array(
				'label' => __('WooCommerce Layout','supreme'),
				'section' => 'templatic_layout_settings',
				'settings' => 'supreme_theme_settings[supreme_woocommerce_layout]',
				'type' => 'select',
				'choices' => array(
									'layout_default' => 'Default Layout',	
									'layout_1c' => 'One Column',	
									'layout_2c_l' => 'Two Columns, Left',	
									'layout_2c_r' => 'Two Columns, Right',	
								  ),
			));
		}
		
	/*	Add Control END */
	
}
/*	Function to create sections, settings, controls for wordpress customizer END.  */


/*  Handles changing settings for the live preview of the theme START.  */	
	
	function templatic_customize_templatic_excerpt_length( $setting, $object ) {
		
		/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( "templatic_excerpt_length" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* Return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_templatic_excerpt_length", $setting, $object );
	}
	function templatic_customize_templatic_excerpt_link( $setting, $object ) {
		
		/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( "templatic_excerpt_link" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* Return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_templatic_excerpt_link", $setting, $object );
	}
	
	function templatic_customize_supreme_customcss( $setting, $object ) {
		
		/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( "customcss" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* Return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_supreme_customcss", $setting, $object );
	}
	
	function templatic_customize_supreme_show_auto_install_message( $setting, $object ) {
		
		/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( "hide_ajax_notification" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* Return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_supreme_show_auto_install_message", $setting, $object );
	}
	
	function templatic_customize_supreme_enable_comments( $setting, $object ) {
		
		/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( "supreme_theme_settings[enable_comments]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* Return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_supreme_enable_comments", $setting, $object );
	}

	
	
	function templatic_customize_supreme_logo_url( $setting, $object ) {
		
		/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( "supreme_theme_settings[supreme_logo_url]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* Return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_supreme_logo_url", $setting, $object );
	}
	
	function templatic_customize_supreme_favicon_icon( $setting, $object ) {
		
		/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( "supreme_theme_settings[supreme_favicon_icon]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* Return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_supreme_favicon_icon", $setting, $object );
	}
	
	function templatic_customize_supreme_site_description( $setting, $object ) {
		
		/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( "supreme_theme_settings[supreme_site_description]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* Return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_supreme_site_description", $setting, $object );
	}

	function templatic_customize_supreme_archive_display_excerpt( $setting, $object ) {
		
		/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( "supreme_theme_settings[supreme_archive_display_excerpt]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* Return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_supreme_archive_display_excerpt", $setting, $object );
	}
	
	function templatic_customize_supreme_search_display_excerpt( $setting, $object ) {
		
		/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( "supreme_theme_settings[supreme_search_display_excerpt]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* Return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_supreme_search_display_excerpt", $setting, $object );
	}
	
	function templatic_customize_supreme_author_bio_posts( $setting, $object ) {
		
		/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( "supreme_theme_settings[supreme_author_bio_posts]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* Return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_supreme_author_bio_posts", $setting, $object );
	}
	
	function templatic_customize_supreme_author_bio_pages( $setting, $object ) {
		
		/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( "supreme_theme_settings[supreme_author_bio_pages]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* Return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_supreme_author_bio_pages", $setting, $object );
	}
	
	
	function templatic_customize_supreme_global_layout( $setting, $object ) {
		
		/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( "supreme_theme_settings[supreme_global_layout]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* Return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_supreme_global_layout", $setting, $object );
	}
	
	
	function templatic_customize_supreme_show_breadcrumb( $setting, $object ) {
		
		/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( "supreme_theme_settings[supreme_show_breadcrumb]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* Return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_supreme_show_breadcrumb", $setting, $object );
	}
	
	
	function templatic_customize_supreme_global_contactus_captcha( $setting, $object ) {
		
		/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( "supreme_theme_settings[supreme_global_contactus_captcha]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* Return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_supreme_global_contactus_captcha", $setting, $object );
	}
	
	if($support_jigoshop){
		function templatic_customize_supreme_jigoshop_layout( $setting, $object ) {
			
			/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
			if ( "supreme_theme_settings[supreme_jigoshop_layout]" == $object->id && !current_user_can( 'unfiltered_html' )  )
				$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
			/* Return the sanitized setting and apply filters. */
			return apply_filters( "templatic_customize_supreme_jigoshop_layout", $setting, $object );
		}
	}
	if($support_woocommerce){
		function templatic_customize_supreme_woocommerce_layout( $setting, $object ) {
			
			/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
			if ( "supreme_theme_settings[supreme_woocommerce_layout]" == $object->id && !current_user_can( 'unfiltered_html' )  )
				$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
			/* Return the sanitized setting and apply filters. */
			return apply_filters( "templatic_customize_supreme_woocommerce_layout", $setting, $object );
		}
	}
	
	
/*  Handles changing settings for the live preview of the theme END.  */	
?>