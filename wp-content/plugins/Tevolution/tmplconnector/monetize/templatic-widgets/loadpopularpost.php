<?php
$file = dirname(__FILE__);
$file = substr($file,0,stripos($file, "wp-content"));
require_once($file."/wp-load.php");
global $wpdb,$posts,$post,$query_string;
$ppost = get_option('widget_templatic_popular_post_technews');
	foreach($ppost as $key=>$value)
	{		
		$popular_per=$value['popular_per'];
		$number=$value['number'];		
		break;
	}
	$posthtml = '';		
	$start = $_REQUEST['limitarr'][0];
	$end = $_REQUEST['limitarr'][1];
	$total = $_REQUEST['limitarr'][2];
	$post_type = $_REQUEST['limitarr'][3];
	$num=$_REQUEST['limitarr'][4];
	$popular_per=$_REQUEST['limitarr'][5];
	$number=$_REQUEST['limitarr'][6];
	if(isset($number))
		$_SESSION['total'] = $number;		
	
	if(($start + $end) > $_SESSION['total'])
	{
		$end =   ($_SESSION['total'] - $start );
	}			

		
	//$popular_per = $ppost[3]['popular_per'];	
	if($popular_per == 'views'){		
		$args_popular=array(
					'post_type'=>$post_type,
					'post_status'=>'publish',
					'posts_per_page' => $end,
					'paged'=>$num,
					'meta_key'=>'viewed_count',
					'orderby' => 'meta_value_num',
					'meta_value_num'=>'viewed_count',
					'order' => 'DESC'
					);
	}elseif($popular_per == 'dailyviews'){
		$args_popular=array(
					'post_type'=>$post_type,
					'post_status'=>'publish',
					'posts_per_page' => $end,
					'paged'=>$num,
					'meta_key'=>'viewed_count_daily',
					'orderby' => 'meta_value_num',
					'meta_value_num'=>'viewed_count_daily',
					'order' => 'DESC'
					);
	}else{		
		$args_popular=array(
					'post_type'=>$post_type,
					'post_status'=>'publish',
					'posts_per_page' => $end,
					'paged'=>$num,					
					'orderby' => 'comment_count',					
					'order' => 'DESC'
					);
	}
remove_all_actions('posts_orderby');
$popular_post_query = new WP_Query($args_popular);	
if($popular_post_query):
	while ($popular_post_query->have_posts()) : $popular_post_query->the_post();
		$post_title = stripslashes($post->post_title);
		$guid = get_permalink($post->ID);		
		if($popular_per=="views")
		{
			$total_view = user_single_post_visit_count($post->ID);
			$views = $total_view.__(" View ",DOMAIN);
			if($total_view > 1){
				$views = $total_view.__(" Views ",DOMAIN);
			}
		}
		if($popular_per=="dailyviews")
		{
			$total_view = user_single_post_visit_count_daily($post->ID);
			$views = $total_view.__(" Daily View ",DOMAIN);
			if($total_view > 1){
				$views = $total_view.__(" Daily Views ",DOMAIN);
			}
		}
		$comments = $post->comment_count.__(" Comment ",DOMAIN);
		if($post->comment_count > 1){
			$comments = $post->comment_count.__(" Comments ",DOMAIN);
		}
		$first_post_title=substr($post_title,0,26);					
		$post_images =  bdw_get_images_plugin($post->ID,'thumbnail'); 					
		$attachment_id = $post_images[0]['id'];
		$alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
		$attach_data = get_post($attachment_id);
		$title = stripslashes($attach_data->post_title); 
		if($title ==''){ $title = $post->post_title; }
		if($alt ==''){ $alt = $post->post_title; }
		
		if(isset($post_images[0]['file'])){
			$crop_image = vt_resize($attachment_id, $post_images[0]['file'], 73, 51, $crop = true );
			$first_img = $crop_image['url'];
		}else{
			$first_img = TEMPL_PLUGIN_URL."tmplconnector/monetize/templatic-widgets/widget_images/no-image73.png";
		}		
		
		$posthtml .= '<li class="clearfix">';			
		$posthtml .= '<a href="'. $guid .'" class="post_img"><img src="'.$first_img.'" alt="'.$post_title.'" title="'.$post_title.'" /></a>';
		
		if(isset($post->comment_date) && strtotime($post->comment_date) != 0) {
			$du = strtotime($post->comment_date);
		} else {
			$du = strtotime($post->post_date);
		}
		 $fv = human_time_diff($du, current_time('timestamp')) . " " . __('ago',DOMAIN);
		if($popular_per == 'views' || $popular_per == 'dailyviews'){
			$posthtml .= '<div class="post_data"><h3><a href="'.$guid.'" title="'.$post_title.'">'.$first_post_title.'</a></h3><p><span class="date">'.$fv."</span><span class='views'> ".$views.'</span></p></div></li>';
		}else{
			$posthtml .= '<div class="post_data"><h3><a href="'.$guid.'" title="'.$post_title.'">'.$first_post_title.'</a></h3><p><span class="date">'.$fv."</span><span class='views'> ".$comments.'</span></p></div></li>';
		}				
	 
	 	
	
	endwhile;
	_e($posthtml);	
