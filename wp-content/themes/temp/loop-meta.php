<?php
/**
 * Loop Meta Template
 *
 * Displays information at the top of the page about archive and search results when viewing those pages.  
 * This is not shown on the front page or singular views.
 *
 * @package supreme
 * @subpackage Template
 */
?>

	<?php if ( is_home() && !is_front_page() ) : ?>

		<div class="loop-meta">

			<h1 class="loop-title"><?php echo get_post_field( 'post_title', get_queried_object_id() ); ?></h1>

			<div class="loop-description">
				<?php echo apply_filters( 'the_excerpt', get_post_field( 'post_excerpt', get_queried_object_id() ) ); ?>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php elseif ( is_category() ) : ?>

		<div class="loop-meta">

			<h1 class="loop-title"><?php single_cat_title(); ?></h1>

			<div class="loop-description">
				<?php echo category_description(); ?>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php elseif ( is_tag() ) : ?>

		<div class="loop-meta">

			<h1 class="loop-title"><?php single_tag_title(); ?></h1>

			<div class="loop-description">
				<?php echo tag_description(); ?>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php elseif ( is_tax() ) : ?>

		<div class="loop-meta">

			<h1 class="loop-title"><?php single_term_title(); ?></h1>

			<div class="loop-description">
				<?php echo term_description( '', get_query_var( 'taxonomy' ) ); ?>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php elseif ( is_author() ) : ?>

		<?php $user_id = get_query_var( 'author' ); ?>

		<div id="hcard-<?php the_author_meta( 'user_nicename', $user_id ); ?>" class="loop-meta vcard">

			<h1 class="loop-title fn n"><?php the_author_meta( 'display_name', $user_id ); ?></h1>

			<div class="loop-description">
				<?php $desc = get_the_author_meta( 'description', $user_id ); ?>

				<?php if ( !empty( $desc ) ) { ?>
					<?php echo get_avatar( get_the_author_meta( 'user_email', $user_id ), '60', '', get_the_author_meta( 'display_name', $user_id ) ); ?>

					<p class="user-bio">
						<?php echo $desc; ?>
					</p><!-- .user-bio -->
				<?php } ?>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php elseif ( is_search() ) : ?>

		<div class="loop-meta">

			<h1 class="loop-title"><?php echo esc_attr( get_search_query() ); ?></h1>

			<div class="loop-description">
				<p>                    
				<?php 											
					if(isset($_REQUEST['date']) && ($_REQUEST['date']!="When?" && $_REQUEST['date']!="" ))
						$date=' Date "'.$_REQUEST['date'].'"';
					if(isset($_REQUEST['location']) && ($_REQUEST['location']!="Where?" && $_REQUEST['location']!="" ))
						$location=' near "'.$_REQUEST['location'].'"';
					if(trim(get_search_query())=='')
						printf( __( 'You are browsing the search results for  %s %s', T_DOMAIN ), esc_attr($location),esc_attr($date) ); 	
					else
						printf( __( 'You are browsing the search results for "%s"  %s  %s', T_DOMAIN ), esc_attr( get_search_query()),esc_attr($location),esc_attr($date) ); 	
				?>
				</p>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php elseif ( is_date() ) : ?>

		<div class="loop-meta">
			<h1 class="loop-title"><?php _e( 'Archives by date', T_DOMAIN ); ?></h1>

			<div class="loop-description">
				<p>
				<?php _e( 'You are browsing the site archives by date.', T_DOMAIN ); ?>
				</p>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php elseif ( function_exists( 'is_post_type_archive' ) && is_post_type_archive() ) : ?>

		<?php $post_type = get_post_type_object( get_query_var( 'post_type' ) ); ?>

		<div class="loop-meta">

			<h1 class="loop-title"><?php post_type_archive_title(); ?></h1>

			<div class="loop-description">
				<?php if ( !empty( $post_type->description ) ) echo "<p>{$post_type->description}</p>"; ?>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php elseif ( is_archive() ) : ?>

		<div class="loop-meta">

			<h1 class="loop-title"><?php _e( 'Archives', T_DOMAIN ); ?></h1>

			<div class="loop-description">
				<p>
				<?php _e( 'You are browsing the site archives.', T_DOMAIN ); ?>
				</p>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php endif; ?>