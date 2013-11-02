<?php
if(isset($_REQUEST['activated']) || isset($_REQUEST['deactivate']))
{
	$activated = $_REQUEST['activated'];
	$deactivate = $_REQUEST['deactivate'];
	if($activated)
	{
		templatic_module_activationmsg('custom_taxonomy','Custom Post Types Manager','',$mod_message='You can use this feature by inserting some post types from <a href='.admin_url('/admin.php?page=custom_taxonomy').'><strong>here</strong></a>.',$realted_mod =''); 
	}
	else
	{
		templatic_module_activationmsg('custom_taxonomy','Custom Post Types Manager','',$mod_message='',$realted_mod =''); 
	}
}?>
<div id="templatic_posttype" class="postbox widget_div">
	<div title="Click to toggle" class="handlediv"><br></div>
	<h3 class="hndle"><span><?php _e('Custom Post Types Manager',DOMAIN); ?></span></h3>
	<div class="inside">
	<img class="dashboard_img" id="custom_post" src = "<?php echo TEMPL_PLUGIN_URL.'tmplconnector/monetize/images/custom_post.png'; ?>" />
		<?php
		_e('Creating a new custom post type is tricky unless if you&lsquo;re not very familiar with WordPress - this module solves that problem. With this manager you can easily create new post types and taxonomies. Every created post type / taxonomy will work flawlessly with custom fields and price packages.',DOMAIN);

		?>
		<div class="clearfixb"></div>
		<?php if(!is_active_addons('custom_taxonomy')) { ?>
		<div id="publishing-action">
		<a id="publishing_action_custom_taxonomy" href="<?php echo site_url()."/wp-admin/admin.php?page=templatic_system_menu&activated=custom_taxonomy&true=1";?>" class="button-primary"><?php _e('Activate &rarr;',DOMAIN); ?></a></div>
		<?php } 
		if (is_active_addons('custom_taxonomy')) : ?>
		<div class="settings_style">
			<a href="<?php echo site_url()."/wp-admin/admin.php?page=templatic_system_menu&deactivate=custom_taxonomy&true=0";?>" class="deactive_lnk"><?php _e('Deactivate ',DOMAIN); ?></a> |
			<a id="custom_taxonomy_setting" class="templatic-tooltip set_lnk" href="<?php echo site_url()."/wp-admin/admin.php?page=custom_taxonomy";?>"><?php _e('Settings',DOMAIN); ?></a> 
			<?php 
				global $wpdb;
				$user_meta_table = $wpdb->prefix."usermeta";
				$chk_tour = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
				$flag=0;
				if ( in_array( 'templatic_ecosystem_plugin_custom_taxonomy_install', $chk_tour )){ 
					$flag=1;
				}else{
					$flag=0;
				}
				if($flag==0){
			?>
			| <a  class="templatic-tooltip set_lnk" href="<?php echo site_url().'/wp-admin/admin.php?page=custom_taxonomy&eco_system_custom_taxonomy_tour_step=1#custom_taxonomy_setting';?>"><?php _e('Start tour',DOMAIN); ?></a>
			<?php }?>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php 
if(isset($_REQUEST['start']) && $_REQUEST['start']=="templatic_ecosystem_plugin_custom_taxonomy_install"){
	activate_single_tour($_REQUEST['start']);
}?>