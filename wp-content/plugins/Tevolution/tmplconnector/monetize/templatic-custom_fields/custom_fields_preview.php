<?php
session_start();
get_header(); // display header 
global $upload_folder_path;
if(isset($_POST['preview'])){
	
	$_SESSION['custom_fields'] = $_POST; // set custom_fields session
	$post_title = stripslashes($_POST['post_title']);
	
	$geo_latitude = $_POST['geo_latitude'];
	$geo_longitude = $_POST['geo_longitude'];
	$post_content = $_POST['post_content'];
	$post_excerpt = $_POST['post_excerpt'];
	
	if(isset($_POST['category']))
	 {
		$_SESSION['category'] = $_POST['category'];
	 }
}


$current_user = wp_get_current_user();
$cur_post_id = $_SESSION['custom_fields']['cur_post_id'];
$cur_post_type = get_post_meta($cur_post_id,'template_post_type',true);
$cat_display=get_option('ptthemes_category_dislay'); // fetch category display type


if(!isset($post_title))
	$post_title=stripslashes($_SESSION['custom_fields']['post_title']);
if(!isset($post_content))
	$post_content=$_SESSION['custom_fields']['post_content'];

//contion for captcha inserted properly or not.
$tmpdata = get_option('templatic_settings');
$display = $tmpdata['user_verification_page'];
$id = $_SESSION['custom_fields']['cur_post_id'];
$permalink = get_permalink( $id );
$play = get_option('ptthemes_captcha_option');
if( is_plugin_active('wp-recaptcha/wp-recaptcha.php') && $tmpdata['recaptcha'] == 'recaptcha' && in_array('submit',$display)){
		require_once( ABSPATH.'wp-content/plugins/wp-recaptcha/recaptchalib.php');
		$a = get_option("recaptcha_options");
		$privatekey = $a['private_key'];
						$resp = recaptcha_check_answer ($privatekey,
								getenv("REMOTE_ADDR"),
								$_POST["recaptcha_challenge_field"],
								$_POST["recaptcha_response_field"]);
											
		if (!$resp->is_valid ) {
			if($_REQUEST['pid'] != '')
			 {
				wp_redirect(get_permalink($cur_post_id).'/?ptype=post_event&pid='.$_REQUEST['pid'].'&action=edit&backandedit=1&ecptcha=captch');
			 }
			 else
			 {
				wp_redirect(get_permalink($cur_post_id).'/?ptype=post_event&backandedit=1&ecptcha=captch');	 
			 }
			exit;
		} 
	}
if(file_exists(ABSPATH.'wp-content/plugins/are-you-a-human/areyouahuman.php') && is_plugin_active('are-you-a-human/areyouahuman.php') && $tmpdata['recaptcha'] == 'playthru'  && in_array('submit',$display))
{
	require_once( ABSPATH.'wp-content/plugins/are-you-a-human/areyouahuman.php');
	require_once(ABSPATH.'wp-content/plugins/are-you-a-human/includes/ayah.php');
	$ayah = new AYAH();
	$score = $ayah->scoreResult();
	if(!$score)
	{
		wp_redirect(get_permalink($cur_post_id).'/?ptype=post_event&backandedit=1&invalid=playthru');
		exit;
	}
}
/* show preview of uploaded image begin */
global $upload_folder_path;

