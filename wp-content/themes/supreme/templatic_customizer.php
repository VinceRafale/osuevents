<?php
/*
	@Theme Customizer settings for Wordpress customizer.
*/	
global $pagenow;
if(is_admin() && 'customize.php' == $pagenow){
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this section.' ) );
	}
}


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
		$wp_customize->add_setting('supreme_theme_settings[supreme_site_description]',array(
			'default' => '',
			'type' => 'option',
			'capabilities' => 'edit_theme_options',
			'sanitize_callback' => 	'templatic_customize_supreme_site_description',
			'sanitize_js_callback' => 	'templatic_customize_supreme_site_description',
			
			//'transport' => 'postMessage',
		));
		$wp_customize->add_setting('supreme_theme_settings[supreme_search_display_excerpt]',array(
			'default' => '',
			'type' => 'option',
			'capabilities' => 'edit_theme_options',
			'sanitize_callback' => 	'templatic_customize_supreme_search_display_excerpt',
			'sanitize_js_callback' => 	'templatic_customize_supreme_search_display_excerpt',
			//'transport' => 'postMessage',
		));
		$wp_customize->add_setting('supreme_theme_settings[supreme_archive_display_excerpt]',array(
			'default' => '',
			'type' => 'option',
			'capabilities' => 'edit_theme_options',
			'sanitize_callback' => 	'templatic_customize_supreme_archive_display_excerpt',
			'sanitize_js_callback' => 	'templatic_customize_supreme_archive_display_excerpt',
			//'transport' => 'postMessage',
		));
		$wp_customize->add_setting('supreme_theme_settings[supreme_author_bio_posts]',array(
			'default' => '',
			'type' => 'option',
			'capabilities' => 'edit_theme_options',
			'sanitize_callback' => 	'templatic_customize_supreme_author_bio_posts',
			'sanitize_js_callback' => 	'templatic_customize_supreme_author_bio_posts',
			//'transport' => 'postMessage',
		));
		$wp_customize->add_setting('supreme_theme_settings[supreme_author_bio_pages]',array(
			'default' => '',
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
			'label' => __(' Upload image for logo','templatic'),
			'section' => 'templatic_logo_settings',
			'settings' => 'supreme_theme_settings[supreme_logo_url]',
		)));
		$wp_customize->add_control( 'supreme_site_description', array(
			'label' => __('Hide Site Description','templatic'),
			'section' => 'title_tagline',
			'settings' => 'supreme_theme_settings[supreme_site_description]',
			'type' => 'checkbox',
		));
		$wp_customize->add_control( 'supreme_archive_display_excerpt', array(
			'label' => __('Display excerpts on archive pages','templatic'),
			'section' => 'templatic_theme_settings',
			'settings' => 'supreme_theme_settings[supreme_archive_display_excerpt]',
			'type' => 'checkbox',
		));
		$wp_customize->add_control( 'supreme_search_display_excerpt', array(
			'label' => __('Display excerpts on Search Result Pages','templatic'),
			'section' => 'templatic_theme_settings',
			'settings' => 'supreme_theme_settings[supreme_search_display_excerpt]',
			'type' => 'checkbox',
		));
		$wp_customize->add_control( 'supreme_author_bio_posts', array(
			'label' => __('Show author biography on posts','templatic'),
			'section' => 'templatic_theme_settings',
			'settings' => 'supreme_theme_settings[supreme_author_bio_posts]',
			'type' => 'checkbox',
		));
		$wp_customize->add_control( 'supreme_author_bio_pages', array(
			'label' => __('Show author biography on pages','templatic'),
			'section' => 'templatic_theme_settings',
			'settings' => 'supreme_theme_settings[supreme_author_bio_pages]',
			'type' => 'checkbox',
		));
		$wp_customize->add_control( 'supreme_global_layout', array(
			'label' => __('Global Layout','templatic'),
			'section' => 'templatic_theme_settings',
			'settings' => 'supreme_theme_settings[supreme_global_layout]',
			'type' => 'select',
			'choices' => array(
								'layout_default' => 'Default Layout',	
								'layout_1c' => 'One Column',	
								'layout_2c_l' => 'Two Columns, Left',	
								'layout_2c_r' => 'Two Columns, Right',	
							  ),
		));
		
		if($support_jigoshop){
			$wp_customize->add_control( 'supreme_jigoshop_layout', array(
				'label' => __('Jigoshop Layout','templatic'),
				'section' => 'templatic_theme_settings',
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
				'label' => __('WooCommerce Layout','templatic'),
				'section' => 'templatic_theme_settings',
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
	
	function templatic_customize_supreme_logo_url( $setting, $object ) {
		
		/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( "supreme_theme_settings[supreme_logo_url]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* Return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_supreme_logo_url", $setting, $object );
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