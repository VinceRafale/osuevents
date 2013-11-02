<?php
/**
 * This is your child theme functions file.  In general, most PHP customizations should be placed within this
 * file.  Sometimes, you may have to overwrite a template file.  However, you should consult the theme 
 * documentation and support forums before making a decision.  In most cases, what you want to accomplish
 * can be done from this file alone.  This isn't a foreign practice introduced by parent/child themes.  This is
 * how WordPress works.  By utilizing the functions.php file, you are both future-proofing your site and using
 * a general best practice for coding.
 *
 * All style/design changes should take place within your style.css file, not this one.
 *
 * The functions file can be your best friend or your worst enemy.  Always double-check your code to make
 * sure that you close everything that you open and that it works before uploading it to a live site.
 *
 * @package SupremeChild
 * @subpackage Functions
 */

/* Adds the child theme setup function to the 'after_setup_theme' hook. */
//error_reporting(E_ALL);

add_action( 'after_setup_theme', 'supreme_child_theme_setup', 11 );
define(T_DOMAIN,'nightlife');
load_theme_textdomain(T_DOMAIN);
load_textdomain( T_DOMAIN, get_stylesheet_directory().'/languages/en_US.mo');

/* Only admin is able to visit this page START */
global $pagenow;
if(is_admin() && 'customize.php' == $pagenow){
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this section.' ) );
	}
}
/* Only admin is able to visit this page END */
/**
 * Setup function.  All child themes should run their setup within this function.  The idea is to add/remove 
 * filters and actions after the parent theme has been set up.  This function provides you that opportunity.
 *
 * @since 0.1.0
 */
function supreme_child_theme_setup() {

	/* Get the theme prefix ("supreme"). */
	$prefix = hybrid_get_prefix();
	define('TEMPLATE_FUNCTION_FOLDER_PATH',get_stylesheet_directory()."/functions/");
	define('TEMPLATE_CHILD_DIRECTORY_PATH',get_stylesheet_directory().'/');
	
	if(file_exists(ABSPATH . 'wp-admin/includes/plugin.php' )){
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}
	/* Example action. */
	// add_action( "{$prefix}_header", 'dotos_child_example_action' );

	/* Example filter. */
	// add_filter( "{$prefix}_site_title", 'dotos_child_example_filter' );
		
	add_theme_support( 'theme-layouts', array( // Add theme layout options.
		'1c',
		'2c-l',
		'2c-r'
	) );
	
	
	remove_theme_support( 'supreme_jigoshop_layout' );
	add_theme_support( 'supreme_woocommerce_layout' );
	
	if ( function_exists ( 'is_bbpress' ) ) {
	
		if ( function_exists( 'bbp_is_topic' ) ) {
			if ( bbp_is_topic() )
				wp_dequeue_script( 'supreme-bbpress-topic', trailingslashit( get_template_directory_uri() ) . 'js/bbpress-topic.js', array( 'wp-lists' ), false, true );
		}
				
		if( function_exists( 'bbp_is_single_user_edit' ) ) {
			if ( bbp_is_single_user_edit() )
				wp_dequeue_script( 'user-profile' );
		}
	
	}
	
	/* for BuddyPress */
	
	if ( function_exists ( 'bp_is_active' ) ) {

		wp_dequeue_style( 'bp' );

		/* Load BuddyPress-specific styles. */
		wp_dequeue_script ( 'supreme-buddypress', trailingslashit ( get_template_directory_uri() ) . 'css/buddypress.css', false, '20120608', 'all' );
	
	}
	
	
	global $blog_id;
	if(get_option('upload_path') && !strstr(get_option('upload_path'),'wp-content/uploads'))
	{
		$upload_folder_path = "wp-content/blogs.dir/$blog_id/files/";
	}else
	{
		$upload_folder_path = "wp-content/uploads/";
	}
	/*  Add Action for Customizer Controls Settings Start */
			add_action( 'customize_register',  'nightlife_register_customizer_settings');
	/*  Add Action for Customizer Controls Settings End */
	
	/*	Stylesheet for theme color settings START */
			add_action('wp_head', 'nightlife_load_theme_stylesheet');
	/*	Stylesheet fro theme color settings End */
	
	global $blog_id;
	if($blog_id){ $thumb_url = "&amp;bid=$blog_id";}

	if(file_exists(get_stylesheet_directory()."/language.php")){
		include_once(get_stylesheet_directory()."/language.php");
	}
	if(file_exists(get_stylesheet_directory()."/functions/auto_install/data-generator.php")){
		include_once(get_stylesheet_directory()."/functions/auto_install/data-generator.php");
	}
	if(file_exists(get_stylesheet_directory()."/functions/widget_functions.php") ){
		include_once(get_stylesheet_directory()."/functions/widget_functions.php");
	}
	if(file_exists(get_stylesheet_directory()."/functions/custom_functions.php")){
		include_once(get_stylesheet_directory()."/functions/custom_functions.php");
	}
	
	if(file_exists(get_stylesheet_directory()."/functions/listing_filters.php") && ! is_admin()){
		include_once(get_stylesheet_directory()."/functions/listing_filters.php");
	}
	if(file_exists(TEMPLATE_FUNCTION_FOLDER_PATH."/auto_install/auto_install.php")){
		include_once(TEMPLATE_FUNCTION_FOLDER_PATH.'/auto_install/auto_install.php');
	}
	if(file_exists(get_stylesheet_directory()."/functions/preview_custom_functions.php")){
		include_once(get_stylesheet_directory()."/functions/preview_custom_functions.php");
	}
	if(_iscurlinstalled())
	{					   
		if(file_exists(get_stylesheet_directory()."/functions/facebook-platform/src/facebook.php")){
			include_once (TEMPLATE_FUNCTION_FOLDER_PATH.'facebook-platform/src/facebook.php');
		}
	}
	
		
	
}



