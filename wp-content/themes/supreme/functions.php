<?php
/**
 * The functions file is used to initialize everything in the theme.  It controls how the theme is loaded and 
 * sets up the supported features, default actions, and default filters.  If making customizations, users 
 * should create a child theme and make changes to its functions.php file (not this one).  Friends don't let 
 * friends modify parent theme files. ;)
 *
 * Child themes should do their setup on the 'after_setup_theme' hook with a priority of 11 if they want to
 * override parent theme features.  Use a priority of 9 if wanting to run before the parent theme.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write 
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package supreme
 * @subpackage Functions
 * @version 0.1.6.6
 * @author Tung Do <ttsondo@devpress.com>
 * @copyright Copyright (c) 2012, Tung Do
 * @link http://devpress.com/themes/supreme
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
/* Load the Hybrid Core framework. */
require_once( trailingslashit ( get_template_directory() ) . 'library/hybrid.php' );
$theme = new Hybrid(); // Part of the framework.
/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'supreme_theme_setup' );
$currrent_theme_name = wp_get_theme();
$templatic_woocommerce_themes = get_option('templatic_woocommerce_themes');
update_option('templatic_woocommerce_themes',$templatic_woocommerce_themes.",".$currrent_theme_name);

require_once('wp-updates-theme.php');
new WPUpdatesThemeUpdater( 'http://wp-updates.com/api/1/theme', 135, basename(get_template_directory()) );
/**
 * Theme setup function.  This function adds support for theme features and defines the default theme
 * actions and filters.
 *
 * @since 0.1.0
 */
 //error_reporting(E_ALL);
function supreme_theme_setup() {
	/* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix(); // Part of the framework, cannot be changed or prefixed.
	
	if(file_exists(get_template_directory().'/templatic_customizer.php')){
		require_once((get_template_directory().'/templatic_customizer.php'));
	}
	if(file_exists(get_template_directory().'/functions/templatic_news.php')){
		require_once(get_template_directory().'/functions/templatic_news.php');
	}
	
	add_action('init','attach_mega_menu_js');
	/* Add theme settings */
	if ( is_admin() )
	    require_once( trailingslashit ( get_template_directory() ) . 'functions/admin.php' );
	
	/* Register support for all post formats. */
	add_theme_support( 'post-formats', array(
		'aside',
		'audio',
		'chat',
		'gallery',
		'image',
		'link',
		'quote',
		'status',
		'video'
		) );

	/* Add framework menus. */
	add_theme_support( 'hybrid-core-menus', array( // Add core menus.
		'primary',
		'secondary',
		'subsidiary'
		) );

	/* Register aditional menus */
	add_action( 'init', 'supreme_register_menus' );
	
	if(!strstr($_SERVER['REQUEST_URI'],'/wp-admin/') && isset($_REQUEST['adv_search']) && $_REQUEST['adv_search'] == 1){
		add_action('posts_where','templatic_searching_filter_where');
	}
	/* Add framework sidebars */
	add_theme_support( 'hybrid-core-sidebars', array( // Add sidebars or widget areas.
		'primary',
		'secondary',
		'subsidiary',
		'header',
		'before-content',
		'after-content',
		'after-singular'
		) );

	/* Register additional widget areas. */
	add_action( 'widgets_init', 'supreme_register_sidebars', 11 ); // Number 11 indicates custom sidebars should be registered after Hybrid Core Sidebars

	add_theme_support( 'hybrid-core-widgets' );
	add_theme_support( 'hybrid-core-shortcodes' );
	//add_theme_support( 'hybrid-core-drop-downs' ); // Works with registered menus above.
	add_theme_support( 'hybrid-core-template-hierarchy' ); // This is important. Do not remove. */
	
	/* Add aditional layouts */
	add_filter( 'theme_layouts_strings', 'supreme_theme_layouts' );

	/* Add theme support for framework layout extension. */
	add_theme_support( 'theme-layouts', array( // Add theme layout options.
		'1c',
		'2c-l',
		'2c-r',
		'3c-c',
		'3c-l',
		'3c-r',
		'hl-1c',
		'hl-2c-l',
		'hl-2c-r',
		'hr-1c',
		'hr-2c-l',
		'hr-2c-r'
		) );

	/* Add theme support for other framework extensions */
	add_theme_support( 'post-stylesheets' );
	add_theme_support( 'loop-pagination' );
	add_theme_support( 'get-the-image' );
	add_theme_support( 'breadcrumb-trail' );
	add_theme_support( 'cleaner-gallery' );
	add_theme_support( 'hybrid-core-theme-settings', array( 'footer' ) );

	/* Add theme support for WordPress features. */
	add_theme_support( 'automatic-feed-links' );
	
	/* Load resources into the theme. */
	add_action( 'wp_enqueue_scripts', 'supreme_resources' );
	
	/* Add theme support for WordPress background feature */
	//add_custom_background( 'supreme_custom_background_callback' );

	add_theme_support( 'custom-background', array (
		'default-color' => '',
		'default-image' => '',
		'wp-head-callback' => 'supreme_custom_background_callback',
		'admin-head-callback' => '',
		'admin-preview-callback' => ''
	));
	
	/* Modify excerpt more */
	add_filter('excerpt_more', 'supreme_new_excerpt_more');
	
	/* Register new image sizes. */
	add_action( 'init', 'supreme_register_image_sizes' );
	
	/* Wraps <blockquote> around quote posts. */
	add_filter( 'the_content', 'supreme_quote_post_content' );

	/* Set content width. */
	hybrid_set_content_width( 600 );
	
	/* Embed width/height defaults. */
	add_filter( 'embed_defaults', 'supreme_embed_defaults' ); // Set default widths to use when inserting media files
	
	/* Assign specific layouts to pages based on set conditions and disable certain sidebars based on layout choices. */
	add_action( 'template_redirect', 'supreme_layouts' );
	add_filter( 'sidebars_widgets', 'supreme_disable_sidebars' );
	
	/* bbPress Functions */
	if ( function_exists( 'is_bbpress' ) ) {
		add_action( 'wp_head', 'supreme_bbpress_scripts' );
		add_filter( 'bbp_show_lead_topic', '__return_true' );
		add_filter( 'wp_enqueue_scripts', 'supreme_localize_topic_script' );
		add_action( 'wp_ajax_dim-favorite', 'supreme_dim_favorite' );
		add_action( 'wp_ajax_dim-subscription', 'supreme_dim_subscription' );
	}
	
	/* Plugin Layouts */
	if ( function_exists ( 'bp_loaded' ) ) {
		add_filter( 'get_theme_layout', 'supreme_plugin_layouts' );
	}
	
	/* Jigoshop Functions. */
	if ( function_exists( 'is_jigoshop' ) ) {
		remove_action( 'jigoshop_before_main_content', 'jigoshop_output_content_wrapper', 10);
		remove_action( 'jigoshop_after_main_content', 'jigoshop_output_content_wrapper_end', 10);
		remove_action( 'jigoshop_before_main_content', 'jigoshop_breadcrumb', 20, 0);
	}
	
	/* WooCommerce Functions. */
	if ( function_exists( 'is_woocommerce' ) ) {
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
	}
}

