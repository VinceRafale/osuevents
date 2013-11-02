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

get_header(); // Loads the header.php template. 
global $post;
$single_post = $post;
?>

	<?php do_atomic( 'before_content' ); // supreme_before_content ?>
	
	<?php if ( current_theme_supports( 'breadcrumb-trail' ) && hybrid_get_setting('supreme_show_breadcrumb')) breadcrumb_trail( array( 'separator' => '&raquo;' ) ); ?>


	<div id="content" class="error_404">

		<?php do_atomic( 'open_content' ); // supreme_open_content ?>

		<div class="hfeed">

			<div id="post-0" class="<?php hybrid_entry_class(); ?>">

				<h1 class="error-404-title entry-title"><?php _e( 'Not Found', 'supreme' ); ?></h1>

				<h4><?php _e("Oh, the page you’re looking for can’t be found",'supreme'); ?></h4>
            
            	<div class="entry-content">
                
                	<p>
					<?php printf( __( 'You tried going to %1$s, and it doesn\'t exist. All is not lost! You can search for what you\'re looking for.', 'supreme' ), '<code>' . home_url( esc_url( $_SERVER['REQUEST_URI'] ) ) . '</code>' ); ?>
					</p>

					<?php get_search_form(); // Loads the searchform.php template. ?>

				</div><!-- .entry-content -->
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
				<?php } 
				$archive_query = new WP_Query('showposts=60&post_type=post'); 
				if($archive_query) { ?>
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
					  <?php endwhile;wp_reset_query(); ?>
					</ul>
				  </div>
				 <?php } ?>
				  <!--/arclist -->
				  <!--/arclist -->
				  <div class="arclist">
					<div class="title-container">
						<h2 class="title_green"><span><?php _e('Post Categories','supreme');?></span></h2>
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
						$archive_query = new WP_Query('showposts=60&post_type='.$post_type);
						if( count(@$archive_query->posts) > 0 ){}
							$PostTypeObject = get_post_type_object($post_type);
							$PostTypeName = $PostTypeObject->labels->name;
				?>
						   <div class="arclist">
								<div class="title-container">
									<h2><?php  echo sprintf(__('%s','supreme'), ucfirst($PostTypeName));?></h2>
								</div>
								<ul>
							  <?php 
								while ($archive_query->have_posts()) : $archive_query->the_post(); ?>
							  <li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
								<?php the_title(); ?>
								</a> <span class="arclist_comment">
								<?php comments_number(__('0 comment','supreme'), __('1 comment','supreme'),__('% comments','supreme')); ?>
								</span></li>
							  <?php endwhile;wp_reset_query(); ?>
							</ul>
						  </div>
       <!--/arclist -->
			  <div class="arclist">
				<div class="title-container">
					<h2 class="title_green"><span><?php echo sprintf(__('%s Categories','supreme'), ucfirst($PostTypeName));?></span></h2>
					<div class="clearfix"></div>
				</div>
				<ul>
				  <?php wp_list_categories('title_li=&hierarchical=0&show_count=0&taxonomy='.$taxonomies[0])  ?>
				</ul>
			  </div>
      
	  <?php endif;?>
	<?php endforeach;wp_reset_query();?>      
      
        
        
      
			  <div class="arclist">
				<div class="title-container">
					<h2 class="title_green"><span><?php _e('Archives','supreme');?></span></h2>
					<div class="clearfix"></div>
				</div>
				<ul>
				  <?php wp_get_archives('type=monthly'); ?>
				</ul>
			  </div>

			</div><!-- .hentry -->

		</div><!-- .hfeed -->

		<?php $post = $single_post; do_atomic( 'close_content' ); // supreme_close_content ?>

	</div><!-- #content -->

	<?php do_atomic( 'after_content' ); // supreme_after_content ?>

<?php get_footer(); // Loads the footer.php template. ?>