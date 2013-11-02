<?php
/*
Template Name: Template - Sitemap
*/
get_header(); // Loads the header.php template. ?>

<?php do_atomic( 'before_content' ); // rainbow_before_content ?>

<?php if ( current_theme_supports( 'breadcrumb-trail' ) && !is_home()) breadcrumb_trail( array( 'separator' => '&raquo;' ) ); ?>
<div class="content" id="content">
	<?php do_atomic( 'open_content' ); // rainbow_open_content ?>
	<div class="hfeed">
		<?php 
			get_template_part( 'loop-meta' ); // Loads the loop-meta.php template.
			get_sidebar( 'before-content' ); // Loads the sidebar-before-content.php template. 
		?>
<!--  CONTENT AREA START -->
<div class="entry sitemap">
    <h1 class="entry-title"><?php the_title(); ?></h1>
    <?php the_content(); ?>
      <div class="arclist">
        <div class="title-container">
        	<h2 class="title_green"><span><?php _e('Pages','supreme');?></span></h2>
			<div class="clearfix"></div>
        </div>

        <ul>
          <?php wp_list_pages('title_li='); ?>
        </ul>
      </div>
	  <div class="arclist">
        <div class="title-container">
        	<h2 class="title_green"><span><?php _e('Posts','supreme');?></span></h2>
			<div class="clearfix"></div>
        </div>
        
        <ul>
          <?php $archive_query = new WP_Query('showposts=60&post_type=post');
            while ($archive_query->have_posts()) : $archive_query->the_post(); ?>
          <li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
            <?php the_title(); ?>
            </a> <span class="arclist_comment">
            <?php comments_number(__('0 comment','templatic'), __('1 comment','templatic'),__('% comments','templatic')); ?>
            </span></li>
          <?php endwhile; ?>
        </ul>
      </div>
	  <!--/arclist -->
      <!--/arclist -->
      <div class="arclist">
        <div class="title-container">
        	<h2 class="title_green"><span><?php _e('Post Categories','rainbow');?></span></h2>
        	<div class="clearfix"></div>
        </div>
        <ul>
          <?php wp_list_categories('title_li=&hierarchical=0&show_count=0&taxonomy=category')  ?>
        </ul>
      </div>	     
	<?php 
		$post_types=get_post_types();
		foreach($post_types as $post_type):		
			if($post_type!='post' && $post_type!='page' && $post_type!="attachment" && $post_type!="revision" && $post_type!="nav_menu_item"):
			$taxonomies = get_object_taxonomies( (object) array( 'post_type' => $post_type,'public'   => true, '_builtin' => true ));	
	?>
	   <div class="arclist">
            <div class="title-container">
                <h2 class="title_green"><span><?php _e(ucfirst($post_type),'supreme');?></span></h2>
                <div class="clearfix"></div>
            </div>
            <ul>
          <?php $archive_query = new WP_Query('showposts=60&post_type='.$post_type);
            while ($archive_query->have_posts()) : $archive_query->the_post(); ?>
          <li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
            <?php the_title(); ?>
            </a> <span class="arclist_comment">
            <?php comments_number(__('0 comment','templatic'), __('1 comment','templatic'),__('% comments','templatic')); ?>
            </span></li>
          <?php endwhile; ?>
        </ul>
      </div>
       <!--/arclist -->
      <div class="arclist">
        <div class="title-container">
        	<h2 class="title_green"><span><?php _e(ucfirst($post_type).' Categories','rainbow');?></span></h2>
        	<div class="clearfix"></div>
        </div>
        <ul>
          <?php wp_list_categories('title_li=&hierarchical=0&show_count=0&taxonomy='.$taxonomies[0])  ?>
        </ul>
      </div>
      
	  <?php endif;?>
	<?php endforeach;?>      
      
        
        
      
	  <div class="arclist">
        <div class="title-container">
        	<h2 class="title_green"><span><?php _e('Archives','rainbow');?></span></h2>
			<div class="clearfix"></div>
        </div>
        <ul>
          <?php wp_get_archives('type=monthly'); ?>
        </ul>
      </div>
      <!--/arclist -->
    </div>
<?php get_sidebar( 'after-content' ); // Loads the sidebar-after-content.php template. ?>
		
	</div><!-- .hfeed -->
	
	<?php do_atomic( 'close_content' ); // rainbow_close_content ?>
	
	<?php //get_template_part( 'loop-nav' ); // Loads the loop-nav.php template. ?>

</div><!-- #content -->

<?php do_atomic( 'after_content' ); // rainbow_after_content ?>

<?php get_footer(); // Loads the footer.php template. ?>