function attach_mega_menu_js(){
		  add_action('wp_footer', 'load_megamenu');				
	}
	function load_megamenu(){
			wp_enqueue_script('jquerymegamenu', get_template_directory_uri()."/js/jquery.megamenu.1.2.js");
			wp_enqueue_script('jquerymegamenuhoverint', get_template_directory_uri()."/js/jquery.hoverIntent.minified.js");
		}
/**
 * Loads the theme scripts.
 *
 * @since 0.1
 */
function supreme_resources() {
	
	wp_enqueue_script ( 'supreme-scripts', trailingslashit ( get_template_directory_uri() ) . 'js/supreme.js', array( 'jquery' ), '20120606', true );?>
	<script type="text/javascript" language="javascript">var rootfolderpath = '<?php echo trailingslashit ( get_template_directory_uri() ) ;?>images/';</script>
	<?php
	wp_enqueue_script( 'dhtmlgoodies_calendar', get_template_directory_uri().'/js/dhtmlgoodies_calendar.js');
	wp_enqueue_style( 'dhtmlgoodies_calendar_css', get_template_directory_uri().'/css/dhtmlgoodies_calendar.css');	
	
	/* for bbPress. */
	
	if ( function_exists ( 'is_bbpress' ) ) {
	
		if ( function_exists( 'bbp_is_topic' ) ) {
			if ( bbp_is_topic() )
				wp_enqueue_script( 'supreme-bbpress-topic', trailingslashit( get_template_directory_uri() ) . 'js/bbpress-topic.js', array( 'wp-lists' ), false, true );
		}
				
		if( function_exists( 'bbp_is_single_user_edit' ) ) {
			if ( bbp_is_single_user_edit() )
				wp_enqueue_script( 'user-profile' );
		}
	
	}
	
	/* for BuddyPress */
	
	if ( function_exists ( 'bp_is_active' ) ) {

		wp_dequeue_style( 'bp' );

		/* Load BuddyPress-specific styles. */
		wp_enqueue_style ( 'supreme-buddypress', trailingslashit ( get_template_directory_uri() ) . 'css/buddypress.css', false, '20120608', 'all' );
	
	}
	
	/* for Jigoshop */
	
	if( function_exists( 'is_jigoshop') ) {
		wp_dequeue_style( 'jigoshop_frontend_styles' );
		wp_enqueue_style ( 'supreme-jigoshop', trailingslashit ( THEME_URI ) . 'css/jigoshop.css', false, '20120310', 'all' );
	}
	
	/* for Gravity Forms */
	
	if( class_exists( 'RGForms' ) && class_exists( 'RGFormsModel' )) {

		wp_enqueue_style( 'supreme-gravity-forms', trailingslashit (get_template_directory_uri() ) . 'css/gravity-forms.css', false, '20120312', 'all' );

	}
	
	/* for WooCommerce */
	
	if( function_exists( 'is_woocommerce') ) {
		wp_dequeue_style( 'woocommerce_frontend_styles' );
		wp_dequeue_style( 'woocommerce_chosen_styles' );
		wp_enqueue_style ( 'supreme-woocommerce', trailingslashit ( THEME_URI ) . 'css/woocommerce.css', false, '20120702', 'all' );
	}

}

/**
 * This is a fix for when a user sets a custom background color with no custom background image.  What 
 * happens is the theme's background image hides the user-selected background color.  If a user selects a 
 * background image, we'll just use the WordPress custom background callback.
 * 
 * Thanks to Justin Tadlock for the code.
 *
 * @since 0.1
 * @link http://core.trac.wordpress.org/ticket/16919
 */
function supreme_custom_background_callback() {

	/* Get the background image. */
	$image = get_background_image();

	/* If there's an image, just call the normal WordPress callback. We won't do anything here. */
	if ( !empty( $image ) ) {
		_custom_background_cb();
		return;
	}

	/* Get the background color. */
	$color = get_background_color();

	/* If no background color, return. */
	if ( empty( $color ) )
		return;

	/* Use 'background' instead of 'background-color'. */
	$style = "background: #{$color};";

?>
<style type="text/css">body.custom-background { <?php echo trim( $style ); ?> }</style>
<?php

}

