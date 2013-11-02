<?php
/*
Template Name: Page - Submit Form
*/
if(session_id()=="")
	session_start();
get_header();

	
if ( current_theme_supports( 'breadcrumb-trail' ) ) breadcrumb_trail( array( 'separator' => '&raquo;' ) ); 


?>

	<?php do_action( 'before_content' ); // supreme_before_content ?>

	<div id="content">

	<?php do_action( 'open_content' ); // supreme_open_content ?>

	<div class="hfeed">
<?php
	global $post,$current_user,$all_cat_id;
	/* If user not login and try to edit post then run this code */
	if(!$current_user->ID && $_REQUEST['action'] == 'edit' && $_REQUEST['pid'])
	{
		wp_redirect(get_settings('home').'/index.php?ptype=login');
		exit;
	}
	/* End */
	$cat_array = array();
	$tmpdata = get_option('templatic_settings');
	$post_type = get_post_meta($post->ID,'template_post_type',true);
	$post_type_search = in_array($post_type,array_keys(get_option('templatic_custom_post')));
	if(!$post_type_search && $post_type !='post')
	 {
		$post_type = '';
	 }

	if(isset($_REQUEST['pid']) && $_REQUEST['pid'] !=''){
		$edit_id = $_REQUEST['pid']; /* edit post id */
	}else{ $edit_id =''; }
	if((isset($_REQUEST['category']) && count($_REQUEST['category']) > 0 ) || $tmpdata['templatic-category_custom_fields'] == 'No' || $_REQUEST['fields'])
		if(isset($_REQUEST['lang']) && $_REQUEST['lang']!="")
		{
			$form_action_url = tmpl_get_ssl_normal_url(site_url().'/?page=preview&lang='.$_REQUEST['lang']);
		}else{
			$form_action_url = tmpl_get_ssl_normal_url(site_url().'/?page=preview');
		}
	elseif($_REQUEST['backandedit'] && isset($_SESSION['custom_fields']))
		$form_action_url = get_permalink($post->ID).'?backandedit=1&fields=custom_fields';
	elseif($tmpdata['templatic-category_custom_fields'] == 'Yes' && $_REQUEST['action'] == 'edit')
		if(isset($_REQUEST['lang']) && $_REQUEST['lang']!="")
		{
			$form_action_url = tmpl_get_ssl_normal_url(site_url().'/?page=preview&lang='.$_REQUEST['lang']);
		}
		else{
			$form_action_url = tmpl_get_ssl_normal_url(site_url().'/?page=preview');
		}
	else
	$form_action_url = get_permalink($post->ID);
	$post_id = $post->ID;
	$taxonomy = fetch_page_taxonomy($post_id);
	$cat_display = $tmpdata['templatic-category_type'];
	if(!$cat_display)
	  {
		$cat_display = 'checkbox';
	  }

	if(isset($edit_id) && $edit_id !='')
	{	
		/* fetch categories on renew and edit */
		if(isset($_REQUEST['category']) && count($_REQUEST['category']) > 0)
		{
			$category = $_REQUEST['category'];
			$_SESSION['category'] = $category;
		}

		global $monetization;
		$get_category = wp_get_post_terms($edit_id,$taxonomy);
		foreach($get_category as $_get_category)
		 {
			 $cat_array[] = $_get_category->term_id;
		 }
		$all_cat_id = implode(',',$cat_array);
	}
	else
	{ 
		if(isset($_REQUEST['category']) && count($_REQUEST['category']) > 0)
		{
			$category = $_REQUEST['category'];
			$_SESSION['category'] = $category;
		}
		if(isset($cat_display) && $cat_display == 'checkbox' && isset($_SESSION['category']) && $_SESSION['category'] != '')
		{
			foreach($_SESSION['category'] as $_category_arr)
			 {
				$category[] = explode(",",$_category_arr);
			 }
			foreach($category as $_category)
			 {
				 $arr_category[] = $_category[0];
			 }
			$cat_array = $arr_category;
		}
		else
		{
			if(isset($_REQUEST['category']) && count($_REQUEST['category']) > 0)
			{
				$category = $_REQUEST['category'];
				$_SESSION['category'] = $category;
			}
			if(isset($_SESSION['category']))
			{
				global $monetization;
				if(class_exists('monetization')){
					$cat_array = $monetization->templ_get_selected_category_id($_SESSION['category']);
					$cat_array_price = $monetization->templ_fetch_category_price($_SESSION['category']);
				}
			}
		}
	 }
