<?php
$no_include = array('templatic-generalizaion','templ_header_section.php','general_settings.php','general_functions.php','templ_footer_section.php','images','.svn');
/*
Includes modules install.php file
*/
if ($handle = opendir(TEMPL_MONETIZE_FOLDER_PATH)) {
	/* This is the correct way to loop over the directory. */
	if(file_exists(TEMPL_MONETIZE_FOLDER_PATH.'templatic-custom_taxonomy/install.php'))
	  { 
		require_once(TEMPL_MONETIZE_FOLDER_PATH."templatic-custom_taxonomy/install.php" ); 
	  }
	while (false !== ($file = readdir($handle))) 
	{
	    if($file=='.' || $file=='..'){ }else
		{		
				if(!$no_include){ $no_include = array(); } if(!$file){ $file = array(); }
				if(!in_array($file,$no_include) && $file != 'templatic-custom_taxonomy'){ 
				if(file_exists(TEMPL_MONETIZE_FOLDER_PATH.$file.'/install.php')){ 
					require_once(TEMPL_MONETIZE_FOLDER_PATH.$file."/install.php" ); 
				}}
		}
	}
			closedir($handle);
}
require_once(TEMPL_MONETIZE_FOLDER_PATH."templatic-generalizaion/general_functions.php" );
add_action('admin_menu', 'templ_add_admin_menu_'); /* create templatic admin menu */
if(strstr($_SERVER['REQUEST_URI'],'/wp-admin/') )
{
	add_action('init', 'templ_add_my_stylesheet'); /* include style sheet */
}
else
{
	add_action('wp_head', 'templ_add_my_stylesheet'); /* include style sheet */	
}
add_action('templ_add_admin_menu_', 'templ_add_mainadmin_menu_', 0);

/*
Name : templ_add_admin_menu_
Description : do action for admin menu
*/
function templ_add_admin_menu_()
{
	do_action('templ_add_admin_menu_');
}
/*
Name : templ_add_mainadmin_menu_
Description : Return the main menu at admin sidebar
*/
function templ_add_mainadmin_menu_()
{
	$menu_title = __('Tevolution', DOMAIN);
	if (function_exists('add_object_page'))
	{
		if(isset($_REQUEST['page']) && $_REQUEST['page'] == 'templatic_system_menu'){
		$icon = TEMPL_PLUGIN_URL.'favicon-active.png';
		}else{
		$icon = TEMPL_PLUGIN_URL.'favicon.png';
		}
		 $hook = add_menu_page("Admin Menu", $menu_title, 'administrator', 'templatic_system_menu', 'dashboard_bundles', $icon,61); // title of new sidebar
	 }else{
		add_menu_page("Admin Menu", $menu_title, 'administrator',  'templatic_wp_admin_menu', 'design', TEMPL_PLUGIN_URL.'favicon.png');		
	  }
	  
}
/*
Name : dashboard_bundles
Description : return the connection with bashboard wizards(bundle box)
*/
function dashboard_bundles()
{
  $Templatic_connector = New Templatic_connector;
  $Templatic_connector->templ_dashboard_bundles();
}

/*
Name : templ_add_my_stylesheet
Description : return main CSS of Plugin
*/
function templ_add_my_stylesheet()
{
  /* Respects SSL, Style.css is relative to the current file */
  wp_register_style('prefix-style1', TEMPL_PLUGIN_URL.'style.css');
  wp_enqueue_style('prefix-style1');
  
  $TemplaticSettings = get_option('supreme_theme_settings');
  if((isset($TemplaticSettings['supreme_archive_display_excerpt']) && $TemplaticSettings['supreme_archive_display_excerpt']==1)){
	  if(function_exists('tevolution_excerpt_length')){
		//if(!is_search()){
			add_filter('excerpt_length', 'tevolution_excerpt_length');
		//}
	  }
	  if(function_exists('new_excerpt_more')){
		add_filter('excerpt_more', 'new_excerpt_more');
	  }
  }
}

/*
Name : is_active_addons
Description : return each addons is activated or not
*/
function is_active_addons($key)
{
  $act_key = get_option($key);
  if ($act_key != '')
  {
    return true;
  }
}

/*
Name : templ_remove_dashboard_widgets
Description : Function will remove the admin dashboard widget
*/
function templ_remove_dashboard_widgets()
{
  // Globalize the metaboxes array, this holds all the widgets for wp-admin
  global $wp_meta_boxes;

  // Remove the Dashboard quickpress widget
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);

  // Remove the Dashboard  incoming links widget
  unset
    ($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);

  // Remove the Dashboard secoundary widget
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
}

add_action('wp_dashboard_setup', 'templ_remove_dashboard_widgets');