/* Register new image sizes. */
add_action( 'init', 'nightlife_register_image_sizes' );
function nightlife_register_image_sizes()
{
	add_image_size( 'event-home-thumb', 96, 55, true );
	add_image_size( 'home-page-slider', 2400, 528, true );
	add_image_size( 'thumbnail', 210, 210, true );
	if(get_option('thumbnail_size_w')!=210)
		update_option('thumbnail_size_w',210);
	if(get_option('thumbnail_size_h')!=210)
		update_option('thumbnail_size_h',210);
	add_image_size( 'taxonomy-thumbnail', 310, 150, true );
	add_image_size( 'taxonomy-slider', 640, 200, true );
}
/**-- function to fetch category listng--**/
function fetch_categories_ids($taxonomy){
global $wpdb;
 $terms = $wpdb->get_results("select * from $wpdb->terms t,$wpdb->term_taxonomy tt where t.term_id = tt.term_id and tt.taxonomy = '".$taxonomy."'");
 foreach($terms as $ttl){
	$sep=" , ";
	$cat_list = "<b>".$ttl->term_id."</b> - ".$ttl->slug.$sep;
	echo $cat_list;
 }	
}

/* remove templ_post_info action for remov the post meta information */
remove_action('templ_post_info','post_info');
/* add action for display post information in listing page*/
add_action('templ_before_post_title','templ_listing_page_post_info');



/*
 * Add new meta post in page 
 */
add_action( 'add_meta_boxes', 'facebook_page_meta_box' );
function facebook_page_meta_box()
{
	add_meta_box("facebook_page_option", "Facebook Evenet Options", "facebook_page_option", "page");
	
}
add_action( 'save_post', 'facebook_save_postdata' );
/*
 * Add meta box for facebook event option
 */
