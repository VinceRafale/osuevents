<?php
function validation_type_cmb_plugin($validation_type = ''){
	$validation_type_display = '';
	$validation_type_array = array(" "=>"Select validation type","require"=>"Require","phone_no"=>"Phone No.","digit"=>"Digit","email"=>"Email");
	foreach($validation_type_array as $validationkey => $validationvalue){
		if($validation_type == $validationkey){
			$vselected = 'selected';
		} else {
			$vselected = '';
		}
		$validation_type_display .= '<option value="'.$validationkey.'" '.$vselected.'>'.__($validationvalue,DOMAIN).'</option>';
	}
	return $validation_type_display;
}
/*
Name : templ_number_of_days
desc : difference between two date date must be in Y-m-d format
*/

function templ_number_of_days($date1, $date2) {

	$date1Array = explode('-', $date1);
	$date1Epoch = mktime(0, 0, 0, $date1Array[1],
	$date1Array[2], $date1Array[0]);

	$date2Array = explode('-', $date2);
	$date2Epoch = mktime(0, 0, 0, $date2Array[1],
	$date2Array[2], $date2Array[0]);

	$date_diff = $date2Epoch - $date1Epoch;
	return round($date_diff / 60 / 60 / 24);
}

/*
Name : templ_get_parent_categories
Args : pass the taxonomy
desc : return the array of categories
*/

function templ_get_parent_categories($taxonomy) {

	$cat_args = array(
	'taxonomy'=>$taxonomy,
	'orderby' => 'name', 				
	'hierarchical' => 'true',
	'parent'=>0,
	'hide_empty' => 0,	
	'title_li'=>'');				
	$categories = get_categories( $cat_args );	/* fetch parent categories */
	return $categories;
}
/*
Name : templ_get_child_categories
Args : pass the taxonomy, parent id
desc : return the array of child categories
*/

function templ_get_child_categories($taxonomy,$parent_id) {
	$args = array('child_of'=> $parent_id,'hide_empty'=> 0,'taxonomy'=>$taxonomy);                        
	$child_cats = get_categories( $args );	/* get child cats */
	return $child_cats;
}

function custom_field_posts_where_filter($join)
{
	global $wpdb, $pagenow, $wp_taxonomies;
	$language_where='';
	if(is_plugin_active('wpml-translation-management/plugin.php')){
		$language = ICL_LANGUAGE_CODE;
		$join .= " {$ljoin} JOIN {$wpdb->prefix}icl_translations t ON {$wpdb->posts}.ID = t.element_id			
			AND t.element_type IN ('post_custom_fields') JOIN {$wpdb->prefix}icl_languages l ON t.language_code=l.code AND l.active=1 AND t.language_code='".$language."'";
	}	
	return $join;
}
/* 
Name :get_post_custom_fields_templ_plugin
description : Returns all custom fields
*/
function get_post_custom_fields_templ_plugin($post_types,$category_id='',$taxonomy='',$heading_type='') {
	global $wpdb,$post,$_wp_additional_image_sizes,$sitepress;
	$category_id = explode(",",$category_id);
 	$tmpdata = get_option('templatic_settings');
	remove_all_actions('posts_where');
	if($tmpdata['templatic-category_custom_fields'] == 'No')
	{
		if($heading_type)
		  {
			$args=
			array( 
			'post_type' => 'custom_fields',
			'posts_per_page' => -1	,
			'post_status' => array('publish'),
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'post_type_'.$post_types.'',
					'value' => array('all',$post_types),
					'compare' => 'IN',
					'type'=> 'text'
				),
				array(
					'key' => 'post_type',
					'value' => $post_types,
					'compare' => 'LIKE',
					'type'=> 'text'
				),

				array(
					'key' => 'show_on_page',
					'value' =>  array('user_side','both_side'),
					'compare' => 'IN',
					'type'=> 'text'
				),
				
				array(
					'key' => 'is_active',
					'value' =>  '1',
					'compare' => '='
				),
				array(
					'key' => 'heading_type',
					'value' =>  array('basic_inf',$heading_type),
					'compare' => 'IN'
				)
	
			),		 
			'meta_key' => 'sort_order',
			'orderby' => 'meta_value_num',
			'meta_value_num'=>'sort_order',
			'order' => 'ASC'
			);
		  }
		 else
		  {
			$args=
			array( 
			'post_type' => 'custom_fields',
			'posts_per_page' => -1	,
			'post_status' => array('publish'),
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'post_type_'.$post_types.'',
					'value' => array('all',$post_types),
					'compare' => 'In',
					'type'=> 'text'
				),
				array(
					'key' => 'show_on_page',
					'value' =>  array('user_side','both_side'),
					'compare' => 'IN',
					'type'=> 'text'
				),
				
				array(
					'key' => 'is_active',
					'value' =>  '1',
					'compare' => '='
				)),
			'meta_key' => 'sort_order',
			'orderby' => 'meta_value_num',
			'meta_value_num'=>'sort_order',
			'order' => 'ASC'
			);
		  }
	}
	else
	{
		if($heading_type)
		{

			$args=
			array( 
			'post_type' => 'custom_fields',
			'posts_per_page' => -1	,
			'post_status' => array('publish'),
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'post_type_'.$post_types.'',
					'value' => array('all',$post_types),
					'compare' => 'In',
					'type'=> 'text'
				),
				array(
					'key' => 'post_type',
					'value' => $post_types,
					'compare' => 'LIKE',
					'type'=> 'text'
				),
				array(
					'key' => 'show_on_page',
					'value' =>  array('user_side','both_side'),
					'compare' => 'IN',
					'type'=> 'text'
				),
				
				array(
					'key' => 'is_active',
					'value' =>  '1',
					'compare' => '='
				),
				array(
					'key' => 'heading_type',
					'value' =>  $heading_type,
					'compare' => '='
				)
	
			),
			'tax_query' => array(
					'relation' => 'OR',
				array(
					'taxonomy' => $taxonomy,
					'field' => 'id',
					'terms' => $category_id,
					'operator'  => 'IN'
				),
				array(
					'taxonomy' => 'category',
					'field' => 'id',
					'terms' => 1,
					'operator'  => 'IN'
				)
				
			 ),
			 
			'meta_key' => 'sort_order',
			'orderby' => 'meta_value_num',
			'meta_value_num'=>'sort_order',
			'order' => 'ASC'
			);
		}
	  else
	  {
		  	$args=
			array( 
			'post_type' => 'custom_fields',
			'posts_per_page' => -1	,
			'post_status' => array('publish'),
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'post_type_'.$post_types.'',
					'value' => array('all',$post_types),
					'compare' => 'In',
					'type'=> 'text'
				),
				array(
					'key' => 'show_on_page',
					'value' =>  array('user_side','both_side'),
					'compare' => 'IN',
					'type'=> 'text'
				),
				
				array(
					'key' => 'is_active',
					'value' =>  '1',
					'compare' => '='
				)
			),
			'tax_query' => array(
					'relation' => 'OR',
				array(
					'taxonomy' => $taxonomy,
					'field' => 'id',
					'terms' => $category_id,
					'operator'  => 'IN'
				),
				array(
					'taxonomy' => 'category',
					'field' => 'id',
					'terms' => 1,
					'operator'  => 'IN'
				)
				
			 ),
			 
			'meta_key' => 'sort_order',
			'orderby' => 'meta_value_num',
			'meta_value_num'=>'sort_order',
			'order' => 'ASC'
			);

	  }
	}
	$post_query = null;
	remove_all_actions('posts_orderby');	
	add_filter('posts_join', 'custom_field_posts_where_filter');
	$post_query = new WP_Query($args);		
	$post_meta_info = $post_query;	
	$return_arr = array();
	if($post_meta_info){
		while ($post_meta_info->have_posts()) : $post_meta_info->the_post();
			if(get_post_meta($post->ID,"ctype",true)){
				$options = explode(',',get_post_meta($post->ID,"option_values",true));
			}
			$custom_fields = array(
					"name"		=> get_post_meta($post->ID,"htmlvar_name",true),
					"label" 	=> $post->post_title,
					"htmlvar_name" 	=> get_post_meta($post->ID,"htmlvar_name",true),
					"default" 	=> get_post_meta($post->ID,"default_value",true),
					"type" 		=> get_post_meta($post->ID,"ctype",true),
					"desc"      => $post->post_content,
					"option_values" => get_post_meta($post->ID,"option_values",true),
					"is_require"  => get_post_meta($post->ID,"is_require",true),
					"is_active"  => get_post_meta($post->ID,"is_active",true),
					"show_on_listing"  => get_post_meta($post->ID,"show_on_listing",true),
					"show_on_detail"  => get_post_meta($post->ID,"show_on_detail",true),
					"validation_type"  => get_post_meta($post->ID,"validation_type",true),
					"style_class"  => get_post_meta($post->ID,"style_class",true),
					"extra_parameter"  => get_post_meta($post->ID,"extra_parameter",true),
					"show_in_email" =>get_post_meta($post->ID,"show_in_email",true),
					);
			if($options)
			{
				$custom_fields["options"]=$options;
			}
			$return_arr[get_post_meta($post->ID,"htmlvar_name",true)] = $custom_fields;
		endwhile;wp_reset_query();
	}
	remove_filter('posts_join', 'custom_field_posts_where_filter');
	return $return_arr;
}

function get_post_admin_custom_fields_templ_plugin($post_types,$category_id='',$taxonomy='') {
	global $wpdb,$post;
	remove_all_actions('posts_where');
	add_filter('posts_join', 'custom_field_posts_where_filter');
		$args=
		array( 
		'post_type' => 'custom_fields',
		'posts_per_page' => -1	,
		'post_status' => array('publish'),
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key' => 'post_type_'.$post_types.'',
				'value' => $post_types,
				'compare' => '=',
				'type'=> 'text'
			),
			array(
				'key' => 'show_on_page',
				'value' =>  array('admin_side','both_side'),
				'compare' => 'IN',
				'type'=> 'text'
			),
			
			array(
				'key' => 'is_active',
				'value' =>  '1',
				'compare' => '='
			)

		),
		
		'meta_key' => 'sort_order',
		'orderby' => 'meta_value_num',
		'meta_value_num'=>'sort_order',
		'order' => 'ASC'

		);
	
	$post_query = null;
	$post_query = new WP_Query($args);
	$post_meta_info = $post_query;
	$return_arr = array();
	if($post_meta_info){
		while ($post_meta_info->have_posts()) : $post_meta_info->the_post();
			if(get_post_meta($post->ID,"ctype",true)){
				$options = explode(',',get_post_meta($post->ID,"option_values",true));
			}
			$custom_fields = array(
					"name"		=> get_post_meta($post->ID,"htmlvar_name",true),
					"label" 	=> $post->post_title,
					"htmlvar_name" 	=> get_post_meta($post->ID,"htmlvar_name",true),
					"default" 	=> get_post_meta($post->ID,"default_value",true),
					"type" 		=> get_post_meta($post->ID,"ctype",true),
					"desc"      => $post->post_content,
					"option_values" => get_post_meta($post->ID,"option_values",true),
					"is_require"  => get_post_meta($post->ID,"is_require",true),
					"is_active"  => get_post_meta($post->ID,"is_active",true),
					"show_on_listing"  => get_post_meta($post->ID,"show_on_listing",true),
					"show_on_detail"  => get_post_meta($post->ID,"show_on_detail",true),
					"validation_type"  => get_post_meta($post->ID,"validation_type",true),
					"style_class"  => get_post_meta($post->ID,"style_class",true),
					"extra_parameter"  => get_post_meta($post->ID,"extra_parameter",true),
					);
			if($options)
			{
				$custom_fields["options"]=$options;
			}
			$return_arr[get_post_meta($post->ID,"htmlvar_name",true)] = $custom_fields;
		endwhile;
		wp_reset_query();
	}
	remove_filter('posts_join', 'custom_field_posts_where_filter');
	return $return_arr;
}


/* 
Name :get_post_fields_templ_plugin
description : Returns all default custom fields
*/
function get_post_fields_templ_plugin($post_types,$category_id='',$taxonomy='') {
	global $wpdb,$post;
	remove_all_actions('posts_where');
		$args=
		array( 
		'post_type' => 'custom_fields',
		'posts_per_page' => -1	,
		'post_status' => array('publish'),
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key' => 'post_type_'.$post_types.'',
				'value' => array($post_types,'all'),
				'compare' => 'IN',
				'type'=> 'text'
			),
			array(
				'key' => 'show_on_page',
				'value' =>  array('user_side','both_side'),
				'compare' => 'IN',
				'type'=> 'text'
			),
			
			array(
				'key' => 'is_active',
				'value' =>  '1',
				'compare' => '='
			)
		),
		'tax_query' => array(
			array(
				'taxonomy' => 'category',
				'field' => 'id',
				'terms' => 1,
				'operator'  => 'IN'
			)
			
		 ),
		 
		'meta_key' => 'sort_order',
		'orderby' => 'meta_value',
		'order' => 'ASC'
		);
	$post_query = null;
	$post_query = new WP_Query($args);	
	$post_meta_info = $post_query;
	$return_arr = array();
	if($post_meta_info){
		while ($post_meta_info->have_posts()) : $post_meta_info->the_post();
			if(get_post_meta($post->ID,"ctype",true)){
				$options = explode(',',get_post_meta($post->ID,"option_values",true));
			}
			$custom_fields = array(
					"name"		=> get_post_meta($post->ID,"htmlvar_name",true),
					"label" 	=> $post->post_title,
					"htmlvar_name" 	=> get_post_meta($post->ID,"htmlvar_name",true),
					"default" 	=> get_post_meta($post->ID,"default_value",true),
					"type" 		=> get_post_meta($post->ID,"ctype",true),
					"desc"      =>  $post->post_content,
					"option_values" => get_post_meta($post->ID,"option_values",true),
					"is_require"  => get_post_meta($post->ID,"is_require",true),
					"is_active"  => get_post_meta($post->ID,"is_active",true),
					"show_on_listing"  => get_post_meta($post->ID,"show_on_listing",true),
					"show_on_detail"  => get_post_meta($post->ID,"show_on_detail",true),
					"validation_type"  => get_post_meta($post->ID,"validation_type",true),
					"style_class"  => get_post_meta($post->ID,"style_class",true),
					"extra_parameter"  => get_post_meta($post->ID,"extra_parameter",true),
					"show_in_email" =>get_post_meta($post->ID,"show_in_email",true),
					"heading_type" => get_post_meta($post->ID,"heading_type",true),
					);
			if($options)
			{
				$custom_fields["options"]=$options;
			}
			$return_arr[get_post_meta($post->ID,"htmlvar_name",true)] = $custom_fields;
		endwhile;
	}
	return $return_arr;
}

/* 
Name :get_search_post_fields_templ_plugin
description : Returns all default custom fields
*/
function get_search_post_fields_templ_plugin($post_types,$category_id='',$taxonomy='') {
	global $wpdb,$post,$sitepress;
		$original_query = $wp_query;
		remove_all_actions('posts_where');
		$args=
		array( 
		'post_type' => 'custom_fields',
		'posts_per_page' => -1	,
		'post_status' => array('publish'),
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key' => 'post_type_'.$post_types,
				'value' => array('all',$post_types),
				'compare' => 'In',
				'type'=> 'text'
			),
			array(
				'key' => 'is_search',
				'value' =>  '1',
				'compare' => '='
			),
			
			array(
				'key' => 'is_active',
				'value' =>  '1',
				'compare' => '='
			)

		),
		 
		'meta_key' => 'sort_order',
		'orderby' => 'meta_value',
		'order' => 'ASC'
		);
	add_filter('posts_join', 'custom_field_posts_where_filter');
	$post_query = null;	
	$post_query = new WP_Query($args);
	$post_meta_info = $post_query;	
	wp_reset_postdata();
	$return_arr = array();
	if($post_meta_info){
		while ($post_meta_info->have_posts()) : $post_meta_info->the_post();
			if(get_post_meta($post->ID,"ctype",true)){
				$options = explode(',',get_post_meta($post->ID,"option_values",true));
			}
			$custom_fields = array(
					"name"		=> get_post_meta($post->ID,"htmlvar_name",true),
					"label" 	=> $post->post_title,
					"htmlvar_name" 	=> get_post_meta($post->ID,"htmlvar_name",true),
					"default" 	=> get_post_meta($post->ID,"default_value",true),
					"type" 		=> get_post_meta($post->ID,"ctype",true),
					"desc"      => $post->post_content,
					"option_values" => get_post_meta($post->ID,"option_values",true),
					"is_require"  => get_post_meta($post->ID,"is_require",true),
					"is_active"  => get_post_meta($post->ID,"is_active",true),
					"show_on_listing"  => get_post_meta($post->ID,"show_on_listing",true),
					"show_on_detail"  => get_post_meta($post->ID,"show_on_detail",true),
					"validation_type"  => get_post_meta($post->ID,"validation_type",true),
					"style_class"  => get_post_meta($post->ID,"style_class",true),
					"extra_parameter"  => get_post_meta($post->ID,"extra_parameter",true),
					);
			if($options)
			{
				$custom_fields["options"]=$options;
			}
			$return_arr[get_post_meta($post->ID,"htmlvar_name",true)] = $custom_fields;
		endwhile;
	}
	remove_filter('posts_join', 'custom_field_posts_where_filter');	
	if(is_plugin_active('wpml-translation-management/plugin.php')){
		add_filter('posts_where', array($sitepress,'posts_where_filter'));	
	}
	return $return_arr;
}

/* 
Name :display_search_custom_post_field_plugin
description : Returns all search custom fields html
*/

