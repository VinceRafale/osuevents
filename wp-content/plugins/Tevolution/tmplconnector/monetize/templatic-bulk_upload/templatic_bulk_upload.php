<?php 
	session_start();ini_set('set_time_limit', 0);ini_set('max_execution_time', 0);error_reporting(0);//ini_set("memory_limit",-1);
	$upload_size_unit = $max_upload_size = wp_max_upload_size();
	$sizes = array( 'KB', 'MB', 'GB' );		
	for ( $u = -1; $upload_size_unit > 1024 && $u < count( $sizes ) - 1; $u++ ) {
		$upload_size_unit /= 1024;
	}

	if ( $u < 0 ) {
		$upload_size_unit = 0;
		$u = 0;
	} else {
		$upload_size_unit = (int) $upload_size_unit;
	}
	
	$msg= "CSV file size large. Maximum allowed upload file size is ".esc_html($upload_size_unit)." ".esc_html($sizes[$u]);
?>
<script type="text/javascript" src="<?php echo plugins_url('Tevolution/js/ajaxupload.js');?>"></script>
<script type="text/javascript">	
		function chek_file()
		{
			jQuery.noConflict();
			
			var csv_import = jQuery('#csv_import').val();
			var my_post_type = jQuery('input[name=my_post_type]:checked', '#bukl_upload_frm').val();
			var ext = csv_import.split('.').pop().toLowerCase();
			if(csv_import == ""){
				jQuery('#csv_import_id').addClass('form-invalid');
				jQuery('#csv_import').focus();
				jQuery('#status').html('Please select csv file to import');
				return false;
			}else if(csv_import != "" && ext != "csv" ){
				jQuery('#csv_import_id').addClass('form-invalid');
				jQuery('#csv_import').focus();
				jQuery('#status').html('Upload csv files only');
				return false;
			}else if(!confirm('Would you like to import data in "'+my_post_type+'" post type ?')){
				return false;
			}else{
				var file_size = jQuery("#csv_import")[0].files[0].size;
				var allowed_file_size = <?php echo wp_max_upload_size()?>;
				if(file_size > allowed_file_size){
					//alert("CSV file size large. Maximum allowed upload file size is "+<?php echo $upload_size_unit;?>+ <?php echo $sizes[$u];?>);
					jQuery('#csv_import_id').addClass('form-invalid');
					jQuery('#csv_import').focus();
					var file_sizes = new Array( 'KB', 'MB', 'GB' );		
					for ( var file_u = -1; file_size > 1024 && file_u < (file_sizes.length) - 1; file_u++ ) {
						file_size /= 1024;
					}
					if ( file_u < 0 ) {
						file_size = 0;
						file_u = 0;
					} else {
						file_size = Math.round(file_size);
					}
					jQuery('#status').css("display","none");
					jQuery('#csv_status').html("Csv file is too large. Maximum upload file size is "+<?php echo $upload_size_unit;?>+ " "+ file_sizes[file_u] + ", uploaded file size is "+file_size+ " " +file_sizes[file_u]);
					return false;
				}else{
					jQuery('#csv_import_id').removeClass('form-invalid');
					jQuery('#status').html('');
					jQuery('#csv_status').html('');
					return true;
				}
			}
		}
