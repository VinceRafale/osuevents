<?php get_header(); ?>
<?php do_action('templ_before_single_container_breadcrumb');
/*Add action for display the bradcrumb in between header and container. */?>

	<div id="content" class="contentarea">

		<?php do_action('templ_inside_single_container_breadcrumb');/*Add action for display the bradcrumn  inside the container. */?>

			<?php do_action('templ_before_categories_title');?>
			<h1><?php single_cat_title(); ?></h1>
			<?php do_action('templ_after_categories_title');?>
			<p><?php echo category_description(); ?></p>
		<?php
		global $current_term,$wpdb,$wp_query;
		$post_type = get_post_type();
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
			}
		}
		$term =	$wp_query->queried_object;		
		$taxonomy_slug=$term->taxonomy;
		$post_meta_info = listing_fields_collection();
		$htmlvar_name='';
		if($post_meta_info)
		{
			while ($post_meta_info->have_posts()) : $post_meta_info->the_post();
				$ctype = get_post_meta($post->ID,'ctype',true);
				$post_name=get_post_meta($post->ID.'htmlvar_name',true);
				$htmlvar_name[$post_name] = $ctype;
				$pos_title[] = $post->post_title;
			endwhile;
		}
		?>
		<div id="loop_taxonomy" class="indexlist">
		<?php
		if (have_posts()) : while (have_posts()) : the_post(); ?>			
				<div class="post">               
					<?php do_action('tmpl_category_page_image');?>
					<div class="entry"> 
						<?php do_action('templ_before_post_title');?>               	
						<h2 id="post-<?php the_ID(); ?>" class="entry-title">
							<a href="<?php the_permalink();?>"><span><?php the_title(); ?></span></a>
						</h2>
						<?php do_action('templ_after_post_title');?>     
						<?php do_action('templ_post_info'); /*add action for display the post info */?>
						<?php do_action('templ_before_post_content');?>
						<?php 
							$TemplaticSettings = get_option('supreme_theme_settings');
							if(isset($TemplaticSettings['supreme_archive_display_excerpt']) && $TemplaticSettings['supreme_archive_display_excerpt']==1){	
								if(function_exists('tevolution_excerpt_length')){
									$length = tevolution_excerpt_length();
									if(function_exists('print_excerpt')){
										echo print_excerpt($length);
									}
								}
							}else{
								the_content(); 
							}
						?>
						<?php do_action('templ_after_post_content');?>
						<!-- Show custom fields where show on listing = yes -->
                               <?php do_action('templ_listing_custom_field',$htmlvar_name,$pos_title);/*add action for display the listing page custom field */?>
                               <?php the_taxonomies(array('before'=>'<p class="bottom_line"><span class="i_category">','sep'=>'</span>&nbsp;&nbsp;<span class="i_tag">','after'=>'</span></p>'));?> 
				 
				   </div>
					   
			   </div>    
		<?php  endwhile;  else: 
		echo "<p class='nodata_msg'>";
		_e('Sorry, no posts matched your criteria.',DOMAIN);
		echo "</p>";
		endif; 
		?>
		</div>
		<?php
		/* pagination */
		if ( have_posts() ) { ?>
			<div id="listpagi">
				<div class="pagination pagination-position">
					 <?php if(function_exists('pagenavi_plugin')) { pagenavi_plugin(); } ?>
				</div>
			</div>   
			<?php }?>
	</div>
<?php do_action( 'templ_after_content' ); // supreme_after_content 

$display = get_option('supreme_theme_settings');//[supreme_global_layout]
$layout = $display['supreme_global_layout'];
if(!$layout){
	$layout ='2c';
}
if ( is_active_sidebar( $taxonomy_slug.'_tag_listing_sidebar' ) && $layout !='layout_1c' ) : 
do_atomic( 'before_sidebar_primary' ); // supreme_before_sidebar_primary ?>

	<div id="sidebar-primary" class="sidebar">

		<?php do_atomic( 'open_sidebar_primary' ); // supreme_open_sidebar_primary ?>

		<?php dynamic_sidebar($taxonomy_slug.'_tag_listing_sidebar'); ?>

		<?php do_atomic( 'close_sidebar_primary' ); // supreme_close_sidebar_primary ?>

	</div><!-- #sidebar-primary -->

	<?php do_atomic( 'after_sidebar_primary' ); // supreme_after_sidebar_primary ?>

<?php endif;  get_footer(); ?>