/**
 * Filters the excerpt more.
 *
 * @since 0.1
 */

function supreme_new_excerpt_more( $more ) {
	return '&#133;';
}

/**
 * Wraps the output of posts with the 'quote' post format with a <blockquote> element if the post content 
 * doesn't already have this element within it.
 *
 * @since 0.1
 * @access private
 * @param string $content The content of the post.
 * @return string $content
 */
function supreme_quote_post_content( $content ) {

	if ( has_post_format( 'quote' ) ) {
		preg_match( '/<blockquote.*?>/', $content, $matches );

		if ( empty( $matches ) )
			$content = "<blockquote>{$content}</blockquote>";
	}

	return $content;
}

/**
 * Registers additional image size 'supreme-thumbnail'.
 *
 * @since 0.1
 */
function supreme_register_image_sizes() {
	add_image_size( 'supreme-thumbnail', 280, 280, true );
}

/**
 * Overwrites the default widths for embeds.  This is especially useful for making sure videos properly
 * expand the full width on video pages.  This function overwrites what the $content_width variable handles
 * with context-based widths.
 *
 * @since 0.1
 */
function supreme_embed_defaults( $args ) {

	$args['width'] = 600;

	if ( current_theme_supports( 'theme-layouts' ) ) {

		$layout = theme_layouts_get_layout();

		if ( 'layout-3c-l' == $layout || 'layout-3c-r' == $layout || 'layout-3c-c' == $layout || 'layout-hl-2c-l' == $layout || 'layout-hl-2c-r' == $layout || 'layout-hr-2c-l' == $layout || 'layout-hr-2c-r' == $layout )
		
			$args['width'] = 280;
			
		elseif ( 'layout-1c' == $layout )
		
			$args['width'] = 920;

	}

	return $args;
}

/**
 * Conditional logic deciding the layout of certain pages.
 *
 * @since 0.1
 */
function supreme_layouts() {

	if ( current_theme_supports( 'theme-layouts' ) ) {

		$global_layout = hybrid_get_setting( 'supreme_global_layout' );
		$bbpress_layout = hybrid_get_setting( 'supreme_bbpress_layout' );
		$jigoshop_layout = hybrid_get_setting( 'supreme_jigoshop_layout' );
		$woocommerce_layout = hybrid_get_setting( 'supreme_woocommerce_layout' );
		$layout = theme_layouts_get_layout();

		if ( !is_singular() && $global_layout !== 'layout_default' && function_exists( "supreme_{$global_layout}" ) ) {
			add_filter( 'get_theme_layout', 'supreme_' . $global_layout );
		} // end global layout control
		
		if ( is_singular() && $layout == 'layout-default' && $global_layout !== 'layout_default' && function_exists( "supreme_{$global_layout}" ) ) {
			add_filter( 'get_theme_layout', 'supreme_' . $global_layout );
		} // end singular layout control relative to global layout control
		
		if ( function_exists ( 'bbp_loaded' ) ) {
			if ( is_bbpress() && !is_singular() && $bbpress_layout !== 'layout_default' && function_exists( "supreme_{$bbpress_layout}" ) ) {
				add_filter( 'get_theme_layout', 'supreme_' . $bbpress_layout );
			}
			elseif ( is_bbpress() && is_singular() && $layout == 'layout-default' && $bbpress_layout !== 'layout_default' && function_exists( "supreme_{$bbpress_layout}" ) ) {
				add_filter( 'get_theme_layout', 'supreme_' . $bbpress_layout );
			}
		} // end bbpress layout control
		
		if ( function_exists ( 'is_jigoshop' ) ) {
			if ( is_jigoshop() && !is_singular() && $jigoshop_layout !== 'layout_default' && function_exists( "supreme_{$jigoshop_layout}" ) ) {
				add_filter( 'get_theme_layout', 'supreme_' . $jigoshop_layout );
			}
			elseif ( is_jigoshop() && is_singular() && $layout == 'layout-default' && $jigoshop_layout !== 'layout_default' && function_exists( "supreme_{$jigoshop_layout}" ) ) {
				add_filter( 'get_theme_layout', 'supreme_' . $jigoshop_layout );
			}
		} // end jigoshop layout control
		
		if ( function_exists ( 'is_woocommerce' ) ) {
			if ( is_woocommerce() && !is_singular() && $woocommerce_layout !== 'layout_default' && function_exists( "supreme_{$woocommerce_layout}" ) ) {
				add_filter( 'get_theme_layout', 'supreme_' . $woocommerce_layout );
			}
			elseif ( is_woocommerce() && is_singular() && $layout == 'layout-default' && $woocommerce_layout !== 'layout_default' && function_exists( "supreme_{$woocommerce_layout}" ) ) {
				add_filter( 'get_theme_layout', 'supreme_' . $woocommerce_layout );
			}
		} // end woocommerce layout control

	}
	
}

/**
 * Filters 'get_theme_layout' to set layouts for specific installed plugin pages.
 *
 * @since 0.1
 */

