<?php
/*	
*	Code to check whether some changes done in Plugin's page template files or not.
*	Two parameter required to check file content compatision for function
*	If Change found in two files then function return 1 else it return 0.
*   If we leave both parameter then it will return 0.
*	START	
*/

function is_changes_in_page_template_file($original_file_path = NULL , $comparision_file_path = NULL){
	$original_file = $original_file_path;
	$comparision_file = $comparision_file_path;
	$updated_file_name = $updated_file_name;
	$flag = 0;
	if(is_plugin_active('Tevolution/templatic.php')){
		if(is_active_addons('custom_fields_templates') || is_active_addons('custom_taxonomy')){
			if(file_exists($original_file) && file_exists($comparision_file)){
				$plugins_file = file($original_file,  FILE_SKIP_EMPTY_LINES);
				$supreme_file = file($comparision_file,  FILE_SKIP_EMPTY_LINES);
				$count_plugin_file = count($plugins_file);
				$count_supreme_file = count($supreme_file);
				if(empty($plugins_file) && !empty($supreme_file)){
					$flag = 1;
				}elseif($count_plugin_file!=$count_supreme_file){
					$flag = 1;
				}elseif(!empty($plugins_file)){
					foreach($plugins_file as $file_lines){
						if(!in_array($file_lines,$supreme_file)){
							$flag = 1;
							break;
						}
					}
				}
			}
		}
	}
	return $flag;
}
/*	
*	Function Name: tevolution_delete_files
*	Used for: deleting files from supreme framework
*	Reqired parameter: filename to delete
*	Result: delete file if passed in parameter
*/
function tevolution_delete_files($filename = NULL){
	if(is_plugin_active('Tevolution/templatic.php')){
		if(is_active_addons('custom_fields_templates') || is_active_addons('custom_taxonomy')){
			if(file_exists($filename)){
				unlink($filename);
			}
		}
	}
}

/*	Call Function to check any changes in particular file START	 */

//Submit form page template Start
$submit_form_original_file = ABSPATH . 'wp-content/plugins/Tevolution/tmplconnector/monetize/templatic-custom_fields/page-template_form.php';
$submit_form_comparision_file = get_template_directory()."/page-template_form.php";
$is_submit_changes = is_changes_in_page_template_file($submit_form_original_file,$submit_form_comparision_file);


//Advanced search page template Start
$advanced_search_original_file = ABSPATH . 'wp-content/plugins/Tevolution/tmplconnector/monetize/templatic-custom_fields/page-template_advanced_search.php';
$advanced_search_comparision_file = get_template_directory()."/page-template_advanced_search.php";
$is_advanced_changes = is_changes_in_page_template_file($advanced_search_original_file,$advanced_search_comparision_file);


//Submit form validation file Start
$submission_validation_original_file = ABSPATH . 'wp-content/plugins/Tevolution/tmplconnector/monetize/templatic-custom_fields/submition_validation.php';
$submission_validation_comparision_file = get_template_directory()."/submition_validation.php";
$is_validation_changes = is_changes_in_page_template_file($submission_validation_original_file,$submission_validation_comparision_file);


//Map page template Start
$map_original_file = ABSPATH . 'wp-content/plugins/Tevolution/tmplconnector/monetize/templatic-custom_fields/page-template_map.php';
$map_comparision_file = get_template_directory()."/page-template_map.php";
$is_map_changes = is_changes_in_page_template_file($map_original_file,$map_comparision_file);


// single Post 
$update_custom_post = '';
$update_custom_post = get_option('templatic_custom_post');
$is_post_changes = array();
if($update_custom_post)
{
	foreach($update_custom_post as $key=>$value){
		$post_original_file = ABSPATH . "wp-content/plugins/Tevolution/tmplconnector/monetize/templatic-custom_taxonomy/single-post.php";
		$post_comparision_file = get_template_directory()."/single-$key.php";
		$is_post_changes[$key] = is_changes_in_page_template_file($post_original_file,$post_comparision_file);
	}
}
// Custom Taxonomies 
$update_taxonomy_category = get_option('templatic_custom_taxonomy');
$is_key_changes = array();
if($update_taxonomy_category)
{
	foreach($update_taxonomy_category as $key=>$value){
		$key_original_file = ABSPATH . "wp-content/plugins/Tevolution/tmplconnector/monetize/templatic-custom_taxonomy/taxonomy-category.php";
		$key_comparision_file = get_template_directory()."/taxonomy-$key.php";
		$is_key_changes[$key] = is_changes_in_page_template_file($key_original_file,$key_comparision_file);
	}
}
// Custom tags
$update_custom_tags = get_option('templatic_custom_tags');
$is_tags_changes = array();
if($update_custom_tags)
{
	foreach($update_custom_tags as $key=>$value){
		$tags_original_file = ABSPATH . "wp-content/plugins/Tevolution/tmplconnector/monetize/templatic-custom_taxonomy/taxonomy-tags.php";
		$tags_comparision_file = get_template_directory()."/taxonomy-$key.php";
		$is_tags_changes[$key] = is_changes_in_page_template_file($tags_original_file,$tags_comparision_file);
	}
}
/*	Call Function to check any changes in particular file END  */


//Delete files from supreme so it replaces with updated. START 
if($is_submit_changes==1){
	if(is_admin()){
		$filename = get_template_directory()."/page-template_form.php";
		tevolution_delete_files($filename);
	}
}
if($is_advanced_changes==1){
	if(is_admin()){
		$filename = get_template_directory()."/page-template_advanced_search.php";
		tevolution_delete_files($filename);
	}
}
if($is_validation_changes==1){
	if(is_admin()){
		$filename = get_template_directory()."/submition_validation.php";
		tevolution_delete_files($filename);
	}
}
if($is_map_changes==1){
	if(is_admin()){
		$filename = get_template_directory()."/page-template_map.php";
		tevolution_delete_files($filename);
	}
}
if(!empty($is_post_changes)){
	foreach($is_post_changes as $key => $values){
		if($values==1){
			$filename = get_template_directory()."/single-$key.php";
			tevolution_delete_files($filename);
		}
	}
}
if(!empty($is_key_changes)){
	foreach($is_key_changes as $key => $values){
		if($values==1){
			$filename = get_template_directory()."/taxonomy-$key.php";
			tevolution_delete_files($filename);
		}
	}
}
if(!empty($is_tags_changes)){
	foreach($is_tags_changes as $key => $values){
		if($values==1){
			$filename = get_template_directory()."/taxonomy-$key.php";
			tevolution_delete_files($filename);
		}
	}
}
//Delete files from supreme so it replaces with updated. END
/*	CLOSE	*/
?>