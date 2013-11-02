<?php
class Templatic{
	var $file;
	var $version;
}
class Templatic_connector { 

	/*
	Name : templ_dashboard_bundles
	Description : Function contains bundles of file which creates the bunch of options in backend BOF 
	*/
	public function templ_dashboard_bundles(){
		
		require_once(TEMPL_MONETIZE_FOLDER_PATH.'templ_header_section.php' );
		$no_include = array('templatic-generalizaion','templ_header_section.php','general_settings.php','general_functions.php','templ_footer_section.php','images','.svn');
		echo '<div id="tevolution_bundled" class="metabox-holder wrapper widgets-holder-wrap">';
		if ($handle = opendir(TEMPL_MONETIZE_FOLDER_PATH)) {
		/* This is the correct way to loop over the directory. */
			while (false !== ($file = readdir($handle))) 
			{
			   if($file=='.' || $file=='..'){ }else
			   {				
					if(!in_array($file,$no_include)){ 
						if(is_file(TEMPL_MONETIZE_FOLDER_PATH.$file."/bundle_box.php"))
							require_once(TEMPL_MONETIZE_FOLDER_PATH.$file."/bundle_box.php" ); 
					}
			   }
			}
			closedir($handle);
		}
		
		/* to get t plugins */	
		do_action('templconnector_bundle_box');
		echo '<div>';
		require_once(TEMPL_MONETIZE_FOLDER_PATH.'templ_footer_section.php' );
	
	}
	/* -- Function contains bundles of file which creates the bunch of options in backend EOF - */
	
	/*
	Name : bdw_get_images_with_info
	Description :Return the images of post with attachment information
	*/
	function bdw_get_images_with_info($iPostID,$img_size='thumb') 
	{
    $arrImages =& get_children('order=ASC&orderby=menu_order ID&post_type=attachment&post_mime_type=image&post_parent=' . $iPostID );
	$return_arr = array();
	if($arrImages) 
	{		
       foreach($arrImages as $key=>$val)
	   {
	   		$id = $val->ID;
			if($img_size == 'large')
			{
				$img_arr = wp_get_attachment_image_src($id,'full');	// THE FULL SIZE IMAGE INSTEAD
				$imgarr['id'] = $id;
				$imgarr['file'] = $img_arr[0];
				$return_arr[] = $imgarr;
			}
			elseif($img_size == 'medium')
			{
				$img_arr = wp_get_attachment_image_src($id, 'medium'); //THE medium SIZE IMAGE INSTEAD
				$imgarr['id'] = $id;
				$imgarr['file'] = $img_arr[0];
				$return_arr[] = $imgarr;
			}
			elseif($img_size == 'thumb')
			{
				$img_arr = wp_get_attachment_image_src($id, 'thumbnail'); // Get the thumbnail url for the attachment
				$imgarr['id'] = $id;
				$imgarr['file'] = $img_arr[0];
				$return_arr[] = $imgarr;
				
			}
	   }
	  return $return_arr;
	}
	}
}
?>