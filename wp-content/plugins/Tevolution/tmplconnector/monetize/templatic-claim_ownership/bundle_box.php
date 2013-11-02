<?php 
$activated = @$_REQUEST['activated'];
$deactivate = @$_REQUEST['deactivate'];
if($activated){
templatic_module_activationmsg('claim_ownership','Claim Ownership','',$mod_message='Now move on to the <a href='.admin_url('/admin.php?page=templatic_settings').'>General Settings</a> page to select the post types for your site. You need to set up the widget in order to show the <strong>Claim for this post</strong> link. You can set up the widget <a href='.admin_url('/widgets.php').'>here</a>.',$realted_mod =''); 
}else{
templatic_module_activationmsg('claim_ownership','Claim Ownership','',$mod_message='',$realted_mod =''); 
}?>
<div id="templatic_claimownership" class="postbox widget_div">
	<div title="Click to toggle" class="handlediv"><br></div>
	<h3 class="hndle"><span><?php _e('Claim Post Manager',DOMAIN); ?></span></h3>
	<div class="inside">
	<img class="dashboard_img" id="claim_image" src = "<?php echo TEMPL_PLUGIN_URL.'tmplconnector/monetize/images/claim.png'; ?>" />
		<?php
		_e('A tempting feature Claim Ownership from templatic. This feature enables the users to claim on your site and also generates a new user automatically if you verify the claim.<br/><strong>NOTE : </strong>You can set up the claim feature for Posts, Pages and for all the other custom taxonomies. By default it works for all the post types.',DOMAIN);
		?>

		<div class="clearfixb"></div>
		<?php if(!is_active_addons('claim_ownership')) { delete_option('claim_enabled'); ?>
		<div id="publishing-action">
		<a href="<?php echo site_url()."/wp-admin/admin.php?page=templatic_system_menu&activated=claim_ownership&true=1";?>" class="button-primary"><?php _e('Activate &rarr;',DOMAIN); ?></a></div>
		<?php } 
		if (is_active_addons('claim_ownership')) :  update_option('claim_enabled','No'); ?>
		<div class="settings_style">
			<a href="<?php echo site_url()."/wp-admin/admin.php?page=templatic_system_menu&deactivate=claim_ownership&true=0";?>" class="deactive_lnk"><?php _e('Deactivate',DOMAIN); ?></a> |
			<a class="templatic-tooltip set_lnk" id="WpEcoWorld_claim" href="<?php echo site_url()."/wp-admin/admin.php?page=templatic_settings";?>"><?php _e('Settings',DOMAIN); ?>
			</a>
			<?php 
				global $wpdb;
				$user_meta_table = $wpdb->prefix."usermeta";
				$chk_tour = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
				$flag=0;
				if ( in_array( 'templatic_wpecoworld_plugin_claim_install', $chk_tour )){ 
					$flag=1;
				}else{
					$flag=0;
				}
				if($flag==0){
			?>
			| <a class="templatic-tooltip set_lnk" style="padding-left:3px;" href="<?php echo site_url().'/wp-admin/admin.php?page=templatic_settings&tab=general&sub_tab=basic&WpEcoWorld_claim_tour_step=1#WpEcoWorld_claim';?>" title="<?php _e('Restart tour',DOMAIN); ?>"><?php _e('Start tour',DOMAIN); ?></a>
			<?php }?>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php 
if(isset($_REQUEST['start']) && $_REQUEST['start']=="templatic_wpecoworld_plugin_claim_install"){
	activate_single_tour($_REQUEST['start']);
}?>