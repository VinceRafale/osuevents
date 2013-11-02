<?php 
$activated = @$_REQUEST['activated'];
$deactivate = @$_REQUEST['deactivate'];
if($activated){
templatic_module_activationmsg('manage_ip','Security Manager','',$mod_message='You can set up the IPs straight away from <a href='.admin_url('/admin.php?page=templatic_settings&tab=security-settings').'><strong>here</strong></a> to block them on your site. Or You can block the IPs from inside the post. You will be able to see a meta box on Add / Edit post page. You can block the user from there as well.',$realted_mod =''); 
}else{
templatic_module_activationmsg('manage_ip','Security Manager','',$mod_message='',$realted_mod =''); 
}?>
<div id="templatic_manage_ip_module" class="postbox widget_div">
	<div title="Click to toggle" class="handlediv"><br></div>
	<h3 class="hndle"><span><?php _e('Security Manager',DOMAIN); ?></span></h3>
	<div class="inside">
		<img class="dashboard_img" id="security_manager" src = "<?php echo TEMPL_PLUGIN_URL.'tmplconnector/monetize/images/security_manager.png'; ?>" />
		<?php
		_e('Another classic feature from templatic. This feature will lead to you help with security related issues.You can block some users by just mentioning their IP addresses in the settings.<br/><strong>NOTE : </strong>The user whose IP is blocked, will not be able to post anything on your site.',DOMAIN);
		?>

		<div class="clearfixb"></div>
		<?php if(!is_active_addons('manage_ip')) { update_option('manage_ip_enabled','No'); ?>
		<div id="publishing-action">
		<a href="<?php echo site_url()."/wp-admin/admin.php?page=templatic_system_menu&activated=manage_ip&true=1";?>" class="button-primary"><?php _e('Activate &rarr;',DOMAIN); ?></a></div>
		<?php } 
		if (is_active_addons('manage_ip')) : ?>
		<div class="settings_style">
		<a href="<?php echo site_url()."/wp-admin/admin.php?page=templatic_system_menu&deactivate=manage_ip&true=0";?>" class="deactive_lnk"><?php _e('Deactivate',DOMAIN); ?></a> |
				
		<a class="templatic-tooltip set_lnk" href="<?php echo site_url()."/wp-admin/admin.php?page=templatic_settings&tab=security-settings";?>" ><?php _e('Settings',DOMAIN); ?></a>
		</div>
		<?php endif; ?>
	</div>
</div>