if($_POST['imgarr'])
{ ?>
	<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>js/jquery.lightbox-0.5.js"></script>
		 <script type="text/javascript">
			var IMAGE_LOADING = '<?php echo plugin_dir_url( __FILE__ )."images/lightbox-ico-loading.gif"; ?>';
			var IMAGE_PREV = '<?php echo plugin_dir_url( __FILE__ )."images/lightbox-btn-prev.gif"; ?>';
			var IMAGE_NEXT = '<?php echo plugin_dir_url( __FILE__ )."images/lightbox-btn-next.gif"; ?>';
			var IMAGE_CLOSE = '<?php echo plugin_dir_url( __FILE__ )."images/lightbox-btn-close.gif"; ?>';
			var IMAGE_BLANK = '<?php echo plugin_dir_url( __FILE__ )."images/lightbox-blank.gif"; ?>';
			jQuery(function() {
				jQuery('#gallery a').lightBox();
			});
		</script>
	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ); ?>/css/jquery.lightbox-0.5.css" media="screen" />
	<?php
		$_SESSION["file_info"] = explode(",",$_POST['imgarr']);
	}else{
		$_SESSION["file_info"] = ''; }
	if($_SESSION["file_info"])
	{
		foreach($_SESSION["file_info"] as $image_id=>$val)
		{
			 $image_src =  get_template_directory_uri().'/images/tmp/'.$val;
			 break;
		}				
		
	}else
	{
		/* exucutre when come after go back nad edit */
		$image_src = $thumb_img_arr[0];
		if($_REQUEST['pid']){
			$large_img_arr = bdw_get_images_plugin($_REQUEST['pid'],'medium');
			$thumb_img_arr = bdw_get_images_plugin($_REQUEST['pid'],'thumb');
		}
		$image_src = $large_img_arr[0];		
	}
	if($_REQUEST['pid'])
	{	/* exicute when comes for edit the post */
		$large_img_arr = bdw_get_images_plugin($_REQUEST['pid'],'medium');
		$thumb_img_arr = bdw_get_images_plugin($_REQUEST['pid'],'thumb');
		$largest_img_arr = bdw_get_images_plugin($_REQUEST['pid'],'large');		
	}
/* show preview of uploaded image end */


	
	do_action('templ_before_preview_container_breadcrumb');/*Add action for display the bradcrumb in between header and container. */
?>
<style type="text/css">
	.payment_error{
		color:red;
		font-size:12px;
		display:block;
	}
</style>
<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>js/payment_gateway_validation.js"></script>
<div class="contentarea" id="content">
<?php
	do_action('templ_inside_preview_container_breadcrumb');/*Add action for display the bradcrumn  inside the container. */
	include (TEMPL_MONETIZE_FOLDER_PATH . "templatic-custom_fields/submit_preview_buttons.php"); /* fetch publish options and button options */?>
	<?php do_action('templ_preview_before_post_title');/*do_action before the preview post title */?>
	
    <h2><?php echo stripslashes($post_title); ?></h2>
    
	<?php do_action('templ_preview_after_post_title');/*do_action after previwe post title. */?>
    
	<?php do_action('tmpl_preview_page_gallery');/* Add Action for preview display single post image gallery. */?> 
	
		
	<?php do_action('templ_preview_before_post_content'); /*Add Action for before preview post content. */?> 
	<?php if(isset($post_content) && $post_content !=''): /* Check condition for post content not balank */?>      
            <div class="title-container">
                <h1><span><?php _e('Post Description',DOMAIN);?></span></h1>
                <p><?php echo nl2br(stripslashes($post_content)); ?></p>
            </div>         
    <?php endif; /* Finish the post content condition */ ?>
    
    <div>
	<?php		
		if($_REQUEST['action'] == 'delete'):
			do_action('tmpl_detail_page_custom_fields_collection');
		else:	
			do_action('tmpl_preview_page_fields_collection',$cur_post_type);
		endif;	
	?>    
    	<div class="clearfix"></div>
    </div>
    <?php do_action('templ_preview_page_file_upload');// Add action for preview file upload	 ?>
    
	<?php do_action('templ_preview_after_post_content'); /*Add Action for after preview post content. */?> 
      
	<?php do_action('templ_preview_address_map');/*Add action for display preview map */?>	
<br/>   
<?php //include (TEMPL_MONETIZE_FOLDER_PATH . "templatic-custom_fields/submit_preview_buttons.php");?>
<script type="text/javascript">
var $scroll = jQuery.noConflict();
$scroll(document).ready(function(){
	$scroll(function () {
		$scroll('#back-top a').click(function () {
			$scroll('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	});
});
</script>
<div id="back-top" class="get_direction clearfix">
	<a href="#top" class="button getdir" style=""><?php _e('Back to Top',DOMAIN);?></a>
</div>
</div>
<div class="sidebar" id="sidebar-primary">
<?php dynamic_sidebar($cur_post_type.'_detail_sidebar');?>
</div>
<?php get_footer(); ?>