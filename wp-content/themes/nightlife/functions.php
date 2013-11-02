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

define('T_DOMAIN','nightlife');
load_theme_textdomain(T_DOMAIN);
load_textdomain( T_DOMAIN, get_stylesheet_directory().'/languages/en_US.mo');

/* Only admin is able to visit this page START */
global $pagenow;
if(is_admin() && 'customize.php' == $pagenow){
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this section.',T_DOMAIN ) );
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
	
	/*Remove suprem register menus Header Primary, Header Secondary, Header Horizontal, Footer */
	remove_action( 'init', 'supreme_register_menus' );
	
	/* Add framework menus. */
	add_theme_support( 'hybrid-core-menus', array( // Add core menus.
		'primary',
		'secondary',		
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
	$tmpdata = get_option('templatic_settings');
	$home_listing = $tmpdata['home_listing_type_value'];
	if(file_exists(get_stylesheet_directory()."/functions/listing_filters.php") && ! is_admin()){
		if(is_array($home_listing) && in_array('event',$home_listing)){
		include_once(get_stylesheet_directory()."/functions/listing_filters.php");
		}
	}
	if(file_exists(TEMPLATE_FUNCTION_FOLDER_PATH."/auto_install/auto_install.php")){
		include_once(TEMPLATE_FUNCTION_FOLDER_PATH.'/auto_install/auto_install.php');
	}
	if(file_exists(get_stylesheet_directory()."/functions/preview_custom_functions.php")){
		include_once(get_stylesheet_directory()."/functions/preview_custom_functions.php");
	}
	if(function_exists('_iscurlinstalled') && _iscurlinstalled())
	{	
		if(file_exists(get_stylesheet_directory()."/functions/facebook-platform/src/facebook.php")){
			include_once (TEMPLATE_FUNCTION_FOLDER_PATH.'facebook-platform/src/facebook.php');
		}
	}
	global $sitepress;
	if(class_exists('sitepress')){
		$default_language = $sitepress->get_default_language();
	}else{ $default_language ='en'; }
	if(is_plugin_active('wpml-translation-management/plugin.php') && ICL_LANGUAGE_CODE !=$default_language){
					
					$siteurl = site_url()."/".ICL_LANGUAGE_CODE;
					$site_url = $siteurl;
	}else{
		$site_url = site_url().'/';
	}	
	
	/*Add filter sidebars widgets  disable according layout option */
	add_filter( 'sidebars_widgets', 'nightlife_disable_sidebars' );
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
		global $post;		
		$post_types=get_post_types();
		if($post->ID!="")
		{
			$page_template=get_post_meta($post->ID,'_wp_page_template',true);
			if($page_template=='page-template_map.php' || $page_template=='page-template_form.php' || $page_template=='page-template_advanced_search.php')
			{
				remove_meta_box('theme-layouts-post-meta-box',$post_type,'side');
			}			
		}
		foreach($post_types as $post_type):		
			if($post_type!='post' && $post_type!='page' && $post_type!="attachment" && $post_type!="revision" && $post_type!="nav_menu_item"):				
				remove_meta_box('theme-layouts-post-meta-box',$post_type,'side');
			endif;
		endforeach;
	}
	/*Add Meta Boxes for remove theme layout meta box */
	add_action( 'add_meta_boxes', 'remove_theme_layout_meta_box',11 );
	
	add_action('admin_head','theme_layout_meta_box_script');
	function theme_layout_meta_box_script()
	{
		?>
           <script type="text/javascript">
			jQuery.noConflict(); 
			jQuery(document).ready(function() {				
									  
			if(jQuery("#page_template").val() !='page-template_form.php' && jQuery("#page_template").val() !='page-template_map.php' && jQuery("#page_template").val() !='page-template_advanced_search.php'){
				jQuery("#theme-layouts-post-meta-box").css('display','block');				
			}else{
				jQuery("#theme-layouts-post-meta-box").css('display','none');

			}
			
		    jQuery("#page_template").change(function() {
			   var src = jQuery(this).val();
					if(jQuery("#page_template").val() =='page-template_form.php' || jQuery("#page_template").val() =='tpl_archives.php' || jQuery("#page_template").val() =='page-template_map.php' || jQuery("#page_template").val() =='page-template_advanced_search.php'){
						jQuery("#theme-layouts-post-meta-box").fadeOut(2000);
					 }else{
						jQuery("#theme-layouts-post-meta-box").fadeIn(2000);
					}
				});
			});
		</script>
          <?php	
	}
	
	
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
	return '<p><i>If you want to use categorywise custom fields with Nightlife theme then select YES for <b>"Show custom fields categorywise"</b> option from general settings.</i></p><p>Note : Nightlife default fields will work only if categorywise custom fields set as "NO"</p>';
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
	
	if(is_array($home_listing) || !empty($home_listing))
	{
		if(empty($home_listing))
		{		
			update_option('templatic_settings',$tmpdata);
		}
	}else{update_option('templatic_settings',$tmpdata);}
}

/* Theme upgrade notification msg code */
require ('theme-update-checker.php');
$example_update_checker = new ThemeUpdateChecker(
	'nightlife',                                            //Theme folder name, AKA "slug". 
	'http://update.templatic.com/version-nightlife.php' //URL of the metadata file.
);

add_action('templ_post_type_description','home_listing_post_type_description');

function home_listing_post_type_description()
{
	echo "<p class='description'> "; 
	_e('If you want to display the listing of another post type in home page then first deselect the POST and EVENT post type, NightLife is specially contain the features related to event theme so when ever you select EVENT post type listing you can&lsquo;t show the listing of any other post type.',T_DOMAIN);	
	echo "</p>";
}

/*
 * Add action save posts for store then recurring event user template
 */
add_action( 'save_post', 'recurring_event_user' );
function recurring_event_user()
{
	global $post;	
	if(isset($_POST['page_template']) && $_POST['page_template']=='recurring_event_user.php')
	{
		update_option('recurring_event_page_template_id',$post->ID);
	}
	
}

/*
 * Function name: Slider search form
 * return: display slider search option from widget bar
 */
add_action('templ_search_slider_widget_form','templ_search_slider',10,2);
function templ_search_slider($this_id,$instance)
{
	define('DISPLAY_TEXT',__('Display Text',T_DOMAIN));
	?>
     <script type="text/javascript">
	function select_search(id,div_id)
	{
		var checked=id.checked;						
		jQuery('#'+div_id).slideToggle('slow');						
	}
	function select_location(id,div_id)
	{
		var checked=id.checked;						
		jQuery('#'+div_id).slideToggle('slow');						
	}
	</script>
     <p>
       <input id="<?php echo $this_id->get_field_id('search'); ?>" type="checkbox" name="<?php echo $this_id->get_field_name('search'); ?>" value="1" <?php checked(1, $instance['search']); ?> onclick="select_search(this,'<?php echo $this_id->get_field_id('search_home_slide_post'); ?>');"/> 
       <label for="<?php echo $this_id->get_field_id('search'); ?>"><?php _e('show search on slider',DOMAIN); ?></label>
     </p>                      
     <div id="<?php echo $this_id->get_field_id('search_home_slide_post'); ?>" style=" <?php if($instance['search'] =='1'){ ?>display:block;<?php }else{?>display:none;<?php }?>">                         
          <p>
          <label for="<?php echo $this_id->get_field_id('search_post_type');?>" ><?php _e('Select Post Type:',T_DOMAIN);?>     </label>	
          <select  id="<?php echo $this_id->get_field_id('search_post_type'); ?>" name="<?php echo $this_id->get_field_name('search_post_type'); ?>" class="widefat">        	
         <?php				   
               $all_post_types = get_post_types();
               foreach($all_post_types as $post_types){
                    if( $post_types != "page" && $post_types != "attachment" && $post_types != "revision" && $post_types != "nav_menu_item" ){
                         ?>
                         <option value="<?php echo $post_types;?>" <?php if($post_types== $instance['search_post_type'])echo "selected";?>><?php echo esc_attr($post_types);?></option>
                     <?php				
                    }
               }
          ?>	
          </select>                       
          <span><?php _e('Search by selected post type',DOMAIN);?></span>
         </p>
     </div>     
     <p>                      
       <input id="<?php echo $this_id->get_field_id('location'); ?>" type="checkbox" name="<?php echo $this_id->get_field_name('location'); ?>" value="1" <?php checked(1, $instance['location']); ?> onclick="select_location(this,'<?php echo $this_id->get_field_id('search_home_slide_distance'); ?>');"/> 
       <label for="<?php echo $this_id->get_field_id('location'); ?>"> <?php _e('Show Search With Location.<br/>',DOMAIN); ?></label>
       <span> <?php _e('This option work only with address custom field.',DOMAIN); ?></span>  
     </p>    
     <div id="<?php echo $this_id->get_field_id('search_home_slide_distance'); ?>" style=" <?php if($instance['location'] =='1'){ ?>display:block;<?php }else{?>display:none;<?php }?>">
          <p>
            <label for="<?php echo $this_id->get_field_id('distance'); ?>"><?php _e('Distance in',DOMAIN); ?>:
             <select id="<?php echo $this_id->get_field_id('distance'); ?>" name="<?php echo $this_id->get_field_name('distance'); ?>" style="width:50%;">
                 <option value="Miles" <?php if(esc_attr($instance['distance']) == 'Miles'){ echo 'selected="selected"';}?>><?php _e('Miles',DOMAIN);?></option>
                 <option value="Kilometer" <?php if(esc_attr($instance['distance']) == 'Kilometer'){ echo 'selected="selected"';}?>><?php _e('Kilometer',DOMAIN);?></option>
            </select>
            </label>
          </p>
          <p>
            <label for="<?php echo $this_id->get_field_id('radius'); ?>"><?php _e('Radius',DOMAIN); ?>:
            <input class="widefat" id="<?php echo $this_id->get_field_id('radius'); ?>" name="<?php echo $this_id->get_field_name('radius'); ?>" type="text" value="<?php echo esc_attr($instance['radius']); ?>" />
            </label>
          </p>
     </div> 
      <p>
      
          <label for="<?php echo $this_id->get_field_id('display_text'); ?>"><?php echo DISPLAY_TEXT; ?>:
          <input class="widefat" id="<?php echo $this_id->get_field_id('display_text'); ?>" name="<?php echo $this_id->get_field_name('display_text'); ?>" type="text" value="<?php echo esc_attr($instance['display_text']); ?>" />
          </label>
        </p>  
     <?php
}
/*
 * Function Name: slider search option show on front side
 * Return : display search form
 */
add_action('templ_slider_search_widget','templ_slider_search_widget_output');
function templ_slider_search_widget_output($instance)
{
	if( isset($instance['search']) && $instance['search']!=''):
		 if( isset($instance['location']) && $instance['location'] != ''):
				$distance= isset($instance['distance']) && $instance['distance']!=""?$instance['distance']:'Miles';
				$radius= isset($instance['radius']) && $instance['radius']!=""?$instance['radius']:'1000';		
				echo slider_search_form('search_box',$instance['search_post_type'],$instance['display_text'],'slider_search_date',$distance,$radius);
		 else:
			echo slider_search_form('search_box',$instance['search_post_type'],$instance['display_text']);
		 endif;
	endif;	
}
/*
 * slider_search_form Name:
 */
function slider_search_form($class,$post_type='post',$display_text='',$date_id='',$distance='',$radius='')
{			
	$form_url = site_url()."/";	
	?>	
	<script type="text/javascript">
     jQuery.noConflict();
	jQuery(document).ready(function(){
		jQuery('.show_hide').click(function(){
			jQuery(".slidingDiv").slideToggle();
		});
     });
     
     function inputFocus(ph, el){
		if(el.placeholder == ph)
			el.placeholder = "";
     }
     function inputBlur(ph, el){
		if(el.placeholder == "")
			el.placeholder = ph;
     }
     </script>
                                                
          <div class="slider_content_bg">
          
               <div class="slider_content_wrap">
                    <a href="#" class="show_hide"><?php _e('Search Event',DOMAIN); ?></a>
                    <div class="slider_content slidingDiv clearfix">
					<?php $count_posts = wp_count_posts($post_type);
                         echo '<h2><strong>';
                                          $count_posts = wp_count_posts($post_type);
                                         printf($display_text,$count_posts->publish);
                                         echo '</strong></h2>';
                                         $s = get_search_query();
										 
                                         ?>
                           <script>
					function search_slider_filter()
					{						
						var sr = '';
						if(document.getElementById('s').value=='')
						{
							document.getElementById('s').value = ' ';
						}					
					}
					</script>              
                         <div class="<?php echo $class;?> clearfix">	
                              <form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) );?>" >
                             
                              <input type="text" name="s" id="s" class="input_white" placeholder='<?php _e('Which',T_DOMAIN);?> <?php echo $post_type;?> <?php _e('you like to search?',T_DOMAIN);?>' value="<?php echo $s;?>"/>
                              
                              <input type="hidden" name="post_type" value="<?php echo $post_type;?>" />
                              <?php if($radius!='' && $distance!=''):?>
                              <script type="text/javascript">
                              jQuery(function(){
                                   var pickerOpts = {						
                                        dateFormat: 'yy-mm-dd'
                                   };	
                                   jQuery("#<?php echo $date_id?>").datepicker(pickerOpts);
                              });
                              </script>
                              <input type="text" id="<?php echo $date_id;?>" name="date" class="input_grey when"  placeholder='<?php _e('When?',T_DOMAIN); ?>'/>
                              <input type="text" name="location" id="location" class="input_grey where"   placeholder='<?php _e('Where?',T_DOMAIN); ?>'/>
                              <input type="hidden" name="radius"  value="<?php echo $radius; ?>" />
                              <input type="hidden" name="distance"   value="<?php echo $distance; ?>" />
                              <?php endif;?>
                        
                               <input type="hidden" name="slider_search" value="1" />
                              <input type="submit" value="" name="submit" class="submit" onclick="return search_slider_filter();" />            
                              </form>                                                   
                         </div>
                    </div>
               </div>
          </div>
	<?php					
	
}