$form_fields = array();
if(!isset($_REQUEST['category']) && count(@$_REQUEST['category']) <= 0 && !isset($_REQUEST['fields']) && @$_REQUEST['fields'] =='' && $tmpdata['templatic-category_custom_fields'] == 'Yes'  && @$_REQUEST['action'] != 'edit')
{
	$form_fields['category'] = array(
				   'name'	=> $taxonomy,
				   'espan'	=> 'category_span',
				   'type'	=> $tmpdata['templatic-category_type'],
				   'text'	=> 'Please select Category',
				   'validation_type' => 'require');
  }
  else
  {
		if($tmpdata['templatic-category_custom_fields'] == 'No')
		{
			$form_fields['category'] = array(
					   'name'	=> $taxonomy,
					   'espan'	=> 'category_span',
					   'type'	=> 'checkbox',
					   'text'	=> 'Please select Category',
					   'validation_type' => 'require');
		}
		remove_all_actions('posts_where');
		if($tmpdata['templatic-category_custom_fields'] == 'No')
		{
			$args=
			array( 'post_type' => 'custom_fields',
			'posts_per_page' => -1	,
			'post_status' => array('publish'),
			'meta_query' => array(
			   'relation' => 'AND',
				array(
					'key' => 'post_type_'.get_post_meta($post->ID,'template_post_type',true).'',
					'value' =>array( get_post_meta($post->ID,'template_post_type',true),'all'),
					'compare' => 'IN',
					'type'=> 'text'
				),
				array(
					'key' => 'show_on_page',
					'value' =>  array('user_side','both_side'),
					'compare' => 'IN'
				),
				array(
					'key' => 'validation_type',
					'value' =>  '',
					'compare' => '!='
				)
			)
			);
		}
		else
		{
			if((isset($_REQUEST['category']) && $_REQUEST['category']!="") || $_REQUEST['backandedit'] == 1)
				$all_cat_id = implode(",",templ_get_custom_categoryid($_SESSION['category']));
			$args=
			array( 'post_type' => 'custom_fields',
			'posts_per_page' => -1	,
			'post_status' => array('publish'),
			'meta_query' => array(
			   'relation' => 'AND',
				array(
					'key' => 'post_type_'.get_post_meta($post->ID,'template_post_type',true).'',
					'value' =>array( get_post_meta($post->ID,'template_post_type',true),'all'),
					'compare' => 'IN',
					'type'=> 'text'
				),
				array(
					'key' => 'show_on_page',
					'value' =>  array('user_side','both_side'),
					'compare' => 'IN'
				),
				array(
					'key' => 'validation_type',
					'value' =>  '',
					'compare' => '!='
				)
			)
			,
			'tax_query' => array(
					'relation' => 'OR',
				array(
					'taxonomy' => $taxonomy,
					'field' => 'id',
					'terms' => array($all_cat_id),
					'operator'  => 'IN'
				),
				array(
					'taxonomy' => 'category',
					'field' => 'id',
					'terms' => 1,
					'operator'  => 'IN'
				)
				
			 )
			);
		}
		$extra_field_sql = null;
		$extra_field_sql = new WP_Query($args);

if($extra_field_sql->have_posts())
 {
	while ($extra_field_sql->have_posts()) : $extra_field_sql->the_post();
		$title = get_post_meta($post->ID,'site_title',true);
		$name = get_post_meta($post->ID,'htmlvar_name',true);
		$type = get_post_meta($post->ID,'ctype',true);
		$require_msg = get_post_meta($post->ID,'field_require_desc',true);
		$is_require = get_post_meta($post->ID,'is_require',true);
		$validation_type = get_post_meta($post->ID,'validation_type',true);
		if($name != 'category')
		{
			$form_fields[$name] = array(
					   'title'	=> $title,
					   'name'	=> $name,
					   'espan'	=> $name.'_error',
					   'type'	=> $type,
					   'text'	=> $require_msg,
					   'is_require'	=> $is_require,
					   'validation_type' => $validation_type);
		}
	endwhile;
  }
}
$validation_info = array();
 foreach($form_fields as $key=>$val)
			{
				$str = ''; $fval = '';
				$field_val = $key.'_val';
				if(!isset($val['title']))
				   {
					 $val['title'] = '';
				   }
				$validation_info[] = array(
											   'title'	=> $val['title'],
											   'name'	=> $key,
											   'espan'	=> $key.'_error',
											   'type'	=> $val['type'],
											   'text'	=> $val['text'],
											   'is_require'	=> $val['is_require'],
											   'validation_type'	=> $val['validation_type']);
			}
