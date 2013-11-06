<?php 
// Define variables for Event post type

define('CUSTOM_POST_TYPE_EVENT','event');
define('CUSTOM_CATEGORY_TYPE_EVENT','ecategory');
define('CUSTOM_TAG_TYPE_EVENT','etags');

define('CUSTOM_MENU_TITLE',__('Events',T_DOMAIN));
define('CUSTOM_MENU_NAME',__('Events',T_DOMAIN));
define('CUSTOM_MENU_SIGULAR_NAME',__('Event',T_DOMAIN));
define('CUSTOM_MENU_ADD_NEW',__('Add an Event',T_DOMAIN));
define('CUSTOM_MENU_ADD_NEW_ITEM',__('Add new Event',T_DOMAIN));
define('CUSTOM_MENU_EDIT',__('Edit',T_DOMAIN));
define('CUSTOM_MENU_EDIT_ITEM',__('Edit Event',T_DOMAIN));
define('CUSTOM_MENU_NEW',__('New Event',T_DOMAIN));
define('CUSTOM_MENU_VIEW',__('View Event',T_DOMAIN));
define('CUSTOM_MENU_SEARCH',__('Search Event',T_DOMAIN));
define('CUSTOM_MENU_NOT_FOUND',__('No Event found',T_DOMAIN));
define('CUSTOM_MENU_NOT_FOUND_TRASH',__('No Event found in trash',T_DOMAIN));


define('CUSTOM_MENU_EVENT_CAT_LABEL',__('Event categories',T_DOMAIN));
define('CUSTOM_MENU_EVENT_CAT_TITLE',__('Event categories',T_DOMAIN));
define('CUSTOM_MENU_EVENT_SIGULAR_CAT',__('Event Category',T_DOMAIN));
define('CUSTOM_MENU_EVENT_CAT_SEARCH',__('Search category',T_DOMAIN));
define('CUSTOM_MENU_EVENT_CAT_POPULAR',__('Popular categories',T_DOMAIN));
define('CUSTOM_MENU_EVENT_CAT_ALL',__('All categories',T_DOMAIN));
define('CUSTOM_MENU_EVENT_CAT_PARENT',__('Parent category',T_DOMAIN));
define('CUSTOM_MENU_EVENT_CAT_PARENT_COL',__('Parent category:',T_DOMAIN));
define('CUSTOM_MENU_EVENT_CAT_EDIT',__('Edit category',T_DOMAIN));
define('CUSTOM_MENU_EVENT_CAT_UPDATE',__('Update category',T_DOMAIN));
define('CUSTOM_MENU_EVENT_CAT_ADDNEW',__('Add new category',T_DOMAIN));
define('CUSTOM_MENU_EVENT_CAT_NEW_NAME',__('New category name',T_DOMAIN));

	
define('CUSTOM_MENU_TAG_LABEL_EVENT',__('Event tags',T_DOMAIN));
define('CUSTOM_MENU_TAG_TITLE_EVENT',__('Event tags',T_DOMAIN));
define('CUSTOM_MENU_TAG_SEARCH_EVENT',__('Event tags',T_DOMAIN));
define('CUSTOM_MENU_TAG_POPULAR_EVENT',__('Popular Event tags',T_DOMAIN));
define('CUSTOM_MENU_TAG_ALL_EVENT',__('All Event tags',T_DOMAIN));
define('CUSTOM_MENU_TAG_PARENT_EVENT',__('Parent Event tags',T_DOMAIN));
define('CUSTOM_MENU_TAG_PARENT_COL_EVENT',__('Parent Event tags:',T_DOMAIN));
define('CUSTOM_MENU_TAG_EDIT_EVENT',__('Edit Event tags',T_DOMAIN));
define('CUSTOM_MENU_TAG_UPDATE_EVENT',__('Update Event tags',T_DOMAIN));
define('CUSTOM_MENU_TAG_ADD_NEW_EVENT',__('Add new Event tags',T_DOMAIN));
define('CUSTOM_MENU_TAG_NEW_ADD_EVENT',__('New Event tag name',T_DOMAIN));

define('EVENT_ST_TIME',__('Start Time',T_DOMAIN));
define('EVENT_END_TIME',__('End Time',T_DOMAIN));
//custom field information title
define('EVENT_CUSTOM_INFORMATION',__('Event Custom Information',T_DOMAIN));
define('CUSTOM_INFORMATION',__('Custom Information',T_DOMAIN));
define('ORGANIZER_CUSTOM_INFORMATION',__('More about the Organizers',T_DOMAIN));

