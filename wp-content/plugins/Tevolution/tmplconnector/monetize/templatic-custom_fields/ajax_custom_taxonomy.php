<?php
$file = dirname(__FILE__);
$file = substr($file,0,stripos($file, "wp-content"));
require($file . "/wp-load.php");
?>
<ul>
<li>
    <input type="checkbox" name="selectall" id="selectall" class="checkbox" onclick="displaychk_frm();" />
    <label for="selectall">&nbsp;<?php _e('Select All',DOMAIN); ?></label>
</li>
<?php
if($_REQUEST['post_type'] == 'all' || $_REQUEST['post_type'] == 'all,')
{
	$custom_post_types_args = array();
	$custom_post_types = get_post_types($custom_post_types_args,'objects');
	foreach ($custom_post_types as $content_type) {
                    if($content_type->name!='nav_menu_item' && $content_type->name!='attachment' && $content_type->name!='revision' && $content_type->name!='page'){
						if($content_type->name == 'post')
						   {
								@get_wp_category_checklist_plugin('category','');
						   }
						else
						   {
							   @get_wp_category_checklist_plugin($content_type->slugs[0],'');
						   }
					}
	}
}
else
{
	$my_post_type = explode(",",substr($_REQUEST['post_type'],0,-1));
	foreach($my_post_type as $_my_post_type)
	{
		@get_wp_category_checklist_plugin($_my_post_type,'');
	}
}
?>
</ul>