/* CONDOTION TO SHOW AN ERROR MSG IF USER'S IP IS BLOCKED */
			
$ip = templ_fetch_ip();
if($ip == "")
{ 
	wp_reset_query();
	global $post,$wp_query;  
/*  Edit title of submit form page template when editing any post START  */
	if(isset($_REQUEST['pid']) && isset($_REQUEST['action']) && $_REQUEST['pid']!="" && $_REQUEST['action']!="" && $_REQUEST['action']=="edit"){
		add_action('the_title','nightlife_the_title',10,2);
	}
	if(isset($_REQUEST['pid']) && $_REQUEST['pid']!=""){
		function nightlife_the_title($title){
			$post_type = get_post_type($_REQUEST['pid']);
			$title = __("Edit ".$post_type);
			return $title;
		}
	}
	/*  Edit title of submit form page template when editing any post END  */
	
	?>
	
	<h1><?php the_title();?></h1>
	<?php remove_action('the_title','nightlife_the_title');?>
	<p><?php echo get_the_content($post->ID); ?></p>
    <?php
	$page_post_type = get_post_meta($post->ID,'template_post_type',true);
    if(!$page_post_type)
	{
		echo '<p><span class="message_error2" style="font-size:18px;">You have not seleted any post type yet.</span></p>';
	}

	if(isset($_REQUEST['ecptcha']) == 'captch') {
	$a = get_option("recaptcha_options");
	$blank_field = $a['no_response_error'];
	$incorrect_field = $a['incorrect_response_error'];
	echo '<div class="error_msg">'.$incorrect_field.'</div>';
	}
	if(isset($_REQUEST['invalid']) == 'playthru') {
		echo '<div class="error_msg">You need to play the game to submit post successfully.</div>';
	}
     ?>

	<!-- Start Login Form -->
	<?php if($current_user->ID=='' && is_active_addons('templatic-login') && isset($_REQUEST['category']) && count($_REQUEST['category']) > 0 && ($current_user->ID=='' && !isset($_REQUEST['fields']) && $_REQUEST['fields'] =='')  || ($current_user->ID=='' && $tmpdata['templatic-category_custom_fields'] == 'No') && is_active_addons('templatic-login')) {  
			templ_fecth_login_onsubmit(); 
	} ?>
	<!-- End Login Form -->
	<?php
	/* Edit Form Security Code */
	$post_sql = $wpdb->get_row("select post_author,ID from $wpdb->posts where post_author = '".$current_user->ID."' and ID = '".@$_REQUEST['pid']."'");
	if((count($post_sql) <= 0) && ($current_user->ID != '') && ($current_user->ID != 1) && (isset($_REQUEST['pid'])))
		{ 
			echo "ERROR: Sorry, you are not allowed to edit this post.";
		}
	else
	{ 
		global $submit_form_validation_id;
		$submit_form_validation_id = "submit_form";
	?>
	<form name="submit_form" id="submit_form" class="form_front_style" action="<?php echo $form_action_url; ?>" method="post" enctype="multipart/form-data">
		
	<?php
		if(is_active_addons('templatic-login') && $current_user->ID=='' && (($tmpdata['templatic-category_custom_fields'] == 'Yes') || $tmpdata['templatic-category_custom_fields'] == 'No')){
			templ_fetch_registration_onsubmit(); /* display registration form is registration addon activate */
		}
		global $post;
		$action = @$_REQUEST['action'];
		wp_reset_query();
	
		//if(isset($_SESSION['category']) && count($_SESSION['category']) > 0 && !isset($_REQUEST['category']) && count($_REQUEST['category']) <= 0)
		if(!isset($_REQUEST['category']) && count($_REQUEST['category']) <= 0 && $tmpdata['templatic-category_custom_fields'] == 'Yes')
		{
			if(isset($_SESSION['category']) && $_SESSION['category']!="" && $_REQUEST['backandedit'] == 1)
				$all_cat_id = implode(",",templ_get_custom_categoryid($_SESSION['category']));
		}elseif(isset($_REQUEST['category']) && count($_REQUEST['category']) > 0)
		{
			$all_cat_id = implode(",",templ_get_custom_categoryid($_REQUEST['category']));
		}
		/* fetch categories only when category wise custom fields are allow */
        if(!isset($_REQUEST['category']) && count(@$_REQUEST['category']) <= 0 && !isset($_REQUEST['fields']) && @$_REQUEST['fields'] =='' && ($tmpdata['templatic-category_custom_fields'] == 'Yes' && @$_REQUEST['action'] !='edit'))
		{
			$button_text  = NEXT_STEP;
			$default_custom_metaboxes = get_post_fields_templ_plugin($post_type,'custom_fields','post');//custom fields for all category.
			if(!isset($_REQUEST['backandedit']) && $_REQUEST['backandedit'] == '')
			{
				unset($_SESSION['category']);
				unset($_SESSION['custom_fields']);
			}
			?> 
			
			<div class="cont_box">	
				<?php
					display_custom_category_field_plugin($default_custom_metaboxes,'custom_fields','post');//displaty  post category html.
				?>
			</div>
			<?php
		}
		else
		{
			$button_text  = PREVIEW_BUTTON_TEXT;
			/* fetch categories only when category wise custom fields are not allow */
			if($_REQUEST['backandedit'] == 1 && $_REQUEST['action'] == 'edit' && $tmpdata['templatic-category_custom_fields'] == 'Yes')
				{
					$all_cat_id = implode(',',$cat_array);
				}

			if(isset($all_cat_id)){}else{ $all_cat_id='';}
			$custom_metaboxes = array();
			$heading_type = fetch_heading_per_post_type($post_type);
			if(count($heading_type) > 0)
			 {
				foreach($heading_type as $_heading_type)
				 {
					$custom_metaboxes[$_heading_type] = get_post_custom_fields_templ_plugin($post_type,$all_cat_id,$taxonomy,$_heading_type);//custom fields for custom post type..
				 }
			 }
			else
			 {
				 $custom_metaboxes[] = get_post_custom_fields_templ_plugin($post_type,$all_cat_id,$taxonomy,'');//custom fields for custom post type..
			 }
			
			$default_custom_metaboxes = get_post_fields_templ_plugin($post_type,'custom_fields','post');//custom fields for default post type.
			$all_cat_id_array = explode(",",$all_cat_id);
			if($tmpdata['templatic-category_custom_fields'] == 'No'){
				if(isset($_REQUEST['action']) && $_REQUEST['action'] =='edit'){
					display_custom_category_name($default_custom_metaboxes,$all_cat_id_array,$taxonomy);//display selected category name when come for edit .
				}
				display_custom_post_field_plugin($custom_metaboxes,'custom_fields',$post_type);//displaty custom fields html.
			
			}
			if($tmpdata['templatic-category_custom_fields'] == 'Yes'){
				display_custom_category_name($default_custom_metaboxes,$all_cat_id_array,$taxonomy);//display selected category name.
				display_custom_post_field_plugin($custom_metaboxes,'custom_fields',$post_type);//displaty default post html.
			}
			
			/* if You have succesfully activated monetization then this function will be included for listing prices */
			if(is_active_addons('monetization'))
			{
				global $monetization;
				if(class_exists('monetization')){			
					global $current_user;
					$user_have_pkg = $monetization->templ_get_packagetype($current_user->ID,$post_type); /* User selected package type*/
					$user_have_days = $monetization->templ_days_for_packagetype($current_user->ID,$post_type); /* return alive days(numbers) of last selected package  */
					$is_user_have_alivedays = $monetization->is_user_have_alivedays($current_user->ID,$post_type); /* return user have an alive days or not true/false */
					if($current_user->ID)// check user wise post per  Subscription limit number post post 
					{
						$package_id=get_user_meta($current_user->ID,'package_select',true);// get the user selected price package id
						$user_limit_post=get_user_meta($current_user->ID,'list_of_post',true); //get the user wise limit post count on price package select
						$package_limit_post=get_post_meta($package_id,'limit_no_post',true);// get the price package limit number of post
						$user_have_pkg = get_post_meta($package_id,'package_type',true); 
					}
					
					if($user_have_pkg == 1  || !$is_user_have_alivedays || !$current_user->ID || $package_limit_post <= $user_limit_post){
						if(isset($edit_id) && $edit_id !=''){
						$pkg_id = get_post_meta($edit_id,'package_select',true); /* user comes to edit fetch selected package */
						}else{ $pkg_id==''; }
						$monetization->fetch_monetization_packages_front_end($pkg_id,'all_packages',$post_type,$taxonomy,''); /* call this function to fetch price packages which have to show even no categories selected */
						if(!isset($all_cat_id)){ $all_cat_id ==0;}elseif(isset($_REQUEST['backandedit'])){ $all_cat_id = implode(',',$cat_array);}else if(isset($edit_id) && $edit_id !=''){ $all_cat_id = $all_cat_id; }
						$monetization->fetch_monetization_packages_front_end($pkg_id,'packages_checkbox',$post_type,$taxonomy,$all_cat_id); /* call this function to fetch price packages */
						echo '<span class="message_error2" id="all_packages_error"></span>';
						if(!isset($_REQUEST['action']) && $_REQUEST['action'] !='edit'){
							$monetization->fetch_package_feature_details($edit_id,$pkg_id,$all_cat_id); /* call this function to display fetured packages */
							if($user_have_pkg == 2 && $user_have_days > 0){
								echo "<div class='form_row clearfix act_success'>".sprintf(SUBMIT_LISTING_DAYS_TEXT,$user_have_days)."</div>";
							}	
							$coupon_code = '';
							if(@$_REQUEST['backandedit']) { $coupon_code = $_SESSION['custom_fields']['add_coupon']; }else if(isset($edit_id) && $edit_id !=''){ $coupon_code = get_post_meta($edit_id,'add_coupon',true); }else{ $coupon_code = ''; } /* coupon code when click ok GBE*/
							templ_get_coupon_fields($coupon_code); /* fetch coupon code */
						}
					}else
					{
						$featured_type= $monetization->templ_get_featured_type($current_user->ID, $post_type);
						echo '<input type="hidden" name="all_cat" id="all_cat" value="0"/>';
						echo '<input type="hidden" name="all_cat_price" id="all_cat_price"  value="0" />';
						echo '<input type="hidden" name="feture_price" id="feture_price"  value="0" />';
						echo '<input type="hidden" name="cat_price" id="cat_price"  value="0" />';
						echo '<input type="hidden" name="package_select" value="'.$package_id.'" />';						
						echo '<input type="hidden" name="featured_type" value="'.$featured_type.'">';
					}
				}
			//for geting the alive days	
			if($current_user->ID){if(function_exists(templ_days_for_user_packagetype))$alive_days= $monetization->templ_days_for_user_packagetype($current_user->ID, $post_type);}
			/* monetization end */
			}
	templ_captcha_integrate('submit'); /* Display recaptcha in submit form */
	
	tevolution_show_term_and_condition(); // show terms and conditions check box
	?>

	<?php } ?>
     <span class="message_error2" id="common_error"></span>
	<input type="hidden" name="cur_post_type" id="cur_post_type" value="<?php echo $post_type; ?>"  />
	<input type="hidden" name="cur_post_taxonomy" id="cur_post_taxonomy" value="<?php echo $taxonomy; ?>"  />
	<input type="hidden" name="cur_post_id" value="<?php echo $post_id; ?>"  />
	<?php if(isset($edit_id) && $edit_id !=''): ?>
	    <input type="hidden" name="pid" id="pid" value="<?php echo $edit_id; ?>"  />
    <?php endif; ?>    
	<?php if(isset($_REQUEST['renew']) && $_REQUEST['renew'] !=''): ?>
	    <input type="hidden" name="renew" id="renew" value="<?php echo $_REQUEST['renew']; ?>"  />
    <?php endif; 
	global $submit_button;
	if(!isset($submit_button)){ $submit_button = ''; }
	?>
    <?php if(!isset($_REQUEST['category']) && count(@$_REQUEST['category']) <= 0 && !isset($_REQUEST['fields']) && @$_REQUEST['fields'] =='' && ($tmpdata['templatic-category_custom_fields'] == 'Yes' && @$_REQUEST['action'] !='edit')):?>
    	<input type="submit" name="preview" value="<?php  _e('Next Step','supreme');?>" class="normal_button" <?php echo $submit_button; ?>/>    
    <?php else:?>
		<input type="submit" name="preview" value="<?php  _e('Preview This Post','supreme');?>" class="normal_button" <?php echo $submit_button; ?>/>    
    <?php endif;?>
     <input type="hidden" value="<?php echo $alive_days;?>" id="alive_days" name="alive_days" >
	</form>
	<?php include_once('submition_validation.php');
}
}
else
{ ?>
    <div class="error_msg">
    <?php _e(IP_BLOCK,DOMAIN); ?>
    </div>
<?php }
/* END OF BLOCK IP CONDITION */
?>
</div>
</div> 
<?php
	$display = get_option('supreme_theme_settings');//[supreme_global_layout]
	$layout = $display['supreme_global_layout'];
	if(!$layout){
		$layout ='2c';
	}
?>
<?php if ( is_active_sidebar( 'add_'.$post_type.'_submit_sidebar' ) && $layout !='layout_1c' ) : ?>
    <div class="sidebar" id="sidebar-primary">
    	<?php dynamic_sidebar('add_'.$post_type.'_submit_sidebar');?>
    </div>
<?php endif; ?>
<?php get_footer(); ?>