<?php
global $wp_query,$wpdb,$wp_rewrite,$post;
/**
 * conditions for activation Templatic Widgets
 */
if(@$_REQUEST['activated'] == 'templatic_widgets' && @$_REQUEST['true']==1){
		update_option('templatic_widgets','Active');
}else if(@$_REQUEST['deactivate'] == 'templatic_widgets' && @$_REQUEST['true']==0){
		delete_option('templatic_widgets');
}

function get_templatic_widgets_list()
{
	$list_of_widgest=array(
			'templatic_listing_post'=>'Listing Post',
			'templatic_browse_by_categories'=>'Browse By Categories',
			'templatic_browse_by_tag'=>'Browse By Tag',
			'templatic_advertisements'=>'Advertisements',			
			'templatic_aboust_us'=>'About Us',
			'templatic_slider'=>'Slider',
			'templatic_facebook'=>'Facebook',
			'templatic_twiter'=>'Twitter',
			'templatic_popular_post'=>'Popular Post',
			'templatic_recent_review'=>'Recent Review',
		);
	return $list_of_widgest;	
}

if(file_exists(TEMPL_MONETIZE_FOLDER_PATH.'templatic-custom_fields/install.php')){
	
	$list_of_widgest=get_templatic_widgets_list();
	$tmpdata = get_option('templatic_settings');
	$templatic_widgets =  @$tmpdata['templatic_widgets'];	
	
	foreach($list_of_widgest as $key=>$value): 
		if(is_array($templatic_widgets) && $templatic_widgets !=''){
		if(in_array($key, $templatic_widgets))	:
			if(file_exists(TEMPL_MONETIZE_FOLDER_PATH.'templatic-widgets/'.$key.'_widget.php'))
			{				
				include (TEMPL_MONETIZE_FOLDER_PATH . "templatic-widgets/".$key."_widget.php");			
			}
		endif;
		}
	endforeach;
}
function templatic_exclude_taxonomies( $taxonomy ) {
	$filters = array( '', 'nav_menu' );
	$filters = apply_filters( 'templatic_exclude_taxonomies', $filters );

	return ( ! in_array( $taxonomy->name, $filters ) );

}


/*
 * Add Filter for create the general setting sub tab for widgest setting
 */
add_filter('templatic_general_settings_subtabs', 'widget_setting',14); 
function widget_setting($sub_tabs ) {
	
	$sub_tabs['widgets']='Templatic Widgets';					
	return $sub_tabs;
}

/*
 * Add Action for display widgets setting data in general setting tabs
 */
add_action('admin_head','widget_checked_all');
add_action('templatic_general_setting_data','widgets_setting_data');
function widgets_setting_data($column)
{	
	$list_of_widgest=get_templatic_widgets_list();
	$tmpdata = get_option('templatic_settings');
	$templatic_widgets =  @$tmpdata['templatic_widgets'];
	
	switch($column)
	{
		case 'widgets':						
			?>         
				<tr>
					<th colspan="2">
                    	<h2><?php _e('A wide variety of Templatic widgets.',DOMAIN);?></h2>
						<p class="description"><?php _e('Here is the list of templatic widgets. You just need to check mark each in order to use it on your site',DOMAIN); ?>.<br/><strong><?php _e('Note',DOMAIN); ?> :  </strong><?php _e('Widgets which are check marked here will only be display on widgets page',DOMAIN); ?>.</p>
					</th>
				</tr>              
				<tr>
					<th><label><?php _e('Enable Templatic Widgets',DOMAIN);?></label></th>
					<td>
					<?php
						$all_widgets_count = count($list_of_widgest);
						$checked_widgets_count = count($templatic_widgets);
					?>					
					<label><input type="checkbox" class="checkall" name="select_all" <?php if($checked_widgets_count == $all_widgets_count){echo $widget_checked = "checked=checked";}?> onclick="SelectAllCheckBoxes()" value="select_all" /> <?php _e("Select all");?></label><br/>
				<?php foreach($list_of_widgest as $key=>$value):?>	
					<label><input type="checkbox" name="templatic_widgets[]" class="templatic_widgets" value="<?php echo $key;?>"  <?php if(count($templatic_widgets) > 0 && in_array($key, $templatic_widgets)){ echo "checked=checked"; } ?>/> <?php echo $value;?></label><br/>
				<?php endforeach;?>
				
					</td>
				</tr>
			<?php					
			break;
	}
}
function widget_checked_all(){?>
	<script type="text/javascript">
		var $checkall = jQuery.noConflict();
		function SelectAllCheckBoxes()
		{
		$checkall('.templatic_settings').find(':checkbox').attr('checked', $checkall('.checkall').is(":checked"));
		}
	</script>
	<?php
}
?>