</script>
<div class="wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php _e('Bulk Import/Export',DOMAIN); ?></h2><br/>
	
	<p class="description"><?php _e('This section will allow you to import/export your content in the post type of your choice, you can import/export thousands of records in no time.',DOMAIN);?><strong> <?php _e('Note',DOMAIN); ?> : </strong><?php _e('For a successful import/export you will have to follow the sample CSV format, there is a link below to download the sample file.',DOMAIN); ?></p>	
	
	<div class="updated fade" id="csv_import_message" style="display:none;"></div>
	<?php
	if(isset($_REQUEST['ptype']) && $_REQUEST['ptype'] == "csvdl"){
		if(file_exists(TEMPL_MONETIZE_FOLDER_PATH."templatic-bulk_upload/csvdl.php")){
			include_once(TEMPL_MONETIZE_FOLDER_PATH."templatic-bulk_upload/csvdl.php");
		}
	}
	if(isset($_POST['start']) && $_POST['start']!="" && $_POST['start']!=0 && isset($_SESSION['data']) && $_SESSION['data']!=""){
		$inserted = $_POST['start'];
		$total_record = count($_SESSION['data']);
		$completed="";
		$imported = ($_SESSION['imported'] > 0) ? $_SESSION['imported'] : 0;
		$updated  = ($_SESSION['updated'] > 0 ) ? $_SESSION['updated'] : 0;
		$skipped  = ($_SESSION['skipped'] > 0 ) ? $_SESSION['skipped'] : 0;
		if($inserted==$total_record ){
			$completed = "<span style='color:green'>&nbsp;&nbsp; Import process completed.</span>";
			unset($_SESSION['imported']);
			unset($_SESSION['updated']);
			unset($_SESSION['skipped']);
			$_SESSION['imported']="";
			$_SESSION['updated']="";
			$_SESSION['skipped']="";
		}
	?>
	<div class="updated fade" style="padding:10px;width:960px;margin:0 0 10px;">
		<?php 
			//_e("<b>$inserted of $total_record posts affected. $completed</b>","templatic");
			_e("<b>$imported imported, $updated updated, $skipped skipped of $total_record posts. $completed</b>","templatic");
		?>
	</div>
	<?php } if(isset($_REQUEST['structure_error']) && $_REQUEST['structure_error']!="" && $_REQUEST['structure_error']==1){?>
				<div  id="message" class="error" style="padding:10px;width:960px;margin:0 0 10px;">
					<?php 
						$download = "<a href='".get_bloginfo("url")."/wp-admin/admin.php?page=bulk_upload&ptype=csvdl' style='color:#21759B'>download</a>";
						_e("csv file structure doesn't match. Please $download sample csv file to see required structure.","templatic");
					?>
				</div>	
	<?php }?>
	<!-- It's section to export csv form BOF-->
	<h3><?php _e('Bulk Import(.csv)',DOMAIN); ?></h3>
	<form action="<?php echo site_url('/wp-admin/admin.php')?>?page=bulk_upload" method="post" name="bukl_upload_frm" enctype="multipart/form-data" style="padding-top:10px;" id="bukl_upload_frm" onsubmit="return chek_file();">
	
	<table class="form-table" style="width:50%">
							 <input type="hidden" name="ptype" id="ptype" value="post"/>
							 <tbody>
							 <tr>
							 <th><?php _e('Select post type',DOMAIN);?></th>
							 <td>
									<?php
										$all_post_types = get_post_types();
										foreach($all_post_types as $post_types){
											if( $post_types != "page" && $post_types != "attachment" && $post_types != "revision" && $post_types != "nav_menu_item" ){
									?>
												<label><input type="radio" id="my_post_type" name="my_post_type" value="<?php echo $post_types;?>" <?php if($post_types == "post" ){echo "checked='checked'";}?> /> <?php echo $post_types;?></label><br/>
									<?php	
											}
										}	
									?>
								<div style="width:500px;" id="csv_import_id">
			
									<input name="csv_import" id="csv_import" class="csv_import" type="file"  value="" style="margin-top:25px;"/><br/>
									<input type="submit" class="button button-secondary" name="submit" id="submit" value="Import csv file" style="margin-top:20px;margin-bottom:0; clear:both;"/>
									<div id="status" style="padding:0 0 0 130px;color:red"></div>
									<div id="csv_status" style="text-align:center;margin-left:10px;color:red"></div>
									<span id="read" style="font-weight:bold;color:black"></span>
								</div>
								<p class="description"><i><?php _e('Download the sample file to see the correct structure of the .csv file. To use bulk upload with custom fields simply add them as new columns inside the .csv file. Add them last (on the end).');?> <a href="<?php echo get_bloginfo('url')."/wp-admin/admin.php?page=bulk_upload&ptype=csvdl"?>"><?php _e('sample CSV file');?></a></i></p>
								</td>
							</tr>
		</tbody>
	</table>
	</form>
	<h3><?php _e('Bulk export(.csv)',DOMAIN); ?></h3>
	<table class="form-table" style="width:40%">
		<tbody>
			<tr>
			<th><?php _e('Select post type',DOMAIN);?> </th>
			<td>
						<form name="templatic_bulk_upload" method="post" action="<?php echo plugins_url('Tevolution/tmplconnector/monetize/templatic-bulk_upload/export_to_CSV.php');?>" style="padding-top:10px;" >
								<?php
									$all_post_types = get_post_types();
									foreach($all_post_types as $post_types){
										if( $post_types != "page" && $post_types != "attachment" && $post_types != "revision" && $post_types != "nav_menu_item" ){
								?>
											<label><input type="radio" id="post_type_export" name="post_type_export" value="<?php echo $post_types;?>" <?php if($post_types == "post" ){echo "checked='checked'";}?>/> <?php echo $post_types;?></label><br/>
								<?php	
										}
									}	
								?>
							<input type="submit" name="export_to_csv" value="<?php _e('Export To CSV',DOMAIN);?>" class="button button-secondary" id="submit">
							<p class="description"><i><?php _e('If your current theme doesn&lsquo;t support .csv exports connect to your database and open the wp_posts table. Select the posts you want to export (use Search or a custom SQL query) and click on the Export tab in the header. Use the CSV format for the export.',DOMAIN);?></i></p>
						</form>	
			</td>
			</tr>
		</tbody>
	</table>
