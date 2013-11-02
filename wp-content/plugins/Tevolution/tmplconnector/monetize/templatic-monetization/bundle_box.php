<?php 
$activated = @$_REQUEST['activated'];
$deactivate = @$_REQUEST['deactivate'];
if($activated){
	$mod_message='Now move on to the <a href='.admin_url('/admin.php?page=monetization').'>Monetization</a> page where you can add different packages on your site.';
	if(!is_active_addons('custom_taxonomy'))
		$mod_message.='You need to activate <strong>Templatic Custom Post Types</strong> add-on in order to use this feature on your site.';
	
templatic_module_activationmsg('monetization','Monetization','',$mod_message,$realted_mod =''); 
}else{
templatic_module_activationmsg('monetization','Monetization','',$mod_message='',$realted_mod =''); 
}?>
<div id="templatic_monetization" class="postbox widget_div">
	<div title="Click to toggle" class="handlediv"><br></div>
	<h3 class="hndle"><span><?php _e('Monetization',DOMAIN); ?></span></h3>
	<div class="inside">
		<img class="dashboard_img" id="monetization" src = "<?php echo TEMPL_PLUGIN_URL.'tmplconnector/monetize/images/monetilazaion.png'; ?>" />
		<?php
		_e('Making money with WordPress is no easy task, that&lsquo;s why we created several features that will make that process easier. The Monetization module allows you to setup price packages and control the currency, coupons and payment gateways used on the site. Every price package is category and post type-specific for unparalleled flexibility. ',DOMAIN);
		?>
		<div class="clearfixb"></div>
		<?php if(is_active_addons('monetization'))
		{ ?>
		<div class="settings_style">
			<a href="<?php echo site_url()."/wp-admin/admin.php?page=templatic_system_menu&deactivate=monetization&true=0";?>" class="deactive_lnk"><?php _e('Deactivate',DOMAIN); ?></a> |
			<a id="WpEcoWorld_monetization" class="templatic-tooltip set_lnk" href="<?php echo site_url()."/wp-admin/admin.php?page=monetization";?>"><?php _e('Settings',DOMAIN); ?></a>
			<?php 
				global $wpdb;
				$user_meta_table = $wpdb->prefix."usermeta";
				$chk_tour = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
				$flag=0;
				if ( in_array( 'templatic_wpecoworld_plugin_monetization_install', $chk_tour )){ 
					$flag=1;
				}else{
					$flag=0;
				}
				if($flag==0){
			?>
			| <a class="templatic-tooltip set_lnk" href="<?php echo site_url().'/wp-admin/admin.php?page=monetization&WpEcoWorld_monetization_tour_step=1#WpEcoWorld_monetization';?>"><?php _e('Start tour',DOMAIN); ?></a>
			<?php }?>
		</div>
	<?php } else { ?>
		<div id="publishing-action">
		<a href="<?php echo site_url()."/wp-admin/admin.php?page=templatic_system_menu&activated=monetization&true=1";?>" class="button-primary"><?php _e('Activate &rarr;',DOMAIN); ?></a></div>
	<?php } ?>
	</div>
</div>
<?php 
if(isset($_REQUEST['start']) && $_REQUEST['start']=="templatic_wpecoworld_plugin_monetization_install"){
	activate_single_tour($_REQUEST['start']);
}?>