function display_search_custom_post_field_plugin($custom_metaboxes,$session_variable,$post_type){
	
		foreach($custom_metaboxes as $key=>$val) {
			$name = $val['name'];
			$site_title = $val['label'];
			$type = $val['type'];
			$htmlvar_name = $val['htmlvar_name'];
			$admin_desc = $val['desc'];
			$option_values = $val['option_values'];
			$default_value = $val['default'];
			$style_class = $val['style_class'];
			$extra_parameter = $val['extra_parameter'];
			if(!$extra_parameter){ $extra_parameter ='';}
			/* Is required CHECK BOF */
			$is_required = '';
			$input_type = '';
			if($val['is_require'] == '1'){
				$is_required = '<span class="required">*</span>';
				$is_required_msg = '<span id="'.$name.'_error" class="message_error2"></span>';
			} else {
				$is_required = '';
				$is_required_msg = '';
			}
			/* Is required CHECK EOF */
			if(@$_REQUEST['pid'])
			{
				$post_info = get_post($_REQUEST['pid']);
				if($name == 'post_title') {
					$value = $post_info->post_title;
				}
				elseif($name == 'post_content') {
					$value = $post_info->post_content;
				}
				elseif($name == 'post_excerpt'){
					$value = $post_info->post_excerpt;
				}
				else {
					$value = get_post_meta($_REQUEST['pid'], $name,true);
				}
			
			}
			if(@$_SESSION[$session_variable] && @$_REQUEST['backandedit'])
			{
				$value = @$_SESSION[$session_variable][$name];
			}
		?>
        <input type="hidden" name="search_custom[<?php echo $name;?>]"  />
		<div class="form_row clearfix">
		   <?php if($type=='text'){?>
		   <label><?php echo $site_title; ?></label>
		   <?php if($name == 'geo_latitude' || $name == 'geo_longitude') {
				$extra_script = 'onblur="changeMap();"';
				
			} else {
				$extra_script = '';
				
			}?>
			 <input name="<?php echo $name;?>" id="<?php echo $name;?>" value="<?php if(isset($value))echo $value;?>" type="text" class="textfield <?php echo $style_class;?>" <?php echo $extra_parameter; ?> <?php echo $extra_script;?> PLACEHOLDER="<?php echo $val['default']; ?> "/>
			 	 <span class="message_note msgcat submit"><?php echo $admin_desc;?></span>
			<?php
			}elseif($type=='date'){
				//jquery data picker			
			?>     
				<script type="text/javascript">
					jQuery(function(){
						var pickerOpts = {						
							showOn: "both",
							dateFormat: 'yy-mm-dd',
							buttonImage: "<?php echo TEMPL_PLUGIN_URL;?>css/datepicker/images/cal.png",
							buttonText: "Show Datepicker"
						};	
						jQuery("#<?php echo $name;?>").datepicker(pickerOpts);
					});
				</script>
				<label><?php echo $site_title; ?></label>
				<input type="text" name="<?php echo $name;?>" id="<?php echo $name;?>" class="textfield <?php echo $style_class;?>" value="<?php echo esc_attr(stripslashes($value)); ?>" size="25" <?php echo 	$extra_parameter;?> />          
			<?php
			}
			elseif($type=='multicheckbox')
			{ ?>
			 <label><?php echo $site_title; ?></label>
			<?php
				$options = $val['option_values'];
				if(!isset($_REQUEST['pid']) && !$_REQUEST['backandedit'])
				{
					$default_value = explode(",",$val['default']);
				}
	
				if($options)
				{  
					$chkcounter = 0;
					echo '<div class="form_cat_left">';
					$option_values_arr = explode(',',$options);
					for($i=0;$i<count($option_values_arr);$i++)
					{
						$chkcounter++;
						$seled='';
						if(isset($_REQUEST['pid']) || $_REQUEST['backandedit'])
						  {
							$default_value = $value;
						  }
						if($default_value !=''){
						if(in_array($option_values_arr[$i],$default_value)){ 
						$seled='checked="checked"';} }	
											
						echo '
						<div class="form_cat">
							<label>
								<input name="'.$key.'[]"  id="'.$key.'_'.$chkcounter.'" type="checkbox" value="'.$option_values_arr[$i].'" '.$seled.'  '.$extra_parameter.' /> '.$option_values_arr[$i].'
							</label>
						</div>';							
					}
					echo '</div>';
				}
			}		
			elseif($type=='texteditor'){	?>
				<label><?php echo $site_title; ?></label>
				<?php
					// default settings
					$settings =   array(
						'wpautop' => true, // use wpautop?
						'media_buttons' => false, // show insert/upload button(s)
						'textarea_name' => $name, // set the textarea name to something different, square brackets [] can be used here
						'textarea_rows' => '10', // rows="..."
						'tabindex' => '',
						'editor_css' => '<style>.wp-editor-wrap{width:640px;margin-left:0px;}</style>', // intended for extra styles for both visual and HTML editors buttons, needs to include the <style> tags, can use "scoped".
						'editor_class' => '', // add extra class(es) to the editor textarea
						'teeny' => false, // output the minimal editor config used in Press This
						'dfw' => false, // replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)
						'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
						'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
					);				
					if(isset($value) && $value != '') 
					{  $content=$value; }
					else{$content= $val['default']; } 				
					wp_editor( $content, $name, $settings);
				?>
			<?php
			}elseif($type=='textarea'){ 
			?>
			<label><?php echo $site_title; ?></label>
			<textarea name="<?php echo $name;?>" id="<?php echo $name;?>" class="<?php if($style_class != '') { echo $style_class;}?> textarea" <?php echo $extra_parameter;?>><?php if(isset($value))echo $value;?></textarea>       
			<?php
			}elseif($type=='radio'){
			?>
			<?php if($name != 'position_filled' || @$_REQUEST['pid']): ?>
			 <label class="r_lbl"><?php echo $site_title; ?></label>
			<?php
				$options = $val['option_values'];
				if($options)
				{  $chkcounter = 0;
					echo '<div class="form_cat_left">';
					$option_values_arr = explode(',',$options);
					for($i=0;$i<count($option_values_arr);$i++)
					{
						$chkcounter++;
						$seled='';
						if($default_value == $option_values_arr[$i]){ $seled='checked="checked"';}
						if (isset($value) && trim($value) == trim($option_values_arr[$i])){ $seled='checked="checked"';}
						echo '<div class="form_cat">
							<label class="r_lbl">
								<input name="'.$key.'"  id="'.$key.'_'.$chkcounter.'" type="radio" value="'.$option_values_arr[$i].'" '.$seled.'  '.$extra_parameter.' /> '.$option_values_arr[$i].'
							</label>
						</div>';
					}
					echo '</div>';
				}
			 endif;	
			}elseif($type=='select'){
			?>
			 <label><?php echo $site_title; ?></label>
				<select name="<?php echo $name;?>" id="<?php echo $name;?>" class="textfield textfield_x <?php echo $style_class;?>" <?php echo $extra_parameter;?>>
				<option value="">Please Select</option>
				<?php if($option_values){
				$option_values_arr = explode(',',$option_values);
				for($i=0;$i<count($option_values_arr);$i++)
				{
				?>
				<option value="<?php echo $option_values_arr[$i]; ?>" <?php if($value==$option_values_arr[$i]){ echo 'selected="selected"';} else if($default_value==$option_values_arr[$i]){ echo 'selected="selected"';}?>><?php echo $option_values_arr[$i]; ?></option>
				<?php	
				}
				?>
				<?php }?>
			   
				</select>
			<?php
			}
			elseif(!isset($_REQUEST['action']) && $type=='post_categories' && $tmpdata['templatic-category_custom_fields'] == 'No')
				{
				/* fetch catgories on action */ ?>
				<div class="form_row clearfix">
				  
						<label><?php echo $site_title; ?></label>
						 <div class="category_label"><?php require_once (TEMPL_MONETIZE_FOLDER_PATH.'templatic-custom_fields/category.php');?></div>
						 <?php echo $is_required_msg;?>
						 <span class="message_note msgcat"><?php _e(CATEGORY_MSG,DOMAIN);?></span>
					
					<div class="clearfix"></div>
				 </div>    
				<?php }
			else if($type=='upload'){ ?>
			 <label><?php echo $site_title; ?></label>
			 <input type="file" value="<?php echo $_SESSION['upload_file']; ?>" name="<?php echo $name; ?>" class="fileupload" id="<?php echo $name; ?>" />
			 <?php if($_REQUEST['pid']): ?>
				<p class="resumback"><a href="<?php echo get_post_meta($_REQUEST['pid'],$name, $single = true); ?>"><?php echo basename(get_post_meta($_REQUEST['pid'],$name, $single = true)); ?></a></p>
			 <?php elseif($_SESSION['upload_file']): ?>
				<p class="resumback"><a href="<?php echo $_SESSION['upload_file'][$name]; ?>"><?php echo basename($_SESSION['upload_file'][$name]); ?></a></p>
			 <?php endif; ?>
			<?php } 
			if($type != 'image_uploader' ) {?>
			   <?php if($admin_desc != ''): ?>
				   <?php if(@$_REQUEST['pid']): ?>
					 <span class="message_note msgcat submit"><?php echo $admin_desc;?></span>
				   <?php endif; ?>  
			   <?php endif; ?>  
			   <?php if($type!='geo_map') { ?>
				   <?php echo $is_required_msg;?>
			 <?php }} ?>
			<?php if($type == 'image_uploader' ) {?>
			 <div class="form_row clearfix">
				<?php include (TEMPL_MONETIZE_FOLDER_PATH."templatic-custom_fields/image_uploader.php"); ?>
				<span class="message_note"><?php echo $admin_desc;?></span>
                <span class="message_error2" id="post_images_error"></span>
			 </div>
			<?php } ?> 
		  <?php if($type=='geo_map') { ?>
			<div class="form_row clearfix">
				<label><?php _e('Address','supreme'); ?></label>
				<input type="text" name="address" id="address" value="<?php echo @$_REQUEST['address']; ?>"/>
				 <?php if($admin_desc):?>
				 <span class="message_note"><?php echo $admin_desc;?></span>
			<?php else:?>
					 <span class="message_note"><?php echo $GET_MAP_MSG;?></span>
			<?php endif; ?>
			 </div>
			 <?php } ?> 
			<div class="clearfix"></div>
		 </div>    
		<?php
		}
}


/* 
Name :display_custom_category_field_plugin
description : Returns cateegory custom fields html
*/
function display_custom_category_field_plugin($custom_metaboxes,$session_variable,$post_type){

	foreach($custom_metaboxes as $key=>$val) {
		$name = $val['name'];
		$site_title = $val['label'];
		$type = $val['type'];
		$htmlvar_name = $val['htmlvar_name'];
		$admin_desc = $val['desc'];
		$option_values = $val['option_values'];
		$default_value = $val['default'];
		$style_class = $val['style_class'];
		$extra_parameter = $val['extra_parameter'];
		if(!$extra_parameter){ $extra_parameter ='';}
		/* Is required CHECK BOF */
		$is_required = '';
		$input_type = '';
		if($val['is_require'] == '1'){
			$is_required = '<span class="required">*</span>';
			$is_required_msg = '<span id="'.$name.'_error" class="message_error2"></span>';
		} else {
			$is_required = '';
			$is_required_msg = '';
		}
		/* Is required CHECK EOF */
		if(@$_REQUEST['pid'])
		{
			$post_info = get_post($_REQUEST['pid']);
			if($name == 'post_title') {
				$value = $post_info->post_title;
			}else {
				$value = get_post_meta($_REQUEST['pid'], $name,true);
			}
			
		}else if(@$_SESSION[$session_variable] && @$_REQUEST['backandedit'])
		{
			$value = @$_SESSION[$session_variable][$name];
		}else{
			$value='';
		}
	   
	if(!isset($_REQUEST['action']) && $type=='post_categories')
	{ /* fetch catgories on action */ ?>
	<div class="form_row clearfix">
	  
			<label><?php echo $site_title.$is_required; ?></label>
             <div class="category_label"><?php require_once (TEMPL_MONETIZE_FOLDER_PATH.'templatic-custom_fields/category.php');?></div>
			 <?php echo $is_required_msg;?>
             <span class="message_note msgcat"><?php _e(CATEGORY_MSG,DOMAIN);?></span>
		
		<div class="clearfix"></div>
     </div>    
    <?php }
	}

}
/*
	Name : templ_get_selected_category_id
	Desc : get selected category ID
	*/
function templ_get_custom_categoryid($category_id){
		foreach($category_id as $_category_arr)
		{
			$category[] = explode(",",$_category_arr);
		}
		if(isset($category))
		foreach($category as $_category){
			$arr_category[] = $_category[0];
			$arr_category_price[] = $_category[1];
		}
		return $cat_array = $arr_category;	
}
/* 
Name :display_custom_category_name
description : Returns cateegory name in custom fields page.
*/
function display_custom_category_name($custom_metaboxes,$session_variable,$taxonomy){

	foreach($custom_metaboxes as $key=>$val) {
		$type = $val['type'];	
		$site_title = $val['label'];	
	?>
	
	   <?php if($type=='post_categories')
		{ 
		 ?>
		 <div class="form_row clearfix">
			<label><?php echo $site_title; ?></label>
             <div class="category_label">
			 <?php 
				 for($i=0;$i<count($session_variable);$i++)
				 {
					if($i == (count($session_variable) -1 ))
						$sep = '';
					else
						$sep = ',';
					$category_name = get_term_by('id', $session_variable[$i], $taxonomy);
					if($category_name)
					 {
						echo "<strong>".$category_name->name.$sep."</strong>";
					 }
				}
				if(isset($_SESSION['custom_fields']['cur_post_id']) && count($_SESSION['custom_fields']['cur_post_id']) > 0 && !isset($_REQUEST['cur_post_id']) && $_REQUEST['category'] == '')
					$id = $_SESSION['custom_fields']['cur_post_id'];
				elseif(isset($_REQUEST['cur_post_id']) && count($_REQUEST['cur_post_id']) > 0)
					$id = $_REQUEST['cur_post_id'];

				$permalink = get_permalink( $id );
		?></div>
		<?php
		/* Go back and edit link */
		if(strpos($permalink,'?'))
		{
			  if($_REQUEST['pid']){ $postid = '&amp;pid='.$_REQUEST['pid']; }
				 $gobacklink = $permalink."&backandedit=1&amp;".$postid;
		}else{
			if($_REQUEST['pid']){ $postid = '&amp;pid='.$_REQUEST['pid']; }
			$gobacklink = $permalink."?backandedit=1";
		}
			if(!isset($_REQUEST['pid'])){
			?>
			  <a href="<?php echo $gobacklink; ?>" class="btn_input_normal fl" ><?php echo GO_BACK_AND_EDIT_TEXT;?></a>
			<?php } ?>
		<div class="clearfix"></div>
		</div>   	
		<?php }	
	}
}


/* 
Name :display_custom_post_field_plugin
description : Returns all custom fields html
*/

function display_custom_post_field_plugin($custom_metaboxes,$session_variable,$post_type){
	$tmpdata = get_option('templatic_settings');
	foreach($custom_metaboxes as $heading=>$_custom_metaboxes)
	  {		 
		$activ = fetch_active_heading($heading);
		if($activ):
			$PostTypeObject = get_post_type_object($post_type);
			$PostTypeName = $PostTypeObject->labels->name;
			if($heading == '[#taxonomy_name#]' && $_custom_metaboxes)
			{
				
				
			?>	
            	<div class="sec_title"><h3><?php echo ucfirst($PostTypeName); ?><?php _e(' Information',DOMAIN); ?></h3></div>
			<?php
            }
			else
			{
				if($_custom_metaboxes){
				echo "<div class='sec_title'><h3>".$heading."</h3>";
				if($_custom_metaboxes['basic_inf']['desc']!=""){echo '<p>'.$_custom_metaboxes['basic_inf']['desc'].'</p>';}
				echo "</div>";
				}
			}
		endif;	
		foreach($_custom_metaboxes as $key=>$val) {
			$name = $val['name'];
			$site_title = $val['label'];
			$type = $val['type'];
			$htmlvar_name = $val['htmlvar_name'];			
			
			//set the post category , post title, post content, post image and post expert replace as per post type
			if($htmlvar_name=="category")
			{
				$site_title=str_replace('Post Category',ucfirst($PostTypeName).' Category',$site_title);
			}
			if($htmlvar_name=="post_title")
			{
				$site_title=str_replace('Post Title',ucfirst($PostTypeName).' Title',$site_title);
			}
			if($htmlvar_name=="post_content")
			{
				$site_title=str_replace('Post Content',ucfirst($PostTypeName).' Content',$site_title);
			}
			if($htmlvar_name=="post_excerpt")
			{
				$site_title=str_replace('Post Excerpt',ucfirst($PostTypeName).' Excerpt',$site_title);
			}
			if($htmlvar_name=="post_images")
			{
				$site_title=str_replace('Post Images',ucfirst($PostTypeName).' Images',$site_title);
			}
			//finish post type wise replace post category, post title, post content, post expert, post images
			$admin_desc = $val['desc'];
			$option_values = $val['option_values'];
			$default_value = $val['default'];
			$style_class = $val['style_class'];
			$extra_parameter = $val['extra_parameter'];
			if(!$extra_parameter){ $extra_parameter ='';}
			/* Is required CHECK BOF */
			$is_required = '';
			$input_type = '';
			if($val['validation_type'] != ''){
				if($val['is_require'] == '1'){
				$is_required = '<span class="required">*</span>';
				}
				
				$is_required_msg = '<span id="'.$name.'_error" class="message_error2"></span>';
			} else {
				$is_required = '';
				$is_required_msg = '';
			}
			/* Is required CHECK EOF */
			if(@$_REQUEST['pid'])
			{
				$post_info = get_post($_REQUEST['pid']);
				if($name == 'post_title') {
					$value = $post_info->post_title;
				}
				elseif($name == 'post_content') {
					$value = $post_info->post_content;
				}
				elseif($name == 'post_excerpt'){
					$value = $post_info->post_excerpt;
				}
				else {
					$value = get_post_meta($_REQUEST['pid'], $name,true);
				}
			
			}
			if(@$_SESSION[$session_variable] && @$_REQUEST['backandedit'])
			{
				$value = @$_SESSION[$session_variable][$name];
			}
			$value = apply_filters('SelectBoxSelectedOptions',$value,$name);
		?>
		<div class="form_row clearfix <?php echo $style_class;?>">
		   <?php if($type=='text'){?>
		   <label><?php echo $site_title.$is_required; ?></label>
		   <?php if($name == 'geo_latitude' || $name == 'geo_longitude') {
				$extra_script = 'onblur="changeMap();"';
				
			} else {
				$extra_script = '';
				
			}?>
             <?php do_action('tmpl_custom_fields_'.$name.'_before'); ?>
			 <input name="<?php echo $name;?>" id="<?php echo $name;?>" value="<?php if(isset($value)){ echo stripslashes($value); } else { echo @$val['default']; } ?>" type="text" class="textfield <?php echo $style_class;?>" <?php echo $extra_parameter; ?> <?php echo $extra_script;?> />
              <?php echo $is_required_msg;?>
			 	<?php if($admin_desc!=""):?><div class="description"><?php echo $admin_desc; ?></div><?php endif;?>
             <?php do_action('tmpl_custom_fields_'.$name.'_after'); ?>
			<?php
			}elseif($type=='date'){
				//jquery data picker			
			?>     
				<script type="text/javascript">
					jQuery(function(){
						var pickerOpts = {						
							showOn: "both",
							dateFormat: 'yy-mm-dd',
							buttonImage: "<?php echo TEMPL_PLUGIN_URL;?>css/datepicker/images/cal.png",
							buttonText: "Show Datepicker",
							buttonImageOnly: true,
							onChangeMonthYear: function(year, month, inst) {
							  	jQuery("#<?php echo $name;?>").blur();
						     },
						     onSelect: function(dateText, inst) {
							   //jQuery("#<?php echo $name;?>").focusin();
							     jQuery("#<?php echo $name;?>").blur();
						     }
						};	
						jQuery("#<?php echo $name;?>").datepicker(pickerOpts);
					});
				</script>
				<label><?php echo $site_title.$is_required; ?></label>
                <?php do_action('tmpl_custom_fields_'.$name.'_before'); ?>
				<input type="text" name="<?php echo $name;?>" id="<?php echo $name;?>" class="textfield <?php echo $style_class;?>" value="<?php echo esc_attr(stripslashes($value)); ?>" size="25" <?php echo 	$extra_parameter;?> />
				 <?php echo $is_required_msg;?>
				<?php if($admin_desc!=""):?><div class="description"><?php echo $admin_desc; ?></div><?php endif;?>
                <?php do_action('tmpl_custom_fields_'.$name.'_after'); ?>	          
			<?php
			}
			elseif($type=='multicheckbox')
			{ ?>
			 <label><?php echo $site_title.$is_required; ?></label>
			<?php
				$options = $val['option_values'];
				if(!isset($_REQUEST['pid']) && !$_REQUEST['backandedit'])
				{
					$default_value = explode(",",$val['default']);
				}
	
				if($options)
				{  
					$chkcounter = 0;
					echo '<div class="form_cat_left">';
					do_action('tmpl_custom_fields_'.$name.'_before');
					$option_values_arr = explode(',',$options);
					for($i=0;$i<count($option_values_arr);$i++)
					{
						$chkcounter++;
						$seled='';
						if(isset($_REQUEST['pid']) || $_REQUEST['backandedit'])
						  {
							$default_value = $value;
						  }
						if($default_value !=''){
						if(in_array($option_values_arr[$i],$default_value)){ 
						$seled='checked="checked"';} }	
											
						echo '
						<div class="form_cat">
							<label>
								<input name="'.$key.'[]"  id="'.$key.'_'.$chkcounter.'" type="checkbox" value="'.$option_values_arr[$i].'" '.$seled.'  '.$extra_parameter.' /> '.$option_values_arr[$i].'
							</label>
						</div>';
					}
					echo '</div>';
					?>
                     <?php echo $is_required_msg;?>
					<?php if($admin_desc!=""):?><div class="description"><?php echo $admin_desc; ?></div><?php endif;?>
					<?php
					do_action('tmpl_custom_fields_'.$name.'_after');
				}
			}		
			elseif($type=='texteditor'){	?>
				<label><?php echo $site_title.$is_required; ?></label>
                <?php do_action('tmpl_custom_fields_'.$name.'_before'); ?>
				<?php
					// default settings
					$settings =   array(
						'wpautop' => true, // use wpautop?
						'media_buttons' => false, // show insert/upload button(s)
						'textarea_name' => $name, // set the textarea name to something different, square brackets [] can be used here
						'textarea_rows' => '10', // rows="..."
						'tabindex' => '',
						'editor_css' => '<style>.wp-editor-wrap{width:640px;margin-left:0px;}</style>', // intended for extra styles for both visual and HTML editors buttons, needs to include the <style> tags, can use "scoped".
						'editor_class' => '', // add extra class(es) to the editor textarea
						'teeny' => false, // output the minimal editor config used in Press This
						'dfw' => false, // replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)
						'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
						'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
					);				
					if(isset($value) && $value != '') 
					{  $content=$value; }
					else{$content= $val['default']; } 				
					wp_editor( stripslashes($content), $name, $settings);
				?>
                 <?php echo $is_required_msg;?>
				<?php if($admin_desc!=""):?><div class="description"><?php echo $admin_desc; ?></div><?php endif;?>
                <?php do_action('tmpl_custom_fields_'.$name.'_after'); ?>
			<?php
			}elseif($type=='textarea'){ 
			?>
                <label><?php echo $site_title.$is_required; ?></label>
                <?php do_action('tmpl_custom_fields_'.$name.'_before'); ?>
                <textarea name="<?php echo $name;?>" id="<?php echo $name;?>" class="<?php if($style_class != '') { echo $style_class;}?> textarea" <?php echo $extra_parameter;?>><?php if(isset($value))echo stripslashes($value);?></textarea>
               	 <?php echo $is_required_msg;?>
                <?php if($admin_desc!=""):?><div class="description"><?php echo $admin_desc; ?></div><?php endif;?>
                <?php do_action('tmpl_custom_fields_'.$name.'_after'); ?>
			<?php
			}elseif($type=='radio'){
			?>
			<?php if($name != 'position_filled' || @$_REQUEST['pid']): ?>
			 <label class="r_lbl"><?php echo $site_title.$is_required; ?></label>
            <?php do_action('tmpl_custom_fields_'.$name.'_before'); ?>
			<?php
				$options = $val['option_values'];
				if($options)
				{  $chkcounter = 0;
					echo '<div class="form_cat_left">';
					$option_values_arr = explode(',',$options);
					for($i=0;$i<count($option_values_arr);$i++)
					{
						$chkcounter++;
						$seled='';
						if($default_value == $option_values_arr[$i]){ $seled='checked="checked"';}
						if (isset($value) && trim($value) == trim($option_values_arr[$i])){ $seled='checked="checked"';}
						echo '<div class="form_cat">
									<label class="r_lbl">
										<input name="'.$key.'"  id="'.$key.'_'.$chkcounter.'" type="radio" value="'.$option_values_arr[$i].'" '.$seled.'  '.$extra_parameter.' /> '.$option_values_arr[$i].'
									</label>
								</div>';
					}
					echo '</div>';
				}
				?>
                 <?php echo $is_required_msg;?>
				<?php if($admin_desc!=""):?><div class="description"><?php echo $admin_desc; ?></div><?php endif;?>
				<?php
			 do_action('tmpl_custom_fields_'.$name.'_after');
			 endif;	
			}elseif($type=='select'){
			?>
			 <label><?php echo $site_title.$is_required; ?></label>
				<?php do_action('tmpl_custom_fields_'.$name.'_before'); ?>
                <select name="<?php echo $name;?>" id="<?php echo $name;?>" class="textfield textfield_x <?php echo $style_class;?>" <?php echo $extra_parameter;?>>
				<option value="">Please Select</option>
				<?php if($option_values){
				//$option_values_arr = explode(',',$option_values);
				$option_values_arr = apply_filters('SelectBoxOptions',explode(',',$option_values),$name);
				for($i=0;$i<count($option_values_arr);$i++)
				{
				?>
				<option value="<?php echo $option_values_arr[$i]; ?>" <?php if($value==$option_values_arr[$i]){ echo 'selected="selected"';} else if($default_value==$option_values_arr[$i]){ echo 'selected="selected"';}?>><?php echo $option_values_arr[$i]; ?></option>
				<?php	
				}
				?>
				<?php }?>
			   
				</select>
                 <?php echo $is_required_msg;?>
				<?php if($admin_desc!=""):?><div class="description"><?php echo $admin_desc; ?></div><?php endif;?>
                <?php do_action('tmpl_custom_fields_'.$name.'_after'); ?>
			<?php
			}
			elseif(!isset($_REQUEST['action']) && $type=='post_categories' && $tmpdata['templatic-category_custom_fields'] == 'No')
				{
				/* fetch catgories on action */ ?>
				<div class="form_row clearfix">
				  
						<label><?php echo $site_title.$is_required; ?></label>
						 <div class="category_label"><?php require_once (TEMPL_MONETIZE_FOLDER_PATH.'templatic-custom_fields/category.php');?></div>
						 <?php echo $is_required_msg;?>
						 <span class="message_note msgcat"><?php _e(CATEGORY_MSG,DOMAIN);?></span>					
				 </div>    
				<?php }
			else if($type=='upload'){ ?>
			 <label><?php echo $site_title.$is_required; ?></label>
             <?php do_action('tmpl_custom_fields_'.$name.'_before'); ?>
			 <input type="file" value="<?php echo $_SESSION['upload_file']; ?>" name="<?php echo $name; ?>" class="fileupload" id="<?php echo $name; ?>" />
             <?php do_action('tmpl_custom_fields_'.$name.'_after'); ?>
			 <?php if($_REQUEST['pid']): ?>
				<p class="resumback"><a href="<?php echo get_post_meta($_REQUEST['pid'],$name, $single = true); ?>"><?php echo basename(get_post_meta($_REQUEST['pid'],$name, $single = true)); ?></a></p>
			 <?php elseif($_SESSION['upload_file'] && @$_REQUEST['backandedit']): ?>
				<p class="resumback"><a href="<?php echo $_SESSION['upload_file'][$name]; ?>"><?php echo basename($_SESSION['upload_file'][$name]); ?></a></p>
			 <?php endif; ?>
             <?php echo $is_required_msg;?>
			<?php } 
		
			if($type == 'image_uploader' ) {?>
			 	<label><?php echo $site_title ?></label>
				<?php include (TEMPL_MONETIZE_FOLDER_PATH."templatic-custom_fields/image_uploader.php"); ?>
				<span class="message_note"><?php echo $admin_desc;?></span>
                <span class="message_error2" id="post_images_error"></span>
			<?php } ?> 
		  <?php if($type=='geo_map') { ?>
			 <?php include_once(TEMPL_MONETIZE_FOLDER_PATH."templatic-custom_fields/location_add_map.php"); ?>
			<?php if($admin_desc):?>
				 <span class="message_note"><?php echo $admin_desc;?></span>
			<?php else:?>
					 <span class="message_note"><?php echo $GET_MAP_MSG;?></span>
			<?php endif; ?>
			<?php } ?>
			<div class="clearfix"></div>
		 </div>    
		<?php
		}
	}
}

