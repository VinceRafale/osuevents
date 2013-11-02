<?php session_start();

/**-- conditions for activation of Custom Taxonomy --**/
if(((isset($_REQUEST['activated']) && $_REQUEST['activated'] == 'custom_taxonomy') && (isset($_REQUEST['true']) && $_REQUEST['true']==1)) || (isset($_REQUEST['activated']) && $_REQUEST['activated']=='true'))
{
		update_option('custom_taxonomy','Active');
		/* ADDING A FIELD TERM_PRICE IN TERMS TABLE */
		global $wpdb;
		$field_check = $wpdb->get_var("SHOW COLUMNS FROM $wpdb->terms LIKE 'term_price'");
		if('term_price' != $field_check){
		$wpdb->query("ALTER TABLE $wpdb->terms ADD term_price varchar(100) NOT NULL DEFAULT '0'");
		}
}
else if((isset($_REQUEST['deactivate']) && $_REQUEST['deactivate'] == 'custom_taxonomy') && (isset($_REQUEST['true']) && $_REQUEST['true']==0))
{
		delete_option('custom_taxonomy');
}
/**-- coding to add submenu under main menu--**/
if(file_exists(TEMPL_MONETIZE_FOLDER_PATH.'templatic-custom_taxonomy/install.php') && is_active_addons('custom_taxonomy')){
	add_action('templ_add_admin_menu_', 'templ_add_submenu_taxonomy',1);
	function templ_add_submenu_taxonomy()
	{
		$menu_title = __('Custom Post Types',DOMAIN);
		global $taxonomy_screen_option;
		$taxonomy_screen_option = add_submenu_page('templatic_system_menu', $menu_title,$menu_title, 'administrator', 'custom_taxonomy', 'add_custom_taxonomy');
		add_action("load-$taxonomy_screen_option", "taxonomy_screen_options");
	}
	include (TEMPL_MONETIZE_FOLDER_PATH . "templatic-custom_taxonomy/custom_post_type_lang.php");
}
/* Function for screen option */
function taxonomy_screen_options() {
 	global $taxonomy_screen_option;
 	$screen = get_current_screen();
 	// get out of here if we are not on our settings page
	if(!is_object($screen) || $screen->id != $taxonomy_screen_option)
		return;
 
	$args = array(
		'label' => __('Taxonomy per page', DOMAIN),
		'default' => 10,
		'option' => 'taxonomy_per_page'
	);
	add_screen_option( 'per_page', $args );
}

function taxonomy_set_screen_option($status, $option, $value) {
	if ( 'taxonomy_per_page' == $option ) return $value;
}
add_filter('set-screen-option', 'taxonomy_set_screen_option', 10, 3);

/* NAME : Add taxonomy sub menu page
DESCRIPTION : this function adds a submenu page for creating or editing the taxonomies */
function add_custom_taxonomy()
{

	if((isset($_REQUEST['action']) &&  $_REQUEST['action']== 'add_taxonomy') || (isset($_REQUEST['action']) && $_REQUEST['action']== 'edit-type'))
	 {
		 include (TEMPL_MONETIZE_FOLDER_PATH . "templatic-custom_taxonomy/add_custom_taxonomy.php");
	 }
	else
	 {
		 include (TEMPL_MONETIZE_FOLDER_PATH . "templatic-custom_taxonomy/manage_custom_taxonomy.php");
	 }
}
/* EOF - add submenu page for taxonomies */

if(file_exists(TEMPL_MONETIZE_FOLDER_PATH.'templatic-custom_taxonomy/taxonomy_functions.php') && is_active_addons('custom_taxonomy'))
{
	include (TEMPL_MONETIZE_FOLDER_PATH . "templatic-custom_taxonomy/taxonomy_functions.php");	
}