</div><!-- wrap close -->

<?php
//echo $_FILES['csv_import']['size']."asdas";
// saved file name to session	
if(isset($_FILES['csv_import']['tmp_name']) && $_FILES['csv_import']['tmp_name']!=""){
	$_SESSION['file_name'] = $_FILES['csv_import']['tmp_name'];
}
// finish saved file name to session

// saved post type to session	
if(isset($_POST['my_post_type']) && $_POST['my_post_type']!=""){
	$_SESSION['my_post_type'] = $_POST['my_post_type'];
}
if(isset($_SESSION['my_post_type']) && $_SESSION['my_post_type']!=""){
	$post_type = $_SESSION['my_post_type'];
}else{
	$post_type = 'post';
}
// finish saved post type to session	

$file = isset($_FILES['csv_import']['tmp_name']) ? $_FILES['csv_import']['tmp_name'] : "";
if($_POST){
$error= isset($_FILES['csv_import']['error']) ? $_FILES['csv_import']['error'] : "";
//check the upload file error 
if($error > 0)
{	
	$upload_size_unit = $max_upload_size = wp_max_upload_size();
	$sizes = array( 'KB', 'MB', 'GB' );		
	for ( $u = -1; $upload_size_unit > 1024 && $u < count( $sizes ) - 1; $u++ ) {
		$upload_size_unit /= 1024;
	}

	if ( $u < 0 ) {
		$upload_size_unit = 0;
		$u = 0;
	} else {
		$upload_size_unit = (int) $upload_size_unit;
	}
	
	$msg= "CSV file size large. Maximum allowed upload file size is ".esc_html($upload_size_unit)." ".esc_html($sizes[$u]);
	echo "<script type='text/javascript'>jQuery('#status').html('$msg')</script>";
	exit;	
}
//finish the upload file error condition
}
if(isset($_FILES['csv_import']['tmp_name']) && $_FILES['csv_import']['tmp_name']!=""){
	$rows    = array();
	$headers = array();
	//open upload file 
	if($file!=""){
		$res = fopen($file, 'r');
	}	
	if($file!=""){
		$c=0;
		while ($keys = fgetcsv($res,99999)) {
			if ($c == 0) {
				$headers = $keys;
				
			}else {
				array_push($rows, $keys);
			}
			$c ++;
		}		
		fclose($res);	

		$columns=$headers;
		$ret_arr = array();

		foreach ($rows as $record) {
			$item_array = array();
			foreach ($record as $column => $value) {
			  if($value!=""){
				$header = $headers[$column];			
				//echo $header."=".$value."<br>";
				if (in_array($header, $columns)) {
					$item_array[$header] = $value;
				}
			  }	
			}

			// do not append empty results
			if ($item_array !== array()) {
				array_push($ret_arr, $item_array);			
			}
		}
		$_SESSION['data']= $ret_arr;
	}
}
if($_POST && isset($_SESSION['file_name']) && $_SESSION['file_name']!=""){
	//print_r($_SESSION['data']);exit;
	if(isset($_SESSION['data'][0]['templatic_post_author']) && $_SESSION['data'][0]['templatic_post_author']!=""){
		
	}else{
		$_SESSION['data']="";
		$_SESSION['file_name']="";
		$_SESSION['my_post_type']="";
		unset($_SESSION['data']);
		unset($_SESSION['file_name']);
		unset($_SESSION['my_post_type']);
		$error_url =site_url().'/wp-admin/admin.php';
	?>
		<form action="<?php echo $error_url; ?>?page=bulk_upload" method="post" id="structure_error_frm" name="structure_error_frm">
			<input type="hidden" name="structure_error" value="1"/>
		</form>
		<script type="text/javascript">
			document.structure_error_frm.submit();
		</script>
	<?php
		continue;
	}
	echo "<script type='text/javascript'>jQuery('#read').html('Reading file...')</script>";
	
	
	$file_path = dirname(__FILE__);
	$file = substr($file_path,0,stripos($file_path, "wp-content"));
	//include wp-load.php file
	require($file . "/wp-load.php");
	require_once(ABSPATH . 'wp-admin/includes/taxonomy.php');
	require_once(ABSPATH . 'wp-admin/includes/template.php');
	global $wpdb;
	$upload_csv_folder=$file_path."/csv/";
	
	$file = isset($_FILES['csv_import']['tmp_name']) ? $_FILES['csv_import']['tmp_name'] : "";
	$error= isset($_FILES['csv_import']['error']) ? $_FILES['csv_import']['error'] : "";
	
	$comments = 0;	 
	$imported = isset($_SESSION['imported']) ? $_SESSION['imported'] : 0;
	$updated = isset($_SESSION['updated']) ? $_SESSION['updated'] : 0;
	$skipped = isset($_SESSION['skipped']) ? $_SESSION['skipped'] : 0;
	
	$count_arr = count($_SESSION['data']);
	if(isset($_REQUEST['start']) && $_REQUEST['start']!=""){
		echo "<script type='text/javascript'>jQuery('#read').html('');</script>";	
		if($count_arr>$_REQUEST['start']){
			$start = $_REQUEST['start'];
		}else{
			$start=0;
		}
	}else{
		$start = 0;
	}
	if(isset($_REQUEST['loop']) && $_REQUEST['loop']!=""){
		if($count_arr>$_REQUEST['loop']){
			$remain = $count_arr - $_REQUEST['loop'];
			if($remain>=10){
				$loop = $_REQUEST['loop'] + 10;
			}else{
				$loop = $_REQUEST['loop'] + $remain;
			}
		}else{
			$loop=0;
			$_SESSION['data']="";
			$_SESSION['file_name']="";
			$_SESSION['my_post_type']="";
			unset($_SESSION['data']);
			unset($_SESSION['file_name']);
			unset($_SESSION['my_post_type']);
		}
	}else{
		if($count_arr>=10){
			$loop=10;
		}else{
			$loop=$count_arr;
		}
	}
		for($i=$start;$i<$loop; $i++){
			
			$postid = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_type='".$post_type."' and  post_title = '".$_SESSION['data'][$i]['templatic_post_title']."'" );		 
			if($postid==""):
				$new_post = array(
				'post_title'   => convert_chars(addslashes(iconv('', 'utf-8',$_SESSION['data'][$i]['templatic_post_title']))),
				'post_content' => wpautop(convert_chars(addslashes(iconv('', 'utf-8',$_SESSION['data'][$i]['templatic_post_content'])))),
				'post_status'  => 'publish',	
				'post_type'    => $post_type,
				'post_date'    => date('Y-m-d H:i:s', strtotime($_SESSION['data'][$i]['templatic_post_date'])),
				'post_excerpt' => convert_chars(addslashes(iconv('', 'utf-8',$_SESSION['data'][$i]['templatic_post_excerpt']))),
				'post_name'    => convert_chars(addslashes(iconv('', 'utf-8',$_SESSION['data'][$i]['templatic_post_name']))),
				'post_author'  =>($_SESSION['data'][$i]['templatic_post_author']) ? $_SESSION['data'][$i]['templatic_post_author'] : 0, 		
				'post_parent'  => $_SESSION['data'][$i]['templatic_post_parent'],
				//'tags_input' => $_SESSION['data'][$i]['templatic_post_tags'],
			 );
			
			  // pages don't have tags or categories
			  //create the or get the categories id
				if ('page' !== $post_type) {
					$new_post['tags_input'] = $_SESSION['data'][$i]['templatic_post_tags'];
				
					// Setup categories before inserting - this should make insertion
					// faster, but I don't exactly remember why :) Most likely because
					// we don't assign default cat to post when csv_post_categories
					// is not empty.
					//if($_SESSION['data'][$i]['templatic_post_category']!=""){}
					$cats =create_or_get_categories($_SESSION['data'][$i]);
					$new_post['post_category'] = $cats['post'];
				}				
				$taxonomies = get_object_taxonomies( (object) array( 'post_type' => $post_type,'public'   => true, '_builtin' => true ));					
				$last_postid = wp_insert_post( $new_post );
				/* Finish the place geo_latitude and geo_longitude in postcodes table*/
				
				if($_SESSION['data'][$i]['templatic_post_category']!="")
				{
					$category_name=explode(',',$_SESSION['data'][$i]['templatic_post_category']);
					wp_set_object_terms($last_postid,$category_name, $taxonomies[0]);
				}
				if($_SESSION['data'][$i]['templatic_post_tags']!="")
				{
					wp_set_post_terms($last_postid,$_SESSION['data'][$i]['templatic_post_tags'],$taxonomies[1]);
				}
				
				if(is_plugin_active('wpml-translation-management/plugin.php') && function_exists('wpml_insert_templ_post')){
					wpml_insert_templ_post($last_postid,$post_type); /* insert post in language */
				}
				 //below add for comment
				 
				 //check the temlatic header is available in csv file or not
				 if($_SESSION['data'][$i]["templatic_comments_data"]!=""){
					 $comments=$_SESSION['data'][$i]["templatic_comments_data"];			
					 $comeents_explode = explode('##',$comments);
						foreach($comeents_explode as $comeents_explode_obj){
							$comment_data = explode("~",$comeents_explode_obj);
							$data = array(
									'comment_post_ID' => $last_postid,
									'comment_author' =>convert_chars($comment_data[2]),
									'comment_author_email' =>convert_chars( $comment_data[3]),
									'comment_author_url' =>convert_chars($comment_data[4]),
									'comment_content' =>convert_chars($comment_data[8]),
									'comment_type' =>  $comment_data[12],
									'comment_parent' =>  $comment_data[13],
									'user_id' =>  $comment_data[14],
									'comment_author_IP' => $comment_data[5],
									'comment_agent' =>  $comment_data[11],
									'comment_date' =>  date('Y-m-d H:i:s', strtotime($comment_data[6])),
									'comment_approved' =>  $comment_data[10],
								);						
								wp_insert_comment($data);
						}
						
				 }//finish the insert comment if condition
				 
				 //below add the custom field
				 create_templatic_custom_field($last_postid,$_SESSION['data'][$i]);
				 
				 //upload images
				 upload_templatic_images($last_postid,$_SESSION['data'][$i]);
				 $imported++;
			 elseif($postid!=""):	
				//update the existing post
				$new_post = array(
					'ID'=>$postid,
					'post_title'   => convert_chars(addslashes(iconv('', 'utf-8',$_SESSION['data'][$i]['templatic_post_title']))),
					'post_content' => wpautop(convert_chars(addslashes(iconv('', 'utf-8',$_SESSION['data'][$i]['templatic_post_content'])))),
					'post_status'  => 'publish',
					'post_type'    => $post_type,
					'post_date'    => date('Y-m-d H:i:s', strtotime($_SESSION['data'][$i]['templatic_post_date'])),
					'post_excerpt' => convert_chars(addslashes(iconv('', 'utf-8',$_SESSION['data'][$i]['templatic_post_excerpt']))),
					'post_name'    => convert_chars(addslashes(iconv('', 'utf-8',$_SESSION['data'][$i]['templatic_post_name']))),
					'post_author'  =>($_SESSION['data'][$i]['templatic_post_author']) ? $_SESSION['data'][$i]['templatic_post_author'] : 0, 		
					'post_parent'  => $_SESSION['data'][$i]['templatic_post_parent'],
					'tags_input' => convert_chars(addslashes(iconv('', 'utf-8',$_SESSION['data'][$i]['templatic_post_tag']))),
				 );
				 wp_update_post( $new_post );
				 
				//below update the custom field
				create_templatic_custom_field($postid,$_SESSION['data'][$i]);
				$updated++;
			 else:
				$skipped++;
			 endif;						
			
			if($i==($loop-1)){ 
				$start=$loop;
			
				$url = site_url().'/wp-admin/admin.php';
		
		?>
				<form action="<?php echo $url; ?>?page=bulk_upload" method="post" id="upload_frm" name="upload_frm">
					<input type="hidden" name="start" value="<?php echo $start;?>"/>
					<input type="hidden" name="loop" value="<?php echo $loop;?>"/>
				</form>
				<script type="text/javascript">
					document.upload_frm.submit();
				</script>
			<?php 
			}
			/*echo'<script type="text/javascript">document.bukl_upload_frm.submit();</script>';*/
		}
		$_SESSION['imported'] = $imported;
		$_SESSION['updated'] = $updated;
		$_SESSION['skipped'] = $skipped;
}
//
// Function Name: create_or_get_categories
// Argument: csv data array
// return: create new categories and return ids or get the existing categories ids
//
function create_or_get_categories($data)
{	
	$ids = array(
            'post' => array(),
            'cleanup' => array(),
        );
        $items = array_map('trim', explode(',', $data['templatic_post_category']));
        foreach ($items as $item) {
            if (is_numeric($item)) {
                if (get_category($item) !== null) {
                    $ids['post'][] = $item;
                }
            } else {
                $parent_id = 0;
                // item can be a single category name or a string such as
                // Parent > Child > Grandchild
                $categories = array_map('trim', explode('>', $item));
                if (count($categories) > 1 && is_numeric($categories[0])) {
                    $parent_id = $categories[0];
                    if (get_category($parent_id) !== null) {
                        // valid id, everything's ok
                        $categories = array_slice($categories, 1);
                    } 
                }
                foreach ($categories as $category) {
                    if ($category) {
                        $term = templatic_term_exists($category, 'category', $parent_id);
                        if ($term) {
                            $term_id = $term['term_id'];
                        } else {
                            $term_id = wp_insert_category(array(
                                'cat_name' => $category,
                                'category_parent' => $parent_id,
                            ));
                            $ids['cleanup'][] = $term_id;
                        }
                        $parent_id = $term_id;
                    }
                }
                $ids['post'][] = $term_id;
            }
        }
        return $ids;	
}