/* 
Name :ptthemes_taxonomy_meta_box
description : Function to add metaboxes in taxonomies BOF
*/

if(!function_exists('ptthemes_taxonomy_meta_box')){
	function ptthemes_taxonomy_meta_box() {
		$custom_post_types_args = array();  
		$custom_post_types = get_post_types($custom_post_types_args,'objects');
		foreach ($custom_post_types as $content_type) 
		{
			if($content_type->name!='nav_menu_item' && $content_type->name!='attachment' && $content_type->name!='revision' && $content_type->name!='page')
			{
				$post_types=$content_type->name;

				if ( function_exists('add_meta_box')) {
					apply_filters('templ_admin_post_type_custom_filter',add_meta_box('ptthemes-settings',apply_filters('templ_admin_post_custom_fields_title_filter','Custom Settings'),'tvolution_custom_meta_box_content',$post_types,'normal','high',array( 'post_types' => $post_types)));
				}
			}
		}
	
		
		add_meta_box("post_type_meta", "Post type options", "post_type_meta", "page", "side", "default");
		add_meta_box("map_page_option", "Map Page Options", "map_page_option", "page", "normal", "default");
		/* - Code to add meta box for page template - */
	}	
}

/* 
Name :ptthemes_taxonomy_metabox_insert
description : Function to add metaboxes BOF
*/

