<?php
/**
 * Comments Template
 *
 * Lists comments and calls the comment form.  Individual comments have their own templates.  The 
 * hierarchy for these templates is $comment_type.php, comment.php.
 *
 * @package supreme
 * @subpackage Template
 */

/* Kill the page if trying to access this template directly. */
if ( 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) )
	die( __( 'Please do not load this page directly. Thanks!', 'supreme' ) );

/* If a post password is required or no comments are given and comments/pings are closed, return. */
if ( post_password_required() || ( !have_comments() && !comments_open() && !pings_open() ) )
	return;
?>

<div id="comments-template">

	<div class="comments-wrap">

		<div id="comments">

			<?php if ( have_comments() ) : ?>

				<h3 id="comments-number" class="comments-header"><?php comments_number( __( 'No Comments', 'supreme' ), __( 'One Comment', 'supreme' ), __( '% Comments', 'supreme' ) ); ?></h3>

				<?php do_atomic( 'before_comment_list' );// supreme_before_comment_list ?>
				
				<?php if ( get_option( 'page_comments' ) ) : ?>
					<div class="comment-navigation comment-pagination">
						<span class="page-numbers"><?php printf( __( 'Page %1$s of %2$s', 'supreme' ), ( get_query_var( 'cpage' ) ? absint( get_query_var( 'cpage' ) ) : 1 ), get_comment_pages_count() ); ?></span>
						<?php paginate_comments_links(); ?>
					</div><!-- .comment-navigation -->
				<?php endif; ?>

				<ol class="comment-list">
					<?php wp_list_comments( hybrid_list_comments_args() ); ?>
				</ol><!-- .comment-list -->

				<?php do_atomic( 'after_comment_list' ); // supreme_after_comment_list ?>
				
			<?php endif; ?>

			<?php if ( pings_open() && !comments_open() ) : ?>

				<p class="comments-closed pings-open">
					<?php printf( __( 'Comments are closed, but <a href="%1$s" title="Trackback URL for this post">trackbacks</a> and pingbacks are open.', 'supreme' ), get_trackback_url() ); ?>
				</p><!-- .comments-closed .pings-open -->

			<?php elseif ( !comments_open() ) : ?>

				<p class="comments-closed">
					<?php _e( 'Comments are closed.', 'supreme' ); ?>
				</p><!-- .comments-closed -->

			<?php endif; ?>

		</div><!-- #comments -->

		<?php $comment_args = array( 'fields' => apply_filters( 'comment_form_default_fields', array(
						'author' => '<div class="form_row">' .
									'<input id="author" name="author" type="text" value="' .
									esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' PLACEHOLDER="'.__('Your name','supreme').'"/>' .
									( $req ? ' <span class="required">*</span>' : '' ) .
									'</div><!-- #form-section-author .form-section -->',
						'email'  => '<div class="form_row">' .
									'<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' PLACEHOLDER="'.__('Email Address','supreme').'"/>' .
									( $req ? ' <span class="required">*</span>' : '' ) .
							'</div><!-- #form-section-email .form-section -->',
						'url'    => '<div class="form_row">' .
									'<input id="url" name="url" type="text" value="' . esc_attr(  $commenter['comment_author_url'] ) . '" size="30"' . $aria_url . ' PLACEHOLDER="'.__('Website','supreme').'"/>'.'</div>')),
						'comment_field' => '<div class="form_row">' .
									'<textarea id="comments" name="comment" cols="45" rows="8" aria-required="true" PLACEHOLDER="'.__('Comments','supreme').'"></textarea>' .
									( $req ? ' <span class="required">*</span>' : '' ) .
									'</div><!-- #form-section-comment .form-section -->',
						'comment_notes_after' => '',
						'title_reply' => __( 'Add a comment', 'supreme' ),
					);
					if(get_option('default_comment_status') =='open'){
						comment_form($comment_args); } // Loads the comment form.  ?>

	</div><!-- .comments-wrap -->

</div><!-- #comments-template -->