function facebook_page_option()
{
	?>
	<script type="text/javascript">
	jQuery.noConflict(); 
	jQuery(document).ready(function() {
	if(jQuery("#page_template").val() !='page-template_facebookevents.php'){
		jQuery("#facebook_page_option").css('display','none');
	}
	
    jQuery("#page_template").change(function() {
        var src = jQuery(this).val();
			if(jQuery("#page_template").val() =='page-template_facebookevents.php'){
			jQuery("#facebook_page_option").fadeIn(500); }else{
			jQuery("#facebook_page_option").fadeOut(500);
			}
		});
	});
	</script>
     <?php
	global $post;
	$facebook_app_id = get_post_meta($post->ID,'facebook_app_id',true);
	$facebook_secret_id = get_post_meta($post->ID,'facebook_secret_id',true);
	$facebook_page_id = get_post_meta($post->ID,'facebook_page_id',true);	
	?>
    <table >    
    	<tr valign="top">
            <th><label><?php _e("Application Id",DOMAIN);?></label></th>
            <td>
                <input type="text" name="facebook_app_id" value="<?php if(isset($facebook_app_id)) { echo $facebook_app_id; } ?>"/>
                <p class="description"><?php _e('Enter the facebook Application id.',DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th><label><?php _e("Secret ID",DOMAIN);?></label></th>
            <td>
                <input type="text" name="facebook_secret_id" value="<?php if(isset($facebook_secret_id)) { echo $facebook_secret_id; } ?>"/>
                 <p class="description"><?php _e('Enter facebook secrent id.',DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th><label><?php _e("Page ID",DOMAIN);?></label></th>
            <td>
                <input type="text" name="facebook_page_id" value="<?php if(isset($facebook_page_id)) { echo $facebook_page_id; } ?>"/>
                 <p class="description"><?php _e('Enter facebook page id.',DOMAIN);?></p>
            </td>
        </tr>        
    </table>
    <?php
}
function facebook_save_postdata()
{
	global $post;		
	if(isset($_POST['facebook_app_id']) && $_POST['facebook_app_id']!="")
		update_post_meta($post->ID, 'facebook_app_id', $_POST['facebook_app_id']);
	if(isset($_POST['facebook_secret_id']) && $_POST['facebook_secret_id']!="")
		update_post_meta($post->ID, 'facebook_secret_id', $_POST['facebook_secret_id']);
	if(isset($_POST['facebook_page_id']) && $_POST['facebook_page_id']!="")
		update_post_meta($post->ID, 'facebook_page_id', $_POST['facebook_page_id']);
}

/*Check is admin only */
if (is_admin()) 
{	
	/* Remove theme layout post meta box */
	function remove_theme_layout_meta_box()
	{		
		$post_types=get_post_types();
		foreach($post_types as $post_type):		
			if($post_type!='post' && $post_type!='page' && $post_type!="attachment" && $post_type!="revision" && $post_type!="nav_menu_item"):				
				remove_meta_box('theme-layouts-post-meta-box',$post_type,'side');
			endif;
		endforeach;			
	}
	/*Add Meta Boxes for remove theme layout meta box */
	add_action( 'add_meta_boxes', 'remove_theme_layout_meta_box',11 );
}
function wp_scripts()
{
	?>
	<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
	<?php	
}
add_action('wp_footer', 'wp_scripts');
//do_action('custom_field_filter');

// Theme Specific message for category wise custom fields. START
function function_filter(){
	return '<p><i>If you want to use custom fields with Nightlife theme then select YES for <b>"Show custom fields categorywise"</b> option from general settings.</i></p>';
}
// Theme Specific message for category wise custom fields. END
function nightlife_load_theme_stylesheet(){
	/*	Function to load the custom stylesheet. 
	from this if we select any color from 
	"Theme Color Settings" in backend and 
	save some color then then this file is called	*/
	if(file_exists(get_stylesheet_directory()."/css/admin-style.php")){
		require_once(get_stylesheet_directory().'/css/admin-style.php');
	}
}

/*
 * admin init action for set the home listing type value
 */
add_action('admin_init','home_listing_type_value');

function home_listing_type_value()
{
	$tmpdata = get_option('templatic_settings');
	$home_listing = $tmpdata['home_listing_type_value'];
	if($home_listing=='')
		$home_listing=array();
	$home_listing_type_value = array('event');
	$tmpdata['home_listing_type_value'] = @array_merge($home_listing,$home_listing_type_value);	
	if(is_array($home_listing))
	{
		if(!in_array('event',$home_listing))
		{			
			update_option('templatic_settings',$tmpdata);
		}
	}else{update_option('templatic_settings',$tmpdata);}
}

/* Theme upgrade notification msg code */
require ('theme-update-checker.php');
$example_update_checker = new ThemeUpdateChecker(
	'nightlife',                                            //Theme folder name, AKA "slug". 
	'http://update.templatic.com/version.php' //URL of the metadata file.
);
?>