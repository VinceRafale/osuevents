<?php
/* Custom Taxonomies Lists */
?>
<div class="wrap">
	<?php if(isset($_SESSION['custom_msg_type']) && $_SESSION['custom_msg_type'] == 'add'):?>
	<div class="message updated"><p><?php _e('Custom post type saved successfully , Sidebar area for this taxonomy (Listing page , Detail page + Add listing page) has been created in <strong><a href="'.site_url('/wp-admin/widgets.php').'">Widgets</a></strong> area.',DOMAIN); ?></p></div>
	<?php endif; ?>
	<?php if(isset($_SESSION['custom_msg_type']) && $_SESSION['custom_msg_type'] == 'delete'):?>
	<div class="message updated"><p><?php _e('Custom post type deleted successfully',DOMAIN); ?></p></div>
	<?php endif; ?>    
	<div id="icon-edit" class="icon32 icon32-posts-post"><br/></div>
	<h2>
	<?php _e("Custom Post types",DOMAIN); ?>
	<a class="add-new-h2" id="add_custom_taxonomy" href="<?php echo admin_url("admin.php?page=custom_taxonomy&action=add_taxonomy"); ?>"><?php _e('Add Custom Post type'); ?></a>
	</h2>
	<p class="description"><?php _e('This is another classic feature from Templatic. This will enable you to create your own post types for your site. You can create multiple post types.',DOMAIN); ?></p>
</div>
<form name="all_custom_post_types" id="posts-filter" action="<?php echo admin_url("admin.php?page=custom_taxonomy"); ?>" method="post" >
	<?php
	$templ_list_table = new taxonmy_list_table();
	$templ_list_table->prepare_items();
	$templ_list_table->display();
	?>
</form>
<?php unset($_SESSION['custom_msg_type']);unset($_SESSION['taxonomy_post_type']); ?>