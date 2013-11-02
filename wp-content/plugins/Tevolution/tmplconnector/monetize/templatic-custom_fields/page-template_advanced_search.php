<?php
/*
Template Name: Page - Advanced Search
*/
get_header(); 
$post_type = get_post_meta($post->ID,'template_post_type',true);
?>
<?php if ( current_theme_supports( 'breadcrumb-trail' ) ) breadcrumb_trail( array( 'separator' => '&raquo;' ) ); ?>
<div id="content" class="contentarea">
        <h1 class="title-container"><?php _e('Search this website','supreme'); ?></h1>
        <form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" onsubmit="return sformcheck();" class="form_front_style">        
           <div class="form_row clearfix">
			   <label><?php _e('Search','supreme');?><span class="required">*</span></label>
			   <input class="adv_input" name="s" id="adv_s" type="text" PLACEHOLDER="<?php _e('Search','supreme'); ?>" value="" />			  
			   <span class="message_error2"  style="color:red;font-size:12px;" id="search_error"></span>			  
		   </div>
           
		   <div class="form_row clearfix">
			   <label><?php _e('Tags','supreme');?></label>
			   <input class="adv_input" name="tag_s" id="tag_s" type="text"  PLACEHOLDER="<?php _e('Tags','supreme'); ?>" value=""  />			  
		   </div>
		   <?php 
				$post_type = get_post_meta($post->ID,'template_post_type',true);
				$custom_post_types_args = array();  
				$custom_post_types = get_post_types($custom_post_types_args,'objects');
				foreach ($custom_post_types as $content_type){
					if($content_type->name == $post_type)
					{
						if($content_type->name =='post' || strtolower($content_type->name) ==strtolower('posts')){ 
							$taxonomy='category';
						}else{
							$taxonomy =  $content_type->slugs[0];
						}
					}else{
						if($content_type->name =='post' || strtolower($content_type->name) ==strtolower('posts')){ 
							$taxonomy='category';
						}else{
							$taxonomy =  $content_type->slugs[0];
						}
					}
				}
				$taxonomies = get_object_taxonomies( (object) array( 'post_type' => $post_type,'public'   => true, '_builtin' => true ));
				if($post_type!='post'){	
						$taxonomy=$taxonomies[0];
				}else
					$taxonomy='category';
					
				$categories = get_terms($taxonomy, 'orderby=count&hide_empty=0');				
			?>
		   <div class="form_row clearfix">
			   <label><?php _e('Category','supreme');?></label>
				<select name="category">
					<option value=""><?php _e("Select Category",'supreme');?></value>
					<?php foreach($categories as $cat_informs){?>
							<option value="<?php echo $cat_informs->term_id;?>"><?php echo $cat_informs->name;?></value>
					<?php }?>		
				</select>
			   <div class="clearfix"></div>
		   </div>
		   <script type="text/javascript">				
				jQuery(function(){
				var pickerOpts = {
					showOn: "both",
					buttonImage: "<?php echo TEMPL_PLUGIN_URL; ?>css/datepicker/images/cal.png",
					buttonText: "Show Datepicker"
				};	
				jQuery("#todate").datepicker(pickerOpts);
				jQuery("#frmdate").datepicker(pickerOpts);
			});
			</script>
		   <div class="form_row clearfix">
			   <label><?php _e('Date','supreme');?></label>
			   <input name="todate" id="todate" type="text" size="25" PLACEHOLDER="<?php _e('Start Date','supreme'); ?>"  class="clearfix" /><br />
               <input name="frmdate" id="frmdate" type="text" size="25" PLACEHOLDER="<?php _e('End Date','supreme'); ?>"   class="clearfix"  />
           </div>	
            <div class="form_row clearfix">
			   <label><?php _e(AUTHOR_TEXT,'supreme');?></label>
			   <input name="articleauthor" type="text" PLACEHOLDER="<?php _e('Author','supreme'); ?>" />
			   <label class="adv_author">
               <?php _e('Exact author','supreme');?>
			   <input name="exactyes" type="checkbox" value="1" class="checkbox" />	
			   </label>
            </div>
			<?php 
			if(function_exists('get_search_post_fields_templ_plugin')){
				$default_custom_metaboxes = get_search_post_fields_templ_plugin($post_type,'custom_fields','post');
				display_search_custom_post_field_plugin($default_custom_metaboxes,'custom_fields','post');//displaty custom fields html.
				}
			?>
			<input type="hidden" name="search_template" value="1"/>
            <!--<input class="adv_input" name="adv_search" id="adv_search" type="hidden" value="1"  />-->
		    <input class="adv_input" name="post_type" id="post_type" type="hidden" value="<?php echo $post_type; ?>"  />
           <input type="submit" name="submit" value="<?php _e('Search','supreme'); ?>" class="adv_submit" />              
        </form>
</div> 
<script type="text/javascript" >
function sformcheck(){
	jQuery.noConflict();
	var search = jQuery('#adv_s').val();
	if(search==""){
		jQuery('#search_error').html('<?php _e('Please enter word you want to search','supreme'); ?>');
		return false;
	}else{
		search.bind(change,function(){jQuery('#search_error').html('');});
		jQuery('#search_error').html('');
		return true;
	}
}
</script>        
<?php get_footer(); ?>