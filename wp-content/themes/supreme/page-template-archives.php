<?php
/*
Template Name: Template - Archives
*/
?>
<?php 
	get_header(); 
	do_atomic( 'before_content' ); // supreme_before_content
	if ( current_theme_supports( 'breadcrumb-trail' ) && hybrid_get_setting('supreme_show_breadcrumb')) breadcrumb_trail( array( 'separator' => '&raquo;' ) ); 
?>
<!--  CONTENT AREA START -->

<div id="content" class="multiple">
  <?php do_atomic( 'open_content' ); // supreme_open_content ?>
  <div class="hfeed">
    <h1 class="entry-title"><?php the_title(); ?></h1>
    <div class="entry-content">
		<?php 
          $content = $post->post_content;
          $content = apply_filters('the_content', $content);	
          echo $content;
          ?>  
     </div><!-- .entry-content -->
    
	<?php 
		global $post;
		$archives_post=$post;
		$templatic_catelog_post_type = get_post_meta($post->ID,'template_post_type',true);
		if(isset($templatic_catelog_post_type) && $templatic_catelog_post_type!=""){
			$templatic_catelog_post_type = $templatic_catelog_post_type;
		}else{
			$templatic_catelog_post_type = "post";
		}
		
		$years = $wpdb->get_results("SELECT DISTINCT MONTH(post_date) AS month, YEAR(post_date) as year
			FROM $wpdb->posts WHERE post_status = 'publish' and post_date <= now( ) and 
			post_type = '$templatic_catelog_post_type' ORDER BY post_date DESC");
			if($years)
			{
				foreach($years as $years_obj)
				{
					$year = $years_obj->year;	
					$month = $years_obj->month; ?>
				<?php if($templatic_catelog_post_type != '') {
						query_posts("post_type=$templatic_catelog_post_type&showposts=1000&year=$year&monthnum=$month");
					  } else {
						query_posts("post_type='product'&showposts=1000&year=$year&monthnum=$month");
					  }?>
         		<div class="arclist">  
               <div class="arclist_head">
                   <h3><?php echo $year; ?> <?php echo  date('F', mktime(0,0,0,$month,1)); ?></h3>
                </div>
               <ul>
	          <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                   <li>
                        <a href="<?php the_permalink() ?>">
                            <?php the_title(); ?>
                        </a><br />
                        <span class="arclist_date">  
						<?php _e('&nbsp;by&nbsp;','supreme');?>
                        <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" title="Posts by <?php the_author(); ?>"><?php the_author(); ?></a>
                        <?php _e('&nbsp;on&nbsp;','supreme');?> 
                        <?php the_time(__('M j, Y','supreme')) ?> // 
						<?php comments_popup_link(__('&nbsp;No Comments&nbsp;','supreme'), __('&nbsp;1 Comment&nbsp;','supreme'), __('% Comments','supreme'), '', __('&nbsp;Comments Closed&nbsp;','supreme')); ?>
                        </span>
                    </li> 
          <?php endwhile; endif; ?>
	          </ul>
            </div>
                <?php
			}
		}
		$post=$archives_post;
	 ?> 
  </div>
  <?php do_atomic( 'close_content' ); // supreme_close_content ?>
</div>
<?php do_atomic( 'after_content' ); // supreme_after_content ?>
<?php get_footer(); ?>