if((isset($_REQUEST['page']) && $_REQUEST['page'] == 'delete-type') && is_active_addons('custom_taxonomy'))
{ 
	 $post_type = get_option("templatic_custom_post");
	 $taxonomy = get_option("templatic_custom_taxonomy");
	 $tag = get_option("templatic_custom_tags");
	 $taxonomy_slug = $post_type[$_REQUEST['post-type']]['slugs'][0];
	 $tag_slug = $post_type[$_REQUEST['post-type']]['slugs'][1];
	 
	 unset($post_type[$_REQUEST['post-type']]);
	 unset($taxonomy[$taxonomy_slug]);
	 unset($tag[$tag_slug]);
	 update_option("templatic_custom_post",$post_type);
	 update_option("templatic_custom_taxonomy",$taxonomy);
	 update_option("templatic_custom_tags",$tag);
	 unlink(get_template_directory()."/taxonomy-".$taxonomy_slug.".php");
	 unlink(get_template_directory()."/taxonomy-".$tag_slug.".php");
	 unlink(get_template_directory()."/single-".$_REQUEST['post-type'].".php");
	 wp_redirect(admin_url("admin.php?page=custom_taxonomy"));
	 $_SESSION['custom_msg_type'] = 'delete';
	 exit;
}

/* NAME : function to load scripts
DESCRIPTION : this function will load all the jscripts */
function upload_admin_scripts()
{
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('my-upload', __(plugin_dir_url( __FILE__ ),DOMAIN).'/upload-script.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('my-upload');
}
/* EOF - load scripts */

/* NAME : function to load the css
DESCRIPTION : this function will load all the css scripts */ 
function upload_admin_styles() {
	wp_enqueue_style('thickbox');
}
/* EOF - load css */ 
if((isset($_REQUEST['action']) && $_REQUEST['action']=="add_taxonomy") || $_REQUEST['action'] == 'edit-type'){
if (is_active_addons('custom_taxonomy')) {
	add_action('admin_print_scripts', 'upload_admin_scripts');
	add_action('admin_print_styles', 'upload_admin_styles');
}
}

/* Register all custom posts, taxonomies, tags from here */
if(is_active_addons('custom_taxonomy')):
	add_action( 'init', 'create_post_type' );
	function create_post_type() {
		$args = get_option('templatic_custom_post');		
		if($args):
			foreach($args as $key=> $_args)
			{
				register_post_type($_args['label'],$args[$key]);
			}			
			if(isset($_REQUEST['post_type']))
			{
				$post_type = $_REQUEST['post_type'];
				if( $post_type != "page" && $post_type != "post" && $post_type != "attachment" && $post_type != "revision" && $post_type != "nav_menu_item" ){
					add_filter( 'manage_edit-'.$post_type.'_columns', 'templatic_edit_taxonomy_columns',10,2) ;
					//add_action( 'manage_'.$post_type.'_posts_custom_column', 'templatic_manage_taxonomy_columns', 10, 2 );
					add_action('manage_posts_custom_column','templatic_manage_taxonomy_columns',10,2);
				}
			}
		endif;
	}
	
	/*
	NAME : create_custom_taxonomy
	DESCRIPTION : Create custom taxonomy , Move taxonomies and detail page files in template directory 
	*/
	add_action( 'init', 'create_custom_taxonomy' );
	function create_custom_taxonomy() {
		$args = get_option('templatic_custom_taxonomy');
		$args1 = get_option('templatic_custom_post');
		if($args):
			foreach($args as $key=> $_args)
			{
				register_taxonomy($_args['labels']['singular_name'],array(@$_args['post_type']),$args[$key]);
				$_name = $args1[$_args["post_type"]]['labels']['name'];
				/*Listing page Sider bar */
				register_sidebars(1,array('id'=>''.$_args["labels"]["singular_name"].'_listing_sidebar','name'=>''.ucfirst($_name).' Listing -  Sidebar','description'=>'An optional widget area for your site','before_widget'=>'<div class="widget">','after_widget'=>'</div>','before_title'=>'<h3>','after_title'=>'</h3>'));
				
				/*Single post Type sider bar  */
				register_sidebars(1,array('id'=>''.$_args["post_type"].'_detail_sidebar','name'=>''.ucfirst($_name).' Detail -  Sidebar','description'=>'An optional widget area for your site','before_widget'=>'<div class="widget">','after_widget'=>'</div>','before_title'=>'<h3>','after_title'=>'</h3>'));
				
				/*Add post submit side bar*/
				register_sidebars(1,array('id'=>'add_'.$_args["post_type"].'_submit_sidebar','name'=>'Add '.ucfirst($_name).' - Sidebar','description'=>'An optional widget area for your site','before_widget'=>'<div class="widget">','after_widget'=>'</div>','before_title'=>'<h3>','after_title'=>'</h3>'));
				
				
				if(!file_exists(get_template_directory().'/taxonomy-'.$_args['labels']['singular_name'].'.php'))
				 {
					$custom_fields = dirname( __FILE__ )."/taxonomy-category.php";
					copy($custom_fields,get_template_directory()."/taxonomy-".$_args['labels']['singular_name'].".php");
				 }
				if(!file_exists(get_template_directory().'/single-'.$_args['post_type'].'.php'))
				 {
					$custom_fields_single = dirname( __FILE__ )."/single-post.php";
					copy($custom_fields_single,get_template_directory()."/single-".$_args['post_type'].".php");
				 }
				
				 $taxonomy = $_args['labels']['singular_name']; /* DEFINE TAXONOMY */
				 /* CODE TO CALL THE FUNCTIONS WHICH MANAGE THE PRICE FIELD IN CATEGORIES */
				 if(isset($taxonomy) && $taxonomy == $_args['labels']['singular_name']) 
				 {
					if(is_active_addons('monetization'))
					{
						add_action($taxonomy.'_edit_form_fields','category_custom_fields_Edit');
						add_action($taxonomy.'_add_form_fields','category_custom_fields_AddField');
						add_action('edited_term','category_custom_fields_AlterField');
						add_action('created_'.$taxonomy,'category_custom_fields_AlterField');
						/* FILTERS TO MANAGE PRICE COLUMNS */
						add_filter('manage_edit-'.$taxonomy.'_columns', 'edit_price_cat_column');	
						add_filter('manage_'.$taxonomy.'_custom_column', 'tmpl_manage_price_cat_col', 10, 3);
					}
				 }
			}
		endif;
	}
	
	add_action( 'init', 'create_custom_tags' );
	function create_custom_tags() {
		$args = get_option('templatic_custom_tags');		
		$args1 = get_option('templatic_custom_post');
		if($args):
			foreach($args as $key=> $_args)
			{
				register_taxonomy($_args['labels']['singular_name'],@$_args['post_type'],$args[$key]);
				$_name = $args1[$_args["post_type"]]['labels']['name'];
				/*Listing page Sider bar */
				register_sidebars(1,array('id'=>''.$_args["labels"]["singular_name"].'_tag_listing_sidebar','name'=>''.ucfirst($_name).' Tag Listing -  Sidebar','description'=>'An optional widget area for your site','before_widget'=>'<div class="widget">','after_widget'=>'</div>','before_title'=>'<h3>','after_title'=>'</h3>'));
				if(!file_exists(get_template_directory().'/taxonomy-'.$_args['labels']['singular_name'].'.php'))
				 {
					$custom_fields = dirname( __FILE__ )."/taxonomy-tags.php";
					copy($custom_fields,get_template_directory()."/taxonomy-".$_args['labels']['singular_name'].".php");
				 }
			}
		endif;
	}
	
	/*
	NAME : templatic_edit_taxonomy_columns
	DESCRIPTION : Return the columns name for backend listing
	*/

	function templatic_edit_taxonomy_columns( $columns )
	{
		global $wpdb;		
		$post_type = $_REQUEST['post_type'];
		wp_reset_query();
		$cus_post_type = get_post_meta($post_id,'template_post_type',true);
		remove_all_actions('posts_where');
		/* code to fetch the columns from custom fields */
		$args = array( 'post_type' => 'custom_fields',
				'posts_per_page' => -1	,
				'post_status' => array('publish'),
				'meta_query' => array(
				   'relation' => 'AND',
					array(
						'key' => 'post_type_'.$post_type.'',
						'value' => array('all',$post_type),
						'compare' => 'IN',
						'type'=> 'text'
					),
					array(
						'key' => 'show_in_column',
						'value' =>  1,
						'compare' => '='
					),
					array(
						'key' => 'is_active',
						'value' =>  1,
						'compare' => '='
					)
				),
					'meta_key' => 'sort_order',
					'orderby' => 'meta_value',
					'order' => 'ASC'
				);
				$fld_query = null;
				add_filter('posts_join', 'custom_field_posts_where_filter');
				$fld_query = new WP_Query($args);
				remove_filter('posts_join', 'custom_field_posts_where_filter');
				$fld_meta_info = $fld_query;

			if(is_plugin_active('wpml-translation-management/plugin.php')){
				$languages = icl_get_languages('skip_missing=0');
				if(!empty($languages)){
					foreach($languages as $l){
						if(!$l['active']) echo '<a href="'.$l['url'].'">';
						if(!$l['active']) $country_flag .= '<img src="'.$l['country_flag_url'].'" height="12" alt="'.$l['language_code'].'" width="18" />'.' ';
						if(!$l['active']) echo '</a>';
					}
				}				
				$columns1 = array(
				'cb' => '<input type="checkbox" />',
				'title' => __('Title',DOMAIN),
				'icl_translations' => $country_flag,
				'categories_' => __('Categories',DOMAIN),
				'tags_' => __('Tags',DOMAIN),
				'author' => __('Author',DOMAIN),
				'posted_on' => __('Posted On',DOMAIN));
			}else
			{
				if($post_type && $post_type=="templatic_booking"){
					$columns1 = array(
					'cb' => '<input type="checkbox" />',
					'title' => __('Title',DOMAIN),				
					'categories_' => __('Categories',DOMAIN),
					'tags_' => __('Tags',DOMAIN),
					'author' => __('Author',DOMAIN),
					'posted_on' => __('Posted On',DOMAIN),
					'status' => __('Status',DOMAIN));
				}else{
					$columns1 = array(
					'cb' => '<input type="checkbox" />',
					'title' => __('Title',DOMAIN),				
					'categories_' => __('Categories',DOMAIN),
					'tags_' => __('Tags',DOMAIN),
					'author' => __('Author',DOMAIN),
					'posted_on' => __('Posted On',DOMAIN));
				}
			}			
			$fld_columns = array();
			if($fld_meta_info->have_posts()){
				while ($fld_meta_info->have_posts()) : $fld_meta_info->the_post();
					global $post;
					$fldname = $post->post_title;
					$varname = $post->post_name;
					if($fldname){
					$array = array( $varname => $fldname);
					$fld_columns = array_merge($array, $fld_columns);
					}
				endwhile;			
			}
			wp_reset_query();
			$columns = array_merge($columns1,$fld_columns);		

		return $columns;
	}
	/* END OF FUNCTION */

	/*
	NAME : templatic_manage_event_columns
	DESCRIPTION : Return the value for specific column
	*/
	function templatic_manage_taxonomy_columns( $column, $post_id )
	{
		global $post;
		if(isset($_REQUEST['post_ID']))
			$post_id=$_REQUEST['post_ID'];
		
		$taxonomy ='';
		$post_type = @$_REQUEST['post_type'];
		$custom_post_types_args = array();  
		$custom_post_types = get_post_types($custom_post_types_args,'objects');
		if  ($custom_post_types) {
			 foreach ($custom_post_types as $content_type) {
			 
				if($content_type->name == $post_type){
				$taxonomy = @$content_type->slugs[0];
				$tags = @$content_type->slugs[1]; break;
				}
			
		  }
		}  				
		switch( $column ) { 
		case 'categories_' :
				/* Get the post_category for the post. */
				$templ_events = get_the_terms($post_id,$taxonomy);
				if (is_array($templ_events)) {
					foreach($templ_events as $key => $templ_event) {
						$edit_link = site_url()."/wp-admin/edit.php?".$taxonomy."=".$templ_event->slug."&post_type=".$post_type;
						$templ_events[$key] = '<a href="'.$edit_link.'">' . $templ_event->name . '</a>';
						}
					echo implode(' , ',$templ_events);
				}else {
					_e( 'Uncategorized',DOMAIN );
				}
				break;
				
			case 'tags_' :
				/* Get the post_tags for the post. */
				$templ_event_tags = get_the_terms($post_id,$tags);
				if (is_array($templ_event_tags)) {
					foreach($templ_event_tags as $key => $templ_event_tag) {
						$edit_link = site_url()."/wp-admin/edit.php?".$tags."=".$templ_event_tag->slug."&post_type=".$post_type;
						$templ_event_tags[$key] = '<a href="'.$edit_link.'">' . $templ_event_tag->name . '</a>';
					}
					echo implode(' , ',$templ_event_tags);
				}else {
					_e( 'No Tags',DOMAIN );
				}
					
				break;
			
			case 'posted_on' :
				/* Get the post_tags for the post. */
				if ($post->post_date) {
					$date_format = get_option('date_format');
					$time_format = get_option('time_format');
					$date = strtotime($post->post_date);
					echo date($date_format." , ".$time_format,$date);
				}else {
					date("F j, Y, g:i a");
				}
					
				break;
			
			case $column :
				if (get_post_meta($post_id,$column ,true)) {
					$value =  get_post_meta($post_id,$column ,true);
					if(is_array($value)){
						echo implode(',',$value);
					}else{
						echo $value;
					}
				}else{
					echo "";
				}
			
			/* Just break out of the switch statement for everything else. */
			default :
				break;
		}		
		
	}
	/* EOF - FETCH DATA IN BACK END */
endif;

/* 
NAME : ADD THE CATEGORY PRICE
ARGUMENTS : TAXONOMY NAME
DESCRIPTION : THIS FUNCTIONS IS USED TO ADD THE PRICE FIELD IN CATEGORY
*/
function category_custom_fields_AddField($tax)
{
	add_category_price_field($tax,'add');
}
/* EOF - ADD CATEGORY PRICE */

/* NAME : FUNCTION TO ADD/EDIT CATEGORY PRICE FIELD
ARGUMENTS : TAXONOMY NAME, OPERATION
DESCRIPTION : THIS FUNCTION ADDS/EDITS THE CATEGORY PRICE FIELD IN BACK END */
function add_category_price_field($tax,$screen)
{
	if((isset($tax->taxonomy) && $tax->taxonomy != '') || (isset($tax->term_price) && $tax->term_price != ''))
	{
		$taxonomy = $tax->taxonomy;
		$term_price = $tax->term_price;
	}
		$currency_symbol = get_option('currency_symbol');			
		?>
			<tr class="form-field">
				<th scope="row" valign="top"><label for="cat_price"><?php _e("Category Price", DOMAIN); echo ' ('.$currency_symbol.')'?></label></th>
				<td><input type="text"  name="cat_price" id="category_price" value="<?php if(isset($term_price) && $term_price != '') { echo $term_price; } ?>"  size="20"/>
				<p class="description"><?php _e('Here you can set the category price for a particular category',DOMAIN);?>.</p>
				</td>
			</tr>

	<?php
}
/* EOF - ADD/EDIT CATEGORY PRICE FIELD */

/* NAME : EDIT THE CATEGORY PRICE
ARGUMENTS : TAXONOMY NAME
DESCRIPTION : THIS FUNCTIONS IS USED TO EDIT THE PRICE FIELD IN CATEGORY */
function category_custom_fields_Edit($tax)
{
	add_category_price_field($tax,'edit');	
}
/* EOF - EDIT CATEGORY PRICE */

/* NAME : EDIT THE CATEGORY PRICE
ARGUMENTS : TERM ID
DESCRIPTION : THIS FUNCTIONS IS USED TO EDIT THE PRICE FIELD IN CATEGORY */
function category_custom_fields_AlterField($termId)
{
	global $wpdb;
	$term_table = $wpdb->prefix."terms";	
	$cat_price = $_POST['cat_price'];
	if($cat_price == '')
	{
		$cat_price = 0;
	}
	if($cat_price != '' || $cat_price == 0)
	{
		$sql = "update $term_table set term_price=".$cat_price." where term_id=".$termId;
		$wpdb->query($sql);
	}
}
/* EOF - EDIT CATEGORY PRICE */

/* NAME : ADD PRICE COLUMN IN TERMS TABLE
ARGUMENTS : COLUMN NAME
DESCRIPTION : THIS FUNCTION ADDS A COLUMN IN CATEGORY TABLE */
function edit_price_cat_column($columns)
{
	$args = get_option('templatic_custom_post');
	foreach($args as $key => $val)
	{
		$taxonomy = $val['label'];
		$posts = $val['labels']['name'];
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'name' => __('Name'),
			'price' =>  __('Price'),
			'description' => __('Description'),
			'slug' => __('Slug'),
			'posts' => __('Posts')
			);
	}
	return $columns;
}