if(!function_exists('ptthemes_taxonomy_metabox_insert')){
function ptthemes_taxonomy_metabox_insert($post_id) {
    global $globals,$wpdb,$post;
	if(is_templ_wp_admin() && isset($_POST['template_post_type']) && $_POST['template_post_type'] != '')
	{
		update_post_meta(@$_POST['post_ID'], 'template_post_type', @$_POST['template_post_type']);
	}
	// store map template option data
	if(is_templ_wp_admin() && isset($_POST['map_image_size']))			
		update_post_meta($_POST['post_ID'], 'map_image_size', $_POST['map_image_size']);
	if(is_templ_wp_admin() && isset($_POST['map_width']))			
		update_post_meta($_POST['post_ID'], 'map_width', $_POST['map_width']);
	if(is_templ_wp_admin() && isset($_POST['map_height']))			
		update_post_meta($_POST['post_ID'], 'map_height', $_POST['map_height']);
	if(is_templ_wp_admin() && isset($_POST['map_center_latitude']))			
		update_post_meta($_POST['post_ID'], 'map_center_latitude', $_POST['map_center_latitude']);
	if(is_templ_wp_admin() && isset($_POST['map_center_longitude']))
		update_post_meta($_POST['post_ID'], 'map_center_longitude', $_POST['map_center_longitude']);
	if(is_templ_wp_admin() && isset($_POST['map_type']))
		update_post_meta($_POST['post_ID'], 'map_type', $_POST['map_type']);
	if(is_templ_wp_admin() && isset($_POST['map_display']))
		update_post_meta($_POST['post_ID'], 'map_display', $_POST['map_display']);
	if(is_templ_wp_admin() && isset($_POST['map_zoom_level']))
		update_post_meta($_POST['post_ID'], 'map_zoom_level', $_POST['map_zoom_level']);
	if(is_templ_wp_admin() && isset($_POST['zooming_factor']))
		update_post_meta($_POST['post_ID'], 'zooming_factor', $_POST['zooming_factor']);

	//
	// verify nonce
    if (!wp_verify_nonce(@$_POST['templatic_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    $pt_metaboxes = get_post_admin_custom_fields_templ_plugin($_POST['post_type']);
    $pID = $_POST['post_ID'];
    $counter = 0;

	
    foreach ($pt_metaboxes as $pt_metabox) { // On Save.. this gets looped in the header response and saves the values submitted
    if($pt_metabox['type'] == 'text' OR $pt_metabox['type'] == 'select' OR $pt_metabox['type'] == 'checkbox' OR $pt_metabox['type'] == 'textarea' OR $pt_metabox['type'] == 'radio'  OR $pt_metabox['type'] == 'upload' OR $pt_metabox['type'] == 'date' OR $pt_metabox['type'] == 'multicheckbox' OR $pt_metabox['type'] == 'geo_map' OR $pt_metabox['type'] == 'texteditor') // Normal Type Things...
        {
			
            $var = $pt_metabox["name"];
			
			if($pt_metabox['type'] == 'geo_map'){ 
				update_post_meta($pID, 'address', $_POST['address']);
				update_post_meta($pID, 'geo_latitude', $_POST['geo_latitude']);
				update_post_meta($pID, 'geo_longitude', $_POST['geo_longitude']);
			}
			if( get_post_meta( $pID, $pt_metabox["name"] ) == "" )
			  {
				add_post_meta($pID, $pt_metabox["name"], $_POST[$var], true );
			  }
			elseif($_POST[$var] != get_post_meta($pID, $pt_metabox["name"], true))
			  {
				update_post_meta($pID, $pt_metabox["name"], $_POST[$var]);
			  }
			elseif($_POST[$var] == "")
			  {
				delete_post_meta($pID, $pt_metabox["name"], get_post_meta($pID, $pt_metabox["name"], true));
			  }
            elseif($_POST['featured_type'] != get_post_meta($pID, 'featured_type', true)){
				if($_POST['featured_type']):
					if($_POST['featured_type'] == 'both'):
						 update_post_meta($pID, 'featured_c', 'c');
						 update_post_meta($pID, 'featured_h', 'h');
						 update_post_meta($pID, 'featured_type', $_POST['featured_type']);
					endif;
					if($_POST['featured_type'] == 'c'):
						 update_post_meta($pID, 'featured_c', 'c');
						 update_post_meta($pID, 'featured_h', 'n');
						 update_post_meta($pID, 'featured_type', $_POST['featured_type']);
					endif;	 
					if($_POST['featured_type'] == 'h'):
						 update_post_meta($pID, 'featured_h', 'h');
						 update_post_meta($pID, 'featured_c', 'n');
						 update_post_meta($pID, 'featured_type', $_POST['featured_type']);
					endif;
					if($_POST['featured_type'] == 'none'):
						 update_post_meta($pID, 'featured_h', 'n');
						 update_post_meta($pID, 'featured_c', 'n');
						 update_post_meta($pID, 'featured_type', $_POST['featured_type']);
					endif;	 
				else:
					 update_post_meta($pID, 'featured_type', 'none');
					 update_post_meta($pID, 'featured_c', 'n');
					 update_post_meta($pID, 'featured_h', 'n');
				endif;
					}
			elseif($_POST['alive_days'] != ''){
				update_post_meta($pID, 'alive_days', $_POST['alive_days']);
			}
        } 
    }
	
}

}
/* - Function to add metaboxes EOF - */

/* - Function to fetch the contents in metaboxes BOF - */
if(!function_exists('ptthemes_meta_box_content')){
function tvolution_custom_meta_box_content($post, $metabox ) {
	$pt_metaboxes = get_post_admin_custom_fields_templ_plugin($metabox['args']['post_types'],'','admin_side');
	$post_id = $post->ID;
    $output = '';
    if($pt_metaboxes){
		if(get_post_meta($post_id,'remote_ip',true)  != ""){
			$remote_ip = get_post_meta($post_id,'remote_ip',true);
		} else {
			$remote_ip= getenv("REMOTE_ADDR");
		}
		if(get_post_meta($post_id,'ip_status',true)  != ""){
			$ip_status = get_post_meta($post_id,'ip_status',true);
		} else {
			$ip_status= '0';
		}
		$geo_latitude= get_post_meta($post_id,'geo_latitude',true);
		$geo_longitude= get_post_meta($post_id,'geo_longitude',true);
		$zooming_factor= get_post_meta($post_id,'zooming_factor',true);
	   echo '<table id="tvolution_fields" style="width:100%"  class="form-table">'."\n";  
	   echo '<input type="hidden" name="templatic_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />
	   <input type="hidden" name="remote_ip" value="'.$remote_ip.'" />
	   <input type="hidden" name="zooming_factor" id="zooming_factor" value="'.$zooming_factor.'" />
	   <input type="hidden" name="ip_status" value="'.$ip_status.'" />';
	   foreach ($pt_metaboxes as $pt_id => $pt_metabox) {
		if($pt_metabox['type'] == 'text' OR $pt_metabox['type'] == 'select' OR $pt_metabox['type'] == 'radio' OR $pt_metabox['type'] == 'checkbox' OR $pt_metabox['type'] == 'textarea' OR $pt_metabox['type'] == 'upload' OR $pt_metabox['type'] == 'date' OR $pt_metabox['type'] == 'multicheckbox' OR $pt_metabox['type'] == 'texteditor')
				$pt_metaboxvalue = get_post_meta($post_id,$pt_metabox["name"],true);
				if ($pt_metaboxvalue == ""  ) {
					$pt_metaboxvalue = $pt_metabox['default'];
				}
				if($pt_metabox['type'] == 'text'){
					if($pt_metabox["name"] == 'geo_latitude' || $pt_metabox["name"] == 'geo_longitude') {
						$extra_script = 'onblur="changeMap();"';
					} else {
						$extra_script = '';
					}
					echo  '<tr>';
					echo  '<th><label for="'.$pt_id.'">'.$pt_metabox['label'].'</label>'."</th>";
					echo "<td>";
					do_action('tmpl_custom_fields_'.$pt_metabox["name"].'_before');
					echo  '<input size="100" class="regular-text pt_input_text" type="'.$pt_metabox['type'].'" value="'.$pt_metaboxvalue.'" name="'.$pt_metabox["name"].'" id="'.$pt_id.'" '.$extra_script.'/>'."\n";
					do_action('tmpl_custom_fields_'.$pt_metabox["name"].'_after');
					echo  '<p class="description">'.$pt_metabox['desc'].'</p>';
					echo '</td></tr>';							  
				}
				
				elseif ($pt_metabox['type'] == 'textarea'){
							
					echo  "<tr>";
					echo  '<th><label for="'.$pt_id.'">'.$pt_metabox['label'].'</label></th>';
					echo "<td>";
					do_action('tmpl_custom_fields_'.$pt_metabox["name"].'_before');
					echo  '<textarea rows="5" cols="98" class="pt_input_textarea" name="'.$pt_metabox["name"].'" id="'.$pt_id.'">' . $pt_metaboxvalue . '</textarea>';
					do_action('tmpl_custom_fields_'.$pt_metabox["name"].'_after');
					echo  '<p class="description">'.$pt_metabox['desc'].'</p>';
					echo  "</td></tr>";
								  
				}
				
				elseif ($pt_metabox['type'] == 'texteditor'){
							
					echo  "<tr>";
					echo  '<th><label for="'.$pt_id.'">'.$pt_metabox['label'].'</th>';
					echo "<td>";
					do_action('tmpl_custom_fields_'.$pt_metabox["name"].'_before');
					echo  '<textarea rows="5" cols="98" class="pt_input_textarea" name="'.$pt_metabox["name"].'" id="'.$pt_id.'">' . $pt_metaboxvalue . '</textarea>';
					do_action('tmpl_custom_fields_'.$pt_metabox["name"].'_after');
					echo  '<p class="description">'.$pt_metabox['desc'].'</p>'."\n";
					echo  '</td></tr>'."\n";
								  
				}

				elseif ($pt_metabox['type'] == 'select'){
					echo "<tr>";
					echo  '<th><label for="'.$pt_id.'">'.$pt_metabox['label'].'</label></th>';
					echo "<td>";
					do_action('tmpl_custom_fields_'.$pt_metabox["name"].'_before');
					echo  '<select class="pt_input_select" id="'.$pt_id.'" name="'. $pt_metabox["name"] .'">';
					echo  '<option value="">Select a '.$pt_metabox['label'].'</option>';
					$array = $pt_metabox['options'];
					if($array){
						foreach ( $array as $id => $option ) {
							$selected = '';
							if($pt_metabox['default'] == $option){$selected = 'selected="selected"';} 
							if($pt_metaboxvalue == $option){$selected = 'selected="selected"';}
							echo  '<option value="'. $option .'" '. $selected .'>' . $option .'</option>';
						}
					}
					echo  '</select><p class="description">'.$pt_metabox['desc'].'</p>'."\n";
					do_action('tmpl_custom_fields_'.$pt_metabox["name"].'_after');
					echo  "</td></tr>";
				}
				elseif ($pt_metabox['type'] == 'multicheckbox'){
					
						echo  '<tr>';
						echo  '<th><label for="'.$pt_id.'">'.$pt_metabox['label'].'</label></th>';
						echo "<td>";
						 $array = $pt_metabox['options'];
						do_action('tmpl_custom_fields_'.$pt_metabox["name"].'_before'); 
						if($array){
							foreach ( $array as $id => $option ) {
							   
								$checked='';
								if(is_array($pt_metaboxvalue)){
								$fval_arr = $pt_metaboxvalue;
								if(in_array($option,$fval_arr)){ $checked='checked=checked';}
								}elseif($pt_metaboxvalue !='' && !is_array($pt_metaboxvalue)){ 
								$fval_arr[] = array($pt_metaboxvalue,'');
								
								if(in_array($option,$fval_arr[0])){ $checked='checked=checked';}
								}else{
								$fval_arr = $pt_metabox['default'];
								if(is_array($fval_arr)){
								if(in_array($option,$fval_arr)){$checked = 'checked=checked';}  }
								}
								echo  "\t\t".'<div class="multicheckbox"><input type="checkbox" '.$checked.' class="pt_input_radio" value="'.$option.'" name="'. $pt_metabox["name"] .'[]" />  ' . $option .'</div>'."\n";
							}
						}
						do_action('tmpl_custom_fields_'.$pt_metabox["name"].'_after');
						echo  '<p class="description">'.$pt_metabox['desc'].'</p>'."\n";
						echo  '</td></tr>';
				}
				 elseif ($pt_metabox['type'] == 'date'){
					 
					 ?>
					 <script type="text/javascript">	
						jQuery(function(){
						var pickerOpts = {
								showOn: "both",
								dateFormat: 'yy-mm-dd',
								buttonImage: "<?php echo TEMPL_PLUGIN_URL; ?>css/datepicker/images/cal.png",
								buttonText: "Show Datepicker"
							};	
							jQuery("#<?php echo $pt_metabox["name"];?>").datepicker(pickerOpts);
						});
					</script>
					 <?php
							
					echo  '<tr>';
					echo  '<th><label for="'.$pt_id.'">'.$pt_metabox['label'].'</label></th>';
					echo "<td>";
					do_action('tmpl_custom_fields_'.$pt_metabox["name"].'_before');
					echo  '<input size="40" class="pt_input_text" type="text" value="'.$pt_metaboxvalue.'" id="'.$pt_metabox["name"].'" name="'.$pt_metabox["name"].'"/>';
					do_action('tmpl_custom_fields_'.$pt_metabox["name"].'_after');
					
					echo  '<p class="description">'.$pt_metabox['desc'].'</p>';
					echo  '</td></tr>';
								  
				}
				elseif ($pt_metabox['type'] == 'radio'){
						echo  '<tr>';
						echo  '<th><label for="'.$pt_id.'">'.$pt_metabox['label'].'</label></th>';
						$array = $pt_metabox['options'];
						echo '<td>';
						do_action('tmpl_custom_fields_'.$pt_metabox["name"].'_before'); 
						$i=1;
						if($array){
							foreach ( $array as $id => $option ) {
							   $checked='';
							   if($pt_metabox['default'] == $option){$checked = 'checked="checked"';} 
								if(trim($pt_metaboxvalue) == trim($option)){$checked = 'checked="checked"';}
								echo  "\t\t".'<div class="input_radio"><input type="radio" '.$checked.' class="pt_input_radio" value="'.$option.'" name="'. $pt_metabox["name"] .'" id="'. $pt_metabox["name"].'_'.$i .'" />  ' . $option .'</div>'."\n";
								$i++;
							}
						}
						do_action('tmpl_custom_fields_'.$pt_metabox["name"].'_after');
						echo  '<p class="description">'.$pt_metabox['desc'].'</p>'."\n";
						echo "</td>";
						echo  '</tr>';
				}
				elseif ($pt_metabox['type'] == 'checkbox'){
					if($pt_metaboxvalue == '1') { $checked = 'checked="checked"';} else {$checked='';}
					echo  "<tr>";
					echo  '<th><label for="'.$pt_id.'">'.$pt_metabox['label'].'</label></th>';
					echo "<td>";
					do_action('tmpl_custom_fields_'.$pt_metabox["name"].'_before');
					//echo  '<p class="value"><input type="checkbox" '.$checked.' class="pt_input_checkbox"  id="'.$pt_id.'" value="1" name="'. $pt_metabox["name"] .'" /></p>';
					echo  '<p class="value"><input id="'. $pt_metabox["name"] .'" type="text" size="36" name="'.$pt_metabox["name"].'" value="'.$pt_metaboxvalue.'" />';
	                echo  '<input id="'. $pt_metabox["name"] .'_button" type="button" value="Browse Logo" /></p>';
					do_action('tmpl_custom_fields_'.$pt_metabox["name"].'_after');
					echo  '<p class="description">'.$pt_metabox['desc'].'</p>'."\n";
					echo  '</td></tr>'."\n";
				}elseif ($pt_metabox['type'] == 'upload'){
				   $pt_metaboxvalue = get_post_meta($post->ID,$pt_metabox["name"],true);
				   if($pt_metaboxvalue!=""):
						$up_class="upload ".$pt_metaboxvalue;
						echo  '<tr>';
			
						echo  '<th><label for="'.$pt_id.'">'.$pt_metabox['label'].'</label></th>';
						//echo  '<td><input type="file" class="'.$up_class.'"  id="'. $pt_metabox["name"] .'" name="'. $pt_metabox["name"] .'" value="'.$pt_metaboxvalue.'"/>';
						echo  '<td><input id="'. $pt_metabox["name"] .'" type="text" size="36" name="'.$pt_metabox["name"].'" value="'.$pt_metaboxvalue.'" />';
		                echo  '<input id="'. $pt_metabox["name"] .'_button" type="button" value="Browse Logo" />';
						echo  '<p><a href="'.$pt_metaboxvalue.'">'.basename($pt_metaboxvalue).'</a></p>'."\n";
						echo  '<p class="description">'.$pt_metabox['desc'].' </p>';
						$dirinfo = wp_upload_dir();
						$path = $dirinfo['path'];
						$url = $dirinfo['url'];
						echo '<img src="'.get_post_meta($post->ID,$pt_metabox["name"], $single = true).'" border="0" class="company_logo" height="140" width="140" />';
						echo  '</td></tr>';

				   else:
					$up_class="upload has-file";
					echo  '<tr>';

					echo  '<th><label for="'.$pt_id.'">'.$pt_metabox['label'].'</label></th>';
						//echo  '<td><input type="file" class="'.$up_class.'"  id="'. $pt_metabox["name"] .'" name="'. $pt_metabox["name"] .'" value="'.$pt_metaboxvalue.'"/>';
						echo  '<td><input id="'. $pt_metabox["name"] .'" type="text" size="36" name="'.$pt_metabox["name"].'" value="'.$pt_metaboxvalue.'" />';
		                echo  '<input id="'. $pt_metabox["name"] .'_button" type="button" value="Browse Logo" />';
						echo  '<p><a href="'.$pt_metaboxvalue.'">'.basename($pt_metaboxvalue).'</a></p>'."\n";
						echo  '<p class="description">'.$pt_metabox['desc'].' </p>';
						echo  '</td></tr>'."\n";
				  endif;		
				}else {
				if($pt_metabox['type'] == 'geo_map'){
					echo  '<tr>';
					echo '<td colspan=2 id="tvolution_map">';
					include_once(TEMPL_MONETIZE_FOLDER_PATH . "templatic-custom_fields/location_add_map.php");
					if($admin_desc):
				 		echo '<p class="description">'.$admin_desc.'</p>'."\n";
					else:
					 	echo '<p class="description">'.$GET_MAP_MSG.'</p>'."\n";
					endif;

					 echo  '</td> </tr>';
				}
				}
			}
		
		global $post_type;		
		do_action('tevolution_featured_list',$post->ID);		 
		
		echo "</tbody>";
		echo "</table>";
	}else{
		echo "No custom fields was inserted for this post type."."<a href='".site_url('wp-admin/admin.php?page=custom_fields')."'> Click Here </a> to add fields for this post.";
	}
}
}


/* action to add option of featured listing in add listing page in wp-admin */

function tevolution_featured_list_fn($post_id){
	global $post;
	
	if(get_post_meta($post_id,'featured_type',true) == "h"){ $checked = "checked=checked"; }
	elseif(get_post_meta($post_id,'featured_type',true) == "c"){ $checked1 = "checked=checked"; }
	elseif(get_post_meta($post_id,'featured_type',true) == "both"){ $checked2 = "checked=checked"; }
	elseif(get_post_meta($post_id,'featured_type',true) == "none"){ $checked3 = "checked=checked"; }
	else { $checked = ""; }
	if(get_post_meta($post_id,'alive_days',true) != '')
	 {
		$alive_days = get_post_meta($post_id,'alive_days',true);	 
	 }
	echo '<tr>';
	echo '<th><label for="featured_type">'.FEATURED_LISTING_LABEL.'</th>';
	echo '<td><p><input size="100" type="radio" '.$checked.' value="h" name="featured_type"/>&nbsp; Featured for home page</p>';
	echo '<p><input size="100" type="radio"   '.@$checked1.' value="c" name="featured_type"/>&nbsp; Featured for category page</p>';
	echo '<p><input size="100" type="radio"   '.@$checked2.' value="both" name="featured_type"/>&nbsp; Both</p>';
	echo  '<p><input size="100" type="radio"  '.@$checked3.' value="none" name="featured_type" />&nbsp; None of above</p>';
	echo '</td></tr>';
	
	
	
	echo '<tr>';
	echo  '<th><label for="alive_days">'.SET_ALIVE_DAYS_LABEL.'</label></th>';
	echo  '<td><input type="text" value="'.$alive_days.'" class="regular-text pt_input_text" name="alive_days" id="alive_days" size="100" /></td>';
	echo '</tr>';
}
/* - Function to fetch the contents in metaboxes EOF - */

add_action('admin_menu', 'ptthemes_taxonomy_meta_box');
add_action('save_post', 'ptthemes_taxonomy_metabox_insert');

/* -Add metabox for page - */
function post_type_meta(){ 
 ?>
	<script type="text/javascript">
	jQuery.noConflict(); 
	jQuery(document).ready(function() {
	if(jQuery("#page_template").val() !='page-template_form.php' && jQuery("#page_template").val() !='page-template_map.php' && jQuery("#page_template").val() !='page-template_advanced_search.php' && jQuery("#page_template").val() !='page-template-archives.php'){
		jQuery("#post_type_meta").css('display','none');
	}else{
		jQuery("#post_type_meta").css('display','block');
	}
	
    jQuery("#page_template").change(function() {
        var src = jQuery(this).val();
			if(jQuery("#page_template").val() =='page-template_form.php' || jQuery("#page_template").val() =='tpl_archives.php' || jQuery("#page_template").val() =='page-template_map.php' || jQuery("#page_template").val() =='page-template_advanced_search.php' || jQuery("#page_template").val() =='page-template-archives.php'){
			jQuery("#post_type_meta").fadeIn(2000); }else{
			jQuery("#post_type_meta").fadeOut(2000);
			}
		});
	});
	</script>
<?php
		$custom_post_types = get_post_types();  		
		global $post;
		foreach ($custom_post_types as $content_type) 
		{
			if($content_type!='nav_menu_item' && $content_type!='attachment' && $content_type!='revision' && $content_type!='page')
			{
			$template_post_type = get_post_meta($post->ID,'template_post_type',true);
			if($template_post_type == $content_type){ $c = 'checked=checked';}else{ $c=''; }
			echo "<input type='radio' name='template_post_type' id='".$content_type."' value='".$content_type."' $c/> ".ucfirst($content_type)."<br/>"; }
		}	
}

/*
 * Add meta box or map template
 */
function map_page_option()
{
	?>
	<script type="text/javascript">
	jQuery.noConflict(); 
	jQuery(document).ready(function() {		
	if(jQuery("#page_template").val() !='page-template_map.php'){
		jQuery("#map_page_option").css('display','none');
	}else{
		jQuery("#map_page_option").css('display','block');
	}
	
    jQuery("#page_template").change(function() {
        var src = jQuery(this).val();
			if(jQuery("#page_template").val() =='page-template_map.php'){
				jQuery("#map_page_option").fadeIn(500);				
			}else{				
				jQuery("#map_page_option").fadeOut(500);
			}
		});
	});
	</script>
    <?php
	global $post;	
	$map_image_size = get_post_meta($post->ID,'map_image_size',true);
	$map_center_latitude = get_post_meta($post->ID,'map_center_latitude',true);
	$map_center_longitude = get_post_meta($post->ID,'map_center_longitude',true);
	$map_type = get_post_meta($post->ID,'map_type',true);
	$map_display = get_post_meta($post->ID,'map_display',true);
	$map_zoom_level = get_post_meta($post->ID,'map_zoom_level',true);
	?>
    <table >   
    		<tr>
          	<th>
				<label for="map_image_size"><?php _e('Map popup Image Size', DOMAIN); ?>:</label>
			     <?php $sizes = get_additional_image_sizes(); ?>               
               </th>
               <td>
                  <select id="map_image_size" name="map_image_size">
                      <option style="padding-right:10px;" value="thumbnail">thumbnail (<?php echo get_option('thumbnail_size_w'); ?>x<?php echo get_option('thumbnail_size_h'); ?>)</option>
                      <?php
                      foreach((array)$sizes as $name => $size) :
				  	$selected='';
				  	if($name==$map_image_size) $selected="selected";
                      echo '<option style="padding-right: 10px;" value="'.esc_attr($name).'" '.$selected.'>'.esc_html($name).' ('.$size['width'].'x'.$size['height'].')</option>';
                      endforeach;
                      ?>
                  </select>
			</td>
          </tr>  		
    	    <tr valign="top">
            <th><label><?php _e("Map Center Latitude",DOMAIN);?></label></th>
            <td>
                <input type="text" name="map_center_latitude" value="<?php if(isset($map_center_latitude)) { echo $map_center_latitude; } ?>"/>
                <p class="description"><?php _e('Enter the Latitude to centralize the map. by defaul("21.167086220869788")',DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th><label><?php _e("Map Center Longitude",DOMAIN);?></label></th>
            <td>
                <input type="text" name="map_center_longitude" value="<?php if(isset($map_center_longitude)) { echo $map_center_longitude; } ?>"/>
                 <p class="description"><?php _e('Enter longitude for centralize the map.by defaul("72.82231945000001")',DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th><label><?php _e("Map Type",DOMAIN);?></label></th>
            <td>
                <select name="map_type">
                    <option value="">--- Choose One ---</option>
                    <option value="ROADMAP" <?php if(isset($map_type) && $map_type=='ROADMAP')echo "selected";?>>ROADMAP</option>
                    <option value="TERRAIN" <?php if(isset($map_type) && $map_type=='TERRAIN')echo "selected";?>>TERRAIN</option>
                    <option value="HYBRID" <?php if(isset($map_type) && $map_type=='HYBRID')echo "selected";?>>HYBRID</option>
                </select>
                 <p class="description"><?php _e('You can select the Map type from here. by default("Road Map")',DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th><label><?php _e("Map display",DOMAIN);?></label></th>
            <td>
                <input type="radio" name="map_display" value="As per zoom level"  <?php if(isset($map_display) && $map_display=='As per zoom level')echo "checked";?> id="zoom_level" />&nbsp;<label for="zoom_level"><?php _e('As per zoom level',DOMAIN);?></label><br/>
                <input type="radio" name="map_display" value="Fit all available listing"  <?php if(isset($map_display) && $map_display=='Fit all available listing')echo "checked";?> id="fit_all" />&nbsp;<label for="fit_all"><?php _e('Fit all available listing',DOMAIN);?></label>
                <p class="description"> <?php _e('You can set the map display from here. by default("Fit all available listing")',DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th><label><?php _e("Map Zoom Level",DOMAIN);?></label></th>
            <td>
                <input type="text" name="map_zoom_level" value="<?php if(isset($map_zoom_level) ){ echo $map_zoom_level; } ?>" />
                 <p class="description"><?php _e('Enter zoom level if you want to display map as per zoom level. by default("13" zoom level)',DOMAIN);?></p>
            </td>
        </tr>    
    </table>
    <?php
}

/* 
Name : get_image_phy_destination_path_plugin
description : Return Upload directory path
*/

function get_image_phy_destination_path_plugin()
{	
	$wp_upload_dir = wp_upload_dir();
	$path = $wp_upload_dir['path'];
	$url = $wp_upload_dir['url'];
	  $destination_path = $path."/";
      if (!file_exists($destination_path)){
      $imagepatharr = explode('/',str_replace(ABSPATH,'', $destination_path));
	   $year_path = ABSPATH;
		for($i=0;$i<count($imagepatharr);$i++)
		{
		  if($imagepatharr[$i])
		  {
			$year_path .= $imagepatharr[$i]."/";
			  if (!file_exists($year_path)){
				  mkdir($year_path, 0777);
			  }     
			}
		}
	}
	  return $destination_path;
}

/* 
Name : get_image_size_plugin
description : Create Image from different extension
*/

function get_image_size_plugin($src)
{
	$filextenson = stripExtension_plugin($src);
	if($filextenson == "jpeg" || $filextenson == "jpg")
	  {
		$img = imagecreatefromjpeg($src);  
	  }
	
	if($filextenson == "png")
	  {
		$img = imagecreatefrompng($src);  
	  }

	if($filextenson == "gif")
	  {
		$img = imagecreatefromgif($src);  
	  }

	$width = imageSX($img);
	$height = imageSY($img);
	return array('width'=>$width,'height'=>$height);
	
}

/* 
Name : stripExtension_plugin
description : Return the extension of file
*/

function stripExtension_plugin($filename = '') {
    if (!empty($filename)) 
	   {
        $filename = strtolower($filename);
        $extArray = split("[/\\.]", $filename);
        $p = count($extArray) - 1;
        $extension = $extArray[$p];
        return $extension;
    } else {
        return false;
    }
}

/* 
Name : get_attached_file_meta_path_plugin
description : Return the file path
*/

function get_attached_file_meta_path_plugin($imagepath)
{
	$imagepath_arr = explode('/',$imagepath);
	$imagearr = array();
	for($i=0;$i<count($imagepath_arr);$i++)
	{
		$imagearr[] = $imagepath_arr[$i];
		if($imagepath_arr[$i] == 'uploads')
		{
			break;
		}
	}
	$imgpath_ini = implode('/',$imagearr);
	return str_replace($imgpath_ini.'/','',$imagepath);
}

/* 
Name : image_resize_custom_plugin
description : Image resize
*/
function image_resize_custom_plugin($src,$dest,$twidth,$theight)
{
	global $image_obj;
	// Get the image and create a thumbnail
	$img_arr = explode('.',$dest);
	$imgae_ext = strtolower($img_arr[count($img_arr)-1]);
	if($imgae_ext == 'jpg' || $imgae_ext == 'jpeg')
	{
		$img = imagecreatefromjpeg($src);
	}elseif($imgae_ext == 'gif')
	{
		$img = imagecreatefromgif($src);
	}
	elseif($imgae_ext == 'png')
	{
		$img = imagecreatefrompng($src);
	}
	if($img)
	{
		$width = imageSX($img);
		$height = imageSY($img);
	
		if (!$width || !$height) {
			echo "ERROR:Invalid width or height";
			exit(0);
		}
		
		if(($twidth<=0 || $theight<=0))
		{
			return false;
		}
		$image_obj->load($src);
		$image_obj->resize($twidth,$theight);
		$new_width = $image_obj->getWidth();
		$new_height = $image_obj->getHeight();
		$imgname_sub = '-'.$new_width.'X'. $new_height.'.'.$imgae_ext;
		$img_arr1 = explode('.',$dest);
		unset($img_arr1[count($img_arr1)-1]);
		$dest = implode('.',$img_arr1).$imgname_sub;
		$image_obj->save($dest);
		
		
		return array(
					'file' => basename( $dest ),
					'width' => $new_width,
					'height' => $new_height,
				);
	}else
	{
		return array();
	}
}


/* 
Name : move_original_image_file_plugin
description : Image move in Upload folder
*/
function move_original_image_file_plugin($src,$dest)
{
	copy($src, $dest);
	unlink($src);
	$dest = explode('/',$dest);
	$img_name = $dest[count($dest)-1];
	$img_name_arr = explode('.',$img_name);

	$my_post = array();
	$my_post['post_title'] = $img_name_arr[0];
	$my_post['guid'] = get_bloginfo('url')."/files/".get_image_rel_destination_path_plugin().$img_name;
	return $my_post;
}

/* 
Name : get_image_rel_destination_path_plugin
description : Image Final path
*/

function get_image_rel_destination_path_plugin()
{
	$today = getdate();
	if ($today['month'] == "January"){
	  $today['month'] = "01";
	}
	elseif ($today['month'] == "February"){
	  $today['month'] = "02";
	}
	elseif  ($today['month'] == "March"){
	  $today['month'] = "03";
	}
	elseif  ($today['month'] == "April"){
	  $today['month'] = "04";
	}
	elseif  ($today['month'] == "May"){
	  $today['month'] = "05";
	}
	elseif  ($today['month'] == "June"){
	  $today['month'] = "06";
	}
	elseif  ($today['month'] == "July"){
	  $today['month'] = "07";
	}
	elseif  ($today['month'] == "August"){
	  $today['month'] = "08";
	}
	elseif  ($today['month'] == "September"){
	  $today['month'] = "09";
	}
	elseif  ($today['month'] == "October"){
	  $today['month'] = "10";
	}
	elseif  ($today['month'] == "November"){
	  $today['month'] = "11";
	}
	elseif  ($today['month'] == "December"){
	  $today['month'] = "12";
	}
	global $upload_folder_path;
	$tmppath = $upload_folder_path;
	global $blog_id;
	if($blog_id)
	{
		return $user_path = $today['year']."/".$today['month']."/";
	}else
	{
		return $user_path = get_option( 'siteurl' ) ."/$tmppath".$today['year']."/".$today['month']."/";
	}
}


/* 
Name : get_site_emailId_plugin
description : Get site email Id
*/
function get_site_emailId_plugin()
{
	$generalinfo = get_option('mysite_general_settings');
	if($generalinfo['site_email'])
	{
		return $generalinfo['site_email'];
	}else
	{
		return get_option('admin_email');
	}
}

/* 
Name : get_site_emailName_plugin
description : Get site email Name
*/

function get_site_emailName_plugin()
{
	$generalinfo = get_option('mysite_general_settings');
	if($generalinfo['site_email_name'])
	{
		return stripslashes($generalinfo['site_email_name']);
	}else
	{
		return stripslashes(get_option('blogname'));
	}
}
/* 
Name : display_amount_with_currency_plugin
description : Display Amount with symbol
*/
function display_amount_with_currency_plugin($amount,$currency = ''){
	$amt_display = '';
	if($amount != ""){
	$currency = get_option('currency_symbol');
	$position = get_option('currency_pos');
		if($position == '1'){
		$amt_display = $currency.$amount;
	} else if($position == '2'){
		$amt_display = $currency.' '.$amount;
	} else if($position == '3'){
		$amt_display = $amount.$currency;
	} else {
		$amt_display = $amount.' '.$currency;
	}
	return $amt_display;
	}
}

/* 
Name : bdw_get_images_plugin
description : Resize image
*/
function bdw_get_images_plugin($iPostID,$img_size='thumb',$no_images='') 
{
     $arrImages =& get_children('order=ASC&orderby=menu_order ID&post_type=attachment&post_mime_type=image&post_parent=' . $iPostID );	
	$counter = 0;
	$return_arr = array();	

	if($arrImages) 
	{
       foreach($arrImages as $key=>$val)
	   {		  
			$id = $val->ID;
			if($val->post_title!="")
			{
				if($img_size == 'thumb')
				{
					$img_arr = wp_get_attachment_image_src($id, 'thumbnail'); // Get the thumbnail url for the attachment
					$return_arr[] = $img_arr[0];
				}
				else
				{
					$img_arr = wp_get_attachment_image_src($id, $img_size); 
					$imgarr['id'] = $id;
					$imgarr['file'] = $img_arr[0];
					$return_arr[] = $imgarr;
				}
			}

			$counter++;
			if($no_images!='' && $counter==$no_images)
			{
				break;	
			}
			
	   }
	  return $return_arr;
	}
}

// Variable & intelligent excerpt length.
function print_content($length) { // Max excerpt length. Length is set in characters
	global $post;
	$text = $post->post_content;
	if ( '' == $text ) {
		$text = get_the_content('');
		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]>', $text);
	}
	$text = strip_shortcodes($text); // optional, recommended
	$text = strip_tags($text); // use ' $text = strip_tags($text,'<p><a>'); ' if you want to keep some tags

	$text = substr($text,0,$length);
	if(function_exists('reverse_strrchr')){
		$excerpt = reverse_strrchr($text, '.', 1);
	}
	if( $excerpt ) {
		echo apply_filters('the_content',$excerpt);
	} else {
		echo apply_filters('the_content',$text);
	}
}

/* Paginaton start BOF
   Function that performs a Boxed Style Numbered Pagination (also called Page Navigation).
   Function is largely based on Version 2.4 of the WP-PageNavi plugin */
function pagenavi_plugin($before = '', $after = '') {
    global $wpdb, $wp_query;
	
    $pagenavi_options = array();
   // $pagenavi_options['pages_text'] = ('Page %CURRENT_PAGE% of %TOTAL_PAGES%:');
    $pagenavi_options['current_text'] = '%PAGE_NUMBER%';
    $pagenavi_options['page_text'] = '%PAGE_NUMBER%';
    $pagenavi_options['first_text'] = ('First Page');
    $pagenavi_options['last_text'] = ('Last Page');
    $pagenavi_options['next_text'] = '<strong class="page-numbers">'.__('NEXT',DOMAIN).'</strong>';
    $pagenavi_options['prev_text'] = '<strong class="page-numbers">'.__('PREV',DOMAIN).'</strong>';
    $pagenavi_options['dotright_text'] = '...';
    $pagenavi_options['dotleft_text'] = '...';
    $pagenavi_options['num_pages'] = 5; //continuous block of page numbers
    $pagenavi_options['always_show'] = 0;
    $pagenavi_options['num_larger_page_numbers'] = 0;
    $pagenavi_options['larger_page_numbers_multiple'] = 5;
 
    if (!is_single()) {
        $request = $wp_query->request;
        $posts_per_page = intval(get_query_var('posts_per_page'));
        $paged = intval(get_query_var('paged'));
        $numposts = $wp_query->found_posts;
        $max_page = $wp_query->max_num_pages;
 
        if(empty($paged) || $paged == 0) {
            $paged = 1;
        }
 
        $pages_to_show = intval($pagenavi_options['num_pages']);
        $larger_page_to_show = intval($pagenavi_options['num_larger_page_numbers']);
        $larger_page_multiple = intval($pagenavi_options['larger_page_numbers_multiple']);
        $pages_to_show_minus_1 = $pages_to_show - 1;
        $half_page_start = floor($pages_to_show_minus_1/2);
        $half_page_end = ceil($pages_to_show_minus_1/2);
        $start_page = $paged - $half_page_start;
 
        if($start_page <= 0) {
            $start_page = 1;
        }
 
        $end_page = $paged + $half_page_end;

        if(($end_page - $start_page) != $pages_to_show_minus_1) {
            $end_page = $start_page + $pages_to_show_minus_1;
        }
        if($end_page > $max_page) {
            $start_page = $max_page - $pages_to_show_minus_1;
            $end_page = $max_page;
        }
        if($start_page <= 0) {
            $start_page = 1;
        }
 
        $larger_per_page = $larger_page_to_show*$larger_page_multiple;
        //templ_round_num() custom function - Rounds To The Nearest Value.
        $larger_start_page_start = (templ_round_num($start_page, 10) + $larger_page_multiple) - $larger_per_page;
        $larger_start_page_end = templ_round_num($start_page, 10) + $larger_page_multiple;
        $larger_end_page_start = templ_round_num($end_page, 10) + $larger_page_multiple;
        $larger_end_page_end = templ_round_num($end_page, 10) + ($larger_per_page);
 
        if($larger_start_page_end - $larger_page_multiple == $start_page) {
            $larger_start_page_start = $larger_start_page_start - $larger_page_multiple;
            $larger_start_page_end = $larger_start_page_end - $larger_page_multiple;
        }
        if($larger_start_page_start <= 0) {
            $larger_start_page_start = $larger_page_multiple;
        }
        if($larger_start_page_end > $max_page) {
            $larger_start_page_end = $max_page;
        }
        if($larger_end_page_end > $max_page) {
            $larger_end_page_end = $max_page;
        }
        if($max_page > 1 || intval($pagenavi_options['always_show']) == 1) {

             $pages_text = str_replace("%CURRENT_PAGE%", number_format_i18n($paged), $pagenavi_options['pages_text']);
            $pages_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pages_text);
			previous_posts_link($pagenavi_options['prev_text']);
       
            if ($start_page >= 2 && $pages_to_show < $max_page) {
                $first_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pagenavi_options['first_text']);

                echo '<a href="'.esc_url(get_pagenum_link()).'" class="first" title="'.$first_page_text.'"></a>';
                if(!empty($pagenavi_options['dotleft_text'])) {
                    echo '<span class="expand page-numbers">'.$pagenavi_options['dotleft_text'].'</span>';
                }
            }
 
            if($larger_page_to_show > 0 && $larger_start_page_start > 0 && $larger_start_page_end <= $max_page) {
                for($i = $larger_start_page_start; $i < $larger_start_page_end; $i+=$larger_page_multiple) {
                    $page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
                    echo '<a href="'.esc_url(get_pagenum_link($i)).'" class="page-numbers" title="'.$page_text.'">'.$page_text.'</a>';
                }
            }
 
            for($i = $start_page; $i  <= $end_page; $i++) {
                if($i == $paged) {
                    $current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['current_text']);
                    echo '<a  class="current page-numbers">'.$current_page_text.'</a>';
                } else {
                    $page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
                    echo '<a href="'.esc_url(get_pagenum_link($i)).'" class="page-numbers" title="'.$page_text.'"><strong>'.$page_text.'</strong></a>';
                }
            }
 
            if ($end_page < $max_page) {
                if(!empty($pagenavi_options['dotright_text'])) {
                    echo '<span class="expand page-numbers">'.$pagenavi_options['dotright_text'].'</span>';
                }
                $last_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pagenavi_options['last_text']);

                echo '<a class="page-numbers" href="'.esc_url(get_pagenum_link($max_page)).'" title="'.$last_page_text.'">'.$max_page.'</a>';

            }
           
            if($larger_page_to_show > 0 && $larger_end_page_start < $max_page) {
                for($i = $larger_end_page_start; $i <= $larger_end_page_end; $i+=$larger_page_multiple) {
                    $page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
                    echo '<a href="'.esc_url(get_pagenum_link($i)).'" class="page-numbers" title="'.$page_text.'">'.$page_text.'</a>';
                }
            }
            echo $after;
			 next_posts_link($pagenavi_options['next_text'], $max_page);
        }
    }
}
function templ_round_num($num, $to_nearest) {
   /*Round fractions down (http://php.net/manual/en/function.floor.php)*/
   return floor($num/$to_nearest)*$to_nearest;
}
/*--Paginaton start EOF--*/


/**-- Upload BOF --**/
function get_file_upload($file_details)
{
	global $upload_folder_path;
	$wp_upload_dir = wp_upload_dir();
	$path = $wp_upload_dir['path'];
	$url = $wp_upload_dir['url'];
	$destination_path = $wp_upload_dir['path'].'/';
	if (!file_exists($destination_path))
	{
		$imagepatharr = explode('/',$upload_folder_path);
		$year_path = ABSPATH;
		for($i=0;$i<count($imagepatharr);$i++)
		{
		  if($imagepatharr[$i])
		  {
			 $year_path .= $imagepatharr[$i]."/";
			  if (!file_exists($year_path)){
				  mkdir($year_path, 0777);
			  }     
			}
		}
	   $imagepatharr = explode('/',$imagepath);
	   $upload_path = ABSPATH . "$upload_folder_path";
	  if (!file_exists($upload_path)){
		mkdir($upload_path, 0777);
	  }
	  for($i=0;$i<count($imagepatharr);$i++)
	  {
		  if($imagepatharr[$i])
		  {
			  $year_path = ABSPATH . "$upload_folder_path".$imagepatharr[$i]."/";
			  if (!file_exists($year_path))
			  {
				  mkdir($year_path, 0777);
			  }     
			  @mkdir($destination_path, 0777);
		}
	  }
	}
	
	if($file_details['name'])
	{		
		$srch_arr = array(' ',"'",'"','?','*','!','@','#','$','%','^','&','(',')','+','=');
		$replace_arr = array('_','','','','','','','','','','','','','','','');
		$name = time().'_'.str_replace($srch_arr,$replace_arr,$file_details['name']);
		$tmp_name = $file_details['tmp_name'];
		$target_path = $destination_path . str_replace(',','',$name);
		$extension_file = array('.php','.js');
		$file_ext= substr($target_path, -4, 4);		
		if(!in_array($file_ext,$extension_file))
		{
			if(@move_uploaded_file($tmp_name, $target_path))
			{
				$imagepath1 = $url."/".$name;
				return $imagepath1 = $imagepath1;
			}
		}
	}	
}
/**-- Upload resume EOF --**/

/*  Here I made an array of user custom fields */

function user_fields_array()
{
	global $post;
	remove_all_actions('posts_where');
	$user_args=
	array( 'post_type' => 'custom_user_field',
	'posts_per_page' => -1	,
	'post_status' => array('publish'),
	'meta_query' => array(
	   'relation' => 'AND',
		array(
			'key' => 'on_registration',
			'value' =>  '1',
			'compare' => '='
		)
	),
	'meta_key' => 'sort_order',
	'orderby' => 'meta_value',
	'order' => 'ASC'
	);
	$user_meta_sql = null;
	$user_meta_sql = new WP_Query($user_args);
	if($user_meta_sql)
 	{
	while ($user_meta_sql->have_posts()) : $user_meta_sql->the_post();
	$name = $post->post_name;
	$site_title = $post->post_title;
	$type = get_post_meta($post->ID,'ctype',true);
	$is_require = get_post_meta($post->ID,'is_require',true);
	$admin_desc = $post->post_content;
	$option_values = get_post_meta($post->ID,'option_values',true);
	$on_registration = get_post_meta($post->ID,'on_registration',true);
	$on_profile = get_post_meta($post->ID,'on_profile',true);
	$on_author_page =  get_post_meta($post->ID,'on_author_page',true);
	if($type=='text'){
		$form_fields_usermeta[$name] = array(
		"label"		=> $site_title,
		"type"		=>	'text',
		"default"	=>	$default_value,
		"extra"		=>	'id="'.$name.'" size="25" class="textfield"',
		"is_require"	=>	$is_require,
		"outer_st"	=>	'<div class="form_row clearfix">',
		"outer_end"	=>	'</div>',
		"tag_st"	=>	'',
		"tag_end"	=>	'<span class="message_note">'.$admin_desc.'</span>',
		"on_registration"	=>	$on_registration,
		"on_profile"	=>	$on_profile,
		"on_author_page" => $on_author_page,
		);
	}elseif($type=='checkbox'){
		$form_fields_usermeta[$name] = array(
		"label"		=> $site_title,
		"type"		=>	'checkbox',
		"default"	=>	$default_value,
		"extra"		=>	'id="'.$name.'" size="25" class="checkbox"',
		"is_require"	=>	$is_require,
		"outer_st"	=>	'<div class="form_row clearfix checkbox_field">',
		"outer_end"	=>	'',
		"tag_st"	=>	'',
		"tag_end"	=>	'<span class="message_note">'.$admin_desc.'</span></div>',
		"on_registration"	=>	$on_registration,
		"on_profile"	=>	$on_profile,
		"on_author_page" => $on_author_page,
		);
	}elseif($type=='textarea'){
		$form_fields_usermeta[$name] = array(
		"label"		=> $site_title,
		"type"		=>	'textarea',
		"default"	=>	$default_value,
		"extra"		=>	'id="'.$name.'" size="25" class="textarea"',
		"is_require"	=>	$is_require,
		"outer_st"	=>	'<div class="form_row clearfix">',
		"outer_end"	=>	'</div>',
		"tag_st"	=>	'',
		"tag_end"	=>	'<span class="message_note">'.$admin_desc.'</span>',
		"on_registration"	=>	$on_registration,
		"on_profile"	=>	$on_profile,
		"on_author_page" => $on_author_page,
		);
		
	}elseif($type=='texteditor'){
		$form_fields_usermeta[$name] = array(
		"label"		=> $site_title,
		"type"		=>	'texteditor',
		"default"	=>	$default_value,
		"extra"		=>	'id="'.$name.'" size="25" class="mce"',
		"is_require"	=>	$is_require,
		"outer_st"	=>	'<div class="form_row clear">',
		"outer_end"	=>	'</div>',
		"tag_before"=>	'<div class="clear">',
		"tag_after"=>	'</div>',
		"tag_st"	=>	'',
		"tag_end"	=>	'<span class="message_note">'.$admin_desc.'</span>',
		"on_registration"	=>	$on_registration,
		"on_profile"	=>	$on_profile,
		"on_author_page" => $on_author_page,
		);
	}elseif($type=='select'){
		//$option_values=explode(",",$option_values );
		$form_fields_usermeta[$name] = array(
		"label"		=> $site_title,
		"type"		=>	'select',
		"default"	=>	$default_value,
		"extra"		=>	'id="'.$name.'"',
		"options"	=> 	$option_values,
		"is_require"	=>	$is_require,
		"outer_st"	=>	'<div class="form_row clear">',
		"outer_end"	=>	'</div>',
		"tag_st"	=>	'',
		"tag_end"	=>	'',
		"on_registration"	=>	$on_registration,
		"on_profile"	=>	$on_profile,
		"on_author_page" => $on_author_page,
		);
	}elseif($type=='radio'){
		//$option_values=explode(",",$option_values );
		$form_fields_usermeta[$name] = array(
			"label"		=> $site_title,
			"type"		=>	'radio',
			"default"	=>	$default_value,
			"extra"		=>	'',
			"options"	=> 	$option_values,
			"is_require"	=>	$is_require,
			"outer_st"	=>	'<div class="form_row clear">',
			"outer_end"	=>	'</div>',
			"tag_before"=>	'<div class="form_cat">',
			"tag_after"=>	'</div>',
			"tag_st"	=>	'',
			"tag_end"	=>	'<span class="message_note">'.$admin_desc.'</span>',
			"on_registration"	=>	$on_registration,
			"on_profile"	=>	$on_profile,
			"on_author_page" => $on_author_page,
			);
	}elseif($type=='multicheckbox'){
		//$option_values=explode(",",$option_values );
		$form_fields_usermeta[$name] = array(
			"label"		=> $site_title,
			"type"		=>	'multicheckbox',
			"default"	=>	$default_value,
			"extra"		=>	'',
			"options"	=> 	$option_values,
			"is_require"	=>	$is_require,
			"outer_st"	=>	'<div class="form_row clear">',
			"outer_end"	=>	'</div>',
			"tag_before"=>	'<div class="form_cat">',
			"tag_after"=>	'</div>',
			"tag_st"	=>	'',
			"tag_end"	=>	'<span class="message_note">'.$admin_desc.'</span>',
			"on_registration"	=>	$on_registration,
			"on_profile"	=>	$on_profile,
			"on_author_page" => $on_author_page,
			);
	
	}elseif($type=='date'){
		$form_fields_usermeta[$name] = array(
		"label"		=> $site_title,
		"type"		=>	'date',
		"default"	=>	$default_value,
		"extra"		=>	'id="'.$name.'" size="25" class="textfield_date"',
		"is_require"	=>	$is_require,
		"outer_st"	=>	'<div class="form_row clearfix">',
		"outer_end"	=>	'</div>',
		//"tag_st"	=>	'<img src="'.get_template_directory_uri().'/images/cal.gif" alt="Calendar"  onclick="displayCalendar(document.userform.'.$name.',\'yyyy-mm-dd\',this)" style="cursor: pointer;" align="absmiddle" border="0" class="calendar_img" />',
		"tag_end"	=>	'<span class="message_note">'.$admin_desc.'</span>',
		"on_registration"	=>	$on_registration,
		"on_profile"	=>	$on_profile,
		"on_author_page" => $on_author_page,
		);
		
	}elseif($type=='upload'){
	$form_fields_usermeta[$name] = array(
		"label"		=> $site_title,
		"type"		=>	'upload',
		"default"	=>	$default_value,
		"extra"		=>	'id="'.$name.'" class="textfield"',
		"is_require"	=>	$is_require,
		"outer_st"	=>	'<div class="form_row clearfix upload_img">',
		"outer_end"	=>	'</div>',
		"tag_st"	=>	'',
		"tag_end"	=>	'<span class="message_note">'.$admin_desc.'</span>',
		"on_registration"	=>	$on_registration,
		"on_profile"	=>	$on_profile,
		"on_author_page" => $on_author_page,
		);
	}elseif($type=='head'){
	$form_fields_usermeta[$name] = array(
		"label"		=> $site_title,
		"type"		=>	'head',
		"outer_st"	=>	'<h5 class="form_title">',
		"outer_end"	=>	'</h5>',
		);
	}elseif($type=='geo_map'){
	$form_fields_usermeta[$name] = array(
		"label"		=> '',
		"type"		=>	'geo_map',
		"default"	=>	$default_value,
		"extra"		=>	'',
		"is_require"	=>	$is_require,
		"outer_st"	=>	'',
		"outer_end"	=>	'',
		"tag_st"	=>	'',
		"tag_end"	=>	'',
		"on_registration"	=>	$on_registration,
		"on_profile"	=>	$on_profile,
		"on_author_page" => $on_author_page,
		);		
	}elseif($type=='image_uploader'){
	$form_fields_usermeta[$name] = array(
		"label"		=> '',
		"type"		=>	'image_uploader',
		"default"	=>	$default_value,
		"extra"		=>	'',
		"is_require"	=>	$is_require,
		"outer_st"	=>	'',
		"outer_end"	=>	'',
		"tag_st"	=>	'',
		"tag_end"	=>	'',
		"on_registration"	=>	$on_registration,
		"on_profile"	=>	$on_profile,
		"on_author_page" => $on_author_page,
		);
	}
  endwhile;
  return $form_fields_usermeta;
}
}

/* With the help of User custom fields array, To fetch out the user custom fields */

function display_usermeta_fields($user_meta_array)
{
  $form_fields_usermeta	= $user_meta_array;
 global $user_validation_info;
 $user_validation_info = array();
  foreach($form_fields_usermeta as $key=>$val)
	{
	$str = ''; $fval = '';
	$field_val = $key.'_val';
	if(isset($_REQUEST['user_fname']) || (!isset($_REQUEST['backandedit'])  && $_REQUEST['backandedit'] == '')){ $field_val = $_REQUEST[$key]; } elseif(isset($_REQUEST['backandedit']) && $_REQUEST['backandedit'] == '1' ) {$field_val = $_SESSION['custom_fields'][$key]; }
	if(@$field_val){ $fval = $field_val; }else{ $fval = $val['default']; }
   
	if($val['is_require'])
	{
		$user_validation_info[] = array(
								   'name'	=> $key,
								   'espan'	=> $key.'_error',
								   'type'	=> $val['type'],
								   'text'	=> $val['label'],
								   );
	}
	if($val['type']=='text')
	{
		$str = '<input name="'.$key.'" type="text" '.$val['extra'].' value="'.$fval.'">';
		if($val['is_require'])
		{
			$str .= '<span id="'.$key.'_error"></span>';
		}
	}elseif($val['type']=='hidden')
	{
		$str = '<input name="'.$key.'" type="hidden" '.$val['extra'].' value="'.$fval.'">';	
		if($val['is_require'])
		{
			$str .= '<span id="'.$key.'_error"></span>';	
		}
	}else
	if($val['type']=='textarea')
	{
		$str = '<textarea name="'.$key.'" '.$val['extra'].'>'.$fval.'</textarea>';	
		if($val['is_require'])
		{
			$str .= '<span id="'.$key.'_error"></span>';	
		}
	}else
	if($val['type']=='file')
	{
		$str = '<input name="'.$key.'" type="file" '.$val['extra'].' value="'.$fval.'">';
		if($val['is_require'])
		{
			$str .= '<span id="'.$key.'_error"></span>';	
		}
	}else
	if($val['type']=='include')
	{
		$str = @include_once($val['default']);
	}else
	if($val['type']=='head')
	{
		$str = '';
	}else
	if($val['type']=='date')
	{
		?>
         <script type="text/javascript">	
				jQuery(function(){
				var pickerOpts = {
						showOn: "both",
						dateFormat: 'yy-mm-dd',
						buttonImage: "<?php echo TEMPL_PLUGIN_URL; ?>css/datepicker/images/cal.png",
						buttonText: "Show Datepicker"
					};	
					jQuery("#<?php echo $key;?>").datepicker(pickerOpts);					
				});
			</script>
        <?php
		$str = '<input name="'.$key.'" id="'.$key.'" type="text" '.$val['extra'].' value="'.$fval.'">';			
		if($val['is_require'])
		{
			$str .= '<span id="'.$key.'_error"></span>';	
		}
	}else
	if($val['type']=='catselect')
	{
		$term = get_term( (int)$fval, CUSTOM_CATEGORY_TYPE1);
		$str = '<select name="'.$key.'" '.$val['extra'].'>';
		$args = array('taxonomy' => CUSTOM_CATEGORY_TYPE1);
		$all_categories = get_categories($args);
		foreach($all_categories as $key => $cat) 
		{
		
			$seled='';
			if($term->name==$cat->name){ $seled='selected="selected"';}
			$str .= '<option value="'.$cat->name.'" '.$seled.'>'.$cat->name.'</option>';	
		}
		$str .= '</select>';
		if($val['is_require'])
		{
			$str .= '<span id="'.$key.'_error"></span>';	
		}
	}else
	if($val['type']=='catdropdown')
	{
		$cat_args = array('name' => 'post_category', 'id' => 'post_category_0', 'selected' => $fval, 'class' => 'textfield', 'orderby' => 'name', 'echo' => '0', 'hierarchical' => 1, 'taxonomy'=>CUSTOM_CATEGORY_TYPE1);
		$cat_args['show_option_none'] = __('Select Category',DOMAIN);
		$str .=wp_dropdown_categories(apply_filters('widget_categories_dropdown_args', $cat_args));
		if($val['is_require'])
		{
			$str .= '<span id="'.$key.'_error"></span>';	
		}
	}else
	if($val['type']=='select')
	{
		$str = '<select name="'.$key.'" '.$val['extra'].'>';
		 $str .= '<option value="" >'.PLEASE_SELECT.' '.$val['label'].'</option>';	
		$option_values_arr = explode(',', $val['options']);
		for($i=0;$i<count($option_values_arr);$i++)
		{
			$seled='';
			
			if($fval==$option_values_arr[$i]){ $seled='selected="selected"';}
			$str .= '<option value="'.$option_values_arr[$i].'" '.$seled.'>'.$option_values_arr[$i].'</option>';	
		}
		$str .= '</select>';
		if($val['is_require'])
		{
			$str .= '<span id="'.$key.'_error"></span>';	
		}
	}else
	if($val['type']=='catcheckbox')
	{
		$fval_arr = explode(',',$fval);
		$str .= $val['tag_before'].get_categories_checkboxes_form(CUSTOM_CATEGORY_TYPE1,$fval_arr).$oval.$val['tag_after'];
		if($val['is_require'])
		{
			$str .= '<span id="'.$key.'_error"></span>';	
		}
	}else
	if($val['type']=='catradio')
	{
		$args = array('taxonomy' => CUSTOM_CATEGORY_TYPE1);
		$all_categories = get_categories($args);
		foreach($all_categories as $key1 => $cat) 
		{
			
			
				$seled='';
				if($fval==$cat->term_id){ $seled='checked="checked"';}
				$str .= $val['tag_before'].'<input name="'.$key.'" type="radio" '.$val['extra'].' value="'.$cat->name.'" '.$seled.'> '.$cat->name.$val['tag_after'];	
			
		}
		if($val['is_require'])
		{
			$str .= '<span id="'.$key.'_error"></span>';	
		}
	}else
	if($val['type']=='checkbox')
	{
		if($fval){ $seled='checked="checked"';}
		$str = '<input name="'.$key.'" type="checkbox" '.$val['extra'].' value="1" '.$seled.'>';
		if($val['is_require'])
		{
			$str .= '<span id="'.$key.'_error"></span>';	
		}
	}else
	if($val['type']=='upload')
	{
		
		$str = '<input name="'.$key.'" type="file" '.$val['extra'].' '.$uclass.' value="'.$fval.'" > ';
		if($val['is_require'])
		{
			$str .= '<span id="'.$key.'_error"></span>';	
		}
	}
	else
	if($val['type']=='radio')
	{
		$options = $val['options'];
		if($options)
		{
			$option_values_arr = explode(',',$options);
			for($i=0;$i<count($option_values_arr);$i++)
			{
				$seled='';
				if($fval==$option_values_arr[$i]){$seled='checked="checked"';}
				$str .= $val['tag_before'].'<input name="'.$key.'" type="radio" '.$val['extra'].'  value="'.$option_values_arr[$i].'" '.$seled.'> '.$option_values_arr[$i].$val['tag_after'];
			}
			if($val['is_require'])
			{
				$str .= '<span id="'.$key.'_error"></span>';	
			}
		}
	}else
	if($val['type']=='multicheckbox')
	{
		$options = $val['options'];
		if($options)
		{  $chkcounter = 0;
			
			$option_values_arr = explode(',',$options);
			for($i=0;$i<count($option_values_arr);$i++)
			{
				$chkcounter++;
				$seled='';
				$fval_arr = explode(',',$fval);
				if(in_array($option_values_arr[$i],$fval_arr)){ $seled='checked="checked"';}
				$str .= $val['tag_before'].'<input name="'.$key.'[]"  id="'.$key.'_'.$chkcounter.'" type="checkbox" '.$val['extra'].' value="'.$option_values_arr[$i].'" '.$seled.'> '.$option_values_arr[$i].$val['tag_after'];
			}
			if($val['is_require'])
			{
				$str .= '<span id="'.$key.'_error"></span>';	
			}
		}
	}
	else
	if($val['type']=='packageradio')
	{
		$options = $val['options'];
		foreach($options as $okey=>$oval)
		{
			$seled='';
			if($fval==$okey){$seled='checked="checked"';}
			$str .= $val['tag_before'].'<input name="'.$key.'" type="radio" '.$val['extra'].' value="'.$okey.'" '.$seled.'> '.$oval.$val['tag_after'];	
		}
		if($val['is_require'])
		{
			$str .= '<span id="'.$key.'_error"></span>';	
		}
	}else
	if($val['type']=='geo_map')
	{
		do_action('templ_submit_form_googlemap');	
	}else
	if($val['type']=='image_uploader')
	{
		do_action('templ_submit_form_image_uploader');	
	}
	if($val['is_require'])
	{
		$label = '<label>'.$val['label'].' <span class="indicates">*</span> </label>';
	}else
	{
		$label = '<label>'.$val['label'].'</label>';
	}
	if($val['type']=='texteditor')
			{
				echo $val['outer_st'].$label.$val['tag_st'];
				 echo $val['tag_before'].$val['tag_after'];
            // default settings
					$settings =   array(
						'wpautop' => true, // use wpautop?
						'media_buttons' => false, // show insert/upload button(s)
						'textarea_name' => $key, // set the textarea name to something different, square brackets [] can be used here
						'textarea_rows' => '10', // rows="..."
						'tabindex' => '',
						'editor_css' => '<style>.wp-editor-wrap{width:640px;margin-left:0px;}</style>', // intended for extra styles for both visual and HTML editors buttons, needs to include the <style> tags, can use "scoped".
						'editor_class' => '', // add extra class(es) to the editor textarea
						'teeny' => false, // output the minimal editor config used in Press This
						'dfw' => false, // replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)
						'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
						'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
					);				
					if(isset($fval) && $fval != '') 
					{  $content=$fval; }
					else{$content= $fval; } 				
					wp_editor( $content, $key, $settings);				
			
					if($val['is_require'])
					{
						$str .= '<span id="'.$key.'_error"></span>';	
					}
				echo $str.$val['tag_end'].$val['outer_end'];
			}else{	
				echo $val['outer_st'].$label.$val['tag_st'].$str.$val['tag_end'].$val['outer_end'];
			}
 }
}


/* Return User name */
function get_user_name_plugin($fname,$lname='')
{
	global $wpdb;
	if($lname)
	{
		$uname = $fname.'-'.$lname;
	}else
	{
		$uname = $fname;
	}

	$nicename = strtolower(str_replace(array("'",'"',"?",".","!","@","#","$","%","^","&","*","(",")","-","+","+"," "),array('','','','-','','-','-','','','','','','','','','','-','-',''),$uname));
	$nicenamecount = $wpdb->get_var("select count(user_nicename) from $wpdb->users where user_nicename like \"$nicename\"");
	if($nicenamecount=='0')
	{
		return trim($nicename);
	}else
	{
		$lastuid = $wpdb->get_var("select max(ID) from $wpdb->users");
		return $nicename.'-'.$lastuid;
	}
}

/* Rerturns user currently in admin area or in front end */
function is_templ_wp_admin()
{
	if(strstr($_SERVER['REQUEST_URI'],'/wp-admin/'))
	{
		return true;
	}
	return false;
}

/* 
Name : is_valid_coupon_plugin
description : Return coupon valid or not
*/

function is_valid_coupon_plugin($coupon)
{
	global $wpdb;
    $couponsql = $wpdb->get_var( $wpdb->prepare( "SELECT post_title FROM $wpdb->posts WHERE post_title = %s AND post_type='coupon_code'", $coupon ));
	$couponinfo = $couponsql;
	if($couponinfo)
	{
		if($couponinfo == $coupon)
		{
			return true;
		}
	}
	return false;
}

/* 
Name : get_payable_amount_with_coupon_plugin
description : Return Total amt
*/

function get_payable_amount_with_coupon_plugin($total_amt,$coupon_code)
{
	$discount_amt = get_discount_amount_plugin($coupon_code,$total_amt);
	if($discount_amt>0)
	{
		return $total_amt-$discount_amt;
	}else
	{
		return $total_amt;
	}
}

/* 
Name : get_payable_amount_with_coupon_plugin
description : Return Amt by filtering
*/

function get_discount_amount_plugin($coupon,$amount)
{
	global $wpdb;
	if($coupon!='' && $amount>0)
	{
		$couponsql = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='coupon_code'", $coupon ));
		$couponinfo = $couponsql;
		$start_date = strtotime(get_post_meta($couponinfo,'startdate',true));
		$end_date 	= strtotime(get_post_meta($couponinfo,'enddate',true));
		$todays_date = strtotime(date("Y-m-d"));
		if ($start_date <= $todays_date && $end_date >= $todays_date)
		{
			if($couponinfo)
			{
				if(get_post_meta($couponinfo,'coupondisc',true)=='per')
				{
					$discount_amt = ($amount*get_post_meta($couponinfo,'couponamt',true))/100;
				}
				elseif(get_post_meta($couponinfo,'coupondisc',true)=='amt')
				{
					$discount_amt = get_post_meta($couponinfo,'couponamt',true);
				}
				return $discount_amt;
			}
		}
	}
	return '0';
}
/*
Name :fetch_page_taxonomy
Description : fetch page taxonomy 
*/
function fetch_page_taxonomy($pid){
	global $wp_post_types;
	$post_type = get_post_meta($pid,'template_post_type',true);
	/* code to fetch custom Fields */
	$custom_post_types_args = array();
	$custom_post_types = get_post_type_object($post_type);
	$args_taxonomy = get_option('templatic_custom_post');
	if  ($custom_post_types) {
		 foreach ($custom_post_types as $content_type) {
			$post_slug = @$custom_post_types->rewrite['slug'];
			
			if($post_type == strtolower('post')){
				$taxonomy = 'category';
			}else{
				$taxonomy = $args_taxonomy[$post_slug]['slugs'][0];
			}
	  }
	}	
	return $taxonomy;
}

/*
Name :templ_captcha_integrate
Description : put this function where you want to use captcha
*/

function templ_captcha_integrate($form)
{
	$tmpdata = get_option('templatic_settings');
	$display = @$tmpdata['user_verification_page'];
	if(isset($tmpdata['recaptcha']) &&  $tmpdata['recaptcha'] == 'recaptcha')
	{
		$a = get_option("recaptcha_options");
		if(file_exists(ABSPATH.'wp-content/plugins/wp-recaptcha/recaptchalib.php') && is_plugin_active('wp-recaptcha/wp-recaptcha.php') && in_array($form,$display))
		{
			require_once(ABSPATH.'wp-content/plugins/wp-recaptcha/recaptchalib.php');
			echo '<label class="recaptcha_claim">'.WORD_VERIFICATION.' : </label>  <span>*</span>';
			$publickey = $a['public_key']; // you got this from the signup page ?>
			<div class="claim_recaptcha_div"><?php echo recaptcha_get_html($publickey); ?> </div>
	<?php }
	}
	elseif(isset($tmpdata['recaptcha']) && $tmpdata['recaptcha'] == 'playthru')
	{ ?>
	<?php /* CODE TO ADD PLAYTHRU PLUGIN COMPATIBILITY */
		if(file_exists(ABSPATH.'wp-content/plugins/are-you-a-human/areyouahuman.php') && is_plugin_active('are-you-a-human/areyouahuman.php')  && in_array($form,$display))
		{
			require_once( ABSPATH.'wp-content/plugins/are-you-a-human/areyouahuman.php');
			require_once(ABSPATH.'wp-content/plugins/are-you-a-human/includes/ayah.php');
			$ayah = ayah_load_library();
			echo $ayah->getPublisherHTML();
		}
	}
}

/* NAME : FETCH POST DEFAULT STATUS
DESCRIPTION : THIS FUNCTION WILL FETCH THE DEFAULT STATUS OF THE POSTS SET BY THE ADMIN IN BACKEND GENERAL SETTINGS */
function fetch_posts_default_status()
{
	$tmpdata = get_option('templatic_settings');
	$post_default_status = $tmpdata['post_default_status'];
	return $post_default_status;
}
/* EOF - FETCH DEFAULT STATUS FOR POSTS */

/* NAME : FETCH POST DEFAULT PAID STATUS
DESCRIPTION : THIS FUNCTION WILL FETCH THE DEFAULT STATUS OF THE PAID POSTS SET BY THE ADMIN IN BACKEND GENERAL SETTINGS */
function fetch_posts_default_paid_status()
{
	$tmpdata = get_option('templatic_settings');
	$post_default_status = $tmpdata['post_default_status_paid'];
	return $post_default_status;
}
/* EOF - FETCH DEFAULT STATUS FOR PAID POSTS */


/*
 * add action for add calender css and javascript file inside html head tag
 */ 

add_action ('wp_head', 'header_css_javascript');
add_action('admin_head','header_css_javascript');

/*
 * Function Name:header_css_javascript
 * Front side add css and javascript file in side html head tag 
 */
 
function header_css_javascript()  {  
	wp_enqueue_style('jQuery_datepicker_css',TEMPL_PLUGIN_URL.'css/datepicker/jquery.ui.all.css');	
	wp_enqueue_script('jquery_ui_core',TEMPL_PLUGIN_URL.'js/jquery.ui.core.js');	
	wp_enqueue_script('jquery-ui-datepicker');	
	
  	?>   
	<?php if(is_page() || isset($_REQUEST['ptype']) && $_REQUEST['ptype'] !=''){ /* show only on registration and submoit from */?>
	<script type="text/javascript">
	function set_login_registration_frm(val)
	{

		if(val=='existing_user')
		{
			document.getElementById('login_user_meta').style.display = 'none';
			document.getElementById('login_user_frm_id').style.display = '';
			//document.getElementById('user_login_or_not').value = val;
		}else  //new_user
		{
			document.getElementById('login_user_meta').style.display = 'block';
			document.getElementById('login_user_frm_id').style.display = 'none';
			//document.getElementById('user_login_or_not').value = val;
		}
	}
	</script>
    <?php }
}
/*
Name : tmpl_show_on_detail
Desc : Show on detail page enable fields
*/
function tmpl_show_on_detail($cur_post_type,$heading_type){
	global $wpdb,$post;
	remove_all_actions('posts_where');
	add_filter('posts_join', 'custom_field_posts_where_filter');
	if($heading_type)
	 {
		$args = array( 'post_type' => 'custom_fields',
				'posts_per_page' => -1	,
				'post_status' => array('publish'),
				'meta_query' => array(
				 'relation' => 'AND',
				array(
					'key' => 'post_type_'.$cur_post_type.'',
					'value' => $cur_post_type,
					'compare' => '=',
					'type'=> 'text'
				),
				array(
					'key' => 'show_on_page',
					'value' =>  array('user_side','both_side'),
					'compare' => 'IN'
				),
				array(
					'key' => 'is_active',
					'value' =>  '1',
					'compare' => '='
				),
				array(
					'key' => 'heading_type',
					'value' =>  $heading_type,
					'compare' => '='
				),
				array(
					'key' => 'show_on_detail',
					'value' =>  '1',
					'compare' => '='
					)
				),
				'meta_key' => 'sort_order',
				'orderby' => 'meta_value',
				'order' => 'ASC'
		);
	 }
	else
	 {
		$args = array( 'post_type' => 'custom_fields',
			'posts_per_page' => -1	,
			'post_status' => array('publish'),
			'meta_query' => array(
			 'relation' => 'AND',
			array(
				'key' => 'post_type_'.$cur_post_type.'',
				'value' => $cur_post_type,
				'compare' => '=',
				'type'=> 'text'
			),
			array(
				'key' => 'show_on_page',
				'value' =>  array('user_side','both_side'),
				'compare' => 'IN'
			),
			array(
				'key' => 'is_active',
				'value' =>  '1',
				'compare' => '='
			),
			array(
				'key' => 'show_on_detail',
				'value' =>  '1',
				'compare' => '='
				)
			),
			'meta_key' => 'sort_order',
			'orderby' => 'meta_value',
			'order' => 'ASC'
		);
 
	 }
	$post_query = null;
	$upload = array();
	$post_query = new WP_Query($args);
	remove_filter('posts_join', 'custom_field_posts_where_filter');
	return $post_query;
}
add_action('templatic_fields_onpreview','tmpl_show_custom_fields_onpreview',10,2);
/*
Name : tmpl_show_custom_fields_onpreview
Desc : Show on detail page enable fields
*/
function tmpl_show_custom_fields_onpreview($session,$cur_post_type){
	global $wpdb,$post,$upload;
	$heading_type = fetch_heading_per_post_type($cur_post_type);
	if(count($heading_type) > 0)
	{
		foreach($heading_type as $_heading_type)		
			$post_meta_info_arr[$_heading_type] = tmpl_show_on_detail($cur_post_type,$_heading_type);
	}
	else
		$post_meta_info_arr[] = tmpl_show_on_detail($cur_post_type,'');	  	
	
	echo "<div class='grid02 rc_rightcol clearfix'>";
	echo "<ul class='list'>";
	if($post_meta_info_arr)
	{	$i=0;
		//Display the post details heading only one time if  post_content, Post_title,post_images and post_category not
		foreach($post_meta_info_arr as $key=> $post_meta_field)
		{
			while($post_meta_field->have_posts()) : $post_meta_field->the_post();
				
				if($i==0)
					if($post->post_name != 'post_content' && $post->post_name != 'post_title' && $post->post_name != 'category' &&  $post->post_name != 'post_images')
					{
				?>
				    <div class="title-container">
					   <h1><?php _e(POST_DETAIL,DOMAIN);?></h1>
					</div>
				<?php $i++;
					}
			endwhile;
		}//finish the post details heading one one time 
		
		foreach($post_meta_info_arr as $key=> $post_meta_info)
		 {
			$activ = fetch_active_heading($key);
			if($activ):
				if($key == '[#taxonomy_name#]'):
			?>	
					<div class="sec_title"><h3><?php echo $cur_post_type; ?><?php _e(' Information',DOMAIN); ?></h3></div>
			<?php
            	else:
					echo "<li><h2>".$key."</h2></li>";
				endif;	
		  	endif;	
	
			while ($post_meta_info->have_posts()) : $post_meta_info->the_post();
			if($post->post_name != 'post_content' && $post->post_name != 'post_title' && $post->post_name != 'category')
			{
				if(isset($session[$post->post_name]) && $session[$post->post_name]!=""){
					if(get_post_meta($post->ID,"ctype",true) == 'multicheckbox')
					{
						foreach($session[$post->post_name] as $value)
						{
							$_value .= $value.",";	 
						}
						echo "<li><p>".$post->post_title." : </p> <p> ".substr($_value,0,-1)."</p></li>"; 
					}else
					{
	
						 echo "<li><p>".$post->post_title." : </p> <p> ".stripslashes($session[$post->post_name])."</p></li>";
					}
				}				
				if(get_post_meta($post->ID,"ctype",true) == 'upload')
				{
					$upload[] = $post->post_name;
				}
			}
			endwhile;
		}
	}
	echo "</ul>";
	echo "</div>";
}



/*************************** LOAD THE BASE CLASS *******************************

 * The WP_List_Table class isn't automatically available to plugins, so we need
 * to check if it's available and load it if necessary.
 */
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class custom_fields_list_table extends WP_List_Table
{
	/***** FETCH ALL THE DATA AND STORE THEM IN AN ARRAY *****
	* Call a function that will return all the data in an array and we will assign that result to a variable $custom_fields_data. FIRST OF ALL WE WILL FETCH DATA FROM POST META TABLE STORE THEM IN AN ARRAY $custom_fields_data */
	function fetch_custom_fields_data($post_id = '' ,$post_title = '')
	{ 
		$fields_label  = $post_title;
		$show_in_post_type = get_post_meta($post_id,"post_type",true);
		$is_edit = get_post_meta($post_id,"is_edit",true);
		$type = get_post_meta($post_id,"ctype",true);
		$html_var = get_post_meta($post_id,"htmlvar_name",true);
		$admin_desc = get_post_field('post_content', $post_id);
		if(get_post_meta($post_id,"is_active",true))
		  {
			$active = 'Yes';
		  }	
		else
		  {
			$active = 'No';
		  }	
		if($is_edit =='true'){
			$edit_url = admin_url("admin.php?page=custom_fields&action=addnew&amp;field_id=$post_id");
		}else{ $edit_url ='#'; }
		
		/* Start WPML Language conde*/
		if(is_plugin_active('wpml-translation-management/plugin.php'))
		{
		global $wpdb, $sitepress_settings,$sitepress;			
		global $id, $__management_columns_posts_translations, $pagenow, $iclTranslationManagement;
		// get posts translations
            // get trids		
		  // get trids		            		  
            $trids = $wpdb->get_col("SELECT trid FROM {$wpdb->prefix}icl_translations WHERE element_type='post_custom_fields' AND element_id IN (".$post_id.")");		 
            $ptrs = $wpdb->get_results("SELECT trid, element_id, language_code, source_language_code FROM {$wpdb->prefix}icl_translations WHERE trid IN (". join(',', $trids).")");		  
            foreach($ptrs as $v){
                $by_trid[$v->trid][] = $v;
            }		 
		 
		   foreach($ptrs as $v){			  
                if($v->element_id == $post_id){
                    $el_trid = $v->trid;
                    foreach($ptrs as $val){
                        if($val->trid == $el_trid){
                            $__management_columns_posts_translations[$v->element_id][$val->language_code] = $val;					   
                        }
                    }
                }
            }		  
		$country_url = '';		
		$active_languages = $sitepress->get_active_languages();
        	foreach($active_languages as $k=>$v){				
			if($v['code']==$sitepress->get_current_language()) continue;
			 $post_type = isset($_REQUEST['page']) ? $_REQUEST['page'] : 'custom_fields';						
			 if(isset($__management_columns_posts_translations[$id][$v['code']]) && $__management_columns_posts_translations[$id][$v['code']]->element_id){
				  // Translation exists
				 $img = 'edit_translation.png';
				 $alt = sprintf(__('Edit the %s translation','sitepress'), $v['display_name']);				 
				 $link = 'admin.php?page='.$post_type.'&action=addnew&amp;field_id='.$__management_columns_posts_translations[$id][$v['code']]->element_id.'&amp;lang='.$v['code'];				 
				  
			  }else{
				   // Translation does not exist
				$img = 'add_translation.png';
				$alt = sprintf(__('Add translation to %s','sitepress'), $v['display_name']);
                	$src_lang = $sitepress->get_current_language() == 'all' ? $sitepress->get_default_language() : $sitepress->get_current_language();				        					
                    $link = '?page='.$post_type.'&action=addnew&trid='.$post_id.'&amp;lang='.$v['code'].'&amp;source_lang=' . $src_lang;
			  }
			  
			  if($link){
				 if($link == '#'){
					icl_pop_info($alt, ICL_PLUGIN_URL . '/res/img/' .$img, array('icon_size' => 16, 'but_style'=>array('icl_pop_info_but_noabs')));                    
				 }else{
					$country_url.= '<a href="'.$link.'" title="'.$alt.'">';
					$country_url.= '<img style="padding:1px;margin:2px;" border="0" src="'.ICL_PLUGIN_URL . '/res/img/' .$img.'" alt="'.$alt.'" width="16" height="16" />';
					$country_url.= '</a>';
				 }
			  }			  
			}//finish foreach
		 
		 
		/*Finish WPML language code  */
		$meta_data = array(
			'ID'=> $post_id,
			'title'	=> '<strong><a href="'.$edit_url.'">'.$fields_label.'</a></strong>',
			'icl_translations' => $country_url,
			'html_var' => $html_var,
			'show_in_post_type' 	=> $show_in_post_type,
			'type' => $type,
			'active' 	=> $active,
			'admin_desc' => $admin_desc
			);
		}else
		{
			$meta_data = array(
			'ID'=> $post_id,
			'title'	=> '<strong><a href="'.$edit_url.'">'.$fields_label.'</a></strong>',			
			'show_in_post_type' 	=> $show_in_post_type,
			'html_var' => $html_var,
			'type' => $type,
			'active' 	=> $active,
			'admin_desc' => $admin_desc
			);
		}
		return $meta_data;
	}
	function custom_fields_data()
	{
		global $post, $paged, $query_args,$sitepress_settings,$sitepress;
		$paged   = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		$per_page = get_option('posts_per_page');
		if(isset($_POST['s']) && $_POST['s'] != '')
		{
			$search_key = $_POST['s'];
			$args = array(
				'post_type' 		=> 'custom_fields',
				'suppress_filters' => false,
				'posts_per_page' 	=> $per_page,
				'post_status' 		=> array('publish'),
				'paged' 			=> $paged,
				's'					=> $search_key,
				'order' => 'ASC'
				
				);
		}
		else
		{
			$args = array(
				'post_type' 		=> 'custom_fields',
				'suppress_filters' => false,
				'posts_per_page' 	=> '-1',
				'paged' 			=> $paged,
				'post_status' 		=> array('publish'),
				'order' => 'ASC'
				);
		}
		$post_meta_info = null;		
		add_filter('posts_join', 'custom_field_posts_where_filter');
		$post_meta_info = new WP_Query($args);
		while ($post_meta_info->have_posts()) : $post_meta_info->the_post();
				$custom_fields_data[] = $this->fetch_custom_fields_data($post->ID,$post->post_title);
		endwhile;
		remove_filter('posts_join', 'custom_field_posts_where_filter');
		return $custom_fields_data;
	}
	/* EOF - FETCH CUSTOM FIELDS DATA */
	
	/* DEFINE THE COLUMNS FOR THE TABLE */
	function get_columns()
	{	
		/*WPML lamguage translation plugin is active */
		if(is_plugin_active('wpml-translation-management/plugin.php'))
		{
			$country_flag = '';
			$languages = icl_get_languages('skip_missing=0');
			if(!empty($languages)){
				foreach($languages as $l){
					if(!$l['active']) echo '<a href="'.$l['url'].'">';
					if(!$l['active']) $country_flag .= '<img src="'.$l['country_flag_url'].'" height="12" alt="'.$l['language_code'].'" width="18" />'.' ';
					if(!$l['active']) echo '</a>';
				}
			}
			$columns = array(
				'cb' => '<input type="checkbox" />',
				'title' => __('Field name',DOMAIN),
				'icl_translations' => $country_flag,
				'show_in_post_type' => __('Shown in post-type',DOMAIN),
				'html_var' => $html_var,
				'type' => __('Type',DOMAIN),
				'active' => __('Status',DOMAIN),
				'admin_desc' => __('Description',DOMAIN)
				);
		}else
		{
			$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __('Field name',DOMAIN),			
			'show_in_post_type' => __('Shown in post-type',DOMAIN),
			'html_var' => __('variable name',DOMAIN),
			'type' => __('Type',DOMAIN),
			'active' => __('Active',DOMAIN),
			'admin_desc' => __('Description',DOMAIN)
			);
		}
		return $columns;
	}
	
	function process_bulk_action()
	{ 
		//Detect when a bulk action is being triggered...
		if('delete' == $this->current_action() )
		{
			 foreach($_REQUEST['checkbox'] as $postid)
			  {
				 wp_delete_post($postid);
			  }	 
			 $url = site_url().'/wp-admin/admin.php';
			 wp_redirect($url."?page=custom_fields&custom_field_msg=delsuccess");
			 exit;	
		}
	}
    
	function prepare_items()
	{
		$per_page = $this->get_items_per_page('custom_fields_per_page', 10);
		$columns = $this->get_columns(); /* CALL FUNCTION TO GET THE COLUMNS */
        $hidden = array();
		$sortable = array();
        $sortable = $this->get_sortable_columns(); /* GET THE SORTABLE COLUMNS */
		$this->_column_headers = array($columns, $hidden, $sortable);
		$this->process_bulk_action(); /* FUNCTION TO PROCESS THE BULK ACTIONS */
		$data = $this->custom_fields_data(); /* RETIRIVE THE PACKAGE DATA */
		
		/* FUNCTION THAT SORTS THE COLUMNS */
		function usort_reorder($a,$b)
		{
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'title'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc'; //If no order, default to asc
            $result = strcmp(@$a[$orderby], @$b[$orderby]); //Determine sort order
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
		if(is_array($data))
	        usort( $data, 'usort_reorder');
		
		$current_page = $this->get_pagenum(); /* GET THE PAGINATION */
		$total_items = count($data); /* CALCULATE THE TOTAL ITEMS */
		if(is_array($data))
			$this->found_data = array_slice($data,(($current_page-1)*$per_page),$per_page); /* TRIM DATA FOR PAGINATION*/
		$this->items = $this->found_data; /* ASSIGN SORTED DATA TO ITEMS TO BE USED ELSEWHERE IN CLASS */
		/* REGISTER PAGINATION OPTIONS */
		
		$this->set_pagination_args( array(
            'total_items' => $total_items,      //WE have to calculate the total number of items
            'per_page'    => $per_page         //WE have to determine how many items to show on a page
        ) );
	}
	
	/* To avoid the need to create a method for each column there is column_default that will process any column for which no special method is defined */
	function column_default( $item, $column_name )
	{
		switch( $column_name )
		{
			case 'cb':
			case 'title':
			case 'icl_translations':
			case 'show_in_post_type':
			case 'html_var':
			case 'type':
			case 'active':
			case 'admin_desc':
			return $item[ $column_name ];
			default:
			return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		}
	}
	
	/* DEFINE THE COLUMNS TO BE SORTED */
	function get_sortable_columns()
	{
		$sortable_columns = array(
			'title' => array('title',true),
			'show_in_post_type' => array('show_in_post_type',true)
			);
		return $sortable_columns;
	}
	
	function column_title($item)
	{
		$is_editable = get_post_meta($item['ID'],'is_edit',true);
		$is_deletable = get_post_meta($item['ID'],'is_delete',true);
		
			$action1 = array(
			'edit' => sprintf('<a href="?page=%s&action=%s&field_id=%s">Edit</a>',$_REQUEST['page'],'addnew',$item['ID'])
			);
		
		$action2 = array('delete' => sprintf('<a href="?page=%s&pagetype=%s&field_id=%s" onclick="return confirm(\'Are you sure for deleteing custom field?\')">Delete Permanently</a>','custom_fields','delete',$item['ID']));		
		$actions = array_merge($action1,$action2);
		return sprintf('%1$s %2$s', $item['title'], $this->row_actions($actions , $always_visible = false) );
	}
	
	function get_bulk_actions()
	{
		$actions = array(
			'delete' => 'Delete permanently'
			);
		return $actions;
	}
	
	function column_cb($item)
	{ 
		return sprintf(
			'<input type="checkbox" name="checkbox[]" id="checkbox[]" value="%s" />', $item['ID']
			);
	}
}

/*
Name :templ_searching_filter_where
decs : searching filter for custom fields return the where condition 
*/
add_filter('posts_where', 'templ_searching_filter_where');

function templ_searching_filter_where($where){
	if(is_search() && @$_REQUEST['adv_search'] ==1)
	{
		global $wpdb;
		$serch_post_types = $_REQUEST['post_type'];
		$s = get_search_query();
		$custom_metaboxes = get_search_post_fields_templ_plugin($serch_post_types,'','user_side','1');
		foreach($custom_metaboxes as $key=>$val) {
		$name = $key;
			if($_REQUEST[$name]){ 
				$value = $_REQUEST[$name];
				if($name == 'proprty_desc' || $name == 'event_desc'){
					$where .= " AND ($wpdb->posts.post_content like \"%$value%\" )";
				} else if($name == 'property_name'){
					$where .= " AND ($wpdb->posts.post_title like \"%$value%\" )";
				}else {
					$where .= " AND ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='$name' and ($wpdb->postmeta.meta_value like \"%$value%\" ))) ";
					/* Placed "AND" instead of "OR" because of Vedran said results are ignoring address field */
				}
			}
		}
		
		 /* Added for tags searching */
		if(is_search() && !@$_REQUEST['catdrop']){
			$where .= " OR  ($wpdb->posts.ID in (select p.ID from $wpdb->terms c,$wpdb->term_taxonomy tt,$wpdb->term_relationships tr,$wpdb->posts p ,$wpdb->postmeta t where c.name like '".$s."' and c.term_id=tt.term_id and tt.term_taxonomy_id=tr.term_taxonomy_id and tr.object_id=p.ID and p.ID = t.post_id and p.post_status = 'publish' group by  p.ID))";
		}
	}
	return $where;
}
/*
Name : add_js_script
Desc : add js in file
*/

function add_js_script(){?>
	<script type="text/javascript">	
		jQuery(function(){
		var pickerOpts = {
				showOn: "both",
				dateFormat: 'yy-mm-dd',
				buttonImage: "<?php echo TEMPL_PLUGIN_URL; ?>css/datepicker/images/cal.png",
				buttonText: "Show Datepicker"
			};	
			jQuery("#todate").datepicker(pickerOpts);					
			jQuery("#frmdate").datepicker(pickerOpts);					
		});

		function sformcheck(){
		if(document.getElementById('adv_s').value==""){
			alert('<?php echo SEARCH_ALERT_MSG;?>');
			document.getElementById('adv_s').focus();
			return false;
		}
		if(document.getElementById('adv_s').value=='<?php echo SEARCH;?>'){
			document.getElementById('adv_s').value = ' ';
		}
		return true;
		}	
	</script>
<?php }

/* Fetch posts which field type has heading type */

function fetch_heading_posts()
{
	global $wpdb,$post;
	remove_all_actions('posts_where');
	$heading_title = array();
	$args=
	array( 
	'post_type' => 'custom_fields',
	'posts_per_page' => -1	,
	'post_status' => array('publish'),
	'meta_query' => array(
		'relation' => 'AND',
		array(
			'key' => 'ctype',
			'value' => 'heading_type',
			'compare' => '=',
			'type'=> 'text'
		),
		array(
			'key' => 'is_active',
			'value' => '1',
			'compare' => '=',
			'type'=> 'text'
		)
	),
	'meta_key' => 'sort_order',
	'orderby' => 'meta_value',
	'order' => 'ASC'
	);
	$post_query = null;
	$post_query = new WP_Query($args);
	$post_meta_info = $post_query;
	
	if($post_meta_info){
		while ($post_meta_info->have_posts()) : $post_meta_info->the_post();
			$heading_title[$post->post_name] = $post->post_title;
		endwhile;
	}
	return $heading_title;
}

function fetch_heading_per_post_type($post_type)
{
	global $wpdb,$post;
	remove_all_actions('posts_where');
	$heading_title = array();
	$args=
	array( 
	'post_type' => 'custom_fields',
	'posts_per_page' => -1	,
	'post_status' => array('publish'),
	'meta_query' => array(
		'relation' => 'AND',
		array(
			'key' => 'ctype',
			'value' => 'heading_type',
			'compare' => '=',
			'type'=> 'text'
		),
		array(
			'key' => 'post_type',
			'value' => $post_type,
			'compare' => 'LIKE',
			'type'=> 'text'
		)
		

	),
	'meta_key' => 'sort_order',	
	'orderby' => 'meta_value_num',
	'meta_value_num'=>'sort_order',
	'order' => 'ASC'
	);
	$post_query = null;
	remove_all_actions('posts_orderby');
	$post_query = new WP_Query($args);
	$post_meta_info = $post_query;
	
	if($post_meta_info){
		while ($post_meta_info->have_posts()) : $post_meta_info->the_post();
		$otherargs=
		array( 
		'post_type' => 'custom_fields',
		'posts_per_page' => -1	,
		'post_status' => array('publish'),
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key' => 'is_active',
				'value' => '1',
				'compare' => '=',
				'type'=> 'text'
			),
			array(
				'key' => 'heading_type',
				'value' => $post->post_title,
				'compare' => '=',
				'type'=> 'text'
			)
		));
		$other_post_query = null;
		$other_post_query = new WP_Query($otherargs);
		if(count($other_post_query->post) > 0)
		  {
			$heading_title[$post->post_name] = $post->post_title;
		  }
		endwhile;
	}
	return $heading_title;
}