//ADDED CODE FOR FAVICON ICON SETTINGS START.
add_action('admin_head', 'Nightlifefavocin_icon');
function Nightlifefavocin_icon() {
	$GetSupremeThemeOptions = get_option('supreme_theme_settings');
	$GetFaviconIcon = $GetSupremeThemeOptions['supreme_favicon_icon'];
	if($GetFaviconIcon!=""){
		echo '<link rel="shortcut icon" href="' . $GetFaviconIcon . '" />';
	}
}
//ADDED CODE FOR FAVICON ICON SETTINGS FINISH.

add_action('slider_before_post_title','event_slider_before_post_title');

function event_slider_before_post_title()
{
	global $post;
	?>
     <h2 class='slider_date'><?php echo date('d',strtotime(get_post_meta($post->ID,'st_date',true)));?></h2>
     <span class="slider_address"><?php echo date_i18n(get_option("date_format"),strtotime(get_post_meta($post->ID,'st_date',true))); ?>, <?php echo get_post_meta($post->ID,'st_time',true); ?></span>
     <?php
}


/*
 * Function Name: category_featured_image
 * Return: add the featured image on category listing page
 */
add_action('tmpl_before_archive_page_image','category_featured_image');
add_action('tmpl_before_category_page_image','category_featured_image');
function category_featured_image()
{
	global $post;
	if(is_archive()){
		$featured=get_post_meta(get_the_ID(),'featured_c',true);
		if($featured=='c'){echo '<span class="featured"><img src="'.get_stylesheet_directory_uri().'/images/featured_img.png" /></span>';}
	}
}