function supreme_plugin_layouts( $layout ) {

	if ( current_theme_supports( 'theme-layouts' ) ) {
	
		$global_layout = hybrid_get_setting( 'supreme_global_layout' );
		$buddypress_layout = hybrid_get_setting( 'supreme_buddypress_layout' );

		if ( function_exists( 'bp_loaded' ) && !bp_is_blog_page() && $layout == 'layout-default' ) {
		
			if ( $buddypress_layout !== 'layout_default' ) {
			
				if ( $buddypress_layout == 'layout_1c' )
					$layout = 'layout-1c';
				elseif ( $buddypress_layout == 'layout_2c_l' )
					$layout = 'layout-2c-l';
				elseif ( $buddypress_layout == 'layout_2c_r' )
					$layout = 'layout-2c-r';
				elseif ( $buddypress_layout == 'layout_3c_c' )
					$layout = 'layout-3c-c';
				elseif ( $buddypress_layout == 'layout_3c_l' )
					$layout = 'layout-3c-l';
				elseif ( $buddypress_layout == 'layout_3c_r' )
					$layout = 'layout-3c-r';
				elseif ( $buddypress_layout == 'layout_hl_1c' )
					$layout = 'layout-hl-1c';
				elseif ( $buddypress_layout == 'layout_hl_2c_l' )
					$layout = 'layout-hl-2c-l';
				elseif ( $buddypress_layout == 'layout_hl_2c_r' )
					$layout = 'layout-hl-2c-r';
				elseif ( $buddypress_layout == 'layout_hr_1c' )
					$layout = 'layout-hr-1c';
				elseif ( $buddypress_layout == 'layout_hr_2c_l' )
					$layout = 'layout-hr-2c-l';
				elseif ( $buddypress_layout == 'layout_hr_2c_r' )
					$layout = 'layout-hr-2c-r';
				
			} elseif ( $buddypress_layout == 'layout_default' ) {
			
				if ( $global_layout == 'layout_1c' )
					$layout = 'layout-1c';
				elseif ( $global_layout == 'layout_2c_l' )
					$layout = 'layout-2c-l';
				elseif ( $global_layout == 'layout_2c_r' )
					$layout = 'layout-2c-r';
				elseif ( $global_layout == 'layout_3c_c' )
					$layout = 'layout-3c-c';
				elseif ( $global_layout == 'layout_3c_l' )
					$layout = 'layout-3c-l';
				elseif ( $global_layout == 'layout_3c_r' )
					$layout = 'layout-3c-r';
				elseif ( $global_layout == 'layout_hl_1c' )
					$layout = 'layout-hl-1c';
				elseif ( $global_layout == 'layout_hl_2c_l' )
					$layout = 'layout-hl-2c-l';
				elseif ( $global_layout == 'layout_hl_2c_r' )
					$layout = 'layout-hl-2c-r';
				elseif ( $global_layout == 'layout_hr_1c' )
					$layout = 'layout-hr-1c';
				elseif ( $global_layout == 'layout_hr_2c_l' )
					$layout = 'layout-hr-2c-l';
				elseif ( $global_layout == 'layout_hr_2c_r' )
					$layout = 'layout-hr-2c-r';
			
			}

		}
		
	}
	
	return $layout;

}

/**
 * Filters 'theme_layouts_strings'.
 *
 * @since 0.1.6
 */
function supreme_theme_layouts( $strings ) {

	/* Set up the layout strings. */
	$strings = array(
		'default' => __( 'Default', 'theme-layouts' ),
		'1c' => __( 'One Column', 'theme-layouts' ),
		'2c-l' => __( 'Two Columns, Left', 'theme-layouts' ),
		'2c-r' => __( 'Two Columns, Right', 'theme-layouts' ),
		'3c-c' => __( 'Three Columns, Center', 'theme-layouts' ),
		'3c-l' => __( 'Three Columns, Left', 'theme-layouts' ),
		'3c-r' => __( 'Three Columns, Right', 'theme-layouts' ),
		'hl-1c' => __( 'Header Left One Column', 'theme-layouts' ),
		'hl-2c-l' => __( 'Header Left Two Columns, Left', 'theme-layouts' ),
		'hl-2c-r' => __( 'Header Left Two Columns, Right', 'theme-layouts' ),
		'hr-1c' => __( 'Header Right One Column', 'theme-layouts' ),
		'hr-2c-l' => __( 'Header Right Two Columns, Left', 'theme-layouts' ),
		'hr-2c-r' => __( 'Header Right Two Columns, Right', 'theme-layouts' )
	);

	return $strings;
}

/**
 * Filters 'get_theme_layout'.
 *
 * @since 0.1
 */
function supreme_layout_default( $layout ) {return 'layout-default';}
function supreme_layout_1c( $layout ) {return 'layout-1c';}
function supreme_layout_2c_l( $layout ) {return 'layout-2c-l';}
function supreme_layout_2c_r( $layout ) {return 'layout-2c-r';}
function supreme_layout_3c_c( $layout ) {return 'layout-3c-c';}
function supreme_layout_3c_l( $layout ) {return 'layout-3c-l';}
function supreme_layout_3c_r( $layout ) {return 'layout-3c-r';}
function supreme_layout_hl_1c( $layout ) {return 'layout-hl-1c';}
function supreme_layout_hl_2c_l( $layout ) {return 'layout-hl-2c-l';}
function supreme_layout_hl_2c_r( $layout ) {return 'layout-hl-2c-r';}
function supreme_layout_hr_1c( $layout ) {return 'layout-hr-1c';}
function supreme_layout_hr_2c_l( $layout ) {return 'layout-hr-2c-l';}
function supreme_layout_hr_2c_r( $layout ) {return 'layout-hr-2c-r';}

/**
 * Disables sidebars based on layout choices.
 *
 * @since 0.1
 */