function fetch_active_heading($head)
{
	global $wpdb,$post;
	$query = "SELECT $wpdb->posts.*
    FROM $wpdb->posts, $wpdb->postmeta
    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
    AND $wpdb->postmeta.meta_key = 'is_active'
	AND $wpdb->postmeta.meta_value = '1'
    AND $wpdb->posts.post_status = 'publish'
    AND $wpdb->posts.post_type = 'custom_fields'
    AND $wpdb->posts.post_title = '".$head."'";
	$querystr = $wpdb->get_row($query);
    if(count($querystr) == 0)
	 {
		return false;
	 }
	 else
	 {
		return true;
	 }
}

/* Add action for preview page */
/* Add action for preview map display */
add_action('templ_preview_address_map','templ_preview_address_map_display');
/*
 * Function Name:templ_preview_address_map_display
 * Return : Display the post preview detail map
 */
function templ_preview_address_map_display()
{	
	if(isset($_POST['address']))
	{
		 $add_str = @$_POST['address'];
		 $geo_latitude = $_POST['geo_latitude'];
	     $geo_longitude = $_POST['geo_longitude'];
		 $map_type=isset($_POST['map_view'])?$_POST['map_view']:'';		 
		?>
        <div class="row">
                <h1 class="title-container"><span><?php _e('Map'); ?></span></h1>
                <div class="clearfix"></div>
				<p><strong><?php _e('Location: '); echo $add_str;?></strong></p>

				<div id="gmap" class="graybox img-pad">
					<?php if($geo_longitude &&  $geo_latitude):
                            if($_SESSION["file_info"]):
                                foreach($_SESSION["file_info"] as $image_id=>$val):
                                    $thumb_image = get_template_directory_uri().'/images/tmp/'.$val;
                                    break;
                                endforeach;
                            endif;	
                            $pimg = $thumb_image;
                            if(!$pimg):
                                $pimg = get_template_directory_uri()."/images/img_not_available.png";
                            endif;	
                            $title = $post_title;
                            $address = $add_str;
                            require_once (TEMPL_MONETIZE_FOLDER_PATH . 'templatic-custom_fields/preview_map.php');
                            $retstr .= "<div class=\"forrent\"><img src=\"$pimg\" width=\"192\" height=\"134\" alt=\"\" />";
                            $retstr .= "<h6><a href=\"\" class=\"ptitle\" style=\"color:#444444;font-size:14px;\"><span>$title</span></a></h6>";
                            if($address){$retstr .= "<span style=\"font-size:10px;\">$address</span>";}
							$retstr .= "</div>";
							
                            preview_address_google_map_plugin($geo_latitude,$geo_longitude,$retstr,$map_type);
                          else: ?>
                        <iframe src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=<?php echo $add_str;?>&amp;ie=UTF8&amp;z=14&amp;iwloc=A&amp;output=embed" height="358" width="100%" scrolling="no" frameborder="0" ></iframe>
                    <?php endif; ?>
				
				</div>
			</div>
        <?php
		
	}
}
/* add action for display preview detail page fields collectio */
add_action('tmpl_preview_page_fields_collection','tmpl_preview_detail_page_fields_collection_display');
function tmpl_preview_detail_page_fields_collection_display($cur_post_type)
{		
	if(is_active_addons('custom_fields_templates'))
	{
		$heading_type = fetch_heading_per_post_type($cur_post_type);
		if(count($heading_type) > 0)
		 {
			foreach($heading_type as $_heading_type)
			 {
				$post_meta_info = tmpl_show_on_detail($cur_post_type,$_heading_type); /* return fields selected for detail page  */
			 }
		 }
		else
		 {
			 $post_meta_info = tmpl_show_on_detail($cur_post_type,''); /* return fields selected for detail page  */
		 }
	}
	else{
			$post_meta_info = array();
		}
	
	if($post_meta_info)
	{ /* DISPLAY CUSTOM FIELDS VALUE */
		do_action('templatic_fields_onpreview',$_SESSION['custom_fields'],$cur_post_type);
	}	
	
	
}

