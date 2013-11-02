<div class="wrap"><div class="icon32" id="icon-index"><br></div><h2><?php _e('Tevolution',DOMAIN); ?></h2>
<?php do_action('tevolution_plugin_list');?>
<div id="poststuff" class="metabox-holder wrapper">
	
	<div id="tevolution-panel" class="welcome-panel">  
		<div id="welcome" class="welcome-panel-content">
			<?php if(@$_REQUEST['activated'] == 'true' && @$_REQUEST['page'] == 'templatic_system_menu'){
			global $wpdb;
			
					
			echo "<div class='updated' style='margin-top:15px;'>";
			echo "<p>"; _e('Plugin <strong>activated successfully</strong>.',DOMAIN); 
			echo "</p>";
			echo "</div>";
			} 
			?>
				<h3 id="start_tour_eco_system"><?php _e('Welcome to the Tevolution dashboard !',DOMAIN); ?></h3>
			<p class="about-description tvolution_desc">
			<?php				
			_e('Tevolution is home of add-ons collection.Activate the add-on of your choice,do necessary settings by following some simple steps and you will be ready to use features in your site.',DOMAIN); ?>
			</p>
            	<?php _e('More help ? So Many resources are here to help you',DOMAIN); ?> <a class="tvolution_dash_link" href="<?php echo site_url().'/wp-admin/admin.php?page=templatic_system_menu&start=all'; ?>"><?php _e('Restart tour',DOMAIN); ?></a> | <a class="tvolution_dash_link" target="blank" href="http://templatic.com/docs/tevolution-guide/"><?php _e('Guide',DOMAIN); ?></a> | <a class="tvolution_dash_link" target="blank" href="http://templatic.com/forums/viewtopic.php?f=102&t=12652"><?php _e('Forum',DOMAIN); ?></a>
		</div>
	</div>

</div>
<?php 
/*	  To Restart all Templatic tour remove templatic pointers	from database and save wordpress default pointers START 	*/
if(isset($_REQUEST['start']) && $_REQUEST['start']=='all'){	
	global $wpdb;
	$templatic_tours = array('templatic_ecosystem_plugin_install','templatic_ecosystem_plugin_custom_taxonomy_install','templatic_wpecoworld_plugin_monetization_install','templatic_ecosystem_plugin_custom_fields_install','templatic_weecoworld_plugin_user_registration_install','templatic_wpecoworld_plugin_claim_install','templatic_ecosystem_plugin');
	$restart_tour = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
	$default_pointers = "";
	foreach($restart_tour as $tour){
		if(in_array($tour,$templatic_tours)){
		}else{
			$default_pointers .= $tour.',';
		}
	}
	$default_pointers = rtrim($default_pointers,',');
	update_user_meta(get_current_user_id(),'dismissed_wp_pointers',$default_pointers);
	wp_redirect(site_url().'/wp-admin/admin.php?page=templatic_system_menu');
}

/*   To Restart all Templatic tour remove templatic pointers from database and save wordpress default pointers END 	*/
?>