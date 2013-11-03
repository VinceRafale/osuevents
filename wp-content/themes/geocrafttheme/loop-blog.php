<?php
$limit = get_option('posts_per_page');
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
query_posts("post_type=post&showposts=$limit&paged=$paged");
$wp_query->is_archive = true;
$wp_query->is_home = false;
if (have_posts()) :
    while (have_posts()): the_post();
        ?>
        <!--Start Featured Post-->
        <div <?php post_class('featured_post post'); ?> id="post-<?php the_ID(); ?>">
            <!--Start Featured thumb-->
            <!--End Featured thumb-->
            <div class="f_post_content">
                <h1 class="f_post_title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
                <div class="post_meta">
                    <ul class="meta-nav">
                        <li class="author"><?php echo 'By '; ?><?php printf('%s', the_author_posts_link()); ?></li>
                        <li class="date"><?php the_time('M-j-Y') ?></li>
                        <li class="category"><?php the_category(', '); ?></li>
                        <li class="comment"><?php comments_popup_link('0 Comments.', '1 Comment.', '% Comments.'); ?></li>
                    </ul>
                </div>
				<div class="featured_thumb blog">
                <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                    <?php inkthemes_get_thumbnail(205, 143); ?>
                <?php } else { ?>
                    <?php inkthemes_get_image(205, 143); ?> 
                    <?php
                }
                ?>
				</div>
                <?php the_excerpt(); ?>
            </div>
        </div>
        <!--End Featured Post-->
        <?php
    endwhile;
     inkthemes_pagination();
     wp_reset_query();
else:
    ?>
    <div class="featured_post featured">
        <p class="place"><?php echo NO_POST_FOUND; ?></p>
    </div>
<?php
endif;
?>