/* Add Action for display the preview page post image gallery  */
add_action('tmpl_preview_page_gallery','tmpl_preview_detail_page_gallery_display');
function tmpl_preview_detail_page_gallery_display()
{
	?>
    <div>
    <?php
	$thumb_img_counter = 0;
	/* gallery begin */
		if($_SESSION["file_info"])
		{
			$thumb_img_counter = $thumb_img_counter+count($_SESSION["file_info"]);
			$image_path = get_image_phy_destination_path_plugin();
			$tmppath = "/".$upload_folder_path."tmp/";
			foreach($_SESSION["file_info"] as $image_id=>$val):
				$thumb_image = get_template_directory_uri().'/images/tmp/'.$val;
				break;
			endforeach;	
		 ?>
             <div class="content_details">
                 <div class="graybox">
                 <?php $f=0; foreach($_SESSION["file_info"] as $image_id=>$val):
				 		$curry = date("Y");
                        $currm = date("m");
                        $src = TEMPLATEPATH.'/images/tmp/'.$val;
						$img_title = pathinfo($val);									 
				  ?>
                    <?php if($largest_img_arr): ?>
                    		<?php  foreach($largest_img_arr as $value):
								 $name = end(explode("/",$value['file']));
								  if($val == $name):	
							?>
			               		<img src="<?php echo  $value['file'];?>" alt="" />
                        	<?php endif;
								endforeach;?>
                    <?php else: ?>
                        <img src="<?php echo $thumb_image;?>" alt="" />
                    <?php endif; ?>    
                  <?php if($f==0) break; endforeach;?>
                 </div>
             </div>
             <div class="title-container">
                <h1><?php echo MORE_PHOTOS; ?></h1>
              </div>
             <div id="gallery">
			 	<ul class="more_photos">
				 <?php
                    foreach($_SESSION["file_info"] as $image_id=>$val)
                    {
                        $curry = date("Y");
                        $currm = date("m");
                        $src = TEMPLATEPATH.'/images/tmp/'.$val;
						$img_title = pathinfo($val);						
                        if($val):
                        if(file_exists($src)):
                           		 $thumb_image = get_template_directory_uri().'/images/tmp/'.$val; ?>
                            	 <li><a href="<?php echo $thumb_image;?>" title="<?php echo $img_title['filename']; ?>"><img src="<?php echo $thumb_image;?>" alt="" height="70" width="70" title="<?php echo $img_title['filename'] ?>" /></a></li>
                        <?php else: ?>
                            <?php
								if($largest_img_arr):
                                foreach($largest_img_arr as $value):
                                    $name = end(explode("/",$value['file']));									
                                    if($val == $name):?>
                                    <li><a href="<?php echo $value['file']; ?>" title="<?php echo $img_title['filename']; ?>"><img src="<?php echo $value['file'];?>" alt="" height="70" width="70" title="<?php echo $img_title['filename'] ?>" /></a></li>
                            <?php
                                    endif;
                                endforeach;
								endif;
                            ?>
                        <?php endif; ?>
                        
                        <?php else: ?>
                        <?php if($thumb_img_arr): ?>
                            <?php 
                            $thumb_img_counter = $thumb_img_counter+count($thumb_img_arr);
                            for($i=0;$i<count($thumb_img_arr);$i++):
                                $thumb_image = $large_img_arr[$i];
								
								if(!is_array($thumb_image)):
                            ?>
                              <li><a href="<?php echo $thumb_image;?>" title="<?php echo $img_title['filename']; ?>"><img src="<?php echo $thumb_image;?>" alt="" height="70" width="70" title="<?php echo $img_title['filename'] ?>" /></a></li>
                              <?php endif;?>
                        <?php endfor; ?>
                        <?php endif; ?>	
                        <?php endif; ?>
                    <?php
                    $thumb_img_counter++;
                    } ?>
					</ul>
             </div>
	<?php }/* gallery end */?>
	    <div class="clearfix"></div>
    </div>
<?php
}
/*  Finish add action for preview page */
/* 
 * add action for display file upload custom field
 */