/**
 * Disables sidebars based on layout choices.
 *
 * @since 0.1
 */
function nightlife_disable_sidebars( $sidebars_widgets ) {	
	
	global $wpdb,$wp_query,$post;
	//fetch the current page texonomy
	$current_term = $wp_query->get_queried_object();	
	
	//fetch the tevolution taxonomy
	if(function_exists('tevolution_get_taxonomy')){
		$custom_taxonomy = tevolution_get_taxonomy();
	}
	//fetch the tevolution taxonomy tag
	if(function_exists('tevolution_get_taxonomy_tags')){
		$custom_taxonomy_tags = tevolution_get_taxonomy_tags();
	}
	//fetch the tevolution post type
	if(function_exists('tevolution_get_post_type')){
		$custom_post_type = tevolution_get_post_type();
	}

	if ( current_theme_supports( 'theme-layouts' ) && !is_admin() ) {
	
		if ( 'layout-1c' == theme_layouts_get_layout() ) {
				
				$taxonomy=get_query_var( 'taxonomy' );
				if(is_tax()){
					$sidebars_widgets[$taxonomy.'_listing_sidebar'] = false;
					$sidebars_widgets[$taxonomy.'_tag_listing_sidebar'] = false;
				}
				if(is_single()){
					$sidebars_widgets[get_post_type().'_detail_sidebar'] = false;
				}
				if(is_page())
				{
					$post_type=get_post_meta($post->ID,'submit_post_type',true);
					if($post_type!='')
					{
						$sidebars_widgets['add_'.$post_type.'_submit_sidebar'] = false;
					}
				}
				if(is_home())
				{
					$sidebars_widgets['front_sidebar'] = false;
				}
			
			
		}
	}

	return $sidebars_widgets;
}
?>