<?php 
$activated = @$_REQUEST['activated'];
$deactivate = @$_REQUEST['deactivate'];
if($activated){
templatic_module_activationmsg('bulk_upload','Bulk Upload','',$mod_message='Now move on to the <a href='.admin_url('/admin.php?page=bulk_upload').'>Bulk upload</a> section to import/export .csv files.',$realted_mod =''); 
}else{
templatic_module_activationmsg('bulk_upload','Bulk Upload','',$mod_message='',$realted_mod =''); 
}?>
<div id="templatic_bulkupload" class="postbox widget_div">
	<div title="Click to toggle" class="handlediv"></div>
	<h3 class="hndle"><span><?php _e('Bulk Import / Export',DOMAIN); ?></span></h3>
	<div class="inside">
		<img class="dashboard_img" id="bulk_image" src = "<?php echo TEMPL_PLUGIN_URL.'tmplconnector/monetize/images/bulk.png'; ?>" />
		<?php
		_e('Use this feature to import .csv content from other sites. If the theme you&lsquo;re using doesn&lsquo;t support .csv exports connect to your database and export wp_posts table content into a .csv file.<br/><strong>NOTE :</strong>This feature works for the default wordpress blog posts as well as for all the other custom taxonomies you add in your site.',DOMAIN);
		?>
		<div class="clearfixb"></div>
		
		<?php if(!is_active_addons('bulk_upload')) { ?>

		<div id="publishing-action">
		<a href="<?php echo site_url()."/wp-admin/admin.php?page=templatic_system_menu&activated=bulk_upload&true=1";?>" class="templatic-tooltip button-primary"><?php _e('Activate &rarr;',DOMAIN); ?>
		</a></div>
		<?php } 
		if (is_active_addons('bulk_upload')) : ?>
		<div class="settings_style">
		<a href="<?php echo site_url()."/wp-admin/admin.php?page=templatic_system_menu&deactivate=bulk_upload&true=0";?>" class="deactive_lnk"><?php _e('Deactivate ',DOMAIN); ?></a> |
		<a class="templatic-tooltip set_lnk" href="<?php echo site_url()."/wp-admin/admin.php?page=bulk_upload";?>"><?php _e('Settings',DOMAIN); ?></a>
		</div><?php endif; ?>
	</div>
</div>