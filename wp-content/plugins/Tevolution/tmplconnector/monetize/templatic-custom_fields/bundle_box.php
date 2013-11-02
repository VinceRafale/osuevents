<?php 
$activated = @$_REQUEST['activated'];
$deactivate = @$_REQUEST['deactivate'];
if($activated){
templatic_module_activationmsg('custom_fields_templates','Custom Fields Manager','',$mod_message='In order to use this feature, you can create the custom fields <a href='.admin_url('/admin.php?page=custom_fields').'>here</a>.',$realted_mod =''); 
}else{
templatic_module_activationmsg('custom_fields_templates','Custom Fields Manager','',$mod_message='',$realted_mod =''); 
}?>
<div id="templatic_customfields" class="postbox widget_div">
	<div title="Click to toggle" class="handlediv"><br></div>
	<h3 class="hndle"><span><?php _e('Custom Fields Manager',DOMAIN); ?></span></h3>
	<div class="inside">
	<img class="dashboard_img" id="custum_field" src = "<?php echo TEMPL_PLUGIN_URL.'tmplconnector/monetize/images/custum_field.png'; ?>" />
		<?php
		_e('With this module you can easily populate a submission form with fields. The feature also allows you to control on which pages will the submitted value show. Category-specific and post type-specific fields are also possible!',DOMAIN);

		?>
		<div class="clearfixb"></div>
		<?php if(!is_active_addons('custom_fields_templates')) { ?>
		<div id="publishing-action">
		<a href="<?php echo site_url()."/wp-admin/admin.php?page=templatic_system_menu&activated=custom_fields_templates&true=1";?>" class="button-primary"><?php _e('Activate &rarr;',DOMAIN); ?></a></div>
		<?php } 
		if (is_active_addons('custom_fields_templates')) : ?>
		<div class="settings_style">
		<a href="<?php echo site_url()."/wp-admin/admin.php?page=templatic_system_menu&deactivate=custom_fields_templates&true=0";?>" class="deactive_lnk"><?php _e('Deactivate ',DOMAIN); ?></a> |
		<a id="custom_fields_setting" class="templatic-tooltip set_lnk"  href="<?php echo site_url()."/wp-admin/admin.php?page=templatic_settings&eco_system_custom_fields_tour_step=1#custom_fields_setting";?>"><?php _e('Settings',DOMAIN); ?></a>
		<?php 
			global $wpdb;
			$user_meta_table = $wpdb->prefix."usermeta";
			$chk_tour = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
			$flag=0;
			if ( in_array( 'templatic_ecosystem_plugin_custom_fields_install', $chk_tour )){ 
				$flag=1;
			}else{
				$flag=0;
			}
			if($flag==0){
		?>
				| <a class="templatic-tooltip set_lnk"  href="<?php echo site_url().'/wp-admin/admin.php?page=templatic_settings&start=templatic_ecosystem_plugin_custom_fields_install&eco_system_custom_fields_tour_step=1';?>"><?php _e('Start tour',DOMAIN); ?></a>
		<?php }?>
		

		</div>
		<?php endif; ?>
	</div>
</div>