add_action('templ_preview_page_file_upload','templ_preview_page_file_upload_display');
function templ_preview_page_file_upload_display()
{
	global $upload;
	if($_FILES && $upload)
	{
		foreach($upload as $_upload)
		 {
			$upload_file[$_upload] = get_file_upload($_FILES[$_upload]);
			$_SESSION['upload_file'] = $upload_file;
		 }
	}
	?>
	<?php
	if($_SESSION['upload_file']):
	   foreach($_SESSION['upload_file'] as $fileval):
		if($fileval):
	?>
		  <p><?php _e('Click here to download File'); ?> <a href="<?php echo $fileval; ?>" class="normal_button"><?php _e('Download',DOMAIN); ?></a></p>
	<?php
		endif;
	   endforeach;
	endif;	
}

/*
 * Advance search function 
 */
if(!is_admin())
{
	add_action('pre_get_posts', 'advance_search_template_function',11);
}
function advance_search_template_function($query){		
	if(is_search() && (isset($_REQUEST['search_template']) && $_REQUEST['search_template']==1) )
	{		
		remove_all_actions('posts_where');
		add_filter('posts_where', 'advance_search_template_where');		
	}	
}
/*
 * Function Name: advance_search_template_where
 * Return : sql where 
 */
function advance_search_template_where($where)
{	
	if(isset($_REQUEST['search_template']) && $_REQUEST['search_template']==1 && is_search())
	{		
		global $wpdb;
		$post_type=$_REQUEST['post_type'];
		$tag_s=$_REQUEST['tag_s'];
		$taxonomies = get_object_taxonomies( (object) array( 'post_type' => $post_type,'public'   => true, '_builtin' => true ));
		$todate = trim($_REQUEST['todate']);		
		$frmdate = trim($_REQUEST['frmdate']);
		$articleauthor = trim($_REQUEST['articleauthor']);
		$exactyes = trim($_REQUEST['exactyes']);
		
		if(isset($_REQUEST['todate']) && $_REQUEST['todate'] != ""){
			$todate = $_REQUEST['todate'];
			$todate= explode('/',$todate);
			$todate = $todate[2]."-".$todate[0]."-".$todate[1];
			
		}
		if(isset($_REQUEST['frmdate']) && $_REQUEST['frmdate'] != ""){
			$frmdate = $_REQUEST['frmdate'];
			$frmdate= explode('/',$frmdate);
			$frmdate = $frmdate[2]."-".$frmdate[0]."-".$frmdate[1];
		}
		
		
		
		if($todate!="" && $frmdate=="")
		{
			$where .= " AND   DATE_FORMAT($wpdb->posts.post_date,'%Y-%m-%d %G:%i:%s') >='".$todate."'";
		}
		else if($frmdate!="" && $todate=="")
		{
			
			$where .= " AND  DATE_FORMAT($wpdb->posts.post_date,'%Y-%m-%d %G:%i:%s') <='".$frmdate."'";
		}
		else if($todate!="" && $frmdate!="")
		{
			$where .= " AND  DATE_FORMAT($wpdb->posts.post_date,'%Y-%m-%d %G:%i:%s') BETWEEN '".$todate."' and '".$frmdate."'";
			
		}
		if($articleauthor!="" && $exactyes!=1)
		{
			$where .= " AND  $wpdb->posts.post_author in (select $wpdb->users.ID from $wpdb->users where $wpdb->users.display_name  like '".$articleauthor."') ";
		}
		if($articleauthor!="" && $exactyes==1)
		{
			$where .= " AND  $wpdb->posts.post_author in (select $wpdb->users.ID from $wpdb->users where $wpdb->users.display_name  = '".$articleauthor."') ";
		}		
		//search custom field
		if(isset($_REQUEST['search_custom']) && is_array($_REQUEST['search_custom']))
		{
			foreach($_REQUEST['search_custom'] as $key=>$value)
			{		
				if($_REQUEST[$key]!="" && $key != 'category')
				{
					$where .= " AND ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='$key' and ($wpdb->postmeta.meta_value like \"%$_REQUEST[$key]%\" ))) ";					
				}
			}
		}
		//finish custom field			
		
		if(isset($_REQUEST['category']) && $_REQUEST['category']!="")
		{
			$scat = $_REQUEST['category'];
			$where .= " AND  $wpdb->posts.ID in (select $wpdb->term_relationships.object_id from $wpdb->term_relationships join $wpdb->term_taxonomy on $wpdb->term_taxonomy.term_taxonomy_id=$wpdb->term_relationships.term_taxonomy_id where $wpdb->term_taxonomy.taxonomy=\"$taxonomies[0]\" AND $wpdb->term_taxonomy.term_id=\"$scat\" ) ";
		}
		
		 /* Added for tags searching */
		if(is_search() && $_REQUEST['tag_s']!=""){
			$where .= " OR  ($wpdb->posts.ID in (select p.ID from $wpdb->terms c,$wpdb->term_taxonomy tt,$wpdb->term_relationships tr,$wpdb->posts p ,$wpdb->postmeta t where c.name like '".$tag_s."' and c.term_id=tt.term_id and tt.term_taxonomy_id=tr.term_taxonomy_id and tr.object_id=p.ID and p.ID = t.post_id and p.post_status = 'publish' group by  p.ID))";
		}	
		return $where;
	}
	return $where;
}