/* Quick edit code start */
add_action('quick_edit_custom_box', 'category_price_show', 10, 2);
function category_price_show( $col, $type) {
    if( $type == 'event' ) return;
    
    switch ( $col ) {
         case 'price':?>
<fieldset class="inline-edit-col-left">
    <div class="inline-edit-group">
         <label for="category_price">
         <span class="title"><?php _e('Price',DOAMIN); ?></span>
         <span class="input-text-wrap">
	         <input id="category_price" type="text" name="cat_price" value="" size="10" />
         </span>
         </label>
    </div>
</fieldset>
<script type="text/javascript">
jQuery(document).ready(function(){  
    jQuery('.editinline').live('click', function(){
        var tag_id = jQuery(this).parents('tr').attr('id');
        var cat_price = jQuery('.price', '#'+tag_id).text().substr(1);
        jQuery(':input[name="cat_price"]', '.inline-edit-row').val(cat_price);
        return false;  
    });  
});
</script>
 
<?php 
	break;
    }
}

/* Quick edit code end */

/* EOF - ADD COLUMN */
	
/* NAME : DISPLAY PRICE COLUMN IN TERMS TABLE
ARGUMENTS : COLUMN NAME, OUTPUT, CATEGORU ID
DESCRIPTION : THIS FUNCTION DISPLAYS PRICE IN CATEGORY TABLE */
function tmpl_manage_price_cat_col($out, $column_name, $cat_id)
{
	$args = get_option('templatic_custom_taxonomy');
	foreach($args as $key => $val)
	{
		$taxonomy = $val['labels']['singular_name'];
		$term = get_term($cat_id, $taxonomy);
		switch ($column_name)
		{
			case 'price':	
				$currency_symbol = get_option('currency_symbol');			
				$symbol_position = get_option('currency_pos');
				$amount = isset($term->term_price) ? $term->term_price : 0;
				$price = fetch_currency_with_position($amount);
				$out .= $price;
			break;
		}
	}
	return $out;	
}
/* EOF - DISPLAY PRICE */