//
//  Function Name: create_templatic_custom_field
//  add the custom field.
//
function create_templatic_custom_field($post_id, $data) {
	foreach ($data as $k => $v) {
		// anything that doesn't start with csv_ is a custom field
		if (!preg_match('/^templatic_/', $k) && $v != '') {
			//add_post_meta($post_id, $k, $v);
			update_post_meta($post_id, trim($k), trim($v));
		}
	}
}
//
//  Function Name: upload_templatic_images
//  Upload images
//
function upload_templatic_images($last_postid,$data)
{
	$image_folder_name = '/bulk/';
	$dirinfo = wp_upload_dir();		
	$path = $dirinfo['path'];
	$url = $dirinfo['url'];
	$subdir = $dirinfo['subdir'];
	$basedir = $dirinfo['basedir'];
	$baseurl = $dirinfo['baseurl'];	
	
	foreach ($data as $k => $v) {
		if (preg_match('/^templatic_img/', $k) && $v != '') 
		{
			$image_name=$v;// image name
			$image_name_arr = explode(';',$image_name);
			foreach($image_name_arr as $_image_name_arr)
			{
				$upload_img_path=$basedir.$image_folder_name._wp_relative_upload_path( $_image_name_arr );
				//if (file_exists($upload_img_path)) 
				//{	echo "hello";exit;
					  $wp_filetype = wp_check_filetype(basename($_image_name_arr), null );
					  $attachment = array(
						 'guid' => $baseurl.$image_folder_name._wp_relative_upload_path( $_image_name_arr ), 
						 'post_mime_type' => $wp_filetype['type'],
						 'post_title' => preg_replace('/\.[^.]+$/', '', basename($_image_name_arr)),
						 'post_content' => '',
						 'post_status' => 'inherit'
					  );		
						
					  $img_attachment=substr($image_folder_name.$_image_name_arr,1);				  
					  $attach_id = wp_insert_attachment( $attachment, $img_attachment, $last_postid );				  
					 
					  // you must first include the image.php file
					  // for the function wp_generate_attachment_metadata() to work
					  require_once(ABSPATH . 'wp-admin/includes/image.php');
					  $upload_img_path=$basedir.$image_folder_name._wp_relative_upload_path( $_image_name_arr );     			  
					  
					  $attach_data = wp_generate_attachment_metadata( $attach_id, $upload_img_path );					  			 
					  wp_update_attachment_metadata( $attach_id, $attach_data );
				//}//finish the file existing upload image path								
			}//finish foreach loop
		}//finish the templatic_img preg_match condition
	}//finish the foreach loop
}

//
// Compatibility wrapper for WordPress term lookup.
//
function templatic_term_exists($term, $taxonomy = '', $parent = 0)
{
	return is_term($term, $taxonomy, $parent);
} 
?>