define('EVENT_TITLE_HEAD',__('Title',T_DOMAIN));
define('EVENT_TYPE_TEXT',__('Event type',T_DOMAIN));
define('ADDRESS',__('Address',T_DOMAIN));
define('CATGORIES_TEXT',__('Categories',T_DOMAIN));
define('TAGS_TEXT_HEAD',__('Tags',T_DOMAIN));

global $wpdb;
$custom_post_type = CUSTOM_POST_TYPE_EVENT;
$custom_cat_type = CUSTOM_CATEGORY_TYPE_EVENT;
$custom_tag_type = CUSTOM_TAG_TYPE_EVENT;


		
				
				
if(strstr($_SERVER['REQUEST_URI'],'themes.php') && !isset($_REQUEST['template']) && !isset($_GET['page']) ) 
{
	//$post_arr_merge = array();
	$post_arr_merge[$custom_post_type] = 
				array(	'label' 			=> CUSTOM_MENU_SIGULAR_NAME,
						'labels' 			=> array(	'name' 			 => 	CUSTOM_MENU_SIGULAR_NAME,
												'singular_name' 	 => 	CUSTOM_MENU_SIGULAR_NAME,
												'add_new' 		 =>  CUSTOM_MENU_ADD_NEW,
												'add_new_item' 	 =>  CUSTOM_MENU_ADD_NEW_ITEM,
												'edit' 			 =>  CUSTOM_MENU_EDIT,
												'edit_item' 		 =>  CUSTOM_MENU_EDIT_ITEM,
												'new_item' 		 =>  CUSTOM_MENU_NEW,
												'view_item'		 =>  CUSTOM_MENU_VIEW,
												'search_items' 	 =>  CUSTOM_MENU_SEARCH,
												'not_found' 		 =>  CUSTOM_MENU_NOT_FOUND,
												'not_found_in_trash' =>  CUSTOM_MENU_NOT_FOUND_TRASH	),
						'public' 			=> true,
						'has_archive'       => true,
						'can_export'		=> true,
						'show_ui' 		=> true, /* SHOW UI IN ADMIN PANEL */
						'_builtin' 		=> false, /* IT IS A CUSTOM POST TYPE NOT BUILT IN */
						'_edit_link' 		=> 'post.php?post=%d',
						'capability_type' 	=> 'post',
						'menu_icon' 		=> get_template_directory_uri() .'/images/favicon.ico',
						'hierarchical' 	=> false,
						'rewrite' 		=> array("slug" => "$custom_post_type"), /* PERMALINKS TO EVENT POST TYPE */
						'query_var' 		=> "$custom_post_type", /* THIS GOES TO WPQUERY SCHEMA */
						'supports' 		=> array(	'title', 'author','excerpt','comments','editor','trackbacks','custom-fields','revisions') ,
						'show_in_nav_menus'	=> true ,
						'slugs'			=> array("$custom_cat_type","$custom_tag_type"),
						'taxonomies'		=> array(CUSTOM_MENU_EVENT_SIGULAR_CAT,CUSTOM_MENU_TAG_LABEL_EVENT)
					
				);
				$original = get_option('templatic_custom_post');
				if($original)
				{
					$post_arr_merge = array_merge($original,$post_arr_merge);
				}
				
				ksort($post_arr_merge);
				update_option('templatic_custom_post',$post_arr_merge);

/* EOF - REGISTER EVENT POST TYPE */
				
/* REGISTER CUSTOM TAXONOMY FOR POST TYPE EVENT */
$original = array();
//$taxonomy_arr_merge = array();

	$taxonomy_arr_merge[$custom_cat_type] = 
				 
				array (	"hierarchical" 	=> true, 
						"label" 			=> CUSTOM_MENU_EVENT_CAT_LABEL, 
						"post_type"		=> $custom_post_type,
						'labels' 			=> array(	'name' 			=>  CUSTOM_MENU_EVENT_CAT_TITLE,
												'singular_name' 	=>  $custom_cat_type,
												'search_items' 	=>  CUSTOM_MENU_EVENT_CAT_SEARCH,
												'popular_items' 	=>  CUSTOM_MENU_EVENT_CAT_SEARCH,
												'all_items' 		=>  CUSTOM_MENU_EVENT_CAT_ALL,
												'parent_item' 		=>  CUSTOM_MENU_EVENT_CAT_PARENT,
												'parent_item_colon' =>  CUSTOM_MENU_EVENT_CAT_PARENT_COL,
												'edit_item' 		=>  CUSTOM_MENU_EVENT_CAT_EDIT,
												'update_item'		=>  CUSTOM_MENU_EVENT_CAT_UPDATE,
												'add_new_item' 	=>  CUSTOM_MENU_EVENT_CAT_ADDNEW,
												'new_item_name' 	=>  CUSTOM_MENU_EVENT_CAT_NEW_NAME,	), 
						'public' 			=> true,
						'show_ui' 		=> true,
						"rewrite" 		=> true	
				);
				$original = get_option('templatic_custom_taxonomy');
				if($original)
				{
					$taxonomy_arr_merge = array_merge($original,$taxonomy_arr_merge);
				}
				//register_taxonomy($custom_cat_type,array($custom_post_type),$taxonomy_arr_merge[$custom_cat_type]);
				ksort($taxonomy_arr_merge);
				update_option('templatic_custom_taxonomy',$taxonomy_arr_merge);
/*EOF - REGISTER CUSTOM TAXONOMY FOR POST TYPE EVENT */

	/* REGISTER TAG FOR POST TYPE EVENT */
	$tag_arr_merge = array();
	$tag_arr_merge[$custom_tag_type] =
				array(	"hierarchical" 	=> false, 
						"label" 			=> CUSTOM_MENU_TAG_LABEL_EVENT, 
						"post_type"		=> $custom_post_type,
						'labels' 			=> array(	'name' 			=>  CUSTOM_MENU_TAG_TITLE_EVENT,
												'singular_name' 	=>  $custom_tag_type,
												'search_items' 	=>  CUSTOM_MENU_TAG_SEARCH_EVENT,
												'popular_items' 	=>  CUSTOM_MENU_TAG_POPULAR_EVENT,
												'all_items' 		=>  CUSTOM_MENU_TAG_ALL_EVENT,
												'parent_item' 		=>  CUSTOM_MENU_TAG_PARENT_EVENT,
												'parent_item_colon' =>  CUSTOM_MENU_TAG_PARENT_COL_EVENT,
												'edit_item' 		=>  CUSTOM_MENU_TAG_EDIT_EVENT,
												'update_item'		=>  CUSTOM_MENU_TAG_UPDATE_EVENT,
												'add_new_item' 	=>  CUSTOM_MENU_TAG_ADD_NEW_EVENT,
												'new_item_name' 	=>  CUSTOM_MENU_TAG_NEW_ADD_EVENT,	),  
						'public' 			=> true,
						'show_ui' 		=> true,
						"rewrite" 		=> true	
				);
				$original = get_option('templatic_custom_tags');
				if($original)
				{
					$tag_arr_merge = array_merge($original,$tag_arr_merge);
				}
				ksort($tag_arr_merge);
				update_option('templatic_custom_tags',$tag_arr_merge);
				
				
				
	add_action( 'init', 'create_custom_taxonomy1' );
	function create_custom_taxonomy1() {
	
		$args = get_option('templatic_custom_taxonomy');
		if($args): 
			foreach($args as $key=> $_args)
			{
				//register_taxonomy($_args['labels']['singular_name'],array($_args['post_type']),$args[$key]);
			}
		endif;
	}
				
				
	 $post_category = $wpdb->get_row("SELECT ID FROM $wpdb->posts WHERE $wpdb->posts.post_title = 'Post Category' and $wpdb->posts.post_type = 'custom_fields'");
	 if(count($post_category) != 0)
	 {
	 	update_post_meta($post_category->ID,'heading_type','[#taxonomy_name#]');
		update_post_meta($post_category->ID,'taxonomy_type_'.CUSTOM_CATEGORY_TYPE_EVENT,CUSTOM_CATEGORY_TYPE_EVENT);
		update_post_meta($post_category->ID,'post_type_'.CUSTOM_POST_TYPE_EVENT,CUSTOM_POST_TYPE_EVENT);
		update_post_meta($post_category->ID,'post_type','post,'.CUSTOM_POST_TYPE_EVENT);
	 }
	 $post_title = $wpdb->get_row("SELECT ID FROM $wpdb->posts WHERE $wpdb->posts.post_title = 'Post Title' and $wpdb->posts.post_type = 'custom_fields'");
	 if(count($post_title) != 0)
	 {
	 	update_post_meta($post_title->ID,'heading_type','[#taxonomy_name#]');
		update_post_meta($post_title->ID,'taxonomy_type_'.CUSTOM_CATEGORY_TYPE_EVENT,CUSTOM_CATEGORY_TYPE_EVENT);
		update_post_meta($post_title->ID,'post_type_'.CUSTOM_POST_TYPE_EVENT,CUSTOM_POST_TYPE_EVENT);
		update_post_meta($post_title->ID,'post_type','post,'.CUSTOM_POST_TYPE_EVENT);
	 }
	 $post_content = $wpdb->get_row("SELECT ID FROM $wpdb->posts WHERE $wpdb->posts.post_title = 'Post Content' and $wpdb->posts.post_type = 'custom_fields'");
	 if(count($post_content) != 0)
	 {
	 	update_post_meta($post_content->ID,'heading_type','[#taxonomy_name#]');
		update_post_meta($post_content->ID,'taxonomy_type_'.CUSTOM_CATEGORY_TYPE_EVENT,CUSTOM_CATEGORY_TYPE_EVENT);
		update_post_meta($post_content->ID,'post_type_'.CUSTOM_POST_TYPE_EVENT,CUSTOM_POST_TYPE_EVENT);
		update_post_meta($post_content->ID,'post_type','post,'.CUSTOM_POST_TYPE_EVENT);
	 }
	 $post_excerpt = $wpdb->get_row("SELECT ID FROM $wpdb->posts WHERE $wpdb->posts.post_title = 'Post Excerpt' and $wpdb->posts.post_type = 'custom_fields'");
	 if(count($post_excerpt) != 0)
	 {
	 	update_post_meta($post_excerpt->ID,'heading_type','[#taxonomy_name#]');
		update_post_meta($post_excerpt->ID,'taxonomy_type_'.CUSTOM_CATEGORY_TYPE_EVENT,CUSTOM_CATEGORY_TYPE_EVENT);
		update_post_meta($post_excerpt->ID,'post_type_'.CUSTOM_POST_TYPE_EVENT,CUSTOM_POST_TYPE_EVENT);
		update_post_meta($post_excerpt->ID,'post_type','post,'.CUSTOM_POST_TYPE_EVENT);
	 }
	  $post_images = $wpdb->get_row("SELECT ID FROM $wpdb->posts WHERE $wpdb->posts.post_title = 'Post Images' and $wpdb->posts.post_type = 'custom_fields'");
	 if(count($post_images) != 0)
	 {
	 	update_post_meta($post_images->ID,'heading_type','[#taxonomy_name#]');
		update_post_meta($post_images->ID,'taxonomy_type_'.CUSTOM_CATEGORY_TYPE_EVENT,CUSTOM_CATEGORY_TYPE_EVENT);
		update_post_meta($post_images->ID,'post_type_'.CUSTOM_POST_TYPE_EVENT,CUSTOM_POST_TYPE_EVENT);
		update_post_meta($post_images->ID,'post_type','post,'.CUSTOM_POST_TYPE_EVENT);
	 }
	
	$basic_inf = $wpdb->get_row("SELECT ID FROM $wpdb->posts WHERE $wpdb->posts.post_title = '[#taxonomy_name#]' and $wpdb->posts.post_type = 'custom_fields'");
	if(count($basic_inf) != 0)
	 {
	 	update_post_meta($basic_inf->ID,'heading_type','[#taxonomy_name#]');
		update_post_meta($basic_inf->ID,'taxonomy_type_'.CUSTOM_CATEGORY_TYPE_EVENT,CUSTOM_CATEGORY_TYPE_EVENT);
		update_post_meta($basic_inf->ID,'post_type_'.CUSTOM_POST_TYPE_EVENT,CUSTOM_POST_TYPE_EVENT);
		update_post_meta($basic_inf->ID,'post_type','post,'.CUSTOM_POST_TYPE_EVENT);
	 }
	 
	 
	/* Insert Post heading into posts */
 	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'org_info' and $wpdb->posts.post_type = 'custom_fields'"); 	 
	
	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Organizer Information',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'org_info',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'post_type'=> $custom_post_type,
			'post_type_event'=> $custom_post_type,
			'ctype'=>'heading_type',
			'htmlvar_name'=>'org_info',
			'sort_order' => '7',
			'is_active' => '1',
			'is_require' => '0',
			'show_on_page' => 'both_side',
			'show_in_column' => '0',
			'show_on_listing' => '0',
			'is_edit' => 'true',
			'show_on_detail' => '1',
			'is_search'=>'0',
			'is_delete' => '0'
			);
		
		$post_id = wp_insert_post( $my_post );
		//wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
 	 }	
	
	 /* Insert Post Geo Address into posts */
	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'address' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Address',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'address',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'heading_type' => '[#taxonomy_name#]',
			'post_type'=> $custom_post_type,
			'post_type_event'=> $custom_post_type,
			'ctype'=>'geo_map',
			'htmlvar_name'=>'address',
			'is_require' => '1',
			'sort_order' => '7',
			'is_active' => '1',
			'show_on_page' => 'both_side',
			'show_in_column' => '0',
			'show_on_listing' => '0',
			'is_edit' => 'true',
			'show_on_detail' => '1',
			'is_delete' => '0',
			'show_on_success' => '1',
			'is_search'=>'0',
			'field_require_desc' => 'Please Enter Address',
			'validation_type' => 'require',
			);
		$post_id = wp_insert_post( $my_post );
		//wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
 	 }
	
	 
	 /* Insert Post Google Map View into posts */
	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'map_view' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Google Map View',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'map_view',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'heading_type' => '[#taxonomy_name#]',
			'post_type'=> $custom_post_type,
			'post_type_event'=> $custom_post_type,
			'ctype'=>'radio',
			'htmlvar_name'=>'map_view',
			'sort_order' => '8',
			'is_active' => '1',
			'is_require' => '0',
			'show_on_page' => 'both_side',
			'show_in_column' => '0',
			'show_on_listing' => '0',
			'is_edit' => 'true',
			'show_on_detail' => '1',
			'is_delete' => '0',
			'is_search'=>'0',
			'show_on_success' => '1',
			'option_values' => 'Road Map,Terrain Map,Satellite Map'
			);
		$post_id = wp_insert_post( $my_post );
	//	wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
	 }
	 /* Insert Post Event Start Date into posts */
	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'st_date' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Event Start Date',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'st_date',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'heading_type' => '[#taxonomy_name#]',
			'post_type'=> $custom_post_type,
			'post_type_event'=> $custom_post_type,
			'ctype'=>'date',
			'htmlvar_name'=>'st_date',
			'is_require' => '1',
			'sort_order' => '9',
			'is_active' => '1',
			'show_on_page' => 'both_side',
			'show_in_column' => '0',
			'show_on_listing' => '1',
			'is_edit' => 'true',
			'show_on_detail' => '1',
			'is_delete' => '0',
			'show_on_success' => '1',
			'is_search'=>'0',
			'field_require_desc' => 'Please Enter Start Date',
			'validation_type' => 'require'
			);
		$post_id = wp_insert_post( $my_post );
		//wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
 		
	 }
	 
	 /* Insert Post Event End Date into posts */
	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'end_date' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Event End Date',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'end_date',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'heading_type' => '[#taxonomy_name#]',
			'post_type'=> $custom_post_type,
			'post_type_event'=> $custom_post_type,
			'ctype'=>'date',
			'htmlvar_name'=>'end_date',
			'sort_order' => '10',
			'is_active' => '1',
			'is_require' => '1',
			'show_on_page' => 'both_side',
			'show_in_column' => '0',
			'show_on_listing' => '1',
			'is_edit' => 'true',
			'show_on_detail' => '1',
			'is_delete' => '0',
			'is_search'=>'0',
			'field_require_desc' => 'Please Enter End date',
			'validation_type' => 'require',
			'show_on_success' => '1'
			);
		$post_id = wp_insert_post( $my_post );
		//wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
 		
	 }
	 
	 /* Insert Post Start Time into posts */
	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'st_time' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Start Time',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'st_time',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'heading_type' => '[#taxonomy_name#]',
			'post_type'=> $custom_post_type,
			'post_type_event'=> $custom_post_type,
			'ctype'=>'text',
			'htmlvar_name'=>'st_time',
			'is_require' => '1',
			'sort_order' => '11',
			'is_active' => '1',
			'show_on_page' => 'both_side',
			'show_in_column' => '0',
			'show_on_listing' => '1',
			'is_edit' => 'true',
			'show_on_detail' => '1',
			'is_delete' => '0',
			'show_on_success' => '1',
			'is_search'=>'0',
			'field_require_desc' => 'Please Enter Start Time',
			'validation_type' => 'require'
			);
		$post_id = wp_insert_post( $my_post );
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
 		
	 }
	 
	 /* Insert End Time into posts */
	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'end_time' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'End Time',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'end_time',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'heading_type' => '[#taxonomy_name#]',
			'post_type'=> $custom_post_type,
			'post_type_event'=> $custom_post_type,
			'ctype'=>'text',
			'htmlvar_name'=>'end_time',
			'is_require' => '1',
			'sort_order' => '12',
			'is_active' => '1',
			'show_on_page' => 'both_side',
			'show_in_column' => '0',
			'show_on_listing' => '1',
			'is_edit' => 'true',
			'show_on_detail' => '1',
			'is_delete' => '0',
			'show_on_success' => '1',
			'is_search'=>'0',
			'field_require_desc' => 'Please Enter End time',
			'validation_type' => 'require',
			);
		$post_id = wp_insert_post( $my_post );
	
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
	 }
	 
	 /* Insert Consider this event as into posts */
	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'event_type' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Consider this event as',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'event_type',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'heading_type' => '[#taxonomy_name#]',
			'post_type'=> $custom_post_type,
			'post_type_event'=> $custom_post_type,
			'ctype'=>'radio',
			'htmlvar_name'=>'event_type',
			'sort_order' => '13',
			'is_active' => '1',
			'is_require' => '1',
			'show_on_page' => 'both_side',
			'show_in_column' => '0',
			'show_on_listing' => '0',
			'is_edit' => 'true',
			'is_search'=>'0',
			'show_on_detail' => '1',
			'is_delete' => '0',
			'option_values' => 'Regular event, Recurring event',
			'field_require_desc' => 'Please Select Event Type',
			'validation_type' => 'require'
			);
		$post_id = wp_insert_post( $my_post );
		//wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
 	
	 }
	 
	 /* Insert How to Register into posts */
	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'reg_desc' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'How to Register',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'reg_desc',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'heading_type' => '[#taxonomy_name#]',
			'post_type'=> $custom_post_type,
			'post_type_event'=> $custom_post_type,
			'ctype'=>'texteditor',
			'htmlvar_name'=>'reg_desc',
			'sort_order' => '15',
			'is_active' => '1',
			'is_require' => '0',
			'show_on_page' => 'both_side',
			'show_in_column' => '0',
			'show_on_listing' => '0',
			'is_edit' => 'true',
			'show_on_detail' => '0',
			'is_delete' => '0',
			'is_search'=>'0',
			'show_on_success' => '1'
			);
		$post_id = wp_insert_post( $my_post );
		//wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
 		
	 }
	 /* Insert Phone into posts */
	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'phone' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Phone',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'phone',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'heading_type' => '[#taxonomy_name#]',
			'post_type'=> $custom_post_type,
			'post_type_event'=> $custom_post_type,
			'ctype'=>'text',
			'htmlvar_name'=>'phone',
			'sort_order' => '16',
			'is_active' => '1',
			'is_require' => '0',
			'show_on_page' => 'both_side',
			'show_in_column' => '0',
			'show_on_listing' => '0',
			'is_edit' => 'true',
			'show_on_detail' => '1',
			'is_delete' => '0',
			'is_search'=>'0',
			'show_on_success' => '1'
			);
		$post_id = wp_insert_post( $my_post );
		//wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
 		

	 }
	 /* Insert Email into posts */
	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'email' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Email',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'email',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'heading_type' => '[#taxonomy_name#]',
			'post_type'=> $custom_post_type,
			'post_type_event'=> $custom_post_type,
			'ctype'=>'text',
			'htmlvar_name'=>'email',
			'sort_order' => '17',
			'is_active' => '1',
			'is_require' => '1',
			'show_on_page' => 'both_side',
			'show_in_column' => '0',
			'show_on_listing' => '0',
			'is_edit' => 'true',
			'show_on_detail' => '1',
			'is_delete' => '0',
			'is_search'=>'0',
			'validation_type' => 'email',
			'show_on_success' => '1'
			);
		$post_id = wp_insert_post( $my_post );
		//wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }

	 }
	 /* Insert Website into posts */
	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'website' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Website',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'website',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'heading_type' => '[#taxonomy_name#]',
			'post_type'=> $custom_post_type,
			'post_type_event'=> $custom_post_type,
			'ctype'=>'text',
			'htmlvar_name'=>'website',
			'sort_order' => '18',
			'is_active' => '1',
			'is_require' => '0',
			'show_on_page' => 'both_side',
			'show_in_column' => '0',
			'show_on_listing' => '0',
			'is_edit' => 'true',
			'show_on_detail' => '1',
			'is_delete' => '0',
			'is_search'=>'0',
			'show_on_success' => '1'
			);
		$post_id = wp_insert_post( $my_post );
		//wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
 		
	 }
	 /* Insert Twitter into posts */
	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'twitter' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Twitter',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'twitter',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'heading_type' => 'Organizer Information',
			'post_type'=> $custom_post_type,
			'post_type_event'=> $custom_post_type,
			'ctype'=>'text',
			'htmlvar_name'=>'twitter',
			'sort_order' => '19',
			'is_active' => '1',
			'is_require' => '0',
			'show_on_page' => 'both_side',
			'show_in_column' => '0',
			'show_on_listing' => '0',
			'is_edit' => 'true',
			'show_on_detail' => '1',
			'is_delete' => '0',
			'is_search'=>'0',
			'show_on_success' => '1'
			);
		$post_id = wp_insert_post( $my_post );
		//wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
 		
	 }
	 /* Insert Facebook into posts */
	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'facebook' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Facebook',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'facebook',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'heading_type' => 'Organizer Information',
			'post_type'=> $custom_post_type,
			'post_type_event'=> $custom_post_type,
			'ctype'=>'text',
			'htmlvar_name'=>'facebook',
			'sort_order' => '20',
			'is_active' => '1',
			'is_require' => '0',
			'show_on_page' => 'both_side',
			'show_in_column' => '0',
			'show_on_listing' => '0',
			'is_edit' => 'true',
			'show_on_detail' => '1',
			'is_delete' => '0',
			'is_search'=>'0',
			'show_on_success' => '1'
			);
		$post_id = wp_insert_post( $my_post );
		//wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
 		
	 }
	 /* Insert Video into posts */
	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'video' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Video',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'video',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'heading_type' => 'Organizer Information',
			'post_type'=> $custom_post_type,
			'post_type_event'=> $custom_post_type,
			'ctype'=>'textarea',
			'htmlvar_name'=>'video',
			'sort_order' => '21',
			'is_active' => '1',
			'is_require' => '0',
			'show_on_page' => 'both_side',
			'show_in_column' => '0',
			'show_on_listing' => '0',
			'is_edit' => 'true',
			'show_on_detail' => '1',
			'is_search'=>'0',
			'is_delete' => '0'
			);
		$post_id = wp_insert_post( $my_post );
		//wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
 		
	 }
	 
	 /* Insert Organizer Name into posts */
	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'organizer_name' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Organizer Name',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'organizer_name',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'heading_type' => 'Organizer Information',
			'post_type'=> $custom_post_type,
			'post_type_event'=> $custom_post_type,
			'ctype'=>'text',
			'htmlvar_name'=>'organizer_name',
			'sort_order' => '22',
			'is_active' => '1',
			'is_require' => '0',
			'show_on_page' => 'both_side',
			'show_in_column' => '0',
			'show_on_listing' => '0',
			'is_edit' => 'true',
			'show_on_detail' => '1',
			'is_delete' => '0',
			'is_search'=>'0',
			'show_on_success' => '1'
			);
		$post_id = wp_insert_post( $my_post );
		//wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
 		

	 }
	 /* Insert Organizer Email into posts */
	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'organizer_email' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Organizer Email',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'organizer_email',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'heading_type' => 'Organizer Information',
			'post_type'=> $custom_post_type,
			'post_type_event'=> $custom_post_type,
			'ctype'=>'text',
			'htmlvar_name'=>'organizer_email',
			'sort_order' => '23',
			'is_active' => '1',
			'is_require' => '1',
			'show_on_page' => 'both_side',
			'show_in_column' => '0',
			'show_on_listing' => '0',
			'is_edit' => 'true',
			'show_on_detail' => '1',
			'is_search'=>'0',
			'validation_type' => 'email',
			'is_delete' => '0'
			);
		$post_id = wp_insert_post( $my_post );
		//wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
 		
	 }
	 /* Insert Select Logo into posts */
	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'organizer_logo' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Select Logo',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'organizer_logo',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'heading_type' => 'Organizer Information',
			'post_type'=> $custom_post_type,
			'post_type_event'=> $custom_post_type,
			'ctype'=>'upload',
			'htmlvar_name'=>'organizer_logo',
			'sort_order' => '24',
			'is_active' => '1',
			'is_require' => '0',
			'show_on_page' => 'both_side',
			'show_in_column' => '0',
			'show_on_listing' => '0',
			'is_edit' => 'true',
			'show_on_detail' => '1',
			'is_search'=>'0',
			'is_delete' => '0'
			);
		$post_id = wp_insert_post( $my_post );
		//wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
 	
	 }
	 /* Insert Organizer Address into posts */
	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'organizer_address' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Organizer Address',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'organizer_address',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'heading_type' => 'Organizer Information',
			'post_type'=> $custom_post_type,
			'post_type_event'=> $custom_post_type,
			'ctype'=>'text',
			'htmlvar_name'=>'organizer_address',
			'sort_order' => '25',
			'is_active' => '1',
			'is_require' => '0',
			'show_on_page' => 'both_side',
			'show_in_column' => '0',
			'show_on_listing' => '0',
			'is_edit' => 'true',
			'show_on_detail' => '1',
			'is_search'=>'0',
			'is_delete' => '0'
			);
		$post_id = wp_insert_post( $my_post );
		//wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
 		
		
	 }
	 /* Insert Organizer Contact Info. into posts */
	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'organizer_contact' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Organizer Contact Info.',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'organizer_contact',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'heading_type' => 'Organizer Information',
			'post_type'=> $custom_post_type,
			'post_type_event'=> $custom_post_type,
			'ctype'=>'text',
			'htmlvar_name'=>'organizer_contact',
			'sort_order' => '26',
			'is_active' => '1',
			'is_require' => '0',
			'show_on_page' => 'both_side',
			'show_in_column' => '0',
			'show_on_listing' => '0',
			'is_edit' => 'true',
			'show_on_detail' => '1',
			'is_search'=>'0',
			'is_delete' => '0'
			);
		$post_id = wp_insert_post( $my_post );
		//wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
 		
	 }
	 /* Insert Organizer Website into posts */
	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'organizer_website' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Organizer Website',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'organizer_website',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'heading_type' => 'Organizer Information',
			'post_type'=> $custom_post_type,
			'post_type_event'=> $custom_post_type,
			'ctype'=>'text',
			'htmlvar_name'=>'organizer_website',
			'sort_order' => '27',
			'is_active' => '1',
			'is_require' => '0',
			'show_on_page' => 'both_side',
			'show_in_column' => '0',
			'show_on_listing' => '0',
			'is_edit' => 'true',
			'show_on_detail' => '1',
			'is_search'=>'0',
			'is_delete' => '0'
			);
		$post_id = wp_insert_post( $my_post );
		//wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
	 }
	 /* Insert Organizer Mobile into posts */
	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'organizer_mobile' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Organizer Mobile',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'organizer_mobile',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'heading_type' => 'Organizer Information',
			'post_type'=> $custom_post_type,
			'post_type_event'=> $custom_post_type,
			'ctype'=>'text',
			'htmlvar_name'=>'organizer_mobile',
			'sort_order' => '28',
			'is_active' => '1',
			'is_require' => '0',
			'show_on_page' => 'both_side',
			'show_in_column' => '0',
			'show_on_listing' => '0',
			'is_edit' => 'true',
			'show_on_detail' => '1',
			'is_search'=>'0',
			'is_delete' => '0'
			);
		$post_id = wp_insert_post( $my_post );
		//wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
	 }
	 /* Insert Short Description into posts */
	 $post_content = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'organizer_desc' and $wpdb->posts.post_type = 'custom_fields'");
 	 if(count($post_content) == 0)
	 {
		$my_post = array(
			 'post_title' => 'Short Description',
			 'post_content' => '',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_name' => 'organizer_desc',
			 'post_type' => "custom_fields",
			);
		$post_meta = array(
			'heading_type' => 'Organizer Information',
			'post_type'=> $custom_post_type,
			'post_type_event'=> $custom_post_type,
			'ctype'=>'texteditor',
			'htmlvar_name'=>'organizer_desc',
			'sort_order' => '29',
			'is_active' => '1',
			'is_require' => '0',
			'show_on_page' => 'both_side',
			'show_in_column' => '0',
			'show_on_listing' => '0',
			'is_edit' => 'true',
			'show_on_detail' => '1',
			'is_search'=>'0',
			'is_delete' => '0'
			);
		$post_id = wp_insert_post( $my_post );
		//wp_set_post_terms($post_id,'1','category',true);
		foreach($post_meta as $key=> $_post_meta)
		 {
			add_post_meta($post_id, $key, $_post_meta);
		 }
	 }

}

?>