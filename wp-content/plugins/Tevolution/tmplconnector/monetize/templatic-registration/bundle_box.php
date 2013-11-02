<?php 
$activated = @$_REQUEST['activated'];
$deactivate = @$_REQUEST['deactivate'];
if($activated){
templatic_module_activationmsg('templatic-login','User registration/Login Management','',$mod_message='You can use this feature directly by setting up a widget T - Loginbox in your site. You can set up the widget <a href='.admin_url('/widgets.php').'><strong>here</strong></a>. On activation of this module, it will automatically add two user custom fields on your site. You can add more from <a href='.admin_url('/admin.php?page=user_custom_fields').'><strong>here</strong></a>.',$realted_mod =''); 
}else{
templatic_module_activationmsg('templatic-login','User registration/Login Management','',$mod_message='',$realted_mod =''); 
}?>
<div id="templatic_userreg" class="postbox widget_div">
	<div title="Click to toggle" class="handlediv"><br></div>
	<h3 class="hndle"><span><?php _e('User registration/Login Management',DOMAIN); ?></span></h3>
	<div class="inside">
		<?php
		_e('<img  id="user_sample_image" class="dashboard_img" src = "'.TEMPL_PLUGIN_URL.'tmplconnector/monetize/images/user_sample_image.png" />A classic feature form templatic. By using this feature, you will be able to enable the registration facility on your site. This will create a widget for you so that you can set up the login and registration from anywhere in your site. <br/>',DOMAIN);
		?>
		<div class="clearfix"></div>
		<?php if(!is_active_addons('templatic-login')) { ?>
		
		<div id="publishing-action">
		<a href="<?php echo site_url()."/wp-admin/admin.php?page=templatic_system_menu&activated=templatic-login&true=1";?>" class="button-primary"><?php _e('Activate &rarr;',DOMAIN); ?></a></div>
		<?php } 
		if (is_active_addons('templatic-login')) :  ?>
		<div class="settings_style">
		<a href="<?php echo site_url()."/wp-admin/admin.php?page=templatic_system_menu&deactivate=templatic-login&true=0";?>" class="deactive_lnk"><?php _e('Deactivate ',DOMAIN); ?></a> |
		<a id="WpEcoWorld_user_custom_fields" class="templatic-tooltip set_lnk" href="<?php echo site_url()."/wp-admin/admin.php?page=user_custom_fields";?>" ><?php _e('Settings',DOMAIN); ?></a>
		<?php 
			global $wpdb;
			$user_meta_table = $wpdb->prefix."usermeta";
			$chk_tour = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
			$flag=0;
			if ( in_array( 'templatic_weecoworld_plugin_user_registration_install', $chk_tour )){ 
				$flag=1;
			}else{
				$flag=0;
			}
			if($flag==0){
		?>
		| <a class="templatic-tooltip set_lnk" href="<?php echo site_url().'/wp-admin/admin.php?page=user_custom_fields&WpEcoWorld_user_custom_fields_tour_step=1#WpEcoWorld_user_custom_fields';?>"><?php _e('Start tour',DOMAIN); ?></a>
		<?php }?>
		</div><?php endif; ?>
	</div>
</div>
<?php 
if(isset($_REQUEST['start']) && $_REQUEST['start']=="templatic_weecoworld_plugin_user_registration_install"){
	activate_single_tour($_REQUEST['start']);
}?>