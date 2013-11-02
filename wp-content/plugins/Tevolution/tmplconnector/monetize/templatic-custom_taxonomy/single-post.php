<?php get_header(); ?>

<?php do_action('templ_before_single_container_breadcrumb');
/*Add action for display the bradcrumb in between header and container. */?>

<?php do_action('templ_inside_single_container_breadcrumb');
/*Add action for display the bradcrumn  inside the container. */ ?>

	<!-- End Images slide show -->
	<div id="content" role="main">	
	<?php 
		//get details page custom fields
		global $single_htmlvar_name,$single_pos_title;		
		$post_meta_info = details_field_collection();

		$single_htmlvar_name='';
		if($post_meta_info)
		{
			while ($post_meta_info->have_posts()) : $post_meta_info->the_post();
				$ctype = get_post_meta($post->ID,'ctype',true);
				$post_name = get_post_meta($post->ID,'htmlvar_name',true);				
				$single_htmlvar_name[$post_name] = $ctype;
				$single_pos_title[] = $post->post_title;
			endwhile;
		}
		//finish details page custom fields
	?>
	<?php if(have_posts()) : ?>
	<script type="text/javascript" src="<?php echo CUSTOM_FIELDS_URLPATH; ?>js/jquery.lightbox-0.5.js"></script>
	<script type="text/javascript">
		var IMAGE_LOADING = '<?php echo CUSTOM_FIELDS_URLPATH."images/lightbox-ico-loading.gif"; ?>';
		var IMAGE_PREV = '<?php echo CUSTOM_FIELDS_URLPATH."images/lightbox-btn-prev.gif"; ?>';
		var IMAGE_NEXT = '<?php echo CUSTOM_FIELDS_URLPATH."images/lightbox-btn-next.gif"; ?>';
		var IMAGE_CLOSE = '<?php echo CUSTOM_FIELDS_URLPATH."images/lightbox-btn-close.gif"; ?>';
		var IMAGE_BLANK = '<?php echo CUSTOM_FIELDS_URLPATH."images/lightbox-blank.gif"; ?>';
		jQuery(function() {
			jQuery('#gallery a').lightBox();
		});
	</script>
	<link rel="stylesheet" type="text/css" href="<?php echo CUSTOM_FIELDS_URLPATH; ?>css/jquery.lightbox-0.5.css" media="screen" />	
	<?php while(have_posts()) : the_post() ?>
    
    <?php do_action('templ_before_post_title'); /* add action for before the post title.*/ ?>
    
    <h2><?php the_title(); ?></h2>     

    <?php do_action('templ_after_post_title'); /* add action for after the post title.*/?>
    
    <?php do_action('templ_post_info'); /*add action for display the post info */ ?>
        <?php 
			  $tmpdata = get_option('templatic_settings');
			  $display = $tmpdata['user_verification_page'];
			  $captcha_set = array();
			  $captcha_dis = '';
			  if(is_array($display))
			  {
				  foreach($display as $_display)
				   {
					  if($_display == 'claim' || $_display == 'emaitofrd')
					   { 
						$captcha_set[] = $_display;
						 $captcha_dis = $_display;
					   }
				   }	
			  }?>
               
        <div id="myrecap" style="display:none;"><?php templ_captcha_integrate($captcha_dis); ?></div> 
        <input type="hidden" id="owner_frm" name="owner_frm" value=""  />
        <div id="claim_ship"></div>
		<script type="text/javascript">
            jQuery('#owner_frm').val(jQuery('#myrecap').html());
        </script>

        <?php do_action('templ_send_friend_inquiry_email'); /* Add Action for send to friend and send inquiry mail. */?>
		<?php do_action('tmpl_detail_page_image_gallery'); /* Add Action for display single post image gallery. */ ?>   
        <?php do_action('templ_before_post_content'); /* Add Action for before the post content. */ ?> 
         <div class="row">
               <div class="twelve columns">
                    <div class="title_space">
                        <div class="title-container">
							   <?php 
									$post_type_object = get_post_type_object($post->post_type);
									$post_type_label = $post_type_object->labels->name;
							   ?>	
                        	   <?php $post_description=str_replace('Post',$post_type_label,'Post Description')?>
                            <h1><?php _e($post_description,DOMAIN);?></h1>
                         </div>
                        <?php the_content();?>
                    </div>
                </div>
            </div>
    	<?php do_action('templ_after_post_content'); /* Add Action for after the post content. */?> 

	<?php 
	endwhile;wp_reset_query(); ?>
	<?php endif; ?>    
	
	<?php do_action('tmpl_detail_page_custom_fields_collection');  ?>

	<?php wp_reset_query(); ?>
    
    <?php do_action('tmpl_before_comments'); /* add action for display before the post comments. */ ?>
	  <?php do_action('tmpl_single_post_pagination'); /* add action for display the next previous pagination */ ?>
	<?php
	/* Add ratings after default fields above the comment box, always visible */
	$tmpdata = get_option('templatic_settings');
	if($tmpdata['templatin_rating']=='yes'):
		add_action( 'comment_form_logged_in_after', 'ratings_in_comments' );
		add_action( 'comment_form_after_fields', 'ratings_in_comments' );
		add_action( 'comment_text', 'display_rating_star' );
	endif;
	
	if($post->post_status =='publish'){
	?>
    <div id="comments"><?php comments_template(); ?></div>
    <?php } ?>
	<?php do_action('tmpl_after_comments'); /*Add action for display after the post comments. */?>
    
    <?php do_action('tmpl_related_post'); /*add action for display the related post list. */?>
</div>

<?php
$display = get_option('supreme_theme_settings');//[supreme_global_layout]
$layout = $display['supreme_global_layout'];
if(!$layout){
	$layout ='2c';
}
if ( is_active_sidebar(get_post_type().'_detail_sidebar') && $layout !='layout_1c' ) : 
do_atomic( 'before_sidebar_primary' ); // supreme_before_sidebar_primary ?>

	<div id="sidebar-primary" class="sidebar">

		<?php do_atomic( 'open_sidebar_primary' ); // supreme_open_sidebar_primary ?>

		<?php  dynamic_sidebar(get_post_type().'_detail_sidebar'); ?>

		<?php do_atomic( 'close_sidebar_primary' ); // supreme_close_sidebar_primary ?>

	</div><!-- #sidebar-primary -->

	<?php do_atomic( 'after_sidebar_primary' ); // supreme_after_sidebar_primary ?>

<?php endif; ?>
<?php if(in_array('claim',$captcha_set)): ?>
<script type="text/javascript">
	jQuery("#trigger_id").click(function(){
		var caphtml = jQuery("#owner_frm").val();
		jQuery('#claim_ship_cap').html(caphtml);
		jQuery('#myrecap').html('');
		jQuery("#snd_frnd_cap").html('');
	});
</script>	
<?php endif; ?>
<?php if(in_array('emaitofrd',$captcha_set)): ?>
<script type="text/javascript">
	jQuery("#send_friend_id").click(function(){
			var caphtml = jQuery("#owner_frm").val();
			jQuery('#snd_frnd_cap').html(caphtml);
			jQuery('#myrecap').html('');
			jQuery("#claim_ship").html('');
	});
</script>
<?php endif; ?>
<?php get_footer(); ?>