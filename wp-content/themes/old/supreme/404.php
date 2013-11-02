<?php
/**
 * 404 Template
 *
 * The 404 template is used when a reader visits an invalid URL on your site. By default, the template will 
 * display a generic message.
 *
 * @package supreme
 * @subpackage Template
 * @link http://codex.wordpress.org/Creating_an_Error_404_Page
 */

@header( 'HTTP/1.1 404 Not found', true, 404 );

get_header(); // Loads the header.php template. ?>

	<?php do_atomic( 'before_content' ); // supreme_before_content ?>
	
	<?php if ( current_theme_supports( 'breadcrumb-trail' ) ) breadcrumb_trail( array( 'separator' => '&raquo;' ) ); ?>


	<div id="content" class="error_404">

		<?php do_atomic( 'open_content' ); // supreme_open_content ?>

		<div class="hfeed">

			<div id="post-0" class="<?php hybrid_entry_class(); ?>">

				<h1 class="error-404-title entry-title"><?php _e( 'Not Found', 'supreme' ); ?></h1>

				<h4>Oh, the page you’re looking for can’t be found</h4>
            
            	<div class="entry-content">
                
                	<p>
					<?php printf( __( 'You tried going to %1$s, and it doesn\'t exist. All is not lost! You can search for what you\'re looking for.', 'supreme' ), '<code>' . home_url( esc_url( $_SERVER['REQUEST_URI'] ) ) . '</code>' ); ?>
					</p>

					<?php get_search_form(); // Loads the searchform.php template. ?>

				</div><!-- .entry-content -->
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
							<h2><?php _e(ucfirst($post_type),'supreme');?></h2>
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

			</div><!-- .hentry -->

		</div><!-- .hfeed -->

		<?php do_atomic( 'close_content' ); // supreme_close_content ?>

	</div><!-- #content -->

	<?php do_atomic( 'after_content' ); // supreme_after_content ?>

<?php get_footer(); // Loads the footer.php template. ?>