function supreme_disable_sidebars( $sidebars_widgets ) {
	global $wp_query;

	if ( current_theme_supports( 'theme-layouts' ) && !is_admin() ) {

		if ( 'layout-1c' == theme_layouts_get_layout() ) {
			$sidebars_widgets['primary'] = false;
			$sidebars_widgets['secondary'] = false;
			
		}
		elseif ( 'layout-hl-1c' == theme_layouts_get_layout() || 'layout-hr-1c' == theme_layouts_get_layout() ) {
			$sidebars_widgets['primary'] = false;
			$sidebars_widgets['secondary'] = false;
			$sidebars_widgets['after-header'] = false;
			$sidebars_widgets['after-header-2c'] = false;
			$sidebars_widgets['after-header-3c'] = false;
			$sidebars_widgets['after-header-4c'] = false;
			$sidebars_widgets['after-header-5c'] = false;
		}
		elseif ( 'layout-hl-2c-l' == theme_layouts_get_layout() || 'layout-hl-2c-r' == theme_layouts_get_layout() || 'layout-hr-2c-l' == theme_layouts_get_layout() || 'layout-hr-2c-r' == theme_layouts_get_layout() ) {
			$sidebars_widgets['after-header'] = false;
			$sidebars_widgets['after-header-2c'] = false;
			$sidebars_widgets['after-header-3c'] = false;
			$sidebars_widgets['after-header-4c'] = false;
			$sidebars_widgets['after-header-5c'] = false;
		}
		
	}

	return $sidebars_widgets;
}

/**
 * Put some scripts in the header, like AJAX url for wp-lists
 *
 * @since bbPress (r2652)
 *
 * @uses bbp_is_topic() To check if it's the topic page
 * @uses admin_url() To get the admin url
 * @uses bbp_is_user_profile_edit() To check if it's the profile edit page
 */
function supreme_bbpress_scripts () {

	if ( bbp_is_topic() ) : ?>

	<script type='text/javascript'>
		/* <![CDATA[ */
		var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
		/* ]]> */
	</script>

	<?php elseif ( bbp_is_single_user_edit() ) : ?>

	<script type="text/javascript" charset="utf-8">
		if ( window.location.hash == '#password' ) {
			document.getElementById('pass1').focus();
		}
	</script>

	<?php endif;

}

/**
* Load localizations for topic script
*
* These localizations require information that may not be loaded even by init.
*
* @since bbPress (r2652)
*
* @uses bbp_is_single_topic() To check if it's the topic page
* @uses is_user_logged_in() To check if user is logged in
* @uses bbp_get_current_user_id() To get the current user id
* @uses bbp_get_topic_id() To get the topic id
* @uses bbp_get_favorites_permalink() To get the favorites permalink
* @uses bbp_is_user_favorite() To check if the topic is in user's favorites
* @uses bbp_is_subscriptions_active() To check if the subscriptions are active
* @uses bbp_is_user_subscribed() To check if the user is subscribed to topic
* @uses bbp_get_topic_permalink() To get the topic permalink
* @uses wp_localize_script() To localize the script
*/
function supreme_localize_topic_script() {

	// Bail if not viewing a single topic
	if ( !bbp_is_single_topic() )
		return;

	$user_id = bbp_get_current_user_id();

	$localizations = array(
		'currentUserId' => $user_id,
		'topicId'       => bbp_get_topic_id(),
	);

	// Favorites
	if ( bbp_is_favorites_active() ) {
		$localizations['favoritesActive'] = 1;
		$localizations['favoritesLink']   = bbp_get_favorites_permalink( $user_id );
		$localizations['isFav']           = (int) bbp_is_user_favorite( $user_id );
		$localizations['favLinkYes']      = __( 'favorites',                                         'bbpress' );
		$localizations['favLinkNo']       = __( '?',                                                 'bbpress' );
		$localizations['favYes']          = __( 'This topic is one of your %favLinkYes% [%favDel%]', 'bbpress' );
		$localizations['favNo']           = __( '%favAdd% (%favLinkNo%)',                            'bbpress' );
		$localizations['favDel']          = __( '&times;',                                           'bbpress' );
		$localizations['favAdd']          = __( 'Add this topic to your favorites',                  'bbpress' );
	} else {
		$localizations['favoritesActive'] = 0;
	}

	// Subscriptions
	if ( bbp_is_subscriptions_active() ) {
		$localizations['subsActive']   = 1;
		$localizations['isSubscribed'] = (int) bbp_is_user_subscribed( $user_id );
		$localizations['subsSub']      = __( 'Subscribe',   'bbpress' );
		$localizations['subsUns']      = __( 'Unsubscribe', 'bbpress' );
		$localizations['subsLink']     = bbp_get_topic_permalink();
	} else {
		$localizations['subsActive'] = 0;
	}

	wp_localize_script( 'bbp_topic', 'bbpTopicJS', $localizations );
}

/**
 * Add or remove a topic from a user's favorites
 *
 * @since bbPress (r2652)
 *
 * @uses bbp_get_current_user_id() To get the current user id
 * @uses current_user_can() To check if the current user can edit the user
 * @uses bbp_get_topic() To get the topic
 * @uses check_ajax_referer() To verify the nonce & check the referer
 * @uses bbp_is_user_favorite() To check if the topic is user's favorite
 * @uses bbp_remove_user_favorite() To remove the topic from user's favorites
 * @uses bbp_add_user_favorite() To add the topic from user's favorites
 */