else:?>
	<p><?php _e('No Popular post fond.',DOMAIN);?></p>
<?php
endif;
/**--- Function : Count/fetch the daily views and total views EOF--**/

function vt_resize( $attach_id = null, $img_url = null, $width, $height, $crop = true ) {
	
	// this is an attachment, so we have the ID			
	if ( $attach_id ) {

	$image_src = wp_get_attachment_image_src( $attach_id, 'full' );
	$file_path = get_attached_file( $attach_id );

	// this is not an attachment, let's use the image url
	} else if ( $img_url ) {

	$file_path = parse_url( $img_url );
	$file_path = $_SERVER['DOCUMENT_ROOT'] . $file_path['path'];

	// Look for Multisite Path
	if(file_exists($file_path) === false){
	global $blog_id;
	$file_path = parse_url( $img_url );
	if (preg_match("/files/", $file_path['path'])) {
		$path = explode('/',$file_path['path']);
	foreach($path as $k=>$v){
	if($v == 'files'){
		$path[$k-1] = '/wp-content/blogs.dir/'.$blog_id;
	}
	}
	$path = implode('/',$path);
	}
	if(basename($img_url) !='no-image73.png'){
	
	$file_path = $_SERVER['DOCUMENT_ROOT'].$path;
	}else{
	$file_path = $img_url;
	}
	}
	$orig_size = getimagesize( $file_path );
	
	$image_src[0] = $img_url;
	$image_src[1] = $orig_size[0];
	$image_src[2] = $orig_size[1];
	}

	$file_info = pathinfo( $file_path );

	// check if file exists
	$base_file = $file_info['dirname'].'/'.$file_info['filename'].'.'.$file_info['extension'];
	if ( !file_exists($base_file) )
	return;

	$extension = '.'. $file_info['extension'];

	// the image path without the extension
	$no_ext_path = $file_info['dirname'].'/'.$file_info['filename'];

	$cropped_img_path = $no_ext_path.'-'.$width.'x'.$height.$extension;

	// checking if the file size is larger than the target size
	// if it is smaller or the same size, stop right here and return
	if ( $image_src[1] > $width || $image_src[2] > $height) {

	// the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)
	if ( file_exists( $cropped_img_path ) ) {

	$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );

	$vt_image = array (
	'url' => $cropped_img_url,
	'width' => $width,
	'height' => $height
	);

	return $vt_image;
	}

	// $crop = false or no height set
	if ( $crop == false OR !$height ) {

	// calculate the size proportionaly
	$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
	$resized_img_path = $no_ext_path.'-'.$proportional_size[0].'x'.$proportional_size[1].$extension;

	// checking if the file already exists
	if ( file_exists( $resized_img_path ) ) {

	$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );

	$vt_image = array (
	'url' => $resized_img_url,
	'width' => $proportional_size[0],
	'height' => $proportional_size[1]
	);

	return $vt_image;
	}
}

// check if image width is smaller than set width
$img_size = getimagesize( $file_path );
if ( $img_size[0] <= $width ) $width = $img_size[0];

// Check if GD Library installed
if (!function_exists ('imagecreatetruecolor')) {
_e('GD Library Error: imagecreatetruecolor does not exist - please contact your webhost and ask them to install the GD library');
return;
}

// no cache files - let's finally resize it
$new_img_path = image_resize( $file_path, $width, $height, $crop );
$new_img_size = getimagesize( $new_img_path );
$new_img = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );

// resized output
$vt_image = array (
'url' => $new_img,
'width' => $new_img_size[0],
'height' => $new_img_size[1]
);

return $vt_image;
}

// default output - without resizing
$vt_image = array (
'url' => $image_src[0],
'width' => $width,
'height' => $height
);

return $vt_image;
}
?>