/*
Name : bdw_get_images_
Arguments : $iPostID = Id of post,$img_size = size of image,$no_images = image path if image not available
Description : Return the images of post
*/
function bdw_get_images_($iPostID, $img_size = 'thumb', $no_images = '')
{
  $arrImages = &get_children('order=ASC&orderby=menu_order ID&post_type
    =attachment&post_mime_type=image&post_parent='.$iPostID);
  $counter = 0;
  $return_arr = array();
  if ($arrImages)
  {
    foreach($arrImages as $key => $val)
    {
      $id = $val->ID;
      if ($img_size == 'large')
      {
        $img_arr = wp_get_attachment_image_src($id, 'full');
          // THE FULL SIZE IMAGE INSTEAD
        $return_arr[] = $img_arr[0];
      }
      elseif ($img_size == 'medium')
      {
        $img_arr = wp_get_attachment_image_src($id, 'medium');
          //THE medium SIZE IMAGE INSTEAD
        $return_arr[] = $img_arr[0];
      }
      elseif ($img_size == 'thumb')
      {
        $img_arr = wp_get_attachment_image_src($id, 'thumbnail');
          // Get the thumbnail url for the attachment
        $return_arr[] = $img_arr[0];
      }
      $counter++;
      if ($no_images != '' && $counter == $no_images)
      {
        break;
      }
    }
    return $return_arr;
  }
}

/* -- coading to add submenu under main menu-- */


add_action('templ_add_admin_menu_', 'templ_add_page_menu', 1);
function templ_add_page_menu()
{
	if (is_active_addons('templatic_page-templates') || is_active_addons('templatic-login') || is_active_addons('monetization')  || is_active_addons('claim_ownership') || is_active_addons('custom_fields_templates') || is_active_addons('custom_taxonomy'))
	{
		$menu_title2 = __('General Settings', DOMAIN);
		add_submenu_page('templatic_system_menu', $menu_title2, $menu_title2,'administrator', 'templatic_settings', 'my_page_templates_function');
	}
}

/* -- coading to add submenu under main menu-- */



function my_page_templates_function()
{	
	include(TEMPL_MONETIZE_FOLDER_PATH.'templatic-generalizaion/general_settings.php');
}

add_action('admin_init', 'my_plugin_redirect');
/*
Name : my_plugin_redirect
Description : Redirect on plugin dashboard after activating plugin
*/
function my_plugin_redirect()
{
  //update_option('myplugin_redirect_on_first_activation', 'false');
  if (get_option('myplugin_redirect_on_first_activation') == 'true')
  {
    update_option('myplugin_redirect_on_first_activation', 'false');
    wp_redirect(MY_PLUGIN_SETTINGS_URL);
  }
}
/*
Name : tmpl_get_ssl_normal_url_for
Description : retun SSSL enabled URL
*/

function tmpl_get_ssl_normal_url_for($url)
{
  if ($this->is_on_ssl_url())
  {
    $url = str_replace('http://', 'https://', $url);
  }
  return $url;
}

/*
Name : include_tmpl_jquery
Description : retun SSSL enabled URL
*/
function include_tmpl_jquery()
{
  wp_enqueue_script('jquery'); // include jQuery
}
add_action("init", "include_tmpl_jquery");
/*
 * Function Name: view_counter_single_post
 * Argument: post id
 */
function view_counter_single_post($pid){	
	if($_SERVER['HTTP_REFERER'] == '' || !strstr($_SERVER['HTTP_REFERER'],$_SERVER['REQUEST_URI']))
	{

		$viewed_count = get_post_meta($pid,'viewed_count',true);
		$viewed_count_daily = get_post_meta($pid,'viewed_count_daily',true);
		$daily_date = get_post_meta($pid,'daily_date',true);
	
		update_post_meta($pid,'viewed_count',$viewed_count+1);
	
		if(get_post_meta($pid,'daily_date',true) == date('Y-m-d')){
			update_post_meta($pid,'viewed_count_daily',$viewed_count_daily+1);
		} else {
			update_post_meta($pid,'viewed_count_daily','1');
		}
		update_post_meta($pid,'daily_date',date('Y-m-d'));
	}
}

/*
 * Function Name: get_custom_post_type_template
 * add single post view counter
 */
function get_custom_post_type_template($single_template) {
	global $post;	 
		view_counter_single_post($post->ID);
	
	return $single_template;
}
/*
 * Function Name:user_single_post_visit_count
 * Argument: Post id
 */
function user_single_post_visit_count($pid)
{
	if(get_post_meta($pid,'viewed_count',true))
	{
		return get_post_meta($pid,'viewed_count',true);
	}else
	{
		return '0';	
	}
}
/*
 * Function Name:user_single_post_visit_count_daily
 * Argument: Post id
 */
function user_single_post_visit_count_daily($pid)
{
	if(get_post_meta($pid,'viewed_count_daily',true))
	{
		return get_post_meta($pid,'viewed_count_daily',true);
	}else
	{
		return '0';	
	}
}
/*
 * Functon Name:view_count
 * Argument: post content
 * add view count display after the content
 */

function view_count( $content ) {	
	
	if ( is_single()) 
	{
		global $post;
		$sep =" , ";
		$custom_content.="<p>".sprintf(__('Visited %s times',DOMAIN) ,user_single_post_visit_count($post->ID));
		$custom_content.= $sep.user_single_post_visit_count_daily($post->ID).__(" Visits today",DOMAIN)."</p>";
		$custom_content .= $content;
		//$content.=$custom_content;
		return $custom_content;
	} 
	return $content;
}

function teamplatic_view_counter()
{
   $settings = get_option( "templatic_settings" );   	
   if(isset($settings['templatic_view_counter']) && $settings['templatic_view_counter']=='Yes')
   {
	   	add_filter("single_template", "get_custom_post_type_template" ) ;
		add_filter( 'the_content', 'view_count' );
   }  
   add_filter('the_content','view_sharing_buttons');
	
}
add_action("init", "teamplatic_view_counter");

function view_sharing_buttons($content)
{
	global $post;	
	if (is_single() && ($post->post_type!='post' && $post->post_type!='page')) 
	{
		$settings = get_option( "templatic_settings" );
		echo '<span class="share_link">';
			if($settings['facebook_share_detail_page'] == 'yes')
			  {
				  	$post_img = bdw_get_images_plugin($post->ID,'large');
					$post_images = $post_img[0]['file'];
					$title=urlencode($post->post_title);
					$url=urlencode(get_permalink($post->ID));
					$summary=urlencode(htmlspecialchars($post->post_content));
					$image=$post_images;
					?>
					<a onClick="window.open('http://www.facebook.com/sharer.php?s=100&amp;p[title]=<?php echo $title;?>&amp;p[summary]=<?php echo $summary;?>&amp;p[url]=<?php echo $url; ?>&amp;&amp;p[images][0]=<?php echo $image;?>','sharer','toolbar=0,status=0,width=548,height=325');" href="javascript: void(0)" id="facebook_share_button"><?php _e('Facebook Share.',T_DOMAIN); ?></a>
				<?php
			  }
			if($settings['google_share_detail_page'] == 'yes'): ?>
				<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
				<div class="g-plus" data-action="share" data-annotation="bubble"></div> 
			<?php endif;
			
			if($settings['twitter_share_detail_page'] == 'yes'): ?>
					<a href="https://twitter.com/share" class="twitter-share-button" data-lang="en" data-text='<?php echo $post->post_content;?>' data-url="<?php echo get_permalink($post->ID); ?>" data-counturl="<?php echo get_permalink($post->ID); ?>"><?php _e('Tweet',T_DOMAIN); ?></a>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			<?php endif;
		echo '</span>';
	}
	return $content;
}



/*
Name : templatic_module_activationmsg
Description : this function will return the message related to specific module during activation and dectivation.
*/

function templatic_module_activationmsg($mod_slug='',$mod_name='',$mod_status = '',$mod_message='',$realted_mod =''){
	if(@$_REQUEST['activated'] && @$_REQUEST['activated'] == $mod_slug ){ ?>
		<div class="act_success updated" id="message" style="margin-top:15px;">
		<p><?php echo "<strong>".$mod_name."</strong> wizard has been activated successfully"; ?> .</p>
		<?php if($mod_message){
		      echo "<p>".$mod_message."</p>"; }
			  if($realted_mod){
		      echo "<p><strong>".$realted_mod."</strong> Modules are connected with $mod_name, so please activate them too.</p>"; } ?>
		</div>
	<?php }else if(@$_REQUEST['deactivate'] && @$_REQUEST['deactivate'] == $mod_slug ){ ?>
		<div class="updated" id="message" style="margin-top:15px;">
		<p><?php echo "<strong>".$mod_name."</strong> wizard has been deactivated"; ?> .</p>
		<?php if($mod_message){
		      echo "<p>".$mod_message."</p>"; }
			  if($realted_mod){
		      echo "<p><strong>".$realted_mod."</strong> Modules are affected after deactivation of $mod_name.</p>"; } ?>
		</div>
	<?php }
}

/*
name: templatic_get_currency_type
description: fetch currency.*/
function templatic_get_currency_type()
{
	global $wpdb;
	$option_value = get_option('currency_code');
	if($option_value)
	{
		return stripslashes($option_value);
	}else
	{
		return 'USD';
	}
	
}

/* NAME : FETCH CURRENCY
DESCRIPTION : THIS FUNCTION RETURNS THE CURRENCY WITH POSITION SELECTED IN CURRENCY SETTINGS */
function fetch_currency_with_position($amount,$currency = '')
{
	$amt_display = '';
	if($amount==''){ $amount =0; }
	if($amount != "")
	{
		$currency = get_option('currency_symbol');
		$position = get_option('currency_pos');
		if($position == '1')
		{
			$amt_display = $currency.$amount;
		}
		else if($position == '2')
		{
			$amt_display = $currency.' '.$amount;
		}
		else if($position == '3')
		{
			$amt_display = $amount.$currency;
		}
		else
		{
			$amt_display = $amount.' '.$currency;
		}
		return $amt_display;
	}
}
/* EOF - DISPLAY CURRENCY WITH POSITION */


/* NAME : TEMPLATIC NOTIFICATION LEGENDS
DESCRIPITION : THIS FUNCTION WILL DISPLAY THE LEGENDS DESCRIPTION ON EMAIL SETTINGS PAGE IN GENERAL SETTINGS */
function templatic_legend_notification()
{
	$legend_display = '<h3>Legends : </h3>';
	$legend_display .= '<p style="line-height:30px;width:100%;"><label style="float:left;width:180px;">[#to_name#]</label> : '.__('Name of the recipient.',DOMAIN).'<br />
	<label style="float:left;width:180px;">[#site_name#]</label> : '.__('Site name as you provided in General Settings',DOMAIN).'<br />
	<label style="float:left;width:180px;">[#site_login_url#]</label> : '.__('Site\'s login page URL',DOMAIN).'<br />
	<label style="float:left;width:180px;">[#user_login#]</label> : '.__('Recipient\'s login ID',DOMAIN).'<br />
	<label style="float:left;width:180px;">[#user_password#]</label> : '.__('Recepient\'s password',DOMAIN).'<br />
	<label style="float:left;width:180px;">[#site_login_url_link#]</label> : '.__('Site login page link',DOMAIN).'<br />
	<label style="float:left;width:180px;">[#post_date#]</label> : '.__('Date of post',DOMAIN).'<br />
	<label style="float:left;width:180px;">[#information_details#]</label> : '.__('Information details of place/event.',DOMAIN).'<br />
	<label style="float:left;width:180px;">[#transaction_details#]</label> : '.__('Transaction details of place/event.',DOMAIN).'<br />
	<label style="float:left;width:180px;">[#frnd_subject#]</label> : '.__('Subject for the email to the recipient.',DOMAIN).'<br />
	<label style="float:left;width:180px;">[#frnd_comments#]</label> : '.__('Comment for the email to the recipient.',DOMAIN).'<br />
	<label style="float:left;width:180px;">[#your_name#]</label> : '.__('Sender\'s name',DOMAIN).'<br />
	<label style="float:left;width:180px;">[#submited_information_link#]</label> : '.__('URL of the detail page',DOMAIN).'<br />
	<label style="float:left;width:180px;">[#payable_amt#]</label> : '.__('Payable amount',DOMAIN).'<br />
	<label style="float:left;width:180px;">[#bank_name#]</label> : '.__('Bank name',DOMAIN).'<br />
	<label style="float:left;width:180px;">[#account_number#]</label> : '.__('Account number',DOMAIN).'<br />
	<label style="float:left;width:180px;">[#submition_Id#]</label> : '.__('Submission ID',DOMAIN).'</p>';
	return $legend_display;
}
/* EOF - TEMPLATIC LEGENDS */

/*
Name : tmpl_fetch_currency
Desc : return only currency
*/
function tmpl_fetch_currency(){
	$currency = get_option('currency_symbol');
	if($currency){
		return $currency;
	}else{
		return '$';
	}	
}
/* eof fetch currency*/

/* FUNCTION NAME : TEMPLATIC SEND EMAIL
ARGUMENTS : FROM EMAIL ID, FROM EMAIL NAME, TO EMAIL ID, TO EMAIL NAME, SUBJECT, MESSEGE, HEADERS
RETURNS : THIS FUNCTION IS USED TO SEND EMAILS
*/
function templ_send_email($fromEmail,$fromEmailName,$toEmail,$toEmailName,$subject,$message,$extra='')
{
	
	$fromEmail = apply_filters('templ_send_from_emailid', $fromEmail);
	$fromEmailName = apply_filters('templ_send_from_emailname', $fromEmailName);
	$toEmail = apply_filters('templ_send_to_emailid', $toEmail);
	$toEmailName = apply_filters('templ_send_to_emailname', $toEmailName);

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
		// Additional headers
	$headers .= 'To: '.$toEmailName.' <'.$toEmail.'>' . "\r\n";
	if($fromEmail!="")
	{
		$headers .= 'From: '.$fromEmailName.' <'.$fromEmail.'>' . "\r\n";	
	}else	
		$headers .= 'From: '.get_option('blogname').' <'.get_option('admin_email').'>' . "\r\n";
		
	$subject = apply_filters('templ_send_email_subject', $subject);
	$message = apply_filters('templ_send_email_content', $message);
	$headers = apply_filters('templ_send_email_headers', $headers);	
	// Mail it
	
	if(templ_fetch_mail_type())
	{
		@mail($toEmail, $subject, $message, $headers);	
	}else
	{
		wp_mail($toEmail, $subject, $message, $headers);	
	}
	
}
/* EOF - TEMPLATIC SEND EMAIL */

/* NAME : FETCH MAIL OPTION
DESCRIPTION : THIS FUNCTION WILL FETCH THE EMAIL SETTINGS FOR PHP OR WP MAIL */
function templ_fetch_mail_type()
{
	$tmpdata = get_option('templatic_settings');
	if($tmpdata['php_mail'] == 'php_mail')
	{
		return true;	
	}
	return false;
}
/* EOF - FETCH MAIL OPTION */

/* NAME : FETCH CATEGORIES DROPDOWN
DESCRIPTION : THIS FUNCTION WILL FETCH THE CATEGORY DROPDOWN WHILE ADDING A PRICE PACKAGE OR CUSTOM FIELD */
function get_wp_category_checklist_plugin($post_taxonomy,$pid)
{
	$pid = explode(',',$pid);
	global $wpdb;
	$taxonomy = $post_taxonomy;
	$table_prefix = $wpdb->prefix;
	$wpcat_id = NULL;
	
	/* FETCH PARENT CATEGORY */
	if($taxonomy == "")
	{
		$custom_tax = @array_keys(get_option('templatic_custom_taxonomy'));
		$slugs = @implode(",",$custom_tax);
		$slugs .= ",category";		
		$wpcategories = (array)$wpdb->get_results
						("SELECT * FROM {$table_prefix}terms, {$table_prefix}term_taxonomy
						WHERE {$table_prefix}terms.term_id = {$table_prefix}term_taxonomy.term_id
						AND ({$table_prefix}term_taxonomy.taxonomy in ('" . str_replace(",", "','", $slugs) . "')) and  {$table_prefix}term_taxonomy.parent=0  ORDER BY {$table_prefix}terms.name");
	}
	else
	{
		$wpcategories = (array)$wpdb->get_results
						("SELECT * FROM {$table_prefix}terms, {$table_prefix}term_taxonomy
						WHERE {$table_prefix}terms.term_id = {$table_prefix}term_taxonomy.term_id
						AND {$table_prefix}term_taxonomy.taxonomy ='".$taxonomy."' and  {$table_prefix}term_taxonomy.parent=0  ORDER BY {$table_prefix}terms.name");
	}	
	$wpcategories = array_values($wpcategories);
	$wpcat2 = NULL;
	if($wpcategories)
	{
		$counter = 0;
		foreach ($wpcategories as $wpcat)
		{ 
			$counter++;
			$termid = $wpcat->term_id;;
			$name = ucfirst($wpcat->name); 
			$termprice = $wpcat->term_price;
			$tparent =  $wpcat->parent; ?>
			<li><input type="checkbox" name="category[]" id="<?php echo $termid; ?>" value="<?php echo $termid; ?>" class="checkbox" <?php if($pid[0]){ if(in_array($termid,$pid)){ echo "checked=checked"; } }else{  }?> />&nbsp;<label for="<?php echo $termid; ?>">&nbsp;<?php echo $name; if($termprice != "") { echo " (".fetch_currency_with_position($termprice).") ";}else{  echo " (".fetch_currency_with_position('0').") "; } ?></label></li>
			<?php if($taxonomy !="")
				{
					$child = get_term_children( $termid, $post_taxonomy );
					$args = array('child_of'	=> $termid,
								'hide_empty'	=> 0,
								'taxonomy'		=> $post_taxonomy);
		 $categories = get_categories( $args );
		 foreach($categories as $child_of)
		 { 
			$child_of = $child_of->term_id; 
		 	$p = 0;
			$term = get_term_by( 'id', $child_of,$post_taxonomy);
			$termid = $term->term_taxonomy_id;
			$term_tax_id = $term->term_id;
			$termprice = $term->term_price;
			$name = $term->name;

			if($child_of)
			{
				$catprice = $wpdb->get_row("select * from $wpdb->term_taxonomy tt ,$wpdb->terms t where t.term_id='".$child_of."' and t.term_id = tt.term_id AND tt.taxonomy ='".$taxonomy."'");
				for($i=0;$i<count($catprice);$i++)
				{
					if($catprice->parent)
					{	
						$p++;
						$catprice1 = $wpdb->get_row("select * from $wpdb->term_taxonomy tt ,$wpdb->terms t where t.term_id='".$catprice->parent."' and t.term_id = tt.term_id AND tt.taxonomy ='".$taxonomy."'");
						if($catprice1->parent)
						{
							$i--;
							$catprice = $catprice1;
							continue;
						}
					}
				}
			}
			$p = $p*15;
		 ?>
			<li style="margin-left:<?php echo $p; ?>px;"><label><input type="checkbox" name="category[]" id="<?php echo $term_tax_id; ?>" value="<?php echo $term_tax_id; ?>" class="checkbox" <?php if($pid[0]){ if(in_array($term_tax_id,$pid)){ echo "checked=checked"; } }else{  }?> /></label>&nbsp;<?php echo $name; if($termprice != "") { echo " (".fetch_currency_with_position($termprice).") ";}else{  echo " (".fetch_currency_with_position('0').") "; } ?></li>
		<?php  }	}else{
		 $post_taxonomy  = $wpcat->taxonomy;
		 $child = get_term_children( $termid, $post_taxonomy );
		 if($child ==''){
		 $post_taxonomy  = $wpcat->taxonomy;
		 $child = get_term_children( $termid, $post_taxonomy ); }
		 foreach($child as $child_of)
		 { 
		 	$p = 0;
			$term = get_term_by( 'id', $child_of,$post_taxonomy);
			$termid = $term->term_taxonomy_id;
			$term_tax_id = $term->term_id;
			$termprice = $term->term_price;
			$name = $term->name;

			if($child_of)
			{
				$catprice = $wpdb->get_row("select * from $wpdb->term_taxonomy tt ,$wpdb->terms t where t.term_id='".$child_of."' and t.term_id = tt.term_id AND (tt.taxonomy ='".$post_taxonomy."')");
				for($i=0;$i<count($catprice);$i++)
				{
					if($catprice->parent)
					{	
						$p++;
						$catprice1 = $wpdb->get_row("select * from $wpdb->term_taxonomy tt ,$wpdb->terms t where t.term_id='".$catprice->parent."' and t.term_id = tt.term_id AND (tt.taxonomy ='".$post_taxonomy."')");
						if($catprice1->parent)
						{
							$i--;
							$catprice = $catprice1;
							continue;
						}
					}
				}
			}
			$p = $p*15;
		 ?>
			<li style="margin-left:<?php echo $p; ?>px;"><label><input type="checkbox" name="category[]" id="<?php echo $term_tax_id; ?>" value="<?php echo $term_tax_id; ?>" class="checkbox" <?php if($pid[0]){ if(in_array($term_tax_id,$pid)){ echo "checked=checked"; } }else{  }?> /></label>&nbsp;<?php echo $name; if($termprice != "") { echo " (".fetch_currency_with_position($termprice).") ";}else{  echo " (".fetch_currency_with_position('0').") "; } ?></li>
		<?php  }	
				}		
	}
	}else{
		$custom_tax = get_option('templatic_custom_taxonomy');
		$post_type = $custom_tax[$post_taxonomy]['post_type'];
		echo '<li class="element" style="font-size:12px; color:red; clear:both;">No category has been created for <strong>'.$post_taxonomy.'</strong>, <a href='.site_url('/wp-admin/edit-tags.php?taxonomy='.$post_taxonomy.'&post_type='.$post_type).'>click here</a> to create category.</li>';
	}
}
/* EOF - FETCH CATEGORIES DROPDOWN */

/*
 * Function Name: changes_post_update_link
 * Argument: post link, before, after ,id
 * Return: update post link
 */
function changes_post_update_link($link)
{
	global $post;
	$postid=$post->ID;
	$post_type=$post->post_type;
	$postdate = $post->post_date;
	//get the submited page id from post typpe
	$args=array(	
		'post_type' => 'page',
		'post_status' => 'publish',				
		'meta_query' => array(
							array(
								'key' => '_wp_page_template',
								'value' => 'page-template_form.php',
								'compare' => '='
								),				
							array(
								'key' => 'template_post_type',
								'value' => $post_type,
								'compare' => '='
								)
							)
			);
	$the_query  = new WP_Query( $args );	
	if( $the_query->have_posts()):
		foreach($the_query as $post):
			if($post->ID != ""):
				$page_id=$post->ID;
			endif;	
		endforeach;
		//get the front side submited page id permalink		
		$page_link=get_permalink($page_id);
		$edit_link = '';
		$review_link = '';
		if(strpos($page_link, "?"))
		{
			$edit_link = $page_link."&pid=".$postid."&action=edit";
			$review_link = $page_link."&pid=".$postid."&renew=1";
			$delete_link = $page_link."&pid=".$postid."&page=preview&action=delete";
		}
		else
		{
			$edit_link = $page_link."?pid=".$postid."&action=edit";
			$review_link = $page_link."?pid=".$postid."&renew=1";
			$delete_link = $page_link."?pid=".$postid."&page=preview&action=delete";
		}
		$exp_days = get_time_difference_plugin( $postdate, $postid);
		$link = '';
		if($exp_days > 0 && $exp_days != '' )
		 {
			$link='<a class="post-edit-link" title="Edit Item" href="'.$edit_link.'" target="_blank">'.__('Edit',DOMAIN).'</a>&nbsp;&nbsp;';
		 }
		else
         {		
			$link.='<a class="post-edit-link" title="Renew Item" href="'.$review_link.'" target="_blank">'.__('Renew',DOMAIN).'</a>&nbsp;&nbsp;';
		 }	
		 $link.='&nbsp;<a class="post-edit-link" title="Delete Item" href="'.$delete_link.'" target="_blank">'.__('Delete',DOMAIN).'</a>&nbsp;&nbsp;';
	endif;
	if(is_author()){
		return $link;
	}
}
/*
 * add filter for changes the edit post link for author wise
 */
add_filter('edit_post_link', 'changes_post_update_link');

/* Get expire days */
function get_time_difference_plugin($start, $pid)
{
  if($start)
	{
		$alive_days = get_post_meta($pid,'alive_days',true);
		$uts['start']      =    strtotime( $start );
		$uts['end']        =    mktime(0,0,0,date('m',strtotime($start)),date('d',strtotime($start))+$alive_days,date('Y',strtotime($start)));
	
		$post_days = gregoriantojd(date('m'), date('d'), date('Y')) - gregoriantojd(date('m',strtotime($start)), date('d',strtotime($start)), date('Y',strtotime($start)));
		$days = $alive_days-$post_days;
	
		if($days>0)
		{
			return $days;	
		}else{
			return( false );
		}
	}
}

/*
name : get_content_in_templatic_eco_system_wp_pointer
description : over all tour of Tevolution plugin.
*/
function get_content_in_templatic_eco_system_wp_pointer()
{
	if(!isset($_REQUEST['eco_system_tour_step']) && $_REQUEST['eco_system_tour_step'] == '' && !isset($_REQUEST['WpEcoWorld_claim_tour_step']) && !isset($_REQUEST['eco_system_custom_fields_tour_step']) && !isset($_REQUEST['eco_system_custom_taxonomy_tour_step']) && !isset($_REQUEST['WpEcoWorld_user_custom_fields_tour_step']) && !isset($_REQUEST['WpEcoWorld_monetization_tour_step']))
	{
			$pointer_content = '<h3>' . __( 'Welcome To Tevolution!.', DOMAIN ) . '</h3>';
			$pointer_content .= '<p>' . __( 'Congratulations !<br/><br/> Tevolution is successfully installed on your site. Click on "Start Tour" for quick overview.<br/><br/>Thank you for installing Tevolution', DOMAIN ) . '</p>';
			$templatic_url = __('Start Tour',DOMAIN);
			$pointer_id = 'toplevel_page_templatic_system_menu';
			$tour_url = site_url()."/wp-admin/admin.php?page=templatic_system_menu&eco_system_tour_step=1";
			$done = true;
			$postion = true;

	}
	elseif(isset($_REQUEST['eco_system_tour_step']) && $_REQUEST['eco_system_tour_step'] == '1')
	{
		$pointer_content = '<h3>' . __( 'Tevolution Overview', DOMAIN ) . '</h3>';
		//$pointer_content .= '<p>' . __( 'Tevolution is designed to provide all the advanced tools altogether in a bunch. You can create taxonomies, custom fields, use widgets and also you will be able to use the monetization module. All you can achieve by a single click.', DOMAIN ) . '</p>';
		$pointer_content .= '<p><strong>Custom Post Type Manager</strong></p><p>' . __( 'You can add/edit/delete custom post-types/taxonomies by activating Custom Post Type manager.', DOMAIN ) . '</p>';
		$pointer_content .= '<p><strong>Custom Fields Manager</strong></p><p>' . __( 'Manage different types of custom fields for different post types, it contains bundle of options.', DOMAIN ) . '</p>';
		$pointer_content .= '<p><strong>User Profile Manager</strong></p><p>' . __( 'It enables you to manage registration/login+profile with custom fields, you can also display these fields on your author page.', DOMAIN ) . '</p>';
		$pointer_content .= '<p><strong>Monetization</strong></p><p>' . __( 'This feature rich module contains price packages, currency settings and bundle of payment gateways.', DOMAIN ) . '</p>';
		$pointer_content .= '<p><strong>Security Manager</strong></p><p>' . __( 'To stay safe from attackers/spam posting you can block IPs, this module also allows you to enable SSL while doing payment', DOMAIN ) . '</p>';
		$pointer_content .= '<p><strong>Claim Post Manager</strong></p><p>' . __( 'This will allow your site visitors to claim listings on your site', DOMAIN ) . '</p>';
		$pointer_content .= '<p><strong>Bulk Import-Export Manager</strong></p><p>' . __( 'This module allows you to import or export the listings of the site(<b>NOTE</b> : with sample csv format).', DOMAIN ) . '</p>';
		$templatic_url = __('Next',DOMAIN);
		$pointer_id = 'start_tour_eco_system';
		$tour_url = site_url()."/wp-admin/admin.php?page=templatic_system_menu&eco_system_tour_step=2";
		$at = "bottom";
		$done = true;
		$postion = false;
	}
	elseif(isset($_REQUEST['eco_system_tour_step']) && $_REQUEST['eco_system_tour_step'] == '2')
	{
		$pointer_content = '<h3>' . __( 'Custom Post Type manager', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'By using this module, you will be able to create the post types of your choice.', DOMAIN ) . '</p>';
		$templatic_url = __('Next',DOMAIN);
		$pointer_id = 'templatic_posttype';
		$tour_url = site_url()."/wp-admin/admin.php?page=templatic_system_menu&eco_system_tour_step=3#";
		$done = true;
		$postion = false;
	}
	elseif(isset($_REQUEST['eco_system_tour_step']) && $_REQUEST['eco_system_tour_step'] == '3')
	{
		$pointer_content = '<h3>' . __( 'Custom Fields manager', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'Custom fields manager enables you to create fields of your choice. You will also be able to add fields as per categories in your site. The fields you create here will be displayed on submission form.', DOMAIN ) . '</p>';
		$pointer_content .= '<p>' . __( 'Now onwards you can activate any of the modules you want and take the benefit of exclusive features offered by Tevolution.', DOMAIN ) . '</p>';
		
		// Theme Specific message for category wise custom fields. START
		if(function_exists('function_filter')){
			$pointer_content .= function_filter();
		}
		// Theme Specific message for category wise custom fields. END
		$templatic_url = __('Next',DOMAIN);
		$pointer_id = 'templatic_customfields';
		$tour_url = site_url()."/wp-admin/admin.php?page=templatic_system_menu&eco_system_tour_step=4";
		$done = false;  /*  True if want to continue tour or False to finish */
		$postion = false;
	}
	elseif(isset($_REQUEST['eco_system_tour_step']) && $_REQUEST['eco_system_tour_step'] == '4')
	{
		$pointer_content = '<h3>' . __( 'User Login and Registration manager', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'This will enable you to create custom fields for user registration page. You can access the profile page using the T> Login Dashboard Wizard widget in the sidebar widget areas, You can also use facebook and twitter login on your site.', DOMAIN ) . '</p>';
		$templatic_url = __('Next',DOMAIN);
		$pointer_id = 'templatic_userreg';
		$tour_url = site_url()."/wp-admin/admin.php?page=templatic_system_menu&eco_system_tour_step=5";
		$done = true;
		$postion = false;
	}
	elseif(isset($_REQUEST['eco_system_tour_step']) && $_REQUEST['eco_system_tour_step'] == '5')
	{
		$pointer_content = '<h3>' . __( 'Monetization', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'This module is a bunch of all the features which helps you make money. You can create price packages and also you will be able to select payment gateways and manage coupons by using this module.', DOMAIN ) . '</p>';
		$templatic_url = __('Next',DOMAIN);
		$pointer_id = 'templatic_monetization';
		$tour_url = site_url()."/wp-admin/admin.php?page=templatic_system_menu&eco_system_tour_step=6";
		$done = true;
		$postion = false;
	}
	elseif(isset($_REQUEST['eco_system_tour_step']) && $_REQUEST['eco_system_tour_step'] == '6')
	{
		$pointer_content = '<h3>' . __( 'Security manager', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'A very essential feature which helps you to deal with the fake users by blocking their ip address. You can also use the SSL on your site by enabling it.', DOMAIN ) . '</p>';
		$templatic_url = __('Next',DOMAIN);
		$pointer_id = 'templatic_manage_ip_module';
		$tour_url = site_url()."/wp-admin/admin.php?page=templatic_system_menu&eco_system_tour_step=7";
		$done = true;
		$postion = false;
	}
	elseif(isset($_REQUEST['eco_system_tour_step']) && $_REQUEST['eco_system_tour_step'] == '7')
	{
		$pointer_content = '<h3>' . __( 'Bulk Import/Export', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'Bulk import/export is another useful feature which enables you to import or export the data by using CSV file. This will enable you to import/export data for your chosen post type.', DOMAIN ) . '</p>';
		$templatic_url = __('Next',DOMAIN);
		$pointer_id = 'templatic_bulkupload';
		$tour_url = site_url()."/wp-admin/admin.php?page=templatic_system_menu&eco_system_tour_step=8";
		$done = true;
		$postion = false;
	}
	elseif(isset($_REQUEST['eco_system_tour_step']) && $_REQUEST['eco_system_tour_step'] == '8')
	{
		$pointer_content = '<h3>' . __( 'Claim Ownership', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'This module helps you deal with the claims posted by users. You will be able to enable users so that they can claim for an event posted on your site and you will be able to approve or reject them.', DOMAIN ) . '</p>';
		$templatic_url = __('Next',DOMAIN);
		$pointer_id = 'templatic_claimownership';
		$tour_url = site_url()."/wp-admin/admin.php?page=templatic_system_menu&eco_system_tour_step=9";
		$done = false;
		$postion = false;
	}
	
	?>
	<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready( function($) {
	$('#<?php echo $pointer_id ?>').pointer({
	content: '<?php echo $pointer_content; ?>',
	position: {
	<?php if($postion) { ?>
				my: 'left top',
				at: 'left bottom',
				offset: '10 0'
	<?php } elseif(isset($_REQUEST['eco_system_tour_step']) && $_REQUEST['eco_system_tour_step'] == '1') { ?>
				my: 'left top',
				at: 'left <?php echo $at; ?>',
				offset: '10 0'
	<?php }
			else
			{?>
				edge: 'bottom',
				align: 'left'
	<?php	} ?>
		},buttons: function( event, t ) {
	
		var $buttonClose = jQuery('<a class="button-secondary" style="margin-right:10px;" href="#">Dismiss</a>');
				$buttonClose.bind( 'click.pointer', function() {
					
					$.post( ajaxurl, {
					pointer: 'templatic_ecosystem_plugin_install',
					action: 'dismiss-wp-pointer'
				});
					t.element.pointer('close');
				});
	
			<?php if($done) {?>
				var $buttonNext = $('<a class="button-primary" href="<?php echo $tour_url; ?>#<?php echo $pointer_id ?>"><?php echo $templatic_url; ?></a>');
			<?php	} ?>
				var buttons = $('<div class="tiptour-buttons">');
				<?php if( $done ){ ?>buttons.append($buttonNext);<?php	} ?>
				buttons.append($buttonClose);
				return buttons;
		
		
	}		
		}).pointer('open');
	});
	//]]>
	</script>
<?php
}
/*
name : get_content_in_templatic_eco_system_custom_taxonomy_wp_pointer
description : tour of  custom taxonomy of WpEcoWorld plugin.
*/
function get_content_in_templatic_eco_system_custom_taxonomy_wp_pointer() 
{
	
	if(isset($_REQUEST['eco_system_custom_taxonomy_tour_step']) && $_REQUEST['eco_system_custom_taxonomy_tour_step'] == '1')
	{
		$pointer_content = '<h3>' . __( 'Custom post type manager', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'This page shows the Listing of custom post types you have created, To add a new post type click Next.', DOMAIN ) . '</p>';
		$pointer_id = 'add_custom_taxonomy';
		$templatic_url = __('Next',DOMAIN);
		$tour_url = site_url()."/wp-admin/admin.php?page=custom_taxonomy&action=add_taxonomy&eco_system_custom_taxonomy_tour_step=2";
		$done = true;
		$postion = false;
	}
	elseif(isset($_REQUEST['eco_system_custom_taxonomy_tour_step']) && $_REQUEST['eco_system_custom_taxonomy_tour_step'] == '2')
	{
		$pointer_content = '<h3>' . __( 'Custom post type manager', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'Use this form to add a new post type. Fill in the necessary information here (Please insert the slugs carefully). Your post type will be created.', DOMAIN ) . '</p>';

		$pointer_id = 'form_table_taxonomy';
		$templatic_url = __('Next',DOMAIN);
		$tour_url = site_url()."/wp-admin/admin.php?page=custom_taxonomy&action=add_taxonomy";
		$done = false;
		$postion = true;
	}
	
	?>
	<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready( function($) {
	$('#<?php echo $pointer_id ?>').pointer({
	content: '<?php echo $pointer_content; ?>',
	position: {
	<?php if($postion) { ?>
				edge: 'left',
				<?php if($_REQUEST['eco_system_custom_taxonomy_tour_step']=='2'){?>
					align: 'top center'
				<?php }else{?> 
					align: 'center'
				<?php } ?>
				
	<?php } else
		{?>
				my: 'left top',
				at: 'left bottom',
				offset: '-25 0'
	<?php 	} ?>
		},buttons: function( event, t ) {
	
		var $buttonClose = jQuery('<a class="button-secondary" style="margin-right:10px;" href="#">Dismiss</a>');
				$buttonClose.bind( 'click.pointer', function() {
					
					$.post( ajaxurl, {
					pointer: 'templatic_ecosystem_plugin_custom_taxonomy_install',
					action: 'dismiss-wp-pointer'
				});
					t.element.pointer('close');
				});
	
		<?php if($done) { ?>
				var $buttonNext = $('<a class="button-primary" href="<?php echo $tour_url; ?>#<?php echo $pointer_id ?>"><?php echo $templatic_url; ?></a>');
			<?php } ?>
				var buttons = $('<div class="tiptour-buttons">');
			<?php if($done) { ?>	buttons.append($buttonNext); <?php } ?>
				buttons.append($buttonClose);
				return buttons;
		
		
	}		
		}).pointer('open');
	});
	//]]>
	</script>
<?php
}
/*
name : get_content_in_templatic_eco_system_custom_fields_wp_pointer
description : tour of  custom field of WpEcoWorld plugin.
*/
function get_content_in_templatic_eco_system_custom_fields_wp_pointer() 
{
	if(isset($_REQUEST['eco_system_custom_fields_tour_step']) && $_REQUEST['eco_system_custom_fields_tour_step'] == 'custom_taxonomy_activate')
	{
		$pointer_content = '<h3>' . __( 'Custom fileds manager', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'Please activate custom post type manager.!!!', DOMAIN ) . '</p>';
		$pointer_id = 'publishing_action_custom_taxonomy';
		$templatic_url = __('Next',DOMAIN);
		$tour_url = site_url()."/wp-admin/admin.php?page=templatic_settings&eco_system_custom_fields_tour_step=1";
		$done = true;
		$postion = true;
		$edge = 'right';
	}
	elseif(isset($_REQUEST['eco_system_custom_fields_tour_step']) && $_REQUEST['eco_system_custom_fields_tour_step'] == '1')
	{
		$pointer_content = '<h3>' . __( 'Custom fileds manager', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'Select yes if you want to show your custom fields in selected categories on the submission form.', DOMAIN ) . '</p>';
		$pointer_id = 'custom_fields_wp_footer';
		$templatic_url = __('Next',DOMAIN);
		$tour_url = site_url()."/wp-admin/admin.php?page=custom_fields&eco_system_custom_fields_tour_step=2";
		$done = true;
		$postion = true;
		$edge = 'left';
	}
	elseif(isset($_REQUEST['eco_system_custom_fields_tour_step']) && $_REQUEST['eco_system_custom_fields_tour_step'] == '2')
	{
		$pointer_content = '<h3>' . __( 'Custom fileds manager.', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'You can add a new custom field from here!!!', DOMAIN ) . '</p>';
		$pointer_id = 'add_custom_fields';
		$templatic_url = __('Next',DOMAIN);
		$tour_url = site_url()."/wp-admin/admin.php?page=custom_fields&action=addnew&eco_system_custom_fields_tour_step=3";
		$done = true;
		$postion = false;
		$edge = 'left';
	}
	elseif(isset($_REQUEST['eco_system_custom_fields_tour_step']) && $_REQUEST['eco_system_custom_fields_tour_step'] == '3')
	{
		$pointer_content = '<h3>' . __( 'Custom fileds manager.', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'Fill in the necessary information to create a new custom field, you can also select on which post type&prime;s submission form you want to display this field.', DOMAIN ) . '</p>';
		$pointer_id = 'form_table';
		$templatic_url = __('Next',DOMAIN);
		$tour_url = site_url()."/wp-admin/post-new.php?post_type=page&eco_system_custom_fields_tour_step=4";
		$done = true;
		$postion = true;
		$edge = 'left';
	}
	elseif(isset($_REQUEST['eco_system_custom_fields_tour_step']) && $_REQUEST['eco_system_custom_fields_tour_step'] == '4')
	{
		$pointer_content = '<h3>' . __( 'Custom fileds manager.', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'You can add submit form from here by selecting <b>Page - Submit Form</b> and after selecting the template dont forget to select post type for the submission form from below metabox: <b>Post type options</b>', DOMAIN ) . '</p>';
		$pointer_id = 'page_template';
		$templatic_url = __('Next',DOMAIN);
		$tour_url = site_url()."/wp-admin/post-new.php?post_type=page&eco_system_custom_fields_tour_step=4";
		$done = false;
		$postion = true;
		$edge = 'right';
	}
	?>
	<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready( function($) {
	$('#<?php echo $pointer_id ?>').pointer({
	content: '<?php echo $pointer_content; ?>',
	position: {
	<?php if($postion) { ?>
				edge: '<?php echo $edge; ?>',
				<?php if($_REQUEST['eco_system_custom_fields_tour_step']=='3'){?>
					align: 'top center'
				<?php }else{?> 
					align: 'center'
				<?php }if(isset($_REQUEST['eco_system_custom_fields_tour_step']) && ($_REQUEST['eco_system_custom_fields_tour_step']=='custom_taxonomy_activate') || ($_REQUEST['eco_system_custom_fields_tour_step']=='4')){?>
					,offset: '-13 0'
				<?php }?>
	<?php } else
		{?>
				my: 'left top',
				at: 'left bottom',
				offset: '-25 0'
	<?php 	} ?>
		},buttons: function( event, t ) {
	
		var $buttonClose = jQuery('<a class="button-secondary" style="margin-right:10px;" href="#">Dismiss</a>');
				$buttonClose.bind( 'click.pointer', function() {
					
					$.post( ajaxurl, {
					pointer: 'templatic_ecosystem_plugin_custom_fields_install',
					action: 'dismiss-wp-pointer'
				});
					t.element.pointer('close');
				});
	
			<?php if($done) { ?>
				var $buttonNext = $('<a class="button-primary" href="<?php echo $tour_url; ?>#<?php echo $pointer_id ?>"><?php echo $templatic_url; ?></a>');
			<?php } ?>
				var buttons = $('<div class="tiptour-buttons">');
			<?php if($done) { ?>	buttons.append($buttonNext); <?php } ?>
				buttons.append($buttonClose);
				return buttons;
		
		
	}		
		}).pointer('open');
	});
	//]]>
	</script>
<?php
}
/*
name : get_content_in_templatic_WpEcoWorld_user_registration_wp_pointer
description : tour of  user custom field of WpEcoWorld plugin.
*/
function get_content_in_templatic_WpEcoWorld_user_registration_wp_pointer()
{
	
	if(isset($_REQUEST['WpEcoWorld_user_custom_fields_tour_step']) && $_REQUEST['WpEcoWorld_user_custom_fields_tour_step'] == '3')
	{
		$pointer_content = '<h3>' . __( 'User Login and Registration manager.', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'Select Yes if you want to allow user to auto login after registration.', DOMAIN ) . '</p>';
		$pointer_id = 'allow_autologin_after_reg';
		$templatic_url = __('Next',DOMAIN);
		$tour_url = site_url()."/wp-admin/widgets.php?WpEcoWorld_user_custom_fields_tour_step=4";
		$done = false;
		$postion = true;
	}
	elseif(isset($_REQUEST['WpEcoWorld_user_custom_fields_tour_step']) && $_REQUEST['WpEcoWorld_user_custom_fields_tour_step'] == '1')
	{
		$pointer_content = '<h3>' . __( 'User Login and Registration manager.', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'You can add custom fields from here!!! , To display the details or custom fields you created on author dashboard just to  add <b>do_action("author_box")</b> in author.php file located in your current theme directory.', DOMAIN ) . '</p>';
		$pointer_id = 'add_user_custom_fields';
		$templatic_url = __('Next',DOMAIN);
		$tour_url = site_url()."/wp-admin/admin.php?page=user_custom_fields&action=addnew&WpEcoWorld_user_custom_fields_tour_step=2";
		$done = true;
		$postion = false;
	}
	elseif(isset($_REQUEST['WpEcoWorld_user_custom_fields_tour_step']) && $_REQUEST['WpEcoWorld_user_custom_fields_tour_step'] == '2'){
		$pointer_content = '<h3>' . __( 'User Login and Registration manager.', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'You can add user custom fields from here and there is also related login box widget and you can find it here!!!', DOMAIN) . '</p>';
		$pointer_id = 'form_table_user_custom_field';
		$templatic_url = __('Next',DOMAIN);
		$tour_url = site_url()."/wp-admin/admin.php?page=templatic_settings&tab=general&sub_tab=listing&WpEcoWorld_user_custom_fields_tour_step=3";
		$done = true;
		$postion = true;
	}
	
	
	
	?>
	<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready( function($) {
	$('#<?php echo $pointer_id ?>').pointer({
	content: '<?php echo $pointer_content; ?>',
	position: {
	<?php if($postion) { ?>
				edge: 'left',
				<?php if(isset($_REQUEST['WpEcoWorld_user_custom_fields_tour_step']) && $_REQUEST['WpEcoWorld_user_custom_fields_tour_step'] == '3'){?>
				offset: '30 0',
				<?php }?>
				<?php if(isset($_REQUEST['WpEcoWorld_user_custom_fields_tour_step']) && $_REQUEST['WpEcoWorld_user_custom_fields_tour_step'] == '2'){?>
				align: 'top center',	
				<?php }else{?>
				align: 'center',
				<?php }?>
				
	<?php } else{?>
					my: 'left top',
					at: 'left bottom',
					offset: '-25 0'
	<?php 	} ?>
		},buttons: function( event, t ) {
	
		var $buttonClose = jQuery('<a class="button-secondary" style="margin-right:10px;" href="#">Dismiss</a>');
				$buttonClose.bind( 'click.pointer', function() {
					
					$.post( ajaxurl, {
					pointer: 'templatic_weecoworld_plugin_user_registration_install',
					action: 'dismiss-wp-pointer'
				});
					t.element.pointer('close');
				});
	
			<?php if($done) { ?>
				var $buttonNext = $('<a class="button-primary" href="<?php echo $tour_url; ?>#<?php echo $pointer_id ?>"><?php echo $templatic_url; ?></a>');
			<?php } ?>
				var buttons = $('<div class="tiptour-buttons">');
				<?php if($done) { ?> buttons.append($buttonNext); <?php } ?>
				buttons.append($buttonClose);
				return buttons;
		
		
	}		
		}).pointer('open');
	});
	//]]>
	</script>
<?php
}
/*
name : get_content_in_templatic_WpEcoWorld_widget_wp_pointer
description : tour of widget of WpEcoWorld plugin.
*/
function get_content_in_templatic_WpEcoWorld_widget_wp_pointer()
{
	if(!isset($_REQUEST['WpEcoWorld_widget_tour_step']) && $_REQUEST['WpEcoWorld_widget_tour_step'] == '')
	{
		$pointer_content = '<h3>' . __( 'Widget manager.', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'Templatic widgets now included on your site to know how to use please click on &prime; Start Tour &prime;  !', DOMAIN ) . '</p>';
		$templatic_url = __('Start Tour',DOMAIN);
		$pointer_id = 'widget_setting';
		$tour_url = site_url()."/wp-admin/admin.php?page=templatic_settings&tab=general&sub_tab=widgets&WpEcoWorld_widget_tour_step=1";
		$done = true;
		$postion = false;
	}
	elseif(isset($_REQUEST['WpEcoWorld_widget_tour_step']) && $_REQUEST['WpEcoWorld_widget_tour_step'] == '1')
	{
		$pointer_content = '<h3>' . __( 'Widget manager.', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'You can activate your widget and all the activate widget you will finr here!!!', DOMAIN ) . '</p>';
		$pointer_id = 'widgets';
		$templatic_url = __('Next',DOMAIN);
		$tour_url = site_url()."/wp-admin/widgets.php";
		$done = true;
		$postion = true;
	}
	
	?>
	<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready( function($) {
	$('#<?php echo $pointer_id ?>').pointer({
	content: '<?php echo $pointer_content; ?>',
	position: {
	<?php if($postion) { ?>
				edge: 'left',
				align: 'center'
	<?php } else
		{?>
				my: 'left top',
				at: 'left bottom',
				offset: '-25 0'
	<?php 	} ?>
		},buttons: function( event, t ) {
	
		var $buttonClose = jQuery('<a class="button-secondary" style="margin-right:10px;" href="#">Dismiss</a>');
				$buttonClose.bind( 'click.pointer', function() {
					
					$.post( ajaxurl, {
					pointer: 'templatic_wpecoworld_plugin_widget_install',
					action: 'dismiss-wp-pointer'
				});
					t.element.pointer('close');
				});
	
			<?php if($done) { ?>
				var $buttonNext = $('<a class="button-primary" href="<?php echo $tour_url; ?>#<?php echo $pointer_id ?>"><?php echo $templatic_url; ?></a>');
			<?php } ?>
				var buttons = $('<div class="tiptour-buttons">');
				<?php if($done) { ?> buttons.append($buttonNext); <?php } ?>
				buttons.append($buttonClose);
				return buttons;
		
		
	}		
		}).pointer('open');
	});
	//]]>
	</script>
<?php
}
/*
name : get_content_in_templatic_WpEcoWorld_monetization_wp_pointer
description : tour of  Monetization of WpEcoWorld plugin.
*/
function get_content_in_templatic_WpEcoWorld_monetization_wp_pointer()
{
	
	if(isset($_REQUEST['WpEcoWorld_monetization_tour_step']) && $_REQUEST['WpEcoWorld_monetization_tour_step'] == '1')
	{
		$pointer_content = '<h3>' . __( 'Currency Setting', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'Mention currency in which you will take payment and also select how you want the currency symbol to appear. e.g., Before amount or after amount ?', DOMAIN ) . '</p>';
		$pointer_id = 'currency_settings';
		$templatic_url = __('Next',DOMAIN);
		$tour_url = site_url()."/wp-admin/admin.php?page=monetization&tab=packages&WpEcoWorld_monetization_tour_step=2";
		$done = true;
		$postion = true;
	}
	elseif(isset($_REQUEST['WpEcoWorld_monetization_tour_step']) && $_REQUEST['WpEcoWorld_monetization_tour_step'] == '2')
	{
		$pointer_content = '<h3>' . __( 'Price Package', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'You can add your price package from here, !!!', DOMAIN ) . '</p>';
		$pointer_id = 'add_price_package';
		$templatic_url = __('Next',DOMAIN);
		$tour_url = site_url()."/wp-admin/admin.php?page=monetization&action=add_package&tab=packages&WpEcoWorld_monetization_tour_step=3";
		$done = true;
		$postion = true;
	}
	elseif(isset($_REQUEST['WpEcoWorld_monetization_tour_step']) && $_REQUEST['WpEcoWorld_monetization_tour_step'] == '3')
	{
		$pointer_content = '<h3>' . __( 'Add Price Package', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'Fill out the details in this form to create a new price package or click Next to go back to the previous page!!!', DOMAIN ) . '</p>';
		$pointer_id = 'form_table_monetize';
		$templatic_url = __('Next',DOMAIN);
		$tour_url = site_url()."/wp-admin/admin.php?page=monetization&tab=payment_options&WpEcoWorld_monetization_tour_step=4";
		$done = true;
		$postion = true;
	}
	elseif(isset($_REQUEST['WpEcoWorld_monetization_tour_step']) && $_REQUEST['WpEcoWorld_monetization_tour_step'] == '4')
	{
		$pointer_content = '<h3>' . __( 'Payment Gateways', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'You can activate or deactivate your payment gateways from here!!!', DOMAIN ) . '</p>';
		$pointer_id = 'payment_options_settings';
		$templatic_url = __('Next',DOMAIN);
		$tour_url = site_url()."/wp-admin/admin.php?page=monetization&tab=manage_coupon&WpEcoWorld_monetization_tour_step=5";
		$done = true;
		$postion = true;
	}
	elseif(isset($_REQUEST['WpEcoWorld_monetization_tour_step']) && $_REQUEST['WpEcoWorld_monetization_tour_step'] == '5')
	{
		$pointer_content = '<h3>' . __( 'Payment Gateways', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'You can add discount coupons from here, you can advertise these coupons to allow your visitors to get a discount while adding listings!!!', DOMAIN ) . '</p>';
		$pointer_id = 'coupon_list';
		$templatic_url = __('Next',DOMAIN);
		$tour_url = site_url()."/wp-admin/admin.php?page=monetization&tab=manage_coupon&action=addnew&WpEcoWorld_monetization_tour_step=6";
		$done = true;
		$postion = true;
	}
	elseif(isset($_REQUEST['WpEcoWorld_monetization_tour_step']) && $_REQUEST['WpEcoWorld_monetization_tour_step'] == '6')
	{
		$pointer_content = '<h3>' . __( 'Payment Gateways', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'Fill out the mentioned details to add a coupon!!!', DOMAIN ) . '</p>';
		$pointer_id = 'form_table_coupon';
		$done = false;
		$postion = true;
	}
	?>
	<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready( function($) {
	$('#<?php echo $pointer_id ?>').pointer({
	content: '<?php echo $pointer_content; ?>',
	position: {
	<?php if($postion) { ?>
				edge: 'left',
				<?php if(isset($_REQUEST['WpEcoWorld_monetization_tour_step']) && $_REQUEST['WpEcoWorld_monetization_tour_step']=='3' || $_REQUEST['WpEcoWorld_monetization_tour_step']=='6'){?>
						align: 'top center'
				<?php }else{?>
						align: 'center'
				<?php }?>
	<?php } else
		{?>
				my: 'left top',
				at: 'left bottom',
				offset: '-25 0'
	<?php 	} ?>
		},buttons: function( event, t ) {
	
		var $buttonClose = jQuery('<a class="button-secondary" style="margin-right:10px;" href="#">Dismiss</a>');
				$buttonClose.bind( 'click.pointer', function() {
					
					$.post( ajaxurl, {
					pointer: 'templatic_wpecoworld_plugin_monetization_install',
					action: 'dismiss-wp-pointer'
				});
					t.element.pointer('close');
				});
	
			<?php if($done) { ?>
				var $buttonNext = $('<a class="button-primary" href="<?php echo $tour_url; ?>#<?php echo $pointer_id ?>"><?php echo $templatic_url; ?></a>');
			<?php } ?>
				var buttons = $('<div class="tiptour-buttons">');
				<?php if($done) { ?> buttons.append($buttonNext); <?php } ?>
				buttons.append($buttonClose);
				return buttons;
		
		
	}		
		}).pointer('open');
	});
	//]]>
	</script>
<?php
}

/* NAME : get_content_in_templatic_WpEcoWorld_claim_wp_pointer
DESCRIPTION : TOUR OF CLAIM OWNERSHIP MODULE */
function get_content_in_templatic_WpEcoWorld_claim_wp_pointer()
{
	if(isset($_REQUEST['WpEcoWorld_claim_tour_step']) && $_REQUEST['WpEcoWorld_claim_tour_step'] == '1')
	{
		$pointer_content = '<h3>' . __( 'Claim Settings', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'Select the post types for which you want to enable this feature.', DOMAIN ) . '</p>';
		$templatic_url = __('Next',DOMAIN);
		$pointer_id = 'basic';
		$tour_url = site_url()."/wp-admin/widgets.php?WpEcoWorld_claim_tour_step=2";
		$done = true;
		$postion = true;
	}
	elseif(isset($_REQUEST['WpEcoWorld_claim_tour_step']) && $_REQUEST['WpEcoWorld_claim_tour_step'] == '2')
	{
		$pointer_content = '<h3>' . __( 'Set Claim Widget', DOMAIN ) . '</h3>';
		$pointer_content .= '<p>' . __( 'At the end, Place <b>T <small>></small> Claim Ownership</b> widget in detail page sidebar of taxonomy/post you selected from <a href="'.site_url().'/wp-admin/admin.php?page=templatic_settings&tab=general&sub_tab=basic">General Settings</a> ', DOMAIN ) . '</p>';
		$pointer_id = 'icon-themes';
		$done = false;
		$postion = false;
	}
	?>
	<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready( function($) {
	$('#<?php echo $pointer_id ?>').pointer({
	content: '<?php echo $pointer_content; ?>',
	position: {
	<?php if($postion) { ?>
				edge: 'left',
				align: 'center'
	<?php } else
		{?>
				my: 'left top',
				at: 'left bottom',
				offset: '-25 0'
	<?php 	} ?>
		},buttons: function( event, t ) {
	
		var $buttonClose = jQuery('<a class="button-secondary" style="margin-right:10px;" href="#">Dismiss</a>');
				$buttonClose.bind( 'click.pointer', function() {
					
					$.post( ajaxurl, {
					pointer: 'templatic_wpecoworld_plugin_claim_install',
					action: 'dismiss-wp-pointer'
				});
					t.element.pointer('close');
				});
	
			<?php if($done) { ?>
				var $buttonNext = $('<a class="button-primary" href="<?php echo $tour_url; ?>#<?php echo $pointer_id ?>"><?php echo $templatic_url; ?></a>');
			<?php } ?>
				var buttons = $('<div class="tiptour-buttons">');
				<?php if($done) { ?> buttons.append($buttonNext); <?php } ?>
				buttons.append($buttonClose);
				return buttons;
		
		
	}		
		}).pointer('open');
	});
	//]]>
	</script>
<?php
}
/*
name : WpEcoWorld_enqueue_wp_pointer
description : Filter which handle all tour of WpEcoWorld.
*/
function WpEcoWorld_enqueue_wp_pointer( $hook_suffix ) 
{
	
	$enqueue = FALSE;
	$templatic_WpEcoWorld_plugin_seen_it = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
	
	// at first assume we don't want to show pointers
	$do_add_script = false;
	
	// Handle our first pointer announcing the plugin's new settings screen.
	if ( ! in_array( 'templatic_ecosystem_plugin_install', $templatic_WpEcoWorld_plugin_seen_it ) && is_plugin_active('Tevolution/templatic.php') ) 
	{
		$enqueue = TRUE;
		$do_add_script = true;
		// hook to function that will output pointer script just for templatic_ecosystem_plugin
		add_action( 'admin_print_footer_scripts',get_content_in_templatic_eco_system_wp_pointer );
	}
	
	if(!isset($_REQUEST['eco_system_tour_step']) || $_REQUEST['eco_system_tour_step'] == '8' ){
		// Handle our first pointer announcing the plugin's new settings screen.
		if ( ! in_array( 'templatic_ecosystem_plugin_custom_taxonomy_install', $templatic_WpEcoWorld_plugin_seen_it ) && is_plugin_active('Tevolution/templatic.php') && is_active_addons('custom_taxonomy')) 
		{
			$enqueue = TRUE;
			$do_add_script = true;
			// hook to function that will output pointer script just for templatic_ecosystem_plugin
			add_action( 'admin_print_footer_scripts','get_content_in_templatic_eco_system_custom_taxonomy_wp_pointer' );
		}
		// Handle our first pointer announcing the plugin's new settings screen.
		if ( ! in_array( 'templatic_ecosystem_plugin_custom_fields_install', $templatic_WpEcoWorld_plugin_seen_it ) && is_plugin_active('Tevolution/templatic.php') && is_active_addons('custom_fields_templates')) 
		{
			$enqueue = TRUE;
			$do_add_script = true;
			// hook to function that will output pointer script just for templatic_ecosystem_plugin
			add_action( 'admin_print_footer_scripts','get_content_in_templatic_eco_system_custom_fields_wp_pointer');
		}
		if(!isset($_REQUEST['eco_system_custom_fields_tour_step'])){
			// Handle our first pointer announcing the plugin's new settings screen.
			if ( ! in_array( 'templatic_weecoworld_plugin_user_registration_install', $templatic_WpEcoWorld_plugin_seen_it ) && is_plugin_active('Tevolution/templatic.php') && is_active_addons('templatic-login')) 
			{
				$enqueue = TRUE;
				$do_add_script = true;
				// hook to function that will output pointer script just for templatic_ecosystem_plugin
				add_action( 'admin_print_footer_scripts','get_content_in_templatic_WpEcoWorld_user_registration_wp_pointer' );
			}
		}
		if(!isset($_REQUEST['eco_system_custom_fields_tour_step'])){
			// Handle our first pointer announcing the plugin's new settings screen.
			if ( ! in_array( 'templatic_wpecoworld_plugin_widget_install', $templatic_WpEcoWorld_plugin_seen_it ) && is_plugin_active('Tevolution/templatic.php') && is_active_addons('templatic-login')) 
			{
				$enqueue = TRUE;
				$do_add_script = true;
				// hook to function that will output pointer script just for templatic_ecosystem_plugin
				add_action( 'admin_print_footer_scripts','get_content_in_templatic_WpEcoWorld_widget_wp_pointer' );
			}
		}
		if(!isset($_REQUEST['eco_system_custom_fields_tour_step'])){
			// Handle our first pointer announcing the plugin's new settings screen.
			if ( ! in_array( 'templatic_wpecoworld_plugin_monetization_install', $templatic_WpEcoWorld_plugin_seen_it ) && is_plugin_active('Tevolution/templatic.php') && is_active_addons('monetization')) 
			{
				$enqueue = TRUE;
				$do_add_script = true;
				// hook to function that will output pointer script just for templatic_ecosystem_plugin
				add_action( 'admin_print_footer_scripts','get_content_in_templatic_WpEcoWorld_monetization_wp_pointer' );
			}
		}
		if(!isset($_REQUEST['eco_system_custom_fields_tour_step'])){		
			// Handle our first pointer announcing the plugin's new settings screen.
			if ( ! in_array( 'templatic_wpecoworld_plugin_claim_install', $templatic_WpEcoWorld_plugin_seen_it ) && is_plugin_active('Tevolution/templatic.php') && is_active_addons('claim_ownership')) 
			{
				$enqueue = TRUE;
				$do_add_script = true;
				// hook to function that will output pointer script just for templatic_ecosystem_plugin
				add_action( 'admin_print_footer_scripts','get_content_in_templatic_WpEcoWorld_claim_wp_pointer' );
			}
		}	
	}	
	// at first assume we don't want to show pointers
	$do_add_script = false;
	
	// in true, include the scripts
	if ( $enqueue ) {
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
		wp_enqueue_script( 'utils' ); // for user settings
	}
}
add_action( 'admin_enqueue_scripts', 'WpEcoWorld_enqueue_wp_pointer' );
function activate_single_tour($tour_name){
	global $wpdb;
	$restart_tour = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
	$default_pointers = "";
	foreach($restart_tour as $tour){
		if($tour == $tour_name){
		}else{
			$default_pointers .= $tour.",";
		}
	}
	$default_pointers = rtrim($default_pointers,',');
	update_user_meta(get_current_user_id(),'dismissed_wp_pointers',$default_pointers);
}
/*
name :wpml_insert_templ_post
desc : enter language details when wp_insert_post in process ( during insert the post )
*/

function wpml_insert_templ_post($last_post_id,$post_type){
	global $wpdb,$sitepress;
	$icl_table = $wpdb->prefix."icl_translations";
	$current_lang_code= ICL_LANGUAGE_CODE;
	$element_type = "post_".$post_type;
	$default_languages = ICL_LANGUAGE_CODE;
	$default_language = $sitepress->get_default_language();
	$trid = $wpdb->get_var($wpdb->prepare("select trid from $icl_table order by trid desc LIMIT 0,1"));
	//	echo $insert_tr = " INSERT INTO $icl_table (`translation_id` ,`element_type` ,`element_id` ,`trid` ,`language_code` ,`source_language_code`)VALUES ( '' , '".$element_type."', $last_post_id, $trid , '".$current_lang_code."', '".$current_lang_code."')";
	$update = "update $icl_table set language_code = '".$current_lang_code."' where element_id = '".$last_post_id."' and trid=$trid";
	$wpdb->query($update);		/* insert in transactions table */
}

/*
 * Function Name:get_additional_image_sizes;
 * Return : display all image size
 */
function get_additional_image_sizes() {
	global $_wp_additional_image_sizes;
	if ( $_wp_additional_image_sizes )
			return $_wp_additional_image_sizes;
	return array();
}
/*
 * Add action display open all close all tevolution dashboard
 */
add_action('tevolution_plugin_list','open_close_tevolution_dashboard');
function open_close_tevolution_dashboard()
{
	?>
     <ul class="subsubsub fr hide-if-no-js open-close-all">
          <li>
          <a href="#open-all" class="button-secondary">Open All</a>
          </li>
          <li>
          <a href="#close-all" class="button-secondary">Close All</a>
          </li>
     </ul>
     <?php	
}

add_action('admin_head','admin_script');
function admin_script()
{	
	wp_register_script('admin-script',TEMPL_PLUGIN_URL."js/admin-script.js");
	wp_enqueue_script('admin-script');
}

/* Action Edit,renew and delete link on author page */
/*
 * Function Name: tevolution_author_renoew_delete_link 
 * Return: display renew, edit and delete link in author page
 */
add_action('templ_show_edit_renew_delete_link', 'tevolution_author_renoew_delete_link');
function tevolution_author_renoew_delete_link()
{
	global $post,$author_post;
	$author_post=$post;
	if(is_author() && is_user_logged_in())
	{
		//$title.=$title;
		$link='';
		$postid=$post->ID;
		$post_type=$post->post_type;
		$postdate = $post->post_date;
		//get the submited page id from post typpe
		$args=array(	
			'post_type' => 'page',
			'post_status' => 'publish',				
			'meta_query' => array(
								array(
									'key' => '_wp_page_template',
									'value' => 'page-template_form.php',
									'compare' => '='
									),				
								array(
									'key' => 'template_post_type',
									'value' => $post_type,
									'compare' => '='
									)
								)
				);
		remove_all_actions('posts_where');
		$the_query  = new WP_Query( $args );	
		if( $the_query->have_posts()):
			foreach($the_query as $post):
				if($post->ID != ""):
					$page_id=$post->ID;
				endif;	
			endforeach;
			//get the front side submited page id permalink					
			$page_link=get_permalink($page_id);
			$edit_link = '';
			$review_link = '';
			if(strpos($page_link, "?"))
			{
				$edit_link = $page_link."&amp;pid=".$postid."&amp;action=edit";
				$review_link = $page_link."&amp;pid=".$postid."&amp;renew=1";
				$delete_link = $page_link."&amp;pid=".$postid."&amp;page=preview&amp;action=delete";
			}
			else
			{
				$edit_link = $page_link."?pid=".$postid."&amp;action=edit";
				$review_link = $page_link."?pid=".$postid."&amp;renew=1";
				$delete_link = $page_link."?pid=".$postid."&amp;page=preview&amp;action=delete";
			}
			$exp_days = get_time_difference_plugin( $postdate, $postid);
			$link = '';
			if($exp_days > 0 && $exp_days != '' )
			 {
				$link.='<a class="button tiny_btn post-edit-link" title="Edit Item" href="'.$edit_link.'" target="_blank">'.__('Edit',DOMAIN).'</a>&nbsp;&nbsp;';
			 }
			else
			 {		
				$link.='<a class="button tiny_btn post-edit-link" title="Renew Item" href="'.$review_link.'" target="_blank">'.__('Renew',DOMAIN).'</a>&nbsp;&nbsp;';
			 }	
			 $link.='<a class="button tiny_btn post-edit-link" title="Delete Item" href="'.$delete_link.'" target="_blank">'.__('Delete',DOMAIN).'</a>&nbsp;&nbsp;';
		endif;
		$title.=$link;		
	}
	$post=$author_post;
 
   do_action('templ_cancel_recurring_payment', $delete_link, $exp_days);
}
/*
Name : new_excerpt_more
Desc : Read more link filter
*/
if(!function_exists('new_excerpt_more')){
function new_excerpt_more($more) {
      global $post;
	  $tmpdata = get_option('templatic_settings');
	if($tmpdata['templatic_excerpt_link']){
		return '... <a class="moretag" href="'. get_permalink($post->ID) . '">'.$tmpdata['templatic_excerpt_link'].'</a>';
	}else{
		return '... <a class="moretag" href="'. get_permalink($post->ID) . '"> Read more &raquo;</a>';
	}
}
}
?>