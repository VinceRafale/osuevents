<?php
global $wp_query,$wpdb;
/* Add action 'templatic_general_setting_data' for display rating*/
add_action('templatic_general_setting_data','rating_setting_data',10);
/*
 * Function Name: templatic_general_setting_data;
 * Argument: Column 
 */
function rating_setting_data($column)
{
	$tmpdata = get_option('templatic_settings');
	
	switch($column)
	{
		case 'listing' :
				?>
                <tr>
                	<td colspan="2"><?php _e('<h3>Rating Option</h3>',DOMAIN);?></td>
                </tr>
                <tr>
                	<th><?php _e('Rating Option',DOMAIN);?></th>
                    <td>
                    	<input type="radio" name="templatin_rating" value="yes" <?php if($tmpdata['templatin_rating']=='yes')echo 'checked';?> />&nbsp;<label for="rating_yes"><?php _e('Yes',DOMAIN);?></label><br />
                        <input type="radio" name="templatin_rating" value="no"  <?php if($tmpdata['templatin_rating']=='no')echo 'checked';?> />&nbsp;<label for="rating_no"> <?php _e('No',DOMAIN);?></label>
                    </td>
                </tr>
                <?php
			break;
	}
}
$tmpdata = get_option('templatic_settings');
if($tmpdata['templatin_rating']=='yes')
{
	if(file_exists(TEMPL_MONETIZE_FOLDER_PATH . 'templatic-ratings/templatic_post_rating.php'))
	{
		include_once (TEMPL_MONETIZE_FOLDER_PATH . 'templatic-ratings/templatic_post_rating.php');
	}
	if(file_exists(TEMPL_MONETIZE_FOLDER_PATH.'templatic-ratings/language.php'))
	{
		include (TEMPL_MONETIZE_FOLDER_PATH . "templatic-ratings/language.php");
	}
}
?>