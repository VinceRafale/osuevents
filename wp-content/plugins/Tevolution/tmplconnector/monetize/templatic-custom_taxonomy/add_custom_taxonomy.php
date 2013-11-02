<?php
/* The custom Taxanomy will be created fom here */
?>
<div class="wrap">
	<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
	<h2><?php _e('Add New Post type',DOMAIN); ?><a id="back_cutom_taxonomy" class="add-new-h2" href="<?php echo site_url().'/wp-admin/admin.php?page=custom_taxonomy';?>" title="<?php _e('Back to Post type list',DOMAIN);?>" name="btnviewlisting"/><?php _e('Back to Post type list',DOMAIN); ?>
	</a></h2>

<?php
$edit_post = array();
if($_REQUEST['action'] == 'edit-type')
{
	$edit_post = get_option("templatic_custom_post");
	$_SESSION['taxonomy_post_type'] = $_REQUEST['post-type'];
}
?>
<script type="text/javascript" src="<?php echo TEMPL_PLUGIN_URL.'tmplconnector/monetize/templatic-custom_taxonomy/add_taxonomy_validations.js';?>"></script>
<form action="<?php echo site_url();?>/wp-admin/admin.php?page=custom_taxonomy&action=add_taxonomy" method="post" name="custom_taxonomy" onsubmit="return check_taxonomy_form();" >
    <table class="form-table" id="form_table_taxonomy" style="width:50%;">
        <thead>
            <tr>
                <th colspan="3"><p class="description"><?php _e('Fill out the necessary information to create a new post type and matching taxonomies. Be careful when entering slugs.',DOMAIN); ?><br/><strong><?php _e('Please insert the slugs carefully.',DOMAIN); ?></strong></p></th>
            </tr>
        </thead>
        <tbody>
            <tr class="" id="post_title">
            	<td valign="top">
                	<label for="post_name" class="form-textfield-label"><?php _e('Post Type Name'); ?> <span class="description">(<?php echo FLD_REQUIRED_TEXT; ?>)</span></label>
            	</td>
            	<td>
            		<input type="text" class="regular-text" value="<?php if($edit_post){ echo $edit_post[$_REQUEST['post-type']]['labels']['name']; } ?>" name="post_name" id="post_name" /><br/><p class="description"><?php _e('ex. Places, Events etc',DOMAIN); ?>.</p>
				</td>
            </tr>
            <tr class="" id="post_title_slug">
            	<td valign="top">
                	<label for="post_slug" class="form-textfield-label"><?php _e('Post Type Slug'); ?> <span class="description">(<?php echo FLD_REQUIRED_TEXT; ?>)</span></label>
            	</td>
                <td>
            		<input type="text" class="regular-text" <?php if($edit_post) { ?>readonly="readonly" <?php } ?> value="<?php if($edit_post) { echo $edit_post[@$_REQUEST['post-type']]['rewrite']['slug']; } ?>" name="post_slug" id="post_slug" /><br/>
					<p class="description"><?php _e('ex. places, events etc',DOMAIN); ?>.<strong> <?php _e('Note',DOMAIN); ?> : </strong><?php _e('In small letters only. No white space is allowed.',DOMAIN); ?></p>
				</td>
            </tr>
            <tr class="" id="tax_name">
            	<td valign="top">
                	<label for="taxonomy_name" class="form-textfield-label"><?php _e('Custom Taxonomy name',DOMAIN); ?> <span class="description">(<?php echo FLD_REQUIRED_TEXT; ?>)</span></label>
            	</td>
            	<td>
            		<input type="text" class="regular-text" value="<?php if($edit_post) { echo $edit_post[@$_REQUEST['post-type']]['taxonomies'][0]; } ?>" name="taxonomy_name" id="taxonomy_name" /><br/>
					<p class="description">
					<?php _e('ex. Categories, Event Categories etc',DOMAIN); ?>.
				</p></td>
            </tr>
            <tr class="" id="tax_slug">
            	<td valign="top">
                	<label for="taxonomy_slug" class="textfield-label"><?php _e('Taxonomy Slug '); ?> <span class="description">(<?php echo FLD_REQUIRED_TEXT; ?>)</span></label>
            	</td>
                <td>
            		<input type="text" class="regular-text"  <?php if($edit_post) { ?>readonly="readonly" <?php } ?> value="<?php if($edit_post) { echo $edit_post[@$_REQUEST['post-type']]['slugs'][0]; } ?>" name="taxonomy_slug" id="taxonomy_slug">
                <br/><p class="description">
				<?php _e('ex. eventcategories, placecategories etc',DOMAIN); ?>.<strong> <?php _e('Note',DOMAIN); ?> : </strong><?php _e('In small letters only. No white space is allowed.',DOMAIN); ?>
				</p></td>
            </tr>
			<tr class="" id="tag_title">
            	<td valign="top">
                	<label for="tag_name" class="textfield-label"><?php _e('Custom Tag name'); ?> <span class="description">(<?php echo FLD_REQUIRED_TEXT; ?>)</span></label>
            	</td>
            	<td>
            		<input type="text" class="regular-text" value="<?php if($edit_post) { echo $edit_post[@$_REQUEST['post-type']]['taxonomies'][1]; } ?>" name="tag_name" id="tag_name">
                <br/><p class="description">
				<?php _e('ex. Tags, Events Tags etc',DOMAIN); ?>.
				</p></td>
            </tr>
            <tr class="" id="tag_slug_name">
            	<td valign="top">
                	<label for="tag_slug" class="textfield-label"><?php _e('Tags Slug'); ?> <span class="description">(<?php echo FLD_REQUIRED_TEXT; ?>)</span></label>
            	</td>
                <td>
            		<input type="text" class="regular-text"  <?php if($edit_post) { ?>readonly="readonly" <?php } ?> value="<?php if($edit_post) { echo $edit_post[@$_REQUEST['post-type']]['slugs'][1]; } ?>" name="tag_slug" id="tag_slug">
                <br/><p class="description">
				<?php _e('ex. etags, placetags etc',DOMAIN); ?>.<strong> <?php _e('Note',DOMAIN); ?> : </strong><?php _e('In small letters only. No white space is allowed.',DOMAIN); ?>
				</p></td>
            </tr>
            <tr>
            	<td valign="top">
                	<label for="description" class="form-textarea-label"><?php _e('Description') ;?></label>
            	</td>
                <td>
            		<textarea class="form-textarea textarea" cols="40" rows="4" name="description" id="description"><?php if($edit_post) { echo $edit_post[@$_REQUEST['post-type']]['description']; } ?></textarea>
            	<br/><p class="description">
				<?php _e('In a few words, explain what this taxonomy is about.',DOMAIN); ?></p>
				</td>
            </tr>
            <tr>
                <td valign="top">
                    <label for="upload_image" class="form-textarea-label"><?php _e('Upload Icon') ;?></label>
                </td>
                <td>
                <input id="upload_image" type="text" size="36" name="upload_image" value="<?php if($edit_post) { echo $edit_post[@$_REQUEST['post-type']]['menu_icon']; } ?>" />
                <input id="upload_image_button" type="button" value="Upload Image" />
                <br /><p class="description"><?php _e('Enter the URL or upload an image of 16 x 16 for the taxonomy icon and you need to click on "Insert into post" button'); ?>.</p>
                </td>
			</tr>
        </tbody>
    </table>
    <br/>
    <table class="form-table" style="width:75%">
    <thead>
    	<tr>
    		<th colspan="3"><h2><div id="icon-edit" class="icon32 icon32-posts-post"><br></div><?php _e('Labels For Taxonomy Section',DOMAIN); ?></h2></th>
        </tr>
    </thead>
    <tbody>
    <tr>
    	<td valign="top">
            <label for="add_new" class="form-textfield-label"><?php _e('Add New text',DOMAIN); ?></label>
		</td>
        <td>
        	<input type="text" class="regular-text" value="<?php if($edit_post){ echo $edit_post[@$_REQUEST['post-type']]['labels']['add_new']; } else { echo 'Add New'; } ?>" name="add_new" id="add_new">
        <br/><p class="description"><?php _e('Will be display on Add new item links. By default it will display Add New for all the post types.',DOMAIN); ?></p></div>
		</td>
	 </tr>
     <tr>
     	<td valign="top">
        	<label for="add_new_item" class="form-textfield-label"><?php _e('Add New Item'); ?></label>
		</td>
        <td>
        	<input type="text" class="regular-text" value="<?php if($edit_post){ echo $edit_post[@$_REQUEST['post-type']]['labels']['add_new_item']; } else { echo 'Add New Item'; } ?>" name="add_new_item" id="add_new_item">
        <br/><p class="description"><?php _e('Will be display on the post detail page while adding a post. By default it will display New Item.'); ?></p></div>
		</td>
	 </tr>
     <tr>
     	<td valign="top">
        	<label for="edit_item" class="form-textfield-label"><?php _e('Edit Item text',DOMAIN); ?></label>
		</td>
        <td>
        	<input type="text" class="regular-text" value="<?php if($edit_post){ echo $edit_post[@$_REQUEST['post-type']]['labels']['edit_item']; } else { echo 'Edit Item'; } ?>" name="edit_item" id="edit_item">
        <br/><p class="description"><?php _e('Will be display on the post detail page while editing a post. By default it will display Edit Item.'); ?></p></div>
		</td>
	 </tr>
     <tr>
     	<td valign="top">
        	<label for="view_item" class="form-textfield-label"><?php _e('View Item button text',DOMAIN); ?></label>
		</td>
        <td>
        	<input type="text" class="regular-text" value="<?php if($edit_post){ echo $edit_post[@$_REQUEST['post-type']]['labels']['view_item']; } else { echo 'View Item'; } ?>" name="view_item" id="view_item">
        <br/><p class="description"><?php _e('Will be display on the post detail page after you submit the post. By default it will display View Item.'); ?></p></div>
		</td>
	</tr>
    <tr>
    	<td valign="top">
        	<label for="search_items" class="form-textfield-label"><?php _e('Search button text',DOMAIN); ?></label>
		</td>
        <td>
        	<input type="text" class="regular-text" value="<?php if($edit_post){ echo $edit_post[@$_REQUEST['post-type']]['labels']['search_items']; } else { echo 'Search Items'; } ?>" name="search_items" id="search_items">
        <br/><p class="description"><?php _e('Will be display on the Search button. By default it will display Search Items.'); ?></p></div>
		</td>
	</tr>
    <tr>
    	<td valign="top">
        	<label for="not_found" class="form-textfield-label"><?php _e('No item found text',DOMAIN); ?></label>
		</td>
        <td>
        	<input type="text" class="regular-text" value="<?php if($edit_post){ echo $edit_post[@$_REQUEST['post-type']]['labels']['not_found']; } else { echo 'No Item found'; } ?>" name="not_found" id="not_found">
        <br/><p class="description"><?php _e('Will be displayed when there is no item in Posts or Pages. By default it will display No posts found/No pages found.'); ?></p></div>
		</td>
	</tr>
    <tr>
    	<td valign="top">
        	<label for="not_found_in_trash" class="form-textfield-label"><?php _e('Not item found in Trash text',DOMAIN); ?></label>
		</td>
        <td>
        	<input type="text" class="regular-text" value="<?php if($edit_post){ echo $edit_post[@$_REQUEST['post-type']]['labels']['not_found_in_trash']; } else { echo 'No item found in Trash'; } ?>" name="not_found_in_trash" id="not_found_in_trash">
       <br/><p class="description"><?php _e('Will be displayed when there is no item in Trash. By default it will display No posts found in Trash/No pages found in Trash.'); ?></p></div>
		</td>
	</tr>
    </tbody>
    </table>
    <input type="submit" class="button-primary form-submit form-submit submit" value="Save Taxonomy" name="submit-taxonomy" id="submit-1">