if(is_active_addons('custom_taxonomy')) 
{
	/*
	 * Add Filter for create the general setting sub tab for email setting
	 */
	add_filter('templatic_general_settings_subtabs', 'email_setting',13); 
	function email_setting($sub_tabs ) {
		
		$sub_tabs['email']='Email Settings';					
		return $sub_tabs;
	}	
	/*
	 * Create email setting data action
	 */
	add_action('templatic_general_setting_data','taxonomy_email_setting_data',12);
	function taxonomy_email_setting_data($column)
	{	
		$tmpdata = get_option('templatic_settings');		
		switch($column)
		{
			case 'email':						
				?>
				<tr>
					<td>					
						<h3><?php _e('Email Content Settings',DOMAIN); ?></h3>
						<p class="description"><?php _e('Notification e-mails are sent to administrators and users while relevant messages are displayed on the site at different times such as when a new user registers or a payment process completes successfully. You may customize these emails and messages here.',DOMAIN); ?></p>
						<table style="width:60%"  class="widefat post">
						<thead>
							<tr>
								<th>
								<label for="email_type" class="form-textfield-label"><?php _e('Email Type',DOMAIN); ?></label>
								</th>
								<th>
								<label for="email_sub" class="form-textfield-label"><?php _e('Email Subject',DOMAIN); ?></label>
								</th>
								<th>
								<label for="email_desc" class="form-textfield-label"><?php _e('Email Description',DOMAIN); ?></label>
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
								<label for="package_type" class="form-textfield-label"><?php _e('Successful Post submission to Admin',DOMAIN); ?></label>
								</td>
								<td>
								<textarea name="post_submited_success_email_subject" style="width:350px; height:100px;"><?php _e('Post submitted successfully Acknowledgment',DOMAIN); ?></textarea>
								</td>
								<td>
								<textarea name="post_submited_success_email_content" style="width:350px; height:100px;"><p>Dear [#to_name#],</p><p>Following information have been submitted. This email is just for your knowledge.</p><p>[#information_details#]</p><br><p>We hope you enjoy. Thanks!</p><p>[#site_name#]</p></textarea>
								</td>
							</tr>
							<tr>
								<td>
								<label class="form-textfield-label"><?php _e('Payment success email to client',DOMAIN); ?></label>
								</td>
								<td>
								<textarea name="payment_success_email_subject_to_client" style="width:350px; height:100px;"><?php _e('Acknowledgment for your Payment',DOMAIN); ?></textarea>
								</td>
								<td>
								<textarea name="payment_success_email_content_to_client" style="width:350px; height:100px;"><p>Dear [#to_name#],</p><p>[#transaction_details#]</p><br><p>We hope you enjoy. Thanks!</p><p>[#site_name#]</p></textarea>
								</td>
							</tr>
							<tr>
								<td>
								<label class="form-textfield-label"><?php _e('Payment success to Admin ',DOMAIN); ?></label>
								</td>
								<td>
								<textarea name="payment_success_email_subject_to_admin" style="width:350px; height:100px;"><?php _e('Payment received successfully',DOMAIN); ?></textarea>
								</td>
								<td>
								<textarea name="payment_success_email_content_to_admin" style="width:350px; height:100px;"><p>Dear [#to_name#],</p><p>[#transaction_details#]</p><br><p>We hope you enjoy . Thanks!</p><p>[#site_name#]</p></textarea>
								</td>
							</tr>
						</tbody>
						</table>
                        </td>
					</tr>
					
					<tr>
					<td>
                   
						<h3><?php _e('Notification Content Settings',DOMAIN); ?></h3>
						<table style="width:60%"  class="widefat post">
						<thead>
							<tr>
								<th>
								<label for="notification_title" class="form-textfield-label"><?php _e('Notification Title',DOMAIN); ?></label>
								</th>
								<th>
								<label for="msg_desc" class="form-textfield-label"><?php _e('Messege Description',DOMAIN); ?></label>
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
								<label class="form-textfield-label"><?php _e('Successful post submission notification',DOMAIN); ?></label>
								</td>
								<td>
								<textarea name="post_added_success_msg_content" style="width:650px; height:100px;"><p>Thank you, your information has been successfully received.</p><p><a href="[#submited_information_link#]" >View your submitted information</a></p><p>Thank you for visiting us at [#site_name#].</p></textarea>
								</td>
							</tr>
							<tr>
								<td>
								<label class="form-textfield-label"><?php _e('Payment successful notification',DOMAIN); ?></label>
								</td>
								<td>
								<textarea name="post_payment_success_msg_content" style="width:650px; height:100px;"><h4>Your payment received successfully and your information is published.</h4><p><a href="[#submited_information_link#]" >View your submitted information</a></p><h5>Thank you for becoming a member at [#site_name#].</h5></textarea>
								</td>
							</tr>
							<tr>
								<td>
								<label class="form-textfield-label"><?php _e('Payment canceled notification',DOMAIN); ?></label>
								</td>
								<td>
								<textarea name="post_payment_cancel_msg_content" style="width:650px; height:100px;"><h3>Your listing is cancelled. Sorry for cancellation.</h3><h5>Thank you for visiting us at [#site_name#].</h5></textarea>
								</td>
							</tr>
							<tr>
								<td>
								<label class="form-textfield-label"><?php _e('Payment via bank transfer success notification',DOMAIN); ?></label>
								</td>
								<td>
								<textarea name="post_pre_bank_trasfer_msg_content" style="width:650px; height:120px;"><p>Thank you, your request has been received successfully.</p><p>To publish the event please transfer the amount of <b>[#payable_amt#] </b> at our bank with the following information :</p><p>Bank Name : [#bank_name#]</p><p>Account Number : [#account_number#]</p><br><p>Please include the ID as reference :#[#submition_Id#]</p><p><a href="[#submited_information_link#]" >View your submitted listing</a><br/><p>Thank you for visit at [#site_name#].</p></textarea>
								</td>
							</tr>
						</tbody>
						</table>
                        </td>
					</tr>
				<?php					
				break;
		}
	}
	/*Finish the email setting data do action */
}
?>