function supreme_dim_favorite () {
	$user_id = bbp_get_current_user_id();
	$id      = intval( $_POST['id'] );

	if ( !current_user_can( 'edit_user', $user_id ) )
		die( '-1' );

	if ( !$topic = bbp_get_topic( $id ) )
		die( '0' );

	check_ajax_referer( "toggle-favorite_$topic->ID" );

	if ( bbp_is_user_favorite( $user_id, $topic->ID ) ) {
		if ( bbp_remove_user_favorite( $user_id, $topic->ID ) )
			die( '1' );
	} else {
		if ( bbp_add_user_favorite( $user_id, $topic->ID ) )
			die( '1' );
	}

	die( '0' );
}

/**
 * Subscribe/Unsubscribe a user from a topic
 *
 * @since bbPress (r2668)
 *
 * @uses bbp_is_subscriptions_active() To check if the subscriptions are active
 * @uses bbp_get_current_user_id() To get the current user id
 * @uses current_user_can() To check if the current user can edit the user
 * @uses bbp_get_topic() To get the topic
 * @uses check_ajax_referer() To verify the nonce & check the referer
 * @uses bbp_is_user_subscribed() To check if the topic is in user's
 *                                 subscriptions
 * @uses bbp_remove_user_subscriptions() To remove the topic from user's
 *                                        subscriptions
 * @uses bbp_add_user_subscriptions() To add the topic from user's subscriptions
 */
function supreme_dim_subscription () {
	if ( !bbp_is_subscriptions_active() )
		return;

	$user_id = bbp_get_current_user_id();
	$id = intval( $_POST['id'] );

	if ( !current_user_can( 'edit_user', $user_id ) )
		die( '-1' );

	if ( !$topic = bbp_get_topic( $id ) )
		die( '0' );

	check_ajax_referer( "toggle-subscription_$topic->ID" );

	if ( bbp_is_user_subscribed( $user_id, $topic->ID ) ) {
		if ( bbp_remove_user_subscription( $user_id, $topic->ID ) )
			die( '1' );
	} else {
		if ( bbp_add_user_subscription( $user_id, $topic->ID ) )
			die( '1' );
	}

	die( '0' );
}

/**
 * Registers additional menus.
 *
 * @since 0.1.6
 * @uses register_nav_menu() Registers a nav menu with WordPress.
 * @link http://codex.wordpress.org/Function_Reference/register_nav_menu
 */
function supreme_register_menus() {

	register_nav_menu( 'header-primary', _x( 'Header Primary', 'nav menu location', 'supreme' ) );
	register_nav_menu( 'header-secondary', _x( 'Header Secondary', 'nav menu location', 'supreme' ) );
	register_nav_menu( 'header-horizontal', _x( 'Header Horizontal', 'nav menu location', 'supreme' ) );
	register_nav_menu( 'footer', _x( 'Footer', 'nav menu location', 'supreme' ) );

}

/**
 * Register additional sidebars.
 *
 * @since 0.1.6
 */