</form>
</div>
<?php
if(isset($_POST['submit-taxonomy']) && $_POST['submit-taxonomy'] !='')
{
	$post_slug = $_POST['post_slug'];
	$taxonomy_slug = $_POST['taxonomy_slug'];
	$tag_slug = $_POST['tag_slug'];
	$location = site_url().'/wp-admin/admin.php';

	/* -- Register custom taxonomy  --- */
	$custom_tags = array();
	$custom_taxonomy = array();
	$cutom_post = array();
	$custom_post_type = $post_slug;
	$custom_cat_type = $taxonomy_slug;
	$custom_tag_type = $tag_slug;
	if($_POST['upload_image'])
	 {
		$upload_image = $_POST['upload_image'];
	 }
	else
	 {
		$upload_image = plugin_dir_url( __FILE__ ).'images/favicon.ico';
	 }

	if($post_slug)
	 {
		$cutom_post[$post_slug] =
					array(	'label' 			=> $_POST['post_name'],
							'labels' 			=> array(	'name' 					=> 	$_POST['post_name'],
															'singular_name' 		=> 	$_POST['post_name'],
															'add_new' 				=>  $_POST['add_new'],
															'add_new_item' 			=>  $_POST['add_new_item'],
															'edit_item' 			=>  $_POST['edit_item'],
															'new_item' 				=>  $_POST['new_item'],
															'view_item'				=>  $_POST['view_item'],
															'search_items' 			=>  $_POST['search_items'],
															'not_found' 			=>  $_POST['not_found'],
															'not_found_in_trash' 	=>  $_POST['not_found_in_trash']),
							'public' 			=> true,
							'can_export'		=> true,
							'show_ui' 			=> true, // UI in admin panel
							'_builtin' 			=> false, // It's a custom post type, not built in
							'_edit_link' 		=> 'post.php?post=%d',
							'capability_type' 	=> 'post',
							'menu_icon' 		=> $upload_image,
							'hierarchical' 		=> false,
							'rewrite' 			=> array("slug" => "$custom_post_type"), // Permalinks
							'query_var' 		=> "$custom_post_type", // This goes to the WP_Query schema
							'supports' 			=> array(	'title',
															'author', 
															'excerpt',
															'thumbnail',
															'comments',
															'editor', 
															'trackbacks',
															'custom-fields',
															'revisions') ,
							'show_in_nav_menus'	=> true ,
							'description' => $_POST['description'],
							'slugs'		=> array("$custom_cat_type","$custom_tag_type"),
							'taxonomies'		=> array($_POST['taxonomy_name'],$_POST['tag_name'])
						);
	 }
		global $wpdb;
		/* add this taxonomy in custom fields selection */
		$post_category_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE $wpdb->posts.post_title = 'Post Category' and $wpdb->posts.post_type = 'custom_fields'");
		update_post_meta($post_category_id, 'post_type_'.$post_slug.'' , 'all');

		
		$post_title_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE $wpdb->posts.post_title = 'Post Title' and $wpdb->posts.post_type = 'custom_fields'");
		update_post_meta($post_title_id, 'post_type_'.$post_slug.'' , 'all');
		
		
		$post_excerpt_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE $wpdb->posts.post_title = 'Post Excerpt' and $wpdb->posts.post_type = 'custom_fields'");
		update_post_meta($post_excerpt_id, 'post_type_'.$post_slug.'' , 'all');

		
		$post_image_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE $wpdb->posts.post_title = 'Post Images' and $wpdb->posts.post_type = 'custom_fields'");
		update_post_meta($post_image_id, 'post_type_'.$post_slug.'' , 'all');
		
		
		$post_content_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE $wpdb->posts.post_title = 'Post Content' and $wpdb->posts.post_type = 'custom_fields'");
		update_post_meta($post_content_id, 'post_type_'.$post_slug.'' , 'all');
		/* add this taxonomy in custom fields selection */
	// Register custom taxonomy
	if($taxonomy_slug)
	 {
		$custom_taxonomy[$taxonomy_slug] =	
					array (	"hierarchical" 		=> true, 
							"label" 			=> $_POST['taxonomy_name'],
							"post_type"			=> str_replace(" ","",strtolower($_POST['post_name'])),
							'labels' 			=> array(	'name' 				=>  $_POST['taxonomy_name'],
															'singular_name' 	=>  $_POST['taxonomy_slug'],
															'search_items' 		=>  CUSTOM_MENU_CAT_SEARCH,
															'popular_items' 	=>  CUSTOM_MENU_CAT_SEARCH,
															'all_items' 		=>  CUSTOM_MENU_CAT_ALL,
															'parent_item' 		=>  CUSTOM_MENU_CAT_PARENT,
															'parent_item_colon' =>  CUSTOM_MENU_CAT_PARENT_COL,
															'edit_item' 		=>  CUSTOM_MENU_CAT_EDIT,
															'update_item'		=>  CUSTOM_MENU_CAT_UPDATE,
															'add_new_item' 		=>  CUSTOM_MENU_CAT_ADDNEW,
															'new_item_name' 	=>  CUSTOM_MENU_CAT_NEW_NAME,	), 
							'public' 			=> true,
							'show_ui' 			=> true,
							"rewrite" 			=> true	);
	 }
	if($tag_slug)
	 {
		$custom_tags[$tag_slug] = 
					array(	"hierarchical" 		=> false,
							"label" 			=> $_POST['tag_name'],
							"post_type"			=> str_replace(" ","",strtolower($_POST['post_name'])),
							'labels' 			=> array(	'name' 				=>  $_POST['tag_name'],
															'singular_name' 	=>  $_POST['tag_slug'],
															'search_items' 		=>  CUSTOM_MENU_TAG_SEARCH,
															'popular_items' 	=>  CUSTOM_MENU_TAG_POPULAR,
															'all_items' 		=>  CUSTOM_MENU_TAG_ALL,
															'parent_item' 		=>  CUSTOM_MENU_TAG_PARENT,
															'parent_item_colon' =>  CUSTOM_MENU_TAG_PARENT_COL,
															'edit_item' 		=>  CUSTOM_MENU_TAG_EDIT,
															'update_item'		=>  CUSTOM_MENU_TAG_UPDATE,
															'add_new_item' 		=>  CUSTOM_MENU_TAG_ADD_NEW,
															'new_item_name' 	=>  CUSTOM_MENU_TAG_NEW_ADD,	),  
							'public' 			=> true,
							'show_ui' 			=> true,
							"rewrite" 			=> true	);
	 }

if(get_option('templatic_custom_post')):
	$original = array();
	if($_SESSION['taxonomy_post_type'])
	  {
		$original = get_option('templatic_custom_post');
		$tax_slug = $original[$_SESSION['taxonomy_post_type']]['slugs'][0];
		$tag_slug = $original[$_SESSION['taxonomy_post_type']]['slugs'][1];
		$or_arr = $cutom_post[$_POST['post_slug']];
		$cutom_post[$_POST['post_slug']] = array_replace($original[$_SESSION['taxonomy_post_type']],$or_arr);
		$post_arr_merge = $cutom_post;
		global $wpdb;
		$wpdb->query("Update $wpdb->posts SET post_type = '".str_replace(" ","",strtolower($_POST['post_name']))."' where post_type = '".str_replace(" ","",strtolower($original[$_SESSION['taxonomy_post_type']]['label']))."'");
		$wpdb->query("Update $wpdb->term_taxonomy SET taxonomy = '".$_POST['taxonomy_slug']."' where taxonomy = '".$tax_slug."'");
		$wpdb->query("Update $wpdb->term_taxonomy SET taxonomy = '".$_POST['tag_slug']."' where taxonomy = '".$tag_slug."'");
		unset($original[$_SESSION['taxonomy_post_type']]);
		if(empty($original))
		{
			$original = 1;
		}
	  }
	if($original)
	  {
		if($original == 1):
			$post_arr_merge = $cutom_post;
		else:	
			$post_arr_merge = array_merge($original,$cutom_post);
		endif;	
	  }
	else
	  {
		 $post_arr_merge = array_merge(get_option('templatic_custom_post'),$cutom_post);
	  }
else:
	$post_arr_merge = $cutom_post;
endif;

ksort($post_arr_merge);
update_option('templatic_custom_post',$post_arr_merge);

if(get_option('templatic_custom_taxonomy')):
	$original = array();
	if(isset($_SESSION['taxonomy_post_type']))
	  {
		$post_arr = get_option('templatic_custom_post');
		$original = get_option('templatic_custom_taxonomy');
		if($_POST['taxonomy_slug']):
			$or_arr = $custom_taxonomy[$_POST['taxonomy_slug']];
			$custom_taxonomy[$_POST['taxonomy_slug']] = array_replace($original[$tax_slug],$or_arr);
		endif;
		$taxonomy_arr_merge = $custom_taxonomy;
		unset($original[$tax_slug]);
		if(empty($original))
		{
			$original = 1;
		}
	  }
	if($original)
	  {
  		  if($original == 1):
			  $taxonomy_arr_merge = $custom_taxonomy;
		  else:
			  $taxonomy_arr_merge = array_merge($original,$custom_taxonomy);
		  endif;	  
	  }
	else
	  {
		  $taxonomy_arr_merge = array_merge(get_option('templatic_custom_taxonomy'),$custom_taxonomy);
	  }
else:
	$taxonomy_arr_merge = $custom_taxonomy;
endif;

ksort($taxonomy_arr_merge);
update_option('templatic_custom_taxonomy',$taxonomy_arr_merge);

if(get_option('templatic_custom_tags')):
	$original = array();
	if(isset($_SESSION['taxonomy_post_type']))
	  {
		$post_arr = get_option('templatic_custom_post');
		$original = get_option('templatic_custom_tags');
		if($_POST['tag_slug']):
			$or_arr = $custom_tags[$_POST['tag_slug']];
			$custom_tags[$_POST['tag_slug']] = array_replace($original[$tag_slug],$or_arr);
		endif;	
		$tags_arr_merge = $custom_tags;
		unset($original[$tag_slug]);
		if(empty($original))
		{
			$original = 1;
		}
	  }
	if($original)
	  {
  		  if($original == 1):
		  		$tags_arr_merge = $custom_tags;
		  else:
		  		$tags_arr_merge = array_merge($original,$custom_tags);
		  endif;		
	  }
	else
	  {
		  $tags_arr_merge = array_merge(get_option('templatic_custom_tags'),$custom_tags);
	  }
else:
	$tags_arr_merge = $custom_tags;
endif;

ksort($tags_arr_merge);
update_option('templatic_custom_tags',$tags_arr_merge);
?>	
	<form action="<?php echo $location; ?>" method="get" id="frm_edit_custom_taxonomy" name="frm_edit_custom_taxonomy">
        <input type="hidden" value="custom_taxonomy" name="page" />
        <?php $_SESSION['custom_msg_type'] = 'add'; ?>
    </form>
	<script>document.frm_edit_custom_taxonomy.submit();</script>';
<?php
	exit;	
}
?>