function upload_admin_scripts_custom_fields()
 {
	wp_register_script('organize-upload', __(plugin_dir_url( __FILE__ ),DOMAIN).'js/upload-script.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('organize-upload');
 }
 
$post_type = get_post_type( $_REQUEST['post'] );
if (is_active_addons('custom_fields_templates')) {
	add_action('admin_print_scripts', 'upload_admin_scripts_custom_fields');
}

/////////////////Post EXPIRY SETTINGS CODING START/////////////////
global $table_prefix, $wpdb;
$table_name = $table_prefix . "post_expire_session";
$current_date = date('Y-m-d');
if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
{
   global $table_prefix, $wpdb,$table_name;
	$sql = 'CREATE TABLE `'.$table_name.'` (
			`session_id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`execute_date` DATE NOT NULL ,
			`is_run` TINYINT( 4 ) NOT NULL DEFAULT "0"
			) ENGINE = MYISAM ;';
   $wpdb->query($sql);
}

$today_executed = $wpdb->get_var("select session_id from $table_name where execute_date=\"$current_date\"");
if($today_executed && $today_executed>0){ 
}else{ 
		$tmpdata = get_option('templatic_settings');
		$listing_email_notification = $tmpdata['listing_email_notification'];
		if($listing_email_notification != ""){
			$number_of_grace_days = $listing_email_notification;

			$postid_str = $wpdb->get_results("select p.ID,p.post_author,p.post_date, p.post_title from $wpdb->posts p where p.post_status='publish'  and datediff(\"$current_date\",date_format(p.post_date,'%Y-%m-%d')) = (select meta_value from $wpdb->postmeta pm where post_id=p.ID  and meta_key='alive_days')-$number_of_grace_days");

			foreach($postid_str as $postid_str_obj)
			{
				
				$ID = $postid_str_obj->ID;
				$auth_id = $postid_str_obj->post_author;
				$post_author = $postid_str_obj->post_author;
				$post_date = date_i18n('dS m,Y',strtotime($postid_str_obj->post_date));
				$post_title = $postid_str_obj->post_title;
				$userinfo = $wpdb->get_results("select user_email,display_name,user_login from $wpdb->users where ID=\"$auth_id\"");
				
				do_action('tmpl_post_expired_beforemail',$postid_str_obj);
				
				$user_email = $userinfo[0]->user_email;
				$display_name = $userinfo[0]->display_name;
				$user_login = $userinfo[0]->user_login;
				
				$fromEmail = get_site_emailId();
				$fromEmailName = get_site_emailName();
				$store_name = get_option('blogname');
				$alivedays = get_post_meta($ID,'alive_days',true);
				$productlink = get_permalink($ID);
				$loginurl = home_url().'/?ptype=login';
				$siteurl = home_url();
				$client_message = __("<p>Dear $display_name,<p><p>Your listing -<a href=\"$productlink\"><b>$post_title</b></a> posted on  <u>$post_date</u> for $alivedays days.</p>
				<p>It's going to expiry after $number_of_grace_days day(s). If the listing expire, it will no longer appear on the site.</p>
				<p> If you want to renew, Please login to your member area of our site and renew it as soon as it expire. You may like to login the site from <a href=\"$loginurl\">$loginurl</a>.</p>
				<p>Your login ID is <b>$user_login</b> and Email ID is <b>$user_email</b>.</p>
				<p>Thank you,<br />$store_name.</p>","templatic");				
				$subject = __('Listing expiration Notification','templatic');
				templ_send_email($fromEmail,$fromEmailName,$user_email,$display_name,$subject,$client_message,$extra='');
				do_action('tmpl_post_expired_aftermail');
			}
		}
		
		$postid_str = $wpdb->get_var("select group_concat(p.ID) from $wpdb->posts p where  p.post_status='publish'  and datediff(\"$current_date\",date_format(p.post_date,'%Y-%m-%d')) = (select meta_value from $wpdb->postmeta pm where post_id=p.ID  and meta_key='alive_days')");

		if($postid_str)
		{
			$tmpdata = get_option('templatic_settings');
			$listing_ex_status = $tmpdata['post_listing_ex_status'];
			if($listing_ex_status=='')
			{
				$listing_ex_status = 'draft';
			}
			$wpdb->query("update $wpdb->posts set post_status=\"$listing_ex_status\" where ID in ($postid_str)");
		}

		$wpdb->query("insert into $table_name (execute_date,is_run) values (\"$current_date\",'1')");
	
}

add_action('init','tev_success_msg');
function tev_success_msg(){
	add_action('tevolution_submition_success_msg','tevolution_submition_success_msg_fn');
}
function tevolution_submition_success_msg_fn(){
	global $wpdb;
	$paymentmethod = get_post_meta($_REQUEST['pid'],'paymentmethod',true);
	$paidamount = get_post_meta($_REQUEST['pid'],'paid_amount',true);
	$paid_amount = display_amount_with_currency_plugin(get_post_meta($_REQUEST['pid'],'paid_amount',true));
	
	
	$permalink = get_permalink($_REQUEST['pid']);
	$RequestedId = $_REQUEST['pid'];
	
	$tmpdata = get_option('templatic_settings');
	$post_default_status = $tmpdata['post_default_status'];
	if($post_default_status == 'publish'){
		$post_link = "<p><a href='".get_permalink($_REQUEST['pid'])."'  class='btn_input_highlight' >View your submitted Post &raquo;</a></p>";
	}else{
		$post_link ='';
	}
	$store_name = get_option('blogname');
	
	if($paymentmethod == 'prebanktransfer')
	{
		$paymentupdsql = "select option_value from $wpdb->options where option_name='payment_method_".$paymentmethod."'";
		$paymentupdinfo = $wpdb->get_results($paymentupdsql);
		$paymentInfo = unserialize($paymentupdinfo[0]->option_value);
		$payOpts = $paymentInfo['payOpts'];
		$bankInfo = $payOpts[0]['value'];
		$accountinfo = $payOpts[1]['value'];
	}
	$orderId = $_REQUEST['pid'];
	$siteName = "<a href='".site_url()."'>".$store_name."</a>";
	$search_array = array('[#payable_amt#]','[#bank_name#]','[#account_number#]','[#submition_Id#]','[#store_name#]','[#submited_information_link#]','[#site_name#]');
	$replace_array = array($paid_amount,@$bankInfo,@$accountinfo,$order_id,$store_name,$post_link,$siteName);
	
	if($paymentmethod == 'prebanktransfer'){
		$filecontent = stripslashes(get_option('post_pre_bank_trasfer_msg_content'));
		if(!$filecontent){
			$filecontent = POST_POSTED_SUCCESS_PREBANK_MSG;
		}
	}else{
		$filecontent = stripslashes(get_option('post_added_success_msg_content'));
		if(!$filecontent){
			$filecontent = POST_SUCCESS_MSG;
		}
	}
	$filecontent = str_replace($search_array,$replace_array,$filecontent); 
	echo $filecontent;
}
/* add feature listing options */
add_action('init','tevolution_add_featured_fn1');
function tevolution_add_featured_fn1(){
	add_action('tevolution_featured_list','tevolution_featured_list_fn');
}

/* Function : tevolution_show_term_and_condition
   Desc : to display terms and conditions checkbox
*/
function tevolution_show_term_and_condition()
{
	$tmpdata = get_option('templatic_settings');
	if($tmpdata['tev_accept_term_condition'] && $tmpdata['tev_accept_term_condition'] == 1){	?>
			<div class="form_row clearfix">
             	<label>&nbsp;</label>
             	 <input name="term_and_condition" id="term_and_condition" value="" type="checkbox" class="chexkbox" onclick="hide_error()"/>
                 <?php if($tmpdata['term_condition_content']!=''){
				 echo stripslashes($tmpdata['term_condition_content']); 
				 }else{
					_e('Accept terms and conditions.','templatic');
				 }?>
				 <span class="error" id="terms_error"></span>
            </div>
            <script type="text/javascript">
			  function hide_error(){
				if(document.getElementById('term_and_condition').checked)
				{
					document.getElementById('terms_error').innerHTML  = '';
				}
			  }
              function check_term_condition()
			  {
				if(eval(document.getElementById('term_and_condition')))  
				{
					if(document.getElementById('term_and_condition').checked)
					{	
						return true;
					}else
					{
						//alert('<?php _e('Please accept Term and Conditions','templatic');?>');
						document.getElementById('terms_error').innerHTML  = 'Please accept Term and Conditions.';
						return false;
					}
				}
			  }
            </script>
    <?php global $submit_button;
		$submit_button = 'onclick="return check_term_condition();"';
	}
}


/*
 * Function Name: tevolution_submition_success_post_submited_content
 * Return: display the submited post information
 */

add_action('tevolution_submition_success_post_content','tevolution_submition_success_post_submited_content');
function tevolution_submition_success_post_submited_content()
{
	?>
     <!-- Short Detail of post -->
	<div class="title-container">
		<h1><?php  _e(POST_DETAIL,DOMAIN);?></h1>
	</div>
    <div class="submited_info">
	<?php
	global $wpdb,$post;
	remove_all_actions('posts_where');
	$cus_post_type = get_post_type($_REQUEST['pid']);
	$args = 
	array( 'post_type' => 'custom_fields',
	'posts_per_page' => -1	,
	'post_status' => array('publish'),
	'meta_query' => array(
	   'relation' => 'AND',
		array(
			'key' => 'post_type_'.$cus_post_type.'',
			'value' => $cus_post_type,
			'compare' => '=',
			'type'=> 'text'
		),
		array(
			'key' => 'show_on_page',
			'value' =>  array('user_side','both_side'),
			'compare' => 'IN'
		),
		array(
			'key' => 'is_active',
			'value' =>  '1',
			'compare' => '='
		),
		array(
			'key' => 'show_on_success',
			'value' =>  '1',
			'compare' => '='
		)
	),
		'meta_key' => 'sort_order',
		'orderby' => 'meta_value',
		'order' => 'ASC'
	);
	$post_query = null;
	add_filter('posts_join', 'custom_field_posts_where_filter');
	$post_meta_info = new WP_Query($args);	
	
	remove_filter('posts_join', 'custom_field_posts_where_filter');
	$suc_post = get_post($_REQUEST['pid']);
		if($post_meta_info)
		  {
			echo "<div class='grid02 rc_rightcol clearfix'>";
			echo "<ul class='list'>";
			//echo "<li><p>Post Title : </p> <p> ".stripslashes($suc_post->post_title)."</p></li>";
			printf( __( '<li><p>Post Title :</p> <p> %s </p></li>', DOMAIN ), stripslashes($suc_post->post_title) ); 
			while ($post_meta_info->have_posts()) : $post_meta_info->the_post();
				$post->post_name=get_post_meta(get_the_ID(),'htmlvar_name',true);
				if(get_post_meta($_REQUEST['pid'],$post->post_name,true))
					  {
						if(get_post_meta($post->ID,"ctype",true) == 'multicheckbox')
						  {
							$_value = '';
							foreach(get_post_meta($_REQUEST['pid'],$post->post_name,true) as $value)
							 {
								$_value .= $value.",";
							 }
							 echo "<li><p>".stripslashes($post->post_title)." : </p> <p> ".substr($_value,0,-1)."</p></li>";
						  }
						else
						 {
							 $custom_field=get_post_meta($_REQUEST['pid'],$post->post_name,true);
							 if(substr($custom_field, -4 ) == '.jpg' || substr($custom_field, -4 ) == '.png' || substr($custom_field, -4 ) == '.gif' || substr($custom_field, -4 ) == '.JPG' 
											|| substr($custom_field, -4 ) == '.PNG' || substr($custom_field, -4 ) == '.GIF'){
								  echo "<li><p>".stripslashes($post->post_title)." : </p> <p> <img src='".$custom_field."' /></p></li>";
							 }							 
							 else
							 {
							   if(get_post_meta($post->ID,'ctype',true) == 'upload')
							    {
							   	  echo "<li><p>".stripslashes($post->post_title)." : </p> <p> Click here to download File <a href=".get_post_meta($_REQUEST['pid'],$post->post_name,true).">Download</a></p></li>";
							    }
							   else
							    {
  								  echo "<li><p>".stripslashes($post->post_title)." : </p> <p> ".get_post_meta($_REQUEST['pid'],$post->post_name,true)."</p></li>";
								}
							 }
						 }
					  }
					if($post->post_name == 'post_content' && $suc_post->post_content!='')
					 {
						$suc_post_con = $suc_post->post_content;
					 }
					if($post->post_name == 'post_excerpt' && $suc_post->post_excerpt!='')
					 {
						$suc_post_excerpt = $suc_post->post_excerpt;
					 }

					if(get_post_meta($post->ID,"ctype",true) == 'geo_map')
					 {
						$add_str = get_post_meta($_REQUEST['pid'],'address',true);
						$geo_latitude = get_post_meta($_REQUEST['pid'],'geo_latitude',true);
						$geo_longitude = get_post_meta($_REQUEST['pid'],'geo_longitude',true);
						$map_view = get_post_meta($_REQUEST['pid'],'map_view',true);
					 }
  
			endwhile;
			if(is_active_addons('monetization') && $paidamount > 0){
				fetch_payment_description($_REQUEST['pid']);
			}
			echo "</ul>";
			echo "</div>";
		  }		 
		do_action('after_tevolution_success_msg');
	?>
	</div>
	<?php if(isset($suc_post_con)): ?>
	    <div class="row">
		  <div class="twelve columns">
			  <div class="title_space">
				 <div class="title-container">
					<h1><?php _e('Post Description', DOMAIN);?></h1>
				 </div>
				 <p><?php echo nl2br($suc_post_con); ?></p>
			  </div>
		   </div>
	    </div>
	<?php endif; ?>
	
	<?php if(isset($suc_post_excerpt)): ?>
		 <div class="row">
			<div class="twelve columns">
				<div class="title_space">
					<div class="title-container">
						<h1><?php _e('Post Excerpt',DOMAIN);?></h1>
					</div>
					<p><?php echo nl2br($suc_post_excerpt); ?></p>
				</div>
			</div>
		</div>
	<?php endif; ?>
	
	<?php
	if(@$add_str)
	{
	?>
		<div class="row">
			<div class="title_space">
				<div class="title-container">
					<h1><?php _e('Map',DOMAIN); ?></h1>
				</div>
				<p><strong><?php _e('Location',DOMAIN); echo " : "; echo $add_str;?></strong></p>
			</div>
			<div id="gmap" class="graybox img-pad">
				<?php if($geo_longitude &&  $geo_latitude):
						$pimgarr = bdw_get_images_plugin($_REQUEST['pid'],'thumb',1);
						$pimg = $pimgarr[0];
						if(!$pimg):
							$pimg = plugin_dir_url( __FILE__ )."images/img_not_available.png";
						endif;	
						$title = stripslashes($suc_post->post_title);
						$address = $add_str;
						require_once (TEMPL_MONETIZE_FOLDER_PATH . 'templatic-custom_fields/preview_map.php');
						$retstr ="";
						$retstr .= "<div class=\"forrent\"><img src=\"$pimg\" width=\"192\" height=\"134\" alt=\"\" />";
						$retstr .= "<h6><a href=\"\" class=\"ptitle\" style=\"color:#444444;font-size:14px;\"><span>$title</span></a></h6>";
						if($address){$retstr .= "<span style=\"font-size:10px;\">$address</span>";}
						$retstr .= "</div>";
						preview_address_google_map_plugin($geo_latitude,$geo_longitude,$retstr,$map_view);
					  else:
				?>
						<iframe src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=<?php echo $add_str;?>&amp;ie=UTF8&amp;z=14&amp;iwloc=A&amp;output=embed" height="358" width="100%" scrolling="no" frameborder="0" ></iframe>
				<?php endif; ?>
			</div>
		</div>
	<?php } ?>
	
	
	<!-- End Short Detail of post -->

     <?php
}

?>