<?php
/* Custom Fields Listing page */
if(@$_REQUEST['pagetype']=='delete')
{
	$postid = $_REQUEST['field_id'];
	wp_delete_post($postid);
	$url = site_url().'/wp-admin/admin.php';
	echo '<form action="'.$url.'" method="get" id="frm_custom_field" name="frm_custom_field">
	<input type="hidden" value="custom_fields" name="page"><input type="hidden" value="delsuccess" name="custom_field_msg">
	</form>
	<script>document.frm_custom_field.submit();</script>
	';exit;	
}
?>
<div class="wrap">
	<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
	<h2><?php _e('Manage custom fields',DOMAIN);?> 
	<a id="add_custom_fields" class="add-new-h2" href="<?php echo site_url().'/wp-admin/admin.php?page=custom_fields&action=addnew';?>" title="<?php _e('Add custom field',DOMAIN);?>" name="btnviewlisting"/><?php _e('Add a  custom field',DOMAIN); ?>
	</a></h2>
    
    <p class="description"><?php _e('This section will allow you to create new fields for your submission forms. On top of creating fields for the submission form you also control where they show in the front-end (in which pages). Each field you create is both category and post-type specific.',DOMAIN);?><strong> <?php _e('Note',DOMAIN); ?> : </strong><?php _e('Post category, Post Title, Post Content, Post Excerpt and Post Image fields are required for the submission form to work correctly. Do not delete them.',DOMAIN); ?></p>

	<?php if(isset($_REQUEST['custom_field_msg']))
	{?>
		<div class="updated fade below-h2" id="message" style="padding:5px; font-size:12px;" >
			<?php if($_REQUEST['custom_field_msg']=='delsuccess'){
					_e('Custom field deleted successfully.',DOMAIN);	
				} if($_REQUEST['custom_field_msg']=='success'){
					if($_REQUEST['custom_msg_type']=='add') {
						_e('Custom field created successfully.',DOMAIN);
					} else {
						_e('Custom field updated successfully.',DOMAIN);
					}
				}
			?>
		</div>
	<?php } ?>
	<form name="post_custom_fields" id="post_custom_fields" action="" method="post">
		<?php
			$custom_fields_list_table = new custom_fields_list_table();
			$custom_fields_list_table->prepare_items();
			$custom_fields_list_table->search_box('search', 'search_field');
			$custom_fields_list_table->display();
		?>
	</form>
</div>