function supreme_register_sidebars() {

	$subsidiary_2 = array(
		'id' => 'subsidiary-2c',
		'name' => _x( 'Subsidiary 2 Columns', 'sidebar', 'supreme' ),
		'description' => __( 'A 2-column widget area loaded before the footer of the site.', 'supreme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-wrap widget-inside">',
		'after_widget' => '</div></div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	);
	
	$subsidiary_3 = array(
		'id' => 'subsidiary-3c',
		'name' => _x( 'Subsidiary 3 Columns', 'sidebar', 'supreme' ),
		'description' => __( 'A 3-column widget area loaded before the footer of the site.', 'supreme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-wrap widget-inside">',
		'after_widget' => '</div></div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	);
	
	$subsidiary_4 = array(
		'id' => 'subsidiary-4c',
		'name' => _x( 'Subsidiary 4 Columns', 'sidebar', 'supreme' ),
		'description' => __( 'A 4-column widget area loaded before the footer of the site.', 'supreme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-wrap widget-inside">',
		'after_widget' => '</div></div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	);
	
	$subsidiary_5 = array(
		'id' => 'subsidiary-5c',
		'name' => _x( 'Subsidiary 5 Columns', 'sidebar', 'supreme' ),
		'description' => __( 'A 5-column widget area loaded before the footer of the site.', 'supreme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-wrap widget-inside">',
		'after_widget' => '</div></div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	);
	
	$after_header = array(
		'id' => 'after-header',
		'name' => _x( 'After Header', 'sidebar', 'supreme' ),
		'description' => __( 'A 1-column widget area loaded after the header of the site.', 'supreme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-wrap widget-inside">',
		'after_widget' => '</div></div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	);
	
	$after_header_2 = array(
		'id' => 'after-header-2c',
		'name' => _x( 'After Header 2 Columns', 'sidebar', 'supreme' ),
		'description' => __( 'A 2-column widget area loaded after the header of the site.', 'supreme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-wrap widget-inside">',
		'after_widget' => '</div></div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	);
	
	$after_header_3 = array(
		'id' => 'after-header-3c',
		'name' => _x( 'After Header 3 Columns', 'sidebar', 'supreme' ),
		'description' => __( 'A 3-column widget area loaded after the header of the site.', 'supreme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-wrap widget-inside">',
		'after_widget' => '</div></div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	);
	
	$after_header_4 = array(
		'id' => 'after-header-4c',
		'name' => _x( 'After Header 4 Columns', 'sidebar', 'supreme' ),
		'description' => __( 'A 4-column widget area loaded after the header of the site.', 'supreme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-wrap widget-inside">',
		'after_widget' => '</div></div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	);
	
	$after_header_5 = array(
		'id' => 'after-header-5c',
		'name' => _x( 'After Header 5 Columns', 'sidebar', 'supreme' ),
		'description' => __( 'A 5-column widget area loaded after the header of the site.', 'supreme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-wrap widget-inside">',
		'after_widget' => '</div></div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	);
	
	$entry = array(
		'id' => 'entry',
		'name' => _x( 'Entry', 'sidebar', 'supreme' ),
		'description' => __( 'Loaded directly before the entry content texts.', 'supreme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-wrap widget-inside">',
		'after_widget' => '</div></div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	);
	
	$widgets_template = array(
		'id' => 'widgets-template',
		'name' => __( 'Widgets Template', 'sidebar', 'supreme' ),
		'description' => __( 'Used on widgets only page template.', 'supreme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-wrap widget-inside">',
		'after_widget' => '</div></div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	);


	$widgets_mega_menu = array(
		'id' => 'mega_menu',
		'name' => __('JQuery Mega Menu','supreme'),
		'description' => __('Display jquery mega menu.','supreme'),
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>'
	);
	
	$widgets_contact_page = array(
		'id' => 'contact_page_widget',
		'name' => __('Contact Page Widget Area','supreme'),
		'description' => __('Displays widgets on contact us page.','supreme'),
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>'
	);

	register_sidebar( $subsidiary_2 );
	register_sidebar( $subsidiary_3 );
	register_sidebar( $subsidiary_4 );
	register_sidebar( $subsidiary_5 );
	register_sidebar( $after_header );
	register_sidebar( $after_header_2 );
	register_sidebar( $after_header_3 );
	register_sidebar( $after_header_4 );
	register_sidebar( $after_header_5 );
	register_sidebar( $entry );
	register_sidebar( $widgets_template );
/*	Code By Templatic Start */
	
	/*	Widget area for jQuery Mega Menu	*/
		register_sidebar( $widgets_mega_menu );
	/*	Widget area for jQuery Mega Menu	*/
	
	/*	Widget area for Contact us page	*/
		register_sidebar( $widgets_contact_page );
	/*	Widget area for Contact us page	*/
	
/*	Code By Templatic End */	

}
function templatic_searching_filter_where($where){
	global $wpdb;
	//$_REQUEST['adv_search'];tag_s,catdrop,catelog_todate,catelog_frmdate,articleauthor,exactyes
	//$where.=" AND $wpdb->posts.post_type='product'";
	//echo date('Y-m-d h:i:s');
	$query_and = "";
	if(isset($_REQUEST['catelog_todate']) && $_REQUEST['catelog_todate'] != ""){
		$todate = $_REQUEST['catelog_todate'];
		$todate= explode('/',$todate);
		$todate = $todate[2]."-".$todate[0]."-".$todate[1];
		
	}else{
		$todate = date('Y-m-d');
	}
	if(isset($_REQUEST['catelog_frmdate']) && $_REQUEST['catelog_frmdate'] != ""){
		$fromdate = $_REQUEST['catelog_frmdate'];
		$fromdate= explode('/',$fromdate);
		$fromdate = $fromdate[2]."-".$fromdate[0]."-".$fromdate[1];
	}else{
		$fromdate = date('Y-m-d');
	}
	
	if(isset($_REQUEST['catelog_todate']) && $_REQUEST['catelog_todate']!="" or isset($_REQUEST['catelog_frmdate']) && $_REQUEST['catelog_frmdate']!=""){
		$query_and = " AND post_date between '".$todate." 00:00:00' and '".$fromdate." 23:59:59'";
	}
	
	if(isset($_REQUEST['articleauthor']) && $_REQUEST['articleauthor'] != ""){
		global $wpdb;
		
		$author = $_REQUEST['articleauthor'];
		if(isset($_REQUEST['exactyes']) && $_REQUEST['exactyes'] == 1){
			$sql = "select ID from $wpdb->users where user_login like '$author'";
			$sql_get_users = $wpdb->get_var($sql);
			$users_ids = $sql_get_users;
		}else{
			//articleauthor
			$sql = "select ID,user_login from $wpdb->users where user_login like '%$author%'";
			$sql_get_users = $wpdb->get_results($sql);
			foreach($sql_get_users as $templatic_users){
				$user_id .= $templatic_users->ID.',';
			}
			$users_ids = rtrim($user_id,",");
		}
		$query_and .= " AND post_author in($users_ids)";
	}
	$catdrop = $_REQUEST['catdrop'];
	if(isset($catdrop) && $catdrop>0)
	{
		$query_and .= " AND  $wpdb->posts.ID in (select $wpdb->term_relationships.object_id from $wpdb->term_relationships join $wpdb->term_taxonomy on $wpdb->term_taxonomy.term_taxonomy_id=$wpdb->term_relationships.term_taxonomy_id and $wpdb->term_taxonomy.term_id=\"$catdrop\" ) ";
	}
	
	$where.=$query_and;
	return $where; 
}
add_action('admin_init','supreme_wpup_changes',20);

function supreme_wpup_changes(){
	 remove_action( 'after_theme_row_supreme', 'wp_theme_update_row' ,10, 2 );
}
if(!function_exists('customAdmin')){
function customAdmin() {
    echo "<style>
	
	.table_tnews {
		float: right;
		width: 63%;
		}
		
	.t_theme {
		float: left;
		width: 34%;
		margin-right: 3%;
		}
		
	.t_theme img {
		max-width: 100%;
		}
		
	.clearfix { clear:both; }

	.clearfix:after{
		clear: both;
		content: ".";
		display: block;
		font-size: 0;
		height: 0;
		line-height: 0;
		visibility: hidden;
		}
	
	.theme_meta .more a.btn_viewdetails,
	.theme_meta .more a.btn_viewdemo {
		margin: 10px 10px 0px 0px;
		}
		
	.table_tnews .news li p {
		margin-top: 0;
		}
	.templatic-dismiss {
		background: url('images/xit.gif') no-repeat scroll 0px 2px transparent;
		position: absolute;
		right: 60px;
		top: 8px;
		width: 0px;
		font-size: 13px;
		line-height: 1;
		padding: 0 0 0 10px;
		text-decoration: none;
		text-indent: 3px;
	}

	.templatic-dismiss:hover {
		background-position: -10px 2px;
	}
	
	.templatic_autoinstall{
		position:relative;
	}
	div.updated, .login .message,
	{
		background: #FFFBE4;
		border-color: #DFDFDF;
		}
	
	.postbox .inside {
		margin: 15px 0 !important;
		}
	
	.themeunit{
		margin-bottom: 10px;
		}
	
	
	#TB_window,
	#TB_iframeContent {
		width: 550px !important;
		height: 460px !important;
		margin-top: 0 !important;
		}
		
	#TB_iframeContent body {
		padding: 0 !important;
		}
	
	.templatic_login {
		background: none repeat scroll 0 0 #FFFFFF;
		border: 1px solid #E5E5E5;
		box-shadow: 0 4px 10px -1px rgba(200, 200, 200, 0.7);
		font-size: 14px;
		font-weight: normal;
		margin: 20px !important;
		padding: 26px 24px;
		}
		
	.templatic_login label {
		color: #777777;
		font-size: 14px;
		}
		
	.templatic_login form .input, .templatic_login input[type='text'], .templatic_login input[type='password'] {
		background: none repeat scroll 0 0 #FBFBFB;
		border: 1px solid #E5E5E5;
		box-shadow: 1px 1px 2px rgba(200, 200, 200, 0.2) inset;
		color: #555555;
		font-size: 24px;
		font-weight: 200;
		line-height: 1;
		margin-bottom: 16px;
		margin-right: 6px;
		margin-top: 2px;
		outline: 0 none;
		padding: 10px 8px 6px;
		width: 100%;
		}
		
	.templatic_login input[type='submit'] {
		background-color: #21759B;
		background-image: linear-gradient(to bottom, #2A95C5, #21759B);
		border-color: #21759B #21759B #1E6A8D;
		box-shadow: 0 1px 0 rgba(120, 200, 230, 0.5) inset;
		color: #FFFFFF;
		text-decoration: none;
		text-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
		height: 30px;
    	line-height: 28px;
   		padding: 0 12px 2px;
		border-radius: 3px 3px 3px 3px;
		border-style: solid;
		border-width: 1px;
		cursor: pointer;
		display: inline-block;
		font-size: 12px;
		margin-right: 10px;
		}
		
	.templatic_login p.info {
		margin-top: 0; 
		}
	
	body {
		height: auto;
		min-width: 380px !important;
		}
		
	#pblogo {
		margin-top: 0;
		}
		
	#TB_window {
		left: 53% !important;
		top: 100px !important;
		}
	
	
	</style>";
}
}
add_action('admin_head', 'customAdmin', 11);


/* filter for excerpt length */
if(!function_exists('tevolution_excerpt_length')){
	function tevolution_excerpt_length() {
		$tmpdata = get_option('supreme_theme_settings');
		if($tmpdata['templatic_excerpt_length']){
			return $tmpdata['templatic_excerpt_length'];
		}else{
			return 400;
		}
	}
}

/*
Name : new_excerpt_more
Desc : Read more link filter
*/
if(!function_exists('new_excerpt_more')){
	function new_excerpt_more($more) {
		global $post;
		$tmpdata = get_option('supreme_theme_settings');
		if($tmpdata['templatic_excerpt_link']){
			return '... <a class="moretag" href="'. get_permalink($post->ID) . '">'.$tmpdata['templatic_excerpt_link'].'</a>';
		}else{
			return '... <a class="moretag" href="'. get_permalink($post->ID) . '">'.__('Read more &raquo;',DOMAIN).'</a>';
		}
	}
}

// Variable & intelligent excerpt length.
if(!function_exists('print_excerpt')){
	function print_excerpt($length) { // Max excerpt length. Length is set in characters
		global $post;
		$tmpdata = get_option('supreme_theme_settings');
		$morelink=$tmpdata['templatic_excerpt_link'];
		if($morelink!="")
			$morelink=sprintf(__("<a href='%s'>%s</a>",'supreme'),get_permalink(),$morelink);
		else
			$morelink=sprintf(__("<a href='%s'>Read More...</a>",'supreme'),get_permalink());
			
		$text = $post->post_excerpt;
		if ($text =='') {
			$text = get_the_content($post->ID);
			$text = apply_filters('the_excerpt', $text);
			$text = str_replace(']]>', ']]>', $text);
		}
		$text = strip_shortcodes($text); // optional, recommended
		$text = strip_tags($text); // use ' $text = strip_tags($text,'<p><a>'); ' if you want to keep some tags

		$text = substr($text,0,$length);		
		if(reverse_strrchr($text, '.', 1)){
			$excerpt = reverse_strrchr($text, '.', 1)." ".$morelink;
		}else{
			$excerpt = $text." ".$morelink;
		}
		
		if( $excerpt ) {
			echo apply_filters('the_excerpt',$excerpt);
		} else {
			echo apply_filters('the_excerpt',$text);
		}
	}
}
// Returns the portion of haystack which goes until the last occurrence of needle
if(!function_exists('reverse_strrchr')){
	function reverse_strrchr($haystack, $needle, $trail) {
		return strrpos($haystack, $needle) ? substr($haystack, 0, strrpos($haystack, $needle) + $trail) : false;
	}
}
?>