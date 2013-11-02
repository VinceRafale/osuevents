<?php
/*
Template Name: Template - Sitemap
*/
get_header(); // Loads the header.php template. ?>

<?php do_atomic( 'before_content' ); // rainbow_before_content ?>

<?php if ( current_theme_supports( 'breadcrumb-trail' ) && !is_home() && hybrid_get_setting('supreme_show_breadcrumb')) breadcrumb_trail( array( 'separator' => '&raquo;' ) ); ?>
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
    <div class="entry-content">
		<?php 
          $content = $post->post_content;
          $content = apply_filters('the_content', $content);	
          echo $content;
          ?>  
     </div><!-- .entry-content -->
	 <?php 
		$args = array('title_li' => '', 'echo' => 0 );
		$WPLisPages = new WP_Query('showposts=60&post_type=page');
		if( count(@$WPLisPages->posts) > 0 ){
	 ?>
      <div class="arclist">
        <div class="title-container">
        	<h2 class="title_green"><span><?php _e('Pages','supreme');?></span></h2>
			<div class="clearfix"></div>
        </div>

        <ul>
          <?php 
		  while ($WPLisPages->have_posts()) : $WPLisPages->the_post(); ?>
			<li>
            	<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
					<?php the_title(); ?>
				</a>
            </li>
		  <?php endwhile;wp_reset_query(); ?>
        </ul>
      </div>
	  <?php } ?>
		<?php 
			$archive_query = new WP_Query('showposts=60&post_type=post');
			if( count(@$archive_query->posts) > 0 ){
		?>	
			  <div class="arclist">
				<div class="title-container">
					<h2 class="title_green"><span><?php _e('Posts','supreme');?></span></h2>
					<div class="clearfix"></div>
				</div>
				<ul>
				  <?php 
						while ($archive_query->have_posts()) : $archive_query->the_post(); ?>
					  <li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
						<?php the_title(); ?>
						</a> <span class="arclist_comment">
						<?php comments_number(__('0 comment','supreme'), __('1 comment','supreme'),__('% comments','supreme')); ?>
						</span></li>
					  <?php 
						endwhile;wp_reset_query(); 
				  ?>
				</ul>
			  </div>
		<?php } ?>	  
	  <!--/arclist -->
      <!--/arclist -->
	  <?php 
		$WPListCategories = wp_list_categories('title_li=&hierarchical=0&show_count=0&taxonomy=category&echo=0');
		if(($WPListCategories) && $WPListCategories!="No categories" && $WPListCategories!="<li>No categories</li>"){
	  ?>
      <div class="arclist">
        <div class="title-container">
        	<h2 class="title_green"><span><?php _e('Post Categories','supreme');?></span></h2>
        	<div class="clearfix"></div>
        </div>
        <ul>
          <?php 
			echo $WPListCategories;
		  ?>
        </ul>
      </div>	     
	<?php 
		}
		$post_types=get_post_types();
		foreach($post_types as $post_type):		
			if($post_type!='post' && $post_type!='page' && $post_type!="attachment" && $post_type!="revision" && $post_type!="nav_menu_item"):
			$taxonomies = get_object_taxonomies( (object) array( 'post_type' => $post_type,'public'   => true, '_builtin' => true ));	
			$archive_query = new WP_Query('showposts=60&post_type='.$post_type);
			if( count(@$archive_query->posts) > 0 ){
				$PostTypeObject = get_post_type_object($post_type);
				$PostTypeName = $PostTypeObject->labels->name;
				
	?>
	   <div class="arclist">
            <div class="title-container">
                <h2 class="title_green"><span><?php echo sprintf(__('%s','supreme'), ucfirst($PostTypeName));?></span></h2>
                <div class="clearfix"></div>
            </div>
            <ul>
          <?php 
            while ($archive_query->have_posts()) : $archive_query->the_post(); ?>
          <li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
            <?php the_title(); ?>
            </a> <span class="arclist_comment">
            <?php comments_number(__('0 comment','supreme'), __('1 comment','supreme'),__('% comments','supreme')); ?>
            </span></li>
          <?php endwhile; wp_reset_query(); ?>
        </ul>
      </div>
	  
	  <?php } ?>
       <!--/arclist -->
	   <?php 
		$WPListCustomCategories = wp_list_categories('title_li=&hierarchical=0&show_count=0&echo=0&taxonomy='.$taxonomies[0]);
		if(($WPListCustomCategories) && $WPListCustomCategories!="No categories" && $WPListCustomCategories!="<li>No categories</li>"){
	  ?> 
      <div class="arclist">
        <div class="title-container">
        	<h2 class="title_green"><span><?php echo sprintf(__('%s Categories','supreme'), ucfirst($PostTypeName));?></span></h2>
        	<div class="clearfix"></div>
        </div>
        <ul>
          <?php echo $WPListCustomCategories; ?>
        </ul>
      </div>
      <?php } ?>
	  <?php endif;?>
	<?php endforeach;?>      

	<?php 
		$WPListArchives = wp_get_archives('type=monthly&echo=0');
		if(($WPListArchives)){
	?> 
	<div class="arclist">
        <div class="title-container">
        	<h2 class="title_green"><span><?php _e('Archives','supreme');?></span></h2>
			<div class="clearfix"></div>
        </div>
        <ul>
          <?php echo $WPListArchives; ?>
        </ul>
	</div>
	<?php } ?>  
      <!--/arclist -->
    </div>
<?php get_sidebar( 'after-content' ); // Loads the sidebar-after-content.php template. ?>
		
	</div><!-- .hfeed -->
	
	<?php do_atomic( 'close_content' ); // rainbow_close_content ?>
	
	<?php //get_template_part( 'loop-nav' ); // Loads the loop-nav.php template. ?>

</div><!-- #content -->

<?php do_atomic( 'after_content' ); // rainbow_after_content ?>

<?php get_footer(); // Loads the footer.php template. ?>