<?php
/**
 * Loop Error Template
 *
 * Displays an error message when no posts are found.
 *
 * @package supreme
 * @subpackage Template
 */
?>

	<li id="post-0" class="<?php hybrid_entry_class(); ?>">

		<div class="entry-summary">

			<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'supreme' ); ?></p>
			<?php get_search_form(); ?>
	   
	 	      <?php 
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
          <?php endwhile; ?>
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
          <?php echo $WPListCategories; ?>
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
          <?php endwhile; ?>
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
          <?php echo $WPListCustomCategories;?>
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
          <?php echo $WPListArchives;?>
        </ul>
      </div>
	  <?php } ?>
		</div><!-- .entry-summary -->

	</li><!-- .hentry .error -->